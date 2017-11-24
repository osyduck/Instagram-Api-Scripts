<?php

####Copyright By Janu fb.com/lastducky####
####Thanks For Indra Swastika(fungsi.php)####
####Change This Copyright Doesn't Make You a Coder :) ####



#######EDIT THIS AREA#########
$username = "osyduck"; //Username IGmu
$password = "######"; //Password IGmu
$type     = true; ##true = unfoll not follback, false = unfoll all
$jumlah   = 10; ##jumlah list yang mau di unfoll

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
        
        $userid = instagram(1, $data->useragent, 'users/' . $username . '/usernameinfo', $data->cookies);
        $userid = json_decode($userid[1]);
        $userid = $userid->user->pk;
        //print_r($userid);
        
        
        $cekfoll = instagram(1, $data->useragent, 'friendships/' . $userid . '/following/', $data->cookies);
        $cekfoll = json_decode($cekfoll[1]);
        $cekfoll = array_slice($cekfoll->users,0,$jumlah);
        
        
        
        foreach ($cekfoll as $ids) {
            $cek = instagram(1, $data->useragent, 'friendships/show/' . $ids->pk, $data->cookies);
            $cek = json_decode($cek[1]);
            if ($type == true) {
                if ($cek->followed_by == false) {
                    $unfollow = instagram(1, $data->useragent, 'friendships/destroy/' . $ids->pk . "/", $data->cookies, generateSignature('{"user_id":"' . $ids->pk . '"}'));
                    echo "Sukses Unfollow @" . $ids->username . " Karena Belum Follback";
                } else {
                    echo "@" . $ids->username . " Sudah Follback";
                }
            } else {
                $unfollow = instagram(1, $data->useragent, 'friendships/destroy/' . $ids->pk . "/", $data->cookies, generateSignature('{"user_id":"' . $ids->pk . '"}'));
                echo "Sukses Unfollow @" . $ids->username;
            }
        }
        
        
    }
    
}

?>
