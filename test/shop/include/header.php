<?php
require_once '../../include/config.php';
require_once '../include/login_check.php';
require_once '../../include/func_db.php';
require_once '../../include/func_common.php';
require_once '../../include/func_format.php';

if ($auth1!=1) exit();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?=$html_charset?>">
<title><?php echo $corp ?> - shop</title>
<meta http-equiv="imagetoolbar" content="no">
<meta http-equiv="Pragma" content="no-cache">
<link href="../../common/normal.css" rel="stylesheet" type="text/css">
</head>
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
?>
<div class="no_print">

<table border="0" cellspacing="0" cellpadding="4" width="760">
<tr>
<td align="left" bgcolor="#aaaaff"><b><font color="#ffffff">サービスショップ：<?= $uname ?></font></b></td>
<td align="right" bgcolor="#aaaaff"><a href="../login/logout.php">ＬＯＧＯＵＴ</a></td>
</tr>
</table>


<table border="0">
<tr>
<td><img src="../common/images/index.gif"></td>
<td><a href="../home/index.php">HOME</a></td>
<td><img src="../common/images/customer_search.gif"></td>
<td><a href="../customer/search.php">顧客情報</a></td>
<td><img src="../common/images/machine_search.gif"></td>
<td><a href="../article/search.php">物件マスタ</a></td>
<td><img src="../common/images/mainte_search.gif"></td>
<td><a href="../mainte/search.php">整備検索</a></td>
<td><img src="../common/images/mainte_search.gif"></td>
<td><a href="../mainte/search_shop.php">自社整備検索</a></td>
</tr>

<tr>
<td><img src="../common/images/mainte_search.gif"></td>
<td><a href="../mainte/list_shop.php?clear=1&shop1_0=1">整備未確認</a></td>
<td><img src="../common/images/mainte_search.gif"></td>
<td><a href="../mainte/list_shop.php?clear=1&shop1_0=0&shop2_0=1">整備作業中</a></td>
<td><img src="../common/images/mainte_search.gif"></td>
<td><a href="../mainte/list_shop.php?clear=1&shop2_0=0">整備完了</a></td>
<td><img src="../common/images/mainte_search.gif"></td>
<td><a href="../bill/index.php">請求業務</a></td>
<td><img src="../common/images/index.gif"></td>
<td><a href="../user/index.php">会社情報</a></td>
</tr>
</table>

<?php
$back = $_REQUEST['back'];
if ($back==null) {
	$back_link = "javascript:history.back()";
} else {
	$back_link = "javascript:history.go(-".($back+1).")";
}
?>
<p>[<a href="<?=$back_link?>">戻る</a>] </p>
</div>

<table border="0" cellspacing="0" cellpadding="1" width="760">
<tr><td width=20></td><td><b><?=$page_title?></b></td></tr>
<tr><td></td><td bgcolor="aaaaaa"></td></tr>
</table>


<div style="height:300px">

<?php
if ($body_onload_func<>'' and $debug) {
?>
<p>デバッグモード</p>
<p><a href="javascript:<?=$body_onload_func?>">続ける</a></p>
<?php
}
?>
