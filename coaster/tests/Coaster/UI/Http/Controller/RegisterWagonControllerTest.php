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

final class RegisterWagonControllerTest extends CIUnitTestCase
{
    use FeatureTestTrait;

    /**
     * @throws JsonException|Exception
     */
    public function testRegister(): void
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
            'numberOfPlaces' => 2,
            'speed' => 2.6,
            'coasterId' => $entity->id->getId()->toString(),
        ];
        $response = $this
            ->withHeaders(['Content-Type' => 'application/json'])
            ->withBody(json_encode($payload, JSON_THROW_ON_ERROR))
            ->post(sprintf('api/coasters/%s/wagon', $entity->id->getId()->toString()));

        $response->assertStatus(201);

        $responseData = json_decode($response->getJSON(), true, 512, JSON_THROW_ON_ERROR);

        $this->assertIsString($responseData['id']);
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
            ->post(sprintf('api/coasters/%s/wagon', $entity->id->getId()->toString()));

        $response->assertStatus(400);

        $responseData = json_decode($response->getJSON(), true, 512, JSON_THROW_ON_ERROR);

        $this->assertIsString($responseData['error']);
    }

    /**
     * @throws JsonException|Exception
     */
    public function testWhenCostarNotExists(): void
    {
        $payload = [
            'numberOfPlaces' => 2,
            'speed' => 2.6,
            'coasterId' => 'a53be3c5-2435-40d1-8e6a-df04ebc1dfa2',
        ];
        $response = $this
            ->withHeaders(['Content-Type' => 'application/json'])
            ->withBody(json_encode($payload, JSON_THROW_ON_ERROR))
            ->post(sprintf('api/coasters/%s/wagon', $payload['coasterId']));

        $response->assertStatus(404);

        $responseData = json_decode($response->getJSON(), true, 512, JSON_THROW_ON_ERROR);

        $this->assertIsString($responseData['error']);
    }
}
