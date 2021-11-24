<?php
declare(strict_types=1);

namespace Dev256\Rest\Framework\ObjectManager;

use Dev256\Framework\RequestInterface;
use Dev256\Framework\RouterInterface;

class Preference
{

    /**
     * @return string[]
     */
    public function getRestPreferences(): array
    {
        return [
            RequestInterface::class => \Dev256\Rest\Request::class,
            RouterInterface::class  => \Dev256\Rest\Router::class,
        ];
    }
}
