<?php
include_once('common/db_main.php');
//全案件取得
$db = new db;
$col = $db->get_all("SELECT text FROM `select` WHERE type = 'col'");
$col = $col[0]['text'];
$list = $db->get_all("SELECT * FROM anken WHERE post = '1' AND del = 0 ORDER BY latest DESC, id DESC  LIMIT ".$col);
//セレクト取得
$sl = $db->get_select();
$index = 1;

session_start();
if(isset($_GET['t']) and $_GET['t'] == 1){
    if(!empty($_SESSION['lang']))$_GET['lang'] = $_SESSION['lang'];else $_SESSION['lang'] = "";
    if(!empty($_SESSION['area']))$_GET['area'] = $_SESSION['area'];else $_SESSION['area'] = "";
    if(!empty($_SESSION['price']))$_GET['price'] = $_SESSION['price'];else $_SESSION['price'] = "";
    if(!empty($_SESSION['type']))$_GET['type'] = $_SESSION['type'];else $_SESSION['type'] = "";
    if(!empty($_SESSION['other']))$_GET['other'] = $_SESSION['other'];else $_SESSION['other'] = "";
    if(!empty($_SESSION['other2']))$_GET['other2'] = $_SESSION['other2'];else $_SESSION['other2'] = "";
}

include_once("common/templates/header.tpl");

?>
    <div id="contents" class="clearfix">
    	<div class="main-cont">
            <img src="common/images/main/tb.png" class="theader01" alt="">
            <div class="searchbox">
                <img src="common/images/main/search_title.png" alt="希望条件を選択してください。">
                <ul class="tabs clearfix">
                    <li id="skill" class="tab active"><a href="#cont1">スキル1</a></li>
                    <li id="blank" class="tab"><a href="#cont6">スキル2</a></li>
                    <li id="area" class="tab"><a href="#cont2">地域</a></li>
                    <li id="price" class="tab"><a href="#cont3">金額</a></li>
                    <li id="work" class="tab"><a href="#cont4">業種</a></li>
                    <li id="other" class="tab"><a href="#cont5">その他</a></li>
                </ul>
                <div class="panels">
                    <form method="get" action="search.php">
                    <div id="cont1" class="panel active">
                        <ul class="bg_checkbox clearfix">
                            <?php
                            //スキル一覧表示
                            $trg = "lang";
                            $cnt = count($sl[$trg]);
                            if(isset($dt['lang'])) {
                                $lang = mbsplit(" ", $dt[$trg]);
                                $cnt2 = count($lang);
                            }elseif(isset($_GET[$trg])){
                                $lang = $_GET[$trg];
                                $cnt2 = count($lang);
                            }
                            for($i = 0;$i<$cnt;++$i){
                                $set_type = $sl[$trg][$i]['text'];
                                $chk = "";
                                //データがあれば、チェックを入れる Cookieに変更
                                if(isset($_COOKIE["chk"]) or isset($_GET['lang'])) {
                                    for ($z = 0; $z < $cnt2; ++$z) {
                                        if($lang[$z] == $set_type) $chk = "checked";
                                    }
                                }
                                $set_type=str_replace('_',' ',$set_type);
                                echo '<li><label><input name="lang[]" value="'.$set_type.'" type="checkbox"'.$chk.'>'.$set_type.'</label></li>';
                            }
                            ?>
                        </ul>
                    </div>
                    <div id="cont2" class="panel">
                        <ul class="bg_checkbox clearfix">
                            <?php
                            //スキル一覧表示
                            $trg = "area";
                            $cnt = count($sl[$trg]);
                            if(isset($dt[$trg])){
                                $lang = mbsplit(" ",$dt[$trg]);
                                $cnt2 = count($lang);
                            }elseif(isset($_GET[$trg])){
                                $lang = $_GET[$trg];
                                $cnt2 = count($lang);
                            }
                            for($i = 0;$i<$cnt;++$i){
                                $set_type = $sl[$trg][$i]['text'];
                                $chk = "";
                                //データがあれば、チェックを入れる Cookieに変更
                                if(isset($_COOKIE["chk"]) or isset($_GET[$trg])) {
                                    for ($z = 0; $z < $cnt2; ++$z) {
                                        if($lang[$z] == $set_type) $chk = "checked";
                                    }
                                }
                                echo '<li><label><input name="'.$trg.'[]" value="'.$set_type.'" type="checkbox"'.$chk.'>'.$set_type.'</label></li>';
                            }
                            ?>
                        </ul>
                    </div>
                    <div id="cont3" class="panel">
                        <ul class="bg_checkbox clearfix">
                            <?php
                            //スキル一覧表示
                            $trg = "price";
                            $cnt = count($sl[$trg]);
                            if(isset($dt[$trg])){
                                $lang = mbsplit(" ",$dt[$trg]);
                                $cnt2 = count($lang);
                            }elseif(isset($_GET[$trg])){
                                $lang = $_GET[$trg];
                                $cnt2 = count($lang);
                            }
                            for($i = 0;$i<$cnt;++$i){
                                $set_type = $sl[$trg][$i]['text'];
                                $chk = "";
                                //データがあれば、チェックを入れる Cookieに変更
                                if(isset($_COOKIE["chk"])  or isset($_GET[$trg])) {
                                    for ($z = 0; $z < $cnt2; ++$z) {
                                        if($lang[$z] == $set_type) $chk = "checked";
                                    }
                                }
                                echo '<li><label><input name="'.$trg.'[]" value="'.$set_type.'" type="checkbox"'.$chk.'>'.$set_type.'</label></li>';
                            }
                            ?>
                        </ul>
                    </div>
                    <div id="cont4" class="panel">
                        <ul class="bg_checkbox clearfix">
                            <?php
                            //スキル一覧表示
                            $trg = "type";
                            $cnt = count($sl[$trg]);
                            if(isset($dt[$trg])){
                                $lang = mbsplit(" ",$dt[$trg]);
                                $cnt2 = count($lang);
                            }elseif(isset($_GET[$trg])){
                                $lang = $_GET[$trg];
                                $cnt2 = count($lang);
                            }
                            for($i = 0;$i<$cnt;++$i){
                                $set_type = $sl[$trg][$i]['text'];
                                $chk = "";
                                //データがあれば、チェックを入れる Cookieに変更
                                if(isset($_COOKIE["chk"])  or isset($_GET[$trg])) {
                                    for ($z = 0; $z < $cnt2; ++$z) {
                                        if($lang[$z] == $set_type) $chk = "checked";
                                    }
                                }
                                echo '<li><label><input name="'.$trg.'[]" value="'.$set_type.'" type="checkbox"'.$chk.'>'.$set_type.'</label></li>';
                            }
                            ?>
                        </ul>
                    </div>
                    <div id="cont5" class="panel">
                        <ul class="bg_checkbox clearfix">
                            <?php
                            //スキル一覧表示
                            $trg = "other";
                            $cnt = count($sl[$trg]);
                            if(isset($dt[$trg])){
                                $lang = mbsplit(" ",$dt[$trg]);
                                $cnt2 = count($lang);
                            }elseif(isset($_GET[$trg])){
                                $lang = $_GET[$trg];
                                $cnt2 = count($lang);
                            }
                            for($i = 0;$i<$cnt;++$i){
                                $set_type = $sl[$trg][$i]['text'];
                                $chk = "";
                                //データがあれば、チェックを入れる Cookieに変更
                                if(isset($_COOKIE["chk"])  or isset($_GET[$trg])) {
                                    for ($z = 0; $z < $cnt2; ++$z) {
                                        if($lang[$z] == $set_type) $chk = "checked";
                                    }
                                }
                                echo '<li><label><input name="'.$trg.'[]" value="'.$set_type.'" type="checkbox"'.$chk.'>'.$set_type.'</label></li>';
                            }
                            ?>
                        </ul>
                    </div>
                        <div id="cont6" class="panel">
                            <ul class="bg_checkbox clearfix">
                                <?php
                                //スキル一覧表示
                                $trg = "other2";
                                $cnt = count($sl[$trg]);
                                if(isset($dt[$trg])){
                                    $lang = mbsplit(" ",$dt[$trg]);
                                    $cnt2 = count($lang);
                                }elseif(isset($_GET[$trg])){
                                    $lang = $_GET[$trg];
                                    $cnt2 = count($lang);
                                }
                                for($i = 0;$i<$cnt;++$i){
                                    $set_type = $sl[$trg][$i]['text'];
                                    $chk = "";
                                    //データがあれば、チェックを入れる Cookieに変更
                                    if(isset($_COOKIE["chk"])  or isset($_GET[$trg])) {
                                        for ($z = 0; $z < $cnt2; ++$z) {
                                            if($lang[$z] == $set_type) $chk = "checked";
                                        }
                                    }
                                    echo '<li><label><input name="'.$trg.'[]" value="'.$set_type.'" type="checkbox"'.$chk.'>'.$set_type.'</label></li>';
                                }
                                ?>
                            </ul>
                        </div>
                    <img src="common/images/main/selected.png" alt="選択した項目">
                    <div class="clearfix">
                        <div class="selected">
                            <ul class="clearfix" id="chklist"></ul>
                        </div>
                        <input type="submit" class="search" value="検索">
                    </div>
                </div>
                </form>
            </div>
            <div class="list-title">
                <img src="common/images/main/new.png" alt="New 新着案件">
                <p>今週の求人情報をご紹介します。</p>
            </div>
            <table class="list">
                <?php
                //リスト一覧表示
                $listcnt = count($list);
                for($i=0;$i<$listcnt;++$i){
                $id = $list[$i]['id'];
                $post = $list[$i]['post'];
                $title = $list[$i]['title'];
                $lang = $list[$i]['lang'];
                $area = $list[$i]['area'];
                $types = $list[$i]['types'];
                $other = $list[$i]['other'];
                //hidden value
                $seg = $list[$i]['seg'];
                $price = $list[$i]['price'];
                    $seg0 = "";
                    if($seg == "未経験") $seg0 = 1;
                    if($seg == "オススメ") $seg0 = 2;
                    if($seg == "急募") $seg0 = 3;
                    if($seg == "高額") $seg0 = 4;
                    if($seg == "即決") $seg0 = 5;
                ?>
                <tr>
                    <th>
                        <?php if($seg0){?>
                        <img src="common/images/seg/0<?php echo $seg0?>.gif" alt="<?php echo $seg?>">
                        <?php }else{echo $seg?>
                        <?php }?>
                    </th>
                    <td class="first"><a href="/detail/<?php echo $id?>"><?php echo $title?></a></td>
                    <td><?php echo $area?></td>
                    <td><?php echo $price?></td>
                </tr>
                <?php }?>
            </table>
        </div>
<?php include_once("common/templates/footer.tpl") ?>