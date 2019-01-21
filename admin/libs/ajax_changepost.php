<?php
if(isset($_POST['id'])){

    include_once('gl_logincheck.php');
    include_once('db_config.php');

    if($_POST['mode'] == "main") {
        $id = $_POST['id'];
        $pst = $_POST['pst'];
        $dbh = new PDO(DB_HOST, DB_USER, DB_PASS);
        $query = "UPDATE anken SET post = '" . $pst . "' WHERE id = '" . $id . "'";
        $st = $dbh->prepare($query);
        $flag = $st->execute();
        if ($flag) echo $flag;
    }
    if($_POST['mode'] == "users") {
        $id = $_POST['id'];
        $pst = $_POST['pst'];
        $dbh = new PDO(DB_HOST, DB_USER, DB_PASS);
        $query = "UPDATE users SET type = '" . $pst . "' WHERE id = '" . $id . "'";
        $st = $dbh->prepare($query);
        $flag = $st->execute();
        if ($flag) echo $flag;
    }
    //anken_detail.php
    if($_POST['mode'] == "userflag") {
        $id = $_POST['id'];
        $memo = $_POST['memo'];
        $dbh = new PDO(DB_HOST, DB_USER, DB_PASS);
        $query = "UPDATE users SET memo = '".$memo."' WHERE id = '" . $id . "'";
        $st = $dbh->prepare($query);
        $flag = $st->execute();
        if ($flag) echo $flag;
    }

}else{
    exit;
}