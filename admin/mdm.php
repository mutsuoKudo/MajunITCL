<?php
include_once('libs/templates/header.php');
include_once('libs/db_pod.php');
$db = new db;
$m = "reg";
$ti = "追加";
$et = "";
$panel = "panel-primary";
if(isset($_GET['edit'])){
    $panel = "panel-green";
    $m = "edit";
    $ti = "編集";
    $no = $_GET['edit'];
    $sql ="SELECT text FROM `select` WHERE no = '".$no."'";
    $et = $db->get_all($sql)[0]['text'];
}
$msg = "";
if(isset($_POST['mode'])){
    if($_POST['mode'] == "reg"){
        $sql = "SELECT no FROM `select` WHERE text = '".$_POST['txt']."'";
        $chk = $db->get_all($sql);
        if(isset($chk[0]['no'])){
            $msg =  '<div class="alert alert-danger alert-dismissable">
                                <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                               既に登録されているマスタです。
                            </div>';
        }else {
            $sql = "INSERT INTO `select` (`type` ,`text` ,`show`)
                VALUES (
                '" . $_POST['type'] . "', '" . htmlspecialchars($_POST['txt']) . "', '0'
                );";
            $db->get_all($sql);
        }
    }elseif($_POST['mode'] == "delete" ){
        $sql ="DELETE FROM `select` WHERE `no` = '".$_POST['id']."'";
        $db->get_all($sql);
        $msg =  '<div class="alert alert-success alert-dismissable">
                                <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                               削除が完了しました
                            </div>';
    }else if($_POST['mode']=="edit"){
        $no = $_POST['no'];
        $sql ="UPDATE `select` SET text = '".htmlspecialchars($_POST['txt'])."' WHERE no = '".$no."'";
        $db->get_all($sql);
        $et = htmlspecialchars($_POST['txt']);
    }
}

$mode = $_GET['m'];
$sql ="SELECT no,text FROM `select` WHERE type = '".$mode."'";
$list = $db->get_all($sql);

$title['area'] = "エリア";
$title['type'] = "業種";
$title['lang'] = "スキル１";
$title['other'] = "その他";
$title['other2'] = "その他２";
$ta = "";
?>
<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <?php if(!empty($msg))echo $msg?>
            <h1 class="page-header">マスタ管理 - <?php echo $title[$mode]?></h1>
        </div>
        <div class="col-lg-12">
            <form method="post" action="" id="form01">
                <div class="panel <?=$panel?>">
                    <div class="panel-heading">
                        <?php echo $title[$mode]?>の<?= $ti?> ※全角15文字、半角30文字まで
                    </div>
                    <div class="panel-body">
                        <div class="form-group input-group">
                            <input type="text" class="form-control" name="txt" value="<?= $et?>">
                            <input type="hidden" name="mode" value="<?php echo $m?>">
                            <input type="hidden" name="no" value="<?php if(isset($_GET['edit']))echo $_GET['edit']?>">
                            <input type="hidden" name="type" value="<?php echo $mode?>">
                                <span class="input-group-btn">
                                    <input type="submit" class="btn btn-default" value="<?= $ti?>">
                                </span>
                        </div>
                        <div id="counter"></div>
                    </div>
                </div>
            </form>
        </div>

        <div class="col-lg-12">
            <table class="table table-striped table-bordered table-hover">
                <thead>
                <tr>
                    <th>#</th>
                    <th><?php echo $title[$mode]?></th>
                    <th>編集</th>
                    <th>削除</th>
                </tr>
                </thead>
                <tbody>
                <?php
                    foreach($list as $key => $value){
                    $txt = $list[$key]['text'];
                        $no = $list[$key]['no'];
                ?>
                <tr>
                    <td><?php echo $key?></td>
                    <td><?php echo $txt?></td>
                    <td><a href="mdm.php?m=<?php echo $mode?>&edit=<?php echo $no?>"><button class="btn btn-info" type="button">編集</button></a></td>
                    <td>

                        <div data-target="#myModal<?php echo $no?>" data-toggle="modal" class="btn btn-danger">削除</div>
                        <div style="display: none;" class="modal fade" id="myModal<?php echo $no?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                            <div class="modal-dialog text-left">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                        <h4 class="modal-title" id="myModalLabel">データベースからの完全削除</h4>
                                    </div>
                                    <div class="modal-body">
                                        <?php echo $txt?>を削除します。
                                    </div>
                                    <div class="modal-footer">
                                        <form name="delform" action="" method="post">
                                            <input type="hidden" value="<?php echo $no?>" name="id">
                                            <button value="delete" name="mode" type="submit" class="btn btn-danger">削除</button>
                                            <button type="button" class="btn btn-default" data-dismiss="modal">閉じる</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </td>

                </tr>
                <?php }?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php

include_once('libs/templates/footer.php');

?>
<script src="js/jquery.validate.min.js"></script>
<script src="js/jquery.jcount.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $(".form-control").jcount({limit:15});
        $('#form01').submit(function(){
            var msg = "";
            var cnt = $("#counter").text();
            if(cnt == 10) return false;
            if(cnt<0) msg = "エラー：名称は全角15文字、半角30文字以内で入力してください。 "+cnt;
            if(msg) {
                alert(msg);
                return false;
            }else {
                $(this).submit();
            }
        });
    });
</script>
</body>
</html>