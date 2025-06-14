<?php

namespace Config;

use App\Coaster\Infrastructure\Redis\CoasterRepository;
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

    public static function coasterRepository($getShared = true): CoasterRepository
    {
        return $getShared
            ? static::getSharedInstance('coasterRepository')
            : new CoasterRepository();
    }
}
