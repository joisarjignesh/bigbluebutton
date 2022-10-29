<?php

namespace JoisarJignesh\Bigbluebutton\Services;

use BigBlueButton\Parameters\DeleteRecordingsParameters;
use BigBlueButton\Parameters\GetRecordingsParameters;
use BigBlueButton\Parameters\PublishRecordingsParameters;
use BigBlueButton\Parameters\UpdateRecordingsParameters;

trait InitRecordings
{
    /**
     * @param  mixed  $parameters
     *
     * optional fields
     * meetingID
     * recordID
     * state
     * @return GetRecordingsParameters
     */
    public function initGetRecordings($parameters)
    {
        $request = Fluent($parameters);
        $recordings = new GetRecordingsParameters();

        $recordings->setMeetingID(implode(',', (array) $request->get('meetingID')));
        $recordings->setRecordID(implode(',', (array) $request->get('recordID')));
        $recordings->setState($request->get('state', config('bigbluebutton.getRecordings.state')));

        return $recordings;
    }

    /**
     * @param  mixed  $parameters
     *
     * required fields
     * recordID
     * @return PublishRecordingsParameters
     */
    public function initPublishRecordings($parameters)
    {
        $request = Fluent($parameters);

        return new PublishRecordingsParameters(
            implode(',', (array) $request->get('recordID')),
            $request->get('publish', true)
        );
    }

    /**
     * @param  mixed  $recording
     *
     * required fields
     * recordID
     * @return DeleteRecordingsParameters
     */
    public function initDeleteRecordings($recording)
    {
        $request = Fluent($recording);

        return new DeleteRecordingsParameters(implode(',', (array) $request->get('recordID')));
    }

    /**
     * @param  mixed  $recording
     *
     * required fields
     * recordID
     * @return UpdateRecordingsParameters
     */
    public function initUpdateRecordings($recording)
    {
        $request = Fluent($recording);

        return new UpdateRecordingsParameters(implode(',', (array) $request->get('recordID')));
    }
}
