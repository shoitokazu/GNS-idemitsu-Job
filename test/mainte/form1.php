<?php require 'header.php'; ?>

<script src="../common/selectWindow.js" language="JavaScript"></script>
<script src="../common/calendar.js" language="JavaScript"></script>

<?php
$id = $_REQUEST['id'];
?>

<form action="update1.php" method="POST" name="form1">

<?php
$base = "mainte_h";
$where = "$base.ID=".$id;

$table = $base;
$table = "($table) left join sales_d on mainte_h.did=sales_d.ID";
$table = "($table) left join sales_h on sales_d.hid=sales_h.ID";
$table = "($table) left join work_h on sales_h.wid=work_h.ID";

$field = "$base.*";
$field .= ", work_h.wstaff, sales_h.ID as sid, work_h.ID as wid";
$sql = "select $field from $table";
if ($where<>"") $sql .= " where $where";
if ($sort<>"") $sql .= " order by $sort";
$rs = db_exec($conn, $sql);
db_fetch_row($rs);

echo_menu_tab($form_tab, 1, $id);

if (!is_my_company($rs)) lock_form();
$state = db_result($rs, "mainte_state");
if ($state==3) lock_form();

$buttons_html = "";
$sid = db_result($rs, "sid");
if ($sid=="") {
	$buttons_html .= ' / <input type="button" value="売上" onClick="btn_make_sales()">';
} else {
	$buttons_html .= ' / <a href="../sales/form1.php?id='.$sid.'">売上</a> ';
}
/*
$wid = db_result($rs, "wid");
if ($wid<>"") {
	$buttons_html .= ' <a href="../work/form1.php?id='.$wid.'">案件</a> ';
}
*/
$buttons_html .= ' / <input name="copy" type="submit" value="複製" onClick="return confirm(\'整備伝票を複製します。よろしいですか？\')">';

//$id_list = paging_list_fetch($id, $table);
//echo_sub_header($buttons_html, "更新", "", $id_list);

echo_button_frame();
echo '<tr><td>';
echo_form_submit();
echo $buttons_html;
echo '</tr></td>';
echo_button_frame_end();

init_form_format($rs);

echo_form_frame("伝票情報");
echo_html_tr($rs, "事業所", "company", "table");
echo_form_tr($rs, "管理番号", "mcode", "str");
echo_html_tr($rs, "作成日", "making_date", "date");
echo_form_tr($rs, "状態", "mainte_state", "select2");
echo '<tr><td colspan=2>↑「見積記録」は集計に含みません。</td></tr>';
echo '<tr><td colspan=2>「売上確定」にすると編集できなくなります。</td></tr>';
echo '<tr><td colspan=2>サービスショップは「新規整備」「作業中」でないと編集できません。</td></tr>';
//echo_form_tr($rs, "担当者", "uid", "account");
echo_form_frame_end();

echo_form_frame("顧客情報");
echo '<tr><th>顧客コード</th><td>';
echo_form_db($rs, "ccode", "ccode");
if (!is_lock()) echo '<input type="button" value="個人住所" onClick="getCustomerValue(1)">';
if (!is_lock()) echo '<input type="button" value="会社住所" onClick="getCustomerValue(2)">';
echo '</td></tr>';
echo_form_tr($rs, "顧客名", "cname", "str");
echo_form_tr($rs, "郵便番号", "zip", "str");
echo_form_tr($rs, "住所", "address", "str");
echo_form_tr($rs, "建物名", "building", "str");
echo_form_tr($rs, "電話", "tel", "str");
echo_form_frame_end();

echo_form_frame("物件情報");
echo '<tr><th>製品ID（物件番号）</th><td>';
echo_form_db($rs, "acode", "acode");
if (!is_lock()) echo '<input type="button" value="引当" onClick="getArticleValue()">';
echo '</td></tr>';
echo_form_tr($rs, "製品名（商品名）", "model_name", "str");
echo_form_tr($rs, "艇番（商品コード）", "model_code", "str");
echo_form_tr($rs, "船名（物件名）", "aname", "str");
echo_form_tr($rs, "エンジン名１", "engine_name", "str");
echo_form_tr($rs, "エンジン番号１", "engine_code", "str");
echo_form_tr($rs, "エンジン名２", "engine_name2", "str");
echo_form_tr($rs, "エンジン番号２", "engine_code2", "str");
echo_form_tr($rs, "保管場所", "dock", "str");
echo_form_frame_end();

echo_form_frame("整備情報");
echo_form_tr($rs, "売上区分", "mainte_category", "select2");
//echo_form_tr($rs, "伝票区分", "mainte1", "select1");
echo_form_tr($rs, "案件名", "mname", "str");
echo_form_tr($rs, "依頼区分", "mainte3", "select1");
echo_form_tr($rs, "依頼内容", "mainte4", "txt");
echo_form_tr($rs, "結果内容", "mainte5", "txt");
echo_form_tr($rs, "社内備考", "mainte6", "txt");
echo_form_frame_end();

echo_form_frame("整備の流れ");
echo_form_tr($rs, "依頼日付", "mainte7", "date");
echo_form_tr($rs, "完了予定日", "mainte8", "date");
echo_form_tr($rs, "完了日付", "mainte9", "date");
echo_form_tr($rs, "売上日付", "mainte10", "date");
echo '<tr><td colspan=2>↑売上集計する際の日付</td></tr>';
echo_form_tr($rs, "案件担当者", "wstaff", "staff");
echo_form_tr($rs, "整備担当者", "mstaff", "str");
echo_form_frame_end();

echo_form_frame("見積内容");
echo_form_tr($rs, "発行日", "mainte11", "date");
echo_form_tr($rs, "納期", "mainte12", "str");
echo_form_tr($rs, "完了予定日", "mainte13", "str");
echo_form_frame_end();
db_free($rs);
?>

</form>

<script language="JavaScript">
function btn_make_sales() {
	if (confirm('売上伝票を作成します。よろしいですか？')) {
		location.href="../sales/addnew.php?mid=<?=$id?>";
	}
}
function getXmlhttp() {
	var xmlhttp=false;
	if (typeof ActiveXObject!="undefined") { /* IE5, IE6 */
		try {
			xmlhttp=new ActiveXObject("Msxml2.XMLHTTP"); /* MSXML3 */
		} catch(e) {
			xmlhttp=new ActiveXObject("Microsoft.XMLHTTP"); /* MSXML2 */
		}
	}
	if (!xmlhttp && typeof XMLHttpRequest!="undefined") {
		xmlhttp=new XMLHttpRequest(); /* Firefox, Safari, IE7 */
	}
	if (!xmlhttp){
		alert("XMLHttpRequest非対応ブラウザ");
		return false;
	}
	return xmlhttp;
}
function getCustomerValue(type) {
	var xmlhttp = getXmlhttp();
	xmlhttp.onreadystatechange=function(){
		if (xmlhttp.readyState==4 && xmlhttp.status==200){
			var r = xmlhttp.responseText;
			var v = r.split(",");
			document.form1.cname.value = decodeURIComponent(v[0]);
//			document.form1.kana.value = decodeURIComponent(v[1]);
			document.form1.zip.value = decodeURIComponent(v[2]);
			document.form1.address.value = decodeURIComponent(v[3]);
			document.form1.building.value = decodeURIComponent(v[4]);
			document.form1.tel.value = decodeURIComponent(v[5]);
//			document.form1.fax.value = decodeURIComponent(v[6]);
//			document.form1.mobile.value = decodeURIComponent(v[7]);
		}
	}
	if (type==undefined) type="";
	var code = document.form1.ccode.value;
	xmlhttp.open("GET", "../customer/getValue.php?code=" + code + "&type=" + type, true); 
	xmlhttp.send(null); 
} 
function getArticleValue() {
	var xmlhttp = getXmlhttp();
	xmlhttp.onreadystatechange=function(){
		if (xmlhttp.readyState==4 && xmlhttp.status==200){
			var r = xmlhttp.responseText;
			var v = r.split(",");
			document.form1.aname.value = decodeURIComponent(v[0]);
			document.form1.model_code.value = decodeURIComponent(v[1]);
			document.form1.model_name.value = decodeURIComponent(v[2]);
			document.form1.engine_code.value = decodeURIComponent(v[3]);
			document.form1.engine_name.value = decodeURIComponent(v[4]);
			document.form1.engine_code2.value = decodeURIComponent(v[5]);
			document.form1.engine_name2.value = decodeURIComponent(v[6]);
			document.form1.dock.value = decodeURIComponent(v[7]);
		}
	}
	var code = document.form1.acode.value;
	xmlhttp.open("GET", "../article/getValue.php?code=" + code, true); 
	xmlhttp.send(null); 
}
</script>

<?php require 'footer.php'; ?>
