<?php require 'header.php'; ?>

<script src="../common/calendar.js" language="JavaScript"></script>

<?php
$id = $_REQUEST['id'];
$ssid = $_REQUEST['ssid'];

$base = "mainte_h";
$table = $base;
$where = "ID=".$id;
$field = "*";
$sql = "select $field from $table";
if ($where<>"") $sql .= " where $where";
if ($sort<>"") $sql .= " order by $sort";
$rs = db_exec($conn, $sql);
db_fetch_row($rs);
?>

<?php echo_menu_tab($form_tab, 4, $id); ?>

<?php
echo_button_frame("更新ボタン");
?>
<tr><td>
<input type="button" value="明細書" onClick="location.href='../print/mainte_print.php?id=<?=$id?>&type=1'">
<input type="button" value="納品書" onClick="location.href='../print/mainte_print.php?id=<?=$id?>&type=2'">
<input type="button" value="見積書" onClick="location.href='../print/mainte_print.php?id=<?=$id?>&type=3'">
</td></tr>
<?php
echo_button_frame_end();

echo_form_frame("顧客情報");
echo_html_tr($rs, "顧客コード", "ccode", "str");
echo_html_tr($rs, "顧客名", "cname", "str");
echo_html_tr($rs, "住所", "address", "str");
echo_html_tr($rs, "電話", "tel", "str");
echo_form_frame_end();

echo_form_frame("振替先");
echo_html_tr($rs, "振替先", "transfer", "str");
echo_html_tr($rs, "発行元", "scenter", "str");
echo_form_frame_end();

db_free($rs);

$base = "mainte_shop";
$where = "mid=$id";
$where .= " and ssid=$uid";
$table = $base;
$field = "$base.*";
$sql = "select $field from $table";
if ($where<>"") $sql .= " where $where";
if ($sort<>"") $sql .= " order by $sort";
$rs = db_exec($conn, $sql);
if (db_fetch_row($rs)) {
	echo_form_frame("サービスショップ対応状況");
	echo_html_tr($rs, "印刷日<br>(明細書)", "shop6", "date");
	echo_form_frame_end();
}
db_free($rs);
?>

<?php require 'footer.php'; ?>
