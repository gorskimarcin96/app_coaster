<?php

declare(strict_types=1);

namespace Coaster\UI\Http\Controller;

use Tests\Support\AbstractApiTestCase;
use Exception;
use JsonException;

final class ChangeCoasterControllerTest extends AbstractApiTestCase
{
    /**
     * @throws JsonException|Exception
     */
    public function testChange(): void
    {
        $entity = $this->createCoaster();
        $payload = [
            "availablePersonnel" => 2,
            "clientsPerDay" => 3,
            "from" => "08:00",
            "to" => "16:00",
        ];
        $response = $this
            ->withHeaders(['Content-Type' => 'application/json'])
            ->withBody(json_encode($payload, JSON_THROW_ON_ERROR))
            ->put(sprintf('api/coasters/%s', $entity->id));

        $response->assertStatus(200);

        $entity = $this->getCoaster($entity->id);

        $this->assertSame($payload['availablePersonnel'], $entity->availablePersonnel);
        $this->assertSame($payload['clientsPerDay'], $entity->clientsPerDay);
        $this->assertSame($payload['from'], $entity->timeRange->from->format('H:i'));
        $this->assertSame($payload['to'], $entity->timeRange->to->format('H:i'));
    }

    /**
     * @throws JsonException|Exception
     */
    public function testBadRequest(): void
    {
        $entity = $this->createCoaster();
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
        $payload = [
            "availablePersonnel" => 2,
            "clientsPerDay" => 3,
            "from" => "08:00",
            "to" => "16:00",
        ];
        $response = $this
            ->withHeaders(['Content-Type' => 'application/json'])
            ->withBody(json_encode($payload, JSON_THROW_ON_ERROR))
            ->put('api/coasters/44253660-2904-40e8-9f9f-c910a8b9c017');

        $response->assertStatus(404);

        $responseData = json_decode($response->getJSON(), true, 512, JSON_THROW_ON_ERROR);

        $this->assertIsString($responseData['error']);
    }
}
