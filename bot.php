<?php

####Copyright By Janu fb.com/lastducky####
####Thanks For Indra Swastika(fungsi.php)####
####Change This Copyright Doesn't Make You a Coder :) ####



#######EDIT THIS AREA#########
$username = "osyduck"; //Username IGmu
$password = "######"; //Password IGmu


#######END OF EDIT AREA########
require_once('fungsi.php');

if (!file_exists("datacookies.ig")) {
    $log = masuk($username, $password);
    if ($log == "data berhasil diinput") {
        echo "Berhasil Input Data";
    } else {
        echo "Gagal Input Data";
    }
} else {
    $gip    = file_get_contents('datacookies.ig');
    $gip    = json_decode($gip);
    $cekuki = instagram(1, $gip->useragent, 'feed/timeline/', $gip->cookies);
    $cekuki = json_decode($cekuki[1]);
    if ($cekuki->status != "ok") {
        $ulang = masuk($username, $password);
        if ($ulang != "data berhasil diinput") {
            echo "Cookie Telah Mati, Gagal Membuat Ulang Cookie";
        } else {
            echo "Cookie Telah Mati, Sukses Membuat Ulang Cookie";
        }
    } else {
        
        $data = file_get_contents('datacookies.ig');
        $data = json_decode($data);
        
        
        $a = instagram(1, $data->useragent, 'news/inbox', $data->cookies);
        $a = json_decode($a[1]);
        
        $b = count($a->new_stories);
        foreach ($a->old_stories as $story) {
            $username = $story->args->inline_follow->user_info->username;
            if (strpos($story->args->text, "started following you.") != FALSE) {
                $cek = instagram(1, $data->useragent, 'friendships/show/' . $story->args->profile_id, $data->cookies);
                $cek = json_decode($cek[1]);
                if ($cek->following == true) {
                    echo "Sudah Follow @" . $username . PHP_EOL;
                    
                } else {
                    $follow = instagram(1, $data->useragent, 'friendships/create/' . $story->args->profile_id . "/", $data->cookies, generateSignature('{"user_id":"' . $story->args->profile_id . '"}'));
                    $follow = json_decode($follow[1]);
                    echo "Sukses FollowBack @" . $username . PHP_EOL;
                }
            }
            
        }
    }
}




function masuk($username, $password)
{
    $login = json_decode(login($username, $password));
    if ($login->result == true) {
        $file = fopen("datacookies.ig", "w");
        fwrite($file, json_encode(array(
            'cookies' => $login->cookies,
            'useragent' => $login->useragent,
            'device_id' => $login->devid,
            'username' => $login->username
        )));
        fclose($file);
        return "data berhasil diinput";
    } else {
        return false;
    }
}
?>
