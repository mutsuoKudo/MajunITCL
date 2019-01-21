<?php

include('libs/templates/header.php');
include_once('libs/db_pod.php');
$db = new db;
$msg = "";
if(isset($_POST['mode']) and $_POST['mode'] == "更新"){
    $str = $_POST['col'];
    if (preg_match("/^[0-9]+$/", $str)) {
        $db->get_all("UPDATE `select` SET text = '".$_POST['col']."'  WHERE type = 'col'");
        $msg = '<div class="alert alert-success">一覧表示件数を更新しました。</div>';
    } else {
        $msg = '<div class="alert alert-danger">エラー：半角数字で入力してください。</div>';
    }
}
$now_anken = $db->count_table("SELECT id FROM anken where del=0 ");
$now_users = $db->count_table("SELECT id FROM users");
$now_select = $db->count_table("SELECT no FROM `select` WHERE NOT type = 'price' AND NOT type= 'col'");
$yest = date('Y-m-d', strtotime('-1 day'));
$yest_users = $db->count_table("SELECT id FROM users WHERE latest LIKE '%".$yest."%'");
$m_users = $db->count_table("SELECT id FROM users WHERE type = '0'");
$col = $db->get_all("SELECT text FROM `select` WHERE type = 'col'")[0]['text'];
?>
    <div id="page-wrapper">
        <div class="row">
            <?= $msg?>
            <div class="col-lg-12">
                <h1 class="page-header">TOP</h1>
            </div>
            <!-- /.col-lg-12 -->
        </div>
        <div class="row">
            <div class="col-lg-3 col-md-6">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-xs-3">
                                <i class="fa fa-comments fa-5x"></i>
                            </div>
                            <div class="col-xs-9 text-right">
                                <div class="huge"><?php echo $now_anken?></div>
                                <div>現在の案件</div>
                            </div>
                        </div>
                    </div>
                    <a href="main.php">
                        <div class="panel-footer">
                            <span class="pull-left">View Details</span>
                            <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                            <div class="clearfix"></div>
                        </div>
                    </a>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="panel panel-green">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-xs-3">
                                <i class="fa fa-users fa-5x"></i>
                            </div>
                            <div class="col-xs-9 text-right">
                                <div class="huge"><?php echo $yest_users?></div>
                                <div>昨日の登録</div>
                            </div>
                        </div>
                    </div>
                    <a href="users.php?mode=1">
                        <div class="panel-footer">
                            <span class="pull-left">View Details</span>
                            <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                            <div class="clearfix"></div>
                        </div>
                    </a>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="panel panel-yellow">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-xs-3">
                                <i class="fa fa-tags fa-5x"></i>
                            </div>
                            <div class="col-xs-9 text-right">
                                <div class="huge"><?php echo $now_select?></div>
                                <div>登録マスタ</div>
                            </div>
                        </div>
                    </div>
                    <a href="mdm.php?m=lang">
                        <div class="panel-footer">
                            <span class="pull-left">View Details</span>
                            <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                            <div class="clearfix"></div>
                        </div>
                    </a>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="panel panel-red">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-xs-3">
                                <i class="fa fa-support fa-5x"></i>
                            </div>
                            <div class="col-xs-9 text-right">
                                <div class="huge"><?php echo $m_users?></div>
                                <div>未対応エンジニア</div>
                            </div>
                        </div>
                    </div>
                    <a href="users.php?mt=1">
                        <div class="panel-footer">
                            <span class="pull-left">View Details</span>
                            <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                            <div class="clearfix"></div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-3">
                <div class="panel panel-green">
                    <form action="" method="post">
                        <div class="panel-heading">
                            TOP一覧表示件数
                        </div>
                        <div class="panel-body">
                            <input class="OnlyNumber form-control" maxlength="3" name="col" style="ime-mode: disabled;" value="<?=$col?>" oncontextmenu="return false;" type="text" placeholder="半角数字のみ" />
                        </div>
                        <div class="panel-footer">
                            <input type="submit" class="btn btn-info btn-block" name="mode" value="更新">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
<?php

include('libs/templates/footer.php');

?>
<script>
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