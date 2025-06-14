<?php

namespace Config;

use App\Coaster\Domain\Repository\CoasterRepository as DomainCoasterRepository;
use App\Coaster\Infrastructure\Redis\CoasterRepository as InfrastructureCoasterRepository;
use App\Coaster\Domain\Repository\WagonRepository as DomainWagonRepository;
use App\Coaster\Infrastructure\Redis\WagonRepository as InfrastructureWagonRepository;
use CodeIgniter\Config\BaseService;

class Services extends BaseService
{
    public static function redis($getShared = true): \Redis
    {
        if ($getShared) {
            return static::getSharedInstance('redis');
        }

        $redis = new \Redis();
        $redis->connect($_ENV['REDIS_HOST'], $_ENV['REDIS_PORT']);
        $redis->select($_ENV['REDIS_DB']);

        return $redis;
    }

    public static function coasterRepository($getShared = true): DomainCoasterRepository
    {
        return $getShared
            ? static::getSharedInstance('coasterRepository')
            : new InfrastructureCoasterRepository();
    }

    public static function wagonRepository($getShared = true): DomainWagonRepository
    {
        return $getShared
            ? static::getSharedInstance('wagonRepository')
            : new InfrastructureWagonRepository();
    }
}
