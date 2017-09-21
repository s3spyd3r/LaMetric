<?php

const lastfmServer = "http://ws.audioscrobbler.com/2.0/";
const lastFmAPIkey = "YOUR_LASTFM_API_KEY";

const method = "user.getinfo";
const format = "json";

try {

    $user = isset($_GET['profile']) ? strip_tags($_GET['profile']) : false;

    if($user) {

        $url = lastfmServer.'?method='.method.'&user='.$user.'&api_key='.lastFmAPIkey.'&format='.format;
        $result = file_get_contents($url);
        $result = utf8_encode($result);

        if(isset($result))
        {
            $content = json_decode($result, true);

            if (json_last_error() === JSON_ERROR_NONE)
            {
                echo "{\"frames\":[{\"icon\":\"i11667\",\"text\":\"{$content['user']['playcount']}\"}]}";
            }
        }
    }

}catch (Exception $ex)
{
    echo "{\"frames\":[{\"icon\":\"i11667\",\"text\":\"$ex->getMessage()\"}]}";
}