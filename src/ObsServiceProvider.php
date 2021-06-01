<?php

declare(strict_types=1);

namespace Zing\LaravelFlysystem\Obs;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\ServiceProvider;
use League\Flysystem\Filesystem;
use Obs\ObsClient;
use Zing\Flysystem\Obs\ObsAdapter;
use Zing\Flysystem\Obs\Plugins\FileUrl;
use Zing\Flysystem\Obs\Plugins\Kernel;
use Zing\Flysystem\Obs\Plugins\SetBucket;
use Zing\Flysystem\Obs\Plugins\SignatureConfig;
use Zing\Flysystem\Obs\Plugins\SignUrl;
use Zing\Flysystem\Obs\Plugins\TemporaryUrl;

class ObsServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Storage::extend('obs', function ($app, $config) {
            $root = $config['root'] ?? null;
            $options = $config['options'] ?? [];
            $adapter = new ObsAdapter(
                new ObsClient($config),
                $config['endpoint'],
                $config['bucket'],
                $root,
                $options
            );

            $filesystem = new Filesystem($adapter, $config);

            $filesystem->addPlugin(new FileUrl());
            $filesystem->addPlugin(new SignUrl());
            $filesystem->addPlugin(new TemporaryUrl());
            $filesystem->addPlugin(new SignatureConfig());
            $filesystem->addPlugin(new SetBucket());
            $filesystem->addPlugin(new Kernel());

            return $filesystem;
        });
    }
}
