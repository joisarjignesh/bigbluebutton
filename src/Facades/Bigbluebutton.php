<?php

namespace JoisarJignesh\Bigbluebutton\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static array all()
 * @method static isMeetingRunning($arrayOtherWiseObject)
 * @method static getMeetingInfo($arrayOtherWiseObject)
 * @method static create($arrayOtherWiseObject)
 * @method static close($arrayOtherWiseObject)
 * @method static join($arrayOtherWiseObject)
 * @method static start($arrayOtherWiseObject)
 * @method static getRecordings($arrayOtherWiseObject)
 * @method static publishRecordings($arrayOtherWiseObject)
 * @method static deleteRecordings($arrayOtherWiseObject)
 * @method static make()
 * @method static initCreateMeeting(array $array)
 * @method static initCloseMeeting(array $array)
 * @method static initJoinMeeting(array $array)
 * @method static initIsMeetingRunning(array $array)
 * @method static initGetMeetingInfo(array $array)
 * @method static initGetRecordings(array $array)
 * @method static initDeleteRecordings(array $array)
 * @method static initStart(array $array)
 * @method static initPublishRecordings(array $array)
 * @method static getApiVersion()
 * @method static hooksCreate(array $array)
 * @method static hooksDestroy(array $array)
 * @method static server(string $serverName)
 * @method static isConnect()
 *
 * @see \JoisarJignesh\Bigbluebutton\Bbb
 */
class Bigbluebutton extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'Bigbluebutton';
    }
}
