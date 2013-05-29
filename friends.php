<?php

require_once("config.php");
require_once("system/functions.php");

ob_start();

if($login)
{
	$mode = strtolower($_GET["mode"]);
	$action = strtolower($_GET["action"]);
	$uid = strtolower($_GET["uid"]);
	
	// Select all friends from the user, id1 and id2 represent who sent the original request to who
	$sql = "SELECT * FROM connections WHERE id2='".$_SESSION["login_id"]."' OR id1='".$_SESSION["login_id"]."'";
	$query_friends = mysql_query($sql, $sql_con);
	
	$requests = array();
	$confirmed = array();
	
	while($row = mysql_fetch_array($query_friends))
	{
		switch($row["status"])
		{
			// Detect if person is already a friend. If not (requested), check if the user is not the sender
			case 0: if($row["id1"] != $_SESSION["login_id"]) $requests[] = $row; break;
			case 1: $confirmed[] = $row; break;
		}
	}
	
	echo '<div class="splitbutton" style="margin-bottom:8px;">';
	echo '<a class="button" href="friends.php">'.lang("friends_my").' ('.count($confirmed).')</a>';
	echo '<a class="button" href="friends.php?mode=requests">'.lang("friends_requests").' ('.count($requests).')</a>';
	echo '<a class="button" href="friends.php?mode=whotoadd">'.lang("friends_whotoadd").'</a>';
	echo '</div>';
	
	echo '<div>';
	
	// Actions (accept, deny, remove friendship, send request)
	switch($mode)
	{
		case "requests":
			switch($action)
			{
				case "accept":
					$sql = "UPDATE connections SET status='1' WHERE id1='".mysql_real_escape_string($uid)."' AND id2='".mysql_real_escape_string($_SESSION["login_id"])."' AND status='0'";
					mysql_query($sql, $sql_con);
					header("Location: friends.php?mode=requests");
					break;
				case "deny":
					$sql = "DELETE FROM connections WHERE id1='".mysql_real_escape_string($uid)."' AND id2='".mysql_real_escape_string($_SESSION["login_id"])."' AND status='0'";
					mysql_query($sql, $sql_con);
					header("Location: friends.php?mode=requests");
					break;
				case "remove":
					$sql = "DELETE FROM connections WHERE id1='".mysql_real_escape_string($uid)."' AND id2='".mysql_real_escape_string($_SESSION["login_id"])."' AND status='1'";
					mysql_query($sql, $sql_con);
					$sql = "DELETE FROM connections WHERE id2='".mysql_real_escape_string($uid)."' AND id1='".mysql_real_escape_string($_SESSION["login_id"])."' AND status='1'";
					mysql_query($sql, $sql_con);
					header("Location: friends.php");
					break;
				case "add":
					$canAdd = true;
					$sql = "SELECT * FROM connections WHERE id1='".mysql_real_escape_string($_SESSION["login_id"])."' AND id2='".mysql_real_escape_string($uid)."'";
					$check1 = mysql_query($sql, $sql_con);
					$sql = "SELECT * FROM connections WHERE id2='".mysql_real_escape_string($_SESSION["login_id"])."' AND id1='".mysql_real_escape_string($uid)."'";
					$check2 = mysql_query($sql, $sql_con);
					
					if(mysql_num_rows($check1) > 0 || mysql_num_rows($check2) > 0) $canAdd = false;
					
					$sql = "INSERT INTO connections (id1, id2, status) VALUES('".mysql_real_escape_string($_SESSION["login_id"])."', '".mysql_real_escape_string($uid)."', '0')";
					if($canAdd) mysql_query($sql, $sql_con);
					header("Location: profile.php?uid=".$uid);
					break;
				default:
					if(count($requests) > 0)
					{
						foreach($requests as $row)
						{
							$other = $row["id1"];
							if($other == $_SESSION["login_id"]) $other = $row["id2"];
							$profile = getUser($other);
							echo '<a href="profile.php?uid='.$profile["id"].'" class="button" style="padding:4px;vertical-align:middle;">';
							echo '<img height="42" src="'.$profile["imageurl"].'" style="float:left;clear:left;border:1px solid #DDD" />';
							echo '<p style="padding-left:8px;float:left;clear:right;line-height:1em;display:inline-block;">'.doText($profile["name"]).'</p>';
							echo '</a>';
						}
					}
					else echo '<div>'.lang("friends_norequests").'</div>';
					break;
			}
			break;
		// Friend suggestions
		case "whotoadd":
			$sql = "SELECT * FROM users ORDER BY RAND() LIMIT 0, 10";
			$query = mysql_query($sql, $sql_con);
			while($row = mysql_fetch_array($query))
			{
				$profile = getUser($row["id"]);
				echo '<a href="profile.php?uid='.$profile["id"].'" class="button" style="padding:4px;vertical-align:middle;">';
				echo '<img height="42" src="'.$profile["imageurl"].'" style="float:left;clear:left;border:1px solid #DDD" />';
				echo '<p style="padding-left:8px;float:left;clear:right;line-height:1em;display:inline-block;">'.doText($profile["name"]).'</p>';
				echo '</a>';
			}
			break;
		default:
			if(count($confirmed) > 0)
			{
				foreach($confirmed as $row)
				{
					$other = $row["id1"];
					if($other == $_SESSION["login_id"]) $other = $row["id2"];
					$profile = getUser($other);
					echo '<a href="profile.php?uid='.$profile["id"].'" class="button" style="padding:4px;vertical-align:middle;">';
					echo '<img height="42" src="'.$profile["imageurl"].'" style="float:left;clear:left;border:1px solid #DDD" />';
					echo '<p style="padding-left:8px;float:left;clear:right;line-height:1em;display:inline-block;">'.doText($profile["name"]).'</p>';
					echo '</a>';
				}
			}
			else echo '<div>'.lang("friends_no").'</div>';
			break;
	}
	
	echo '</div>';
}

$page_content = ob_get_contents();
ob_end_clean();

require_once("template.php");

?>