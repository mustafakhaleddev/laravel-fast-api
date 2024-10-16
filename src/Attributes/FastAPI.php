<?php

namespace MKD\FastAPI\Attributes;

use Attribute;
use MKD\FastAPI\Enums\FastApiMethod;

#[Attribute(Attribute::TARGET_METHOD)]
class FastAPI
{
    public FastApiMethod $method;
    public string $path;
    public ?array $options;
    public FastAPIGroup $group;

    public function __construct(FastApiMethod $method, string $path, ?array $options = [])
    {
        $this->method = $method;
        $this->path = $path;
        $this->options = $options;
    }

    public function setGroup(FastAPIGroup $group){
        $this->group = $group;
    }

}
