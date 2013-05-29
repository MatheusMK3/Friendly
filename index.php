<?php

require_once("config.php");
require_once("system/functions.php");

ob_start();

if($login)
{
	// This part will handle the user homepage, show an AJAX stream, etc.
	$me = getUser($_SESSION["login_id"]);
	$myFriends = getFriends($me["id"], "1");
	
	$count = 0;
	
	echo '<div class="profileSide" style="width: 110px;">';
	echo '<div style="text-align:left;">';
	
	$display = array();
	$ids = array();
	
	foreach($myFriends as $friend)
	{
		if($count >= 10) break;
		echo '<a style="padding:4px;" class="button" href="profile.php?uid='.$friend["id"].'" title="'.$friend["name"].'"><img width="32" height="32" src="'.$friend["imageurl"].'" /></a>';
		$count++;
	}
	echo '</div>';
	if(count($myFriends) > 10) echo '<a href="friends.php">'.lang("home_allfriends").'</a>';
	echo '</div>';

	echo '<div class="profileMain">';
	echo '<div class="profileName">'.lang("stream_my").'</div>';
	
	$sql = "SELECT * FROM connections WHERE id2='".$_SESSION["login_id"]."' AND status='0'";
	$query_friends = mysql_query($sql, $sql_con);
	if(mysql_num_rows($query_friends) > 0)
	{
		echo '<div class="notification">'.lang("notification_friend_request", array("count" => mysql_num_rows($query_friends))).' <a href="friends.php?mode=requests">'.lang("notification_friend_request_view").'</a></div>';
	}
	
	echo '<div id="stream"><span id="stream_loading">'.lang("stream_loading").'</span></div>';
	echo '<script type="text/javascript">uid = "'.$_SESSION["login_id"].'"; stream_interval = 15000; stream_mode = "updateall"; stream_update(); stream_show = false;</script>';
	echo '</div>';
}
else
{
	// Website default page
	if($_GET["lang"])
	{
		$lang = $_GET["lang"];
		setcookie("lang", $lang, time() + 60*60*24*365);
		$_SESSION["login_lang"] = $lang;
		header("Location: index.php");
	}

	echo '<a href="?lang=en" style="margin:0px 8px;">'.htmlentities("English").'</a>';
	echo '<a href="?lang=pt" style="margin:0px 8px;">'.htmlentities("Português").'</a>';
	
	echo '<p>Friendly is made to help you sharing you and your interests with your friends and the world.</p>';		
	echo '<p>Below there are some people that use Friendly:</p>';
	echo '<div id="homeBar"><div>';
	
	$sql = "SELECT * FROM users ORDER BY RAND() LIMIT 0, 20";
	$query = mysql_query($sql, $sql_con);
	while($row = mysql_fetch_array($query))
	{
		echo '<a href="profile.php?uid='.$row["id"].'">';
		echo '<img src="'.htmlspecialchars($row["imageurl"]).'" alt="'.htmlspecialchars($row["name"]).'" title="'.htmlspecialchars($row["name"]).'" />';
		echo '</a>';
	}
	
	echo '</div></div>';
}

$page_content = ob_get_contents();
ob_end_clean();

require_once("template.php");

?>