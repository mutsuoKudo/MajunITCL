<?php
$par = explode("/",$_SERVER['PATH_INFO']);
$url = $par[1];
if($url) {
    include('admin/libs/db_config.php');
    $query = "SELECT image, mine FROM images WHERE id = '" . $url."'";
    $dbh = new PDO(DB_HOST, DB_USER, DB_PASS);
    $sth = $dbh->prepare($query);
    $sth->execute();
    $dbh = null;
    $res = $sth->fetchAll(PDO::FETCH_ASSOC);
    if (!empty($res) and $res[0]) {
        $dt = $res[0];
        header('Content-type: '.$dt['mine']);
        echo $dt['image'];

    }else{
        echo "error";
    }

}else{
    exit();
}