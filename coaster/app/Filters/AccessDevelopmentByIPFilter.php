<?php

declare(strict_types=1);

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\Response;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

final readonly class AccessDevelopmentByIPFilter implements FilterInterface
{
    private const ALLOWED_IPS = ['127.0.0.1'];

    public function before(RequestInterface $request, $arguments = null)
    {
        if (
            env('CI_ENVIRONMENT') === 'development'
            && !in_array($request->getIPAddress(), self::ALLOWED_IPS, true)
        ) {
            log_message(
                'info',
                sprintf('User with IP address %s tries to connect to the application.', $request->getIPAddress()),
            );

            /** @var Response $response */
            $response = service('response');

            return $response->setStatusCode(403)->setJSON(['error' => 'Forbidden']);
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null): void
    {
    }
}
