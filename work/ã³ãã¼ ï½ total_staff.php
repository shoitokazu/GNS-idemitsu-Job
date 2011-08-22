<?php include 'header.php'; ?>

<?php
$base = "work_h";
$table = "$base LEFT JOIN account ON $base.uid=account.ID";
$where = "work_state<>9";
$field = "Count($base.ID) AS 案件数, uid, account.name as staff";
$sql = "SELECT $field FROM $table WHERE $where GROUP BY uid ORDER BY account.sort";

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
	$id = db_result($rs, 'uid');
	$n = db_result($rs, '案件数');
	$name = db_result($rs, 'staff');
?>
<TR>
<TD>
<a href="list.php?clear=1&uid=<?=$id?>&sort=wcode">
<?php echo $name ?>
</a>
</TD>
<TD><?php echo $n ?></TD>
</TR>
<?php
}
?>

</TABLE>

</div>

<?php include "footer.php" ?>
