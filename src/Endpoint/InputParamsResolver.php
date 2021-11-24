<?php
declare(strict_types=1);

namespace Dev256\Rest\Endpoint;

use Dev256\Framework\RequestInterface;
use Dev256\Rest\Endpoint;

class InputParamsResolver
{
    public function __construct(private ServiceInputProcessor $serviceInputProcessor) {}

    /**
     * @param \Dev256\Rest\Request  $request
     * @param \Dev256\Rest\Endpoint $endpoint
     * @return array
     */
    public function resolve(RequestInterface $request, Endpoint $endpoint): array
    {
        $serviceMethodName = $endpoint->getServiceMethod();
        $serviceClassName = $endpoint->getServiceClass();
        $inputData = $request->getParams();
        return $this->serviceInputProcessor->process($serviceClassName, $serviceMethodName, $inputData);
    }
}
