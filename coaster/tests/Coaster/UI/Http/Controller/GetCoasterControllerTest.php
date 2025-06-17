<?php

namespace Coaster\UI\Http\Controller;

use DateTimeInterface;
use Exception;
use JsonException;
use Tests\Support\AbstractApiTestCase;

final class GetCoasterControllerTest extends AbstractApiTestCase
{
    /**
     * @throws JsonException|Exception
     */
    public function testGet(): void
    {
        $entity = $this->createCoaster();
        $response = $this->get('api/coasters/' . $entity->id->getId()->toString());

        $response->assertStatus(200);

        $responseData = json_decode($response->getJSON(), true, 512, JSON_THROW_ON_ERROR);

        $this->assertStructure(
            ['id', 'personNumber', 'clientNumber', 'distanceLength', 'fromDate', 'toDate'],
            $responseData,
        );

        $this->assertSame($responseData['id'], $entity->id->getId()->toString());
        $this->assertSame($responseData['personNumber'], $entity->personNumber);
        $this->assertSame($responseData['clientNumber'], $entity->clientNumber);
        $this->assertSame($responseData['distanceLength'], $entity->distanceLength);
        $this->assertSame($responseData['fromDate'], $entity->timeRange->fromDate->format(DateTimeInterface::ATOM));
        $this->assertSame($responseData['toDate'], $entity->timeRange->toDate->format(DateTimeInterface::ATOM));
    }

    /**
     * @throws Exception
     */
    public function testNotFound(): void
    {
        $this->get('api/coasters/e59c5487-5bfe-465a-b960-7f8a347113e6')->assertStatus(404);
    }
}
