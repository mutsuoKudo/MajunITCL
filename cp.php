<?php
include_once('common/db_main.php');
$db = new db;
include_once("common/templates/header.tpl");
?>
    <div id="contents" class="clearfix">
    	<div class="main-cont">
            <div class="list-title">
                <img src="/common/images/main/cp.png" alt="会社概要" class="fix">
            </div>
                <table class="cp">
                    <tr>
                        <th>社名</th>
                        <td>株式会社 ＢＲＩＤＧＥ</td>
                    </tr>
                    <tr>
                        <th>設立年月日</th>
                        <td>2008年4月</td>
                    </tr>
                    <tr>
                        <th>本社</th>
                        <td>東京都中央区銀座 7-17-2<br>
                            アーク銀座ビルディング 6 階 <a href="http://goo.gl/vdj4G4" target="_blank" rel="nofollow">【Map】</a>
                        </td>
                    </tr>
                    <tr>
                        <th>代表取締役</th>
                        <td>熊谷 典久</td>
                    </tr>
                    <tr>
                        <th>資本金</th>
                        <td>33,000,000円</td>
                    </tr>
                    <tr>
                        <th>事業内容</th>
                        <td>IT ソリューションサービス<br>
                            Web ソリューションサービス<br>
                            BPO サービス・ヘルプデスク<br>
                            教育ビジネス<br>
                            人材派遣サービス<br>
                            （特定派遣番号 特 13-317196）
                        </td>
                    </tr>
                </table>
        </div>
<?php include_once("common/templates/footer.tpl") ?>