<?php

namespace App\Coaster\Infrastructure\Redis;

use App\Coaster\Application\Query\GetCoastersHandler\GetCoastersQuery;
use App\Coaster\Domain\Model\Coaster;
use App\Coaster\Domain\Repository\CoasterRepository as CoasterRepositoryInterface;
use App\Coaster\Domain\ValueObject\CoasterId;
use App\Coaster\Infrastructure\Mapper\CoasterMapper;
use Exception;
use JsonException;
use Redis;

final readonly class CoasterRepository implements CoasterRepositoryInterface
{
    private const REDIS_KEY = 'coaster';

    /**
     * @throws Exception
     */
    public function get(CoasterId $id): ?Coaster
    {
        /** @var Redis $redis */
        $redis = service('redis');

        $response = $redis->get(self::REDIS_KEY . ':' . $id->getId()->toString());

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
        $pattern = self::REDIS_KEY . ':*';
        $data = [];
        $iterator = null;

        do {
            foreach ($redis->scan($iterator, $pattern) as $key) {
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
        $redis->set(self::REDIS_KEY . ':' . $entity->id, CoasterMapper::toJSON($entity));
    }
}
