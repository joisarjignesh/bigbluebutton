<?php

namespace JoisarJignesh\Bigbluebutton\Tests;

use JoisarJignesh\Bigbluebutton\Facades\Bigbluebutton;
use Orchestra\Testbench\TestCase;
use JoisarJignesh\Bigbluebutton\BigbluebuttonServiceProvider;

class StartMeetingTest extends TestCase
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
