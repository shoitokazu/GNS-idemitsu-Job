<?php include 'header.php'; ?>

<script src="../common/selectWindow.js" language="JavaScript"></script>
<script src="../common/calendar.js" language="JavaScript"></script>

<form action="update.php" method="POST" name="form1">
<input type="hidden" name="select" value="<?=$select?>">
<?php
$table = "article";
$id = $_REQUEST['id'];
if ($id==null) {
	$code = $_REQUEST['code'];
	$id = DLookUp("ID", $table, "acode=".db_value($code, "str"));
	if ($id==null) return_error("存在しません。");
}

$where = "ID=$id";
//$where = limitCompany($where, $table);

$order = "ID";
$sql = "select * from $table where $where order by $order";
$rs = db_exec($conn, $sql);
db_fetch_row($rs);
$code = db_result($rs, "acode");
$ccode = db_result($rs, "ccode");

if ($page_mode=="shop") lock_form();

if ($page_mode!="shop") {
	$buttons_html .= ' / <a href="../mainte/addnew.php?acode='.urlencode($code).'&ccode='.urlencode($ccode).'">新規整備</a>';
	$buttons_html .= ' / <a href="../sales/list.php?clear=1&acode='.urlencode($code).'">販売履歴</a>';
}
$buttons_html .= ' / <a href="../mainte/list.php?clear=1&acode='.urlencode($code).'">整備履歴</a>';
$buttons_html .= ' / <a href="../customer/form.php?code='.urlencode($ccode).'">所有者</a>';

$tag = $_SESSION['article_tag'];
if ($tag=="article2") $tag="";
$id_list = paging_list_fetch($id, $tag);
echo_sub_header($buttons_html, "更新", "delete.php?id=$id", $id_list);
init_form_format($rs);

echo_form_frame("基本情報");
echo_html_tr($rs, "物件種別", "atype", "select2");
echo_form_tr($rs, "物件コード", "acode", "code");
echo_form_tr($rs, "船名", "aname", "name");
echo_form_tr($rs, "製造番号", "article16", "code");
echo_form_tr($rs, "在庫区分", "article17", "select1");
echo_form_tr($rs, "在庫エリア", "article18", "select1");
echo_html_tr($rs, "登録日", "making_date", "date");
echo_html_tr($rs, "更新日", "update_date", "date");
//echo_form_input("update_date", "hidate", date('Y/m/d'));
echo_form_frame_end();

/*
echo_form_frame("選択設定");
$sel = DLookUp("selected", "list_select", "`table`='$table' and rid=$id and uid=$uid");
echo '<tr><th>選択</th><td><input type="checkbox" name="selected" value="1"'.($sel[0]==1 ? " checked" : "").'></td></tr>';
echo_form_frame_end();
*/

echo_form_frame("所有者情報");
//echo_form_tr($rs, "顧客コード", "ccode", "ccode");
echo '<tr><th>顧客コード</th><td>';
echo_form_db($rs, "ccode", "ccode");
if (!is_lock()) echo '<input type="button" value="個人" onClick="getCustomerValue(1)">';
if (!is_lock()) echo '<input type="button" value="会社" onClick="getCustomerValue(2)">';
echo_form_tr($rs, "顧客名１", "cname", "name");
echo_form_tr($rs, "顧客名２", "csub", "name");
echo_form_frame_end();

echo_form_frame("商品情報 ※売上");
//echo_form_tr($rs, "艇種（商品コード）※整備", "model_code", "model");
echo '<tr><th>艇種（商品コード）※整備</th><td>';
echo_form_db($rs, "model_code", "model");
if (!is_lock()) echo '<input type="button" value="引当" onClick="getModelValue()" id="setModel">';
echo '</td></tr>';
echo_form_tr($rs, "製品名（商品名）※整備", "model_name", "name");
echo_form_tr($rs, "売上区分", "sales_category", "select2");
echo_form_tr($rs, "商品単価", "model_price", "cur");
echo_form_tr($rs, "商品原価", "model_cost", "cur");
echo_form_frame_end();

echo_form_frame("エンジン情報");
?>
<tr><th></th><th>(1)</th><th>(2)</th></tr>
<tr><th>エンジン名 ※整備</th>
<?php
echo_form_td($rs, "engine_name", "name");
echo_form_td($rs, "engine_name2", "name");
?>
</tr>
<tr><th>エンジン番号 ※整備</th>
<?php
echo_form_td($rs, "engine_code", "code");
echo_form_td($rs, "engine_code2", "code");
?>
</tr>
<tr><th>シンバル</th>
<?php
echo_form_td($rs, "engine1_1", "str");
echo_form_td($rs, "engine2_1", "str");
?>
</tr>
<tr><th>マリンギア</th>
<?php
echo_form_td($rs, "engine1_2", "str");
echo_form_td($rs, "engine2_2", "str");
?>
</tr>
<tr><th>ドライブ</th>
<?php
echo_form_td($rs, "engine1_3", "str");
echo_form_td($rs, "engine2_3", "str");
?>
</tr>
<tr><th>発電機</th>
<?php
echo_form_td($rs, "engine1_4", "str");
echo_form_td($rs, "engine2_4", "str");
?>
</tr>
<tr><th>（発電機）エンジン番号</th>
<?php
echo_form_td($rs, "engine1_5", "str");
echo_form_td($rs, "engine2_5", "str");
?>
</tr>
<?php
echo_form_frame_end();

echo_form_frame("所有艇情報");
echo_form_tr($rs, "HIN番号", "article1", "str");
echo_form_tr($rs, "納入日", "article2", "date");
echo_form_tr($rs, "船検登録日", "article3", "date");
echo_form_tr($rs, "初回検査日", "article19", "date");
echo_form_tr($rs, "次回検査日", "article15", "date");
echo_form_tr($rs, "船舶検査番号", "article4", "str");
echo_form_tr($rs, "登録船籍登録番号", "article5", "str");
echo_form_tr($rs, "保管形式", "article6", "str");
echo_form_tr($rs, "保管場所 ※整備", "dock", "str");
echo_form_tr($rs, "航行区域", "article7", "str");
echo_form_tr($rs, "備考", "remarks", "txt");
echo_form_frame_end();

echo_form_frame("中古艇管理情報");
echo_form_tr($rs, "メーカー分類", "article8", "str");
echo_form_tr($rs, "年式", "article9", "str");
echo_form_tr($rs, "馬力", "article10", "str");
echo_form_tr($rs, "燃料", "article11", "str");
echo_form_tr($rs, "形式", "article12", "str");
echo_form_tr($rs, "価格", "article13", "str");
echo_form_tr($rs, "儀装品", "article14", "txt");
echo_form_frame_end();

echo_list_frame("船検管理");
echo '<tr>';
echo '<th>削除</th>';
echo '<th>検査日</th>';
echo '<th>検査内容</th>';
echo '<th>ＤＭの発送日付</th>';
echo '<th>返事確認</th>';
echo '<th>返事確認日</th>';
echo '<th>実施日</th>';
echo '<th>備考</th>';
echo '</tr>';
$sql = "select * from article_task where hid=$id order by atask_date";
$rs = db_exec($conn, $sql);
init_list_format();
while (db_fetch_row($rs)) {
	init_list_line($rs);
	echo '<tr>';
	echo_list_td_delete();
	echo_list_td($rs, 'atask_date', 'date');
	echo_list_td($rs, 'atask_name', 'select1');
	echo_list_td($rs, 'atask1', 'date');
	echo_list_td($rs, 'atask2', 'select1');
	echo_list_td($rs, 'atask3', 'date');
	echo_list_td($rs, 'atask4', 'date');
	echo_list_td($rs, 'atask5', 'varchar');
	echo '</tr>';
}
echo '<tr><td colspan=8>';
if ($is_first_line) {
	echo '<input type="submit" name="addnew0" value="船検予定作成">';
} else {
	echo '<input type="submit" value="更新">';
	echo ' / <input type="submit" name="addnew1" value="一行追加">';
}
echo '</td></tr>';
echo_list_frame_end();
?>

</form>

<?php db_free($rs);?>

<script language="javaScript">
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
			document.form1.csub.value = decodeURIComponent(v[1]);
		}
	}
	if (type==undefined) type="";
	var code = document.form1.ccode.value;
	xmlhttp.open("GET", "../customer/getValue.php?code=" + code + "&type=" + type, true); 
	xmlhttp.send(null); 
}
function getModelValue() {
	var xmlhttp = getXmlhttp();
	xmlhttp.onreadystatechange=function(){
		if (xmlhttp.readyState==4 && xmlhttp.status==200){
			var r = xmlhttp.responseText;
			var v = r.split(",");
			document.form1.model_name.value = decodeURIComponent(v[0]);
			document.form1.sales_category.value = decodeURIComponent(v[1]);
			document.form1.model_price.value = decodeURIComponent(v[2]);
			document.form1.model_cost.value = decodeURIComponent(v[3]);
		}
	}
	var code = document.form1.model_code.value;
	xmlhttp.open("GET", "../select/getModelValue.php?code=" + code, true); 
	xmlhttp.send(null); 
}
</script>

<?php include 'footer.php'; ?>
