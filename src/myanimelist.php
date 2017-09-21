<?php

const myAnimeListServer = "https://myanimelist.net/profile/";

try {

    $user = isset($_GET['profile']) ? strip_tags($_GET['profile']) : false;

    if($user) {

        $result = file_get_contents(myAnimeListServer.$user);

        if(isset($result))
        {
            $result = substr($result, strpos($result,"Episodes</span>") + 15);
            $result = substr($result, strpos($result, ">") + 1);
            $result = substr($result, 0, strpos($result, "</span"));
            $total = str_replace(",","", $result);

            echo "{\"frames\":[{\"icon\":\"i13457\",\"text\":\"$total\"}]}";
        }
    }

}catch (Exception $ex)
{
    echo "{\"frames\":[{\"icon\":\"i13457\",\"text\":\"$ex->getMessage()\"}]}";
}