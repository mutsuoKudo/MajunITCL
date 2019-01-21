<?php
include('libs/templates/header.php');
$token = sha1(uniqid(mt_rand(), true));
$_SESSION['token'][] = $token;
?>
<div id="page-wrapper">
    <div class="row">
<?php
include_once('libs/db_pod.php');
$db = new db;
$id = "";
if(isset($_POST['mode']) and $_POST['mode']=='delete') {
    //画像の削除
    $sql = 'DELETE FROM images WHERE id = "'.$_POST['id'].'"';
    $db->get_all($sql);
    echo '<div class="alert alert-success">画像を削除しました。</a></div>';
}
//新規登録の場合
if(isset($_POST['mode']) and $_POST['mode']=='reg'){
    //フォームからのPOSTかtokenをチェック
    $key = array_search($_POST['token'], $_SESSION['token']);
    if ($key !== false) {
        // 正常な POST
        unset($_SESSION['token'][$key]); // 使用済みトークンを破棄
        unset($_POST['mode'],$_POST['token']);
        unset($_SESSION["token"]);
        $db->add_image();
        $db->add_banner();
    } else {
        echo '不正なアクセスです。';
    }
}else if(isset($_POST['mode']) and $_POST['mode']=='edit') {
    //編集の場合
    //フォームからのPOSTかtokenをチェック
    $key = array_search($_POST['token'], $_SESSION['token']);
    if ($key !== false) {
        $db->edit_banner();
        echo
            '
        <div class="alert alert-success">
        '.$_POST['title'].'の編集が完了しました。<a class="alert-link" href="banner.php">新規登録</a> | <a class="alert-link" href="banner_list.php">一覧</a>
        </div>
        ';
    } else {
        echo '不正なアクセスです。';
    }
}

//IDがあればinputデータを取得してセットする(編集・完了画面)
if(isset($_GET['id']))$id = $_GET['id'];
if(isset($_POST['id']))$id = $_POST['id'];
if($id){
    $dt = $db->get_banner($id);
    $sl = $db->get_select();
    $title = "バナー編集";
    $rand = $id;
}else{
    $title = "バナー登録";
    $sl = $db->get_select();
    $rand = mt_rand();
}
//画像が存在するか
$sql = 'SELECT no FROM images WHERE id = "'.$id.'"';
$img = $db->get_all($sql);
if(isset($img[0]['no'])) $img = 1;

//画像のrequired
$i_chk = "true";
if(isset($_GET['id'])) $i_chk = "false";
if(isset($_POST['mode']) and $_POST['mode'] == "edit") $i_chk = "false";
?>

<div class="col-lg-12">
            <h1 class="page-header"><?php echo $title?></h1>

        <!-- /.col-lg-12 -->
      <form name="form01" method="post" action="banner.php" enctype="multipart/form-data" id="form01">
                <div class="panel panel-default">
                    <div class="panel-body">
                      <h5>基本情報　<span style="color: red;">※必須項目</span></h5>
                        <div class="row show-grid">
                            <div class="col-md-3">
                                <input type="hidden" id="id" name="id" value="<?php echo $rand?>">
                                <input class="OnlyNumber form-control" maxlength="3" id="latest" name="latest" style="ime-mode: disabled;" onkeydown="return OnlyNumber(event)" value="<?php if(isset($dt['latest']))echo $dt['latest']?>" oncontextmenu="return false;" type="text" placeholder="表示順" />
                            </div>
                            <div class="col-md-6">
                                <input class="form-control" name="title" id="title" value="<?php if(isset($dt['title']))echo $dt['title']?>" placeholder="バナー管理用名称">
                            </div>
                            <div class="col-md-3">
                                <select id="post" name="post" class="form-control">
                                    <option value="">▶掲載状況</option>
                                    <option value="1"<?php if(isset($dt['post']) and $dt['post']==1)echo " selected"?>>掲載</option>
                                    <option value="0"<?php if(isset($dt['post']) and $dt['post']==0)echo " selected"?>>非表示</option>
                                </select>
                            </div>
                            <div class="clearfix"></div>
                            <h5>登録画像バナー（横幅219pxが最適サイズです）</h5>
                            <div class="col-md-12 txt">
                                 <span class="preview text-center">
                                        <?php
                                        if($img) echo '<img src="/src/'.$dt['id'].'">
                                    <div data-target="#myModal" data-toggle="modal" class="btn btn-outline btn-danger btn-xs mt10">画像削除</div>';
                                        else echo "Preview";
                                        ?>
                                    </span>
                                <input type="file" name="image">
                            </div>
                            <div class="clearfix"></div>
                            <h5>リンク先　※エンラボ内の場合（例）PHP、医療　/search.php?lang[]=PHP&type[]=医療</h5>
                            <div class="col-md-12 txt">
                                <input type="text" id="link" name="link" class="form-control mt10" value="<?php if(isset($dt['link']))echo $dt['link']?>" placeholder="リンク先">
                            </div>
                            <div class="clearfix"></div>
                            <h5>基本情報オプション　※登録した検索条件結果ページにバナーが表示されます（TOPに表示する際は条件を登録しないでください）</h5>
                            <div class="col-md-4">
                                <select id="area" name="area" class="form-control">
                                    <option value="">▶エリア</option>
                                    <?php
                                    $trg = "area";
                                    $cnt = count($sl[$trg]);
                                    for($i = 0;$i<$cnt;++$i){
                                        $set_type = $sl[$trg][$i]['text'];
                                        if(isset($dt[$trg]) and $dt[$trg]== $set_type) $chk = " selected";else $chk = "";
                                        echo "<option".$chk.">".$set_type."</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <select id="types" name="types" class="form-control">
                                    <option value="">▶業種</option>
                                    <?php
                                    $trg = "type";
                                    $cnt = count($sl[$trg]);
                                    for($i = 0;$i<$cnt;++$i){
                                        $set_type = $sl[$trg][$i]['text'];
                                        if(isset($dt['types']) and $dt['types']== $set_type) $chk = " selected";else $chk = "";
                                        echo "<option".$chk.">".$set_type."</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <select id="price" name="price" class="form-control">
                                    <option value="">▶金額</option>
                                    <?php
                                    $trg = "price";
                                    $cnt = count($sl[$trg]);
                                    for($i = 0;$i<$cnt;++$i){
                                        $set_type = $sl[$trg][$i]['text'];
                                        if(isset($dt[$trg]) and $dt[$trg]== $set_type) $chk = " selected";else $chk = "";
                                        echo "<option".$chk.">".$set_type."</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <select id="seg" name="seg" class="form-control">
                                    <option value="">▶セグメント</option>
                                    <option<?php if(isset($dt['seg']) and $dt['seg']=="長期")echo ' selected'?>>長期</option>
                                    <option<?php if(isset($dt['seg']) and $dt['seg']=="未経験")echo ' selected'?>>未経験</option>
                                    <option<?php if(isset($dt['seg']) and $dt['seg']=="高額")echo ' selected'?>>高額</option>
                                    <option<?php if(isset($dt['seg']) and $dt['seg']=="急募")echo ' selected'?>>急募</option>
                                    <option<?php if(isset($dt['seg']) and $dt['seg']=="オススメ")echo ' selected'?>>オススメ</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <select id="other" name="other" class="form-control">
                                    <option value="">▶その他</option>
                                    <?php
                                    $trg = "other";
                                    $cnt = count($sl[$trg]);
                                    for($i = 0;$i<$cnt;++$i){
                                        $set_type = $sl[$trg][$i]['text'];
                                        if(isset($dt[$trg]) and $dt[$trg]== $set_type) $chk = " selected";else $chk = "";
                                        echo "<option".$chk.">".$set_type."</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <select id="other" name="other2" class="form-control">
                                    <option value="">▶その他2</option>
                                    <?php
                                    $trg = "other2";
                                    $cnt = count($sl[$trg]);
                                    for($i = 0;$i<$cnt;++$i){
                                        $set_type = $sl[$trg][$i]['text'];
                                        if(isset($dt[$trg]) and $dt[$trg]== $set_type) $chk = " selected";else $chk = "";
                                        echo "<option".$chk.">".$set_type."</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="clearfix"></div>
                            <h5>スキル　※登録した検索条件結果ページにバナーが表示されます（TOPに表示する際は条件を登録しないでください）</h5>
                            <div class="col-md-12 m10">
                                <?php
                                $trg = "lang";
                                $cnt = count($sl[$trg]);
                                if(isset($dt['lang'])){
                                    $lang = mbsplit(" ",$dt['lang']);
                                    $cnt2 = count($lang);
                                }
                                for($i = 0;$i<$cnt;++$i){
                                    $set_type = $sl[$trg][$i]['text'];
                                    $chk = "";
                                    //POSTデータがあれば、チェックを入れる
                                    if(isset($dt[$trg]) ) {
                                        for ($z = 0; $z < $cnt2; ++$z) {
                                            if($lang[$z] == $set_type) $chk = "checked";
                                        }
                                    }
                                    echo '
                                        <label class="checkbox-inline">
                                        <input type="checkbox" name="lang[]" value="'.$set_type.'"'.$chk.'>'.$set_type.'
                                    </label>
                                    ';
                                }
                                ?>
                            </div>
                    </div>
                    <div class="panel-footer">
                        <input type="hidden" name="token" value="<?php echo $token?>">
                        <?php if(!$id){?><button class="btn btn-warning reg" type="submit" name="mode" value="reg">登録</button>
                        <?php }else{?>
                            <button class="btn btn-info" type="submit" name="mode" value="edit">更新</button><?php }?>
                    </div>
                </div>
    </form> </div>
</div>
</div>
<!-- Modal -->
<div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="myModal" class="modal fade" style="display: none;">
    <div class="modal-dialog text-left">
        <div class="modal-content">
            <div class="modal-header">
                <button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button>
                <h4 id="myModalLabel" class="modal-title">画像の削除</h4>
            </div>
            <div class="modal-body">
                バナーid:<?php echo $id?>を削除します。
            </div>
            <div class="modal-footer">
                <form method="post" action="banner.php?id=<?php echo $id?>" name="delform">
                    <input type="hidden" name="id" value="<?php echo $id?>">
                    <input type="hidden" name="token" value="<?php echo $token?>">
                    <button class="btn btn-danger" type="submit" name="mode" value="delete">画像削除</button>
                    <button data-dismiss="modal" class="btn btn-default" type="button">閉じる</button>
                </form>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->
<?php

include('libs/templates/footer.php');

?>
<script src="js/jquery.validate.min.js"></script>
<script src="js/localization/messages_ja.min.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $("#form01").validate({
            errorClass: 'text-danger',
            errorID:'inputError',
            rules : {
                latest: {
                    required: true
                },
                post: {
                    required: true
                },
                title: {
                    required: true
                },
                image: {
                    required: <?= $i_chk?>
                },
                type: {
                    required: true
                }
            }
        });
    });
    // 数値のみの入力にする
    function OnlyNumber(evt) {
        var evt = evt || window.event;
        var c = evt.keyCode;
        // 48～57=0～9のキー、96～105=テンキーの0～9、8=バックスペース、9=タブキー、32=スペースキー、37=左矢印キー、39=右矢印キー、46=Deleteキー、18=Altキー、112～123=F1～F12キー、
        if ((48 <= c && c <= 57) || (96 <= c && c <= 105) || c == 8 || c == 9 || c == 32 || c == 37 || c == 39 || c == 46 || c == 18 || (112 <= c && c <= 123))
            return true;
        else
            return false;
    }
</script>
</body>
</html>