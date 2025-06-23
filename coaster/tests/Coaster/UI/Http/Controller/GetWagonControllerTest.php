<?php

namespace Coaster\UI\Http\Controller;

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
        $entity = $this->createWagon($this->createCoaster()->id);
        $response = $this->get(sprintf('api/coasters/%s/wagon/%s', $entity->coasterId, $entity->id));

        $response->assertStatus(200);

        $responseData = json_decode($response->getJSON(), true, 512, JSON_THROW_ON_ERROR);

        $this->assertStructure(['id', 'coasterId', 'seats', 'speedInMetersPerSecond'], $responseData);
        $this->assertSame($responseData['id'], $entity->id->getId()->toString());
        $this->assertSame($responseData['coasterId'], $entity->coasterId->getId()->toString());
        $this->assertSame($responseData['seats'], $entity->seats);
        $this->assertSame($responseData['speedInMetersPerSecond'], $entity->speedInMetersPerSecond);
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
