<?php

namespace MKD\FastAPI;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;
use MKD\FastAPI\Attributes\FastAPI;
use MKD\FastAPI\Attributes\FastAPIGroup;
use MKD\FastAPI\Enums\FastApiMethod;

class FastAPIService
{

    private array $supportedFunctions = [
        'middleware',
        'where',
        'whereNumber',
        'whereAlpha',
        'whereAlphaNumeric',
        'whereUuid',
        'whereUlid',
        'whereIn',
        'name',
        'withTrashed',
        'scopeBindings',
        'withoutScopedBindings',
    ];

    final public function registerRoutes(): void
    {
//        dd($this->getControllers());
        // Define controllers to scan for route groups
        $controllers = $this->getControllers();

        foreach ($controllers as $controller) {
            $reflectionClass = new \ReflectionClass($controller);

            // Check if the class has the RouteGroup attribute
            $groupAttributes = $reflectionClass->getAttributes(FastAPIGroup::class);

            if (count($groupAttributes) > 0) {
                // Create an instance of the RouteGroup attribute
                $groupAttribute = $groupAttributes[0]->newInstance();

                $this->registerGroup($groupAttribute, $reflectionClass, $controller);

            } else {
                $this->registerMethods($reflectionClass, $controller);
            }
        }
    }

    private function registerGroup(FastAPIGroup $groupAttribute, \ReflectionClass $reflectionClass, string $controller): void
    {
        // Define the route group with prefix, middleware, and options
        Route::group([
            'prefix' => $groupAttribute->prefix,
            'middleware' => $groupAttribute->middlewares,
            ...$groupAttribute->options // Spread operator to merge additional options
        ], function () use ($reflectionClass, $controller, $groupAttribute) {
            $this->registerMethods($reflectionClass, $controller, $groupAttribute);
        });
    }

    private function registerMethods(\ReflectionClass $reflectionClass, string $controller, FastAPIGroup $group = null): void
    {
        foreach ($reflectionClass->getMethods() as $method) {
            $this->registerMethod($method, $controller, $group);
        }
    }

    private function registerMethod(\ReflectionMethod $method, string $controller, FastAPIGroup $group = null): void
    {
        foreach ($method->getAttributes(FastAPI::class) as $routeAttribute) {
            // Create an instance of the FastAPI attribute
            $route = $routeAttribute->newInstance();
            $routeRegister = false;
            // Register the route dynamically within the group
            if ($route->method == FastApiMethod::REDIRECT) {
                if (isset($route->options['to'])) {
                    $routeRegister = Route::redirect($route->path, $route->options['to'], $route->options['code'] ?? 301);
                }
            } elseif ($route->method == FastApiMethod::MATCH) {
                if (isset($route->options['methods'])) {
                    $routeRegister = Route::match($route->options['methods'], $route->path, [$controller, $method->getName()]);
                }

            } else {
                $routeRegister = Route::{$route->method->name}($route->path, [$controller, $method->getName()]);
            }

            if ($routeRegister) {
                if (isset($route->options['middleware'])) {
                    $routeRegister->middleware($route->options['middleware']);
                }

                if (isset($route->options['name'])) {
                    $routeName = $route->options['name'];
                    if ($group != null && isset($group->options['name'])) {
                        $routeName = $group->options['name'] . '.' . $routeName;
                    }
                    $routeRegister->name($routeName);
                }

                if (isset($route->options['functions']) && is_array($route->options['functions'])) {
                    foreach ($route->options['functions'] as $functionName => $parameters) {
                        if ($this->supportedFunction($functionName)) {
                            $routeRegister->{$functionName}(...$parameters ?? []);
                        }
                    }
                }
            }

        }
    }

    final public function getControllers(bool $cache = true): array
    {
         if (Cache::has('fast-api-controllers') && $cache) {
             return Cache::get('fast-api-controllers');
        }

        $controllersPaths = config('fast-api.paths', []);
        $fastAPIControllers = [];

        foreach ($controllersPaths as $path) {
            $controllers = File::allFiles($path);
            foreach ($controllers as $controller) {
                $filePath = $controller->getRealPath();
                $fileContents = File::get($filePath);


                // Search for the usage of MKD\FastAPI\Attributes\FastAPI
                if (strpos($fileContents, 'MKD\\FastAPI\\Attributes\\FastAPI') !== false) {
                    // Extract namespace
                    $namespace = $this->getNamespace($fileContents);

                    // Get the class name from the file path
                    $className = $namespace . '\\' . $controller->getBasename('.php');
                    $fastAPIControllers[] = $className;
                }
            }

        }

        return array_unique(array_merge($fastAPIControllers, config('fast-api.controllers', [])));
    }

    private function getNamespace($fileContents): string
    {
        // Match the namespace declaration
        if (preg_match('/namespace\s+([^\s;]+);/', $fileContents, $matches)) {
            return trim($matches[1]);
        }
        return 'App\\Http\\Controllers'; // Default namespace if none is found
    }

    private function supportedFunction(string $functionName): bool
    {
        return in_array($functionName, $this->supportedFunctions);
    }
}
