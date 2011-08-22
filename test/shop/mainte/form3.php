<?php require 'header.php'; ?>

<script src="../common/calendar.js" language="JavaScript"></script>

<?php
$id = $_REQUEST['id'];
$ssid = $uid;

echo_menu_tab($form_tab, 3, $id);
?>

<form action="update3.php" method="POST" name="form1">
<input type="hidden" name="mid" value="<?=$id?>">

<?php
$base = "mainte_shop";
$where = "mid=$id";
$where .= " and ssid=$ssid";
$table = $base;
$field = "$base.*";
$sql = "select $field from $table";
if ($where<>"") $sql .= " where $where";
if ($sort<>"") $sql .= " order by $sort";
$rs = db_exec($conn, $sql);
if (!db_fetch_row($rs)) {
	return_error("自社案件ではありません。");
}
$shop_lock = db_result($rs, "shop_lock");
$complete = db_result($rs, "shop2");
$confirm = db_result($rs, "shop1");

$state = DLookUp("mainte_state", "mainte_h", "ID=$id");
if (!($state==0 or $state==1)) {
	$state_lock = ture;
	$attr = " disabled";
} else {
	$state_lock = false;
}

lock_form($shop_lock or $state_lock);

echo_button_frame();
echo '<tr><td>';
if ($shop_lock) {
	echo '確定済';
} elseif ($complete==null) {
	echo_form_submit();
} else {
	echo '完了済';
}
echo '</td></tr>';
echo_button_frame_end();

init_form_format($rs);
echo_form_frame("サービスショップ対応状況");
?>
<tr><th>確認日</th>
<td>
<?php
echo_html_db($rs, "shop1", "date");
echo '</td><td>';
if ($shop_lock==false and $state_lock==false) {
	if ($confirm==null) {
		echo '<input type="submit" name="confirm" value="確認">';
	} else {
		echo '<input type="submit" name="unconfirm" value="取消">';
	}
}
?>
</td></tr>

<tr><th>完了日</th>
<td>
<?php
echo_html_db($rs, "shop2", "date");
echo '</td><td>';
if ($shop_lock==false and $state_lock==false) {
	if ($complete==null) {
		echo '<input type="submit" name="complete" value="完了">';
	} else {
		echo '<input type="submit" name="uncomplete" value="取消">';
	}
}
?>
</td></tr>

<?php
echo_html_tr($rs, "売上日", "shop3", "date");
?>
<tr><th>完了納期</th><td>
<?php
if ($complete==null and $shop_lock==false) {
	echo_form_db($rs, "shop4", "date");
} else {
	echo_html_db($rs, "shop4", "date");
}
?>
</td></tr>

<?php
echo_html_tr($rs, "備考", "remarks", "txt");
echo_html_tr($rs, "印刷日<br>(明細書)", "shop6", "date");
echo_form_frame_end();

db_free($rs);
?>

<?php require 'footer.php'; ?>
