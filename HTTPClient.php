<?php

namespace Caspla;

interface HTTPClient
{
    /**
     * send post request
     *
     */
    public function post($url, $data, $headers);
    /**
     * send post request
     *
     */
    public function get($url, $data, $headers);
}
