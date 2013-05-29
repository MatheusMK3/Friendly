<?php

require_once("config.php");
require_once("system/functions.php");

ob_start();

$query_raw = $_POST["query"];
if(get_magic_quotes_gpc()) $query_raw = stripslashes($query_raw);

$query = mysql_real_escape_string($query_raw);

if(strlen($query) < 3) header("Location: index.php");

$sql = "SELECT *, MATCH(name) AGAINST ";
$sql .= "('".$query."' IN BOOLEAN MODE) AS score ";
$sql .= "FROM users WHERE MATCH(name) AGAINST ";
$sql .= "('".$query."' IN BOOLEAN MODE) OR ";
$sql .= "name RLIKE '(".$query.")'";

$query = mysql_query($sql, $sql_con) or die(mysql_error());

echo '<div>';
echo '<h2>'.lang("search_for", array("search_query" => $query_raw)).'</h2>';
echo '</div>';

while($profile = mysql_fetch_array($query))
{
	echo '<a href="profile.php?uid='.$profile["id"].'" class="button" style="padding:4px;vertical-align:middle;">';
	echo '<img height="42" src="'.$profile["imageurl"].'" style="float:left;clear:left;border:1px solid #DDD" />';
	echo '<p style="padding-left:8px;float:left;clear:right;line-height:1em;display:inline-block;">'.doText($profile["name"]).'</p>';
	echo '</a>';
}

$page_content = ob_get_contents();
ob_end_clean();

require_once("template.php");

?>