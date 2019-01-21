<?php
if(isset($_POST['mode']) and isset($_POST['pst']) and $_POST['pst'] and $_POST['mode'] = "csv-safari") {
    $filename = 'download.csv';
    $data = $_POST['pst'];
    header("Content-disposition: attachment; filename=" . $filename);
    header("Content-type: text/csv; name=" . $filename);
    $fp = fopen('php://output', 'w');
    $ua = $_SERVER['HTTP_USER_AGENT'];
    $safari = 0;
    //Safari の場合SJISに変換
    if (strstr($ua, 'Safari')) $safari = 1;
    if(!$safari)fwrite($fp, "\xef\xbb\xbf");
    //エクセルで開きたい場合は、SJISに変換する
    if($safari)mb_convert_variables('SJIS', 'UTF-8', $data);
    echo $data;
    fclose($fp);
    exit;
}
