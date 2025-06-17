<?php

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

        $entity = $this->getCoaster($entity->id);

        $this->assertSame($payload['personNumber'], $entity->personNumber);
        $this->assertSame($payload['clientNumber'], $entity->clientNumber);
        $this->assertSame($payload['fromDate'], $entity->timeRange->fromDate->format('Y-m-d'));
        $this->assertSame($payload['toDate'], $entity->timeRange->toDate->format('Y-m-d'));
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
            "personNumber" => 2,
            "clientNumber" => 3,
            "fromDate" => "2000-01-02",
            "toDate" => "2000-01-03",
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
