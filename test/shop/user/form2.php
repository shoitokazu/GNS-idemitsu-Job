<?php
$page_title = "会社情報　パスワード設定";
require 'header.php';
?>

<form action="update2.php" method="POST" name="form1">

<?php
$where = "ID=".$uid;
$table = "sshop";
$field = "*";
$sql = "select $field from $table";
if ($where<>"") $sql .= " where $where";
if ($sort<>"") $sql .= " order by $sort";
$rs = db_exec($conn, $sql);
init_form_format($rs);
echo_form_frame();
echo_form_tr($rs, "パスワード", "pass", "pass");
echo_form_frame_end();
db_free($rs);

echo_form_db($rs, "encode", "hidden", "0");

echo_button_frame();
echo_form_submit();
echo_button_frame_end();
?>

<?php require 'footer.php'; ?>
