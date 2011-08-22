<?php
//$no_login = true;
//require_once '../include/header.php';
require_once '../../include/config.php';
?>
<HTML>
<HEAD>
<meta http-equiv="Content-Type" content="text/html; charset=<?=$html_charset?>">
<title><?=$header_title?></title>
<link href="../common/normal.css" rel="stylesheet" type="text/css">
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
