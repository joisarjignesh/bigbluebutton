<?php


namespace JoisarJignesh\Bigbluebutton\Services;


use BigBlueButton\Parameters\CreateMeetingParameters;
use BigBlueButton\Parameters\DeleteRecordingsParameters;
use BigBlueButton\Parameters\EndMeetingParameters;
use BigBlueButton\Parameters\GetMeetingInfoParameters;
use BigBlueButton\Parameters\GetRecordingsParameters;
use BigBlueButton\Parameters\IsMeetingRunningParameters;
use BigBlueButton\Parameters\JoinMeetingParameters;
use Illuminate\Support\Str;

trait initMeeting
{

    /*
     * required fields
     * meetingID
     * meetingName
     *
     */
    public function initCreateMeeting(array $parameters)
    {
        $request = Fluent($parameters);
        $meetingParams = new CreateMeetingParameters($request->meetingID, $request->meetingName);
        if ($request->duration) {
            $meetingParams->setDuration($request->duration);
        }

        $meetingParams->setModeratorPassword($request->get('moderatorPW', Str::random(10)));
        $meetingParams->setAttendeePassword($request->get('attendeePW', Str::random(10)));

        if ($request->record) {
            $meetingParams->setRecord(true);
            $meetingParams->setAllowStartStopRecording(true);
            $meetingParams->setAutoStartRecording(true);
        }

        if ($request->maxParticipants) {
            $meetingParams->setMaxParticipants($request->maxParticipants);
        }

        if ($request->logoutUrl) {
            $meetingParams->setLogoutUrl($request->logoutUrl);
        }


        return $meetingParams;
    }

    /*
     * required fields:
     * meetingID
     * moderatorPW close meeting must be there moderator password
     */
    public function initCloseMeeting(array $parameters)
    {
        $request = Fluent($parameters);

        return (new EndMeetingParameters($request->meetingID, $request->moderatorPW));
    }

    /*
     *  required fields
     *
     *  meetingID
     *  userName join by name
     *  password which role want to join
     */
    public function initJoinMeeting(array $parameters)
    {
        $request = Fluent($parameters);
        $meetingParams = new JoinMeetingParameters($request->meetingID, $request->userName, $request->password);
        $meetingParams->setRedirect($request->get('redirect', true));
        $meetingParams->setJoinViaHtml5($request->get('joinViaHtml5', true));


        if ($request->userId) {
            $meetingParams->setUserId($request->userId);
        }

        return $meetingParams;
    }

    /*
     * required fields
     * meetingID
     */
    public function initIsMeetingRunning(array $parameters)
    {
        $request = Fluent($parameters);

        return (new IsMeetingRunningParameters($request->meetingID));
    }

    /*
     * required fields
     * meetingID
     * moderatorPW must be there moderator password
     */
    public function initGetMeetingInfo($parameters)
    {
        $request = Fluent($parameters);

        return (new GetMeetingInfoParameters($request->meetingID, $request->moderatorPW));
    }

    /*
     * required fields
     * meetingID
     *
     * optional fields
     * recordID
     * state
     */
    public function initGetRecordings(array $parameters)
    {
        $request = Fluent($parameters);

        $recordings = new GetRecordingsParameters();
        $recordings->setMeetingId($request->meetingID);
        if ($request->recordID) {
            $recordings->setRecordId($request->recordID);
        }
        if ($request->state) {
            $recordings->setState($request->state);
        }

        return $recordings;
    }

    /*
     * required fields
     * recordingID
     */
    public function initDeleteRecordings($recording)
    {
        $request = Fluent($recording);

        return (new DeleteRecordingsParameters($request->recordingID));
    }

    private function makeJoinMeetingArray($object, $parameters)
    {
        $pass['meetingID'] = $object->get('meetingID');
        $pass['password'] = $object->get('moderatorPW');
        if (isset($parameters['userName'])) {
            $pass['userName'] = $parameters['userName'];
        }
        $pass['meetingName'] = $object->get('meetingName');
        if (isset($parameters['redirect'])) {
            $pass['redirect'] = $parameters['redirect'];
        }

        return $pass;
    }

    /*
     * required fields
     * meetingID
     * meetingName
     * userName
     * attendeePW
     * moderatorPW
     * redirect
     */
    public function initStart(array $parameters)
    {
        if ($this->getMeetingInfo($parameters)->isEmpty()) {
            $object = $this->create($parameters);
            if (method_exists($object, 'isEmpty') && !$object->isEmpty()) {
                return $this->join($this->makeJoinMeetingArray($object, $parameters));
            }
        } else {
            if (isset($parameters['moderatorPW'])) {
                $parameters['password'] = trim($parameters['moderatorPW']);
            }

            return $this->join($parameters);
        }
    }


}
