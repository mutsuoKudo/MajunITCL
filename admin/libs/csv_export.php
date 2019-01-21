<?php
include_once('gl_logincheck.php');
include_once('db_config.php');
$data = get_all("SELECT id,title,types,area,seg,other,other2,price,lang,job,eligible,addr,salary,comment,map,station FROM anken where del=0 ");
//echo "<pre>";print_r($data); echo "</pre>";
//,types,area,seg,other,other2,price,lang,job,eligible,addr,salary,comment,map,station
$time = date("mjHi");
$filename = 'ex'.$time.'.csv';
header("Content-disposition: attachment; filename=".$filename);
header("Content-type: text/csv; name=".$filename);
$fp = fopen('php://output','w');
$ua = $_SERVER['HTTP_USER_AGENT'];
$safari = 0;
if (strstr($ua, 'Safari')) {//Safari の場合SJISに変換
    $safari = 1;
}
//UTF-8
if(!$safari)fwrite($fp, "\xef\xbb\xbf");

$save = "";
foreach($data as $line){
    if($safari)mb_convert_variables('SJIS-win', 'UTF-8', $line);
    fputcsv($fp, $line);
}

fclose($fp);
exit;

function get_all($sql){
    try{
        $dbh = new PDO(DB_HOST, DB_USER, DB_PASS);
        $sth = $dbh->prepare($sql);
        $sth->execute();
        $dbh = null;
        return $sth->fetchAll(PDO::FETCH_ASSOC);
    }catch (PDOException $e){
        print('Error:'.$e->getMessage());
        die();
    }
}