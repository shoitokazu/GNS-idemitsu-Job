<?php include 'header.php'; ?>

<script src="../common/selectWindow.js" language="JavaScript"></script>
<script src="../common/calendar.js" language="JavaScript"></script>
<script language="JavaScript">
function btn_make_sales(wid) {
	if (!confirm('売上(見通し)を作成します。よろしいですか？')) return;
	document.form1.sales_addnew.value = wid;
	document.form1.submit();
}
</script>

<?php
$base = "work_h";
$id = $_REQUEST['id'];
if ($id==null) {
	$code = $_REQUEST['code'];
	$id = DLookUp("ID", $base, "wcode=".db_value($code, "str"));
	if ($id==null) return_error("存在しません。");
}
?>

<form action="update1.php" method="POST" name="form1">
<input type="hidden" name="select" value="<?=$select?>">
<input type="hidden" name="sales_addnew" value="">

<?php
$where = "$base.ID=$id";
//$where = limitCompany($where, $table);

$order = "$base.ID";
$table = $base;
$table = "($table) left join sales_h on $base.ID=sales_h.wid";
$sql = "select $base.*,sales_h.ID as sid from $table where $where order by $order";
$rs = db_exec($conn, $sql);
if (!db_fetch_row($rs)) return_error("存在しません。");
//if (!is_my_company($rs)) lock_form();
$state = db_result($rs, "work_state");
if ($state==3) lock_form();

$sid = db_result($rs, "sid");
if ($sid=="") {
	$buttons_html = '<input type="button" value="売上" onClick="btn_make_sales('.$id.')">';
} else {
	$buttons_html = '<a href="../sales/form1.php?id='.$sid.'">売上</a>';
}
if ($state==3) {
	$buttons_html .= ' <input name="unlock" type="button" value="確定解除" onClick="btn_unlock()">';
}

$id_list = paging_list_fetch($id, "work");
echo_sub_header($buttons_html, "更新", "delete.php?id=$id", $id_list);
init_form_format($rs);

echo_form_frame("伝票情報");
echo_form_tr($rs, "事業所", "company", "table");
$code = echo_form_tr($rs, "管理番号", "wcode", "str");
echo_html_tr($rs, "作成日", "making_date", "date");
echo_form_tr($rs, "状態", "work_state", "select2");
echo '<tr><td colspan=2>↑「見切り」は集計に含みません。</td></tr>';
echo '<tr><td colspan=2>「商談中」「商談Ａ」「商談Ｂ」「商談Ｃ」が見込み案件として抽出されます。</td></tr>';
echo '<tr><td colspan=2>「成約」が成立後案件として抽出されます。</td></tr>';
echo '<tr><td colspan=2>「完了」にすると編集できなくなります。</td></tr>';
echo '<tr><td colspan=2>削除を行うと「見切り」になります。</td></tr>';
//echo_form_tr($rs, "担当者", "uid", "account");
echo_form_frame_end();

echo_form_frame("顧客情報");
//echo_form_tr($rs, "顧客コード", "ccode", "str");
echo '<tr><th>顧客コード</th><td>';
echo_form_db($rs, "ccode", "ccode");
if (!is_lock()) echo '<input type="button" value="個人" onClick="getCustomerValue(1)">';
if (!is_lock()) echo '<input type="button" value="会社" onClick="getCustomerValue(2)">';
echo '</td></tr>';

echo_form_tr($rs, "顧客名１", "cname", "str");
echo_form_tr($rs, "顧客名２", "csub", "str");
echo_form_tr($rs, "ふりがな", "kana", "str");
echo_form_tr($rs, "郵便番号", "zip", "str");
echo_form_tr($rs, "住所", "address", "str");
echo_form_tr($rs, "建物名", "building", "str");
echo_form_tr($rs, "TEL", "tel", "str");
echo_form_tr($rs, "FAX", "fax", "str");
echo_form_tr($rs, "携帯電話", "mobile", "str");
echo_form_frame_end();

echo_form_frame("案件情報");
echo_form_tr($rs, "案件担当者", "wstaff", "staff");
echo_form_tr($rs, "受付日", "work1", "date");
//echo_form_tr($rs, "受注日", "work2", "date");
//echo_form_tr($rs, "売上日", "work3", "date");
echo_form_tr($rs, "案件名", "wname", "str");
echo_form_tr($rs, "購入手段", "work5", "select1");
echo_form_tr($rs, "キャッチ手段", "work6", "choice");
echo_form_tr($rs, "受注予定日", "work7", "str");
echo_form_tr($rs, "見込予算", "work8", "int");
echo_form_tr($rs, "見込金額", "work9", "int");
echo_form_tr($rs, "案件メモ", "work10", "txt");
echo_form_frame_end();

echo_frame_br();
echo_form_frame("顧客状況把握チェック");
//echo_form_tr($rs, "初回商談", "step1", "bool");

echo '<tr><td>';
echo_form_db($rs, "step2", "bool");
echo "免許確認";
echo '</td><td>';
echo_form_db($rs, "step3", "bool");
echo "艇種確認";
echo '</td><td>';
echo_form_db($rs, "step4", "bool");
echo "予算確認";
echo '</td><td>';
echo_form_db($rs, "step5", "bool");
echo "商品説明";
echo '</td></tr>';

echo '<tr><td>';
echo_form_db($rs, "step6", "bool");
echo "競合確認";
echo '</td><td>';
echo_form_db($rs, "step7", "bool");
echo "見積提出";
echo '</td><td>';
echo_form_db($rs, "step8", "bool");
echo "決定権者把握";
echo '</td><td>';
echo_form_db($rs, "step9", "bool");
echo "試乗";
echo '</td></tr>';

echo '<tr><td>';
echo_form_db($rs, "step10", "bool");
echo "マリーナ確認";
echo '</td><td>';
echo_form_db($rs, "step11", "bool");
echo "支払プラン提出";
echo '</td><td>';
echo_form_db($rs, "step12", "bool");
echo "ＯＰ確認";
echo '</td><td>';
echo_form_db($rs, "step13", "bool");
echo "納期把握";
echo '</td></tr>';

//echo_form_tr($rs, "成約待ち（仮契約）", "step14", "bool");
//echo_form_tr($rs, "成約", "step15", "bool");
echo_form_frame_end();
?>

</form>

<?php db_free($rs);?>

<script language="javaScript">
function btn_unlock() {
	if (confirm('確定を解除します。よろしいですか？')) {
		location.href="unlock.php?id=<?=$id?>";
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
			document.form1.csub.value = decodeURIComponent(v[1]);
			document.form1.kana.value = decodeURIComponent(v[2]);
			document.form1.zip.value = decodeURIComponent(v[3]);
			document.form1.address.value = decodeURIComponent(v[4]);
			document.form1.building.value = decodeURIComponent(v[5]);
			document.form1.tel.value = decodeURIComponent(v[6]);
			document.form1.fax.value = decodeURIComponent(v[7]);
			document.form1.mobile.value = decodeURIComponent(v[8]);
		}
	}
	if (type==undefined) type="";
	var code = document.form1.ccode.value;
	xmlhttp.open("GET", "../customer/getValue.php?code=" + code + "&type=" + type, true); 
	xmlhttp.send(null); 
}
function btn_select() {
	setSelectValue('<?=$code?>');
}
</script>

<?php include 'footer.php'; ?>
