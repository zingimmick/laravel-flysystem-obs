# Laravel Flysystem OBS
<p align="center">
<a href="https://github.com/zingimmick/laravel-flysystem-obs/actions"><img src="https://github.com/zingimmick/laravel-flysystem-obs/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://codecov.io/gh/zingimmick/laravel-flysystem-obs"><img src="https://codecov.io/gh/zingimmick/laravel-flysystem-obs/branch/master/graph/badge.svg" alt="Code Coverage" /></a>
<a href="https://packagist.org/packages/zing/laravel-flysystem-obs"><img src="https://poser.pugx.org/zing/laravel-flysystem-obs/v/stable.svg" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/zing/laravel-flysystem-obs"><img src="https://poser.pugx.org/zing/laravel-flysystem-obs/downloads" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/zing/laravel-flysystem-obs"><img src="https://poser.pugx.org/zing/laravel-flysystem-obs/v/unstable.svg" alt="Latest Unstable Version"></a>
<a href="https://packagist.org/packages/zing/laravel-flysystem-obs"><img src="https://poser.pugx.org/zing/laravel-flysystem-obs/license" alt="License"></a>
</p>

> **Requires**
> - **[PHP 7.2+](https://php.net/releases/)**
> - **[Laravel 6.0+](https://php.net/releases/)**

## Version Information

| Version | Illuminate | PHP Version | Status                  |
|:--------|:-----------|:------------|:------------------------|
| 2.x     | 9.x        | >= 8.0      | Active support :rocket: |
| 1.x     | 6.x - 8.x  | >= 7.2      | Active support          |

Require Laravel Flysystem OBS using [Composer](https://getcomposer.org):

```bash
composer require zing/laravel-flysystem-obs
```

## Configuration

```php
return [
    // ...
    'disks' => [
        // ...
        'obs' => [
            'driver' => 'obs',
            'root' => '',
            'key' => env('OBS_KEY'),
            'secret' => env('OBS_SECRET'),
            'bucket' => env('OBS_BUCKET'),
            'endpoint' => env('OBS_ENDPOINT'),
            'bucket_endpoint' => env('OBS_BUCKET_ENDPOINT', false),
        ],
    ]
];
```

## License

Laravel Flysystem OBS is an open-sourced software licensed under the [MIT license](LICENSE).
