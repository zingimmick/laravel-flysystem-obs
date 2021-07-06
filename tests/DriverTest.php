<?php

declare(strict_types=1);

namespace Zing\LaravelFlysystem\Obs\Tests;

use Illuminate\Support\Facades\Storage;
use Zing\Flysystem\Obs\ObsAdapter;

class DriverTest extends TestCase
{
    public function testDriverRegistered(): void
    {
        self::assertInstanceOf(ObsAdapter::class, Storage::disk('obs')->getDriver()->getAdapter());
    }

    public function testUrl(): void
    {
        self::assertStringStartsWith('https://test-url', Storage::disk('obs')->url('test'));
    }

    public function testTemporaryUrl(): void
    {
        self::assertStringStartsWith('https://test-temporary-url', Storage::disk('obs')->temporaryUrl('test', 10));
    }
}
