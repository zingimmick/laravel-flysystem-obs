<?php

declare(strict_types=1);

namespace Zing\LaravelFlysystem\Obs\Tests;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use League\Flysystem\UnableToWriteFile;
use Zing\Flysystem\Obs\ObsAdapter;

/**
 * @internal
 */
final class DriverTest extends TestCase
{
    public function testDriverRegistered(): void
    {
        self::assertInstanceOf(ObsAdapter::class, Storage::disk('obs')->getAdapter());
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
        self::assertStringStartsWith(
            'https://your-endpoint',
            Storage::disk('obs-bucket-endpoint')->temporaryUrl('test', Carbon::now()->addMinutes())
        );
        self::assertStringStartsWith(
            'https://your-endpoint',
            Storage::disk('obs-is-cname')->temporaryUrl('test', Carbon::now()->addMinutes())
        );
    }

    public function testReadOnly(): void
    {
        $this->expectException(UnableToWriteFile::class);
        Storage::disk('obs-read-only')->write('test', 'test');
    }

    public function testPrefix(): void
    {
        self::assertSame(
            'https://your-bucket.your-endpoint/root/prefix/test',
            Storage::disk('obs-prefix-url')->url('test')
        );
        self::assertStringStartsWith(
            'https://your-bucket.your-endpoint/root/prefix/test',
            Storage::disk('obs-prefix-url')->temporaryUrl('test', Carbon::now()->addMinutes())
        );
    }

    public function testReadOnlyAndPrefix(): void
    {
        self::assertSame(
            'https://your-bucket.your-endpoint/root/prefix/test',
            Storage::disk('obs-read-only-and-prefix-url')->url('test')
        );
        self::assertStringStartsWith(
            'https://your-bucket.your-endpoint/root/prefix/test',
            Storage::disk('obs-read-only-and-prefix-url')->temporaryUrl('test', Carbon::now()->addMinutes())
        );
        $this->expectException(UnableToWriteFile::class);
        Storage::disk('obs-read-only-and-prefix-url')->write('test', 'test');
    }

    public function testTemporaryUploadUrl(): void
    {
        $now = Carbon::now()->addMinutes();
        $data = Storage::disk('obs-temporary-url')->temporaryUploadUrl('test', $now);
        self::assertStringStartsWith(
            sprintf('https://test-temporary-url/test?AccessKeyId&Expires=%d&Signature=', $now->getTimestamp()),
            $data['url']
        );
        self::assertSame([
            'Host' => 'your-bucket.your-endpoint',
        ], $data['headers']);
    }
}
