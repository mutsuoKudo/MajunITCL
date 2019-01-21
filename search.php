<?php
$page = "search";
include_once('common/db_main.php');
//全案件取得
$db = new db;
$lang = "";
$type = "";
$price = "";
$area = "";
$other = "";
$other2 = "";
if(isset($_POST['lang'])) $lang = $_POST['lang'];
if(isset($_POST['area'])) $area = $_POST['area'];
if(isset($_POST['price'])) $price = $_POST['price'];
if(isset($_POST['type'])) $type = $_POST['type'];
if(isset($_POST['other'])) $other = $_POST['other'];
if(isset($_GET['lang'])) $lang = $_GET['lang'];
if(isset($_GET['area'])) $area = $_GET['area'];
if(isset($_GET['price'])) $price= $_GET['price'];
if(isset($_GET['type'])) $type = $_GET['type'];
if(isset($_GET['other'])) $other = $_GET['other'];
if(isset($_GET['other2'])) $other2 = $_GET['other2'];
if(isset($_GET['recommended']))$rec = 1;else $rec ="";
$s_lang = "";
//言語
if(!empty($lang)) {
    foreach ($lang as $key => $value) {
        $value=str_replace(' ','_',$value);
        if ($key != 0) {
            $s_lang .= " OR lang LIKE '%" . $value . "%'";
            if($value == "Java"){
                $s_lang .= "OR lang LIKE 'Java' OR lang LIKE '%" . $value . " %'";
            }
        }else {
            if ($value == "C") {
                $s_lang .= "(lang LIKE 'C' OR lang LIKE '" . $value . " %'";
            } elseif ($value=="Java"){
                $s_lang .= "(lang LIKE 'Java' OR lang LIKE '%" . $value . " %'";
            }else {
                $s_lang .= "(lang LIKE '%" . $value . "%'";
            }
        }
    }
}
if($s_lang)$s_lang .= ")";
//検索項目
$code[] = in_sql($area,"area");
$code[] = in_sql($price,"price");
$code[] = in_sql($type,"types");
$code[] = in_sql($other,"other");
$code[] = in_sql($other2,"other2");
$code[] = $s_lang;
$code = array_values(array_filter($code));
$sql ="";
$sql2 ="";
foreach ($code as $key => $value) {
    if ($key == 0) {
        $sql = "SELECT * FROM anken WHERE post = '1' AND del = 0 AND " . $value . " ";
        $sql2 = "SELECT id, title, link FROM banner WHERE post = '1' AND " . $value . " ";
    }else {
        $sql .= " AND " . $value . "";
        $sql2 .= " AND " . $value . "";
    }
}
if(isset($_GET['debug']) and $_GET['debug'] == "egm") {
echo $sql." ORDER BY latest DESC";
echo "<pre>";print_r($code); echo "</pre>";
}

//SQL指定
if($rec) $sql = "SELECT * FROM anken WHERE post = '1' AND del = 0 AND seg = 'オススメ'";
if(!$sql) $sql ="SELECT * FROM anken WHERE post = '1' AND del = 0 ";
$list = $db->get_all($sql." ORDER BY latest DESC");

//バナー結果
$bn = $db->get_all($sql2." AND NOT latest = '1' ORDER BY latest ASC");

//echo "<pre>";print_r($bn); echo "</pre>";


//SQLまとめ
function in_sql($str,$name){
    if(isset($str[0])){
        $line = $name." IN (";
        foreach($str as $key=>$value){
            if($key ==0)
                $line .= "'".$value."'";
            else
                $line .= ",'".$value."'";
        }
        return $line.")";
    }
}

session_start();
if(empty($list[0])) {
//$back_url = str_replace("/search.php", '/', $_SERVER["REQUEST_URI"]);
    if (isset($_GET['lang'])) $_SESSION['lang'] = $_GET['lang']; else $_SESSION['lang'] = "";
    if (isset($_GET['area'])) $_SESSION['area'] = $_GET['area']; else $_SESSION['area'] = "";
    if (isset($_GET['price'])) $_SESSION['price'] = $_GET['price']; else $_SESSION['price'] = "";
    if (isset($_GET['type'])) $_SESSION['type'] = $_GET['type']; else $_SESSION['type'] = "";
    if (isset($_GET['other'])) $_SESSION['other'] = $_GET['other']; else $_SESSION['other'] = "";
    if (isset($_GET['other2'])) $_SESSION['other2'] = $_GET['other2']; else $_SESSION['other2'] = "";
    $link = "/?t=1";
}else{
    $link = "/";
}

include_once("common/templates/header.tpl");
?>
    <div id="contents" class="clearfix">
    	<div class="main-cont">
            <div class="list-title">
                <?php if($rec) $src = "osusume"; else $src = "search_res";?>
                <img src="common/images/main/<?php echo $src?>.png" alt="検索結果">
            </div>
            <?php if(empty($list[0])){?>
                    <div class="searchbox">
                        <div class="panels empty">
                            誠に申し訳ございません。該当条件の案件は現在ありません。<br>
                            お手数ですが、条件を変更して再度 検索してください。<br>
                            <a href="<?=$link?>">条件を変更して再度検索する >></a>
                            </p>
                        </div>
                    </div>
            <?php }?>
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