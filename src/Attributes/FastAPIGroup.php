<?php

namespace MKD\FastAPI\Attributes;

use Attribute;
use MKD\FastAPI\Enums\FastApiMethod;

#[Attribute(Attribute::TARGET_CLASS)]
class FastAPIGroup
{
    public string $prefix;
    public array $middlewares;
    public array $options;

    public function __construct(string $prefix = '', array $middlewares = [], array $options = [])
    {
        $this->prefix = $prefix;
        $this->middlewares = $middlewares;
        $this->options = $options;
    }

}
