<?php include 'header.php'; ?>

<script src="../common/selectWindow.js" language="JavaScript"></script>
<script src="../common/calendar.js" language="JavaScript"></script>

<form action="update_form.php" method="POST">
<?php
echo_sub_header("", "更新");

$table = "environment";
$where = limitCompany("", $table, true);
$sql = "select * from $table where $where";
$rs = db_exec($conn, $sql);
db_fetch_row($rs);

echo_form_frame();
init_form_format($rs);
echo_form_tr($rs, "特約店コード", "code", "str");
echo_form_tr($rs, "特約店名", "name", "str");
echo_form_tr($rs, "印刷発行元", "climant", "str");
echo_form_tr($rs, "振込先口座", "bank", "str");
echo_form_tr($rs, "消費税率", "tax_rate", "int");
echo_form_tr($rs, "小数点以下", "decimals", "decimals");
echo_form_tr($rs, "部品原価率", "cost_rate", "int");
echo_form_frame_end();
db_free($rs);
?>
</form>

<?php include 'footer.php'; ?>
