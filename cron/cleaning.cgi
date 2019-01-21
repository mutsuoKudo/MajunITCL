#!/usr/local/bin/php-cgi-5.5.9
<?php
//////////////////////////////////////////////////
// プログラム名 ：cleaning.php
// 概要         ：旧案件データ完全削除
//
// 作成日/作成者：2015/06/13 Satoshi Tanaka
//////////////////////////////////////////////////


// +---------------------------------
// URL ★本番環境とSTG環境の相違は確認
// +---------------------------------
$SET_PATH['pg_home']='/usr/home/aa221k0x22/html/';
$SET_PATH['db_mode']='';

// +---------------------------------
// ライブラリ
// +---------------------------------
include_once($SET_PATH['pg_home'].'admin/libs/db_config.php');

// +---------------------------------
// 削除設定
// +---------------------------------
$DEL_LIMIT="365"; // 日単位設定
$LIMIT_DAY=date('Y-m-d',mktime(0,0,0,date('m'),date('d')-$DEL_LIMIT,date('Y')));

// +---------------------------------
// 処理開始
// +---------------------------------
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

// +---------------------------------
// 削除条件計算
// +---------------------------------
$SQL="SELECT id from anken{$SET_PATH['db_mode']} where (del=1)AND(latest<='{$LIMIT_DAY} 00:00:00') ORDER BY latest";
$RS=get_all($SQL);
foreach ($RS as $key => $value) {
	if($value["id"]){
		$EXEC_TEMP="DELETE from anken{$SET_PATH['db_mode']} where (del=1)AND(id='{$value["id"]}')"."\n";
		get_all($EXEC_TEMP);
		$EXEC_SQL.=$EXEC_TEMP;
	}
}

// +---------------------------------
// CRON動作確認用メール送付
// +---------------------------------
if($SET_PATH['db_mode']=="_demo"){
	$DAT["from_email"]="stanaka@epyon.co.jp";
	$DAT["to_email"]  ="stanaka@epyon.co.jp";
	$DAT["mail_title"]="CRON TEST";
	$DAT["mail_body"] =$EXEC_SQL;
//	$DAT["mail_body"] ='TEST';
	mb_send_mail($DAT["to_email"],$DAT["mail_title"],$DAT["mail_body"],"From: {$DAT["from_email"]}\nReply-to: {$DAT["from_email"]}\nX-Mailer: PHP/ " . phpversion() ."\n","-f{$DAT["from_email"]}");
}
exit;
?>
