<?php
declare(strict_types=1);

namespace Dev256\Rest\Endpoint;

use Di\ObjectManagerInterface;
use Dev256\Framework\RequestInterface;

class Config
{
    public function __construct(
        private Repository $endpointRepository,
        private ObjectManagerInterface $objectManager
    ) {}

    /**
     * @param \Dev256\Framework\RequestInterface $request
     * @return \Dev256\Rest\Endpoint\Route[]
     */
    public function getRoutes(RequestInterface $request): array
    {
        $routes = [];
        foreach ($this->endpointRepository->getList() as $endpoint) {
            if ($endpoint->getMethod() !== $request->getHttpMethod()) {
                continue;
            }

            $routes[] = $this->objectManager->create(
                Route::class,
                ['endpoint' => $endpoint]
            );
        }

        return $routes;
    }
}
