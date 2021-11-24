<?php


declare(strict_types=1);

namespace Dev256\Rest;

use Di\ObjectManagerInterface;
use Dev256\Framework\RequestInterface;

class Endpoint
{

    /**
     * @param string $pattern
     * @param string $method
     * @param array  $service
     */
    public function __construct(
        private ObjectManagerInterface $objectManager,
        private string $pattern,
        private string $method,
        private array $service
    ) {}

    public function execute(array $params): mixed
    {
        $service = $this->objectManager->get($this->getServiceClass());
        $methodName = $this->getServiceMethod();
        return $service->$methodName(... $params);
    }

    public function getRoute(): string
    {
        return $this->pattern;
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function getServiceClass(): string
    {
        return $this->service['class'];
    }

    public function getServiceMethod(): string
    {
        return $this->service['method'];
    }
}
