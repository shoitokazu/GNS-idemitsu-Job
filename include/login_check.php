<?php
if ($no_login==false) {
	session_start();
	if ($_SESSION['prog_id'] == $system_id) {
		$uid = $_SESSION['uid'];
		$uname = $_SESSION['uname'];
		$auth1 = $_SESSION['auth1'];
		$company = $_SESSION['company'];
		$comp_name = $_SESSION['comp_name'];
	} else {
		$back = 1;
		include '../login/login_form.php';
//		include 'login_form.php';
		exit();
	}
}
?>