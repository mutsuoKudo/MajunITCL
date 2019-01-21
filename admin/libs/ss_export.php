<?php
if(!isset($_GET['id'])) exit;
include_once('gl_logincheck.php');
include_once('db_config.php');
$id = $_GET['id'];
$data = get_all("SELECT file,mine FROM users WHERE  id = '".$id."'")[0];
$mine = $data['mine'];
$filename = 'ss'.$id.'.'.$mine;
header('Content-type: application/force-download');
header('Content-disposition: attachment; filename="'.$filename.'"');
echo $data['file'];
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