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
<title><?=($select_mode ? "select window" : $header_title)?></title>
<link href="print.css" rel="stylesheet" type="text/css">
</HEAD>

<body onLoad=<?=$body_onload_func?>>

<div class="no_print">
<p><input type="button" value="戻る" onClick="history.back()"> /
<input type="button" value="印刷" onClick="window.print()"></p>
</div>
<div class="frame_main">
