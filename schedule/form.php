<?php include 'header.php'; ?>

<script src="../common/selectWindow.js" language="JavaScript"></script>
<script src="../common/calendar.js" language="JavaScript"></script>

<form action="update.php" method="POST" name="form1">
<?php
$table = "schedule";
$id = $_REQUEST['id'];
if ($id=='') return_error();

$where = "ID=$id";
//$where = limitCompany($where, $table);

$order = "ID";
$sql = "select * from $table where $where order by $order";
$rs = db_exec($conn, $sql);
db_fetch_row($rs);

$buttons_html = '<input type="button" value="メール送信" onClick="openSelectWindow(\'../select/staff_maillist.php?select=on&visit_id='.$id.'\')">';

$id_list = paging_list_fetch($id, $table);
echo_sub_header($buttons_html, "更新", "delete.php?id=$id", $id_list);
init_form_format($rs);

echo_form_frame();
$type = echo_html_tr($rs, "種別", "schedule_type", "select2");
echo_form_tr($rs, "日付", "date", "date");
echo_form_tr($rs, "開始時間", "start_time", "time");
echo_form_tr($rs, "終了時間", "end_time", "time");
echo_form_tr($rs, "項目区分", "schedule3_".$type, "select1");
echo_form_tr($rs, "ＰＰランク", "schedule4", "str");
echo_form_tr($rs, "タイトル", "title", "str");
echo_form_tr($rs, "面談者", "schedule1", "str");
echo_form_tr($rs, "場所", "schedule2", "str");
echo_form_tr($rs, "内容", "contents", "blob");
echo_form_tr($rs, "備考", "remarks", "blob");
//echo_form_tr($rs, "参加者", "", "");
//echo_html_tr($rs, "担当者", "uid", "account");
echo_form_tr($rs, "担当者", "uid", "account");
echo_form_tr($rs, "案件", "wcode", "wcode");
echo '<tr><th>顧客コード</th><td>';
echo_form_db($rs, "ccode", "ccode");
if (!is_lock()) echo '<input type="button" value="引当" onClick="getCustomerValue()">';
echo_form_tr($rs, "顧客名", "cname", "name");
//echo_form_tr($rs, "顧客コード", "ccode", "ccode");
//echo_form_tr($rs, "顧客名", "cname", "str");
echo_form_frame_end();
db_free($rs);
?>

</form>
<script language="JavaScript">
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
		}
	}
	if (type==undefined) type="";
	var code = document.form1.ccode.value;
	xmlhttp.open("GET", "../customer/getValue.php?code=" + code + "&type=" + type, true); 
	xmlhttp.send(null); 
}
function getWorkValue() {
}
</script>

<?php include 'footer.php'; ?>
