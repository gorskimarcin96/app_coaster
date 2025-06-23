<?php

namespace Coaster\UI\Http\Controller;

use App\Coaster\Domain\Model\Coaster;
use Exception;
use JsonException;
use Tests\Support\AbstractApiTestCase;

final class GetCoastersControllerTest extends AbstractApiTestCase
{
    /**
     * @throws JsonException|Exception
     */
    public function testGet(): void
    {
        array_map(fn(int $availablePersonnel): Coaster => $this->createCoaster($availablePersonnel), range(1, 10));

        $response = $this->get('api/coasters');

        $response->assertStatus(200);

        $responseData = json_decode($response->getJSON(), true, 512, JSON_THROW_ON_ERROR);

        $this->assertCount(10, $responseData);

        array_map(
            fn(array $row) => $this->assertStructure(
                ['id', 'availablePersonnel', 'clientsPerDay', 'trackLengthInMeters', 'from', 'to'],
                $row,
            ),
            $responseData,
        );
    }
}
