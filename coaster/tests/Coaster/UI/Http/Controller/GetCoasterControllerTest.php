<?php

namespace Coaster\UI\Http\Controller;

use App\Coaster\Domain\Model\Coaster;
use App\Coaster\Domain\Repository\CoasterRepository;
use App\Coaster\Domain\ValueObject\TimeRange;
use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\FeatureTestTrait;
use DateTimeImmutable;
use DateTimeInterface;
use Exception;
use JsonException;

final class GetCoasterControllerTest extends CIUnitTestCase
{
    use FeatureTestTrait;

    /**
     * @throws JsonException|Exception
     */
    public function testGet(): void
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
        $response = $this->get('api/coasters/' . $entity->id->getId()->toString());

        $response->assertStatus(200);

        $responseData = json_decode($response->getJSON(), true, 512, JSON_THROW_ON_ERROR);
        $this->assertSame($responseData['id'], $entity->id->getId()->toString());
        $this->assertSame($responseData['personNumber'], $entity->personNumber);
        $this->assertSame($responseData['clientNumber'], $entity->clientNumber);
        $this->assertSame($responseData['distanceLength'], $entity->distanceLength);
        $this->assertSame($responseData['fromDate'], $entity->timeRange->fromDate->format(DateTimeInterface::ATOM));
        $this->assertSame($responseData['toDate'], $entity->timeRange->toDate->format(DateTimeInterface::ATOM));
    }

    /**
     * @throws Exception
     */
    public function testNotFound(): void
    {
        $this->get('api/coasters/e59c5487-5bfe-465a-b960-7f8a347113e6')->assertStatus(404);
    }
}
