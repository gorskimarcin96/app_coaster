<?php

namespace Coaster\UI\Http\Controller;

use App\Coaster\Domain\Model\Coaster;
use App\Coaster\Domain\Repository\CoasterRepository;
use App\Coaster\Domain\ValueObject\TimeRange;
use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\FeatureTestTrait;
use DateTimeImmutable;
use Exception;
use JsonException;

final class ChangeCoasterControllerTest extends CIUnitTestCase
{
    use FeatureTestTrait;

    /**
     * @throws JsonException|Exception
     */
    public function testChange(): void
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
        $payload = [
            "personNumber" => 2,
            "clientNumber" => 3,
            "fromDate" => "2000-01-02",
            "toDate" => "2000-01-03",
        ];
        $response = $this
            ->withHeaders(['Content-Type' => 'application/json'])
            ->withBody(json_encode($payload, JSON_THROW_ON_ERROR))
            ->put(sprintf('api/coasters/%s', $entity->id));

        $response->assertStatus(200);
    }

    /**
     * @throws JsonException|Exception
     */
    public function testBadRequest(): void
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

        $response = $this
            ->withHeaders(['Content-Type' => 'application/json'])
            ->withBody(json_encode([], JSON_THROW_ON_ERROR))
            ->put(sprintf('api/coasters/%s', $entity->id));

        $response->assertStatus(400);

        $responseData = json_decode($response->getJSON(), true, 512, JSON_THROW_ON_ERROR);

        $this->assertIsString($responseData['error']);
    }

    /**
     * @throws JsonException|Exception
     */
    public function testWhenCostarNotExists(): void
    {
        $response = $this
            ->withHeaders(['Content-Type' => 'application/json'])
            ->withBody(json_encode([], JSON_THROW_ON_ERROR))
            ->put('api/coasters/44253660-2904-40e8-9f9f-c910a8b9c017');

        $response->assertStatus(400);
    }
}
