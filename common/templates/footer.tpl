<div class="sub-cont">
    <div class="banners">
    <?php
    //SELECT * FROM banner WHERE latest = '1' AND post = '1'
        $banner = $db->get_all("SELECT * FROM banner WHERE types = '' AND  area = '' AND seg = '' AND other = '' AND other2 = '' AND lang = '' AND post = '1' ORDER BY latest ASC");
        foreach($banner as $i=>$v){
            $id = $banner[$i]['id'];
            $title = $banner[$i]['title'];
            $link = $banner[$i]['link'];
            $trg = strstr($link, "http");
            $blank = "";
            if($trg) $blank =  'target="_blank" rel="nofollow"';
            if($link)
            echo '<a href="'.$link.'"'.$blank.'><img src="/src/'.$id.'" alt="'.$title.'"></a>';
            else
            echo '<img src="/src/'.$id.'" alt="'.$title.'">';
         }

    if(isset($bn)) {
        foreach ($bn as $i => $v) {
        $id = $bn[$i]['id'];
        $title = $bn[$i]['title'];
        $link = $bn[$i]['link'];
        $trg = strstr($link, "http");
        $blank = "";
        if ($trg) $blank = 'target="_blank" rel="nofollow"';
        if($link)
        echo '<a href="' . $link . '"' . $blank . '><img src="/src/' . $id . '" alt="' . $title . '"></a>';
        else
        echo '<img src="/src/' . $id . '" alt="' . $title . '">';
        }
    }
    ?>
        </div>
    <img src="/common/images/sub/selected.png" alt="選択中案件">
    <form id="eform" method="post" action="">
        <ul class="bg_checkbox2"></ul>
        <div class="sub-entry">
            <p>スキルシートをお持ちでない方は、メールアドレスをご記入下さい。</p>
            <input type="text" name="email" id="email" value="" placeholder="メアド入力だけで簡単エントリー">
            <p>スキルシートをお持ちの方は、添付して下さい。</p>
            <div class="file"><img src="/common/images/sub/file.png" alt="添付"><input type="file" name="file"></div>
            <div id="entry">ENTRY</div>
        </div>
    </form>
</div>
</div>
<!--/contents-->
<div id="footer">
    <div class="inner">
        <div class="logo">株式会社BRIDGE</div>
    </div>
    <ul>
        <li><a href="/">ホーム</a></li><!--
                --><li><a href="/search.php?recommended=1">オススメ案件</a></li><!--
                --><li><a href="/policy.php">サイトポリシー</a></li><!--
                --><li><a href="/cp.php">会社概要</a></li>
    </ul>
    <p>Copyright© 2015 BRIDGE Co. All Rights Reserved.</p>
</div>
<!--/footer-->
</div>
<!--/container-->
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<script>window.jQuery || document.write("<script src='/common/js/jquery.min.js'>\x3C/script>")</script>
<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+"://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
<!-- build:js /common/js/all.min.js -->
<script src="/common/js/jquery.cookie.js"></script>
<?php if(isset($thispage) and $thispage == "detail" and $d['addr'] and !$d['map'] and isset($this_is_not_set)){ ?>
<script src="/common/js/map.js"></script>
<script type="text/javascript">
    var url = $.staticMap({
        markerIcon : 'http://tinyurl.com/2ftvtt6',
        address : '<?= $d['addr']?>',
        width : 290,
        height : 200,
        zoom : 14
    });
    $('#staticMap').attr('src', url);
    var urlLive = $.liveMapLink({
        address : "<?= $d['addr']?>",
        zoom : 12
    });
    $('.liveMap').attr('href', urlLive);
</script>
<?php }?>
<script src="/common/js/common.js"></script>
<!-- endbuild -->
</body>
</html>