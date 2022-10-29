<?php

namespace JoisarJignesh\Bigbluebutton\Tests;

use JoisarJignesh\Bigbluebutton\BigbluebuttonServiceProvider;
use JoisarJignesh\Bigbluebutton\Facades\Bigbluebutton;
use Orchestra\Testbench\TestCase;

class IsMeetingRunningTest extends TestCase
{
    protected function getPackageProviders($app)
    {
        return [BigbluebuttonServiceProvider::class];
    }

    protected function getApplicationAliases($app)
    {
        return Bigbluebutton::class;
    }

    public function testIsMeetingRunningParameters()
    {
        $instance = Bigbluebutton::initIsMeetingRunning('tamku');
        $this->assertInstanceOf(\BigBlueButton\Parameters\IsMeetingRunningParameters::class, $instance);
        $this->assertEquals('tamku', $instance->getMeetingID());

        $instanceArray = Bigbluebutton::initIsMeetingRunning(['meetingID' => 'tamku1']);
        $this->assertInstanceOf(\BigBlueButton\Parameters\IsMeetingRunningParameters::class, $instanceArray);
        $this->assertEquals('tamku1', $instanceArray->getMeetingID());
        $meetingId = 'new1';
        $instanceArray->setMeetingID($meetingId);
        $this->assertEquals($meetingId, $instanceArray->getMeetingID());
        $this->assertEquals('meetingID='.$meetingId, $instanceArray->getHTTPQuery());
    }
}
