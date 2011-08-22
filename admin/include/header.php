<?php
require_once '../../include/config.php';
require_once '../../include/login_check.php';
require_once '../../include/func_db.php';
require_once '../../include/func_common.php';
require_once '../../include/func_format.php';

if ($auth1!=2) exit();
?>
<HTML>
<HEAD>
<meta http-equiv="Content-Type" content="text/html; charset=<?=$html_charset?>">
<title><?=$header_title?> - 管理システム</title>
<link href="../../common/normal.css" rel="stylesheet" type="text/css">
</HEAD>

<?php
if ($body_onload_func=='' or $debug) {
?>
<body bgcolor=#DADADA>
<?php
} else {
?>
<body bgcolor=#DADADA onLoad=<?=$body_onload_func?>>
<?php
}
?>
<div class="frame_logo">
<p align=right height=43>USER <?=$uname?> : <a href="../../login/logout.php">LOGOUT</a></p>
</div>

<div class="frame_menu">
<table cellspacing=0 cellpadding=0>
<tr height=50>
<td> [ <a href="../../home/index.php">MYBS HOME</a> ] </td>
<td> [ <a href="../config/index.php">設定メニュー</a> ] </td>
<td> [ <a href="../master/index.php">データ管理</a> ] </td>
</tr>
</table>
</div>

<div class="frame_header">

<table cellspacing=0 cellpadding=0 width=100%><tr>
<td width=10></td>
<td width=150 align=left height=23><font color=white>[ 管理システム ]</font></td>
<td align=left>
<span> | 
<a href="javascript:history.back()"><img src="../../common/images/icon_back.gif">戻る</a>
 | </span>
</td></tr></table>
</div>

<div class="frame_main">

<?php
if ($body_onload_func<>'' and $debug) {
?>
<p>デバッグモード</p>
<p><a href="javascript:<?=$body_onload_func?>">続ける</a></p>
<?php
}
?>
