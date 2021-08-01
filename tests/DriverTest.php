<?php

declare(strict_types=1);

namespace Zing\LaravelFlysystem\Obs\Tests;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use Obs\ObsClient;

class DriverTest extends TestCase
{
    public function testDriverRegistered(): void
    {
        self::assertInstanceOf(\Zing\LaravelFlysystem\Obs\ObsAdapter::class, Storage::disk('obs'));
    }

    public function testUrl(): void
    {
        self::assertStringStartsWith('https://test-url', Storage::disk('obs-url')->url('test'));
    }

    public function testTemporaryUrl(): void
    {
        self::assertStringStartsWith(
            'https://test-temporary-url',
            Storage::disk('obs-temporary-url')->temporaryUrl('test', Carbon::now()->addMinutes())
        );
    }

    public function testBucketEndpoint(): void
    {
        self::assertStringStartsWith('https://your-endpoint', Storage::disk('obs-bucket-endpoint')->url('test'));
    }

    public function testIsCname(): void
    {
        if (version_compare(ObsClient::SDK_VERSION, '3.21.6') < 0) {
            self::markTestSkipped('Option `is_cname` not supported.');
        }
        self::assertStringStartsWith(
            'https://your-endpoint',
            Storage::disk('obs-bucket-endpoint')->temporaryUrl('test', Carbon::now()->addMinutes())
        );
    }
}
