<?php require 'header.php'; ?>

<script src="../common/selectWindow.js" language="JavaScript"></script>
<script src="../common/calendar.js" language="JavaScript"></script>

<?php
$id = $_REQUEST['id'];
$ssid = $_REQUEST['ssid'];
$sql = "select * from mainte_h where ID=$id";
$rs = db_exec($conn, $sql);
db_fetch_row($rs);

echo_menu_tab($form_tab, 3, $id);

$state_lock = false;
if (!is_my_company($rs)) $state_lock = true;
$state = db_result($rs, "mainte_state");
if ($state==3) $state_lock = true;
lock_form($state_lock);
if ($state_lock) $attr = " disabled";
?>

<form action="update3.php" method="POST" name="form1">
<input type="hidden" name="id" value="<?=$id?>">

<?php
echo_button_frame();
?>
<tr><td>
<input type="submit" value="更新"<?=$attr?>>
/ <input type="button" value="サービスショップ選択" onClick="openSelectWindow('../select/mshop_list.php?mid=<?=$id?>');"<?=$attr?>>
/ <input type="button" value="すべて確定" onClick="set_lock_all()"<?=$attr?>>
<?php
echo_button_frame_end();
?>

<?php
$where = "mainte_shop.mid=$id";
$table = "mainte_shop left join sshop";
$table .= " on mainte_shop.ssid=sshop.ID";
$field = "mainte_shop.*";
$field .= ",sshop.sort";
$sort[] = "sshop.sort";
$sort[] = "mainte_shop.ssid";
$sql = "select $field from $table";
if ($where<>"") $sql .= " where $where";
if ($sort<>null) $sql .= " order by ".implode(",", $sort);
$rs = db_exec($conn, $sql);
echo_list_frame("整備明細");
?>
<tr>
<th>削除</th>
<th>サービスショップ名</th>
<th>確認日</th>
<th>完了日</th>
<th>売上日</th>
<th>完了納期</th>
<th>確定</th>
<th>備考</th>
</tr>
<?php
init_list_format();
$i=0;
while (db_fetch_row($rs)) {
	init_list_line($rs);
	$i++;
	$ssid = db_result($rs, "ssid");
	$name = DLookUp("name", "sshop", "ID=$ssid");
	echo '<tr>';
	echo "<td>";
	echo_list_delete();
	echo "</td>";
	echo_html_td(0, "", "str", $name, "default");
	echo_list_td($rs, "shop1", "date");
	echo_list_td($rs, "shop2", "date");
	echo_list_td($rs, "shop3", "date");
	echo_html_td($rs, "shop4", "date");
	echo_list_td($rs, "shop_lock", "bool");
	echo_list_td($rs, "remarks", "txt");
	echo '</tr>';
}
db_free($rs);
echo_list_frame_end();
?>

<script language="javaScript">
function set_lock_all() {
	for (i=0; i<=<?=$line?>; i++) {
		document.getElementById("shop_lock_" + i).checked = true;
	}
}
</script>

<?php require 'footer.php'; ?>
