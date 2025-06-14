<?php

namespace Coaster\UI\Http\Controller;

use App\Coaster\Domain\Model\Coaster;
use App\Coaster\Domain\Model\Wagon;
use App\Coaster\Domain\Repository\CoasterRepository;
use App\Coaster\Domain\Repository\WagonRepository;
use App\Coaster\Domain\ValueObject\TimeRange;
use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\FeatureTestTrait;
use DateTimeImmutable;
use Exception;
use JsonException;

final class DeleteWagonControllerTest extends CIUnitTestCase
{
    use FeatureTestTrait;

    /**
     * @throws Exception
     */
    public function testDelete(): void
    {
        /** @var CoasterRepository $repository */
        $repository = service('coasterRepository');
        $entity = Coaster::register(
            1,
            2,
            10,
            new TimeRange(new DateTimeImmutable('01-01-2000'), new DateTimeImmutable('01-01-2000')),
        );
        $repository->save($entity);
        /** @var WagonRepository $repository */
        $repository = service('wagonRepository');
        $entity = Wagon::register($entity->id, 1, 2.2);
        $repository->save($entity);

        $response = $this->delete(
            sprintf('api/coasters/%s/wagon/%s', $entity->coasterId->getId(), $entity->id->getId()),
            [],
        );

        $response->assertStatus(200);
    }

    /**
     * @throws JsonException|Exception
     */
    public function testWhenCostarNotExists(): void
    {
        $response = $this->delete(
            'api/coasters/384ca810-694d-4ab9-a281-62762daac029/wagon/4b8ca444-0d1f-48e3-b321-53eb3f503907',
        );

        $response->assertStatus(404);

        $responseData = json_decode($response->getJSON(), true, 512, JSON_THROW_ON_ERROR);

        $this->assertSame('Coaster not found.', $responseData['error']);
    }


    /**
     * @throws JsonException|Exception
     */
    public function testWhenWagonNotExists(): void
    {
        /** @var CoasterRepository $repository */
        $repository = service('coasterRepository');
        $entity = Coaster::register(
            1,
            2,
            10,
            new TimeRange(new DateTimeImmutable('01-01-2000'), new DateTimeImmutable('01-01-2000')),
        );
        $repository->save($entity);

        $response = $this->delete(sprintf('api/coasters/%s/wagon/4b8ca444-0d1f-48e3-b321-53eb3f503907', $entity->id));

        $response->assertStatus(404);

        $responseData = json_decode($response->getJSON(), true, 512, JSON_THROW_ON_ERROR);

        $this->assertSame('Wagon not found.', $responseData['error']);
    }
}
