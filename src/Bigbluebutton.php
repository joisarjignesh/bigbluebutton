<?php

namespace JoisarJignesh\Bigbluebutton;

use BigBlueButton\BigBlueButton as BigBlueButtonParent;
use BigBlueButton\Http\Transport\CurlTransport;
use BigBlueButton\Util\UrlBuilder;
use Illuminate\Support\Str;

class Bigbluebutton extends BigBlueButtonParent
{
    /**
     * Bigbluebutton constructor.
     * Allows to set url and secret as parameter, otherwise use values in env.
     *
     * @param $bbbServerBaseUrl API Base Url
     * @param $securitySecret API Server secret
     * @param  TransportInterface|null  $transport
     */
    public function __construct($bbbServerBaseUrl, $securitySecret, $transport = null)
    {
        $this->bbbServerBaseUrl = Str::finish(trim($bbbServerBaseUrl), '/');
        $this->securitySecret = trim($securitySecret);
        $this->urlBuilder = new UrlBuilder($this->securitySecret, $this->bbbServerBaseUrl);
        $this->transport = $transport ?? CurlTransport::createWithDefaultOptions();
    }
}
