<?php

namespace App\Services;

class CheckServerStatusCodeService
{
    public function checkStatusCode($url)
    {
        $headers = get_headers($url);

        return substr($headers[0], 9, 3);
    }
}