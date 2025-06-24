<?php

declare(strict_types=1);

namespace Coaster\UI\Http\Controller;

use App\Coaster\Domain\Model\Wagon;
use Exception;
use JsonException;
use Tests\Support\AbstractApiTestCase;

final class GetWagonsControllerTest extends AbstractApiTestCase
{
    /**
     * @throws JsonException|Exception
     */
    public function testGet(): void
    {
        $coaster = $this->createCoaster();
        array_map(fn(int $seats): Wagon => $this->createWagon($coaster->id, $seats), range(1, 10));
        $response = $this->get(sprintf('api/coasters/%s/wagon', $coaster->id));

        $response->assertStatus(200);

        $responseData = json_decode($response->getJSON(), true, 512, JSON_THROW_ON_ERROR);

        array_map(
            fn(array $row) => $this->assertStructure(['id', 'coasterId', 'seats', 'speedInMetersPerSecond'], $row),
            $responseData,
        );
    }
}
