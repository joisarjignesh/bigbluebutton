<?php

namespace JoisarJignesh\Bigbluebutton;

use BigBlueButton\BigBlueButton as BigBlueButtonParent;
use BigBlueButton\Util\UrlBuilder;

class Bigbluebutton extends BigBlueButtonParent
{
    /**
     * Bigbluebutton constructor.
     * Allows to set url and secret as parameter, otherwise use values in env.
     * @param $bbbServerBaseUrl API Base Url
     * @param $securitySecret API Server secret
     */
    public function __construct($bbbServerBaseUrl, $securitySecret)
    {
        $this->securitySecret = $securitySecret;
        $this->bbbServerBaseUrl = $bbbServerBaseUrl;
        $this->urlBuilder = new UrlBuilder($this->securitySecret, $this->bbbServerBaseUrl);
    }
}
