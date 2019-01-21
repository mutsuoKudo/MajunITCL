<?php
include('libs/templates/header.php');
include_once('libs/db_pod.php');
$db = new db;
$list = $db->get_all("SELECT * FROM banner");
$sl = $db->get_select();
?>
    <div id="page-wrapper">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">バナー一覧</h1>
            </div>
            <!-- /.col-lg-12 -->
        </div>
            <table id="list" class="table table-striped table-bordered table-hover dataTable no-footer">
                <thead>
                    <tr>
                        <th>詳細</th>
                        <th>画像</th>
                        <th>バナー管理用名称</th>
                        <th>掲載順</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                //リスト一覧表示
                $listcnt = count($list);
                for($i=0;$i<$listcnt;++$i){
                    $id = $list[$i]['latest'];
                    $post = $list[$i]['post'];
                    $title = $list[$i]['title'];
                    $lang = $list[$i]['lang'];
                    $area = $list[$i]['area'];
                    $types = $list[$i]['types'];
                    $other = $list[$i]['other'];
                    //hidden value
                    $seg = $list[$i]['seg'];
                    $price = $list[$i]['price'];
                ?>
                <tr>
                    <td class="text-center"><a href="banner.php?id=<?php echo $list[$i]['id']?>"><div class="btn btn-primary" type="button">編集</div></a></td>
                    <td><img src="/src/<?php echo $list[$i]['id']?>" width="100" alt="<?php echo $title?>"></td>
                    <td class=""><?php echo $title?></td>
                    <td class="text-center"><?php echo $id?></td>
                </tr>
                <?php }?>
                </tbody>
            </table>
        </div>
        </form>
    </div>
<?php

include('libs/templates/footer.php');

?>
</body>
</html>