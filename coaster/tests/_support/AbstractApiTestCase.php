<?php

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

    public function assertStructure(array $except, array $data): void
    {
        $this->assertSame($except, array_keys($data));
    }

    /**
     * @throws Exception
     */
    protected function createCoaster(
        int $personNumber = 1,
        int $clientNumber = 2,
        int $distanceLength = 10,
        string $from = '01-01-2000',
        string $to = '07-01-2000',
    ): Coaster {
        /** @var CoasterRepository $repository */
        $repository = service('coasterRepository');
        $entity = Coaster::register(
            $personNumber,
            $clientNumber,
            $distanceLength,
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
        int $numberOfPlaces = 10,
        float $speed = 2.2,
    ): Wagon {
        /** @var WagonRepository $repository */
        $repository = service('wagonRepository');
        $entity = Wagon::register($coasterId, $numberOfPlaces, $speed);
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
