<?php

function getUser($id, $mode="id")
{
	global $sql_con;
	
	$userinfo = array();
	$sql = "SELECT * FROM users WHERE ".mysql_real_escape_string($mode)."='".mysql_real_escape_string($id)."' LIMIT 0, 1";
	$query = mysql_query($sql, $sql_con) or die(mysql_error());
	$userinfo = mysql_fetch_array($query);
	
	if(strlen($userinfo["name"]) == 0) $userinfo["name"] = lang("unknown")." (".$userinfo["username"].")";
	
	return $userinfo;
}
function is_valid_email($address) {
	return (preg_match("/[a-z0-9!#$%&'*+/=?^_`{|}~-]+(?:\\.[a-z0-9!#$%&'*+/=?^_`{|}~-]+)*@(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?/i", $address) > 0);
}
function doDate($time)
{
	$str = date(lang(".format_time"), $time);
	$pre = "";
	$now = time();
	$today = mktime(0, 0, 0, date("n"), date("j"), date("Y"));
	if($time < $today) $pre = lang("date_yesterday")." ";
	if($time < $today - (60 * 60 * 24)) { $pre = ""; $str = date(lang(".format_date"), $time); }
	
	return $pre.$str;
}
function doText($raw, $process=false)
{
	$proc = nl2br(htmlentities($raw, null, "UTF-8"));
	
	$regex = "/@[A-Z0-9._]+/i";
	preg_match_all($regex, $proc, $match);
	
	$match = $match[0];
	
	if($process)
	{
		foreach($match as $k => $v)
		{
			$username = substr($v, 1, strlen($v) - 1);
			$user = getUser($username, "username");
			$proc = str_replace($v, '<a href="profile.php?uid='.$user["id"].'" class="procReference">&rarr;'.doText($user["name"]).'</a>', $proc);
		}
	}
	
	if(strlen($proc) > 0) return $proc;
	else return nl2br(htmlentities($raw));
}
function getFriends($uid, $status = false)
{
	global $sql_con;
	
	$friends = array();
	$sql = "SELECT * FROM connections WHERE (id2='".$uid."' OR id1='".$uid."')";
	if($status !== false) $sql .= " AND status='".mysql_real_escape_string($status)."'";
	$query = mysql_query($sql, $sql_con);
	while($row = mysql_fetch_array($query))
	{
		$id = $row["id1"];
		if($id == $uid) $id = $row["id2"];
		$friends[] = getUser($id);
	}
	
	return $friends;
}
function getExtension($file)
{
	$i = strrpos($file,".");
	if (!$i) return "";
	$l = strlen($file) - $i;
	$ext = substr($file,$i+1,$l);
	return $ext;
}

function breakText($text, $chars = 16, $divider = "\n")
{
	$res = "";
	for($i = 0; $i < strlen($text); $i += $chars)
	{
		if($i > 0)
		{
			if(substr($text, $i-1, 1) != " " && substr($text, $i, 1) != " " && $i > 0 && ctype_alnum(substr($text, $i-1, 1)) && ctype_alnum(substr($text, $i, 1))) $res .= "-";
			$res .= $divider;
		}
		$res .= substr($text, $i, $chars);
	}
	return $res;
}

function lang($name, $args = array())
{
	return htmlentities(_lang($name, $args));
}
function _lang($name, $args = array())
{
	global $lang;
	$proc = $lang[strtolower($name)];
	if(count($args) > 0)
	{
		foreach($args as $key => $value)
		{
			$proc = str_replace("%{".$key."}", $value, $proc);
		}
	}	
	return $proc;
}

?>