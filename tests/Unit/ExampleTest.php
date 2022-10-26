<?php

namespace SpiritSaint\LaravelDTE\Tests\Unit;

use SpiritSaint\LaravelDTE\Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * @return void
     */
    public function testTrue()
    {
        $this->assertTrue(true);
    }

    /**
     * @return void
     */
    public function testFalse()
    {
        $this->assertFalse(false);
    }
}