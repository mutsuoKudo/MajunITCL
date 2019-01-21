<?php
$thispage = "detail";
include_once('common/db_main.php');
$db = new db;
//NEWアイコンを出す期間。$new日以内
$new = 7;
//全案件取得
$id = explode("/",$_SERVER['PATH_INFO'])[1];
$d = $db->get_all("SELECT * FROM anken WHERE id = '".$id."' AND post = '1' AND del = 0");
include_once("common/templates/header.tpl");
if(!empty($d[0])) $d = $d[0];

$sq = "";
if($d) {
    $lang = explode(" ", $d['lang']);
    if (!empty($lang)) {
        foreach ($lang as $key => $value) {
            if ($key != 0)
                $sq .= " OR lang LIKE '%" . $value . "%'";
            else
                $sq .= "lang LIKE '%" . $value . "%'";
        }
    }
    if ($d['types']) if ($sq) $sq .= " OR types = '" . $d['types'] . "'"; else $sq .= "types = '" . $d['types'] . "'";
    if ($d['area']) if ($sq) $sq .= " OR area = '" . $d['area'] . "'"; else $sq .= "area = '" . $d['area'] . "'";
    if ($d['seg']) if ($sq) $sq .= " OR seg = '" . $d['seg'] . "'"; else $sq .= "seg = '" . $d['seg'] . "'";
    if ($d['other']) if ($sq) $sq .= " OR other = '" . $d['other'] . "'"; else $sq .= "other = '" . $d['other'] . "'";
    if ($d['other2']) if ($sq) $sq .= " OR other2 = '" . $d['other2'] . "'"; else $sq .= "other2 = '" . $d['other2'] . "'";
}

//echo $sq;
$bn = $db->get_all("SELECT id, title, link FROM banner WHERE post = '1' AND ".$sq." ORDER BY latest");

?>
    <div id="contents" class="clearfix">
    	<div class="main-cont">
            <?php if($d){

                $d['works'] =  json_decode($d['works'], true);
                ?>
            <img src="common/images/main/tb.png" class="theader01" alt="">
            <div class="detail-top">
                <?php
                    //日付計算して NEWアイコンを出す
                    $today = date('Y-m-d');
                    $day = (strtotime($today) - strtotime($d['latest'])) / ( 60 * 60 * 24);
                    if($day <= $new) echo '<p class="new day-'.$day.'">new</p>';
                ?>

                <h2><?php echo $d['title']?></h2>
                <div class="clearfix mt20">
                <div class="detail-top-left">
                    <?php
                    $img_chk = $db->get_all("SELECT id FROM images WHERE id = '".$d['id']."'");
                    if(isset($img_chk[0]))$img_chk = $img_chk[0]['id'];
                    if(!empty($img_chk))
                            $img = "/src/".$d['id'];
                        else
                            $img = "common/images/noimage.jpg";
                    ?>
                    <img src="<?php echo $img?>" alt="<?php echo $d['comment']?>">
                    <p><?php echo $d['comment']?></p>
                </div>
                <div class="detail-top-right">
                    <table>
                        <tr>
                            <th>仕事内容</th>
                            <td>
                                <?php echo nl2br($d['job'])?>
                            </td>
                        </tr>
                        <tr>
                            <th>応募資格</th>
                            <td>
                                <?php echo nl2br($d['eligible'])?>
                            </td>
                        </tr>
                        <tr>
                            <th>勤務地</th>
                            <td>
                                <?php echo nl2br($d['addr'])?>
                            </td>
                        </tr>
                        <tr>
                            <th>金額</th>
                            <td>
                                <?php echo nl2br($d['salary'])?>
                            </td>
                        </tr>
                    </table>
                </div>
                </div>
                <p class="ar">お気に入りに追加すると、まとめてエントリーできます！</p>
                <?php
                //お気に入り選択中ならアクティブに
                $ac = "";
                    if(isset($_COOKIE["EL-ID"]) and mb_substr_count($_COOKIE["EL-ID"], $d['id'])) $ac = ' active';
                ?>
                <div class="jq<?php echo $ac?>" data-id="<?php echo $d['id']?>" data-title="<?php echo $d['title']?>">お気に入りに追加</div>
                <form id="eform" method="post" action="">
                    <div class="entry">
                        <p class="e1">スキルシートをお持ちでない方は、メールアドレスをご記入下さい。</p>
                        <input type="text" name="email" class="entryemail" value="" placeholder="メアド入力だけで簡単エントリー">
                        <div class="e2">
                            <p>スキルシートをお持ちの方は、添付して下さい。</p>
                            <input type="hidden" name="entry[]" value="<?php echo $d['id']?>">
                            <div class="file"><div class="fileimg"></div><input type="file" name="file"></div>
                        </div>
                        <div class="entry2" data-id="<?php echo $d['id']?>" data-title="<?php echo $d['title']?>">ENTRY</div>
                    </div>
                </form>
            </div>
            <div class="detail-bot mt20">
                <div class="list-title">
                    <img alt="New 新着案件" src="common/images/main/anken.png">
                </div>
                <div class="detail-table">
                    <table>

                        <?php
                        if(isset($d)) {
                            $w_1 = $d['works']['title'];
                            $w_2 = $d['works']['txt'];
                        }
                        if(!empty($w_1[0])){
                            foreach($w_1 as $key => $v){
                                if(!empty($w_1[$key])){
                                    $w_2[$key] = str_replace("<>", "<br>", $w_2[$key]);
                         ?>
                                <tr>
                                    <th><?php echo $w_1[$key]?></th>
                                    <td>
                                        <?php echo nl2br($w_2[$key])?>
                                    </td>
                                </tr>
                            <?php } } }?>
                    </table>
                    <div class="clearfix maps">
                        <div class="map">
                            <i>勤務地</i>
                            <?php
                            if (preg_match('/^(https?|ftp)(:\/\/[-_.!~*\'()a-zA-Z0-9;\/?:\@&=+\$,%#]+)$/', $d['map'])) {
                                echo '<iframe src="'.$d["map"].'" width="290" height="200"></iframe>';
                            ?>
                            <p><img src="common/images/main/arrow.gif" alt="->">
                                <a href="<?php echo nl2br($d['map'])?>" target="_blank" rel="nofollow">地図を拡大して表示</a>
                                <img src="common/images/main/link.gif" alt="リンクを開く">
                            </p>
                            <?php }else{?>
                            <a class="liveMap" target="_blank" href="#"><img id="staticMap" src="" alt="" /></a>
                            <?php }?>
                        </div>
                        <div class="map-detail">
                            <p>
                                <i>勤務地</i>
                                <?php echo nl2br($d['addr'])?>
                            </p>
                            <p>
                                <i>最寄駅</i>
                                <?php echo nl2br($d['station'])?>
                            </p>
                        </div>
                    </div>
                    <p class="ar">お気に入りに追加すると、まとめてエントリーできます！</p>
                    <div class="jq" data-id="<?php echo $d['id']?>" data-title="<?php echo $d['title']?>">お気に入りに追加</div>
                    <form id="eform" method="post" action="">
                        <div class="entry">
                            <p class="e1">スキルシートをお持ちでない方は、メールアドレスをご記入下さい。</p>
                            <input type="text" name="email" class="entryemail" value="" placeholder="メアド入力だけで簡単エントリー">
                            <div class="e2">
                                <p>スキルシートをお持ちの方は、添付して下さい。</p>
                                <input type="hidden" name="entry[]" value="<?php echo $d['id']?>">
                                <div class="file"><div class="fileimg"></div><input type="file" name="file"></div>
                            </div>
                            <div class="entry2" data-id="<?php echo $d['id']?>" data-title="<?php echo $d['title']?>">ENTRY</div>
                        </div>
                    </form>
                </div>
            </div>
            <?php }else{?>
            <div class="searchbox">
                <div class="panels">
                    <h2>案件が存在しません。</h2>
                    <p class="p404">この案件は一時的にアクセスができない状況にあるか、<br>
                    移動もしくは削除された可能性があります。<br>
                    <a href="/">トップページへ戻る</a>
                    </p>
                </div>
            </div>
            <?php } ?>
        </div>
<?php include_once("common/templates/footer.tpl") ?>