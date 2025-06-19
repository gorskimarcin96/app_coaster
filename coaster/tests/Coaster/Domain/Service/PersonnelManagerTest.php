<?php

namespace Coaster\Domain\Service;

use App\Coaster\Domain\Model\Coaster;
use App\Coaster\Domain\Model\Wagon;
use App\Coaster\Domain\Service\Notifier;
use App\Coaster\Domain\Service\PersonnelManager;
use App\Coaster\Domain\ValueObject\CoasterWagons;
use App\Coaster\Domain\ValueObject\TimeRange;
use DateTimeImmutable;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;

final class PersonnelManagerTest extends TestCase
{
    /**
     * @throws Exception
     */
    public function testCheckPersonsInCoasterSystemWhenHasEqualPersonnel(): void
    {
        $coaster = Coaster::register(7, 2, 10, new TimeRange(new DateTimeImmutable(), new DateTimeImmutable()));
        $wagons = [
            Wagon::register($coaster->id, 1, 2.2),
            Wagon::register($coaster->id, 1, 2.2),
            Wagon::register($coaster->id, 1, 2.2),
        ];

        $notifier = $this->createMock(Notifier::class);
        $notifier->expects($this->never())
            ->method('notify');

        $manager = new PersonnelManager();
        $manager->checkPersonsInCoasterSystem([new CoasterWagons($coaster, $wagons)], $notifier);
    }

    /**
     * @throws Exception
     */
    public function testCheckPersonsInCoasterSystemWhenNeedsMorePersonnel(): void
    {
        $coaster = Coaster::register(7, 2, 10, new TimeRange(new DateTimeImmutable(), new DateTimeImmutable()));
        $wagons = [
            Wagon::register($coaster->id, 1, 2.2),
            Wagon::register($coaster->id, 1, 2.2),
            Wagon::register($coaster->id, 1, 2.2),
            Wagon::register($coaster->id, 1, 2.2),
        ];

        $notifier = $this->createMock(Notifier::class);
        $notifier->expects($this->once())
            ->method('notify')
            ->with(sprintf('The coaster %s needs %s persons.', $coaster->id, 2));

        $manager = new PersonnelManager();
        $manager->checkPersonsInCoasterSystem([new CoasterWagons($coaster, $wagons)], $notifier);
    }

    /**
     * @throws Exception
     */
    public function testCheckPersonsInCoasterSystemWhenHasToManyPersonnel(): void
    {
        $coaster = Coaster::register(7, 2, 10, new TimeRange(new DateTimeImmutable(), new DateTimeImmutable()));
        $wagons = [
            Wagon::register($coaster->id, 1, 2.2),
            Wagon::register($coaster->id, 1, 2.2),
        ];

        $notifier = $this->createMock(Notifier::class);
        $notifier->expects($this->once())
            ->method('notify')
            ->with(sprintf('The coaster %s has %s persons too many.', $coaster->id, 2));

        $manager = new PersonnelManager();
        $manager->checkPersonsInCoasterSystem([new CoasterWagons($coaster, $wagons)], $notifier);
    }
}
