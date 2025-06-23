<?php

namespace App\Coaster\Infrastructure\Redis;

use App\Coaster\Domain\Model\Coaster;
use App\Coaster\Domain\Query\GetCoastersQuery;
use App\Coaster\Domain\Repository\CoasterRepository as CoasterRepositoryInterface;
use App\Coaster\Domain\ValueObject\CoasterId;
use App\Coaster\Infrastructure\Mapper\CoasterMapper;
use Exception;
use JsonException;
use Redis;

final readonly class CoasterRepository implements CoasterRepositoryInterface
{
    public const KEY = 'coaster';

    /**
     * @throws Exception
     */
    public function get(CoasterId $id): ?Coaster
    {
        /** @var Redis $redis */
        $redis = service('redis');

        $response = $redis->get($this->key($id));

        return $response ? CoasterMapper::toDomain($response) : null;
    }

    /**
     * @return Coaster[]
     * @throws Exception
     */
    public function getByQuery(GetCoastersQuery $query): array
    {
        /** @var Redis $redis */
        $redis = service('redis');
        $data = [];
        $iterator = null;

        do {
            foreach ($redis->scan($iterator, $this->key(null)) as $key) {
                $data[] = CoasterMapper::toDomain($redis->get($key));
            }
        } while ($iterator > 0);

        return $data;
    }

    /**
     * @throws JsonException
     */
    public function save(Coaster $entity): void
    {
        /** @var Redis $redis */
        $redis = service('redis');
        $redis->set($this->key($entity->id), CoasterMapper::toJSON($entity));
    }

    /**
     * @throws JsonException
     */
    public function update(Coaster $entity): void
    {
        /** @var Redis $redis */
        $redis = service('redis');
        $redis->set($this->key($entity->id), CoasterMapper::toJSON($entity));
    }

    private function key(?CoasterId $id): string
    {
        return sprintf('%s:%s', self::KEY, $id?->getId()->toString() ?? '*');
    }
}
