<?php
session_start();
$user = $_REQUEST['user'];
$pass = $_REQUEST['pass'];

$_SESSION = array();
$list = array();
$value = array();

require_once '../../include/config.php';
require_once '../../include/func_db.php';
require_once '../../include/func_common.php';
$sql = "select encode from sshop where user='$user'";
$row = db_row($conn, $sql);
if ($row[0]) $pass = md5($pass);

$sql = "SELECT * FROM sshop WHERE user='$user' and pass='$pass' and auth<>0";
$rs = db_exec($conn, $sql);
if (db_fetch_row($rs)) {
	$_SESSION['prog_id'] = "mybs_shop";
	$_SESSION['uid'] = db_result($rs, 'ID');
	$_SESSION['uname'] = db_result($rs, 'name');
	$_SESSION['auth1'] = db_result($rs, 'auth');
	$_SESSION['company'] = db_result($rs, 'company');
	$_SESSION['office'] = db_result($rs, 'office');
	$v = DLookUp('name', 'company', 'ID='.$_SESSION['company']);
	$_SESSION['comp_name'] = $v;
	db_free($rs);
	logging("shop_login_success");
	require 'login_success.php';
} else {
	unset($_SESSION['prog_id']);
	logging("shop_login_fail");
	require 'login_error.php';
}
?>