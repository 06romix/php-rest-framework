<?php
declare(strict_types=1);

namespace Dev256\Rest\Action;

use Dev256\Framework\Action\ResultFactory;
use Dev256\Framework\Action\ResultInterface;
use Dev256\Framework\ActionInterface;
use Dev256\Framework\RequestInterface;
use Dev256\Rest\Endpoint;
use Dev256\Rest\Endpoint\ServiceOutputProcessor;
use Dev256\Rest\Endpoint\InputParamsResolver;

class DefaultAction implements ActionInterface
{

    public function __construct(
        private ResultFactory $resultFactory,
        private Endpoint $endpoint,
        private ServiceOutputProcessor $serviceOutputProcessor,
        private InputParamsResolver $inputParamsResolver,
    ) {}

    public function execute(RequestInterface $request): ResultInterface
    {
        /** @var \Dev256\Framework\Action\JsonResult $jsonResult */
        $jsonResult = $this->resultFactory->create(ResultFactory::TYPE_JSON);
        $outputData = $this->endpoint->execute(
            $this->inputParamsResolver->resolve($request, $this->endpoint)
        );
        $outputData = $this->serviceOutputProcessor->process(
            $outputData,
            $this->endpoint->getServiceClass(),
            $this->endpoint->getServiceMethod()
        );
        return $jsonResult->setData($outputData);
    }
}
