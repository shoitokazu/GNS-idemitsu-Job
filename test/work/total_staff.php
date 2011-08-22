<?php include 'header.php'; ?>

<?php
$base = "work_h";
$table = $base;
$where = "work_state<>9";
$field = "Count($base.ID) AS 案件数, wstaff";
$sql = "SELECT $field FROM $table WHERE $where GROUP BY wstaff";

$rs = db_exec($conn, $sql);
?>
<h1>担当者別　案件数　集計表</h1>

<TABLE border=1>
<TR>
<TD>社員名</TD>
<TD>案件数</TD>
</TR>

<?php
while (db_fetch_row($rs)) {
	$n = db_result($rs, '案件数');
	$name = db_result($rs, 'wstaff');
?>
<TR>
<TD>
<?php
if ($name=="") {
	echo "不明";
} else {
?>
<a href="list.php?clear=1&wstaff=<?=urlencode($name)?>&sort=wcode"><?=$name?></a>
<?php
}
?>
</TD>
<TD><?php echo $n ?></TD>
</TR>
<?php
}
?>

</TABLE>

</div>

<?php include "footer.php" ?>
