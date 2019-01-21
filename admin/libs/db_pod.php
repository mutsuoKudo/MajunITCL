<?php
if(!isset($sep))$sep = "";
include_once($sep.'libs/db_config.php');
class db{
    public $db_host = DB_HOST;
    public $db_user = DB_USER;
    public $db_pass = DB_PASS;
    //DB登録項目
    public $name = array(
        "案件番号" => "id",
        "案件名" => "title",
        "掲載状況" => "post",
        "エリア" => "area",
        "業種" => "types",
        "金額" => "price",
        "セグメント" => "seg",
        "その他" => "other",
        "その他2" => "other2",
        "言語" => "lang",
        "仕事内容" => "job",
        "応募資格" => "eligible",
        "勤務地" => "addr",
        "給与" => "salary",
        "写真コメント" => "comment",
        "仕事紹介" => "works",
        "勤務地地図URL" => "map",
        "最寄駅" => "station",
        "登録管理者" => "edituser",
        "自動削除" => "timer",
        "自動フラグ" => "del",
        "最終更新時" => "latest",
    );
    public $banner_name = array(
        "ランダムID" => "id",
        "案件名" => "title",
        "掲載状況" => "post",
        "リンク先" => "link",
        "エリア" => "area",
        "業種" => "types",
        "金額" => "price",
        "セグメント" => "seg",
        "その他" => "other",
        "その他2" => "other2",
        "言語" => "lang",
        "登録管理者" => "edituser",
        "最終更新時" => "latest",
    );
    function count_table($sql){
        $dbh = new PDO(DB_HOST, DB_USER, DB_PASS);
        $stmt = $dbh->query($sql);
        $stmt->execute();
        return $stmt->rowCount();
    }
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    //                                                                                                               GET
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    //【全体】SELECT 全て取得する
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
    //案件取得
    function get_anken($id){
        if(isset($id))
            $q ="SELECT * FROM `anken` WHERE CONVERT( id USING utf8 ) = '".$id."'";
        $result = $this->get_all($q);
        if(isset($result[0]))
        return $result[0];
        else
            echo '<div class="alert alert-danger">No.'.$id.'：案件が存在しません。</div>';
    }
    //バナー取得
    function get_banner($id){
        if(isset($id))
            $result = $this->get_all("SELECT * FROM banner  WHERE id = '".$id."'");
        if(isset($result[0]))
            return $result[0];
        else
            echo '<div class="alert alert-danger">No.'.$id.'：バナーが存在しません。</div>';
    }
    //【セレクト】セレクト項目を全て取得して種類別に分ける
    function get_select(){
        $result = $this->get_all("SELECT * FROM `select`");
        foreach ($result as $key => $value){
            $tp = $value['type'];
            $rows[$tp][] = $value;
        }
        return $rows;
    }
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    //                                                                                                               ADD
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    //バナーを追加        【バナー】
    function  add_banner()
    {
        $db_key = "";
        $db_pd = "";
        $db_lang = "";
        $time = $_POST['latest'];
        unset($_POST['latest']);
        if (!empty($_POST['lang'][0])) {
            foreach ($_POST['lang'] as $key => $value) {
                if ($db_lang) $db_lang .= " " . $value;
                else       $db_lang .= $value;
            }
         }else{
            $ssss = $_POST['link'];
            unset($_POST['link']);
            $_POST['lang'] = " ";
            $_POST['link'] = $ssss;
        }
        foreach ($this->banner_name as $key => $value){
            if($db_key)$db_key .= ", ".$value;
            else       $db_key .= $value;
        }
        foreach ($_POST as $key => $value){
            if($key=="lang" and $db_lang)$value = $db_lang;
            $db_pd .= "'".htmlspecialchars($value)."', ";
        }
        $db_pd =  $db_pd."'".$_SESSION['USERID']."','".$time."'";
        $query = "INSERT INTO banner (".$db_key.") values (".$db_pd.")";
        $dbh = new PDO(DB_HOST, DB_USER, DB_PASS);
        $st = $dbh->prepare($query);
        $flag = $st->execute();
        $dbh = null;
        //echo "<pre>";print_r($_POST); echo "</pre>";
        //echo $query;
        if ($flag){
            echo '<script type="text/javascript">window.location.href = "banner.php?id='.$_POST['id'].'&reg=1";</script>';

        }else{
            echo '<div class="alert alert-danger">No'.$_POST['id'].'：バナー登録に失敗しました。</div>';
        }
    }
    //案件を追加
    function  add_anken(){
        $db_key = "";
        $db_pd = "";
        $db_lang = "";
        $time = date('Y-m-d H:i');
        foreach ($_POST['lang'] as $key => $value){
            $value = htmlspecialchars($value);
            if($db_lang)$db_lang .= " ".$value;
            else       $db_lang .= $value;
        }
        foreach ($this->name as $key => $value){
            if($db_key)$db_key .= ", ".$value;
            else       $db_key .= $value;
        }
        foreach ($_POST as $key => $value){
            if($key=="lang")$value = $db_lang;
            if($key=="works") {
                $wk = $value;
                $cnt = count($wk['txt']);
                for ($i = 0; $i < $cnt; ++$i) {
                    $wk['title'][$i] = $value['title'][$i];
                    $wk['txt'][$i] = str_replace("\r\n", '<>', $value['txt'][$i]);
                }
                $_POST['works'] = json_encode($wk, JSON_UNESCAPED_UNICODE);
                $value = json_encode($wk, JSON_UNESCAPED_UNICODE);
            }

            //sql
            if($key=="works"){
                $db_pd .= "'".$value."', ";
            }else{

                $db_pd .= "'".htmlspecialchars($value)."', ";
            }
        }


        $db_pd =  $db_pd."'".$_SESSION['USERID']."', "."'0'".",'0','".$time."'";
        $query = "INSERT INTO anken (".$db_key.") values (".$db_pd.")";
        //$this->query($query);
        //ID+自動削除時間+削除フラグ＋最終更新時
        $dbh = new PDO(DB_HOST, DB_USER, DB_PASS);
        $st = $dbh->prepare($query);
        $flag = $st->execute();
        $dbh = null;
        if ($flag){
            //echo '<script type="text/javascript">window.location.href = "regit.php?id='.$_POST['id'].'&regster=1";</script>';
            echo '<div class="alert alert-success">No'.$_POST['id'].'：案件登録が完了しました。<a href="regit.php?id='.$_POST['id'].'">登録した案件を編集する</a></div>';
            include('libs/templates/footer.php');
            echo "</body></html>";
            exit;
        }else{
            echo '<div class="alert alert-danger">No'.$_POST['id'].'：同じ案件Noがすでに存在します。登録できません</div>';
            //echo $query;
        }
    }
    //画像を追加
    function add_image(){
        if(isset($_FILES['image']['name']) and $_FILES['image']['name']){
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


            // データベースに保存
            $dbh = new PDO(DB_HOST, DB_USER, DB_PASS);
            $sql = 'INSERT INTO images (id, image, mine) VALUES("'.$id.'","'.$imgdat.'","'.$mine.'")';
            $stmt = $dbh->prepare($sql);
            $stmt->execute();
            $dbh = null;
        }
    }
    function add_image2(){
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

        $dbh = new PDO(DB_HOST, DB_USER, DB_PASS);
        $sql = 'insert into images (id, image, mine) values (?, ?, ?)';
        $stmt = $dbh->prepare($sql);
        $stmt->execute(array($id, $imgdat, $mine));
        $dbh = null;
    }
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    //                                                                                                               EDIT
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    //バナーを編集        【バナー】
    function edit_banner(){
        $db_key = "";
        $db_lang = "";
        //言語の整列
        if (isset($_POST['lang'])) {
            foreach ($_POST['lang'] as $key => $value) {
                if ($db_lang) $db_lang .= " " . $value;
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
                $db_key .= ", ".htmlspecialchars($value).'="' .htmlspecialchars($set).'"';
            } else{
                $db_key .= "".htmlspecialchars($value).'="' .htmlspecialchars($set).'"';
            }
        }
        $dbh = new PDO(DB_HOST, DB_USER, DB_PASS);
        //画像があれば既存を削除して追加
        if(isset($_FILES['image']['name']) and $_FILES['image']['name']){
            $query = "DELETE FROM images WHERE id  = '".$_POST['id']."'";
            $st = $dbh->prepare($query);
            $flag =  $st->execute();
            if($flag)$this->add_image();
            else
                echo "バナーの削除失敗";
        }
        try {
            $query = "UPDATE banner SET ".$db_key." WHERE id = '".$_POST['id']."'";
            $st = $dbh->prepare($query);
            $st->execute();
        } catch (PDOException $e) {
            echo 'Connection failed: ' . $e->getMessage();
        }

        $dbh = null;

//        if ($flag){
//            echo '<div class="alert alert-success">No'.$_POST['id'].'：編集が完了しました。<a class="alert-link" href="regit.php">新規登録はこちら</a> | <a class="alert-link" href="main.php">一覧</a></div>';
//        }else{
//            echo '<div class="alert alert-danger">No'.$_POST['id'].'：編集に失敗しました。</a></div>';
//        }
    }
    //案件を編集
    function edit_anken(){
        $db_key = "";
        $db_lang = "";
        //言語の整列
        if (isset($_POST['lang'])) {
            foreach ($_POST['lang'] as $key => $value) {
                if ($db_lang) $db_lang .= " " . $value;
                else       $db_lang .= $value;
            }
        }else{
            $_POST['lang'] = "なし";
        }
        //UPDATE    整列
        $_POST['count'] = 0;

        if(isset($_POST['works'])){
            $wk = $_POST['works'];
            $cnt = count($wk['txt']);
            for($i=0;$i<$cnt;++$i){
                $wk['title'][$i] = $_POST['works']['title'][$i];
                $wk['txt'][$i] = str_replace("\r\n", '<>', $_POST['works']['txt'][$i]);
            }
            $_POST['works'] = json_encode($wk,JSON_UNESCAPED_UNICODE);
        }


        foreach ($this->name as $key => $value){
            if($value == "edituser")$set = $_SESSION['USERID'];
            else if($value == "lang") $set = $db_lang;
            else if($value == "timer" or $value == "del") $set = 0;
            else if($value == "latest")$set = date('Y-m-d H:i');
            else $set = $_POST[$value];
            if($db_key){
                if($value == "works")
                    $db_key .= ", ".$value."='" .$set."'";
                else
                $db_key .= ", ".htmlspecialchars($value)."='" .htmlspecialchars($set)."'";
            }else{
                if($value == "works")
                    $db_key .= "".$value."='" .$set."'";
                else
                $db_key .= "".htmlspecialchars($value)."='" .htmlspecialchars($set)."'";
            }
        }
        $dbh = new PDO(DB_HOST, DB_USER, DB_PASS);
        //画像があれば既存を削除して追加
        if(isset($_FILES['image']['name']) and $_FILES['image']['name']){
            $query = "DELETE FROM images WHERE id  = '".$_POST['id']."'";
            $st = $dbh->prepare($query);
           $flag =  $st->execute();
            $this->add_image();
        }
        try {
            $query = "UPDATE anken SET ".$db_key." WHERE id = '".$_POST['id']."'";
//            echo $query;
//            echo "<pre>";print_r($_POST); echo "</pre>";
            $st = $dbh->prepare($query);
            $flag = $st->execute();
        } catch (PDOException $e) {
            echo 'Connection failed: ' . $e->getMessage();
        }

        $dbh = null;

        if ($flag){
            echo '<div class="alert alert-success">'.$_POST['id'].'：編集が完了しました。<a class="alert-link" href="regit.php">新規登録はこちら</a> | <a class="alert-link" href="main.php">一覧</a></div>';
        }else{
            echo '<div class="alert alert-danger">'.$_POST['id'].'：編集に失敗しました。</div>';
        }
    }
    //案件の完全削除
    function delete($id){
        $dbh = new PDO(DB_HOST, DB_USER, DB_PASS);
        //画像があれば既存を削除して追加
//            $query = "DELETE FROM anken WHERE id  = '".$id."'";
              $query = "UPDATE anken SET del=1 WHERE id  = '".$id."'";
            $st = $dbh->prepare($query);
/*
            $st->execute();
            $query = "DELETE FROM images WHERE id  = '".$id."'";
            $st = $dbh->prepare($query);
*/
            $flag =  $st->execute();

            if($flag)
                echo '<div class="alert alert-danger"> 案件番号:' . $_POST['id'] . 'を完全に削除しました。<a class="alert-link" href="main.php">一覧</a></div> ';
            else
                echo '<div class="alert alert-danger"> エラー:削除が完了できませんでした。 <a class="alert-link" href="main.php">一覧</a></div> ';
    }
}

