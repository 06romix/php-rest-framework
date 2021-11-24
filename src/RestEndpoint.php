<?php
declare(strict_types=1);

namespace Dev256\Rest;

use Attribute;

#[Attribute(Attribute::TARGET_METHOD)]
class RestEndpoint
{
    public const GET = 'GET';
    public const POST = 'POST';

    public function __construct(string $pattern, string $method)
    {
    }
}
