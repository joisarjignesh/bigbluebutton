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
    public function initCreateMeeting(array $parameters)
    {
        $request = Fluent($parameters);
        $meetingParams = new CreateMeetingParameters($request->meetingID, $request->meetingName);
        if ($request->duration) {
            $meetingParams->setDuration($request->duration);
        }

        $meetingParams->setModeratorPassword($request->get('moderatorPW', Str::random(10)));
        $meetingParams->setAttendeePassword($request->get('attendeePW', Str::random(10)));

        if ($request->maxParticipants) {
            $meetingParams->setMaxParticipants($request->maxParticipants);
        }

        return $meetingParams;
    }

    public function initCloseMeeting(array $parameters)
    {
        $request = Fluent($parameters);

        return new EndMeetingParameters($request->meetingID, $request->password);
    }

    public function initJoinMeeting(array $parameters)
    {
        $request = Fluent($parameters);
        $meetingParams = new JoinMeetingParameters($request->meetingID, $request->meetingName, $request->password);
        $meetingParams->setRedirect($request->get('redirect', true));
        $meetingParams->setJoinViaHtml5($request->get('joinViaHtml5', true));

        if ($request->userId) {
            $meetingParams->setUserId($request->userId);
        }

        return $meetingParams;
    }

    public function initIsMeetingRunning(array $parameters)
    {
        $request = Fluent($parameters);

        return new IsMeetingRunningParameters($request->meetingID);
    }

    public function initGetMeetingInfo($parameters)
    {
        $request = Fluent($parameters);

        return new GetMeetingInfoParameters($request->meetingID, $request->password);
    }

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

    public function initDeleteRecordings($recording)
    {
        $request = Fluent($recording);

        return new DeleteRecordingsParameters($request->recordingID);
    }

    public function initStart(array $parameters)
    {
        if ($this->getMeetingInfo($parameters)->isEmpty()) {
            return $this->create($parameters);
        } else {
            return $this->join($parameters);
        }
    }
}
