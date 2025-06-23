<?php

namespace Coaster\Domain\Service;

use App\Coaster\Domain\Model\Coaster;
use App\Coaster\Domain\Model\Wagon;
use App\Coaster\Domain\Service\Manager\ClientManager;
use App\Coaster\Domain\Service\Notifier;
use App\Coaster\Domain\ValueObject\CoasterWagons;
use App\Coaster\Domain\ValueObject\TimeRange;
use DateTimeImmutable;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;

final class ClientManagerTest extends TestCase
{
    /**
     * @throws Exception|\Exception
     */
    public function testIdealScenario(): void
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

        $manager = new ClientManager();
        $manager->handle(new CoasterWagons($coaster, $wagons), $notifier);
    }

    /**
     * @throws Exception|\Exception
     */
    public function testWhenClientsIsLess(): void
    {
        $coaster = Coaster::register(
            11,
            500,
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

        $manager = new ClientManager();
        $manager->handle(new CoasterWagons($coaster, $wagons), $notifier);
    }

    /**
     * @throws Exception|\Exception
     */
    public function testWhenMissingWagons(): void
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
        ];

        $notifier = $this->createMock(Notifier::class);
        $notifier->expects($this->once())
            ->method('notify')
            ->with(sprintf('The coaster %s needs %s more wagons.', $coaster->id, 1));

        $manager = new ClientManager();
        $manager->handle(new CoasterWagons($coaster, $wagons), $notifier);
    }

    /**
     * @throws Exception|\Exception
     */
    public function testWhenMissingPersonnel(): void
    {
        $coaster = Coaster::register(
            10,
            500,
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
            ->with(sprintf('The coaster %s needs %s more personnel.', $coaster->id, 1));

        $manager = new ClientManager();
        $manager->handle(new CoasterWagons($coaster, $wagons), $notifier);
    }

    /**
     * @throws Exception|\Exception
     */
    public function testWhenMissingWagonsAndPersonnel(): void
    {
        $coaster = Coaster::register(
            10,
            540,
            2400,
            new TimeRange(new DateTimeImmutable('08:00'), new DateTimeImmutable('16:00')),
        );
        $wagons = [
            Wagon::register($coaster->id, 20, 1),
            Wagon::register($coaster->id, 20, 1),
            Wagon::register($coaster->id, 20, 1),
            Wagon::register($coaster->id, 20, 1),
        ];

        $messages = [];
        $notifier = $this->createMock(Notifier::class);
        $notifier->method('notify')
            ->willReturnCallback(
                function ($message) use (&$messages): void {
                    $messages[] = $message;
                },
            );

        $manager = new ClientManager();
        $manager->handle(new CoasterWagons($coaster, $wagons), $notifier);

        $this->assertCount(2, $messages);
        $this->assertSame(sprintf('The coaster %s needs %s more wagons.', $coaster->id, 1), $messages[0]);
        $this->assertSame(sprintf('The coaster %s needs %s more personnel.', $coaster->id, 1), $messages[1]);
    }

    /**
     * @throws Exception|\Exception
     */
    public function testWhenWagonsIsTooMany(): void
    {
        $coaster = Coaster::register(
            7,
            270,
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
            ->with(sprintf('The coaster %s has %s wagons too many.', $coaster->id, 2));

        $manager = new ClientManager();
        $manager->handle(new CoasterWagons($coaster, $wagons), $notifier);
    }

    /**
     * @throws Exception|\Exception
     */
    public function testWhenWagonsAndPersonnelIsTooMany(): void
    {
        $coaster = Coaster::register(
            10,
            270,
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

        $messages = [];
        $notifier = $this->createMock(Notifier::class);
        $notifier->method('notify')
            ->willReturnCallback(
                function ($message) use (&$messages): void {
                    $messages[] = $message;
                },
            );

        $manager = new ClientManager();
        $manager->handle(new CoasterWagons($coaster, $wagons), $notifier);

        $this->assertCount(2, $messages);
        $this->assertSame(sprintf('The coaster %s has %s wagons too many.', $coaster->id, 2), $messages[0]);
        $this->assertSame(sprintf('The coaster %s has %s persons too many.', $coaster->id, 3), $messages[1]);
    }
}
