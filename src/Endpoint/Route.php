<?php


declare(strict_types=1);

namespace Dev256\Rest\Endpoint;

use Dev256\Framework\RequestInterface;
use Dev256\Rest\Endpoint;
use JetBrains\PhpStorm\Pure;

class Route
{
    private array $variables = [];

    public function __construct(private Endpoint $endpoint) {}

    public function match(RequestInterface $request)
    {
        /** @var \Dev256\Rest\Request $request */
        $pathParts = $this->getPathParts($request->getPath());
        $routeParts = $this->getRouteParts();
        if (count($pathParts) !== count($routeParts)) {
            return false;
        }

        $result = [];
        foreach ($pathParts as $key => $value) {
            if (!array_key_exists($key, $routeParts)) {
                return false;
            }
            $variable = $this->variables[$key] ?? null;
            if ($variable) {
                $result[$variable] = urldecode($value);
            } else {
                if ($value !== $routeParts[$key]) {
                    return false;
                }
            }
        }
        return $result;
    }

    /**
     * Retrieve unified requested path
     *
     * @param string $path
     * @return string[]
     */
    #[Pure]
    private function getPathParts(string $path): array
    {
        return explode('/', trim($path, '/'));
    }

    /**
     * Split route by parts and variables
     *
     * @return string[]
     */
    private function getRouteParts(): array
    {
        $result = [];
        $routeParts = explode('/', $this->endpoint->getRoute());
        foreach ($routeParts as $key => $value) {
            if ($this->isVariable($value)) {
                $this->variables[$key] = substr($value, 1);
                $value = null;
            }
            $result[$key] = $value;
        }
        return $result;
    }

    /**
     * Check if current route part is a name of variable
     */
    private function isVariable(string $value): bool
    {
        return str_starts_with($value, ':') && $value[1] !== ':';
    }

    /**
     * @return \Dev256\Rest\Endpoint
     */
    public function getEndpoint(): Endpoint
    {
        return $this->endpoint;
    }
}
