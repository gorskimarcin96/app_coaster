<?php

namespace Config;

use App\Coaster\Domain\AsyncRepository\CoasterRepository as DomainCoasterAsyncRepository;
use App\Coaster\Domain\AsyncRepository\WagonRepository as DomainWagonAsyncRepository;
use App\Coaster\Domain\Repository\CoasterRepository as DomainCoasterRepository;
use App\Coaster\Domain\Repository\WagonRepository as DomainWagonRepository;
use App\Coaster\Domain\Service\Notifier\Notifier as DomainNotifier;
use App\Coaster\Infrastructure\AsyncRedis\CoasterRepository as InfrastructureCoasterAsyncRepository;
use App\Coaster\Infrastructure\AsyncRedis\WagonRepository as InfrastructureWagonAsyncRepository;
use App\Coaster\Infrastructure\Redis\CoasterRepository as InfrastructureCoasterRepository;
use App\Coaster\Infrastructure\Redis\WagonRepository as InfrastructureWagonRepository;
use App\Coaster\Infrastructure\Service\Notifier as InfrastructureNotifier;
use Clue\React\Redis\Client;
use Clue\React\Redis\Factory;
use CodeIgniter\Config\BaseService;
use Redis;

final class Services extends BaseService
{
    public static function notifier(bool $getShared = true): DomainNotifier
    {
        return $getShared
            ? self::getSharedInstance('notifier')
            : new InfrastructureNotifier();

    }

    public static function asyncRedis(bool $getShared = true): Client
    {
        return $getShared
            ? self::getSharedInstance('asyncRedis')
            : (new Factory())->createLazyClient($_ENV['REDIS_HOST'] . ':' . $_ENV['REDIS_PORT']);

    }

    public static function coasterAsyncRepository(bool $getShared = true): DomainCoasterAsyncRepository
    {
        return $getShared
            ? self::getSharedInstance('coasterAsyncRepository')
            : new InfrastructureCoasterAsyncRepository();
    }

    public static function wagonAsyncRepository(bool $getShared = true): DomainWagonAsyncRepository
    {
        return $getShared
            ? self::getSharedInstance('wagonAsyncRepository')
            : new InfrastructureWagonAsyncRepository();
    }

    public static function redis(bool $getShared = true): Redis
    {
        if ($getShared) {
            return self::getSharedInstance('redis');
        }

        $redis = new Redis();
        $redis->connect($_ENV['REDIS_HOST'], $_ENV['REDIS_PORT']);
        $redis->select($_ENV['REDIS_DB']);

        return $redis;
    }

    public static function coasterRepository(bool $getShared = true): DomainCoasterRepository
    {
        return $getShared
            ? self::getSharedInstance('coasterRepository')
            : new InfrastructureCoasterRepository();
    }

    public static function wagonRepository(bool $getShared = true): DomainWagonRepository
    {
        return $getShared
            ? self::getSharedInstance('wagonRepository')
            : new InfrastructureWagonRepository();
    }
}
