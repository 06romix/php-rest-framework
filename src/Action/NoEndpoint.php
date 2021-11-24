<?php
declare(strict_types=1);

namespace Dev256\Rest\Action;

use Dev256\Framework\Action\ResultFactory;
use Dev256\Framework\Action\ResultInterface;
use Dev256\Framework\ActionInterface;
use Dev256\Framework\RequestInterface;

class NoEndpoint implements ActionInterface
{

    public function __construct(private ResultFactory $resultFactory) {}

    public function execute(RequestInterface $request): ResultInterface
    {
        /** @var \Dev256\Framework\Action\JsonResult $jsonResult */
        $jsonResult = $this->resultFactory->create(ResultFactory::TYPE_JSON);
        $jsonResult->setData(['message' => 'Rest endpoint not found.']);
        return $jsonResult;
    }
}
