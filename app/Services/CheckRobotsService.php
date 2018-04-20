<?php

namespace App\Services;

class CheckRobotsService
{
    private $url_validator;
    private $status_code_checker;

    public function __construct(UrlValidatorService $url_validator, CheckServerStatusCodeService $codeService)
    {
        $this->url_validator = $url_validator;
        $this->status_code_checker = $codeService;
    }

    public function checkRobotsForUrl($url)
    {
        $check_status = [
            'status_code' => null,
            'valid_url' => null,
            'hosts_dir' => null,
            'sitemap' => null,
            'statusfail' => null,
            'no_robots' => null,
            'file_size' => null
        ];

        $valid_url = $this->url_validator->parse_url_if_valid($url);

        if($valid_url) {

            $check_status['valid_url'] = $valid_url;

                $host_direct_count = 0;
                $sitemap_direct_count = 0;

                try {
                    $pre_robots = file($valid_url . '/robots.txt', FILE_IGNORE_NEW_LINES);
                }catch (\Exception $e){
                    $check_status['no_robots'] = 1;

                    return $check_status;
                }

            $server_status_code = $this->status_code_checker->checkStatusCode($valid_url);

            $check_status['status_code'] = $server_status_code;

            if($server_status_code != 200){

                $check_status['statusfail'] = $server_status_code;
            }

            $check_status['file_size'] = (integer) $this->getRemoteFileSize($valid_url . '/robots.txt');

                foreach ($pre_robots as $robot){
                    $temp_str = explode(':', $robot);

                    if(isset($temp_str[0])){
                        $first_item = trim($temp_str[0]);
                        $first_item_t = strtolower($first_item);

                        if($first_item_t == 'host'){
                            $host_direct_count++;
                        }

                        if($first_item_t == 'sitemap'){
                            $sitemap_direct_count++;
                        }
                    }

                }

                $check_status['hosts_dir'] = $host_direct_count;
                $check_status['sitemap'] = $sitemap_direct_count;
                $check_status['valid_url'] = $url;

                return $check_status;


        }else{

            $check_status['valid_url'] = false;

            return $check_status;
        }

    }

    function getRemoteFileSize($url){

        try {
            $fp = fopen($url, "r");
            $inf = stream_get_meta_data($fp);
            fclose($fp);
        }catch (\Exception $e){
            return false;
        }
        foreach($inf["wrapper_data"] as $v)
            if (stristr($v,"content-length"))
            {
                $v = explode(":",$v);
                return trim($v[1]);
            }
    }
}