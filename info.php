<?php

require_once("config.php");
require_once("system/functions.php");

$arg = $_GET["opt"];

// This handles a mini-profile and other functions (like getting time) and user info.

switch(strtolower($arg))
{
	case "time": echo time(); break;
	case "miniprofile":
		$user = $_GET["uid"];
		$user = getUser($user);
		echo '<div class="miniprofile" style="width:500px;height:100px;font-family:Arial,sans-serif;">';
		echo '<img src="'.$user["imageurl"].'" width="64" height="64" style="float:left;clear:left;" />';
		echo '<div style="float:right;clear:right;margin-left:0px;padding-left:6px;border-left:1px solid #DDD;width:420px;height:100px;">';
		echo '<div style="font-size:24pt;overflow:hidden;text-overflow:ellipsis;" title="'.$user["name"].'">'.$user["name"].'</div>';
		echo '<div style="font-size:12pt;overflow:hidden;text-overflow:ellipsis;line-height:1em;height:2.5em;" title="'.$user["description"].'">'.$user["description"]."</div>";
		echo '<a href="profile.php?uid='.$user["id"].'">Visit profile</a>';
		echo '</div>';
		echo '<br style="clear:both;" />';
		echo '</div>';
		break;
	case "userinfo":
		$type = $_GET["info"];
		$user = $_GET["user"];
		$user = getUser($user);
		switch(strtolower($type))
		{
			case "name": echo $user["name"]; break;
			case "desc": echo $user["desc"]; break;
			case "image": echo $user["imageurl"]; break;
		}
		break;
}

?>