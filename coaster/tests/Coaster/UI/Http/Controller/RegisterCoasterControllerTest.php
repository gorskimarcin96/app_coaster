<?php

namespace Coaster\UI\Http\Controller;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\FeatureTestTrait;
use Exception;
use JsonException;

final class RegisterCoasterControllerTest extends CIUnitTestCase
{
    use FeatureTestTrait;

    /**
     * @throws JsonException|Exception
     */
    public function testRegister(): void
    {
        $payload = [
            "personNumber" => 1,
            "clientNumber" => 2,
            "distanceLength" => 10,
            "fromDate" => "2000-01-01",
            "toDate" => "2000-01-02",
        ];
        $response = $this
            ->withHeaders(['Content-Type' => 'application/json'])
            ->withBody(json_encode($payload, JSON_THROW_ON_ERROR))
            ->post('api/coasters');

        $response->assertStatus(201);

        $responseData = json_decode($response->getJSON(), true, 512, JSON_THROW_ON_ERROR);

        $this->assertIsString($responseData['id']);
    }

    /**
     * @throws JsonException|Exception
     */
    public function testBadRequest(): void
    {
        $response = $this
            ->withHeaders(['Content-Type' => 'application/json'])
            ->withBody(json_encode([], JSON_THROW_ON_ERROR))
            ->post('api/coasters');

        $response->assertStatus(400);

        $responseData = json_decode($response->getJSON(), true, 512, JSON_THROW_ON_ERROR);

        $this->assertIsString($responseData['error']);
    }
}
