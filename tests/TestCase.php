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

    protected function getEnvironmentSetUp($app)
    {
        Config::set('filesystems.disks.obs', [
            'driver' => 'obs',
            'key' => '',
            'secret' => '',
            'bucket'=>'',
            'security_token' => '',
            'endpoint' => 'x',
            'signature' => 'v2',
            'path_style' => '',
            'region' => '',
            'ssl_verify' => '',
            'ssl.certificate_authority' => '',
            'max_retry_count' => '',
            'timeout' => '',
            'socket_timeout' => '',
            'connect_timeout' => '',
            'chunk_size' => '',
            'exception_response_mode' => '']
        );
    }
}
