<?php include 'header.php'; ?>

<script src="../common/selectWindow.js" language="JavaScript"></script>
<script src="../common/calendar.js" language="JavaScript"></script>

<form action="update1.php" method="POST" name="form1">

<?php
$base = "sales_h";
$hid = $_REQUEST['id'];
if ($hid=='') return_error();

$table = $base;
//$table = "($table) left join work_h on $base.wid=work_h.ID";
$where = "$base.ID=$hid";
//$where = limitCompany($where, $table);

$sql = "select $base.* from $table where $where";
$rs = db_exec($conn, $sql);
if (!db_fetch_row($rs)) return_error("存在しません。");
//if (!is_my_company($rs)) lock_form();
$state = db_result($rs, "sales_state");
if ($state==3) lock_form();
if (is_lock()) $attr = " disabled";

$wid = db_result($rs, "wid");
if ($wid<>0) {
	$buttons_html = '<a href="../work/form1.php?id='.$wid.'">案件</a>';
} else {
	$buttons_html = "";
}
if ($state==3) {
	$buttons_html .= '　<input name="unlock" type="button" value="確定解除" onClick="btn_unlock()">';
}

$id_list = paging_list_fetch($hid, "sales");
echo_sub_header($buttons_html, "更新", "delete.php?id=$hid", $id_list);
init_form_format($rs);

echo_form_frame("伝票情報");
echo_form_tr($rs, "事業所", "company", "select2");
echo_form_tr($rs, "管理番号", "scode", "str");
echo_html_tr($rs, "作成日", "making_date", "date");
echo_form_tr($rs, "状態", "sales_state", "select2");
echo '<tr><td colspan=2>↑「失注」は、集計に含みません。</td></tr>';
echo '<tr><td colspan=2>「見通し」「受注」｢売上」は、管理表の見通しとして集計されます。</td></tr>';
echo '<tr><td colspan=2>「計画」は、管理表の計画として集計されます。</td></tr>';
echo '<tr><td colspan=2>「受注」「売上」は、販売実績表で抽出されます。</td></tr>';
echo '<tr><td colspan=2>「売上」にすると編集できなくなります。</td></tr>';
echo '<tr><td colspan=2>削除を行うと「失注」になります。</td></tr>';
//echo_form_tr($rs, "担当者", "uid", "account");
echo_form_frame_end();

echo_form_frame("売上情報");
echo_form_tr($rs, "エリア", "area", "select1");
echo_form_tr($rs, "案件担当者", "wstaff", "staff");
echo_form_tr($rs, "受注日", "acceptance_date", "date");
echo_form_tr($rs, "売上予定日", "due_date", "date");
echo '<tr><td colspan=2>↑管理表で集計する際の日付</td></tr>';
echo_form_tr($rs, "売上日", "sales_date", "date");
//echo_form_tr($rs, "顧客", "ccode", "ccode");
echo '<tr><th>顧客コード</th><td>';
echo_form_db($rs, "ccode", "ccode");
if (!is_lock()) echo '<input type="button" value="個人" onClick="getCustomerValue(1)">';
if (!is_lock()) echo '<input type="button" value="会社" onClick="getCustomerValue(2)">';
echo_form_tr($rs, "顧客名１", "cname", "name");
echo_form_tr($rs, "顧客名２", "csub", "name");
//echo_form_tr($rs, "本体物件", "acode", "acode");
echo '<tr><th>本体物件</th><td>';
echo_form_db($rs, "acode", "acode");
if (!is_lock()) echo '<input type="button" value="引当" onClick="getArticleValue()">';
echo '</td></tr>';
echo_form_tr($rs, "物件名", "aname", "name");
echo_form_frame_end();
?>

<?php
echo_button_frame();
?>
<tr><td>
<!--
<input type="submit" name="addnew1" value="本体" onClick="return check_acode()"<?=$attr?>>
-->
<input type="hidden" name="addnew1" value="">
<input type="button" value="本体" onClick="return btn_addnew1()"<?=$attr?>>
<input type="submit" name="addnew2" value="特ギ"<?=$attr?>>
<input type="submit" name="addnew3" value="値引き"<?=$attr?>>
<input type="submit" name="addnew4" value="その他売上"<?=$attr?>>
<input type="submit" name="addnew5" value="原価修正"<?=$attr?>>
</td></tr>
<?php
echo_button_frame_end();
?>

<?php
$base = "sales_d";
$table = $base;
//$table = "$base left join mainte_h on $base.ID=mainte_h.did";
$where = "$base.hid=$hid";
$sort = get_sort();
if ($sort<>"") $sort .= ",";
$sort .= "sales_category";
$field = "$base.*";
//$field .= ", mainte_h.mainte_state";
$sql = "select $field from $table where $where";
if ($sort<>"") $sql .= " order by $sort";
$rs = db_exec($conn, $sql);
echo_list_frame("明細");
?>
<tr>
<th>削除</th>
<?php
$href = "?id=$hid&";
echo_sort_td("表示順", "sort", "link", $href);
echo_sort_td("項目名", "name", "link", $href);
echo_sort_td("売上区分", "sales_category", "link", $href);
//echo_sort_td("売上額", "amount", "link", $href);
echo_sort_td("単価", "price", "link", $href);
echo_sort_td("原価", "cost", "link", $href);
echo_sort_td("数量", "num", "link", $href);
echo '<th>整備</th>';
echo '</tr>';
init_list_format();
$i=0;
$asum = 0;
$profit = 0;
while (db_fetch_row($rs)) {
	$id = init_list_line($rs);
	$i++;
	echo "<tr>";
	echo "<td>";
	echo_list_delete();
	echo "</td>";
	echo_list_td($rs, "sort", "sort");
	echo_list_td($rs, "name", "name");
	echo_list_td($rs, "sales_category", "select2");
//	echo_list_td($rs, "amount", "cur");
	$price = echo_list_td($rs, "price", "cur");
	$cost = echo_list_td($rs, "cost", "cur");
	$num = echo_list_td($rs, "num", "cur");
	$asum += $price * $num;
	$csum += $cost * $num;
	$sql = "select ID,mainte_state from mainte_h where did=$id";
	$row = db_row($conn, $sql);
	if ($row==false) {
?>
<td>
<input type="button" value="新規" onClick="if (check_acode()) btn_addnew(<?=$id?>)"<?=$attr?>>
<!--
<input type="button" value="選択" onClick="btn_select(<?=$id?>)"<?=$attr?>>
-->
</td>
<?php
	} else {
?>
<td><a href="../mainte/form1.php?id=<?=$row[0]?>">内容</a>
<?php
		if ($row[1]==2) {
			echo '<input type="button" value="確定" onClick="btn_decide('.$row[0].')">';
		}
		if ($row[1]==3) {
			echo '<input type="button" value="確定解除" onClick="btn_cancel('.$row[0].')">';
		}
?>
</td>
<?php
	}
	echo "</tr>";
}
echo_list_frame_end();
db_free($rs);

echo_list_frame("合計金額");
?>
<tr>
<th>売上</th>
<th>原価</th>
<th>荒利</th>
<th>荒利率</th>
</tr>
<?php
echo '<tr>';
echo_html_td(0, "", "cur", $asum, "default");
echo_html_td(0, "", "cur", $csum, "default");
echo_html_td(0, "", "cur", $asum-$csum, "default");
if ($asum==0) {
	$rate = "";
} else {
	$rate = ($asum-$csum) / $asum * 100;
}
echo_html_td(0, "", "percent", $rate, "default");
echo '</tr>';
echo_form_frame_end();
?>
<input type="hidden" name="mainte_addnew" value="">
<input type="hidden" name="mainte_decide" value="">
<input type="hidden" name="mainte_cancel" value="">
</form>

<script language="javaScript">
function btn_unlock() {
	if (confirm('確定を解除します。よろしいですか？')) {
		location.href="unlock.php?id=<?=$hid?>";
	}
}
function btn_addnew(id) {
	if (!confirm("整備伝票を作成します。よろしいですか？")) return;
	document.form1.mainte_addnew.value = id;
	document.form1.submit();
}
/*
function btn_select(id) {
	openNormalWindow("../mainte/search.php?select=" + id);
}
*/
function btn_decide(mid) {
	if (!confirm("整備内容を確定します。よろしいですか？")) return;
	document.form1.mainte_decide.value = mid;
	document.form1.submit();
}
function btn_cancel(mid) {
	if (!confirm("整備の確定を解除します。よろしいですか？")) return;
	document.form1.mainte_cancel.value = mid;
	document.form1.submit();
}
function check_acode() {
	if (document.form1.acode.value=="") {
		alert("対象物件が選択されていません。");
		return false;
	}
	return true;
}
function btn_addnew1() {
	if (document.form1.acode.value=="") {
		openNormalWindow("../article/search.php?select=acode,sales&acode=" + document.getElementById("acode").value);
	} else {
		document.form1.addnew1.value="ON";
		document.form1.submit();
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
		}
	}
	var code = document.form1.acode.value;
	xmlhttp.open("GET", "../article/getValue.php?code=" + code, true); 
	xmlhttp.send(null); 
} 
</script>

<?php include 'footer.php'; ?>
