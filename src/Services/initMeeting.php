<?php


namespace JoisarJignesh\Bigbluebutton\Services;


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
use BigBlueButton\Parameters\SetConfigXMLParameters;
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
        $meetingParams->setModeratorPassword($request->get('moderatorPW', Str::random(config('bigbluebutton.create.passwordLength', 8))));
        $meetingParams->setAttendeePassword($request->get('attendeePW', Str::random(config('bigbluebutton.create.passwordLength', 8))));
        $meetingParams->setDuration($request->get('duration', config('bigbluebutton.create.duration', 0)));
        $meetingParams->setRecord($request->get('record', config('bigbluebutton.create.record', false)));
        $meetingParams->setMaxParticipants($request->get('maxParticipants', config('bigbluebutton.create.maxParticipants', 0)));
        $meetingParams->setLogoutUrl($request->get('logoutUrl', config('bigbluebutton.create.logoutUrl', null)));
        $meetingParams->setWelcomeMessage(
            $request->get('welcomeMessage', config('bigbluebutton.create.welcomeMessage', null))
        );
        $meetingParams->setDialNumber(
            $request->get('dialNumber', config('bigbluebutton.create.dialNumber', null))
        );
        $meetingParams->setBreakout(
            $request->get('isBreakout', config('bigbluebutton.create.isBreakout', false))
        );
        $meetingParams->setModeratorOnlyMessage(
            $request->get('moderatorOnlyMessage', config('bigbluebutton.create.moderatorOnlyMessage', null))
        );
        $meetingParams->setAutoStartRecording(
            $request->get('autoStartRecording', config('bigbluebutton.create.autoStartRecording', false))
        );
        $meetingParams->setAllowStartStopRecording(
            $request->get('allowStartStopRecording', config('bigbluebutton.create.allowStartStopRecording', true))
        );
        $meetingParams->setWebcamsOnlyForModerator(
            $request->get('webcamsOnlyForModerator', config('bigbluebutton.create.webcamsOnlyForModerator', false))
        );
        $meetingParams->setLogo(
            $request->get('logo', config('bigbluebutton.create.logo', null))
        );
        $meetingParams->setCopyright(
            $request->get('copyright', config('bigbluebutton.create.copyright', null))
        );
        $meetingParams->setMuteOnStart(
            $request->get('muteOnStart', config('bigbluebutton.create.muteOnStart', false))
        );

        $meetingParams->setLockSettingsDisableCam(
            $request->get('lockSettingsDisableCam', config('bigbluebutton.create.lockSettingsDisableCam', false))
        );
        $meetingParams->setLockSettingsDisableMic(
            $request->get('lockSettingsDisableMic', config('bigbluebutton.create.lockSettingsDisableMic', false))
        );
        $meetingParams->setLockSettingsDisablePrivateChat(
            $request->get('lockSettingsDisablePrivateChat', config('bigbluebutton.create.lockSettingsDisablePrivateChat', false))
        );
        $meetingParams->setLockSettingsDisablePublicChat(
            $request->get('lockSettingsDisablePublicChat', config('bigbluebutton.create.lockSettingsDisablePublicChat', false))
        );
        $meetingParams->setLockSettingsDisableNote(
            $request->get('lockSettingsDisableNote', config('bigbluebutton.create.lockSettingsDisableNote', false))
        );
        $meetingParams->setLockSettingsLockedLayout(
            $request->get('lockSettingsLockedLayout', config('bigbluebutton.create.lockSettingsLockedLayout', false))
        );
        $meetingParams->setLockSettingsLockOnJoin(
            $request->get('lockSettingsLockOnJoin', config('bigbluebutton.create.lockSettingsLockOnJoin', false))
        );
        $meetingParams->setLockSettingsLockOnJoinConfigurable(
            $request->get('lockSettingsLockOnJoinConfigurable', config('bigbluebutton.create.lockSettingsLockOnJoinConfigurable', false))
        );

        if (!is_null($request->get('endCallbackUrl', null))) {
            $meetingParams->setEndCallbackUrl($request->get('endCallbackUrl', null));
        }

        if (!is_null($request->get('bbb-recording-ready-url', null))) {
            $meetingParams->setRecordingReadyCallbackUrl($request->get('bbb-recording-ready-url', null));
        }

        $meetingParams->setFreeJoin($request->get('freeJoin', false));

        $presentation = (array)$request->get('presentation', null);
        foreach ($presentation as $item) {
            if (isset($item['fileName']) && !empty($item['fileName'])) {
                if (isset($item['link']) && !empty($item['link'])) {
                    $meetingParams->addPresentation(trim($item['link']), null, trim($item['fileName']));
                } elseif (isset($item['content']) && !empty($item['content'])) {
                    $meetingParams->addPresentation(trim($item['fileName']), trim($item['content']), null);
                }
            }
        }

        $meta = (array)$request->get('meta', null);
        foreach ($meta as $key => $value) {
            $meetingParams->addMeta(trim($key), trim($value));
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
        $meetingParams->setRedirect($request->get('redirect', config('bigbluebutton.join.redirect', true)));
        $meetingParams->setJoinViaHtml5($request->get('joinViaHtml5', config('bigbluebutton.join.joinViaHtml5', true)));
        $meetingParams->setUserId($request->get('userId', null));
        if ($request->createTime) {
            $meetingParams->setCreationTime($request->createTime);
        }
        if ($request->configToken) {
            $meetingParams->setConfigToken($request->configToken);
        }
        if ($request->webVoiceConf) {
            $meetingParams->setWebVoiceConf($request->webVoiceConf);
        }
        if ($request->avatarUrl) {
            $meetingParams->setAvatarURL($request->avatarUrl);
        }
        if ($request->clientUrl) {
            $meetingParams->setClientURL($request->clientUrl);
        }

        return $meetingParams;
    }

    /*
     * required fields
     * meetingID
     */
    public function initIsMeetingRunning($parameters)
    {
        $meetingID = "";
        if (!is_array($parameters)) {
            $meetingID = $parameters;
        } else {
            $meetingID = Fluent($parameters)->get('meetingID');
        }

        return (new IsMeetingRunningParameters($meetingID));
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
     *
     * optional fields
     * meetingID
     * recordID
     * state
     */
    public function initGetRecordings(array $parameters)
    {
        $request = Fluent($parameters);
        $recordings = new GetRecordingsParameters();

        $recordings->setMeetingId(implode(',', (array)$request->get('meetingID')));
        $recordings->setRecordId(implode(',', (array)$request->get('recordID')));
        $recordings->setState($request->get('state', config('bigbluebutton.getRecordings.state')));

        return $recordings;
    }

    /**
     * @param array $parameters
     *
     * @return PublishRecordingsParameters
     */
    public function initPublishRecordings(array $parameters)
    {
        $request = Fluent($parameters);
        $recordings = new PublishRecordingsParameters(null, $request->get('publish', true));
        $recordings->setRecordingId(implode(',', (array)$request->get('recordID')));

        return $recordings;
    }

    /*
     * required fields
     * recordingID
     */
    public function initDeleteRecordings($recording)
    {
        $request = Fluent($recording);

        return (new DeleteRecordingsParameters(implode(',', (array)$request->get('recordID'))));
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

    /**
     * @param $parameters
     *
     * @return SetConfigXMLParameters
     */
    public function initSetConfigXml(array $parameters)
    {
        $parameters = Fluent($parameters);
        $configXml = new SetConfigXMLParameters($parameters->get('meetingID'));
        $rawXml = $parameters->xml;
        if (!$parameters->xml instanceof \SimpleXMLElement) {
            $rawXml = new \SimpleXMLElement($parameters->xml);
        }

        $configXml->setRawXml($rawXml);

        return $configXml;
    }

    /**
     * @param array $parameters
     *
     * @return HooksCreateParameters
     */
    public function initHooksCreate(array $parameters)
    {
        $parameters = Fluent($parameters);
        $hooksCreate = new HooksCreateParameters($parameters->get('callbackURL'));
        if ($parameters->meetingID) {
            $hooksCreate->setMeetingId($parameters->meetingID);
        }
        $hooksCreate->setGetRaw($parameters->get('getRaw', false));

        return $hooksCreate;
    }

    /**
     * @param array $parameters
     *
     * @return HooksDestroyParameters
     */
    public function initHooksDestroy(array $parameters)
    {
        $parameters = Fluent($parameters);
        $hooksDestroy = new HooksDestroyParameters($parameters->get('hooksID'));

        return $hooksDestroy;
    }

    /**
     * Check if connection to api can be established with the end point url and secret
     * @return array connection successful
     */
    private function initIsConnect()
    {
        if (!filter_var(config('bigbluebutton.BBB_SERVER_BASE_URL'), FILTER_VALIDATE_URL)) {
            return [
                'flag'    => false,
                'message' => 'invalid url'
            ];
        }

        try {
            $response = $this->bbb->isMeetingRunning(
                new IsMeetingRunningParameters('connection_check')
            );

            // url and secret working
            if ($response->success()) {
                return ['flag' => true];
            }

            // Checksum error - invalid secret
            if ($response->failed() && $response->getMessageKey() == "checksumError") {
                return [
                    'flag'    => false,
                    'message' => 'invalid secret key'
                ];
            }

            // HTTP exception or XML parse
        } catch (\Exception $e) {
            return [
                'flag'    => false,
                'message' => 'invalid url and secret key'
            ];
        }

        return [
            'flag'    => false,
            'message' => 'invalid url'
        ];
    }
}
