<?php
require_once '../include/config.php';
require_once '../include/login_check.php';
require_once '../include/func_db.php';
require_once '../include/func_common.php';
require_once '../include/func_format.php';
?>
<HTML>
<HEAD>
<meta http-equiv="Content-Type" content="text/html; charset=<?=$html_charset?>">
<title>script</title>
</HEAD>

<?php
if ($body_onload_func=='' or $debug) {
?>
<body>
<?php
} else {
?>
<body onLoad=<?=$body_onload_func?>>
<?php
}
if ($body_onload_func<>'' and $debug) {
?>
<p>デバッグモード</p>
<p><a href="javascript:<?=$body_onload_func?>">続ける</a></p>
<?php
}
?>
