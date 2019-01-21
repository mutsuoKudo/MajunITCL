<?php
include_once('libs/templates/header.php');
include_once('libs/db_pod.php');
$db = new db;
//更新
if(isset($_POST['user_detail'])){
    $id = $_POST['id'];
    $type = $_POST['type'];
    $memo = $_POST['memo'];
    $pd = $db->get_all("UPDATE users SET type = '".$type."', memo = '".$memo."'  WHERE id ='".$id."'");
    $info = 1;
}
$id = 0;
if(isset($_GET['id'])) $id = $_GET['id'];
$list = $db->get_all("SELECT * FROM users WHERE id = '".$id."'")[0];
//全案件取得
$entry = "";
$rt = "";
$entry_title = "";
$cnt = 0;
$email = $list['email'];
$entry_no = $db->get_all("SELECT id FROM entry WHERE email ='".$email."'");
foreach( $entry_no as $value ){
    $rt = $db->get_all("SELECT title FROM anken WHERE id ='".$value['id']."' and del=0 ");
    $entry[] = $value['id'];
    if(isset($rt[0]['title']))$entry_title[$cnt] = $rt[0]['title'];else $entry_title[$cnt] = "";
    ++$cnt;
}

$entry_cnt = count($entry_title);
$type = $list['type'];
$memo = $list['memo'];
?>
<div id="page-wrapper">
    <div class="row">
        <?php if(!empty($info)){?>
            <div class="alert alert-info alert-dismissable">
                <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                [<?php echo $id?>] 更新が完了しました。
            </div>
        <?php } ?>
        <div class="col-lg-12">
            <h1 class="page-header">応募者詳細</h1>
        </div>
    </div>

        <div class="panel panel-default">
            <div class="panel-body">

                <div class="table-responsive">
                    <table class="table table-hover">
                        <tbody>
                        <tr>
                            <th>応募者ID</th>
                            <td><?php echo $list['id']?></td>
                            <th>メールアドレス</th>
                            <td><?php echo $list['email']?></td>
                        </tr>
                        <tr>
                            <th>スキルシート</th>
                            <td colspan="3">
                                <?php if($list['mine']){ ?>
                                <a href="libs/ss_export.php?id=<?php echo$id?>" target="_blank"><button class="btn btn-info" type="button" data-id="<?php echo $id?>">DL</button></a>
                                <?php }else{?>
                                    なし
                                <?php } ?>
                            </td>
                        </tr>
                        <?php
                        for($i=0;$i<$entry_cnt;++$i){
                            ?>
                            <tr>
                                <th><?php echo $entry[$i]?></th>
                                <td colspan="3">
                                    <?php if($entry_title[$i]){?>
                                    <a href="/detail/<?php echo $entry[$i]?>" target="_blank"><?php echo $entry_title[$i]?></a>
                                    <?php }else{?>
                                        完全削除
                                    <?php }?>
                                </td>
                            </tr>
                        <?php }?>
                        </tbody>
                    </table>
                </div>
                <div class="col-lg-14">
                    <form name="form01" method="post" action="">
                        <div class="panel panel-info">
                            <div class="panel-heading">
                                <label>対応状況</label>
                                <input type="hidden" name="id" value="<?php echo $id?>">
                                <select name="type" data-id="<?php echo$id?>">
                                    <option<?php if($type==0) echo " selected"?> value="0">未対応</option>
                                    <option<?php if($type==1) echo " selected"?> value="1">対応中</option>
                                    <option<?php if($type==2) echo " selected"?> value="2">済み</option>
                                </select>
                            </div>
                            <div class="panel-body">
                                <label>メモ</label><br>
                                <textarea name="memo" style="width: 100%;height: 200px;"><?php echo $memo?></textarea>
                            </div>
                            <div class="panel-footer">
                                <input type="submit" class="btn btn-warning" name="user_detail" value="更新">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
</div>

<?php

include_once('libs/templates/footer.php');

?>
</body>
</html>