<?php require 'header.php'; ?>

<?php
$id = $_REQUEST['id'];
$def_ssid = $_REQUEST['ssid'];
?>

<?php echo_menu_tab($form_tab, 2, $id);?>

<form action="update2.php" method="POST" name="form1">

<?php
$base = "mainte_shop";
$table = "$base left join sshop on mainte_shop.ssid=sshop.ID";
$field = "$base.ssid, sshop.sname as shop_name";
$where = "$base.mid=$id";
$sort = "sshop.sort, $base.ssid";
$sql = "select $field from $table";
if ($where<>"") $sql .= " where $where";
if ($sort<>"") $sql .= " order by $sort";
$rs = db_exec($conn, $sql);
$i=0;
$shop_list[$i][0] = -1;
$shop_list[$i][1] = "全体";
$i++;
while (db_fetch_row($rs)) {
	$shop_list[$i][0] = db_result($rs, "ssid");
	$shop_list[$i][1] = db_result($rs, "shop_name");
	$i++;
}
if ($def_ssid=="") $def_ssid=$uid;

$lock = false;
$state = DLookUp("mainte_state", "mainte_h", "ID=$id");
//if (!($state==0 or $state==1)) $lock = true;
if (!($state==0 or $state==1 or $state==9)) $lock = true;
$shop_lock = DLookUp("shop_lock", "mainte_shop", "mid=$id and ssid=$uid");
if ($shop_lock===null) $lock = true;
if ($shop_lock) $lock = true;

if ($lock) $lock_str = " disabled";

echo_button_frame();
echo_buttons(0);
echo_button_frame_end();
?>

<br clear=all>
<div class="frame_box">
<div class="frame_tab">
<?php
$back = $_REQUEST['back'];
if (is_array($shop_list)) {
	foreach ($shop_list as $v) {
		if ($v[0]==$def_ssid) {
			echo '<span class="tab_selected">'.$v[1].'</span>';
		} else {
//			echo '<span class="tab"><a href="javascript:location.replace(\'?id='.$id.'&ssid='.$v[0].'\')">'.$v[1].'</a></span>';
			echo '<span class="tab"><a href="form2.php?id='.$id.'&ssid='.$v[0].'&back='.($back+1).'">'.$v[1].'</a></span>';
		}
	}
}
?>
</div>
<input type="hidden" name="ssid" value="<?=$def_ssid?>">
<input type="hidden" name="id" value="<?=$id?>">

<?php
$base = "mainte_d";
$where = "mainte_d.hid=".$id;
if ($def_ssid<>"" and $def_ssid<>-1) $where .= " and $base.ssid=".$def_ssid;
$table = $base;
$table = "$table left join sshop on mainte_d.ssid=sshop.ID";
$field = "$base.*, sshop.sname as shop_name";
$sort = "group1,sort,itype,icode";
$sql = "select $field from $table";
if ($where<>"") $sql .= " where $where";
if ($sort<>"") $sql .= " order by $sort";
$rs = db_exec($conn, $sql);

echo_list_frame("整備明細", "");
?>
<tr>
<th>削除</th>
<th>グループ</th>
<th>順番</th>
<th>区分</th>
<th>部品番号</th>
<th>項目</th>
<th>数量</th>
<th>単価</th>
<th>定価合計</th>
<th>振替率</th>
<th>振替金額</th>
<th>SC率</th>
<th>変更</th>
<th>SC</th>
<th>サービス店</th>
<th>仕切率</th>
<th>変更</th>
<th>仕切金額</th>
</tr>
<?php
init_list_format();
$i=0;
while (db_fetch_row($rs)) {
	init_list_line($rs);
	if ($i % 10 == 0 and $i<>0) echo_buttons($i);
	$i++;
	$ssid = db_result($rs, "ssid");
	$shop_lock = false;
	if ($ssid<>"") {
		$where0 = "ssid=$ssid and mid=$id";
		$shop_lock = DLookUp("shop_lock", "mainte_shop", $where0);
		if ($shop_lock==1) $shop_lock=true;
	}
	if ($ssid<>$uid) $shop_lock=true;
	lock_form($shop_lock);
	echo '<input type="hidden" id="lock_'.$line.'" value="'.$shop_lock.'">';
	echo "<tr>";
	echo "<td>";
	echo_list_delete();
	echo "</td>";
	echo_list_td($rs, "group1", "str");
	echo_list_td($rs, "sort", "int");
	$type = echo_list_td($rs, "itype", "select2");
	echo_list_td($rs, "icode", "code");
	echo_list_td($rs, "iname", "name");
	$str = "int";
	if ($type==2) $str = "real";
	$num = echo_list_td($rs, "num", $str);
	if ($ssid<>$uid) {
		echo '<td></td><td></td><td></td><td></td><td></td><td></td><td></td>';
		echo_html_td($rs, "shop_name", "str");
		echo '<td></td><td></td><td></td>';
		echo '</tr>';
		continue;
	}
	$price = echo_list_td($rs, "price", "cur");
	$amount = $num * $price;
	echo_html_td($rs, "amount", "cur", $amount, "default");
	$percent = echo_list_td($rs, "rate", "percent");
	$trans = round($amount * $percent / 100);
	echo_html_td($rs, "trans", "cur", $trans, "default");
	$sc = db_result($rs, "cost1");
	$sc_rate = ($amount==0 ? "" : round($sc / $amount * 100));
	echo_html_td($rs, "", "percent", $sc_rate, "default");
	if ($shop_lock) {
		echo '<td></td>';
	} else {
?>
<td><input class="num" type="text" size="3" id="sc_rate_<?=$line?>" onBlur="set_sc(this.value, <?=$line?>)">%</td>
<?php
	}
	echo_list_td($rs, "cost1", "cur");
	echo_html_td($rs, "shop_name", "str");
	
	$invoice = db_result($rs, "cost2");
	$invoice_rate = ($amount==0 ? "" : round($invoice / $amount * 100));
	echo_html_td($rs, "", "percent", $invoice_rate, "default");
	if ($shop_lock) {
		echo '<td></td>';
	} else {
?>
<td><input class="num" type="text" size="3" id="invoice_rate_<?=$line?>" onBlur="set_invoice(this.value, <?=$line?>)">%</td>
<?php
	}
	$invoice = echo_list_td($rs, "cost2", "cur");
	$sum[$type] += $trans;
	echo "</tr>";
}
echo_buttons($i);
echo_list_frame_end("整備明細", "");
db_free($rs);

function echo_buttons($i) {
	global $def_ssid, $uid, $lock, $id;

	if ($lock or ($def_ssid<>$uid and $def_ssid<>-1)) $attr = " disabled";
	if ($def_ssid<>$uid and $def_ssid<>-1) $attr2 = " disabled";
?>
<tr><td colspan=18>
<div style="float:left">
<input type="submit" value="更新"<?=$attr?> id="sub_button_<?=$i?>">
/ <input type="submit" name="addnew" value="空白追加" onclick="window.location.href='../mitem_add.php?mid=<?=$id?>';"<?=$attr?>>
/ <input type="button" value="CSV出力" onClick="btn_export();"<?=$attr2?>>
/ <input type="button" value="CSV入力" onClick="btn_import();"<?=$attr?>>
</div>
<?php if ($i<>0) { ?>
<div style="float:right"><input type="button" value="←" onClick="btn_to_left('sub_button_<?=$i?>')"></div>
<div style="float:right;width:450px"><input type="button" value="←" onClick="btn_to_left('sub_button_<?=$i?>')"></div>
<?php } ?>
</td></tr>
<?php
}

function echo_list_td_shop($value) {
	global $line, $no, $id, $fn, $tn, $conn, $list_form_tag, $form_lock, $shop_list;

	if ($form_lock) {
		$idstr = " disabled";
	}
	if ($line==0) echo_list_ini("ssid", "int");
	echo '<td>';
	echo "<select name='".$list_form_tag."list[$line][$no]'$idstr>";
//	echo "<option></option>";
	foreach ($shop_list as $v) {
		if ($v[0] == $value) {
			echo "<option value='$v[0]' selected>$v[1]</option>";
		} else {
			echo "<option value='$v[0]'>$v[1]</option>";
		}
	}
	echo "</select>";
	$no++;
}
?>
</div>
<br clear=all>

<?php
if ($def_ssid==$uid or $def_ssid==-1) {
	if (!$lock) {
		echo_form_frame("金額一括計算");
?>
<tr><th>SC率</th>
<td><input id="sc_rate" type="text" size="5">%
<input type="button" value="計算" onClick="set_sc_all()"></td></tr>

<tr><th>仕切率</th>
<td><input id="invoice_rate" type="text" size="5">%
<input type="button" value="計算" onClick="set_invoice_all()"></td></tr>
<?php
		echo_form_frame_end();
	}

	echo_list_frame("合計金額", "frame_box");
?>
<tr>
<th>部品代</th>
<th>工賃</th>
<th>諸経費</th>
<th>非課税</th>
<th>合計（税抜）</th>
</tr>
<?php
	echo '<tr>';
	echo_html_td(0, "", "cur", $sum[1], "default");
	echo_html_td(0, "", "cur", $sum[2], "default");
	echo_html_td(0, "", "cur", $sum[3], "default");
	echo_html_td(0, "", "cur", $sum[4], "default");
	$total = $sum[0] + $sum[1] + $sum[2] + $sum[3] + $sum[4];
	echo_html_td(0, "", "cur", $total, "default");
	echo '</tr>';
	echo_list_frame_end();
}
?>

</form>

<script language="JavaScript">
function btn_export() {
	location.href="../csv/export_mainte.php?id=<?=$id?>";
}
function btn_import() {
	w = window.open('../csv/import_mainte.php?id=<?=$id?>', 'win','width=500,height=260,status=no,scrollbars=yes,directories=no,menubar=no,resizable=yes,toolbar=no');
}
function set_sc_all() {
	var rate = document.getElementById("sc_rate").value;
	for (i=0; i<=<?=$line?>; i++) {
		var lock = document.getElementById("lock_" + i).value;
		if (lock==1) continue;
		document.getElementById("sc_rate_" + i).value = rate;
		set_sc(rate, i);
	}
}
function set_sc(rate, i) {
	if (rate=="") return;
	var n = document.getElementById("num_" + i).value;
	var p = document.getElementById("price_" + i).value;
	p = p.replace(",", "");
	var a2 = Math.round(n * p * rate / 100);
	document.getElementById("cost1_" + i).value = a2;
}
function set_invoice_all() {
	var rate = document.getElementById("invoice_rate").value;
	for (i=0; i<=<?=$line?>; i++) {
		var lock = document.getElementById("lock_" + i).value;
		if (lock==1) continue;
		document.getElementById("invoice_rate_" + i).value = rate;
		set_invoice(rate, i);
	}
}
function set_invoice(rate, i) {
	if (rate=="") return;
	var n = document.getElementById("num_" + i).value;
	var p = document.getElementById("price_" + i).value;
	p = p.replace(",", "");
	var a2 = Math.round(n * p * rate / 100);
	document.getElementById("cost2_" + i).value = a2;
}
function btn_to_left(id) {
	document.getElementById(id).focus();
}
</script>

<?php require 'footer.php'; ?>
