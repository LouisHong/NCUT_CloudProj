<?php
include_once "model/main.php";
$main = new Main();
session_start();
unset($_SESSION['uid']);
unset($_SESSION['username']);
unset($_SESSION['cart_token']);
$main->myUrl();
?>