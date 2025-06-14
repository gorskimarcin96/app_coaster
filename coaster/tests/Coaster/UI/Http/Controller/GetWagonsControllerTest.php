<?php

namespace Coaster\UI\Http\Controller;

use App\Coaster\Domain\Model\Coaster;
use App\Coaster\Domain\Repository\CoasterRepository;
use App\Coaster\Domain\ValueObject\TimeRange;
use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\FeatureTestTrait;
use DateTimeImmutable;
use Exception;
use JsonException;
use Redis;

final class GetWagonsControllerTest extends CIUnitTestCase
{
    use FeatureTestTrait;

    /**
     * @throws JsonException|Exception
     */
    public function testGet(): void
    {
        /** @var Redis $redis */
        $redis = service('redis');
        $redis->flushAll();

        for ($i = 0; $i < 3; $i++) {
            /** @var CoasterRepository $repository */
            $repository = service('coasterRepository');
            $entity = Coaster::register(1, 2, 10, new TimeRange(new DateTimeImmutable(), new DateTimeImmutable()));
            $repository->save($entity);
        }
        $response = $this->get('api/coasters');

        $response->assertStatus(200);

        $responseData = json_decode($response->getJSON(), true, 512, JSON_THROW_ON_ERROR);
        $this->assertCount(3, $responseData);
    }
}
