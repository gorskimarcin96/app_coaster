<?php

namespace Coaster\Domain\Service;

use App\Coaster\Domain\Model\Coaster;
use App\Coaster\Domain\Model\Wagon;
use App\Coaster\Domain\Service\Manager\PersonnelManager;
use App\Coaster\Domain\Service\Notifier;
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
    public function testWhenHasEqualPersonnel(): void
    {
        $coaster = Coaster::register(
            11,
            540,
            2400,
            new TimeRange(new DateTimeImmutable('08:00'), new DateTimeImmutable('16:00')),
        );
        $wagons = [
            Wagon::register($coaster->id, 20, 1),
            Wagon::register($coaster->id, 20, 1),
            Wagon::register($coaster->id, 20, 1),
            Wagon::register($coaster->id, 20, 1),
            Wagon::register($coaster->id, 20, 1),
        ];

        $notifier = $this->createMock(Notifier::class);
        $notifier->expects($this->never())
            ->method('notify');

        $manager = new PersonnelManager();
        $manager->handle(new CoasterWagons($coaster, $wagons), $notifier);
    }

    /**
     * @throws Exception
     */
    public function testWhenNeedsMorePersonnel(): void
    {
        $coaster = Coaster::register(
            9,
            540,
            2400,
            new TimeRange(new DateTimeImmutable('08:00'), new DateTimeImmutable('16:00')),
        );
        $wagons = [
            Wagon::register($coaster->id, 20, 1),
            Wagon::register($coaster->id, 20, 1),
            Wagon::register($coaster->id, 20, 1),
            Wagon::register($coaster->id, 20, 1),
            Wagon::register($coaster->id, 20, 1),
        ];

        $notifier = $this->createMock(Notifier::class);
        $notifier->expects($this->once())
            ->method('notify')
            ->with(sprintf('The coaster %s needs %s persons.', $coaster->id, 2));

        $manager = new PersonnelManager();
        $manager->handle(new CoasterWagons($coaster, $wagons), $notifier);
    }

    /**
     * @throws Exception
     */
    public function testWhenHasToManyPersonnel(): void
    {
        $coaster = Coaster::register(
            13,
            540,
            2400,
            new TimeRange(new DateTimeImmutable('08:00'), new DateTimeImmutable('16:00')),
        );
        $wagons = [
            Wagon::register($coaster->id, 20, 1),
            Wagon::register($coaster->id, 20, 1),
            Wagon::register($coaster->id, 20, 1),
            Wagon::register($coaster->id, 20, 1),
            Wagon::register($coaster->id, 20, 1),
        ];

        $notifier = $this->createMock(Notifier::class);
        $notifier->expects($this->once())
            ->method('notify')
            ->with(sprintf('The coaster %s has %s persons too many.', $coaster->id, 2));

        $manager = new PersonnelManager();
        $manager->handle(new CoasterWagons($coaster, $wagons), $notifier);
    }
}
