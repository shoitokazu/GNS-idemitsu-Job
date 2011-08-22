<?php include 'header.php'; ?>

<script src="../common/selectWindow.js" language="JavaScript"></script>
<script src="../common/calendar.js" language="JavaScript"></script>

<?php
$company=$_REQUEST['company'];
?>

<form action="company_update_form.php" method="POST">

<?php
echo_sub_header("", "更新");


$table = "company";
$sql = "select * from $table";
$rs = db_exec($conn, $sql);
db_fetch_row($rs);

echo_form_frame();
init_form_format($rs);
echo_html_tr($rs, "オフィスID", "ID", "int");
echo_form_tr($rs, "表示順", "sort", "str");
echo_form_tr($rs, "オフィスコード", "code", "str");
echo_form_tr($rs, "オフィス名", "name", "str");
echo_form_frame_end();
db_free($rs);
?>
</form>

<?php include 'footer.php'; ?>
