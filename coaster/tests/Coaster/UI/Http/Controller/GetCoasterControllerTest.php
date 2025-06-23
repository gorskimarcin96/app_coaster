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
            ['id', 'availablePersonnel', 'clientsPerDay', 'trackLengthInMeters', 'from', 'to'],
            $responseData,
        );

        $this->assertSame($responseData['id'], $entity->id->getId()->toString());
        $this->assertSame($responseData['availablePersonnel'], $entity->availablePersonnel);
        $this->assertSame($responseData['clientsPerDay'], $entity->clientsPerDay);
        $this->assertSame($responseData['trackLengthInMeters'], $entity->trackLengthInMeters);
        $this->assertSame($responseData['from'], $entity->timeRange->from->format(DateTimeInterface::ATOM));
        $this->assertSame($responseData['to'], $entity->timeRange->to->format(DateTimeInterface::ATOM));
    }

    /**
     * @throws Exception
     */
    public function testNotFound(): void
    {
        $this->get('api/coasters/e59c5487-5bfe-465a-b960-7f8a347113e6')->assertStatus(404);
    }
}
