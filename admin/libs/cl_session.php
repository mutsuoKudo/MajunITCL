<?php
//Session関係
class cl_Session{
    // day=1：前日のコード　昨日と今日のコードを比較対象としてログイン判断
    // USERID+TODAY+KEY
    const C_KEY = "DVw5RUGeBVeKw5MxgsV8VZzDXibnx3ZCacBed-eXu5QFfRAbB7eNibmTYXW-JDSLXU7V6YaX-6PYFkPdCWyEEziT";
    function set_encrypt($txt,$day=0){
        $today          = date("Ymd");
        $prevday        = date("Ymd",strtotime("-1 day"));
        if($day)$today = $prevday; else $day = $today;
        $encrypt_step01 = MD5($txt.$day);
        $encrypt_step02 = hash('sha256',$encrypt_step01.self::C_KEY);
        return $encrypt_step02;
    }
    //正常なSessionか調べる 昨日から今日までのデータならTrueを返す
    function s_check($id,$session){
        $check01    = $this->set_encrypt($id);
        $check02    = $this-> set_encrypt($id,1);
        if($session == $check01 or $session == $check02) return true;
    }
}
//デバック用
function d($str) {
    echo '<pre style="background:#f1f1f1;color:#444;border:1px solid #ccc;margin:5px;padding:10px;">';
    print_r($str);
    echo '</pre>';
}