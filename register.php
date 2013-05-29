<?php

require_once("config.php");
require_once("system/functions.php");

ob_start();

if(strtolower($_POST["process"]) === strtolower(lang("register_proceed")))
{
	$user = strtolower($_POST["user"]);
	$pass = $_POST["pass"];
	$cpass = $_POST["cpass"];
	$email = $_POST["email"];
	$name = $_POST["name"];
	$desc = $_POST["desc"];
	$imageurl = "static/unknown.jpg";
	
	if(get_magic_quotes_gpc())
	{
		$user = stripslashes($user);
		$pass = stripslashes($pass);
		$cpass = stripslashes($cpass);
		$email = stripslashes($email);
		$name = stripslashes($name);
		$desc = stripslashes($desc);
	}
	
	$user = mysql_real_escape_string(str_replace(" ", "", $user));
	$pass = md5($pass);
	$cpass = md5($cpass);
	$email = mysql_real_escape_string($email);
	$name = mysql_real_escape_string($name);
	$desc = mysql_real_escape_string($desc);
	$imageurl = mysql_real_escape_string($imageurl);
	
	$valid = true;
	
	if(!ctype_alnum($user) || strpos($user, " ") !== false) { echo '<div style="padding:4px;color:#F00;">'.lang("error_invalid_username").'</div>'; $valid = false; }
	if(strlen($name) == 0) { echo '<div style="padding:4px;color:#F00;">'.lang("error_invalid_name").'</div>'; $valid = false; }
	if($pass !== $cpass) { echo '<div style="padding:4px;color:#F00;">'.lang("error_invalid_password").'</div>'; $valid = false; }
	if(!is_valid_email($email)) { echo '<div style="padding:4px;color:#F00;">'.lang("error_invalid_email").'</div>'; $valid = false; }
	
	if($valid)
	{	
		$sql = "INSERT INTO users (username, password, email, name, description, imageurl, lang) VALUES ('".$user."', '".$pass."', '".$email."', '".$name."', '".$desc."', '".$imageurl."', '".mysql_real_escape_string(lang(".id_code"))."')";
		mysql_query($sql, $sql_con);
		$sql = "SELECT * FROM users WHERE username='".$user."' AND password='".$pass."' LIMIT 0, 1";
		$query = mysql_query($sql, $sql_con);
		$data = mysql_fetch_array($query);
		if($data["username"] === $user && strlen($user) > 0 && strlen($pass) > 0)
		{
			$_SESSION["login_id"] = $data["id"];
			$_SESSION["login_valid"] = true;
			setcookie("login", $data["id"]);
			$login = true;
		}
		else echo '<div style="padding:4px;color:#F00;">'.lang("error_username_taken").'</div>';
	}
}

if($login)
	header("Location: index.php");

$page_title = lang("registration");

echo '<h2>'.lang("registration").'</h2>';
echo '<form action="register.php" method="post">';
echo '<label style="width:200px;display:inline-block;text-align:right;" for="user">'.lang("register_username").': </label><input class="textbox" name="user" type="text" /><br />';
echo '<label style="width:200px;display:inline-block;text-align:right;" for="pass">'.lang("register_password").': </label><input class="textbox" name="pass" type="password" /><br />';
echo '<label style="width:200px;display:inline-block;text-align:right;" for="cpass">'.lang("register_confirm").': </label><input class="textbox" name="cpass" type="password" /><br />';
echo '<label style="width:200px;display:inline-block;text-align:right;" for="email">'.lang("register_email").': </label><input class="textbox" name="email" type="text" /><br />';
echo '<div style="margin:8px 160px;border-top:1px solid #DDD;height:0px;display:block;">&nbsp;</div>';
echo '<label style="width:200px;display:inline-block;text-align:right;" for="name">'.lang("register_name").': </label><input class="textbox" name="name" type="text" /><br />';
echo '<label style="width:200px;display:inline-block;text-align:right;" for="desc">'.lang("register_description").': </label><input class="textbox" name="desc" type="text" /><br />';
echo '<input style="margin-left:200px;" class="button" name="process" value="'.lang("register_proceed").'" type="submit" />';

$page_content = ob_get_contents();
ob_end_clean();

require_once("template.php");

?>