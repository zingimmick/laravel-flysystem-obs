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
}
