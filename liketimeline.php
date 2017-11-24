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
        
        $mid = instagram(1, $data->useragent, 'feed/timeline/', $data->cookies);
        $mid = json_decode($mid[1]);
        
        foreach ($mid->items as $media) {
            $like = instagram(1, $data->useragent, 'media/' . $media->pk . '/like/', $data->cookies, generateSignature('{"media_id":"' . $media->pk . '"}'));
            $like = json_decode($like[1]);
            
            echo "Sukses Like Foto https://instagram.com/p/" . $media->code . "\n";
        }
        
    }
}
?>
