<?php
session_start();
$msg = "";
if(isset($_GET['logoff'])){
    session_destroy();
    $msg=
        '<div class="panel-body">
        <div class="alert alert-success">
        ログアウトが完了しました。
        </div>
        </div>';
}
// ログインボタンが押された場合
include('libs/cl_password.php');
$p = new Password();//5.3ver password_verify
$hash = $p->hash('password');

$errorMessage = "";
if (isset($_POST["login"])) {
    if (empty($_POST["id"])) {
        $errorMessage = "IDを入力してください";
    } else if (empty($_POST["password"])) {
        $errorMessage = "パスワードを入力してください";
    }
//2．ユーザIDとパスワードが入力されていたら認証する
    if (!empty($_POST["id"]) && !empty($_POST["password"])) {
        // mysqlへの接続
        include('libs/db_pod.php');
        $db = new db;
        $res = $db->get_all('SELECT id,pw FROM admin WHERE id = "'.$_POST['id'].'"');
        if(isset($res[0])) $res = $res[0];
//        echo "<pre>";print_r($res); echo "</pre>";
//        echo "<pre>";print_r($_POST); echo "</pre>";

//3.    認証
        if($res and $p->verify($_POST["password"], $res['pw'])){
           // if (password_verify($_POST["password"], $res['pw'])) {
            // 認証成功なら、セッションIDを新規に発行する
            include('libs/cl_session.php');
            $enc = new cl_Session();
            $_SESSION["USERID"] = $_POST["id"];
            $_SESSION["KEY"] = $enc->set_encrypt($_POST["id"]);
            header("Location: top.php");
            exit;
        }else{
            $msg=
                '<div class="panel-body">
                <div class="alert alert-danger">
                エラー：ログインIDもしくはパスワードが違います。
                </div>
                </div>';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <title>Login</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/timeline.css" rel="stylesheet">
    <link href="css/sb-admin-2.css" rel="stylesheet">
    <link href="libs/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
</head>
<body>
<div class="container">
    <div class="row">
        <?php if($msg) echo $msg;?>
        <div class="col-md-4 col-md-offset-4">
            <div class="login-panel panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Login</h3>
                </div>
                <div class="panel-body">
                    <form role="form" method="post" action="" name="form">
                        <p class="text-danger"><?php echo $errorMessage?></p>
                        <fieldset>
                            <div class="form-group">
                                <input class="form-control" placeholder="ID" name="id" type="text" autofocus>
                            </div>
                            <div class="form-group">
                                <input class="form-control" placeholder="Password" name="password" type="password" value="">
                            </div>
                            <input type="submit" value="Login" name="login" class="btn btn-lg btn-success btn-block">
                        </fieldset>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>