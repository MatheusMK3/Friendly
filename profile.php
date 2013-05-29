<?php

require_once("config.php");
require_once("system/functions.php");

$user = $_GET["uid"];
$mode = $_GET["mode"];

if($login && $mode === "edit") $user = $_SESSION["login_id"];

ob_start();

$info = getUser($user);
$name_first = explode(" ", $info["name"]);
$name_first = $name_first[0];

$friends = getFriends($info["id"], "1");

$page_title = $info["name"];

// Friends

$sql = "SELECT * FROM connections WHERE id1='".$_SESSION["login_id"]."' AND id2='".$user."' LIMIT 0, 1";
$query_friends = mysql_query($sql, $sql_con);

$sql = "SELECT * FROM connections WHERE id2='".$_SESSION["login_id"]."' AND id1='".$user."' LIMIT 0, 1";
$query_friends_request = mysql_query($sql, $sql_con);

$friendStatus = false;
$row = false;
if(mysql_num_rows($query_friends) == 1)
	$row = mysql_fetch_array($query_friends);
else if(mysql_num_rows($query_friends_request) == 1)
	$row = mysql_fetch_array($query_friends_request);
if($row !== false) $friendStatus = $row["status"];

echo '<script type="text/javascript">var uid='.$user.'; stream_interval = 2500;</script>';
echo '<div class="profileSide">';
//echo '<div class="profilePict"><img src="'.htmlspecialchars($info["imageurl"]).'" width="128" height="128" /></div>';
echo '<div class="profilePict"><img src="'.htmlspecialchars($info["imageurl"]).'" width="128" /></div>';
//if($info["verified"]) echo '<div class="profileVerified">'.lang("verified").'</div>';
if($login)
{
	if($user != $_SESSION["login_id"])
	{
		if($friendStatus === "0")
		{
			if(mysql_num_rows($query_friends) == 1)
				echo '<div class="button" style="cursor:default;">'.lang("friends_pending").'</div>';
			else if(mysql_num_rows($query_friends_request) == 1)
			{
				echo '<div class="splitbutton">';
				echo '<a class="button" href="friends.php?mode=requests&action=accept&uid='.$user.'">'.lang("friends_accept").'</a>';
				echo '<a class="button" href="friends.php?mode=requests&action=deny&uid='.$user.'">'.lang("friends_deny").'</a>';
				echo '</div>';
			}
		}
		else if($friendStatus === "1")
		{
			echo '<a class="button" href="friends.php?mode=requests&action=remove&uid='.$user.'">'.lang("friends_remove").'</a>';
		}
		else
			echo '<a class="button" href="friends.php?mode=requests&action=add&uid='.$user.'">'.lang("friends_add").'</a>';
	}
	
	echo '<div style="border-top:1px solid #DDD;margin:8px 16px;"></div>';
}

echo '<strong>'.lang("friends").':</strong>';
echo '<div style="text-align:left;">';
foreach($friends as $friend)
{
	if($count >= 10) break;
	echo '<a style="padding:4px;" class="button" href="profile.php?uid='.$friend["id"].'" title="'.$friend["name"].'"><img width="32" height="32" src="'.$friend["imageurl"].'" /></a>';
	$count++;
}
echo '</div>';
if(count($friends) > 10 && $login) echo '<a href="profile.php?uid='.$info["id"].'&mode=friends">'.lang("profile_friends", array("name" => $name_first)).'</a>';

echo '</div>';

echo '<div class="profileMain">';

switch(strtolower($mode))
{
	case "friends":
		foreach($friends as $profile)
		{
			echo '<a href="profile.php?uid='.$profile["id"].'" class="button" style="padding:4px;vertical-align:middle;">';
			echo '<img height="42" src="'.$profile["imageurl"].'" style="float:left;clear:left;border:1px solid #DDD" />';
			echo '<p style="padding-left:8px;float:left;clear:right;line-height:1em;display:inline-block;">'.doText($profile["name"]).'</p>';
			echo '</a>';
		}
		break;
	case "edit":
		switch(strtolower($_GET["action"]))
		{
			case "apply":
				if($login)
				{
					$pict_size = 100; // Size limit (KB)
					
					$post_name = $_POST["post_name"];
					$post_desc = $_POST["post_desc"];
					$post_lang = $_POST["post_lang"];
					$post_pict = $_FILES["post_pict"];
					$extra = "";
					
					if(strlen($post_pict["name"]) > 0)
					{
						$post_pict_name = strtolower(stripslashes($post_pict["name"]));
						$post_pict_path = $post_pict["tmp_name"];
						$post_pict_size = filesize($post_pict_path);
						
						$post_pict_old = strtolower($info["imageurl"]);
						$post_pict_old_ext = getExtension($post_pict_old);
						
						$ext = getExtension($post_pict_name);
						
						if($ext == "jpg" || $ext == "jpeg" || $ext == "png" || $ext == "gif")
						{
							if($post_pict_size < $pict_size * 1024)
							{
								$post_pict_upic = "userpic/".$info["username"].".".$ext;
								
								$copied = copy($post_pict_path, $post_pict_upic);
								if($copied)
								{
									if($post_pict_upic != $post_pict_old && $post_pict_old == "userpic/".$info["username"].".".$post_pict_old_ext && file_exists($post_pict_old)) unlink($post_pict_old);
									$extra = ", imageurl='".$post_pict_upic."'";
								}
							}
						}
					}
					
					if(get_magic_quotes_gpc())
					{
						$post_name = stripslashes($post_name);
						$post_desc = stripslashes($post_desc);
						$post_lang = stripslashes($post_lang);
					}
					
					if(strlen($post_name) == 0) $post_name = $info["name"];
					if(strlen($post_lang) == 0) $post_lang = lang(".id_code");
					
					$sql = "UPDATE users SET name='".mysql_real_escape_string($post_name)."', description='".mysql_real_escape_string($post_desc)."'".$extra.", lang='".mysql_real_escape_string($post_lang)."' WHERE id='".mysql_real_escape_string($user)."'";
					mysql_query($sql, $sql_con);
					
					header("Location: profile.php?uid=".$user);
				}
				break;
			default:
				if($login)
				{
					$extra = array("en" => "", "pt" => "");
					$extra[lang(".id_code")] = ' selected="selected"';
					
					echo '<form enctype="multipart/form-data" action="profile.php?mode=edit&action=apply" method="post">';
					
					echo '<div class="profileName"><input type="text" style="width:100%;" autocomplete="off" class="textbox profileName" name="post_name" value="'.htmlentities($info["name"]).'" /></div>';
					echo '<div class="profileDesc"><input type="text" style="width:100%;" autocomplete="off" class="textbox profileDesc" name="post_desc" value="'.doText($info["description"]).'" /></div>';
					echo '<div><input type="file" style="width:100%;" autocomplete="off" class="textbox" name="post_pict" /></div>';
					echo '<div><select class="button" name="post_lang">';
					echo '<option'.$extra["en"].' value="en">'.htmlentities("English").'</option>';
					echo '<option'.$extra["pt"].' value="pt">'.htmlentities("Português").'</option>';
					echo '</select></div>';
					echo '<input class="button" type="submit" name="process" value="'.lang("apply_changes").'" />';
					echo '<p><strong>'.lang("profile_edit_note").': </strong>'.lang("profile_edit_note_info", array("size" => "100")).'</p>';
					
					echo '</form>';
				}
				break;
		}
		break;
	default:
		echo '<div class="profileName">'.$info["name"].'</div>';
		echo '<div class="profileDesc">'.doText($info["description"]).'</div>';
		
		if($user == $_SESSION["login_id"])
		{
			echo '<div class="splitbutton" style="margin-top:20px;">';
			echo '<a class="button" href="profile.php?mode=edit">'.lang("profile_edit").'</a>';
			echo '<a class="button" href="profile.php?uid='.$user.'&mode=friends">'.lang("profile_myfriends").'</a>';
			echo '</div>';
		}
		
		echo '<div id="stream"><span id="stream_loading">'.lang("stream_loading").'</span></div>';
		echo '<script type="text/javascript">stream_update();</script>';
		break;
}
echo '</div>';

$page_content = ob_get_contents();
ob_end_clean();

require_once("template.php");

?>