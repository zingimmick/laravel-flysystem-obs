<?php

declare(strict_types=1);

namespace Zing\LaravelFlysystem\Obs;

use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\ServiceProvider;
use League\Flysystem\Filesystem;
use League\Flysystem\PathPrefixing\PathPrefixedAdapter;
use League\Flysystem\ReadOnly\ReadOnlyFilesystemAdapter;
use League\Flysystem\Visibility;
use Obs\ObsClient;
use Zing\Flysystem\Obs\ObsAdapter as Adapter;
use Zing\Flysystem\Obs\PortableVisibilityConverter;

class ObsServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Storage::extend('obs', static function ($app, $config): FilesystemAdapter {
            $root = $config['root'] ?? '';
            $options = $config['options'] ?? [];
            $portableVisibilityConverter = new PortableVisibilityConverter(
                $config['directory_visibility'] ?? $config['visibility'] ?? Visibility::PUBLIC
            );
            $config['bucket_endpoint'] ??= $config['is_cname'] ?? false;
            $config['token'] ??= $config['security_token'] ?? null;
            $config['is_cname'] = $config['bucket_endpoint'];
            $config['security_token'] = $config['token'];
            $options = array_merge(
                $options,
                Arr::only($config, ['url', 'temporary_url', 'endpoint', 'bucket_endpoint'])
            );
            $obsClient = new ObsClient($config);
            $obsAdapter = new Adapter(
                $obsClient,
                $config['bucket'],
                $root,
                $portableVisibilityConverter,
                null,
                $options
            );
            $adapter = $obsAdapter;
            if (($config['read-only'] ?? false) === true) {
                $adapter = new ReadOnlyFilesystemAdapter($adapter);
            }

            if (! empty($config['prefix'])) {
                $adapter = new PathPrefixedAdapter($adapter, $config['prefix']);
            }

            return new ObsAdapter(new Filesystem($adapter, $config), $obsAdapter, $config, $obsClient);
        });
    }
}
