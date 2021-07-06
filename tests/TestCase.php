<?php

declare(strict_types=1);

namespace Zing\LaravelFlysystem\Obs\Tests;

use Illuminate\Support\Facades\Config;
use Orchestra\Testbench\TestCase as BaseTestCase;
use Zing\LaravelFlysystem\Obs\ObsServiceProvider;

class TestCase extends BaseTestCase
{
    protected function getPackageProviders($app)
    {
        return [ObsServiceProvider::class];
    }

    protected function getEnvironmentSetUp($app): void
    {
        Config::set('filesystems.disks.obs', [
            'driver' => 'obs',
            'key' => '',
            'secret' => '',
            'bucket' => 'test',
            'endpoint' => 'obs.cn-east-3.myhuaweicloud.com',
            'url' => 'https://test-url',
            'temporary_url' => 'https://test-temporary-url',
            'bucket_endpoint' => true,
        ]);
    }
}
