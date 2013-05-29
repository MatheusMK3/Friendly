<?php

require_once("config.php");
require_once("system/functions.php");

$count = 10;

$mode = $_GET["mode"];
$user = $_GET["user"];
$last = $_GET["last"];
$start = $_GET["start"];

if($start === false || strlen($start) == 0) $start = 0;
if($user === false || strlen($user) == 0) $user = false;

if($user === false) $sql = "SELECT * FROM updates WHERE id > '".mysql_real_escape_string($last)."' ORDER BY time DESC LIMIT ".mysql_real_escape_string($start).", ".$count;
else $sql = "SELECT * FROM updates WHERE author='".mysql_real_escape_string($user)."' AND id > '".mysql_real_escape_string($last)."' ORDER BY time DESC LIMIT ".mysql_real_escape_string($start).", ".$count;

$query = mysql_query($sql, $sql_con);

// This will handle AJAX posts

switch($mode)
{
	case "count":
		echo mysql_num_rows($query);
		break;
	case  "update":
		$stream_last = 0;
		while($row = mysql_fetch_array($query))
		{
			$sender = getUser($row["author"]);
			echo '<div class="update">';
			echo '<div class="updateInfo" style="color:#666;"><strong><a href="profile.php?uid='.$sender["id"].'">'.$sender["name"].'</a></strong> - '.doDate($row["time"]).'</div>';
			echo '<div class="updateBody">'.doText($row["content"], true).'</div>';
			echo '</div>';
			if($row["id"] > $stream_last) $stream_last = $row["id"];
		}
		if($stream_last < $last) $stream_last = $last;
		if(mysql_num_rows($query) == 0) echo '<<NULL>>';
		echo '<script type="text/javascript">stream_last = '.$stream_last.';</script>';
		break;
	case  "updateall":
		$users = "author='".mysql_real_escape_string($user)."'";
		
		$friends = getFriends($user, "1");
		foreach($friends as $friend)
		{
			$users .= " OR author='".$friend["id"]."'";
		}
		
		$sql = "SELECT * FROM updates WHERE time > '".mysql_real_escape_string($last)."' AND (".$users.") ORDER BY time DESC LIMIT ".mysql_real_escape_string($start).", ".$count;
		$query = mysql_query($sql, $sql_con);
		
		$stream_last = 0;
		while($row = mysql_fetch_array($query))
		{
			$sender = getUser($row["author"]);
			echo '<div class="update">';
			echo '<div class="updateInfo" style="color:#666;"><strong><a href="profile.php?uid='.$sender["id"].'">'.$sender["name"].'</a></strong> - '.doDate($row["time"]).'</div>';
			echo '<div class="updateBody">'.doText($row["content"], true).'</div>';
			echo '</div>';
			if($row["time"] > $stream_last) $stream_last = $row["time"];
		}
		if($stream_last < $last) $stream_last = $last;
		if(mysql_num_rows($query) == 0) echo '<<NULL>>';
		echo '<script type="text/javascript">stream_last = '.$stream_last.';</script>';
		break;
	case "post":
		if($login && strlen($_POST["body"]) > 0) {
			$body = $_POST["body"];
			if(get_magic_quotes_gpc()) $body = stripslashes($body);
			$sql = "INSERT INTO updates(author, time, content) VALUES('".mysql_real_escape_string($_SESSION["login_id"])."', '".mysql_real_escape_string(time())."', '".mysql_real_escape_string($body)."')";
			mysql_query($sql, $sql_con);
		}
		break;
}

?>