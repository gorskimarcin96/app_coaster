<?php

namespace Coaster\UI\Http\Controller;

use DateInterval;
use DateTimeImmutable;
use Exception;
use JsonException;
use Tests\Support\AbstractApiTestCase;

final class GetWagonControllerTest extends AbstractApiTestCase
{
    /**
     * @throws JsonException|Exception
     */
    public function testGet(): void
    {
        $entity = $this->createWagon(
            $this->createCoaster()->id,
            startTime: new DateTimeImmutable('2000-01-01'),
            rideDuration: new DateInterval('PT30M'),
        );
        $response = $this->get(sprintf('api/coasters/%s/wagon/%s', $entity->coasterId, $entity->id));

        $response->assertStatus(200);

        $responseData = json_decode($response->getJSON(), true, 512, JSON_THROW_ON_ERROR);

        $this->assertStructure(
            ['id', 'coasterId', 'numberOfPlaces', 'speed', 'startedAt', 'expectedReturnAt'],
            $responseData,
        );
        $this->assertSame($responseData['id'], $entity->id->getId()->toString());
        $this->assertSame($responseData['coasterId'], $entity->coasterId->getId()->toString());
        $this->assertSame($responseData['numberOfPlaces'], $entity->numberOfPlaces);
        $this->assertSame($responseData['speed'], $entity->speed);
        $this->assertSame($responseData['startedAt'], '2000-01-01T00:00:00+00:00');
        $this->assertSame($responseData['expectedReturnAt'], '2000-01-01T00:30:00+00:00');
    }

    /**
     * @throws Exception
     */
    public function testNotFound(): void
    {
        $this->get('api/coasters/8cba65b6-b90e-4fdb-a993-f11093f65155/wagon/6cf78a9f-1988-4bc6-8bc2-e8c40d95a6d0')
            ->assertStatus(404);
    }
}
