<?php

declare(strict_types=1);

namespace Coaster\UI\Http\Controller;

use Exception;
use JsonException;
use Tests\Support\AbstractApiTestCase;

final class RegisterWagonControllerTest extends AbstractApiTestCase
{
    /**
     * @throws JsonException|Exception
     */
    public function testRegister(): void
    {
        $entity = $this->createCoaster();
        $payload = [
            'seats' => 20,
            'speedInMetersPerSecond' => 2.6,
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
        $entity = $this->createCoaster();
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
            'seats' => 2,
            'speedInMetersPerSecond' => 2.6,
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
