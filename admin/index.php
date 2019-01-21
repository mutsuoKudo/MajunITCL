<?php
if($_POST['password'] == "adminpass") {
    header("Location:top.php");
}else{
    header("Location:login.php");
}