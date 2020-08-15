<?php

namespace JoisarJignesh\Bigbluebutton\Tests;

use JoisarJignesh\Bigbluebutton\BigbluebuttonServiceProvider;
use JoisarJignesh\Bigbluebutton\Facades\Bigbluebutton;
use Orchestra\Testbench\TestCase;

class CloseMeetingTest extends TestCase
{
    protected function getPackageProviders($app)
    {
        return [BigbluebuttonServiceProvider::class];
    }

    protected function getApplicationAliases($app)
    {
        return Bigbluebutton::class;
    }

    public function testCloseMeetingParameters()
    {
        $meetingId = 'tamku';
        $password = 'test';
        $instance = Bigbluebutton::initCloseMeeting(['meetingID' => $meetingId, 'moderatorPW' => $password]);
        $this->assertInstanceOf(\BigBlueButton\Parameters\EndMeetingParameters::class, $instance);

        $this->assertEquals($meetingId, $instance->getMeetingId());
        $this->assertEquals($password, $instance->getPassword());
        $this->assertEquals('meetingID='.$meetingId.'&password='.$password, $instance->getHTTPQuery());
    }
}
