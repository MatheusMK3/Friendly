<?php

session_start();

error_reporting(E_ERROR | E_WARNING | E_PARSE);

global $sql_user;
global $sql_pass;
global $sql_db;
global $sql_con;
global $login;
global $lang;

$sql_user = "root";	// Your MySQL Username
$sql_pass = "";	// Your MySQL Password
$sql_db = "_friendly";	// Your MySQL Database
$login = false;
$lang = "en";	// Default Language

if($_SESSION["login_id"] === $_COOKIE["login"] && $_SESSION["login_valid"]) $login = true;

if(!$login && strlen($_COOKIE["lang"]) > 0 && $_COOKIE["lang"] !== false) $_SESSION["login_lang"] = $_COOKIE["lang"];

if($_SESSION["login_lang"] !== false && strlen($_SESSION["login_lang"]) > 0) $lang = $_SESSION["login_lang"];
require_once("lang/".strtolower($lang).".lang.php");

$sql_con = mysql_connect("localhost", $sql_user, $sql_pass) or die(mysql_error());
mysql_select_db($sql_db, $sql_con) or die(mysql_error());

?>