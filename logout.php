<?php

require_once("config.php");
require_once("system/functions.php");

ob_start();

// Destroy sessions and log the user off

unset($_SESSION["login_id"]);
unset($_SESSION["login_valid"]);
session_destroy();
setcookie("login", "", 0);
$login = false;

header("Location: index.php");

$page_content = ob_get_contents();
ob_end_clean();

require_once("template.php");

?>