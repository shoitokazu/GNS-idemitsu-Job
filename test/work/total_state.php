<?php include 'header.php'; ?>

<?php
$base = "work_h";
$table = "$base LEFT JOIN account ON account.ID=$base.uid";
$tmp_state = "(select value as ws,name as wsn,sort as wss from choices where field='work_state') as tmp"; 
$table = "($table) left join $tmp_state on work_h.work_state=tmp.ws";
$field = "Count($base.ID) AS 案件数, work_state, wsn, wss";
$where = "work_state<>9";
$sql = "SELECT $field FROM $table WHERE $where GROUP BY work_state ORDER BY wss";

$rs = db_exec($conn, $sql);
?>
<h1>見込度別　案件数　集計表</h1>

<TABLE border=1>
<TR>
<TD>状態</TD>
<TD>案件数</TD>
</TR>

<?php
while (db_fetch_row($rs)) {
	$id = db_result($rs, 'work_state');
	$name = db_result($rs, 'wsn');
	$num = db_result($rs, '案件数');
?>
<TR>
<TD>
<a href="list.php?clear=1&work_state=<?=$id?>&sort=wcode">
<?php echo $name ?>
</a>
</TD>
<TD><?php echo $num ?></TD>
</TR>
<?php
}
?>

</TABLE>

</div>

<?php include "footer.php" ?>
