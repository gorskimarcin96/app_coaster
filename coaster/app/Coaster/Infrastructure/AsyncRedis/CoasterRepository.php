<?php

namespace App\Coaster\Infrastructure\AsyncRedis;

use App\Coaster\Domain\AsyncRepository\CoasterRepository as CoasterRepositoryInterface;
use App\Coaster\Domain\Model\Coaster;
use App\Coaster\Infrastructure\Mapper\CoasterMapper;
use Clue\React\Redis\Client;
use Exception;
use React\Promise\PromiseInterface;
use function React\Promise\all;

class CoasterRepository implements CoasterRepositoryInterface
{
    /**
     * @return PromiseInterface<Coaster[]>
     * @throws Exception
     */
    public function get(): PromiseInterface
    {
        /** @var Client $redis */
        $redis = service('asyncRedis');

        return $redis->keys('coaster:*')->then(
            fn(array $keys) => all(
                array_map(
                    static fn(string $key) => $redis->get($key)->then(fn(string $json): Coaster => CoasterMapper::toDomain($json)),
                    array_filter($keys, static fn(string $key): string => preg_match('/^coaster:[0-9a-f\-]{36}$/i', $key)),
                ),
            ),
        );
    }
}
