# Laravel Fast-API

[![Latest Version](https://img.shields.io/github/v/release/mustafakhaleddev/laravel-fast-api.svg?style=flat-square)](https://github.com/mustafakhaleddev/laravel-fast-api/releases)
[![Issues](https://img.shields.io/github/issues/mustafakhaleddev/laravel-fast-api.svg?style=flat-square)](https://github.com/mustafakhaleddev/laravel-fast-api/issues)
[![License](https://img.shields.io/github/license/mustafakhaleddev/laravel-fast-api.svg?style=flat-square)](https://github.com/mustafakhaleddev/laravel-fast-api/blob/main/LICENSE)

Laravel FastAPI is a PHP attribute-based routing solution for building APIs quickly and efficiently. With FastAPI attributes, you can define routes, methods, and middlewares directly in your controller classes, reducing the need for complex route files and enabling better organization and clarity.

This package also integrates seamlessly with Laravel's `route:cache` for enhanced performance, ensuring your APIs are as fast as possible.

## Features

- **Attribute-Based Routing**: Define your API routes using PHP attributes.
- **Support for Advanced Routing Options**: Middleware, where clauses, route options, and more!
- **Enum-Based HTTP Methods**: Use the predefined `FastApiMethod` for your HTTP methods.
- **API Caching**: Leverage Laravel's `route:cache` for optimal performance.
- **Clear API Cache**: Quickly clear cached API routes with simple Artisan commands.

## Installation

You can install the package via Composer:

```bash
composer require mkd/laravel-fast-api
```

## Usage

### Define FastAPI Routes

Use the `#[FastAPIGroup]` and `#[FastAPI]` attributes to define routes inside your controller classes.

```php
use MKD\FastAPI\Attributes\FastAPI;
use MKD\FastAPI\Attributes\FastAPIGroup;

#[FastAPIGroup(prefix: '/items', options: ['name' => 'items'], middlewares: ['auth'])]
class ItemsController extends Controller
{
    #[FastAPI(method: FastApiMethod::GET, path: '/data/{id}', options: ['functions' => [
        'whereIn' => ['id', ['2']],
    ], 'name' => 'item_id'])]
    public function getItem($id)
    {
        return response()->json(['item' => $id]);
    }
}
```

This simple attribute-based approach automatically handles routing logic, allowing you to focus on building your API logic.

### Supported Methods and Functions

You can define routes with the following HTTP methods:

```php
enum FastApiMethod
{
    case GET;
    case POST;
    case PUT;
    case PATCH;
    case OPTION;
    case DELETE;
    case ANY;
    case REDIRECT;
    case MATCH;
}
```

In addition, you can leverage these functions to customize your routes:

```php
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
```

### Configurations

In your configuration file, you can specify paths and controllers to be scanned for FastAPI attributes.

```php
return [
    //Paths to check for controllers that use fast-api
    'paths' => [
        app_path('Http/Controllers'),
    ],

    //Specify controllers that are not included in the paths
    'controllers' => [
        \App\Http\Controllers\CustomController::class
    ],
];
```

### Artisan Commands

FastAPI provides useful commands for caching and clearing controllers:

- Cache the controllers for better scanning performance:

  ```bash
  php artisan fast-api:cache
  ```

- Clear the cached controllers:

  ```bash
  php artisan fast-api:clear-cache
  ```

### Performance

By using Laravel's `route:cache`, the FastAPI routes are cached to ensure high performance. It is recommended to always cache your routes in production environments for faster API responses.

```bash
php artisan route:cache
```

## Contributing

Feel free to submit issues and pull requests. Contributions are welcome!

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.

---
