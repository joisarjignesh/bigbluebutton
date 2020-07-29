<?php


namespace JoisarJignesh\Bigbluebutton\Services;


use BigBlueButton\Parameters\SetConfigXMLParameters;

trait initConfig
{
    /**
     * @param $parameters
     *
     * require fields
     * xml
     * meetingID
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
}
