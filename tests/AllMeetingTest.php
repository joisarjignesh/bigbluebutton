<?php

namespace JoisarJignesh\Bigbluebutton\Tests;

use JoisarJignesh\Bigbluebutton\Facades\Bigbluebutton;
use Orchestra\Testbench\TestCase;
use JoisarJignesh\Bigbluebutton\BigbluebuttonServiceProvider;

class AllMeetingTest extends TestCase
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
        $this->assertCount(count(Bigbluebutton::all()), 1);
    }
}
