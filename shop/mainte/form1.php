<?php require 'header.php'; ?>

<script src="../common/calendar.js" language="JavaScript"></script>

<?php
$id = $_REQUEST['id'];

echo_menu_tab($form_tab, 1, $id);
?>

<form action="update1.php" method="POST" name="form1">

<?php
$msid = DLookUp("ID", "mainte_shop", "mid=$id and ssid=$uid");
if ($msid==false) lock_form();

$state = DLookUp("mainte_state", "mainte_h", "ID=$id");
if (!($state==0 or $state==1 or $state==9)) lock_form();

$shop_lock = DLookUp("shop_lock", "mainte_shop", "mid=$id and ssid=$uid");
if ($shop_lock===null) lock_form();
if ($shop_lock) lock_form();

echo_button_frame();
echo '<tr><td>';
echo_form_submit();
echo '</td></tr>';
echo_button_frame_end();

$base = "mainte_h";
$table = $base;
$where = "$base.ID=".$id;

$field = "*";
$sql = "select $field from $table";
if ($where<>"") $sql .= " where $where";
if ($sort<>"") $sql .= " order by $sort";
$rs = db_exec($conn, $sql);

init_form_format($rs);
?>

<div class="frame_box">
<?php
echo_form_frame();
?>
<tr><th colspan=2>伝票情報</th></tr>
<?php
echo_html_tr($rs, "管理番号", "mcode", "str");
?>
<tr><th colspan=2>振替先</th></tr>
<?php
echo_html_tr($rs, "振替先", "transfer", "str");
echo_html_tr($rs, "サービスセンター", "scenter", "str");
?>
<tr><th colspan=2>顧客情報</th></tr>
<?php
echo_html_tr($rs, "顧客コード", "ccode", "str");
echo_html_tr($rs, "顧客名", "cname", "str");
echo_html_tr($rs, "住所", "address", "str");
echo_html_tr($rs, "電話", "tel", "str");
?>
<tr><th colspan=2>整備物件情報</th></tr>
<?php
echo_html_tr($rs, "製品ID", "acode", "str");
echo_html_tr($rs, "製品名", "model_name", "str");
echo_html_tr($rs, "艇番", "model_code", "str");
echo_html_tr($rs, "船名", "aname", "str");
echo_html_tr($rs, "エンジン名", "engine_name", "str");
echo_html_tr($rs, "エンジン番号", "engine_code", "str");
echo_html_tr($rs, "保管場所", "dock", "str");
echo_form_frame_end();
?>
</div>

<div class="frame_box">
<?php
echo_form_frame();
?>
<tr><th colspan=2>整備内容</th></tr>
<?php
echo_html_tr($rs, "状態", "mainte_state", "select2");
echo_html_tr($rs, "伝票区分", "mainte1", "str");
echo_html_tr($rs, "案件名", "mname", "str");
echo_html_tr($rs, "依頼区分", "mainte3", "str");
echo_html_tr($rs, "依頼内容", "mainte4", "str");
echo_html_tr($rs, "結果内容", "mainte5", "str");
echo_html_tr($rs, "社内備考", "mainte6", "str");
?>
<tr><th colspan=2>整備の流れ</th></tr>
<?php
echo_html_tr($rs, "依頼日付", "mainte7", "date");
echo_html_tr($rs, "完了予定日", "mainte8", "date");
echo_html_tr($rs, "完了日付", "mainte9", "date");
echo_html_tr($rs, "売上日付", "mainte10", "date");
echo_html_tr($rs, "整備担当者", "mstaff", "str");
echo_form_frame_end();

echo_form_frame();
echo "<tr><th colspan=2>運転時間</th></tr>";
echo_form_tr($rs, "左エンジン", "mainte15", "int");
echo_form_tr($rs, "右エンジン", "mainte16", "int");
echo_form_tr($rs, "発電機", "mainte17", "int");
echo_form_frame_end();

?>
</div>

<?php
db_free($rs);
?>

</form>
<br clear=all>

<?php require 'footer.php'; ?>
