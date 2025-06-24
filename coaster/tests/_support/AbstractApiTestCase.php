<?php

declare(strict_types=1);

namespace Tests\Support;

use App\Coaster\Domain\Model\Coaster;
use App\Coaster\Domain\Model\Wagon;
use App\Coaster\Domain\Repository\CoasterRepository;
use App\Coaster\Domain\Repository\WagonRepository;
use App\Coaster\Domain\ValueObject\CoasterId;
use App\Coaster\Domain\ValueObject\TimeRange;
use App\Coaster\Domain\ValueObject\WagonId;
use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\FeatureTestTrait;
use DateTimeImmutable;
use Exception;
use Redis;

abstract class AbstractApiTestCase extends CIUnitTestCase
{
    use FeatureTestTrait;

    protected function setUp(): void
    {
        parent::setUp();

        /** @var Redis $redis */
        $redis = service('redis');
        $redis->flushAll();
    }

    /**
     * @param string[] $except
     * @param array<string, string> $data
     */
    public function assertStructure(array $except, array $data): void
    {
        $this->assertSame($except, array_keys($data));
    }

    /**
     * @throws Exception
     */
    protected function createCoaster(
        int $availablePersonnel = 11,
        int $clientsPerDay = 540,
        int $trackLengthInMeters = 2400,
        string $from = '08:00',
        string $to = '16:00',
    ): Coaster {
        /** @var CoasterRepository $repository */
        $repository = service('coasterRepository');
        $entity = Coaster::register(
            $availablePersonnel,
            $clientsPerDay,
            $trackLengthInMeters,
            new TimeRange(new DateTimeImmutable($from), new DateTimeImmutable($to)),
        );
        $repository->save($entity);

        return $entity;
    }

    protected function getCoaster(
        CoasterId $coasterId,
    ): ?Coaster {
        /** @var CoasterRepository $repository */
        $repository = service('coasterRepository');

        return $repository->get($coasterId);
    }

    /**
     * @throws Exception
     */
    protected function createWagon(
        CoasterId $coasterId,
        int $seats = 20,
        float $speedInMetersPerSecond = 1.1,
    ): Wagon {
        /** @var WagonRepository $repository */
        $repository = service('wagonRepository');
        $entity = Wagon::register($coasterId, $seats, $speedInMetersPerSecond);

        $repository->save($entity);

        return $entity;
    }

    protected function getWagon(
        CoasterId $coasterId,
        WagonId $wagonId,
    ): ?Wagon {
        /** @var WagonRepository $repository */
        $repository = service('wagonRepository');

        return $repository->get($coasterId, $wagonId);
    }
}
