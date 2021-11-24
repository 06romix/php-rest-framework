<?php
declare(strict_types=1);

namespace Dev256\Rest;

use Dev256\Framework\RequestInterface;

class Request implements RequestInterface
{
    /**#@+
     * HTTP methods supported by REST.
     */
    public const HTTP_METHOD_GET = 'GET';
    public const HTTP_METHOD_POST = 'POST';
    /**#@-*/

    private string $path;
    private array $params;

    public function __construct()
    {
        if (str_contains($_SERVER['REQUEST_URI'], '?')) {
            $this->path = strstr($_SERVER['REQUEST_URI'], '?', true);
        } else {
            $this->path = $_SERVER['REQUEST_URI'];
        }

        $this->path = str_replace('/rest', '', $this->path);
        $this->params = $_REQUEST;
    }

    public function getParam(string $name, mixed $defaultValue): mixed
    {
        return $this->params[$name] ?? $defaultValue;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * @inheritDoc
     */
    public function getHttpMethod(): string
    {
        return $_SERVER['REQUEST_METHOD'];
    }

    /**
     * @return array
     */
    public function getParams(): array
    {
        return $this->params;
    }

    /**
     * @param array $params
     */
    public function setParams(array $params): void
    {
        $this->params = array_merge($this->params, $params);
    }
}
