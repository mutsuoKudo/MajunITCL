<?php
if(!isset($sep))$sep = "";
include_once($sep.'libs/db_config.php');
class db{
    public $db_host = DB_HOST;
    public $db_user = DB_USER;
    public $db_pass = DB_PASS;
    public $db_name = DB_NAME;
    //DB登録項目
    public $name = array(
        "案件番号" => "id",
        "掲載状況" => "post",
        "案件名" => "title",
        "業種" => "types",
        "エリア" => "area",
        "セグメント" => "seg",
        "その他" => "other",
        "金額" => "price01",
        "金額２" => "price02",
        "言語" => "lang",
        "仕事内容" => "job",
        "応募資格" => "eligible",
        "勤務地" => "addr",
        "給与" => "salary",
        "写真コメント" => "comment",
        "仕事内容２" => "works",
        "求める人材" => "parson",
        "事業内容" => "about",
        "勤務地地図URL" => "map",
        "最寄駅" => "station",
        "登録管理者" => "edituser",
        "自動削除" => "timer",
        "自動フラグ" => "del",
        "最終更新時" => "latest",
    );
    public $banner_name = array(
        "ランダムID" => "id",
        "掲載状況" => "post",
        "案件名" => "title",
        "業種" => "types",
        "エリア" => "area",
        "セグメント" => "seg",
        "その他" => "other",
        "金額" => "price01",
        "金額２" => "price02",
        "言語" => "lang",
        "リンク先" => "link",
        "登録管理者" => "edituser",
        "最終更新時" => "latest",
    );
    //画像を取得
    function get_image(){
        $query ='SELECT * FROM images WHERE id = '.$_GET['id'];
        $dt = $this->select($query);
        $img = $this->fetchObject();
        $contents_type = array(
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'png' => 'image/png',
            'gif' => 'image/gif',
            'bmp' => 'image/bmp',
        );
        echo $contents_type[$img->ext];
        return $dt['images'];
    }
    //案件を取得
    //TODO : 編集管理者の制限があるかもしれない
    function get_anken($id){
        $query ='SELECT * FROM anken WHERE id = '.$id;
        $dt = $this->select($query);
        return $dt;
    }
    function get_banner($id){
        $query ='SELECT * FROM banner WHERE id = '.$id;
        $dt = $this->select($query);
        return $dt;
    }
    function get_anken_all(){
        $query ='SELECT * FROM anken ';
        $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS);
        if ($mysqli->connect_errno) {
            print('<p>データベースへの接続に失敗しました。</p>' . $mysqli->connect_error);
            exit();
        }
        $mysqli->select_db(DB_NAME);
        $mysqli->set_charset('utf8');
        $result = $mysqli->query($query);
        while($row = $result->fetch_array(MYSQLI_ASSOC))
        {
           $row['lang'] = str_replace("_"," ",$row['lang']);
           $rows[] = $row;
        }
        $result->free();
        $mysqli->close();
        return $rows;
    }
    //バナー一覧取得
    function get_banner_all(){
        $query ='SELECT * FROM banner ';
        $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS);
        if ($mysqli->connect_errno) {
            print('<p>データベースへの接続に失敗しました。</p>' . $mysqli->connect_error);
            exit();
        }
        $mysqli->select_db(DB_NAME);
        $mysqli->set_charset('utf8');
        $result = $mysqli->query($query);
        while($row = $result->fetch_array(MYSQLI_ASSOC))
        {
            $row['lang'] = str_replace("_"," ",$row['lang']);
            $rows[] = $row;
        }
        $result->free();
        $mysqli->close();
        return $rows;
    }
    //選択項目の取得
    function get_select(){
        $query ='SELECT * FROM `select` ';
        $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS);
        if ($mysqli->connect_errno) {
            print('<p>データベースへの接続に失敗しました。</p>' . $mysqli->connect_error);
            exit();
        }
        $mysqli->select_db(DB_NAME);
        $mysqli->set_charset('utf8');
        $result = $mysqli->query($query);
        while($row = $result->fetch_array(MYSQLI_ASSOC))
        {
            $tp = $row['type'];
            $rows[$tp][] = $row;
        }
        $result->free();
        $mysqli->close();
        return $rows;
    }
    function delete($id){
        $query = "DELETE FROM images WHERE id  = ".$id;
        $this->query($query);
        $query = "DELETE FROM anken WHERE id  = ".$id;
        $this->query($query);
    }
    function edit_anken(){
        $db_key = "";
        $db_lang = "";
        //言語の整列
        if (isset($_POST['lang'])) {
            foreach ($_POST['lang'] as $key => $value) {
                if ($db_lang) $db_lang .= "_" . $value;
                else       $db_lang .= $value;
            }
        }else{
            $_POST['lang'] = "なし";
        }
        //UPDATE    整列
        foreach ($this->name as $key => $value){
            if($value == "edituser")$set = $_SESSION['USERID'];
                else if($value == "lang") $set = $db_lang;
                    else if($value == "timer" or $value == "del") $set = 0;
                        else if($value == "latest")$set = date('Y-m-d');
                            else $set = $_POST[$value];
            if($db_key){
                $db_key .= ", ".$value.'="' .$set.'"';
            } else{
                $db_key .= "".$value.'="' .$set.'"';
            }
        }
        //画像があれば既存を削除して追加
        if(isset($_FILES['image']['name']) and $_FILES['image']['name']){
           $query = "DELETE FROM images WHERE id  = ".$_POST['id'];
            $this->query($query);
            $this->add_image();
        }
        $query = "UPDATE anken SET ".$db_key." WHERE id =".$_POST['id'];
        $this->query($query);
    }
    //バナーを編集        【バナー】
    function edit_banner(){
        $db_key = "";
        $db_lang = "";
        //言語の整列
        if (isset($_POST['lang'])) {
            foreach ($_POST['lang'] as $key => $value) {
                if ($db_lang) $db_lang .= "_" . $value;
                else       $db_lang .= $value;
            }
        }else{
            $_POST['lang'] = "なし";
        }
        //UPDATE    整列
        foreach ($this->banner_name as $key => $value){
            if($value == "edituser")$set = $_SESSION['USERID'];
            else if($value == "lang") $set = $db_lang;
            else $set = $_POST[$value];
            if($db_key){
                $db_key .= ", ".$value.'="' .$set.'"';
            } else{
                $db_key .= "".$value.'="' .$set.'"';
            }
        }
        //画像があれば既存を削除して追加
        if(isset($_FILES['image']['name']) and $_FILES['image']['name']){
            $query = "DELETE FROM images WHERE id  = ".$_POST['id'];
            $this->query($query);
            $this->add_image();
        }
        $query = "UPDATE banner SET ".$db_key." WHERE id =".$_POST['id'];
        $this->query($query);
    }
    //バナーを追加        【バナー】
    function  add_banner(){
        $db_key = "";
        $db_pd = "";
        $db_lang = "";
        $time = $_POST['latest'];
        unset($_POST['latest']);
        foreach ($_POST['lang'] as $key => $value){
            if($db_lang)$db_lang .= "_".$value;
            else       $db_lang .= $value;
        }
        foreach ($this->banner_name as $key => $value){
            if($db_key)$db_key .= ", ".$value;
            else       $db_key .= $value;
        }
        foreach ($_POST as $key => $value){
            if($key=="lang")$value = $db_lang;
            $db_pd .= "'".$value."', ";
        }
        $db_pd =  $db_pd."'".$_SESSION['USERID']."','".$time."'";
        $query = "INSERT INTO banner (".$db_key.") values (".$db_pd.")";
        $this->query($query);
    }
    //案件を追加
    function  add_anken(){
        $db_key = "";
        $db_pd = "";
        $db_lang = "";
        $time = date('Y-m-d');
        foreach ($_POST['lang'] as $key => $value){
            if($db_lang)$db_lang .= "_".$value;
            else       $db_lang .= $value;
        }
        foreach ($this->name as $key => $value){
            if($db_key)$db_key .= ", ".$value;
            else       $db_key .= $value;
        }
        foreach ($_POST as $key => $value){
            if($key=="lang")$value = $db_lang;
            $db_pd .= "'".$value."', ";
        }
        $db_pd =  $db_pd."'".$_SESSION['USERID']."', "."'0'".",'0','".$time."'";
        $query = "INSERT INTO anken (".$db_key.") values (".$db_pd.")";
        $this->query($query);
        //ID+自動削除時間+削除フラグ＋最終更新時
    }
    function add_image(){
        $id =  $_POST['id'];
        // バイナリデータ
        $fp = fopen($_FILES["image"]["tmp_name"], "rb");
        $imgdat = fread($fp, filesize($_FILES["image"]["tmp_name"]));
        fclose($fp);
        $imgdat = addslashes($imgdat);

        // 拡張子
        $dat = pathinfo($_FILES["image"]["name"]);
        $extension = $dat['extension'];

        // MINEタイプ
        if ( $extension == "jpg" || $extension == "jpeg" ) $mine = "image/jpeg";
        else if( $extension == "gif" ) $mine = "image/gif";
        else if ( $extension == "png" ) $mine = "image/png";

        $query = "INSERT INTO images (id,image, mine) values ('$id','$imgdat', '$mine')";
        $this->query($query);
    }
    function select($query){
        $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS);
        if ($mysqli->connect_errno) {
            print('<p>データベースへの接続に失敗しました。</p>' . $mysqli->connect_error);
            exit();
        }
        $mysqli->select_db(DB_NAME);
        $mysqli->set_charset('utf8');
        $result = $mysqli->query($query);
        $mysqli->close();
        return mysqli_fetch_array($result);
    }
    function query($query){
        // MySQL登録
        $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS);
        if ($mysqli->connect_errno) {
            print('<p>データベースへの接続に失敗しました。</p>' . $mysqli->connect_error);
            exit();
        }
        // データベースの選択
        $mysqli->select_db(DB_NAME);
        $mysqli->set_charset('utf8');
        // 入力値のサニタイズ
        //$id = $mysqli->real_escape_string($_POST["id"]);
        // クエリの実行
        $result = $mysqli->query($query);
        if (!$result) {
            print('DB error:' . $mysqli->error);
            $mysqli->close();
            exit();
        }
        $mysqli->close();
    }
}
