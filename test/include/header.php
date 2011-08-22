<?php
require_once '../include/config.php';
require_once '../include/login_check.php';
require_once '../include/func_db.php';
require_once '../include/func_common.php';
require_once '../include/func_format.php';

$select = $_REQUEST['select'];
$select_mode = false;
if ($select!='') {
	$select_mode = true;
	$select_arg = "select=$select";
	$target = explode(',', $select);
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<HTML>
<HEAD>
<meta http-equiv="Content-Type" content="text/html; charset=<?=$html_charset?>">
<title><?=($select_mode ? "select window" : $header_title)?></title>
<link href="../common/normal.css" rel="stylesheet" type="text/css">
<?php if ($select_mode) { ?>
<script language="JavaScript">
function setSelectValue(v) {
	window.opener.document.getElementById('<?=$target[0]?>').value = v;
<?php	if ($target[1]!="") { ?>
	window.opener.<?=$target[1]?>;
<?php	} ?>
//	window.opener.document.form1.submit();
	window.close();
}
</script>
<?php } ?>
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
echo '<div class="no_print">';

function echo_menu_td($src, $link, $title) {
	global $rn;

	echo '<div style="float:left;margin:5px;"><a href="../'.$link.'">';
	if ($src<>"") echo '<img src="../common/images/'.$src.'" border=0 height=16>';
	echo '<font size="-1">'.$title.'</font></a></div>';
	echo $rn;
}

if ($select_mode) {
?>
<div class="frame_select">
<!--
<a href="javascript:history.back()"><img src="../common/images/back_button.gif" border=0></a>
-->
<a href="javascript:window.close()"><img src="../common/images/close_button.gif" border=0></a>
</div>
<?php
} else {
?>
<div class="frame_logo">
<p align=right height=43> [ <?=$comp_name?> ] <br>
USER <?=$uname?> : <a href="../login/logout.php">LOGOUT</a></p>
</div>

<div class="frame_menu">
<table><tr><td>
<?php
echo_menu_td("icon_home.gif", "home/index.php", "HOME");
echo_menu_td("icon_customer.gif", "customer/index.php", "顧客情報");
echo_menu_td("icon_machine.gif", "article/index.php", "物件マスタ");
echo_menu_td("icon_sale.gif", "sales/index.php", "売上");
echo_menu_td("icon_schedule.gif", "schedule/weekly.php?type=0", "スケジュール");
echo_menu_td("icon_visit.gif", "schedule/weekly.php?type=1", "来店履歴");
echo_menu_td("icon_work.gif", "work/index.php", "案件");
echo_menu_td("icon_mainte.gif", "mainte/index.php", "整備");
//echo_menu_td("icon_manage.gif", "management/index.php", "工程管理");
//echo_menu_td("icon_account.gif", "accounting/index.php", "経理");
echo_menu_td("icon_account.gif", "config/index.php", "設定");
echo_menu_td("", "", "");
?>
</td></tr></table>
</div>

<?php
}
$folder_title = array();
$folder_title[0] = "SKY";
$folder_title[1] = "HOME";
$folder_title[2] = "整備";
$folder_title[3] = "案件";
$folder_title[4] = "物件";
$folder_title[5] = "顧客";
$folder_title[6] = "売上";
$folder_title[7] = "商品";
$folder_title[8] = "設定";
$folder_title[9] = ' 予定表 ';
$folder_title[10] = "来店履歴";
$folder_name = array();
$folder_name[0] = "sky";
$folder_name[1] = "home";
$folder_name[2] = "mainte";
$folder_name[3] = "work";
$folder_name[4] = "article";
$folder_name[5] = "customer";
$folder_name[6] = "sales";
$folder_name[7] = "item";
$folder_name[8] = "config";
$folder_name[9] = "schedule";
$folder_name[10] = "visitor";
$folder_icon = array();
$folder_icon[0] = "";
$folder_icon[1] = "icon_home.gif";
$folder_icon[2] = "icon_mainte.gif";
$folder_icon[3] = "icon_work.gif";
$folder_icon[4] = "icon_machine.gif";
$folder_icon[5] = "icon_customer.gif";
$folder_icon[6] = "icon_sale.gif";
$folder_icon[7] = "";
$folder_icon[8] = "icon_account.gif";
$folder_icon[9] = "icon_schedule.gif";
$folder_icon[10] = "icon_visit.gif";

$back = $_REQUEST['back'];
if ($back==null) {
	$back_link = "javascript:history.back()";
} else {
	$back_link = "javascript:history.go(-".($back+1).")";
}
?>

<div class="frame_header">

<table cellspacing=0 cellpadding=0 width=100%><tr>
<td width=10></td>
<td width=150 align=left height=23><font color=white>

<a href="index.php"><img src="../common/images/<?=$folder_icon[$folder_id]?>" border=0 height=16>
<?=$folder_title[$folder_id]?>

</font></td>
<td align=left>
<span> | 
<a href="<?=$back_link?>"><img src="../common/images/icon_back.gif" height=16>戻る</a>

<?php
switch ($folder_id) {
case 1:
case 8:
case 9:
case 10:
default:
	break;
case 2:
?>
 | <a href="search.php"><img src="../common/images/icon_search.gif">検索</a>
 | <a href="list.php?page=0"><img src="../common/images/icon_list.gif">リスト</a>
 | <a href="addnew.php?type=1" onClick="return confirm('見積書を作成します。よろしいですか？')"><img src="../common/images/icon_addnew.gif">見積書作成</a>
 | <a href="addnew.php?type=0" onClick="return confirm('整備伝票を作成します。よろしいですか？')"><img src="../common/images/icon_addnew.gif">整備伝票作成</a>
<?php
	break;
case 3:
?>
 | <a href="search.php?<?=$select_arg?>"><img src="../common/images/icon_search.gif">検索</a>
 | <a href="list.php?page=0&<?=$select_arg?>"><img src="../common/images/icon_list.gif">リスト</a>
 | <a href="addnew.php?<?=$select_arg?>" onClick="return confirm('新規作成します。よろしいですか？')"><img src="../common/images/icon_addnew.gif">新規作成</a>
<?php
	break;
case 4:
?>
 | <a href="search.php?type=0&<?=$select_arg?>"><img src="../common/images/icon_search.gif">船検索</a>
 | <a href="search.php?type=1&<?=$select_arg?>"><img src="../common/images/icon_search.gif">エンジン検索</a>
 | <a href="list.php?page=0&<?=$select_arg?>"><img src="../common/images/icon_list.gif">リスト</a>
 | <a href="addnew.php?type=0&<?=$select_arg?>" onClick="return confirm('船データを作成します。よろしいですか？')"><img src="../common/images/icon_addnew.gif">船作成</a>
 | <a href="addnew.php?type=1&<?=$select_arg?>" onClick="return confirm('エンジンを作成します。よろしいですか？')"><img src="../common/images/icon_addnew.gif">エンジン作成</a>
<?php
	break;
case 5:
?>
 | <a href="search.php?<?=$select_arg?>"><img src="../common/images/icon_search.gif">検索</a>
 | <a href="list.php?page=0&<?=$select_arg?>"><img src="../common/images/icon_list.gif">リスト</a>
 | <a href="addnew.php?<?=$select_arg?>" onClick="return confirm('新規作成します。よろしいですか？')"><img src="../common/images/icon_addnew.gif">新規作成</a>
<?php
	break;
case 6:
?>
 | <a href="search.php"><img src="../common/images/icon_search.gif">検索</a>
 | <a href="list.php"><img src="../common/images/icon_list.gif">リスト</a>
<?php
	break;
case 7:
//	if (!$select_mode) {
?>
 | <a href="search.php?<?=$select_arg?>"><img src="../common/images/icon_search.gif">検索</a>
 | <a href="list.php?page=0&<?=$select_arg?>"><img src="../common/images/icon_list.gif">リスト</a>
 | <a href="addnew.php?type=1&<?=$select_arg?>" onClick="return confirm('部品を新規作成します。よろしいですか？')"><img src="../common/images/icon_addnew.gif">新規部品</a>
 | <a href="addnew.php?type=2&<?=$select_arg?>" onClick="return confirm('作業を新規作成します。よろしいですか？')"><img src="../common/images/icon_addnew.gif">新規作業</a>
 | <a href="javascript:location.reload()"><img src="../common/images/icon_reload.gif">再表示</a>
<?php
//	}
	break;
}
?>
 | </span>
</td>
<?php
$path = $_SERVER['PHP_SELF'];
$path = str_replace("\\", "_", $path);
$path = str_replace("/", "_", $path);
$path = str_replace(".php", "", $path);
$path = str_replace("_sky_", "", $path);
if (file_exists("../help/$path.php")) {
?>
<td align=right><a href="../help/<?=$path?>.php" target="help"><img src="../common/images/icon_help.gif">ヘルプ</a>&nbsp;&nbsp;&nbsp;</td>
<?php
}
?>
</tr></table>
</div>
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
