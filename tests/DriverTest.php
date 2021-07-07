<?php

declare(strict_types=1);

namespace Zing\LaravelFlysystem\Obs\Tests;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;

class DriverTest extends TestCase
{
    public function testDriverRegistered(): void
    {
        self::assertInstanceOf(\Zing\LaravelFlysystem\Obs\ObsAdapter::class, Storage::disk('obs'));
    }

    public function testUrl(): void
    {
        self::assertStringStartsWith('https://test-url', Storage::disk('obs')->url('test'));
    }

    public function testTemporaryUrl(): void
    {
        self::assertStringStartsWith(
            'https://test-temporary-url',
            Storage::disk('obs')->temporaryUrl('test', Carbon::now()->addMinutes())
        );
    }
}
