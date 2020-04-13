<?php

namespace JoisarJignesh\Bigbluebutton;

use BigBlueButton\BigBlueButton;
use BigBlueButton\Parameters\CreateMeetingParameters;
use BigBlueButton\Parameters\DeleteRecordingsParameters;
use BigBlueButton\Parameters\EndMeetingParameters;
use BigBlueButton\Parameters\GetMeetingInfoParameters;
use BigBlueButton\Parameters\GetRecordingsParameters;
use BigBlueButton\Parameters\IsMeetingRunningParameters;
use BigBlueButton\Parameters\JoinMeetingParameters;
use JoisarJignesh\Bigbluebutton\Services\initMeeting;

class Bbb
{
    use initMeeting;

    private $response;
    /**
     * @var BigBlueButton
     */
    protected $bbb;

    protected $url;

    public function __construct(BigBlueButton $bbb)
    {
        $this->bbb = $bbb;
    }

    /**
     *  Return a list of all meetings.
     *
     * @return \Illuminate\Support\Collection
     */
    public function all()
    {
        $this->response = $this->bbb->getMeetings();
        $this->setUrl($this->bbb->getMeetingsUrl());
        if ($this->response->success()) {
            if (count($this->response->getRawXml()->meetings->meeting) > 0) {
                return collect(XmlToArray($this->response->getRawXml()->meetings)['meeting']);
            }
        }

        return collect([]);
    }

    /**
     * $meeting.
     *
     * @param $meeting
     *
     * @return mixed
     */
    public function create($meeting)
    {
        if (! $meeting instanceof CreateMeetingParameters) {
            $meeting = $this->initCreateMeeting($meeting);
        }

        $this->setUrl($this->bbb->getCreateMeetingUrl($meeting));
        $this->response = $this->bbb->createMeeting($meeting);
        if ($this->response->failed()) {
            return $this->response->getMessage();
        } else {
            return collect(XmlToArray($this->response->getRawXml()));
        }
    }

    /**
     * @param $meeting
     *
     * @return bool
     */
    public function isMeetingRunning($meeting)
    {
        if (! $meeting instanceof IsMeetingRunningParameters) {
            $meeting = $this->initIsMeetingRunning($meeting);
        }

        $this->setUrl($this->bbb->getIsMeetingRunningUrl($meeting));
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
     * @param $meeting
     *
     * @return string
     */
    public function join($meeting)
    {
        if (! $meeting instanceof JoinMeetingParameters) {
            $meeting = $this->initJoinMeeting($meeting);
        }

        $this->setUrl($this->bbb->getJoinMeetingURL($meeting));

        if ($meeting->isRedirect()) {
            return redirect()->to($this->bbb->getJoinMeetingURL($meeting));
        }

        return collect(XmlToArray($this->bbb->joinMeeting($meeting)->getRawXml()));
    }

    /**
     *  Returns information about the meeting.
     *
     * @param $meeting
     *
     * @return \Illuminate\Support\Collection
     */
    public function getMeetingInfo($meeting)
    {
        if (! $meeting instanceof GetMeetingInfoParameters) {
            $meeting = $this->initGetMeetingInfo($meeting);
        }

        $this->setUrl($this->bbb->getMeetingInfoUrl($meeting));
        $this->response = $this->bbb->getMeetingInfo($meeting);
        if ($this->response->success()) {
            return collect(XmlToArray($this->response->getRawXml()));
        }

        return collect([]);
    }

    public function start($parameters)
    {
        return $this->initStart($parameters);
    }

    /**
     *  Close meeting.
     *
     * @param  $meeting
     *
     * @return bool
     */
    public function close($meeting)
    {
        if (! $meeting instanceof EndMeetingParameters) {
            $meeting = $this->initCloseMeeting($meeting);
        }

        $this->setUrl($this->bbb->getEndMeetingURL($meeting));
        $this->response = $this->bbb->endMeeting($meeting);
        if ($this->response->success()) {
            return true;
        }

        return false;
    }

    /**
     * @param $recording
     *
     * @return \Illuminate\Support\Collection
     */
    public function getRecordings($recording)
    {
        if (! $recording instanceof GetRecordingsParameters) {
            $recording = $this->initGetRecordings($recording);
        }

        $this->setUrl($this->bbb->getRecordingsUrl($recording));
        $this->response = $this->bbb->getRecordings($recording);
        if (count($this->response->getRawXml()->recordings->recording) > 0) {
            return collect(XmlToArray($this->response->getRawXml()->recordings)['recording']);
        }

        return collect([]);
    }

    public function deleteRecordings($recording)
    {
        if (! $recording instanceof DeleteRecordingsParameters) {
            $recording = $this->initDeleteRecordings($recording);
        }

        $this->setUrl($this->bbb->getDeleteRecordingsUrl($recording));
        $this->response = $this->bbb->deleteRecordings($recording);

        return collect(XmlToArray($this->response->getRawXml()));
    }

    /**
     * @return mixed
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param mixed $url
     */
    private function setUrl($url): void
    {
        $this->url = $url;
    }
}
