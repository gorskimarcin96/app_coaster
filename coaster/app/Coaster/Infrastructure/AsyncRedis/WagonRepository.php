<?php

declare(strict_types=1);

namespace App\Coaster\Infrastructure\AsyncRedis;

use App\Coaster\Domain\AsyncRepository\WagonRepository as WagonRepositoryInterface;
use App\Coaster\Domain\Model\Wagon;
use App\Coaster\Domain\Query\GetWagonsQuery;
use App\Coaster\Infrastructure\Mapper\WagonMapper;
use Clue\React\Redis\Client;
use Exception;
use React\Promise\PromiseInterface;
use function React\Promise\all;

final readonly class WagonRepository implements WagonRepositoryInterface
{
    /**
     * @return PromiseInterface<Wagon[]>
     * @throws Exception
     */
    public function get(GetWagonsQuery $query): PromiseInterface
    {
        /** @var Client $redis */
        $redis = service('asyncRedis');

        return $redis->keys(sprintf("coaster:%s:wagon:*", $query->coasterId))->then(
            fn(array $keys) => all(
                array_map(
                    static fn(string $key) => $redis->get($key)->then(
                        fn(string $json): Wagon => WagonMapper::toDomain($json),
                    ),
                    $keys,
                ),
            ),
        );
    }
}
