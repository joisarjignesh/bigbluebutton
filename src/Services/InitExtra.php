<?php

namespace JoisarJignesh\Bigbluebutton\Services;

use BigBlueButton\Parameters\IsMeetingRunningParameters;

trait InitExtra
{
    /**
     * Check if connection to api can be established with the end point url and secret.
     *
     * @return array connection successful
     */
    private function initIsConnect()
    {
        if (! filter_var(config('bigbluebutton.BBB_SERVER_BASE_URL'), FILTER_VALIDATE_URL)) {
            return [
                'flag'    => false,
                'message' => 'invalid url',
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
            if ($response->failed() && $response->getMessageKey() == 'checksumError') {
                return [
                    'flag'    => false,
                    'message' => 'invalid secret key',
                ];
            }

            // HTTP exception or XML parse
        } catch (\Exception $e) {
            return [
                'flag'    => false,
                'message' => 'invalid url and secret key',
            ];
        }

        return [
            'flag'    => false,
            'message' => 'invalid url',
        ];
    }
}
