<?php

require_once("config.php");
require_once("system/functions.php");

ob_start();

if(strtolower($_POST["process"]) === strtolower(lang("login_proceed")))
{
	$user = strtolower($_POST["user"]);
	$pass = $_POST["pass"];
	
	if(get_magic_quotes_gpc())
	{
		$user = stripslashes($user);
		$pass = stripslashes($pass);
	}
	
	$user = mysql_real_escape_string($user);
	$pass = md5($pass);
	
	$sql = "SELECT * FROM users WHERE username='".$user."' AND password='".$pass."' LIMIT 0, 1";
	$query = mysql_query($sql, $sql_con);
	$data = mysql_fetch_array($query);
	if($data["username"] === $user && strlen($user) > 0 && strlen($pass) > 0)
	{
		$_SESSION["login_id"] = $data["id"];
		$_SESSION["login_valid"] = true;
		$_SESSION["login_lang"] = strtolower($data["lang"]);
		setcookie("login", $data["id"]);
		$login = true;
	}
	else echo '<div style="padding:4px;color:#F00;">'.lang("error_invalid_login").'</div>';
}

if($login)
	header("Location: index.php");

$page_title = lang("login");

echo '<h2>'.lang("login").'</h2>';
echo '<form action="login.php" method="post">';
echo '<label for="user">'.lang("login_username").': </label><input class="textbox" name="user" type="text" /><br />';
echo '<label for="pass">'.lang("login_password").': </label><input class="textbox" name="pass" type="password" /><br />';
echo '<input class="button" name="process" value="'.lang("login_proceed").'" type="submit" />';

$page_content = ob_get_contents();
ob_end_clean();

require_once("template.php");

?>