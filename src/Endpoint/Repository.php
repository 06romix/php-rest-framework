<?php
declare(strict_types=1);

namespace Dev256\Rest\Endpoint;

use Di\ObjectManagerInterface;
use Dev256\Framework\Module\GetList;
use Dev256\Rest\Endpoint;
use Dev256\Rest\RestEndpoint;

class Repository
{

    public function __construct(
        private GetList $getModuleList,
        private ObjectManagerInterface $objectManager
    ) {}

    /**
     * @return \Dev256\Rest\Endpoint[]
     */
    public function getList(): array
    {
        $endpoints = [];
        foreach ($this->getModuleList->execute() as $module) {
            $endpoints[] = $this->findEndpoints($module);
        }

        $endpoints = array_filter($endpoints);
        if ($endpoints > 1) {
            $endpoints = array_merge(... $endpoints);
        } else {
            $endpoints = $endpoints[0];
        }

        return $endpoints;
    }

    /**
     * @param string $module
     * @return Endpoint[]
     */
    private function findEndpoints(string $module): array
    {
        $apiDir = BASEDIR . '/' . $module . '/Api';
        if (! file_exists($apiDir)) {
            return [];
        }

        $apiFiles = array_filter(scandir($apiDir), static function ($file) {
            return str_contains($file, '.php');
        });

        $endpoints = [];
        foreach ($apiFiles as $file) {
            $fullClassName = "\\$module\\Api\\" . str_replace('.php', '', $file);
            $reflectionClass = new \ReflectionClass($fullClassName);
            foreach ($reflectionClass->getMethods() as $reflectionMethod) {
                foreach ($reflectionMethod->getAttributes(RestEndpoint::class) as $attribute) {
                    $endpoints[] = $this->objectManager->create(Endpoint::class, [
                        'pattern' => $attribute->getArguments()['pattern'],
                        'method' => $attribute->getArguments()['method'],
                        'service' => [
                            'class' => $reflectionClass->getName(),
                            'method' => $reflectionMethod->getName(),
                        ],
                    ]);
                }
            }
        }

        return $endpoints;
    }
}
