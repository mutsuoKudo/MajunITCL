<?php
include_once('libs/templates/header.php');
include_once('libs/db_pod.php');
$db = new db;
//更新
$id = 0;
$count = "";
if(isset($_GET['id'])){
    $id = $_GET['id'];
    $list = $db->get_all("SELECT * FROM anken WHERE id = '".$id."' AND del=0 ")[0];
    $user = $db->get_all("SELECT id FROM entry WHERE id = '".$id."'");
    $count = count($user);
}

$post = $list['post'];
if(!$post)$post = "非表示";
else $post ="掲載";
?>
<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">案件概要</h1>
        </div>
    </div>
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <tbody>
                <tr>
                    <th>案件No</th>
                    <td><?php echo $id?></td>
                    <th>掲載状況</th>
                    <td><?php echo $post?></td>
                    <td><?php echo $list['latest']?></td>
                </tr>
                <tr>
                    <th>案件名</th>
                    <td colspan="4"><?php echo $list['title']?></td>
                </tr>
                <tr>
                    <th>マスタ</th>
                    <td><?php echo $list['types']?></td>
                    <td><?php echo $list['area']?></td>
                    <td><?php echo $list['seg']?></td>
                    <td><?php echo $list['other']?> <?php echo $list['other2']?></td>
                </tr>
                <tr>
                    <th>金額</th>
                    <td colspan="4">
                        <?php echo $list['price']?>
                    </td>
                </tr>
                <tr>
                    <th>スキル</th>
                    <td colspan="4"><?php echo $list['lang']?></td>
                </tr>
                <tr>
                    <th>仕事内容1</th>
                    <td colspan="4"><?php echo nl2br($list['job'])?></td>
                </tr>
                <tr>
                    <th>応募資格1</th>
                    <td colspan="4"><?php echo nl2br($list['eligible'])?></td>
                </tr>
                <tr>
                    <th>勤務地</th>
                    <td colspan="4"><?php echo nl2br($list['addr'])?></td>
                </tr>
                <tr>
                    <th>給与</th>
                    <td colspan="4"><?php echo nl2br($list['salary'])?></td>
                </tr>
                </tbody>
            </table>
            <?php if($count){?>
                <a href="anken_detail.php?id=<?php echo $id?>"><button class="btn btn-primary btn-lg btn-block" type="button">この案件の応募者一覧</button></a>
            <?php }else{?>
                <button class="btn btn-default btn-lg btn-block disabled" style="background: #bebebe;color: #fff;" type="button">この案件の応募者一覧</button>
            <?php }?>
        </div>
</div>

<?php

include_once('libs/templates/footer.php');

?>
</body>
</html>