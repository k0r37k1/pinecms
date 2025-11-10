<?php

declare(strict_types=1);

namespace Tests\Unit\Services\Installer;

use App\Services\Installer\WebServerConfigGenerator;
use Tests\TestCase;

class WebServerConfigGeneratorTest extends TestCase
{
    private WebServerConfigGenerator $generator;

    protected function setUp(): void
    {
        parent::setUp();
        $this->generator = new WebServerConfigGenerator;
    }

    // Tests will be added in next tasks
}
