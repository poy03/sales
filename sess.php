<?php
session_start();
$x = array();
$x = $_GET['user'];
$_SESSION['user']=$x;
var_dump($_SESSION['user']);
?>  