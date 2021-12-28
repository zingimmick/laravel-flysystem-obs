<?php

declare(strict_types=1);

namespace Zing\LaravelFlysystem\Obs;

use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\ServiceProvider;
use League\Flysystem\Filesystem;
use League\Flysystem\Visibility;
use Obs\ObsClient;
use Zing\Flysystem\Obs\ObsAdapter;
use Zing\Flysystem\Obs\PortableVisibilityConverter;

class ObsServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Storage::extend('obs', function ($app, $config): FilesystemAdapter {
            $root = $config['root'] ?? '';
            $options = $config['options'] ?? [];
            $portableVisibilityConverter = new PortableVisibilityConverter(
                $config['visibility'] ?? Visibility::PUBLIC
            );
            if (! isset($config['is_cname']) && isset($config['bucket_endpoint'])) {
                $config['is_cname'] = $config['bucket_endpoint'];
            }

            if (isset($config['is_cname']) && ! isset($config['bucket_endpoint'])) {
                $config['bucket_endpoint'] = $config['is_cname'];
            }

            $options = array_merge(
                $options,
                Arr::only($config, ['url', 'temporary_url', 'endpoint', 'bucket_endpoint'])
            );

            $obsClient = new ObsClient($config);
            $obsAdapter = new ObsAdapter(
                $obsClient,
                $config['bucket'],
                $root,
                $portableVisibilityConverter,
                null,
                $options
            );

            return new FilesystemAdapter(new Filesystem($obsAdapter, $config), $obsAdapter, $config);
        });
    }
}
