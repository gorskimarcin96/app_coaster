<?php

declare(strict_types=1);

namespace Coaster\UI\Http\Controller;

use Exception;
use JsonException;
use Tests\Support\AbstractApiTestCase;

final class DeleteWagonControllerTest extends AbstractApiTestCase
{
    /**
     * @throws Exception
     */
    public function testDelete(): void
    {
        $entity = $this->createWagon($this->createCoaster()->id);
        $response = $this->delete(
            sprintf('api/coasters/%s/wagon/%s', $entity->coasterId->getId(), $entity->id->getId()),
            [],
        );

        $response->assertStatus(200);

        $this->assertNull($this->getWagon($entity->coasterId, $entity->id));
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

        $this->assertSame('Entity "coaster" not found.', $responseData['error']);
    }

    /**
     * @throws JsonException|Exception
     */
    public function testWhenWagonNotExists(): void
    {
        $entity = $this->createWagon($this->createCoaster()->id);
        $response = $this->delete(
            sprintf('api/coasters/%s/wagon/4b8ca444-0d1f-48e3-b321-53eb3f503907', $entity->coasterId),
        );

        $response->assertStatus(404);

        $responseData = json_decode($response->getJSON(), true, 512, JSON_THROW_ON_ERROR);

        $this->assertSame('Entity "wagon" not found.', $responseData['error']);
    }
}
