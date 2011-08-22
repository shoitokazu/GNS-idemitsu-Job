<?php
function return_error($msg="不正なアクセスです。") {
	echo "<p>$msg</p>";
	include "footer.php";
	exit();
}
function debug_print($txt) {
	$filename = 'c:\\debug.txt';
	if (is_writable($filename)) {
		if (!$handle = fopen($filename, 'a')) {
			echo "Cannot open file ($filename)";
			exit;
		}
		if (fwrite($handle, $txt) === FALSE) {
			echo "Cannot write to file ($filename)";
			exit;
		}
		fclose($handle);
	} else {
		echo "The file $filename is not writable";
	}
}
function limitCompany($where, $table, $only=false) {
	if ($where!='') $where .= " and ";
	if ($only) {
		$where .= "$table.company=".$_SESSION['company'];
	} else {
		$where .= "($table.company=".$_SESSION['company']." or $table.company=0)";
	}
	return $where;
}
function limitOffice($where, $table) {
	if ($where!='') $where .= " and ";
	$where .= "$table.company=".$_SESSION['company'];
	$where .= " and $table.office=".$_SESSION['office'];
	return $where;
}
function join_customer($table, $base) {
	return "($table) left join customer on $base.ccode=customer.ccode and $base.company=customer.company";
}
function join_machine($table, $base) {
	return "($table) left join machine on $base.mcode=machine.mcode and $base.company=machine.company";
}
function join_item($table, $base, $only0=false, $name="item1") {
	if ($only0) {
		$query = "select * from item where company=0";
	} else {
		$query = "select * from item where company=0 or company=".$_SESSION['company'];
	}
	return "($table) left join ($query) as $name on $base.icode=$name.icode";
}
function join_data($table, $base_h, $base_d) {
	return "($table) left join $base_d on $base_h.ID=$base_d.hid and $base_h.company=$base_d.company";
}
function join_header($table, $base_h, $base_d) {
	return "($table) left join $base_h on $base_h.ID=$base_d.hid and $base_h.company=$base_d.company";
}
/*
function join_stock($table, $base) {
	return "($table) left join stock on $base.icode=stock.icode and $base.company=stock.company";
}
*/
function join_stock($table, $base) {
	$q0 = "select icode, sum(num) as num from stock where ".limitCompany("", "stock", true)." group by icode";
	$q1 = "select icode, sum(num) as num from mainte_d left join mainte_h on mainte_d.hid=mainte_h.ID where ".limitCompany("item_state=1", "mainte_d", true)." group by icode";
	$q2 = "select icode, sum(num-arrival) as num from order_d left join order_h on order_d.hid=order_h.ID where ".limitCompany("order_state<>0 and complete=0", "order_d", true)." group by icode";
	$table = "($table) left join ($q0) as stock_all on $base.icode=stock_all.icode";
	$table = "($table) left join ($q1) as use_plan on $base.icode=use_plan.icode";
	$table = "($table) left join ($q2) as arrival_plan on $base.icode=arrival_plan.icode";
	return $table;
}
function join_company($table, $base) {
	return "($table) left join environment on $base.company=environment.company";
}
function echo_button_customer($eid) {
	echo "<input type='button' value='選択' onClick='openNormalWindow(\"../customer/search.php?select=".$eid."&ccode=\" + document.getElementById(\"$eid\").value);'>";
}
function echo_button_article($eid) {
	echo "<input type='button' value='選択' onClick='openNormalWindow(\"../article/search.php?select=".$eid."&acode=\" + document.getElementById(\"$eid\").value);'>";
}
function echo_button_machine($meid, $ceid="") {
	echo "<input type='button' value='選択' onClick='openNormalWindow(\"../machine/search.php?select=$meid,$ceid&mcode=\" + document.getElementById(\"$meid\").value";
	if ($ceid<>"") {
		echo " + \"&machine__ccode=\" + document.getElementById(\"$ceid\").value";
	}
	echo ");'>";
}
function echo_button_figure($mode, $id, $model="", $type=1) {
	$arg = "select=$mode,$id&type=$type";
	if ($model!="") $arg .= "&model=$model";
	echo '<input type="button" value="図選択" onClick="openNormalWindow(\'../item/fig_list.php?'.$arg.'\');">';
}
function echo_button_item($mode, $id, $model="", $type=1) {
	$arg = "select=$mode,$id&type=$type";
	if ($model!="") $arg .= "&model=$model";
	echo '<input type="button" value="検索選択" onClick="openNormalWindow(\'../item/search.php?'.$arg.'\');">';
}
function echo_button_edit_choices($field) {
	echo '<input type="button" value="編集" onClick="openNormalWindow(\'../config/list.php?select=on&field='.$field.'\');">';
}
function echo_button_edit_group($field) {
	echo '<input type="button" value="編集" onClick="openNormalWindow(\'../config/list2.php?select=on&field='.$field.'\');">';
}
function echo_select_script($eid) {
?>
<script language="javaScript">
function setSelectValue(v) {
	window.opener.document.getElementById('<?=$eid?>').value = v;
	window.opener.document.form1.submit();
	window.close()
}
</script>
<?php
}
function echo_sky_header($filename) {
//	header("Pragma: no-cache");
//	header("Content-Type: application/octet-stream");
	header("Content-Type: text/csv");
//	header('Content-Type: application/download; name="contents.sky"');
//	header("Content-Type: text/plain");
//	header("Content-Length: " . filesize($filename));
	header('Content-Disposition: attachment; filename="'.$filename.'"');
}
function get_env($field, $company="") {
	global $conn;

	$table = "environment";
	if ($company=="") {
		$where = "company=".$_SESSION['company'];
	} else {
		$where = "company=$company";
	}
	$sql = "select $field from $table where $where";
	$row = db_row($conn, $sql);
	return $row[0];
}
function setCode($table, $id, $field="code") {
	global $conn;

	$where = "ID=$id";
	$sql = "select $field from $table where $where";
	$row = db_row($conn, $sql);
	if ($row[0]=="") {
		$fields[0] = $field;
		$types [0] = "str";
//		$values[0] = date('ymdHis');
		$values[0] = "b2".sprintf("%08s", $id);
		db_update($conn, $table, $fields, $types, $values, $where, true);
		return $values[0];
	}
	return $row[0];
}
function echo_sub_header($buttons_html="", $submit_value="更新", $del_link="", $id_list="") {
	global $select_arg, $select_mode;

	echo '<div class="frame_sub"><table width=90%><tr><td height=25 width=30></td>';
	if ($submit_value!="") {
		echo "<td width=50>";
		echo_form_submit($submit_value);
		echo "</td> ";
	} else {
		echo "<td width=50><br></td> ";
	}
	if ($del_link!="") {
		if (is_lock()) $attr=" disabled";
		echo "<td width=50>";
		echo '<input type="button" value="削除" onClick="if (confirm(\'削除しますか？\')) location.href=\''.$del_link.'\'"'.$attr.'>';
		echo "</td> ";
	} else {
		echo "<td width=50><br></td> ";
	}
	echo '<td valign=center>';
	if ($select_mode) {
//		echo '<input type="button" value="決定" onClick="btn_select()">';
		echo '<input name="decide" type="submit" value="決定">';
	} else {
		echo $buttons_html;
	}
	echo '</td>';

	if ($select_arg<>"") $arg = "&".$select_arg;
	if (is_array($id_list)) {
		echo '<td valign=center align=right><span class="sub_cursor">';
		echo ' '.($id_list['pre']=="" ? "(前へ)" : '<a href="?id='.$id_list['pre'].$arg.'">(前へ)</a>');
		echo ' '.$id_list['no']."/".$id_list['records'].' ';
		echo ' '.($id_list['next']=="" ? "(次へ)" : '<a href="?id='.$id_list['next'].$arg.'">(次へ)</a>');
		echo '</span></td>';
	} else {
		echo '<td><br></td>';
	}
	echo '</tr></table></div>';
}
function cutDecimal($value, $decimals_mode) {
	$v = db_value($value, "cur");
	switch ($decimals_mode) {
	case 0:
		return floor($v);
	case 1:
		return ceil($v);
	case 2:
		return round($v);
	}
}
function getFilePath() {
	global $FILES_PATH;

	return $FILES_PATH."/company".$_SESSION['company']."/";
}
function check_order($id) {
	$state = DLookUp("order_state", "order_h", "ID=$id");
	if ($state==null) return_error("無効な発注伝票です。");
	if ($state[0]!=0) return_error("発注済みなので編集できません。");
}
function echo_company_select($value) {
	global $conn;

	echo "<select name='company'>";
	echo '<option value=""></option>';
	$list = DListUp("ID,name", "company", "", "sort,ID,name");
	foreach ($list as $v) {
		if ($v[0] == $value) {
			echo "<option value='$v[0]' selected>$v[1]</option>";
		} else {
			echo "<option value='$v[0]'>$v[1]</option>";
		}
	}
	echo "</select>";
}
function file_delete($id, $hid, $ftype) {
	global $debug, $conn;

	$table = "file_d";
	$where = "ID=$id and hid=$hid and ftype=$ftype";
	$where = limitCompany($where, $table, true);
	$sql = "select filename from $table where $where";
	$row = db_row($conn, $sql);
	if ($row==false) return false;
	$filepath = getFilePath().$row[0];
	if ($debug) echo "unlink : $filepath<br>";
	unlink($filepath);
	db_delete($conn, $table, $where);
	return true;
}
function is_my_company($rs) {
	global $conn, $company;

	$v = db_result($rs, "company");
	if ($v == $company) return true;
	return false;
}
function fgetcsv_reg (&$handle, $length = null, $d = ',', $e = '"') {
        $d = preg_quote($d);
        $e = preg_quote($e);
        $_line = "";
        while ($eof != true) {
            $_line .= (empty($length) ? fgets($handle) : fgets($handle, $length));
            $itemcnt = preg_match_all('/'.$e.'/', $_line, $dummy);
            if ($itemcnt % 2 == 0) $eof = true;
        }
        $_csv_line = preg_replace('/(?:\r\n|[\r\n])?$/', $d, trim($_line));
        $_csv_pattern = '/('.$e.'[^'.$e.']*(?:'.$e.$e.'[^'.$e.']*)*'.$e.'|[^'.$d.']*)'.$d.'/';
        preg_match_all($_csv_pattern, $_csv_line, $_csv_matches);
        $_csv_data = $_csv_matches[1];
        for($_csv_i=0;$_csv_i<count($_csv_data);$_csv_i++){
            $_csv_data[$_csv_i]=preg_replace('/^'.$e.'(.*)'.$e.'$/s','$1',$_csv_data[$_csv_i]);
            $_csv_data[$_csv_i]=str_replace($e.$e, $e, $_csv_data[$_csv_i]);
        }
        return empty($_line) ? false : $_csv_data;
}
function get_schedule_id($uid, $area, $date, $type="") {
	global $conn, $debug;

	$tmp = $debug;
	$debug = false;
	$base = "schedule";
	$table = $base;
	$table = "($table) left join account on $base.uid=account.ID";
	if ($area=="") {
		$where = "$base.uid=$uid";
	} else {
		$where = "account.company=".db_value($area, "int");
	}
	$where .= " and date=".db_value($date, "date");
	$where .= " and account.auth<>0";
	if ($type<>"") $where .= " and schedule_type=".db_value($type, "int");
	$sql = "select $base.ID from $table where $where";
	$rs = db_exec($conn, $sql);
	while (db_fetch_row($rs)) {
		$list[] = db_result($rs, "ID");
	}
	$table = $base;
	$table = "($table) inner join schedule_date on $base.ID=schedule_date.hid";
	$table = "($table) inner join schedule_user on $base.ID=schedule_user.hid";
	$table = "($table) left join account on schedule_user.uid=account.ID";
	if ($area=="") {
		$where = "schedule_user.uid=$uid";
	} else {
		$where = "account.company=".db_value($area, "int");
	}
	$where .= " and schedule_date.date=".db_value($date, "date");
	$where .= " and account.auth<>0";
	if ($type<>"") $where .= " and schedule_type=".db_value($type, "int");
	$sql = "select $base.ID from $table where $where";
	$rs = db_exec($conn, $sql);
	while (db_fetch_row($rs)) {
		$list[] = db_result($rs, "ID");
	}
	$debug = $tmp;
	return $list;
}
?>