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
<title>select window</title>
<link href="../common/select_list.css" rel="stylesheet" type="text/css">
</HEAD>

<?php
if ($body_onload_func=='' or $debug) {
?>
<body bgcolor=#FFFFFF>
<?php
} else {
?>
<body bgcolor=#FFFFFF onLoad=<?=$body_onload_func?>>
<?php
}
?>
<div class="frame_select_window">

<img src="../common/images/close.gif" width="34" height="34" border="0" onClick="window.close();">
<a href="#" onClick="window.close();">閉じる</a>

<?php
if ($body_onload_func<>'' and $debug) {
?>
<p>デバッグモード</p>
<p><a href="javascript:<?=$body_onload_func?>">続ける</a></p>
<?php
}
?>
