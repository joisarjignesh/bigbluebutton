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
 * @method static deleteRecordings($arrayOtherWiseObject)
 * @method static make()
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
