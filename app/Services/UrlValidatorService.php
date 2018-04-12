<?php

namespace App\Services;

use Psy\Exception\ErrorException;

class UrlValidatorService
{
    function parse_url_if_valid($url)
    {

        if (filter_var($url, FILTER_VALIDATE_URL, FILTER_FLAG_HOST_REQUIRED)) {

            return $url;

        } else

            $url = 'http://' . $url;

            if (filter_var($url, FILTER_VALIDATE_URL, FILTER_FLAG_HOST_REQUIRED)){

                return $url;

            }else {

                return false;
            }
    }
}