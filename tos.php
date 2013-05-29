<?php

require_once("config.php");
require_once("system/functions.php");

ob_start();

echo _lang("page_terms");

$page_title = lang("page_terms_title");

$page_content = ob_get_contents();
ob_end_clean();

require_once("template.php");

?>