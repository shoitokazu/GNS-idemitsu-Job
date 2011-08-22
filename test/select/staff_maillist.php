<?php include 'header.php' ?>

<?php
$visit_id = $_REQUEST['visit_id'];

$where = "WHERE auth=1";
$area = $_REQUEST['area'];
if ($area<>'') $where .= " and company=$area";
?>

<br><br>
<b><font size="3">スタッフへメール送信</font><b>
<br>

<form method="POST" action="" name="form_area">
    <p>
        担当エリア
        <select name="area" onchange="document.form_area.submit();">
            <option value="">すべて</option>
<?php
$list = DListUp("ID,name", "company", "ID<>0");
echo_option($list, $area);
?>
        </select>
        <input type="submit" value="表示">
    </p>
</form>

<table id="staff" border="0" cellpadding="0" cellspacing="0">
<form method="post" action="staff_mailsend.php">
<thead>
<tr>
<th>ID</th>
<th class="name" colspan="2">名前</th>
</tr>
</thead>
<tbody>
<?php
	$table = "account";
	$sql = "SELECT * FROM $table $where ORDER BY sort,ID";
	$rs = db_exec($conn, $sql);
	while (db_fetch_row($rs)) {
		$id = db_result($rs, 'ID');
		$name = db_result($rs, 'name');
		if ($class == "odd") {
			$class = "even";
		} else {
			$class = "odd";
		}
?>
<tr class="<?=$class?>">
<th><?= $id ?></th>
<td><?= $name ?></td>
<?
	if ( $select <> 'off' ) {
		?><td class="button"><input type="checkbox" name="pcmail[<?= $id ?>]" value="1">ＰＣ<input type="checkbox" name="mobile[<?= $id ?>]" value="1">携帯<input type="hidden" name="staff_id[]" value="<?= $id ?>"></td><?
	}
?>
</tr>
<?
	}
?>
</tbody>
</table>
<table border="0" cellspacing="0" cellpadding="0" width="100%">
<tr><td align="center"><input type="submit" name="send" value="メール送信"></td></tr>
<input type="hidden" name="select" value="schedule_sid">
<input type="hidden" name="visit_id" value="<?= $visit_id ?>">
</form>
</table>
<p class="close"><a href="javascript:window.close();">[ 閉じる ]</a></p>

<?php include 'footer.php'; ?>
