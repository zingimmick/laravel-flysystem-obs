<?php

declare(strict_types=1);

namespace Zing\LaravelFlysystem\Obs\Tests;

use Illuminate\Support\Facades\Config;
use Orchestra\Testbench\TestCase as BaseTestCase;
use Zing\LaravelFlysystem\Obs\ObsServiceProvider;

class TestCase extends BaseTestCase
{
    /**
     * @param mixed $app
     *
     * @return array<class-string<\Illuminate\Support\ServiceProvider>>
     */
    protected function getPackageProviders($app): array
    {
        return [ObsServiceProvider::class];
    }

    protected function getEnvironmentSetUp($app): void
    {
        Config::set('filesystems.disks.obs', [
            'driver' => 'obs',
            'key' => '',
            'secret' => '',
            'bucket' => 'your-bucket',
            'endpoint' => 'your-endpoint',
        ]);
        Config::set('filesystems.disks.obs-url', [
            'driver' => 'obs',
            'key' => '',
            'secret' => '',
            'bucket' => 'your-bucket',
            'endpoint' => 'your-endpoint',
            'url' => 'https://test-url',
        ]);
        Config::set('filesystems.disks.obs-temporary-url', [
            'driver' => 'obs',
            'key' => '',
            'secret' => '',
            'bucket' => 'your-bucket',
            'endpoint' => 'your-endpoint',
            'temporary_url' => 'https://test-temporary-url',
        ]);
        Config::set('filesystems.disks.obs-bucket-endpoint', [
            'driver' => 'obs',
            'key' => '',
            'secret' => '',
            'bucket' => 'your-bucket',
            'endpoint' => 'your-endpoint',
            'bucket_endpoint' => true,
        ]);
        Config::set('filesystems.disks.obs-is-cname', [
            'driver' => 'obs',
            'key' => '',
            'secret' => '',
            'bucket' => 'your-bucket',
            'endpoint' => 'your-endpoint',
            'is_cname' => true,
        ]);
    }
}
