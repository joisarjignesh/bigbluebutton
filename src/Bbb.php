<?php

namespace JoisarJignesh\Bigbluebutton;

use BigBlueButton\BigBlueButton;
use BigBlueButton\Parameters\CreateMeetingParameters;
use BigBlueButton\Parameters\DeleteRecordingsParameters;
use BigBlueButton\Parameters\EndMeetingParameters;
use BigBlueButton\Parameters\GetMeetingInfoParameters;
use BigBlueButton\Parameters\GetRecordingsParameters;
use BigBlueButton\Parameters\HooksCreateParameters;
use BigBlueButton\Parameters\HooksDestroyParameters;
use BigBlueButton\Parameters\IsMeetingRunningParameters;
use BigBlueButton\Parameters\JoinMeetingParameters;
use BigBlueButton\Parameters\PublishRecordingsParameters;
use BigBlueButton\Parameters\UpdateRecordingsParameters;
use JoisarJignesh\Bigbluebutton\Bigbluebutton as BigBlueButtonServer;
use JoisarJignesh\Bigbluebutton\Services\InitHooks;
use JoisarJignesh\Bigbluebutton\Services\InitMeeting;
use JoisarJignesh\Bigbluebutton\Services\InitRecordings;

class Bbb
{
    use InitMeeting,
        InitRecordings,
        InitHooks;

    /**
     * @var
     */
    private $response;
    /**
     * @var BigBlueButton
     */
    protected $bbb;

    /**
     * Bbb constructor.
     *
     * @param  BigBlueButton  $bbb
     */
    public function __construct(BigBlueButton $bbb)
    {
        $this->bbb = $bbb;
    }

    /**
     * for specific server instance.
     *
     * @param  $serverName
     * @return Bbb
     *
     * @throws \Exception
     */
    public function server($serverName)
    {
        if (is_null(config("bigbluebutton.servers.{$serverName}", null))) {
            throw new \Exception("Could not found {$serverName} server configuration in config file");
        }

        return new self(
            new BigBlueButtonServer(
                config("bigbluebutton.servers.{$serverName}.BBB_SERVER_BASE_URL"),
                config("bigbluebutton.servers.{$serverName}.BBB_SECURITY_SALT")
            )
        );
    }

    /**
     * @return self
     */
    public function make()
    {
        return $this;
    }

    /**
     * @return BigBlueButton
     */
    public function source()
    {
        return $this->bbb;
    }

    /**
     * check url and secret is working.
     *
     * @return bool
     */
    public function isConnect()
    {
        return $this->bbb->isConnectionWorking();
    }

    /**
     *  Return a list of all meetings.
     *
     * @return \Illuminate\Support\Collection
     */
    public function all()
    {
        $this->response = $this->bbb->getMeetings();
        if ($this->response->success()) {
            if (! is_null($this->response->getRawXml()->meetings->meeting) && count($this->response->getRawXml()->meetings->meeting) > 0) {
                $meetings = [];
                foreach ($this->response->getRawXml()->meetings->meeting as $meeting) {
                    $meetings[] = XmlToArray($meeting);
                }

                return collect(XmlToArray($meetings));
            }
        }

        return collect([]);
    }

    /**
     * $meeting.
     *
     * @param  $meeting
     *
     * required fields
     * meetingID
     * meetingName
     * @return mixed
     */
    public function create($meeting)
    {
        if (! $meeting instanceof CreateMeetingParameters) {
            $meeting = $this->initCreateMeeting($meeting);
        }

        $this->response = $this->bbb->createMeeting($meeting);
        if ($this->response->failed()) {
            return $this->response->getMessage();
        } else {
            return collect(XmlToArray($this->response->getRawXml()));
        }
    }

    /**
     * @param  $meeting
     *
     * required fields:
     * meetingID
     * @return bool
     */
    public function isMeetingRunning($meeting)
    {
        if (! $meeting instanceof IsMeetingRunningParameters) {
            $meeting = $this->initIsMeetingRunning($meeting);
        }

        $this->response = $this->bbb->isMeetingRunning($meeting);
        if ($this->response->success()) {
            $response = XmlToArray($this->response->getRawXml());
            if (isset($response['running']) && $response['running'] == 'true') {
                return true;
            }
        }

        return false;
    }

    /**
     *  Join meeting.
     *
     * @param  $meeting
     *                   required fields
     *
     *  meetingID
     *  userName join by name
     *  password which role want to join
     * @return string
     */
    public function join($meeting)
    {
        if (! $meeting instanceof JoinMeetingParameters) {
            $meeting = $this->initJoinMeeting($meeting);
        }

        if ($meeting->isRedirect()) {
            return $this->bbb->getJoinMeetingURL($meeting);
        }

        return $this->bbb->joinMeeting($meeting)->getUrl();
    }

    /**
     *  Returns information about the meeting.
     *
     * @param  $meeting
     *                   required fields
     *                   meetingID
     *                   moderatorPW must be there moderator password
     * @return \Illuminate\Support\Collection
     */
    public function getMeetingInfo($meeting)
    {
        if (! $meeting instanceof GetMeetingInfoParameters) {
            $meeting = $this->initGetMeetingInfo($meeting);
        }

        $this->response = $this->bbb->getMeetingInfo($meeting);
        if ($this->response->success()) {
            return collect(XmlToArray($this->response->getRawXml()));
        }

        return collect([]);
    }

    /**
     * @param  $parameters
     *
     * required fields
     * meetingID
     * meetingName
     * userName
     * attendeePW
     * moderatorPW
     * @return mixed
     */
    public function start($parameters)
    {
        return $this->initStart($parameters);
    }

    /**
     *  Close meeting.
     *
     * @param  $meeting
     *                   required fields:
     *                   meetingID
     *                   moderatorPW close meeting must be there moderator password
     * @return bool
     */
    public function close($meeting)
    {
        if (! $meeting instanceof EndMeetingParameters) {
            $meeting = $this->initCloseMeeting($meeting);
        }

        $this->response = $this->bbb->endMeeting($meeting);
        if ($this->response->success()) {
            return true;
        }

        return false;
    }

    /**
     * @param  $recording
     *                     required fields
     *                     meetingID
     *
     * optional fields
     * recordID
     * state
     * @return \Illuminate\Support\Collection
     */
    public function getRecordings($recording)
    {
        if (! $recording instanceof GetRecordingsParameters) {
            $recording = $this->initGetRecordings($recording);
        }

        $this->response = $this->bbb->getRecordings($recording);
        if ($this->response->success() && ! is_null($this->response->getRawXml()->recordings->recording) && count($this->response->getRawXml()->recordings->recording) > 0) {
            $recordings = [];
            foreach ($this->response->getRawXml()->recordings->recording as $r) {
                $recordings[] = XmlToArray($r);
            }

            return collect($recordings);
        }

        return collect([]);
    }

    /**
     * @param  $recording
     *                     recordID as string(separated by comma)
     *                     publish as bool
     * @return bool
     */
    public function publishRecordings($recording)
    {
        if (! $recording instanceof PublishRecordingsParameters) {
            $recording = $this->initPublishRecordings($recording);
        }

        $this->response = $this->bbb->publishRecordings($recording);
        if ($this->response->success()) {
            $response = XmlToArray($this->response->getRawXml());
            if (isset($response['published']) && $response['published'] == 'true') {
                return true;
            }
        }

        return false;
    }

    /**
     * @param  $recording
     *
     * required fields
     * recordingID
     * @return \Illuminate\Support\Collection
     */
    public function deleteRecordings($recording)
    {
        if (! $recording instanceof DeleteRecordingsParameters) {
            $recording = $this->initDeleteRecordings($recording);
        }

        $this->response = $this->bbb->deleteRecordings($recording);

        return collect(XmlToArray($this->response->getRawXml()));
    }

    /**
     * @param  $recording
     *
     * required fields
     * recordingID
     * @return \Illuminate\Support\Collection
     */
    public function updateRecordings($recording)
    {
        if (! $recording instanceof UpdateRecordingsParameters) {
            $recording = $this->initUpdateRecordings($recording);
        }

        $this->response = $this->bbb->updateRecordings($recording);

        return collect(XmlToArray($this->response->getRawXml()));
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function getApiVersion()
    {
        $this->response = $this->bbb->getApiVersion();

        return collect(XmlToArray($this->response->getRawXml()));
    }

    /**
     * @param  $hooks
     * @return \Illuminate\Support\Collection
     */
    public function hooksCreate($hooks)
    {
        if (! $hooks instanceof HooksCreateParameters) {
            $hooks = $this->initHooksCreate($hooks);
        }

        $this->response = $this->bbb->hooksCreate($hooks);

        return collect(XmlToArray($this->response->getRawXml()));
    }

    /**
     * @param  $hooks
     * @return \Illuminate\Support\Collection
     */
    public function hooksDestroy($hooks)
    {
        if (! $hooks instanceof HooksDestroyParameters) {
            $hooks = $this->initHooksDestroy($hooks);
        }

        $this->response = $this->bbb->hooksDestroy($hooks);

        return collect(XmlToArray($this->response->getRawXml()));
    }
}
