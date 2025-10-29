<?php

namespace Netreviews\Avisverifies\Helper;

class API
{
    public $msg = array();

    /**
     * Codifica el mensaje.
     *
     * @param $data
     * @return string
     */
    public function acEncodeBase64($data)
    {
        $sBase64 = base64_encode($data);
        return strtr($sBase64, '+/', '-_');
    }

    /**
     * Decodifica el mensaje.
     *
     * @param $sData
     * @return string
     */
    public function acDecodeBase64($sData)
    {
        $sBase64 = urldecode($sData);
        $sBase64 = strtr($sBase64, '-_', '+/');
        return base64_decode($sBase64);
    }

    /**
     * @param $message
     */
    public function construct($message)
    {
        if ($message) {
            $this->msg = json_decode($this->acDecodeBase64($message), true);
        }
    }

    /**
     * Verifica si un valor está vacío. Check for isset is essential, because we could have empty $msg.
     *
     * @param $index
     * @return object
     */
    public function msg($index)
    {
        return (isset($this->msg[$index])) ? $this->msg[$index] : null;
    }

}
