<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
 "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-US" lang="en-US">
<head>
	<title><?php echo (count($page_title) > 0) ? $page_title." - " : ""; ?> Friendly</title>
	<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
	<link rel="stylesheet" type="text/css" href="static/style.css" />
	<script type="text/javascript" src="static/jquery-1.6.2.min.js"></script>
	<script type="text/javascript" src="static/stream.js"></script>
	<script type="text/javascript" src="static/chat.js"></script>
	<script type="text/javascript"><?php
	echo "stream_updates_new='".lang("stream_updates_new")."';";
	echo "stream_updates_display='".lang("stream_updates_display")."';";
	?></script>
</head>
<body>

<div id="body">

<div id="container">

<div id="header">
<h1>Friendly</h1>

<div>
	<a href="index.php" class="button"><?php echo lang("menu_home"); ?></a>
	<?php if($login !== false) { ?>
	<a href="profile.php?uid=<?php echo $_SESSION["login_id"]; ?>" class="button"><?php echo lang("menu_profile"); ?></a>
	<a href="friends.php" class="button"><?php echo lang("menu_friends"); ?></a>
	<a href="#" class="button" onclick="stream_post_open();"><?php echo lang("menu_post"); ?></a>
	<a href="logout.php" class="button"><?php echo lang("menu_logout"); ?></a>
	<?php } else { ?>
	<a href="login.php" class="button"><?php echo lang("menu_login"); ?></a>
	<a href="register.php" class="button"><?php echo lang("menu_register"); ?></a>
	<?php } ?>
	
	<div id="search"><div class="splitbutton"><form action="search.php" method="post"><input name="query" type="text" class="button textbox" /><input name="send" type="submit" class="button" value="<?php echo lang("menu_search"); ?>" /></form></div></div>
</div>

</div>
<div id="contents">
<?php echo $page_content; ?>
<br style="clear:both;" />
</div>
<div id="footer"><a href="tos.php"><?php echo lang("menu_terms"); ?></a></div>
</div>

<?php if($login) { ?>
<div class="publish">
	<form action="stream.php?mode=post" method="post">
		<textarea name="body" class="textbox" style="resize: none; width: 454px; height: 300px;"></textarea>
		<a href="#" class="button" onclick="stream_post();"><?php echo lang("post_ok"); ?></a>
		<a href="#" class="button" onclick="stream_post_close();"><?php echo lang("post_cancel"); ?></a>
	</form>
</div>
<div id="chatBar">
<div id="chatUsers" class="chatNormal">
<div class="chatBar"><strong><?php echo lang("chat_friends"); ?></strong><a href="#" onclick="chatFriends();">_</a></div>
<div class="chatBody"><div class="inner"><?php

$friends = getFriends($_SESSION["login_id"]);

foreach($friends as $friend)
{
	echo '<a href="#" class="button __chat_friend_'.$friend["id"].'" onclick="openChat('.$friend["id"].');"><img alt="" title="'.doText($friend["name"]).'" width="32" height="32" src="'.htmlentities($friend["imageurl"]).'" /></a>';
}

?></div></div>
</div>

</div>
</div>
<?php } ?>
<div id="notifications"></div>
<div id="null" style="display:none;width:0px;height:0px;visibility:hidden;"></div>

</div>

</body>
</html>
