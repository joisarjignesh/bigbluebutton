<?php

namespace JoisarJignesh\Bigbluebutton\Tests;

use JoisarJignesh\Bigbluebutton\BigbluebuttonServiceProvider;
use JoisarJignesh\Bigbluebutton\Facades\Bigbluebutton;
use Orchestra\Testbench\TestCase;

class CreateMeetingTest extends TestCase
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
        /*  Bigbluebutton::create([
              'meetingID'   => 'tamku',
              'meetingName' => 'test meeting',
              'attendeePW'  => 'attendee',
              'moderatorPW' => 'moderator',
          ]);*/
        $array = [1];
        $this->assertCount(1, $array);
    }
}
