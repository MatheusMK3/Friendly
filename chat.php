<?php

// Configs
require_once("config.php");
require_once("system/functions.php");

// Chats per request
$count = 10;

// Variables
$mode = $_GET["mode"];
$user = $_GET["uid"];
$last = $_GET["last"]; // Last request time

// No last request
if($last == false || strlen($last) == 0) $last = 0;

switch($mode)
{
	// Send new chat
	case "post":
		if($login && strlen($_POST["body"]) > 0) {
			$body = $_POST["body"];
			if(get_magic_quotes_gpc()) $body = stripslashes($body);
			$sql = "INSERT INTO webchat(id1, id2, message, time) VALUES('".mysql_real_escape_string($_SESSION["login_id"])."', '".mysql_real_escape_string($user)."', '".mysql_real_escape_string($body)."', '".mysql_real_escape_string(time())."')";
			mysql_query($sql, $sql_con);			
		}
		break;
	// Check for new chats
	case "check":		
		$new = array();
		
		$sql = "SELECT * FROM webchat WHERE isread='0' AND id2='".mysql_real_escape_string($_SESSION["login_id"])."'";
		$query = mysql_query($sql, $sql_con);
		
		while($row = mysql_fetch_array($query))
		{
			if(!in_array($row["id1"], $new)) $new[] = $row["id1"];
		}
		
		if(count($new) > 0)
		{
			$html_messages = "";
			
			foreach($new as $id)
			{
				$html_messages .= 'chatNew('.$id.');';
			}
			
			echo '<script type="text/javascript">'.$html_messages.'</script>';
		}
		break;
	// Retrieve new chats
	case "get":
	default:
		$sql = "SELECT * FROM webchat WHERE id1='".mysql_real_escape_string($_SESSION["login_id"])."' OR id2='".mysql_real_escape_string($_SESSION["login_id"])."' ORDER BY time ASC";
		$query = mysql_query($sql, $sql_con);
		
		$new = array();
		
		while($row = mysql_fetch_array($query))
		{
			$new[] = $row;
			
			$sender = getUser($row["id1"]);
			
			if(($row["id1"] == $user && $row["id2"] == $_SESSION["login_id"]) || ($row["id1"] == $_SESSION["login_id"] && $row["id2"] == $user))
			echo '<div class="chatMessage" style="line-height:8px;"><img src="'.htmlentities($sender["imageurl"]).'" />'.doText(breakText($row["message"]), true).'</div>';
		}
		
		if(count($new) > 0)
		{
			$sql = "UPDATE webchat SET isread='1' WHERE id2='".mysql_real_escape_string($_SESSION["login_id"])."' AND (";
			for($i=0;$i<count($new);$i++)
			{
				$row = $new[$i];
				$sql .= "(id1='".mysql_real_escape_string($row["id1"])."' AND message='".mysql_real_escape_string($row["message"])."' AND time='".mysql_real_escape_string($row["time"])."')";
				if($i < count($new) - 1) $sql .= " OR ";
			}
			$sql .= ")";
			
			mysql_query($sql, $sql_con);
		}
		
		echo '<script type="text/javascript">chatLast = '.time().';</script>';
		
		break;	
}

?>