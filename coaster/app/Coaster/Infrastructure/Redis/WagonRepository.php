<?php

namespace App\Coaster\Infrastructure\Redis;

use App\Coaster\Application\Query\GetWagonsHandler\GetWagonsQuery;
use App\Coaster\Domain\Model\Coaster;
use App\Coaster\Domain\Model\Wagon;
use App\Coaster\Domain\Repository\WagonRepository as WagonRepositoryInterface;
use App\Coaster\Domain\ValueObject\CoasterId;
use App\Coaster\Domain\ValueObject\WagonId;
use App\Coaster\Infrastructure\Mapper\WagonMapper;
use Exception;
use JsonException;
use Ramsey\Uuid\Uuid;
use Redis;

final readonly class WagonRepository implements WagonRepositoryInterface
{
    public const KEY = 'wagon';

    /**
     * @throws Exception
     */
    public function get(CoasterId $coasterId, WagonId $wagonId): ?Wagon
    {
        /** @var Redis $redis */
        $redis = service('redis');
        $response = $redis->get($this->key($coasterId, $wagonId));

        return $response ? WagonMapper::toDomain($response) : null;
    }

    /**
     * @return Coaster[]
     * @throws Exception
     */
    public function getByQuery(GetWagonsQuery $query): array
    {
        /** @var Redis $redis */
        $redis = service('redis');
        $data = [];
        $iterator = null;

        do {
            foreach ($redis->scan(
                $iterator,
                $this->key(new CoasterId(Uuid::fromString($query->coasterId)), null),
            ) as $key) {
                $data[] = WagonMapper::toDomain($redis->get($key));
            }
        } while ($iterator > 0);

        return $data;
    }

    /**
     * @throws JsonException
     */
    public function save(Wagon $entity): void
    {
        /** @var Redis $redis */
        $redis = service('redis');
        $redis->set($this->key($entity->coasterId, $entity->id), WagonMapper::toJSON($entity));
    }

    public function delete(Wagon $entity): void
    {
        /** @var Redis $redis */
        $redis = service('redis');
        $redis->del($this->key($entity->coasterId, $entity->id));
    }

    private function key(?CoasterId $coasterId, ?WagonId $wagonId): string
    {
        return sprintf(
            '%s:%s:%s:%s',
            CoasterRepository::KEY,
            $coasterId?->getId()->toString() ?? '*',
            self::KEY,
            $wagonId?->getId()->toString() ?? '*',
        );
    }
}
