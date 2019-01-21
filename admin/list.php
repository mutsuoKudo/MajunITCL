<?php
include_once('libs/templates/header.php');
include_once('libs/db_pod.php');
$db = new db;
//全案件取得
$list = $db->get_all("SELECT * FROM anken where del=0 ");
//セレクト取得
$sl = $db->get_select();
?>
<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">案件別応募者一覧</h1>
        </div>
        <!-- /.col-lg-12 -->
    </div>
    <form name="form01" method="post" action="main.php">
        <div class="panel panel-default">
            <div class="panel-body">
                <div class="row show-grid">
                    <div class="col-md-4">
                        <input class="form-control" name="id" id="id" value="" placeholder="案件No(半角10桁まで)">
                    </div>
                    <div class="col-md-8">
                        <input class="form-control" name="title" id="title" value="" placeholder="案件名">
                    </div>

                    <div class="col-md-6">
                        <select id="post" name="post" class="form-control">
                            <option value="">▶掲載状況</option>
                            <option value="掲載">掲載</option>
                            <option value="非表示">非表示</option>
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
                        <select id="type" name="type" class="form-control">
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
                            <option>長期</option>
                            <option>未経験</option>
                            <option>高額</option>
                            <option>急募</option>
                            <option>オススメ</option>
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
                            <option value="">▶スキル２</option>
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
                    <h5>スキル１</h5>
                    <div class="col-md-12 m10">
                        <?php
                        $trg = "lang";
                        $cnt = count($sl[$trg]);
                        if(isset($dt['lang'])){
                            $lang = mbsplit("_",$dt['lang']);
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
            </div>
    </form>
            <table id="list" class="table table-striped table-bordered table-hover dataTable no-footer">
                <thead>
                <tr>
                    <th>詳細</th>
                    <th>応募件数</th>
                    <th>案件No</th>
                    <th>案件名</th>
                    <th>スキル１</th>
                    <th>エリア</th>
                    <th>業種</th>
                    <th>その他</th>
                    <th>スキル２</th>
                    <th>最終更新日</th>
                    <th class="hide">セグメント</th>
                    <th class="hide">金額</th>
                    <th class="hide">掲載状況</th>
                </tr>
                </thead>
                <tbody>
                <?php
                //リスト一覧表示
                $listcnt = count($list);
                for($i=0;$i<$listcnt;++$i){
                    $id = $list[$i]['id'];
                    $title = $list[$i]['title'];
                    $lang = $list[$i]['lang'];
                    $area = $list[$i]['area'];
                    $types = $list[$i]['types'];
                    $other = $list[$i]['other'];
                    $other2 = $list[$i]['other2'];
                    $latest = $list[$i]['latest'];
                    //hidden value
                    $seg = $list[$i]['seg'];
                    $post = $list[$i]['post'];
                    $price = $list[$i]['price'];
                    if($post)$post = "掲載";
                    else $post ="非表示";


                    //応募件数を取得
                    $ct = $db->get_all("SELECT id FROM entry WHERE id = '".$id."'");
                    $count = count($ct)
                    ?>
                    <tr>
                        <td class="text-center"><a href="anken_g.php?id=<?php echo $id?>"><div class="btn btn-primary" type="button">詳細</div></a></td>
                        <td class="text-center">
                        <?php echo $count?>
                        </td>
                        <td><?php echo $id?></td>
                        <td><?php echo $title?></td>
                        <td class="lang"><?php echo $lang?></td>
                        <td><?php echo $area?></td>
                        <td><?php echo $types?></td>
                        <td><?php echo $other?></td>
                        <td><?php echo $other2?></td>
                        <td><?php echo $latest?></td>
                        <td class="hide"><?php echo $post?></td>
                        <td class="hide"><?php echo $seg?></td>
                        <td class="hide"><?php echo $price?></td>
                    </tr>
                <?php }?>
                </tbody>
            </table>
</div>
<?php

include_once('libs/templates/footer.php');

?>
<script>
    /* Custom filtering function which will search data in column four between two values */
    $.fn.dataTable.ext.search.push(
        function( settings, data, dataIndex ) {
            var min = parseInt( $('#price01').val(), 10 );
            var max = parseInt( $('#price02').val(), 10 );
            var price01 = parseFloat( data[9] ) || 0;
            var price02 = parseFloat( data[10] ) || 0;
            if ((isNaN( max ) && isNaN( min ) )||( !isNaN( max ) && price02 == max) || (!isNaN( min ) && price01 == min))
            {
                return true;
            }
            return false;
        }
    );
    $(function() {
        var types = "";
        oTable= $('#list').dataTable();
        $('#price01,#price02').blur(function() {
            var table = $('#list').DataTable();
            table.draw();
        } );
        //検索
        $('#post,#type,#price,#seg,#other,#other2,#id,#title,#area,input:checkbox').change(function() {
            types = $('input:checkbox:checked').map(function() {
                return this.value;
            }).get().join(' ');
            console.log("change");
            var str = new Array();
            var p = ['post', 'type','price', 'seg', 'other','other2', 'id', 'title', 'area'];
            //基本検索データまとめ
            for(i=0;i<9;++i){
                val = $('#'+p[i]).val();
                if(val) str += val+" ";
            }
            set_search(str+types);
        });
    });
    function set_search(str){
        if(str)
            oTable.fnFilter(str);
        else
            oTable.fnFilter("");
    }
</script>
</body>
</html>