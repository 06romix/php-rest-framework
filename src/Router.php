<?php
declare(strict_types=1);

namespace Dev256\Rest;

use Di\ObjectManagerInterface;
use Dev256\Framework\ActionInterface;
use Dev256\Framework\RequestInterface;
use Dev256\Rest\Action\DefaultAction;
use Dev256\Rest\Action\NoEndpoint;
use Dev256\Rest\Endpoint\Config;
use Dev256\Rest\Endpoint\Repository;
use Dev256\Framework\RouterInterface;
use Index\Controller\Index\NoRoute;

class Router implements RouterInterface
{

    public function __construct(
        private ObjectManagerInterface $objectManager,
        private Config $config
    ) {}

    /**
     * @param \Dev256\Rest\Request $request
     * @return \Dev256\Framework\ActionInterface
     */
    public function match(RequestInterface $request): ActionInterface
    {
        foreach ($this->config->getRoutes($request) as $route) {
            $params = $route->match($request);
            if ($params !== false) {
                $request->setParams($params);
                $matched[] = $route;
            }
        }
        if (! empty($matched)) {
            return $this->objectManager->create(
                DefaultAction::class,
                ['endpoint' => array_pop($matched)->getEndpoint()]
            );
        }
        return $this->objectManager->create(NoEndpoint::class);
    }
}
