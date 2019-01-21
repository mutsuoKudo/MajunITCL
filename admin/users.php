<?php
include_once('libs/templates/header.php');
include_once('libs/db_pod.php');
$db = new db;
//全案件取得
if(isset($_GET['mode']) and $_GET['mode'] == 1){
    $yest = date('Y-m-d', strtotime('-1 day'));
    $list = $db->get_all("SELECT * FROM users WHERE latest LIKE '%".$yest."%' ORDER BY latest DESC");
}else{
    $list = $db->get_all("SELECT * FROM users ORDER BY latest DESC, id DESC");
}
?>
<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">応募者一覧</h1>
        </div>
        <!-- /.col-lg-12 -->
    </div>
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="row show-grid">
                            <div class="col-md-4">
                                <label for="title">案件No</label>
                                <input class="form-control" name="id" id="id" value="" placeholder="">
                            </div>
                            <div class="col-md-8">
                                <label for="title">案件名</label>
                                <input class="form-control" name="title" id="title" value="">
                            </div>

                            <div class="col-md-4">
                                <select id="type" name="type" class="form-control">
                                    <option value="">対応状況</option>
                                    <option value="type:未対応">未対応</option>
                                    <option value="type:対応中">対応中</option>
                                    <option value="type:済み">済み</option>
                                </select>
                            </div>
                            <div class="col-md-5">
                                <input class="form-control" name="email" id="other" value="" placeholder="メールアドレス">
                            </div>
                            <div class="col-md-3">
                                <label for="title">スキルシート</label>
                                <label class="u-la"><input type="radio" name="ss" value="あり">あり</label>
                                <label class="u-la"><input type="radio" name="ss" value="なし">なし</label>
                            </div>
                        </div>
                    </div>
                    <div class="panel-footer">
                        <?php
                        $ua = $_SERVER['HTTP_USER_AGENT'];
                        ?>
                        <?php if(strstr($ua, 'Safari') or strstr($ua, 'MSIE 9') or strstr($ua, 'MSIE 8') ){?>
                            <form target="_blank" method="post" action="libs/csv_safari.php" id="csvsafari">
                                <input type="hidden" name="mode" value="csv-safari">
                                <textarea name="pst" class="hide"></textarea>
                                <a href="#" id="download" onclick="startDownload2()"><button class="btn btn-warning" type="button">CSVダウンロード</button></a>
                            </form>
                        <?php }elseif (strstr($ua, 'Trident') || strstr($ua, 'MSIE')) {?>
                            <a href="#" id="download" onclick="startDownload()"><button class="btn btn-warning" type="button">CSVダウンロード</button></a>
                        <?php }else{?>
                            <button class="btn btn-warning" type="button" id="csv">CSVダウンロード</button>
                        <?php }?>
                    </div>
                </div>
            <table id="list" class="table table-striped table-bordered table-hover dataTable no-footer">
                <thead>
                <tr>
                    <th>詳細</th>
<!--                    <th>対応状況</th>-->
                    <th>応募者ID</th>
                    <th>応募案件No</th>
                    <th>応募件数</th>
                    <th>スキルシート</th>
                    <th>メールアドレス</th>
                    <th>最終更新日</th>
                    <th class="hide">スキルシート</th>
                    <th class="hide">案件No一覧</th>
                    <th class="hide">タイトル</th>
                    <th class="hide">対応状態</th>
                    <th class="hide">案件No全て</th>
                    <th class="hide">メモ</th>
                </tr>
                </thead>
                <tbody>
                <?php
                //リスト一覧表示
                $listcnt = count($list);
                for($i=0;$i<$listcnt;++$i){
                    $entry = "";
                    $rt = "";
                    $entry_title = "";
                    $email = $list[$i]['email'];
                    $entry_cnt = 0;
                    //メールアドレスからエントリーしている案件IDを取得
                    $entry_no = $db->get_all("SELECT id,latest FROM entry WHERE email ='".$email."'");
                    foreach( $entry_no as $value ){
                        $latest = $value['latest'];
                        //案件タイトルを取得
                        $rt = $db->get_all("SELECT title FROM anken WHERE id ='".$value['id']."' and del=0 ");
                        //案件IDのまとめ
                        $entry .= $value['id']." ";
                        //タイトルのまとめ
                        if(isset($rt[0]['title'])){
                            $entry_title .=$rt[0]['title']." ";
                        }else{
                            $entry_title .="削除された案件です  ";
                        }
                        ++$entry_cnt;
                    }
                    $id = $list[$i]['id'];
                    $type = $list[$i]['type'];
                    $memo = $list[$i]['memo'];
                    if(empty($list[$i]['file'])) $file = "なし";else $file = "あり";

                    ?>
                    <tr>
                        <td class="text-center"><a href="user_detail.php?id=<?php echo $id?>"><div class="btn btn-primary" type="button">詳細</div></a></td>
<!--                        <td class="text-center">-->
<!--                            <select name="ct" data-id="--><?php //echo$id?><!--">-->
<!--                                <option--><?php //if($type==0) echo " selected"?><!-- value="0">未対応</option>-->
<!--                                <option--><?php //if($type==1) echo " selected"?><!-- value="1">対応中</option>-->
<!--                                <option--><?php //if($type==2) echo " selected"?><!-- value="2">済み</option>-->
<!--                            </select>-->
<!--                        </td>-->
                        <td class="text-center"><?php echo $id?></td>
                        <td><?php echo mb_strimwidth($entry, 0, 27, "...", 'utf-8');?></td>
                        <td class="text-center"><?php echo $entry_cnt?></td>
                        <td class="text-center">
                            <?php if($file == "あり"){?>
                                <a target="_blank" href="libs/ss_export.php?id=<?php echo $id?>">
                                <button class="btn btn-info" type="button" data-id="<?php echo $id?>">DL</button></a>
                            <?php }else{ ?>
                                なし
                            <?php }?>
                        </td>
                        <td><?php echo $email?></td>
                        <td><?php echo $latest?></td>
                        <td class="hide"><?php echo $file?></td>
                        <td class="hide"><?php echo $entry?></td>
                        <td class="hide"><?php echo $entry_title?></td>
                        <td class="hide">type:<?php
                            if($type == 0) echo "未対応";
                            else if($type == 1) echo "対応中";
                            else if($type == 2) echo "済み";
                            ?></td>
                        <td class="hide"><?php echo $entry;?></td>
                        <td class="hide"><?php echo $memo;?></td>
                    </tr>
                <?php }?>
                </tbody>
            </table>
</div>
<?php

include_once('libs/templates/footer.php');

?>
<script src="js/csv-output.js"></script>
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
        //GETがあれば検索項目へ
        var yd = getUrlVars()["yd"];
        var mt = getUrlVars()["mt"];
        if(yd)oTable.fnFilter(yd);
        if(mt)oTable.fnFilter("type:未対応");
        //検索
        $('#post,#type,#seg,#other,#id,#title,#area,input:checkbox,input:radio').change(function() {
            types = $('input:checkbox:checked,input:radio:checked').map(function() {
                return this.value;
            }).get().join(' ');
            var str = new Array();
            var p = ['post', 'type', 'seg', 'other', 'id', 'title', 'email','ss'];
            //基本検索データまとめ
            for(i=0;i<7;++i){
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
    $('#csv').on('click', function(){
        var save = [];
        save[0] = {"A":"応募者ID","B":"応募案件No","C":"応募件数","D":"メールアドレス","E":"メモ","F":"最終更新日時"};
        var cnt = 1;
            $('#list tr').each(function(index) {
                var s1 = $('td',this).eq(1).text();
                var s2 = $('td',this).eq(11).text().replace(/type:/g,'');
                var s3 = $('td',this).eq(3).text();
                var s4 = $('td',this).eq(5).text();
                var s5 = $('td',this).eq(12).text();
                var latest = $('td',this).eq(6).text();
                if(s4){
                    save[cnt] = {"A":s1,"B":s2,"C":s3,"D":s4,"E":s5,"F":latest};
                    ++cnt;
                }
            });
            console.log(save);
        var fileName = "download.csv";
        CSV_OUTPUT_CTL.exportCsv(save,fileName);
    });

    //IE用CSVダウンロード
    function startDownload() {
        var save = '"応募者ID","応募案件No","応募件数","メールアドレス","メモ","最終更新日時"';
        $('#list tr').each(function() {
            var s1 = $('td',this).eq(1).text();
            var s2 = $('td',this).eq(11).text().replace(/type:/g,'');
            var s3 = $('td',this).eq(3).text();
            var s4 = $('td',this).eq(5).text();
            var s5 = $('td',this).eq(12).text();
            var latest = $('td',this).eq(6).text();
            if(s4){
                save += "\n"+'"'+s1+'",'+'"'+s2+'",'+'"'+s3+'",'+'"'+s4+'",'+'"'+s5+'",'+'"'+latest+'"';
            }
        });

        var bom = new Uint8Array([0xEF, 0xBB, 0xBF]);
        var blob = new Blob([bom, save]);
        if (window.navigator.msSaveBlob) {
            window.navigator.msSaveBlob(blob, 'download.csv');
        } else {
            var url = window.URL.createObjectURL(blob);
            document.getElementById('download').href = url;
        }
    }
    //safari
    function startDownload2() {
        var save = '"応募者ID","応募案件No","応募件数","メールアドレス","メモ","最終更新日時"';
        $('#list tr').each(function() {
            var s1 = $('td',this).eq(1).text();
            var s2 = $('td',this).eq(11).text().replace(/type:/g,'');
            var s3 = $('td',this).eq(3).text();
            var s4 = $('td',this).eq(5).text();
            var s5 = $('td',this).eq(12).text();
            var latest = $('td',this).eq(6).text();
            if(s4){
                save += "\n"+'"'+s1+'",'+'"'+s2+'",'+'"'+s3+'",'+'"'+s4+'",'+'"'+s5+'",'+'"'+latest+'"';
            }
        });

        $("#csvsafari textarea").val(save);
        $('#csvsafari').submit();
    }
    function getUrlVars()
    {
        var vars = [], hash;
        var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
        for(var i = 0; i < hashes.length; i++)
        {
            hash = hashes[i].split('=');
            vars.push(hash[0]);
            vars[hash[0]] = hash[1];
        }
        return vars;
    }

</script>
</body>
</html>