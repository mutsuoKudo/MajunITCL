<?php
include_once('libs/templates/header.php');
include_once('libs/db_config.php');

/* HTML特殊文字をエスケープする関数 */
function h($str) {
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}
// パラメータを正しい構造で受け取った時のみ実行
if (isset($_FILES['upfile']['error']) && is_int($_FILES['upfile']['error'])) {

    try {

        /* ファイルアップロードエラーチェック */
        switch ($_FILES['upfile']['error']) {
            case UPLOAD_ERR_OK:
                // エラー無し
                break;
            case UPLOAD_ERR_NO_FILE:
                // ファイル未選択
                throw new RuntimeException('ファイルが選択されていません');
            case UPLOAD_ERR_INI_SIZE:
            case UPLOAD_ERR_FORM_SIZE:
                // 許可サイズを超過
                throw new RuntimeException('ファイルサイズが大きすぎます');
            default:
                throw new RuntimeException('原因不明エラー、添付したファイルを提出してください');
        }

        $tmp_name = $_FILES['upfile']['tmp_name'];
        $detect_order = 'ASCII,JIS,UTF-8,CP51932,SJIS-win';
        setlocale(LC_ALL, 'ja_JP.UTF-8');

        /* 文字コードを変換してファイルを置換 */
        $buffer = file_get_contents($tmp_name);
        if (!$encoding = mb_detect_encoding($buffer, $detect_order, true)) {
            // 文字コードの自動判定に失敗
            unset($buffer);
            throw new RuntimeException('文字コードの変換に失敗しました');
        }
        file_put_contents($tmp_name, mb_convert_encoding($buffer, 'UTF-8', $encoding));
        unset($buffer);

        /* データベースに接続 */
        $pdo = new PDO(
            DB_HOST, DB_USER, DB_PASS,
            array(
                // カラム型に合わない値がINSERTされようとしたときSQLエラーとする
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET SESSION sql_mode='TRADITIONAL'",
                // SQLエラー発生時にPDOExceptionをスローさせる
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                // プリペアドステートメントのエミュレーションを無効化する
                PDO::ATTR_EMULATE_PREPARES => false,
            )
        );


       $time = date('Y-m-d H:i');


        /* トランザクション処理 */
        $pdo->beginTransaction();
        try {
            $fp = fopen($tmp_name, 'rb');
            while ($row = fgetcsv($fp)) {
                if ($row === array(null)) {
                    // 空行はスキップ
                    continue;
                }
                if (count($row) !== 16) {
                    // カラム数が異なる無効なフォーマット
                    throw new RuntimeException('カラム数が異なる無効なフォーマットです');
                }
                $row[0] = deleteBom(deleteBom($row[0]));

                $stmt = $pdo -> query("SELECT id FROM anken WHERE id = '".$row[0]."'");
                $count = $stmt -> rowCount();
                $td = " (`id`, `post`, `title`, `types`, `area`, `seg`, `other`, `other2`, `price`, `lang`, `job`, `eligible`, `addr`, `salary`, `comment`, `works`, `map`, `station`, `edituser`, `timer`, `del`, `latest`)";
                $sql = 'INSERT INTO anken '.$td.' VALUES (?, "1", ?, ?, ?, ?, ?,?, ?, ?, ?, ?, ?, ?, ?, "", ?, ?,"'.$_SESSION['USERID'].'", "0", "0","'.$time.'")';
                $up = 'update anken set id = ?, title = ?, types = ?, area = ?, seg = ?, other = ?, other2 = ?, price = ?, lang = ?, job= ?, eligible=?, addr = ?, salary= ?, comment = ?, map = ?,  station = ?, latest ="'.$time.'",del=0 where id = "'.$row[0].'" ';
                if($count) $sql = $up;
                $stmt = $pdo->prepare($sql);
                $executed = $stmt->execute($row);
            }
            if (!feof($fp)) {
                // ファイルポインタが終端に達していなければエラー
                throw new RuntimeException('CSV展開エラー');
            }
            fclose($fp);
            $pdo->commit();
        } catch (Exception $e) {
            fclose($fp);
            $pdo->rollBack();
            throw $e;
        }

        /* 結果メッセージをセット */
        if (isset($executed)) {
            // 1回以上実行された
            $msg = array('green', 'インポートが完了しました');
        } else {
            // 1回も実行されなかった
            $msg = array('black', 'インポートするデータがありません');
        }

    } catch (Exception $e) {

        /* エラーメッセージをセット */
        $error = $e->getMessage();
        if(strstr($error,"SQLSTATE[23000]")) $error = "重複している案件があります";
        $msg = array('red', $error);

    }

}


function deleteBom($str)
{
    if (($str == NULL) || (mb_strlen($str) == 0)) {
        return $str;
    }
    if (ord($str{0}) == 0xef && ord($str{1}) == 0xbb && ord($str{2}) == 0xbf) {
        $str = substr($str, 3);
    }
    return $str;
}

?>
<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">CSVインポート</h1>
        </div>
        <!-- /.col-lg-12 -->
        <div class="col-lg-12">
            <form enctype="multipart/form-data" method="post" action="">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                       CSVファイルを選択してください。
                    </div>
                    <div class="panel-body file">
                        <?php if (isset($msg)): ?>
                                <span style="color:<?=h($msg[0])?>;"><?=h($msg[1])?></span>
                        <?php endif; ?>
                        <input type="file" name="upfile" />
                    </div>
                    <div class="panel-footer">
                        <input type="submit" value="インポート" class="btn btn-primary btn-lg text-right" />
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<?php

include_once('libs/templates/footer.php');

?>
<script>
    $(".file").on('change','input' , function(){
        var name = $(this).val().split(".")[1];
        if(name) {
            if (name == 'csv') {
            } else {
                $(this).val("");
                alert('無効なファイルです');
            }
        }
    });
</script>
</body>
</html>