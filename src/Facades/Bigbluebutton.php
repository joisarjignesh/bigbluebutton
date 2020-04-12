<?php

namespace JoisarJignesh\Bigbluebutton\Facades;

use Illuminate\Support\Facades\Facade;


/**
 * @method static array all()
 * @method static isMeetingRunning(array $array)
 * @method static closeMeeting(array $array)
 * @method static getMeetingInfo(array $array)
 * @method static create(array $array)
 * @method static close(array $array)
 * @method static join(array $array)
 * @method static start(array $array)
 * @method static getRecordings(array $array)
 * @method static deleteRecordings(array $array)
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
