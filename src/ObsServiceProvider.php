<?php

declare(strict_types=1);

namespace Zing\LaravelFlysystem\Obs;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\ServiceProvider;
use League\Flysystem\Filesystem;
use League\Flysystem\Visibility;
use Obs\ObsClient;
use Zing\Flysystem\Obs\ObsAdapter as LeagueObsAdapter;
use Zing\Flysystem\Obs\PortableVisibilityConverter;

class ObsServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Storage::extend('obs', function ($app, $config) {
            $root = $config['root'] ?? '';
            $options = $config['options'] ?? [];
            $portableVisibilityConverter = new PortableVisibilityConverter(
                $config['visibility'] ?? Visibility::PUBLIC
            );
            $obsClient = new ObsClient($config);
            $obsAdapter = new LeagueObsAdapter(
                $obsClient,
                $config['bucket'],
                $root,
                $portableVisibilityConverter,
                null,
                $options
            );

            return new ObsAdapter(new Filesystem($obsAdapter, $config), $obsAdapter, $config, $obsClient);
        });
    }
}
