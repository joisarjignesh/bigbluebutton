<?php

namespace JoisarJignesh\Bigbluebutton\Tests;

use JoisarJignesh\Bigbluebutton\Facades\Bigbluebutton;
use Orchestra\Testbench\TestCase;
use JoisarJignesh\Bigbluebutton\BigbluebuttonServiceProvider;

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
        $this->assertEquals('tamku', $instance->getMeetingId());

        $instanceArray = Bigbluebutton::initIsMeetingRunning(['meetingID' => 'tamku1']);
        $this->assertInstanceOf(\BigBlueButton\Parameters\IsMeetingRunningParameters::class, $instanceArray);
        $this->assertEquals('tamku1', $instanceArray->getMeetingId());
        $meetingId = 'new1';
        $instanceArray->setMeetingId($meetingId);
        $this->assertEquals($meetingId, $instanceArray->getMeetingId());
        $this->assertEquals('meetingID=' . $meetingId, $instanceArray->getHTTPQuery());
    }
}
