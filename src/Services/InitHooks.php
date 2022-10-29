<?php

namespace JoisarJignesh\Bigbluebutton\Services;

use BigBlueButton\Parameters\HooksCreateParameters;
use BigBlueButton\Parameters\HooksDestroyParameters;

trait InitHooks
{
    /**
     * @param  array  $parameters
     *
     * require fields
     * callbackURL
     *
     * optional fields
     * meetingID
     * getRaw
     * @return HooksCreateParameters
     */
    public function initHooksCreate(array $parameters)
    {
        $parameters = Fluent($parameters);
        $hooksCreate = new HooksCreateParameters($parameters->get('callbackURL'));
        if (! empty($parameters->get('meetingID'))) {
            $hooksCreate->setMeetingID($parameters->meetingID);
        }
        $hooksCreate->setGetRaw($parameters->get('getRaw', false));

        return $hooksCreate;
    }

    /**
     * @param  mixed  $parameters
     * @return HooksDestroyParameters
     */
    public function initHooksDestroy($parameters)
    {
        $hooksID = '';
        if (is_array($parameters)) {
            $hooksID = Fluent($parameters)->get('hooksID');
        } else {
            $hooksID = $parameters;
        }

        return new HooksDestroyParameters($hooksID);
    }
}
