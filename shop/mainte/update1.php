<?php
$body_onload_func = "history.go(-1);";
include 'header.php';

$mid = $_REQUEST['id'];

$msid = DLookUp("ID", "mainte_shop", "mid=$mid and ssid=$uid");
if ($msid==false) return_error("自社案件ではありません。");

$state = DLookUp("mainte_state", "mainte_h", "ID=$mid");
if (!($state==0 or $state==1 or $state==9)) return_error("完了済み案件です。");

$shop_lock = DLookUp("shop_lock", "mainte_shop", "mid=$mid and ssid=$uid");
if ($shop_lock) return_error("ショップ工程が確定済みです。");

update_form("mainte_h");

include 'footer.php';
?>
