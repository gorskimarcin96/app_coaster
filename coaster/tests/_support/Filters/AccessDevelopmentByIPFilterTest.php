<?php

declare(strict_types=1);

namespace Tests\Support\Filters;

use App\Filters\AccessDevelopmentByIPFilter;
use CodeIgniter\Config\Services;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\Response;
use CodeIgniter\Test\CIUnitTestCase;
use PHPUnit\Framework\MockObject\Exception;

class AccessDevelopmentByIPFilterTest extends CIUnitTestCase
{
    /**
     * @throws Exception
     */
    public function testReturns403IfNotAllowedIPInDevelopment(): void
    {
        putenv('CI_ENVIRONMENT=development');

        $request = $this->createMock(IncomingRequest::class);
        $request
            ->method('getIPAddress')
            ->willReturn('1.2.3.4');

        $responseMock = $this->getMockBuilder(Response::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['setStatusCode', 'setJSON'])
            ->getMock();

        $responseMock->expects($this->once())
            ->method('setStatusCode')
            ->with(403)
            ->willReturnSelf();

        $responseMock->expects($this->once())
            ->method('setJSON')
            ->with(['error' => 'Forbidden'])
            ->willReturnSelf();

        Services::injectMock('response', $responseMock);

        $filter = new AccessDevelopmentByIPFilter();

        $result = $filter->before($request);

        $this->assertSame($responseMock, $result);
    }

    /**
     * @throws Exception
     */
    public function testReturnsNullIfAllowedIPOrNotDevelopment(): void
    {
        putenv('CI_ENVIRONMENT=development');

        $request = $this->createMock(IncomingRequest::class);
        $request
            ->method('getIPAddress')
            ->willReturn('127.0.0.1');

        $filter = new AccessDevelopmentByIPFilter();
        $result = $filter->before($request);

        $this->assertNull($result);

        putenv('CI_ENVIRONMENT=production');

        $request = $this->createMock(IncomingRequest::class);
        $request
            ->method('getIPAddress')
            ->willReturn('1.2.3.4');

        $filter = new AccessDevelopmentByIPFilter();
        $result = $filter->before($request);

        $this->assertNull($result);
    }

    public function testAfterDoesNothing(): void
    {
        $filter = new AccessDevelopmentByIPFilter();

        $this->assertNull($filter->after($this->createMock(IncomingRequest::class), $this->createMock(Response::class)));
    }
}
