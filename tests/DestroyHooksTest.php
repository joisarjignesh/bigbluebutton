<?php

namespace JoisarJignesh\Bigbluebutton\Tests;

use JoisarJignesh\Bigbluebutton\BigbluebuttonServiceProvider;
use JoisarJignesh\Bigbluebutton\Facades\Bigbluebutton;
use Orchestra\Testbench\TestCase;

class DestroyHooksTest extends TestCase
{
    protected function getPackageProviders($app)
    {
        return [BigbluebuttonServiceProvider::class];
    }

    protected function getApplicationAliases($app)
    {
        return Bigbluebutton::class;
    }

    /** @test */
    public function true_is_true()
    {
        $array = [1];
        $this->assertCount(1, $array);
    }
}
