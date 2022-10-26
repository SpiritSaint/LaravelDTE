<?php

namespace SpiritSaint\LaravelDTE\Tests;

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Testing\Concerns\InteractsWithConsole;
use Illuminate\Foundation\Testing\Concerns\InteractsWithContainer;
use Illuminate\Routing\Router;
use SpiritSaint\LaravelDTE\ServiceProvider;

class TestCase extends \Orchestra\Testbench\TestCase
{
    use InteractsWithConsole, InteractsWithContainer;

    protected function setUp(): void
    {
        parent::setUp();
        $this->loadLaravelMigrations(['--database' => 'tests']);
        $this->artisan('migrate', ['--database' => 'tests'])->run();
    }

    /**
     * Get package providers.
     *
     * @param  Application  $app
     *
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [
            ServiceProvider::class,
        ];
    }

    /**
     * Get package aliases.
     *
     * @param Application $app
     *
     * @return array
     */
    protected function getPackageAliases($app)
    {
        return [
            //
        ];
    }

    /**
     * Define environment setup.
     *
     * @param Application $app
     *
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
        /** @var Router $router */
        $router = $app['router'];

        $app['config']->set('database.default', 'tests');
        $app['config']->set('database.connections.tests', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);
    }
}
