<?php

namespace JoisarJignesh\Bigbluebutton\Services;

use BigBlueButton\Parameters\CreateMeetingParameters;
use BigBlueButton\Parameters\EndMeetingParameters;
use BigBlueButton\Parameters\GetMeetingInfoParameters;
use BigBlueButton\Parameters\IsMeetingRunningParameters;
use BigBlueButton\Parameters\JoinMeetingParameters;
use Illuminate\Support\Str;

trait InitMeeting
{
    /**
     * @param  array  $parameters
     *
     * required fields
     * meetingID
     * meetingName
     * optional fields
     * moderatorPW
     * attendeePW
     * @return CreateMeetingParameters
     */
    public function initCreateMeeting(array $parameters)
    {
        $request = Fluent($parameters);
        $meetingParams = new CreateMeetingParameters(
            $request->get('meetingID', Str::uuid()),
            $request->get('meetingName', 'default meeting name '.Str::random(7))
        );
        $meetingParams->setModeratorPW($request->get('moderatorPW', Str::random(config('bigbluebutton.create.passwordLength', 8))));
        $meetingParams->setAttendeePW($request->get('attendeePW', Str::random(config('bigbluebutton.create.passwordLength', 8))));
        $meetingParams->setDuration($request->get('duration', config('bigbluebutton.create.duration', 0)));
        $meetingParams->setRecord($request->get('record', config('bigbluebutton.create.record', false)));
        $meetingParams->setMaxParticipants($request->get('maxParticipants', config('bigbluebutton.create.maxParticipants', 0)));
        if (! is_null($request->get('logoutUrl', config('bigbluebutton.create.logoutUrl', null)))) {
            $meetingParams->setLogoutURL($request->get('logoutUrl', config('bigbluebutton.create.logoutUrl', null)));
        }
        $meetingParams->setGuestPolicy(
            $request->get('guestPolicy', config('bigbluebutton.create.guestPolicy', 'ALWAYS_ACCEPT'))
        );
        if (! is_null($request->get('welcomeMessage', config('bigbluebutton.create.welcomeMessage', null)))) {
            $meetingParams->setWelcome(
                $request->get('welcomeMessage', config('bigbluebutton.create.welcomeMessage', null))
            );
        }
        if (! is_null($request->get('welcome', config('bigbluebutton.create.welcomeMessage', null)))) {
            $meetingParams->setWelcome(
                $request->get('welcome', config('bigbluebutton.create.welcomeMessage', null))
            );
        }
        $meetingParams->setDialNumber(
            $request->get('dialNumber', config('bigbluebutton.create.dialNumber', null))
        );

        if (! empty($request->get('voiceBridge'))) {
            $meetingParams->setVoiceBridge(
                $request->get('voiceBridge', null)
            );
        }

        $meetingParams->setBreakout(
            $request->get('isBreakout', config('bigbluebutton.create.isBreakout', false))
        );

        $meetingParams->setParentMeetingID(
            $request->get('parentMeetingID', '')
        );

        $meetingParams->setSequence(
            $request->get('sequence', rand(1, 10000))
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
        $meetingParams->setBannerText(
            $request->get('bannerText', config('bigbluebutton.create.bannerText', null))
        );
        $meetingParams->setBannerColor(
            $request->get('bannerColor', null)
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
        $meetingParams->setAllowModsToUnmuteUsers(
            $request->get('allowModsToUnmuteUsers', config('bigbluebutton.create.allowModsToUnmuteUsers', false))
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
        $meetingParams->setMeetingKeepEvents(
            $request->get('meetingKeepEvents', config('bigbluebutton.create.meetingKeepEvents', false))
        );
        $meetingParams->setEndWhenNoModerator(
            $request->get('endWhenNoModerator', config('bigbluebutton.create.endWhenNoModerator', false))
        );
        $meetingParams->setEndWhenNoModeratorDelayInMinutes(
            $request->get('endWhenNoModeratorDelayInMinutes', config('bigbluebutton.create.endWhenNoModeratorDelayInMinutes', 1))
        );
        $meetingParams->setMeetingLayout(
            $request->get('meetingLayout', config('bigbluebutton.create.meetingLayout', 'SMART_LAYOUT'))
        );
        $meetingParams->setLearningDashboardCleanupDelayInMinutes(
            $request->get('learningDashboardCleanupDelayInMinutes', config('bigbluebutton.create.learningDashboardCleanupDelayInMinutes', 2))
        );
        $meetingParams->setAllowModsToEjectCameras(
            $request->get('allowModsToEjectCameras', config('bigbluebutton.create.allowModsToEjectCameras', false))
        );
        $meetingParams->setAllowRequestsWithoutSession(
            $request->get('allowRequestsWithoutSession', config('bigbluebutton.create.allowRequestsWithoutSession', false))
        );
        $meetingParams->setUserCameraCap(
            $request->get('userCameraCap', config('bigbluebutton.create.userCameraCap', 3))
        );
        if (! is_null($request->get('endCallbackUrl', null))) {
            $meetingParams->setEndCallbackUrl($request->get('endCallbackUrl', null));
        }

        if (! is_null($request->get('bbb-recording-ready-url', null))) {
            $meetingParams->setRecordingReadyCallbackUrl($request->get('bbb-recording-ready-url', null));
        }

        $meetingParams->setFreeJoin($request->get('freeJoin', false));

        $presentation = (array) $request->get('presentation', null);
        foreach ($presentation as $item) {
            if (isset($item['fileName']) && ! empty($item['fileName'])) {
                if (isset($item['link']) && ! empty($item['link'])) {
                    $meetingParams->addPresentation(trim($item['link']), null, trim($item['fileName']));
                } elseif (isset($item['content']) && ! empty($item['content'])) {
                    $meetingParams->addPresentation(trim($item['fileName']), trim($item['content']), null);
                }
            }
        }

        $meta = (array) $request->get('meta', null);
        foreach ($meta as $key => $value) {
            $meetingParams->addMeta(trim($key), trim($value));
        }

        return $meetingParams;
    }

    /**
     * @param  array  $parameters
     *
     * required fields:
     * meetingID
     * moderatorPW close meeting must be there moderator password
     * @return EndMeetingParameters
     */
    public function initCloseMeeting(array $parameters)
    {
        $request = Fluent($parameters);

        return new EndMeetingParameters($request->meetingID, $request->moderatorPW);
    }

    /**
     * @param  array  $parameters
     *
     *  required fields
     *
     *  meetingID
     *  userName join by name
     *  password which role want to join
     * @return JoinMeetingParameters
     */
    public function initJoinMeeting(array $parameters)
    {
        $request = Fluent($parameters);
        $meetingParams = new JoinMeetingParameters($request->meetingID, $request->userName, $request->password);
        if (! empty($request->get('role'))) {
            $meetingParams->setRole($request->get('role'));
        }
        $meetingParams->setRedirect($request->get('redirect', config('bigbluebutton.join.redirect', true)));
        if (! is_null($request->get('userId'))) {
            $meetingParams->setUserID($request->get('userId'));
        }
        if (! is_null($request->get('userID'))) {
            $meetingParams->setUserID($request->get('userID'));
        }
        if (! empty($request->get('createTime'))) {
            $meetingParams->setCreateTime($request->get('createTime'));
        }
        if (! empty($request->get('defaultLayout'))) {
            $meetingParams->setDefaultLayout($request->get('defaultLayout'));
        }
        if (! empty($request->get('configToken'))) {
            $meetingParams->setConfigToken($request->get('configToken'));
        }
        if (! empty($request->get('webVoiceConf'))) {
            $meetingParams->setWebVoiceConf($request->get('webVoiceConf'));
        }
        if (! empty($request->get('avatarUrl'))) {
            $meetingParams->setAvatarURL($request->get('avatarUrl'));
        }
        if (! empty($request->get('clientUrl'))) {
            $meetingParams->setClientURL($request->get('clientUrl'));
        }
        if (! empty($request->get('guest'))) {
            $meetingParams->setGuest($request->get('guest'));
        }
        if (! empty($request->get('excludeFromDashboard'))) {
            $meetingParams->setExcludeFromDashboard($request->get('excludeFromDashboard'));
        }
        if ($request->customParameters && is_array($request->customParameters)) {
            foreach ($request->customParameters as $key => $value) {
                $meetingParams->addUserData($key, $value);
            }
        }

        return $meetingParams;
    }

    /**
     * @param  $parameters
     *
     * required fields
     * meetingID
     * @return IsMeetingRunningParameters
     */
    public function initIsMeetingRunning($parameters)
    {
        $meetingID = '';
        if (is_array($parameters)) {
            $meetingID = Fluent($parameters)->get('meetingID');
        } else {
            $meetingID = $parameters;
        }

        return new IsMeetingRunningParameters($meetingID);
    }

    /**
     * @param  $parameters
     *
     * required fields
     * meetingID
     * moderatorPW must be there moderator password
     * @return GetMeetingInfoParameters
     */
    public function initGetMeetingInfo($parameters)
    {
        $request = Fluent($parameters);

        return new GetMeetingInfoParameters($request->meetingID, $request->moderatorPW);
    }

    private function makeJoinMeetingArray($object, $parameters)
    {
        $pass['meetingID'] = $object->get('meetingID');
        $pass['password'] = $object->get('moderatorPW');
        if (isset($parameters['userName'])) {
            $pass['userName'] = $parameters['userName'];
        }
        if (isset($parameters['meetingName'])) {
            $pass['meetingName'] = $parameters['meetingName'];
        } else {
            $pass['meetingName'] = $object->get('meetingName');
        }
        if (isset($parameters['redirect'])) {
            $pass['redirect'] = $parameters['redirect'];
        }

        $exceptParameters = ['meetingID', 'moderatorPW', 'userName', 'meetingName', 'redirect'];
        if (is_array($parameters)) {
            foreach ($parameters as $key => $value) {
                if (! empty($key) && is_string($key) && ! empty($value) && ! in_array($key, $exceptParameters)) {
                    $pass[$key] = $value;
                }
            }
        }

        return $pass;
    }

    /**
     * @param  array  $parameters
     *
     * required fields
     * meetingID
     * meetingName
     * userName
     * attendeePW
     * moderatorPW
     * @return mixed
     */
    public function initStart(array $parameters)
    {
        if ($this->getMeetingInfo($parameters)->isEmpty()) {
            $object = $this->create($parameters);

            if (method_exists($object, 'isEmpty') && ! $object->isEmpty()) {
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
