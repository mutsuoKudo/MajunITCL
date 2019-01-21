<?php
function send_mail(){
//-----------------------------------------------------------------------------------------------------//
//差出人
    $sender				=	"info@it-enlabo.com";
//管理者メールアドレス
    $user_mail		=	"info@it-enlabo.com";
//管理者追加,で区切り
    $user_mail_add	=	"";

//管理者メールタイトル
    $title				=	"【登録がありました】エンジニアラボ";
//登録者宛メールタイトル
    $user_title		=	"【エンジニア登録完了】エンジニアラボ";
//管理者メールテンプレート先
    $template			=	"mail.tpl";
//クライアント用テンプレート先
    $user_template	=	"mail_user.tpl";

  // $user_mail = "test@eg-mode.com";
  // $user_mail_add = "";
//-----------------------------------------------------------------------------------------------------//
    $entry = cookie_title();
    require('libs/Smarty.class.php');
    $smarty	 = new Smarty();
    $smarty->template_dir = 'templates';
    $bcc            = "";
    if($user_mail_add) $bcc = $user_mail_add;
    $email          = $_POST['email'];
    $to				= $user_mail;
    $smarty->assign('email', $email);
    $smarty->assign('entry', $entry);
    $body			= $smarty->fetch($template);
    $body_user   	= $smarty->fetch($user_template);
    $res = sendmail($user_title,$email,$body_user,$to,$sender,0,0);
    $res2 = sendmail($title,$to,$body,$email,$sender,$bcc,"admin");
    echo $res+$res2;
}
function cookie_title(){
    $p = $_POST['entry'];
    $cnt = count($p);
    $title = "";
    for($i=0;$i<$cnt;++$i){
        $dbtitle = get_all('SELECT title FROM anken WHERE id = "'.$p[$i].'"')[0]['title'];
        if($p[$i]) $title .= $p[$i].":".$dbtitle."\n";
    }
    return $title;
}
include_once('../admin/libs/db_config.php');

if (isset($_POST['email']) and isset($_POST['entry'])) {
    if ($_POST['email'] and validate_email($_POST['email'])){

      if(is_uploaded_file($_FILES["file"]["tmp_name"])){
        // 拡張子
        $dat = pathinfo($_FILES["file"]["name"]);
        $mine = $dat['extension'];
        switch($mine){
          case "xls":
          case "xlsx":
          case "pdf":
          case "doc":
          case "docx":
            entry();
          break;
          default:
            echo 'ext_error';
          break;
        }
      }
      else{
        entry();
      }
    }
    else{
      echo  'error';
    }
}else{
    echo 'error';
}
function entry(){
    $res = get_all('SELECT email FROM users WHERE email = "'.$_POST['email'].'"');
    $is_entry = get_all('SELECT email FROM entry WHERE email = "'.$_POST['email'].'"');
    $imgdat = "";
    $mine = "";
    $time = date('Y-m-d H:i');
    //添付ファイルがあれば更新
    if(is_uploaded_file($_FILES["file"]["tmp_name"])){
        //echo "filename:".$_FILES["file"]["tmp_name"];
        // バイナリデータ
        $fp = fopen($_FILES["file"]["tmp_name"], "rb");
        $imgdat = fread($fp, filesize($_FILES["file"]["tmp_name"]));
        fclose($fp);
        $imgdat = addslashes($imgdat);
        // 拡張子
        $dat = pathinfo($_FILES["file"]["name"]);
        $mine = $dat['extension'];


    }
    //●ユーザーの登録・スキルシート更新
    $dbh = new PDO(DB_HOST, DB_USER, DB_PASS);

    //2015/05/08重複をチェックして上書きする
    if(!isset($res[0]['email'])) {
        //新規ユーザー
        $sql = "INSERT INTO users (email, file, mine,latest) VALUES ('".$_POST['email']."','".$imgdat."', '".$mine."','".$time."')";
    }else{
        //重複
        if(is_uploaded_file($_FILES["file"]["tmp_name"])){
            //重複している場合でスキルシートが添付されていたらファイルを更新する
            $sql = "UPDATE users SET file = '".$imgdat."', mine = '".$mine."' WHERE email = '".$_POST['email']."'";
        }else{
            $sql = "UPDATE users SET latest = '".$time."' WHERE email = '".$_POST['email']."'";
        }
    }
    $stmt = $dbh->prepare($sql);
    $stmt->execute();
    //●案件へのエントリー
    $entrysql = "";
    foreach ($_POST['entry'] as &$value) {
        $chk_sql = 'SELECT id FROM entry WHERE email = "' . $_POST['email'] . '" AND id = "' . $value . '"';
        $chk = get_all($chk_sql);
        //重複してなければ
        if (empty($chk[0]['id'])) {
            $entrysql = "('" . $value . "', '" . $_POST['email'] . "', '" . $time . "')";
            $entrysql = "INSERT INTO entry (id, email, latest) VALUES " . $entrysql;
            $stmt = $dbh->prepare($entrysql);
            $stmt->execute();
        }
    }
    $dbh = null;
    //メール送信　　●テスト中なので中止中
    send_mail();
    echo "2";
}
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
function rfc2822_func($input) {
    $pattern =
        '/^(?:(?:(?:(?:[a-zA-Z0-9_!#\$\%&\'*+\\/=?\^`{}~|\-]+)(?:\.(?:[a-zA-Z0-9_!'.
        '#\$\%&\'*+\\/=?\^`{}~|\-]+))*)|(?:"(?:\\[^\r\n]|[^\\"])*")))\@(?:(?:(?:(?:'.
        '[a-zA-Z0-9_!#\$\%&\'*+\\/=?\^`{}~|\-]+)(?:\.(?:[a-zA-Z0-9_!#\$\%&\'*+\\/=?\^`'.
        "{}~|\-]+))*)|(?:\[(?:\\\S|[\x21-\x5a\x5e-\x7e])*\])))$/"
    ;
    return (bool)preg_match($pattern, $input);
}
function validate_email($email, $strict = true) {
    $dot_string = $strict ?
        '(?:[A-Za-z0-9!#$%&*+=?^_`{|}~\'\\/-]|(?<!\\.|\\A)\\.(?!\\.|@))' :
        '(?:[A-Za-z0-9!#$%&*+=?^_`{|}~\'\\/.-])'
    ;
    $quoted_string = '(?:\\\\\\\\|\\\\"|\\\\?[A-Za-z0-9!#$%&*+=?^_`{|}~()<>[\\]:;@,. \'\\/-])';
    $ipv4_part = '(?:[0-9]|[1-9][0-9]|1[0-9][0-9]|2[0-4][0-9]|25[0-5])';
    $ipv6_part = '(?:[A-fa-f0-9]{1,4})';
    $fqdn_part = '(?:[A-Za-z](?:[A-Za-z0-9-]{0,61}?[A-Za-z0-9])?)';
    $ipv4 = "(?:(?:{$ipv4_part}\\.){3}{$ipv4_part})";
    $ipv6 = '(?:' .
        "(?:(?:{$ipv6_part}:){7}(?:{$ipv6_part}|:))" . '|' .
        "(?:(?:{$ipv6_part}:){6}(?::{$ipv6_part}|:{$ipv4}|:))" . '|' .
        "(?:(?:{$ipv6_part}:){5}(?:(?::{$ipv6_part}){1,2}|:{$ipv4}|:))" . '|' .
        "(?:(?:{$ipv6_part}:){4}(?:(?::{$ipv6_part}){1,3}|(?::{$ipv6_part})?:{$ipv4}|:))" . '|' .
        "(?:(?:{$ipv6_part}:){3}(?:(?::{$ipv6_part}){1,4}|(?::{$ipv6_part}){0,2}:{$ipv4}|:))" . '|' .
        "(?:(?:{$ipv6_part}:){2}(?:(?::{$ipv6_part}){1,5}|(?::{$ipv6_part}){0,3}:{$ipv4}|:))" . '|' .
        "(?:(?:{$ipv6_part}:){1}(?:(?::{$ipv6_part}){1,6}|(?::{$ipv6_part}){0,4}:{$ipv4}|:))" . '|' .
        "(?::(?:(?::{$ipv6_part}){1,7}|(?::{$ipv6_part}){0,5}:{$ipv4}|:))" .
        ')';
    $fqdn = "(?:(?:{$fqdn_part}\\.)+?{$fqdn_part})";
    $local = "({$dot_string}++|(\"){$quoted_string}++\")";
    $domain = "({$fqdn}|\\[{$ipv4}]|\\[{$ipv6}]|\\[{$fqdn}])";
    $pattern = "/\\A{$local}@{$domain}\\z/";
    return preg_match($pattern, $email, $matches) &&
    (
        !empty($matches[2]) && !isset($matches[1][66]) && !isset($matches[0][256]) ||
        !isset($matches[1][64]) && !isset($matches[0][254])
    )
        ;
}
//send mail
function sendmail($title,$to,$body,$from,$sender,$bcc,$admin){
    mb_language("Japanese");
    mb_internal_encoding("UTF-8");
    $body = fSetLF($body);
    $body = mb_convert_encoding($body, 'ISO-2022-JP-MS');
    $sender = mb_encode_mimeheader($sender);
    $header = "From:".$sender." <".$from.">\n"."Content-Type: text/plain; charset=\"ISO-2022-JP\";\n";
    if($admin)$header = "From:".$from."\n";
    if($bcc)$header .= "Bcc: ".$bcc."\n";
    return mb_send_mail($to, $title, $body, $header);
}
function fSetLF($String){
    $String = str_replace("\r\n", "\n", $String);
    $String = str_replace("\r", "\n", $String);
   // $String = replaceText($String);
    return $String;
}