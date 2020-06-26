<?php

namespace JoisarJignesh\Bigbluebutton\Tests;

use BigBlueButton\Parameters\DeleteRecordingsParameters;
use JoisarJignesh\Bigbluebutton\Facades\Bigbluebutton;
use Orchestra\Testbench\TestCase;
use JoisarJignesh\Bigbluebutton\BigbluebuttonServiceProvider;

class DeleteRecordingsTest extends TestCase
{

    protected function getPackageProviders($app)
    {
        return [BigbluebuttonServiceProvider::class];
    }

    protected function getApplicationAliases($app)
    {
        return Bigbluebutton::class;
    }

   public function testDeleteRecordringsParameters()
   {
        $deleteInstance = Bigbluebutton::initDeleteRecordings(['recordID' => 'dds']);
        $this->assertInstanceOf(DeleteRecordingsParameters::class,$deleteInstance);
   }
}
