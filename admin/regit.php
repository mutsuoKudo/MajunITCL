<?php
//#TODO# セグメントDBの追加、そこから読み込む＋自動選択
include('libs/templates/header.php');
$token = sha1(uniqid(mt_rand(), true));
$_SESSION['token'][] = $token;
?>
<div id="page-wrapper">
    <div class="row">
        <?php
        //編集の場合、データ取得
        //今DB登録
        include_once('libs/db_pod.php');
        $db = new db;
        $id = "";

        //2015/05/12　新規登録時のメッセージ
        if(isset($_GET['regster']) and $_GET['regster'] == 1){
            echo '<div class="alert alert-success">No'.$_GET['id'].'：案件登録が完了しました。</div>';
        }
        if(isset($_POST['mode']) and $_POST['mode']=='imgdelete') {
            //画像の削除
            $sql = 'DELETE FROM images WHERE id = "'.$_POST['id'].'"';
            $db->get_all($sql);
            echo '<div class="alert alert-success">画像を削除しました。</a></div>';
        }
        //新規登録の場合
        if(isset($_POST['mode']) and $_POST['mode']=='reg'){
            //フォームからのPOSTかtokenをチェック
            $key = array_search($_POST['token'], $_SESSION['token']);
            $key = 1;//デバックモード　あとで消す★
            if ($key !== false) {
                // 正常な POST
                unset($_SESSION['token'][$key]); // 使用済みトークンを破棄
                unset($_POST['mode'],$_POST['token']);
                unset($_SESSION["token"]);
                $db->add_image();
                $db->add_anken();
            } else {
                echo '<div class="alert alert-danger">不正なアクセスです。<a class="alert-link" href="regit.php">新規登録はコチラ</a></div>';
            }

        }else if(isset($_POST['mode']) and $_POST['mode']=='edit') {
            //編集の場合
            //フォームからのPOSTかtokenをチェック
            $key = array_search($_POST['token'], $_SESSION['token']);
            if ($key !== false) {
                $db->edit_anken();
            } else {
                echo '<div class="alert alert-danger">不正なアクセスです。<a class="alert-link" href="regit.php">新規登録はコチラ</a></div>';
            }
        }else if(isset($_POST['mode']) and $_POST['mode'] == 'delete'){
            //完全削除
            $key = array_search($_POST['token'], $_SESSION['token']);
            if ($key !== false) {
                $db->delete($_POST['id']);
                unset($_POST['id']);
            }
        }
        //IDがあればinputデータを取得してセットする(編集・完了画面)
        if(isset($_GET['id']))$id = $_GET['id'];
        if(isset($_POST['id']))$id = $_POST['id'];
        //画像が存在するか
        $sql = 'SELECT no FROM images WHERE id = "'.$id.'"';
        $img = $db->get_all($sql);
        if(isset($img[0]['no'])) $img = 1;
        if($id){
            if(isset($id)){
                $dt = $db->get_anken($id);
                $dt['works'] =  json_decode($dt['works'],true);
            }
            $sl = $db->get_select();
            $title = "案件編集";
        }else{
            $title = "案件登録";
            $sl = $db->get_select();
        }

        ?>
        <div class="col-lg-12">
            <h1 class="page-header"><?php echo $title?></h1>
        </div>
        <!-- /.col-lg-12 -->
    </div>
    <form name="form01" method="post" action="" enctype="multipart/form-data" id="form01">
        <div class="row">
            <div class="col-lg-12">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <h5>基本情報　<span style="color: red;">※必須項目</span></h5>
                        <div class="row show-grid">
                            <div class="col-md-4">
                                <input class="form-control OnlyNumber" name="id" style="ime-mode: disabled;" id="id" value="<?php if(isset($dt['id']))echo $dt['id']?>" placeholder="案件No(半角10桁まで)"<?php if(isset($dt['id']))echo " disabled"?>  maxlength="10">
                                <?php if(isset($dt['id'])){?>
                                    <input type="hidden" name="id" value="<?php echo $dt['id']?>">
                                <?php }?>
                            </div>
                            <div class="col-md-8">
                                <input class="form-control" name="title" id="title" value="<?php if(isset($dt['title']))echo $dt['title']?>" placeholder="案件名（全角120文字まで）" maxlength="240">
                                <div id="counter" class="hide"></div>
                            </div>
                            <div class="col-md-6">
                                <select id="post" name="post" class="form-control">
                                    <option value="">▶掲載状況</option>
                                    <option value="1"<?php if(isset($dt['post']) and $dt['post']==1)echo " selected"?>>掲載</option>
                                    <option value="0"<?php if(isset($dt['post']) and $dt['post']==0)echo " selected"?>>非表示</option>
                                </select>
                            </div>
                            <div class="col-md-3">
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
                            <div class="col-md-3">
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
                            <div class="clearfix"></div>
                            <h5>基本情報　<span style="color: red;">検索用オプション</span></h5>
                            <div class="col-md-3">
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
                            <div class="col-md-3">
                                <select id="seg" name="seg" class="form-control">
                                    <option value="">▶セグメント</option>
                                    <option<?php if(isset($dt['seg']) and $dt['seg']=="即決")echo ' selected'?>>即決</option>
                                    <option<?php if(isset($dt['seg']) and $dt['seg']=="未経験")echo ' selected'?>>未経験</option>
                                    <option<?php if(isset($dt['seg']) and $dt['seg']=="高額")echo ' selected'?>>高額</option>
                                    <option<?php if(isset($dt['seg']) and $dt['seg']=="急募")echo ' selected'?>>急募</option>
                                    <option<?php if(isset($dt['seg']) and $dt['seg']=="オススメ")echo ' selected'?>>オススメ</option>
                                </select>
                            </div>
                            <div class="col-md-3">
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
                            <div class="col-md-3">
                                <select id="other2" name="other2" class="form-control">
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
                            <h5>スキル　<span style="color: red;">最低1つ選択してください</span></h5>
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
                            <div class="txt">
                                <h5>見出しエリア</h5>
                                <div class="col-mid-12">
                                    <label for="job">仕事内容1</label>
                                    <textarea name="job" id="job" class="form-control"><?php if(isset($dt['job']))echo $dt['job']?></textarea>
                                </div>
                                <div class="col-mid-12">
                                    <label for="eligible">応募資格</label>
                                    <textarea name="eligible" id="eligible" class="form-control mid"><?php if(isset($dt['eligible']))echo $dt['eligible']?></textarea>
                                </div>
                                <div class="col-mid-12">
                                    <label for="addr">勤務地</label>
                                    <textarea name="addr" id="addr" class="form-control mid"><?php if(isset($dt['addr']))echo $dt['addr']?></textarea>
                                </div>
                                <div class="col-mid-12">
                                    <label for="salary">金額</label>
                                    <textarea name="salary" id="salary" class="form-control small"><?php if(isset($dt['salary']))echo $dt['salary']?></textarea>
                                </div>
                                <div class="col-mid-12">
                                    <label>画像</label>
                                    <span class="preview text-center">
                                        <?php
                                        if($img) echo '<img src="/src/'.$dt['id'].'">
                                        <div data-target="#imgdel" data-toggle="modal" class="btn btn-outline btn-danger btn-xs mt10">画像削除</div>';
                                        else echo "Preview";
                                        ?>
                                    </span>
                                    <input type="file" name="image">
                                    <input type="text" id="comment" name="comment" class="form-control mt10" value="<?php if(isset($dt['comment']))echo $dt['comment']?>" placeholder="写真コメント">
                                </div>
                                <h5>詳細エリア</h5>
                                <div class="col-mid-12">
                                        <?php
                                        if(isset($dt)) {
                                            $w_1 = $dt['works']['title'];
                                            $w_2 = $dt['works']['txt'];
                                        }
                                        if(!empty($w_1[0])){
                                        foreach($w_1 as $key => $v){
                                            $w_2[$key] = str_replace("<>", "\n", $w_2[$key]);
                                        ?>
                                            <input type="text" name="works[title][]" value="<?php echo $w_1[$key]?>" class="form-control m10 mt10 wk">
                                            <textarea name="works[txt][]" id="work" class="form-control big wk"><?php echo $w_2[$key]?></textarea>
                                        <?php }}else{?>
                                            <input type="text" name="works[title][]" value="" class="form-control m10 wk" placeholder="タイトル">
                                            <textarea name="works[txt][]" id="work" class="form-control big wk"></textarea>
                                    <?php }?>
                                </div>
                                <div class="col-mid-12">
                                    <button class="btn btn-primary btn-lg btn-block" type="button" id="addtxt">項目追加</button>
                                </div>
                                <div class="col-mid-12">
                                    <label for="map">通勤地地図URL（URL形式　https://www.google.com/maps/embed?pb=）</label>
                                    <input type="text" name="map" id="map" value="<?php if(isset($dt['map']))echo $dt['map']?>" class="form-control">
                                </div>
                                <div class="col-mid-12">
                                    <label for="station">最寄駅</label>
                                    <textarea name="station" id="station" class="form-control mid"><?php if(isset($dt['station']))echo $dt['station']?></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel-footer">
                        <input type="hidden" name="token" value="<?php echo $token?>">
                        <?php if(!$id){?><button class="btn btn-warning reg" type="submit" name="mode" value="reg">登録</button><?php }else{?>
                            <button class="btn btn-info" type="submit" name="mode" value="edit">更新</button><?php }?>
    </form>
    <?php if($id){ ?>
        <div class="text-right">
            <!-- Button trigger modal -->
            <div data-target="#myModal" data-toggle="modal" class="btn btn-outline btn-danger btn-xs">完全削除</div>
            <!-- Modal -->
            <div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="myModal" class="modal fade" style="display: none;">
                <div class="modal-dialog text-left">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button>
                            <h4 id="myModalLabel" class="modal-title">データベースからの完全削除</h4>
                        </div>
                        <div class="modal-body">
                            案件番号:<?php echo $id?>を完全に削除します。
                        </div>
                        <div class="modal-footer">
                            <form method="post" action="regit.php" name="delform">
                                <input type="hidden" name="id" value="<?php echo $id?>">
                                <input type="hidden" name="token" value="<?php echo $token?>">
                                <button class="btn btn-danger" type="submit" name="mode" value="delete">完全削除</button>
                                <button data-dismiss="modal" class="btn btn-default" type="button">閉じる</button>
                            </form>
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
            <!-- /.modal -->
            <!-- Modal -->
            <div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="imgdel" class="modal fade" style="display: none;">
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
                            <form method="post" action="regit.php?id=<?php echo $id?>" name="delform">
                                <input type="hidden" name="id" value="<?php echo $id?>">
                                <input type="hidden" name="token" value="<?php echo $token?>">
                                <button class="btn btn-danger" type="submit" name="mode" value="imgdelete">画像削除</button>
                                <button data-dismiss="modal" class="btn btn-default" type="button">閉じる</button>
                            </form>
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
            <!-- /.modal -->
        </div>
    <?php }?>
</div>
</div>
</div>
</div>
</div>
<?php

include('libs/templates/footer.php');

?>
<script src="js/jquery.validate.min.js"></script>
<script src="js/localization/messages_ja.min.js"></script>
<script src="js/jquery.jcount.js"></script>
<script type="text/javascript">
    $(document).ready(function() {

        $("#addtxt").on('click', function() {
            var trg = $(this).parent().prev();
            console.log(trg);
            var fst =  trg.find("input").length;
                if (trg.find("textarea:last-child").val()) {
                    trg.append('<input type="text" name="works[title][]" value="" class="form-control m10 mt10" placeholder="タイトル"><textarea name="works[txt][]" id="work" class="form-control big"></textarea>');
                } else {
                    if(fst != 1)
                    {
                        trg.find("textarea:last-child").remove();
                        trg.find("input:last-child").remove();
                    }
                }

           });
        $(".wk,textarea,input").blur(function(){
            $hyphen = $(this).val().replace(/["'\'\"]/gi,'');
            $(this).val($hyphen);
        });
        $("#title").jcount({limit:120});

        $('#form01').submit(function(){
            var msg = "";
            var cnt = $("#counter").text();
            if(cnt == 120) return false;
            if(cnt<0) msg = "エラー：案件名は全角120文字以内で入力してください。 "+cnt;
            if(msg) {
                alert(msg);
                $("#title").css({"border": "1px solid #a94442","box-shadow":"0 1px 1px rgba(0, 0, 0, 0.075) inset"}).focus();
                return false;
            }
        });

        $("#form01").validate({
            errorClass: 'text-danger',
            errorID:'inputError',
            rules : {
                id: {
                    required: true,
                    rangelength: [1, 10],
                    az19: true
                },
                post: {
                    required: true
                },
                types: {
                    required: true
                },
                title: {
                    required: true
                },
                type: {
                    required: true
                },
                area: {
                    required: true
                },
                price01: {
                    required: true
                },
                'lang[]': {
                    required: true
                }
            }
        });

    });

    jQuery.validator.addMethod(
        "az19",
        function(value, element) {
            reg = new RegExp("^[0-9a-zA-Z\-_]+$");
            return this.optional(element) || reg.test(value);
        },
        "ユーザIDは、「半角英数字(a-z, A-Z, 0-9)」記号「-_」で入力してください。"
    );

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