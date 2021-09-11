<?php

declare(strict_types=1);

namespace Zing\LaravelFlysystem\Obs\Tests;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use Obs\ObsClient;
use Zing\Flysystem\Obs\ObsAdapter;

class DriverTest extends TestCase
{
    public function testDriverRegistered(): void
    {
        self::assertInstanceOf(ObsAdapter::class, Storage::disk('obs')->getDriver()->getAdapter());
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

    private function supportIsCname(): bool
    {
        return version_compare(ObsClient::SDK_VERSION, '3.21.6', '>=');
    }

    public function testIsCname(): void
    {
        if (! $this->supportIsCname()) {
            self::markTestSkipped('Option `is_cname` not supported.');
        }

        self::assertStringStartsWith(
            'https://your-endpoint',
            Storage::disk('obs-bucket-endpoint')->temporaryUrl('test', Carbon::now()->addMinutes())
        );
        self::assertStringStartsWith(
            'https://your-endpoint',
            Storage::disk('obs-is-cname')->temporaryUrl('test', Carbon::now()->addMinutes())
        );
    }
}
