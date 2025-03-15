<?php

namespace Toolkito\Larasap\Tests;

use PHPUnit\Framework\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Facade;
use Illuminate\Container\Container;

abstract class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        
        // Create a new container instance
        $container = new Container;
        
        // Set the container as the application instance
        Facade::setFacadeApplication($container);
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        
        // Clear the facade application
        Facade::setFacadeApplication(null);
    }
} 