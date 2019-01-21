<?php
include_once('libs/templates/header.php');
include_once('libs/db_pod.php');
$db = new db;
$id = 0;
if(isset($_GET['id'])){
    $id = $_GET['id'];
    $list = $db->get_all("SELECT * FROM entry WHERE id = '".$id."'");
}
$cnt = count($list);
?>
<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header"><?php echo $id?> 応募者一覧</h1>
        </div>
    </div>
    <div class="table-responsive">
        <?php
            for($i=0;$i<$cnt;++$i){
                $email = $list[$i]['email'];
                $latest = $list[$i]['latest'];
                $user = $db->get_all("SELECT id,file,memo,mine,type FROM users WHERE email = '".$email."'")[0];
                $id = $user['id'];
                $file = $user['file'];
                $memo = $user['memo'];
                $type = $user['type'];
                $img = $user['mine'];
                ?>
        <table class="table table-bordered table-striped">
            <tbody>
            <tr>
                <th>応募者ID</th>
                <td><?php echo$id?></td>
                <th>メールアドレス</th>
                <td><?php echo $email?></td>
                <td class="text-center">
                    <?php if($img){?>
                    <a href="libs/ss_export.php?id=<?php echo$id?>" target="_blank"><button data-id="<?php echo$id?>" type="button" class="btn btn-info">　DL　</button></a>
                    <?php }else{?>
                        なし
                    <?php }?>
                </td>
            </tr>
            <tr>
                <th>対応状況</th>
                <td colspan="4">
                    <select name="type" data-id="<?php echo$id?>">
                        <option<?php if($type==0) echo " selected"?> value="0">未対応</option>
                        <option<?php if($type==1) echo " selected"?> value="1">対応中</option>
                        <option<?php if($type==2) echo " selected"?> value="2">済み</option>
                    </select>
                </td>
            </tr>
            <tr>
                <th>メモ</th>
                <td colspan="4">
                    <textarea style="width: 100%;height: 200px;"><?php echo $memo?></textarea>
                </td>
            </tr>
            <tr>
                <th colspan="4">
                    <button data-id="<?php echo$id?>" type="button" class="btn btn-warning edit">　更新　</button>
                </th>
                <td class="text-center">
                    <?php echo $latest?>
                </td>
            </tr>
            </tbody>
        </table>
        <?php }?>
    </div>
</div>

<?php

include_once('libs/templates/footer.php');

?>
<script>
    $('td').on('click', 'option', function(){
        var val = $(this).val();
        var id = $(this).parent().data("id");
        $.ajax({
            type: 'POST',
            url: 'libs/ajax_changepost.php',
            data: {
                'mode':'users',
                'id':id,
                'pst': val
            },
            success: function(r){
                if(r == 1){
                    console.log('変更完了')
                    $('.alert').fadeOut();
                    $('<div class="upal alert alert-success alert-dismissable"><p class="fa fa-info-circle"></p> ['+id+'] 掲載設定を変更しました。</div>').appendTo('body').hide().slideDown('500');
                    setTimeout(function(){
                        $('.alert').slideUp("slow", function() { $(this).remove(); } );
                    },2000);
                }
            }
        });
    });

    $('th').on('click', '.edit', function(){
        var id = $(this).data("id");
        var memo = $(this).parent().parent().parent().find("td").find("textarea").val();
        $.ajax({
            type: 'POST',
            url: 'libs/ajax_changepost.php',
            data: {
                'mode':'userflag',
                'id':id,
                'memo':memo
            },
            success: function(r){
                if(r == 1){
                    console.log('変更完了')
                    $('.alert').fadeOut();
                    $('<div class="upal alert alert-success alert-dismissable"><p class="fa fa-info-circle"></p> ['+id+'] 掲載設定を変更しました。</div>').appendTo('body').hide().slideDown('500');
                    setTimeout(function(){
                        $('.alert').slideUp("slow", function() { $(this).remove(); } );
                    },2000);
                }
            }
        });
    });
</script>
</body>
</html>