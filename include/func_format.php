<?php
function csv_format($value, $type) {
	switch (strtolower($type)) {
	case "str":
	case "varchar":
	case "string":
	case "blob":
		return '"'.str_replace('"', '""', $value).'"';
	case "date":
		$dt = strToTime($value);
		return date("\"Y/n/j\"", $dt);
	case "time":
		$dt = strToTime($value);
		return date("\"H:i:s\"", $dt);
	case "datetime":
		return '"'.$value.'"';
	case "int":
	case "real":
		return $value;
	}
	return $value;
}
function html_format($value, $type) {
	switch (strtolower($type)) {
	case "str":
	case "varchar":
	case "longchar":
		if ($value=='') return "<br>";
		$v = htmlspecialchars($value);
		$v = nl2br($v);
		return $v;
	case "int":
	case "integer":
	case "counter":
	case "cur":
	case "currency":
		if ($value=='') return "<br>";
		return number_format($value);
	case "date":
		if ($value=='') return "<br>";
		$dt = strToTime($value);
		return date("Y/n/j", $dt);
	case "time":
		if ($value=='') return "<br>";
		$dt = strToTime($value);
		return date("H:i:s", $dt);
	case "datetime":
		if ($value=='') return "<br>";
		$dt = strToTime($value);
		if (date("H:i:s", $dt)=="00:00:00") return date("Y/m/j", $dt);
		return date("Y/n/j H:i:s", $dt);
	case "bool":
		if ($value==0) return "×";
		if ($value==1) return "○";
		return $value;
	case "week":
		switch ($value) {
		case 0:
			return '日';
		case 1:
			return '月';
		case 2:
			return '火';
		case 3:
			return '水';
		case 4:
			return '木';
		case 5:
			return '金';
		case 6:
			return '土';
		}
		return "";
	}
	return $value;
}
function input_format($value, $type) {
	if ($value=='') return '';
	switch (strtolower($type)) {
	case "str":
	case "varchar":
	case "longchar":
		return str_replace('"', '&quot;', $value);
	case "int":
	case "integer":
	case "counter":
	case "cur":
	case "currency":
		return number_format($value);
	case "real":
	case "float":
		return number_format($value, 1);
	case "date":
		$dt = strToTime($value);
		return date("Y/n/j", $dt);
	case "time":
		$dt = strToTime($value);
		return date("H:i:s", $dt);
	case "datetime":
		$dt = strToTime($value);
		if (date("H:i:s", $dt)=="00:00:00") return date("Y/m/j", $dt);
		return date("Y/n/j H:i:s", $dt);
	}
	return $value;
}
function echo_list_value($value, $type, $attribute) {
	switch ($type) {
	case str:
		$size = 15;
		break;
	case date:
		$size = 12;
		break;
	case int:
		$size = 5;
		break;
	}
	if ($size<>"") $attribute .= ' size='.$size;
	echo '<input type="text" value="'.input_format($value, $type).'"'.$attribute.' readonly>';
}
function echo_report($value, $type) {
	echo html_format($value, $type);
}
function echo_input_text($name, $class, $size, $field, $type, $value, $attribute) {
	global $rn;
	echo "<input type='text' class='$class' name='$name' value='".input_format($value, $type)."' size=$size $attribute>$rn";
}
function echo_form_ini($field, $type) {
	global $form_no, $rn;

	$no = $form_no;
	echo "<input type='hidden' name='fields[$no]' value='$field'>$rn";
	echo "<input type='hidden' name='types[$no]' value='$type'>$rn";
	$form_no++;
	return $no;
}
function echo_form_input($field, $type, $value, $attribute="") {
	global $form_no, $rn, $form_lock, $debug, $select_mode;

	$idstr = " id='$field'";
	if ($attribute<>"") $idstr .= " ".$attribute;
	if ($form_lock) {
		$idstr .= " disabled";
	}
	switch ($type) {
	default:
		$no = echo_form_ini($field, $type);
		break;
	case 'hidden':
	case 'hidstr':
	case 'code':
	case 'name':
	case 'ccode':
	case 'mcode':
	case 'acode':
	case 'staff':
	case 'blob':
	case 'choice':
	case 'select1':
	case 'table1':
	case 'txt':
	case 'varchar':
	case 'schar':
	case 'model':
	case 'zip':
	case 'wcode':
		$no = echo_form_ini($field, "str");
		break;
	case 'hidint':
	case 'radio2':
	case 'select2':
	case 'percent':
	case 'mm':
	case 'hour':
		$no = echo_form_ini($field, "int");
		break;
	case 'hidate':
		$no = echo_form_ini($field, "date");
		break;
	case 'timestamp':
	case 'file':
		$no = $form_no;
//		$form_no++;
		break;
	}
	switch ($type) {
	default:
		echo "<input type='text' name='values[$no]' value='$value' size=100$idstr>$rn";
		break;
	case 'int':
		echo "<input class='num' type='text' name='values[$no]' value='".input_format($value, $type)."' size=3$idstr>$rn";
		break;
	case 'real':
	case 'float':
	case 'double':
	case 'cur':
		echo "<input class='num' type='text' name='values[$no]' value='".input_format($value, $type)."' size=10$idstr>$rn";
		break;
	case 'percent':
		echo "<input class='num' type='text' name='values[$no]' value='".input_format($value, "int")."' size=5$idstr> %$rn";
		break;
	case 'mm':
		echo "<input class='num' type='text' name='values[$no]' value='".input_format($value, "float")."' size=5$idstr> mm$rn";
		break;
	case 'hour':
		echo "<input class='num' type='text' name='values[$no]' value='".input_format($value, "int")."' size=5$idstr> h$rn";
		break;
	case 'short':
		echo "<input class='num' type='text' name='values[$no]' value='$value' size=15$idstr>$rn";
		break;
	case 'str':
	case 'string':
		echo "<input class='str' type='text' name='values[$no]' value='$value' size=40$idstr>$rn";
		break;
	case 'varchar':
		echo "<input class='str' type='text' name='values[$no]' value='$value' size=60$idstr>$rn";
		break;
	case 'schar':
		echo "<input class='str' type='text' name='values[$no]' value='$value' size=10$idstr>$rn";
		break;
	case 'name':
		echo "<input class='str' type='text' name='values[$no]' value='$value' size=40$idstr>$rn";
		break;
	case 'code':
		echo "<input class='chr' type='text' name='values[$no]' value='$value' size=40$idstr>$rn";
		break;
	case 'ccode':
		echo "<input class='chr' type='text' name='values[$no]' value='$value' size=15$idstr>$rn";
//		if (!$select_mode) echo '<a href="../customer/form.php?code='.urlencode($value).'">詳細</a>';
		if (!$form_lock) echo " <input type='button' value='選択' onClick='openNormalWindow(\"../customer/search.php?select=$field,getCustomerValue()\");'>";
		break;
	case 'acode':
		echo "<input class='chr' type='text' name='values[$no]' value='$value' size=15$idstr>$rn";
//		if (!$select_mode) echo '<a href="../article/form.php?code='.urlencode($value).'">詳細</a>';
		if (!$form_lock) echo " <input type='button' value='選択' onClick='openNormalWindow(\"../article/search.php?select=$field,getArticleValue()\");'>";
		break;
	case 'model':
		echo "<input class='chr' type='text' name='values[$no]' value='$value' size=15$idstr>$rn";
		if (!$form_lock) echo "<input type='button' value='選択' onClick='openSelectWindow(\"../select/model_list.php?target=$field\")'>";
		break;
	case 'wcode':
		echo "<input class='int' type='text' name='values[$no]' value='$value' size=15$idstr>$rn";
//		if (!$select_mode) echo '<a href="../work/form1.php?code='.urlencode($value).'">詳細</a>';
		if (!$form_lock) echo " <input type='button' value='選択' onClick='openNormalWindow(\"../work/search.php?select=$field,getWorkValue()\");'>";
		break;
	case 'staff':
		echo "<input class='chr' type='text' name='values[$no]' value='$value' size=15$idstr>$rn";
		if (!$form_lock) echo "<input type='button' value='選択' onClick='openSelectWindow(\"../select/staff_list.php?target=$field\")'>";
		break;
	case 'txt':
		echo "<textarea name='values[$no]' cols=30 rows=3$idstr>$value</textarea>$rn";
		break;
	case 'blob':
		echo "<textarea name='values[$no]' cols=50 rows=7$idstr>$value</textarea>$rn";
		break;
	case 'timestamp':
		echo "$value$rn";
		return;
	case 'date':
		echo "<input class='date' type='text' name='values[$no]' value='".input_format($value, "date")."' size=12$idstr>$rn";
		if (!$form_lock) echo_common_calender($field);
		echo "$rn";
		break;
	case 'time':
		echo "<input type='hidden' name='values[$no]' value='".input_format($value, "time")."'$idstr>$rn";
		echo "<select name='time1_$no' onChange='this.form.$field.value=this.form.time1_$no.value+\":\"+this.form.time2_$no.value'>$rn";
		$sel = "";
		if ($value=="") $sel = " selected";
		echo '<option value=""'.$sel.'></option>';
		for ($i=0; $i<24; $i++) {
			$h = 8 + $i;
			if ($h>24) $h-=24;
			$sel = "";
			if ($h==substr($value, 0, 2)) $sel = " selected";
			echo '<option value="'.$h.'"'.$sel.'>'.sprintf("%02d", $h).'</option>';
		}
		echo '</select>'.$rn;
		echo ':';
		echo "<select name='time2_$no' onChange='this.form.$field.value=this.form.time1_$no.value+\":\"+this.form.time2_$no.value'>$rn";
		$sel = "";
		if ($value=="") $sel = " selected";
		echo '<option value="00"'.$sel.'></option>';
		$start_minute = 0;
		for ($i=0; $i<60; $i+=10) {
			$m = 0 + $i;
			$sel = "";
			$ms = substr($value, 3, 2);
			if ($ms>=$m and $ms<$m+10 and $ms<>"") $sel = " selected";
			echo '<option value="'.$m.'"'.$sel.'>'.sprintf("%02d", $m).'</option>';
		}
		echo '</select>';
		echo "$rn";
		break;
	case 'bool':
		echo "<input type='checkbox' name='values[$no]' value='1'".($value==1 ? ' checked' : '')."$idstr>$rn";
		break;
	case 'radio1':
		$list = DListUp("value", "choices", "field='$field'", "sort,value");
		if (is_array($list)) {
			foreach ($list as $v) {
				if ($v[0] == $value) {
					echo "<input type='radio' name='values[$no]' value='$v[0]' checked$idstr>$v[0]$rn";
				} else {
					echo "<input type='radio' name='values[$no]' value='$v[0]'$idstr>$v[0]$rn";
				}
			}
		}
		break;
	case 'radio2':
		$list = DListUp("value,name", "choices", "field='$field'", "sort,value,name");
		if (is_array($list)) {
			foreach ($list as $v) {
				if ($v[0] == $value) {
					echo "<input type='radio' name='values[$no]' value='$v[0]' checked$idstr>$v[1]$rn";
				} else {
					echo "<input type='radio' name='values[$no]' value='$v[0]'$idstr>$v[1]$rn";
				}
			}
		}
		break;
	case 'pass':
		echo "<input type='password' name='values[$no]' value='$value' size=50$idstr>$rn";
		break;
	case 'hidden':
	case 'hidstr':
	case 'hidint':
	case 'hidate':
		echo "<input type='hidden' name='values[$no]' value='$value'$idstr>$rn";
		break;
	case 'choice':
		echo "<input class='str' type='text' name='values[$no]' value='$value' size=20$idstr>$rn";
		if (!$form_lock) echo "<input type='button' value='選択' onClick='openSelectWindow(\"../select/select_list.php?target=$field\")'>";
		break;
	case 'select1':
		$list = DListUp("value", "choices", "field='$field'", "sort,value");
		if (is_array($list)) {
			echo "<select name='values[$no]'$idstr>";
			foreach ($list as $v) {
				if ($v[0] == $value) {
					echo "<option value='$v[0]' selected>$v[0]</option>";
				} else {
					echo "<option value='$v[0]'>$v[0]</option>";
				}
			}
			echo "</select>";
		} else {
			echo "<select name='values[$no]'$idstr disabled>";
			echo "</select>";
		}
		break;
	case 'select2':
		echo "<select name='values[$no]'$idstr>";
		$list = DListUp("value,name", "choices", "field='$field'", "sort,value,name");
		if (is_array($list)) {
			foreach ($list as $v) {
				if ($v[0] == $value) {
					echo "<option value='$v[0]' selected>$v[1]</option>";
				} else {
					echo "<option value='$v[0]'>$v[1]</option>";
				}
			}
		}
		echo "</select>";
		break;
	case 'table':
		echo "<select name='values[$no]'$idstr>";
		$list = DListUp("ID,name", "$field", "", "sort,name");
		if (is_array($list)) {
			foreach ($list as $v) {
				if ($v[0] == $value) {
					echo "<option value='$v[0]' selected>$v[1]</option>";
				} else {
					echo "<option value='$v[0]'>$v[1]</option>";
				}
			}
		}
		echo "</select>";
		break;
	case 'table1':
		echo "<select name='values[$no]'$idstr>";
		$list = DListUp("name", "$field", "", "sort,name");
		if (is_array($list)) {
			foreach ($list as $v) {
				if ($v[0] == $value) {
					echo "<option value='$v[0]' selected>$v[0]</option>";
				} else {
					echo "<option value='$v[0]'>$v[0]</option>";
				}
			}
		}
		echo "</select>";
		break;
	case 'account':
		echo "<select name='values[$no]'$idstr>";
		$list = DListUp("ID,name", "account", "auth<>0", "sort,ID");
		if (is_array($list)) {
			foreach ($list as $v) {
				if ($v[0] == $value) {
					echo "<option value='$v[0]' selected>$v[1]</option>";
				} else {
					echo "<option value='$v[0]'>$v[1]</option>";
				}
			}
		}
		echo "</select>";
		break;
	case 'file':
		echo '<input name="userfile" type="file" size="50"'.$idstr.'>';
		break;
	case 'zip':
		echo "<input class='chr' type='text' name='values[$no]' value='$value' size=10$idstr>$rn";
		echo '<a href="http://www.post.japanpost.jp/zipcode/index.html" target="_blank">郵便番号検索</a>'.$rn;
		break;
	}
}
function echo_form_select($rs, $field, $type, $default="", $attribute="") {
	global $form_no, $rn;

	$no = $form_no;
	if ($attibute<>"") $idstr=" ".$attribute;
	echo_form_ini($field, $type);
	echo "<select name='values[$no]'$idstr>";
	$list = DListkUp("value", "choices", "field='$field'", "sort");
	echo "<option></option>";
	foreach ($list as $v) {
		if ($v[0] == $default) {
			echo "<option selected>$v[0]</option>";
		} else {
			echo "<option>$v[0]</option>";
		}
	}
	echo "</select>";
	$form_no++;
}
function echo_form_db($rs, $field, $type, $default="", $attribute="") {
	$value = db_result($rs, $field);
	if ($value=="") $value=$default;
	echo_form_input($field, $type, $value, $attribute);
	return $value;
}
function echo_form_tr($rs, $name, $field, $type, $default="", $footer=true) {
	global $rn, $form_lock, $id;

	switch ($type) {
	default:
		echo "<tr><th nowrap>$name</th>";
		break;
/*
	case "wcode":
		$wcode = db_result($rs, $field);
		$id = DLookUp("ID", "work_h", "wcode=".db_value($wcode, "str"));
		echo "<tr><th><a href='../work/form1.php?id=$id'>$name</a></th>";
		break;
*/
	}

	$r = echo_form_td($rs, $field, $type, $default, $footer);

	if ($footer) echo_form_tr_end();
	return $r;
}
function echo_form_tr_end() {
	global $rn;
//	echo "</tr></table></div>$rn";
	echo "</tr>$rn";
}
function echo_form_td($rs, $field, $type, $default="", $footer=true) {
	global $rn, $form_lock;

	echo '<td>';
//	$eid = $field;
//	$attribute = "id='$eid'";
	switch ($type) {
	default:
		$r = echo_form_db($rs, $field, $type, $default, $attribute);
		break;
/*
	case 'date':
		$r = echo_form_db($rs, $field, $type, $default, $attribute);
		break;
	case 'estimate':
		$r = echo_form_db($rs, $field, $type, $default, $attribute);
		break;
	case 'choice':
		$r = echo_form_db($rs, $field, $type, $default, $attribute);
		if (!$form_lock) echo_button_edit_choices($field);
		break;
*/
	}
	if ($footer) echo_form_td_end();
	return $r;
}
function echo_form_td_end() {
	echo "</td>";
}
function echo_form_frame($title="", $frame="frame_box") {
	if ($frame<>"") echo "<div class='$frame'>";
?>
<table cellspacing=0 cellpading=0>
<tr>
<td height=23 width=8 bgcolor=#cccc00 style="border:1px solid black"><br></td>
<td bgcolor=silver style="border-top:5px double black;border-bottom:5px double black">&nbsp;&nbsp;<?=$title?>&nbsp;</td>
</tr>
<tr><td bgcolor="#333333">&nbsp;</td><td bgcolor="#bbbbbb">
<div class="frame_form">
<table>
<?php
}
function echo_form_frame_end($title="", $frame="frame_box") {
?>
</table>
</div>
</td></tr>
</table>
<?php
	if ($frame<>"") echo "</div>";
}
function echo_html_format($value, $type) {
	global $html_charset;

	switch ($type) {
	default:
		echo html_format($value, $type);
		break;
	case 'percent':
		echo number_format($value, 1)."%";
		break;
	case 'str10':
		echo html_format(mb_substr($value, 0, 10, $html_charset), "str");
		break;
	case 'str15':
		echo html_format(mb_substr($value, 0, 15, $html_charset), "str");
		break;
	}
}
function echo_html_db($rs, $field, $type) {
	$value = db_result($rs, $field);
	switch ($type) {
	default:
		echo_html_format($value, $type);
		break;
	case "select2":
		global $debug;
		$tmp = $debug;
		$debug = false;
		$v = DLookUp("name", "choices", "field='$field' and value='$value'");
		echo html_format($v, "str");
		$debug = $tmp;
		break;
	case "table":
		global $debug;
		$tmp = $debug;
		$debug = false;
		$v = DLookUp("name", "$field", "ID=$value");
		echo html_format($v, "str");
		$debug = $tmp;
		break;
	case "account":
		global $debug;
		$tmp = $debug;
		$debug = false;
		$v = DLookUp("name", "account", "ID=$value");
		echo html_format($v, "str");
		$debug = $tmp;
		break;
	}
	return $value;
}
function echo_html_tr($rs, $name, $field, $type, $default="", $mode="echo") {
	echo "<tr><th>$name</th>";
	$r = echo_html_td($rs, $field, $type, $default, $mode);
	echo "</tr>";
	return $r;
}
function echo_html_td($rs, $field, $type, $default="", $mode="echo") {
	global $rn;

	switch ($type) {
	case "int":
	case "cur":
	case "percent":
		echo "<td align=right>";
		break;
	case "mid":
		echo "<td align=center>";
		$type = "int";
		break;
	default:
		echo "<td nowrap>";
		break;
	}
	switch ($mode) {
	default:
		$r = echo_html_db($rs, $field, $type);
		break;
	case "null":	//DB非参照
	case "default":	//デフォルト値表示
		echo_html_format($default, $type);
		$r = $default;
		break;
	}
	echo "</td>$rn";
	return $r;
}
function echo_form_submit($value="更新") {
	global $form_lock;

	if ($form_lock) {
		echo '<input type="submit" value="'.$value.'" disabled>';
	} else {
		echo '<input type="submit" value="'.$value.'">';
	}
}
function init_form_format($rs, $field="ID", $type="int") {
	global $form_no;

	if (!db_is_fetch($rs)) db_fetch_row($rs);
	$form_no=0;
	echo "<input type='hidden' name='id' value='".db_result($rs, $field)."'>";
	if ($field<>"ID") echo "<input type='hidden' name='idField' value='$field'>";
	if ($type<>"int") echo "<input type='hidden' name='idType' value='$type'>";
}
function lock_form($lock=true) {
	global $form_lock;

	$form_lock = $lock;
}
function is_lock() {
	global $form_lock;

	return $form_lock;
}
function get_form_field_no() {
	global $form_no;
	return $form_no;
}
function echo_common_select($field, $table, $element_id) {
	echo "<input type='button' value='選択' onclick='openSelectWindow(\"../select/select_list.php?table=$table&field=$field&target=".$element_id."\");'>";
}
function echo_common_calender($element_id) {
	echo '<input type="button" value="選択" onclick="wrtCalendar(this.form.'.$element_id.')">';
}
function echo_list_ini($field, $type) {
	global $no, $rn, $list_form_tag;

	switch ($type) {
	case "code":
	case "name":
	case "varchar":
	case "txt":
	case "choice":
	case "select1":
		$type = "str";
		break;
	case "cur":
	case "select2":
		$type = "int";
		break;
	}
	echo "<input type='hidden' name='".$list_form_tag."listFields[$no]' value='$field'>$rn";
	echo "<input type='hidden' name='".$list_form_tag."listTypes[$no]' value='$type'>$rn";
}
function echo_list_input($field, $type, $value="", $attribute="") {
	global $no, $line;
	global $rn;
	global $form_lock, $list_form_tag;

	$idstr = " id=".$field."_".$line;
	if ($attribute!="") $idstr .= " ".$attribute;
	if ($form_lock) {
		$idstr .= " disabled";
	}
	switch ($type) {
	default:
		echo "<input type='text' name='".$list_form_tag."list[$line][$no]' value='$value' size=10 $idstr>$rn";
		break;
	case 'int':
	case 'real':
		echo "<input class='num' type='text' name='".$list_form_tag."list[$line][$no]' value='".input_format($value, $type)."' size=5 $idstr>$rn";
		break;
	case 'percent':
		echo "<input class='num' type='text' name='".$list_form_tag."list[$line][$no]' value='".input_format($value, $type)."' size=5 $idstr>%$rn";
		break;
	case 'cur':
		echo "<input class='num' type='text' name='".$list_form_tag."list[$line][$no]' value='".input_format($value, $type)."' size=10 $idstr>$rn";
		break;
	case 'str':
		echo "<input class='str' type='text' name='".$list_form_tag."list[$line][$no]' value='".input_format($value, $type)."' size=10 $idstr>$rn";
		break;
	case 'varchar':
		echo "<input class='str' type='text' name='".$list_form_tag."list[$line][$no]' value='".input_format($value, "str")."' size=20 $idstr>$rn";
		break;
	case 'txt':
		echo "<textarea name='".$list_form_tag."list[$line][$no]' cols=30 rows=3$idstr>$value</textarea>$rn";
		break;
	case 'sort':
		echo "<input class='num' type='text' name='".$list_form_tag."list[$line][$no]' value='".input_format($value, "str")."' size=5 $idstr>$rn";
		break;
	case 'code':
		echo "<input class='chr' type='text' name='".$list_form_tag."list[$line][$no]' value='".input_format($value, "str")."' size=15 $idstr>$rn";
		break;
	case 'name':
		echo "<input class='str' type='text' name='".$list_form_tag."list[$line][$no]' value='".input_format($value, "str")."' size=25 $idstr>$rn";
		break;
	case 'date':
		echo "<input class='str' type='text' name='".$list_form_tag."list[$line][$no]' value='".input_format($value, $type)."' size=12 $idstr>$rn";
		if (!$form_lock) echo_common_calender($field."_".$line);
		break;
	case 'hidden':
	case 'hidstr':
	case 'hidint':
	case 'hidate':
		echo "<input type='hidden' name='".$list_form_tag."list[$line][$no]' value='".input_format($value, $type)."' size=10 $idstr>$rn";
		break;
	case 'checkbox':
	case 'bool':
		echo "<input type='checkbox' name='".$list_form_tag."list[$line][$no]' value='1'".($value==1 ? ' checked' : '')."$idstr>$rn";
		break;
	case 'choice':
		echo "<select name='".$list_form_tag."list[$line][$no]'$idstr>";
		$list = DListUp("value", "choices", "field='$field'", "sort");
		echo "<option value='$value'>$value</option>";
		echo "<option value=''>---</option>";
		foreach ($list as $v) {
			if ($v[0] == $value) {
				echo "<option value='$v[0]' selected>$v[0]</option>";
			} else {
				echo "<option value='$v[0]'>$v[0]</option>";
			}
		}
		echo "</select>";
		break;
	case 'select1':
		echo "<select name='".$list_form_tag."list[$line][$no]'$idstr>";
		$list = DListUp("value", "choices", "field='$field'", "sort");
		foreach ($list as $v) {
			if ($v[0] == $value) {
				echo "<option value='$v[0]' selected>$v[0]</option>";
			} else {
				echo "<option value='$v[0]'>$v[0]</option>";
			}
		}
		echo "</select>";
		break;
	case 'select2':
		echo "<select name='".$list_form_tag."list[$line][$no]'$idstr>";
		$list = DListUp("value,name,sort", "choices", "field='$field'", "sort");
		foreach ($list as $v) {
			if ($v[0] == $value) {
				echo "<option value='$v[0]' selected>$v[1]</option>";
			} else {
				echo "<option value='$v[0]'>$v[1]</option>";
			}
		}
		echo "</select>";
		break;
	case 'table':
		echo "<select name='".$list_form_tag."list[$line][$no]'$idstr>";
		$list = DListUp("ID,name", "$field", "", "sort,$field,name");
		foreach ($list as $v) {
			if ($v[0] == $value) {
				echo "<option value='$v[0]' selected>$v[1]</option>";
			} else {
				echo "<option value='$v[0]'>$v[1]</option>";
			}
		}
		echo "</select>";
		break;
	}
	if ((string)$no<>'') $no++;
}
function echo_list_delete() {
	global $line, $rn, $form_lock;

	if ($form_lock) {
		echo "<input type='checkbox' name='listDelete[$line]' value='1' disabled>$rn";
	} else {
		echo "<input type='checkbox' name='listDelete[$line]' value='1'>$rn";
	}
}
function echo_list_td_delete() {
	echo '<td>';
	echo_list_delete();
	echo '</td>';
}
function echo_list_db($rs, $field, $type, $default="", $attribute="", $mode="input") {
	global $line;

	switch ($mode) {
	case "null":
	case "default":
	case "delete":
	case "tmp":
	case "through":
		$value="";
		break;
	default:
		$value = db_result($rs, $field);
		break;
	}
	if ($value=="") $value=$default;
	if ($line==0) echo_list_ini($field, $type);
	switch ($mode) {
	case "echo":	//編集不可
	case "default":	//デフォルト値引渡し
		echo html_format($value, $type);
		echo_list_input($field, "hidden", $value, $attribute);
		break;
	case "output":	//出力のみ
	case "through":	//デフォルト値表示
		echo_list_value($value, $type, $attribute);
		break;
	case "hidden":	//非表示
	case "null":	//DB非参照＆非表示
		echo_list_input($field, "hidden", $value, $attribute);
		break;
	case "delete":	//削除ボタン
		echo_list_delete();
		break;
	case "input":	//DB内容編集
	case "tmp":	//一時入力用
	default:
		echo_list_input($field, $type, $value, $attribute);
		break;
	}
	return $value;
}
function echo_list_td($rs, $field, $type, $default="", $mode="input", $footer="</td>") {
	global $no, $line;

	switch ($type) {
	case "int":
	case "cur":
		echo "<td align=right>";
		break;
	case "mid":
		echo "<td align=center>";
		$type = "str";
		break;
	default:
		echo "<td>";
		break;
	}
	$eid = $field."_".$line;
//	$attribute = "id='$eid'";
	$r = echo_list_db($rs, $field, $type, $default, $attribute, $mode);
	echo $footer;
	return $r;
}
function echo_list_frame($title="", $frame="frame_line") {
	if ($frame<>"") echo "<div class='$frame'>";
?>
<table cellspacing=0 cellpading=0 border=0>
<tr>
<td height=23 width=8 bgcolor=#cccc00 style="border:1px solid black;"><br></td>
<td bgcolor=silver style="border-top:5px double black;border-bottom:5px double black">&nbsp;&nbsp;<?=$title?>&nbsp;</td>
</tr>
<tr><td bgcolor="#333333">&nbsp;</td><td colspan=2 bgcolor="#cccccc">
<div class="frame_list">
<table>
<?php
}
function echo_list_frame_end($title="", $frame="frame_line") {
?>
</table>
</div>
</td></tr>
</table>
<?php
	if ($frame<>"") echo "</div>";
}
function init_list_format($tag="", $field="ID", $type="int") {
	global $line, $is_first_line, $rn, $list_form_tag;
	$line=0;
	$is_first_line=true;
	$list_form_tag = $tag;
	echo '<input type="hidden" name="'.$tag.'keyField" value="'.$field.'">'.$rn;
	echo '<input type="hidden" name="'.$tag.'keyType" value="'.$type.'">'.$rn;
}
function init_list_line($rs, $keyField="ID", $keyType="int") {
	global $no, $line, $is_first_line, $list_form_tag;
	$no=0;
	if ($is_first_line) {
		$is_first_line=false;
	} else {
		$line++;
	}
	if ($rs==null) {
		$keyValue = "";
	} else {
		$keyValue = db_result($rs, $keyField);
	}
	echo '<input type="hidden" name="'.$list_form_tag.'keys['.$line.']" value="'.$keyValue.'">';
	return $keyValue;
}
function get_line_no() {
	global $line;
	return $line;
}
function get_list_field_no() {
	global $no;
	return $no;
}
function update_form($table) {
	global $conn;

	$id = $_REQUEST['id'];
	$fields = $_REQUEST['fields'];
	$types = $_REQUEST['types'];
	$values = $_REQUEST['values'];
	$keyField = $_REQUEST['idField'];
	if ($keyField=="") $keyField = "ID";
	$keyType = $_REQUEST['idType'];
	if ($keyType=="") $keyType = "int";
	if (!is_array($fields)) return $id;
	if ($id=='') {
		$id = db_insert($conn, $table, $fields, $types, $values);
	} else {
		$where = $keyField."=".db_value($id, $keyType);
		db_update($conn, $table, $fields, $types, $values, $where);
	}
	return $id;
}
function read_form_request() {
	global $id, $fields, $types, $values, $fno;

	$id = $_REQUEST['id'];
	$fields = $_REQUEST['fields'];
	$types = $_REQUEST['types'];
	$values = $_REQUEST['values'];
	if (is_array($fields)) $fno = array_flip($fields);
	return $id;
}
function update_list($table, $tag="") {
	global $conn;

	$fields = $_REQUEST[$tag.'listFields'];
	$types = $_REQUEST[$tag.'listTypes'];
	$keys = $_REQUEST[$tag.'keys'];
	$list = $_REQUEST[$tag.'list'];
	$dels = $_REQUEST[$tag.'listDelete'];
	$keyField = $_REQUEST[$tag.'keyField'];
	$keyType = $_REQUEST[$tag.'keyType'];
	if (!is_array($list)) return;
	foreach ($list as $key => $values) {
		$id = $keys[$key];
		if ($id=='') {
			$id = db_insert($conn, $table, $fields, $types, $values);
		} else {
			$where = "$keyField=".db_value($id, $keyType);
			if ($dels[$key]=='1') {
				db_delete($conn, $table, $where);
			} else {
				db_update($conn, $table, $fields, $types, $values, $where);
			}
		}
	}
}
function read_list_request($tag="") {
	global $fields, $types, $keys, $list, $dels, $keyField, $keyType, $fno;

	$fields = $_REQUEST[$tag.'listFields'];
	$types = $_REQUEST[$tag.'listTypes'];
	$keys = $_REQUEST[$tag.'keys'];
	$list = $_REQUEST[$tag.'list'];
	$dels = $_REQUEST[$tag.'listDelete'];
	$keyField = $_REQUEST[$tag.'keyField'];
	$keyType = $_REQUEST[$tag.'keyType'];
	if (is_array($fields)) $fno = array_flip($fields);
}
function echo_search_input($field, $type="str", $default="") {
	global $clear_js, $rn;

	$req_field = str_replace('.', '__', $field);
	$pos = strrpos($field, ".");
	if ($pos!==false) {
		$field = substr($field, $pos+1);
	}
	$f1 = $req_field;
	if (isset($_REQUEST[$f1])) {
		$v1 = $_REQUEST[$f1];
	} elseif (isset($_SESSION[get_page_tag()][$f1])) {
		$v1 = $_SESSION[get_page_tag()][$f1];
	} else {
		$v1=$default;
	}
	switch ($type) {
	default:
		$clear_js .= "document.getElementById('$f1').value='';$rn";
		break;
	case "estimate":
	case "radio":
		$clear_js .= "document.getElementById('".$f1."_default').checked = true;$rn";
		break;
	case 'select1':
	case 'select2':
		$clear_js .= "document.getElementById('$f1').options[0].selected = true;$rn";
		break;
	case 'bool':
		if ($default==="") $op_no = 2;
		if ($default==="0") $op_no = 1;
		if ($default==="1") $op_no = 0;
		$clear_js .= "document.getElementById('$f1').options[$op_no].selected = true;$rn";
		break;
	case "checkbox":
		$clear_js .= "document.getElementById('".$f1."_ctrl').checked = false;$rn";
		$clear_js .= "document.getElementById('$f1').value='';$rn";
		break;
	case "date":
		$clear_js .= "document.getElementById('$f1').value='';$rn";
		$f2 = $req_field.'_2';
		if (isset($_REQUEST[$f2])) {
			$v2 = $_REQUEST[$f2];
		} elseif (isset($_SESSION[get_page_tag()][$f2])) {
			$v2 = $_SESSION[get_page_tag()][$f2];
		} else {
			$v2=$default;
		}
		$clear_js .= "document.getElementById('$f2').value='';$rn";

		$f0 = $req_field.'_0';
		$v0 = 0;
		if (isset($_REQUEST[$f0])) {
			$v0 = $_REQUEST[$f0];
		} elseif (isset($_SESSION[get_page_tag()][$f0])) {
			$v0 = $_SESSION[get_page_tag()][$f0];
		}
		$clear_js .= "document.getElementById('$f0').checked=false;$rn";
		break;
	case "month":
		$clear_js .= "document.getElementById('$f1').value='$default';$rn";

		$f0 = $req_field.'_0';
		$v0 = 0;
		if (isset($_REQUEST[$f0])) {
			$v0 = $_REQUEST[$f0];
		} elseif (isset($_SESSION[get_page_tag()][$f0])) {
			$v0 = $_SESSION[get_page_tag()][$f0];
		}
		$clear_js .= "document.getElementById('$f0').checked=false;$rn";
		break;
	case "default":
	case "hidden":
		break;
	}
	switch ($type) {
	default:
		echo "<input type='text' name='$f1' value='$v1' id='$f1'>";
		break;
	case "hidden":
		echo "<input type='hidden' name='$f1' value='$v1' id='$f1'>";
		break;
	case "default":
		echo "<input type='hidden' name='$f1' value='$default' id='$f1'>";
		break;
	case "str":
		echo "<input class='str' type='text' name='$f1' value='$v1' id='$f1'>";
		break;
	case "int":
		echo "<input class='num' type='text' name='$f1' value='$v1' id='$f1'>";
		break;
	case "customer":
		echo "<input type='text' name='$f1' value='$v1' id='$f1'>";
		echo " <input type='button' value='選択' onClick='openNormalWindow(\"../customer/search.php?select=$f1\");'>";
		break;
	case "staff":
		echo "<input type='text' name='$f1' value='$v1' id='$f1'>";
		echo "<input type='button' value='選択' onClick='openSelectWindow(\"../select/staff_list.php?target=$f1\")'>";
		break;
	case "date":
		echo '<input class="date" type="text" name="'.$f1.'" value="'.$v1.'" id="'.$f1.'">';
		echo_common_calender($f1);
		echo ' ～ ';
		echo '<input class="date" type="text" name="'.$f2.'" value="'.$v2.'" id="'.$f2.'">';
		echo_common_calender($f2);
		echo ' <input type="checkbox" name="'.$f0.'" value=1'.($v0==1 ? " checked" : "").' id="'.$f0.'">未記入';
		break;
	case 'radio':
		$list = DListUp("value,name", "choices", "field='$field'", "sort");
		$first_check = false;
		if ($v1=="") $first_check = true;
		foreach ($list as $v) {
			$default_str = ($v[0] == $v1 ? " checked" : "");
			if ($first_check) {
				$default_str = " checked";
				$first_check = false;
			}
			if ($v[0]==$default) {
				$default_str .= " id='".$f1."_default'";
			}
			echo "<input type='radio' name='$f1' value='$v[0]'$default_str>$v[1]$rn";
		}
		break;
	case 'select1':
		echo '<select name="'.$f1.'" id="'.$f1.'">';
		echo '<option value=""></option>';
		$list = DListUp("value", "choices", "field='$field'", "sort");
		foreach ($list as $v) {
			if ($v[0]=="") continue;
			if ($v[0] == $v1) {
				echo "<option selected>$v[0]</option>";
			} else {
				echo "<option>$v[0]</option>";
			}
		}
		echo "</select>";
		break;
	case 'select2':
		echo '<select name="'.$f1.'" id="'.$f1.'">';
		echo '<option value=""></option>';
		$list = DListUp("value,name", "choices", "field='$field'", "sort");
		foreach ($list as $v) {
			if ($v[0] == $v1) {
				echo "<option value='$v[0]' selected>$v[1]</option>";
			} else {
				echo "<option value='$v[0]'>$v[1]</option>";
			}
		}
		echo "</select>";
		break;
	case 'checkbox':
		echo "<input type='checkbox' id='".$f1."_ctrl' value=1".($v1==1 ? " checked" : "")." onClick='if (this.checked) { document.getElementById(\"$f1\").value=1 } else { document.getElementById(\"$f1\").value=\"\" }'>$rn";
		echo "<input type='hidden' name='$f1' value='$v1' id='$f1'>$rn";
		break;
	case 'bool':
		echo '<select name="'.$f1.'" id="'.$f1.'">';
		$list[0][0] = "1";
		$list[0][1] = "○";
		$list[1][0] = "0";
		$list[1][1] = "×";
		$list[2][0] = "";
		$list[2][1] = "－";
		foreach ($list as $v) {
			if ($v[0] === $v1) {
				echo "<option value='$v[0]' selected id='".$f1."_default'>$v[1]</option>";
			} else {
				echo "<option value='$v[0]'>$v[1]</option>";
			}
		}
		echo "</select>";
		break;
	case 'group':
		echo '<select name="'.$f1.'" id="'.$f1.'">';
		echo '<option value=""></option>';
		$list = DListUp("value,name", "choices", "field='$field'", "sort");
		foreach ($list as $v) {
			if ($v[0] == $v1) {
				echo "<option value='$v[0]' selected>$v[1]</option>";
			} else {
				echo "<option value='$v[0]'>$v[1]</option>";
			}
		}
		echo "</select>";
		echo '<input type="checkbox" value="1">';
		break;
	case 'table':
		echo '<select name="'.$f1.'" id="'.$f1.'">';
		echo '<option value=""></option>';
		$list = DListUp("ID,name", "$field", "", "sort,$field,name");
		foreach ($list as $v) {
			if ($v[0] == $v1) {
				echo "<option value='$v[0]' selected>$v[1]</option>";
			} else {
				echo "<option value='$v[0]'>$v[1]</option>";
			}
		}
		echo "</select>";
		break;
	case 'table1':
		echo '<select name="'.$f1.'" id="'.$f1.'">';
		echo '<option value=""></option>';
		$list = DListUp("name", "$field", "", "sort,name");
		foreach ($list as $v) {
			if ($v[0] == $v1) {
				echo "<option value='$v[0]' selected>$v[0]</option>";
			} else {
				echo "<option value='$v[0]'>$v[0]</option>";
			}
		}
		echo "</select>";
		break;
	case 'account':
		echo '<select name="'.$f1.'" id="'.$f1.'">';
		echo '<option value=""></option>';
		$list = DListUp("ID,name", "account", "", "sort,ID");
		foreach ($list as $v) {
			if ($v[0] == $v1) {
				echo "<option value='$v[0]' selected>$v[1]</option>";
			} else {
				echo "<option value='$v[0]'>$v[1]</option>";
			}
		}
		echo "</select>";
		break;
	case 'month':
		echo ' <input type="checkbox" name="'.$f0.'" value=1'.($v0==1 ? " checked" : "").'>';
		echo '<input class="date" type="text" name="'.$f1.'" value="'.$v1.'" id="'.$f1.'">';
		echo_common_calender($f1);
		break;
	}
}
function echo_search_frame() {
?>
<table><tr>
<td background="../common/images/bg_search.gif" width=167 height=50 align=center>
<font size="+1">
検索
</font>
</td></tr></table>
<input type="hidden" name="clear" value="1">
<table>
<?php
}
function echo_search_frame_end() {
	echo '</table>';
}
function echo_button_search_clear() {
	global $clear_js;
?>
<input type="button" value="クリア" onClick="search_clear()">
<script language="javaScript">
function search_clear() {
<?=$clear_js?>
}
</script>
<?php
}
function echo_search_tr($name, $field, $type="str", $default="") {
	global $rn;

	echo "<tr><th>$name</th><td>";
	echo_search_input($field, $type, $default);
	echo "</td></tr>$rn";
}
function echo_search_select($name, $table, $field, $type, $where="", $null=true, $attribute="") {
	$req_field = str_replace('.', '__', $field);
	$value = $_REQUEST[$req_field];
	$list = DListUp($field, $table, $where);
	echo "<tr><th>$name</th><td><select name='$field' $attribute>";
	if ($null) echo "<option></option>";
	foreach ($list as $v) {
		if ($v[0] == $value) {
			echo "<option value='$v[0]' selected>$v[0]</option>";
		} else {
			echo "<option value='$v[0]'>$v[0]</option>";
		}
	}
	echo "</select></td></tr>";
}
function init_where($tag="") {
	global $where;

	if ($tag=="") $tag = get_page_tag();
	if ($_REQUEST["clear"]==1) {
		$_SESSION[$tag] = "";
	}
	$where = "";
}
function add_where($field, $type="str", $default="") {
	global $where;
	global $html_charset;

	$tag = get_page_tag();
	$req_field = str_replace('.', '__', $field);
	$f1 = $req_field;
	if (isset($_REQUEST[$f1])) {
		$v1 = $_REQUEST[$f1];
		$_SESSION[$tag][$f1] = $v1;
	} else {
		$v1 = $_SESSION[$tag][$f1];
	}
	switch ($type) {
	default:
		if ($v1=="") $v1 = $default;
		if ($v1=="") return "";
		if ($where<>'') $where .= " and ";
		break;
	case "date":
		$f2 = $req_field."_2";
		if (isset($_REQUEST[$f2])) {
			$v2 = $_REQUEST[$f2];
			$_SESSION[$tag][$f2] = $v2;
		} else {
			$v2 = $_SESSION[$tag][$f2];
		}
		if ($v2=='') $v2 = $default;
		$f0 = $req_field."_0";
		if (isset($_REQUEST[$f0])) {
			$v0 = $_REQUEST[$f0];
//		} else {
//			$v0 = $_SESSION[$tag][$f0];
		}
//		if ($v0=='') $v0 = "0";
		$_SESSION[$tag][$f0] = $v0;
		break;
	case "bool":
	case "checkbox":
		if ($v1=="") $v1 = $default;
		break;
	case "checkint":
	case "checkstr":
		if ($v1=="1") $v1=$default;
		break;
	case "none":
		break;
	case "month_3year":
	case "month":
		$f0 = $req_field."_0";
		if (isset($_REQUEST[$f0])) {
			$v0 = $_REQUEST[$f0];
		}
		$_SESSION[$tag][$f0] = $v0;
		break;
	}
	switch ($type) {
	case 'str':
		$where .= "$field=".db_value($v1, "str");
		break;
	case 'like':
		$v = mb_convert_kana($v1, "KHV", $html_charset);
		$where .= "($field like ".db_value("%".$v."%", "str");
		$v = mb_convert_kana($v1, "kh", $html_charset);
		$where .= " or $field like ".db_value("%".$v."%", "str").")";
		break;
	case 'prefix':
		$where .= "$field like ".db_value($v1."%", "str");
		break;
	case 'suffix':
		$where .= "$field like ".db_value("%".$v1, "str");
		break;
	case 'int':
		$where .= "$field=".db_value($v1, "int");
		break;
	case 'date':
//		if ($v1=="" and $v2=="") return "";
		if ($v2!="") {
			if ($where<>'') $where .= " and ";
			$where .= "$field<=".db_value($v2, "date");
			$r = $v2;
		}
		if ($v1!="") {
			if ($where<>'') $where .= " and ";
			$where .= "$field>=".db_value($v1, "date");
			$r = $v1;
		}
		if ($v0=="1") {
			if ($where<>'') $where .= " and ";
			$where .= "$field is null";
		}
		if ($v0==="0") {
			if ($where<>'') $where .= " and ";
			$where .= "$field is not null";
		}
		return $r;
	case 'bool':
	case 'checkbox':
		if ($v1=="") return "";
		if ($where<>'') $where .= " and ";
		$where .= $field."=".db_value($v1, "bool");
		break;
	case 'checkint':
		if ($v1=="") return "";
		if ($where<>'') $where .= " and ";
		$where .= $field."=".db_value($v1, "int");
		break;
	case 'none';
		break;
	case 'month_3year':
		if ($v0<>1) return "";
		if ($v1!="") {
			if ($where<>'') $where .= " and ";
			$dt = strtotime($v1);
			$y = date('Y', $dt);
			$m = date('n', $dt);
			$where .= "((year($field)-$y) % 3)=0";
			$where .= " and month($field)=$m";
			$r = $v1;
		}
		return $r;
	case 'month':
		if ($v0<>1) return "";
		if ($v1!="") {
			if ($where<>'') $where .= " and ";
			$dt = strtotime($v1);
			$y = date('Y', $dt);
			$m = date('n', $dt);
			$where .= "year($field)=$y";
			$where .= " and month($field)=$m";
			$r = $v1;
		}
		return $r;
	case 'year':
		if ($v1!="") {
			$dt = strtotime($v1);
			$y = date('Y', $dt);
			$where .= "year($field)=$y";
			$r = $v1;
		}
		return $r;
	}
	return $v1;
}
function echo_sort_frame() {
	echo '<select name="sort">';
}
function echo_sort_frame_end() {
	echo '</select>';
}
function echo_sort_option($name, $value) {
	$sort = $_SESSION[get_page_tag()]['sort'];
	if ($sort==$value) $attribute = " selected";
	echo '<option value="'.$value.'"'.$attribute.'>'.$name.'</option>';
}
function echo_sort_link($name, $value, $href="") {
	global $select_mode, $select_arg;

	if ($href=="") {
		if ($select_mode) {
			$href="?".$select_arg."&";
		} else {
			$href="?";
		}
	}
	$sort = $_SESSION[get_page_tag()]['sort'];
	if ($sort==$value or $sort==$value." asc") {
		echo '<a class="sort" href="'.$href.'sort='.$value.' desc">▲'.$name.'</a>';
	} elseif ($sort==$value." desc") {
		echo '<a class="sort" href="'.$href.'sort='.$value.'">▼'.$name.'</a>';
	} else {
		echo '<a class="sort" href="'.$href.'sort='.$value.'">'.$name.'</a>';
	}
	return $sort;
}
function echo_sort_td($name, $value, $mode="link", $href="") {
	global $rn;

	echo '<th nowrap>';
	switch ($mode) {
	case "link":
		return echo_sort_link($name, $value, $href);
	}
	echo '</th>'.$rn;
}
function get_sort() {
	$sort = $_REQUEST['sort'];
	$tag = get_page_tag();
	if ($sort=="") $sort = $_SESSION[$tag]['sort'];
	$_SESSION[$tag]['sort'] = $sort;
	return $sort;
}

function set_page_tag($tag="", $mode="") {
	global $page_tag, $rn;

	if ($tag<>"") $page_tag = $tag;
	if ($page_tag=="") $page_tag = $_REQUEST['page_tag'];
	if ($page_tag=="") $page_tag = $_SERVER['PHP_SELF'];
	if ($debug) echo "page_tag:$page_tag<br>";
	if ($mode=="echo") echo '<input type="hidden" name="page_tag" value="'.$page_tag.'">'.$rn;
	return $page_tag;
}
function get_page_tag($tag="") {
	if ($tag<>"") return $tag;
	return set_page_tag($tag, "");
}
function get_paging_sql($tag="") {
	$tag = get_page_tag($tag);
	return $_SESSION[$tag]['page_sql'];
}
function paging_list_init($conn, $sql, $tag="", $keyField="ID") {
	global $page, $debug, $page_line, $PAGE_SIZE;

	$page = $_REQUEST['page'];
	$tag = set_page_tag($tag);
	$page_line = 0;
	if ($page=='') {
		$_SESSION[$tag]['page_sql'] = $sql;
		$rs = db_exec($conn, $sql." limit 1000");
		$i=0;
		while (db_fetch_row($rs)) {
			$id_list[] = db_result($rs, $keyField);
			$i++;
		}
		$_SESSION[$tag]['id_list'] = $id_list;
		$records = $i;
//		$records = mysql_num_rows($rs);
		if ($debug) echo "records:".$records."<br>";
		$_SESSION[$tag]['records'] = $records;
		$page = 1;
		$_SESSION[$tag]['page_no'] = $page;
		$_SESSION[$tag]['current_id'] = 0;
		if ($records=="" or $records==0) return null;
		db_skip_row($rs, 0);
		return $rs;
	}
	$records = $_SESSION[$tag]['records'];
	if ($records=="" or $records==0) return null;
	if ($page==0) {
		$id = $_SESSION[$tag]['current_id'];
		$id_list = $_SESSION[$tag]['id_list'];
		if (is_array($id_list)) {
			$i = array_search($id, $id_list);
		} else {
			$i = false;
		}
		if ($i===false) {
			$page = $_SESSION[$tag]['page_no'];
			if ($page=="") $page = 1;
		} else {
			$page = ceil(($i+1) / $PAGE_SIZE);
		}
	} else {
		$_SESSION[$tag]['current_id'] = "";
	}
	$_SESSION[$tag]['page_no'] = $page;
	$max = ceil($records / $PAGE_SIZE);
	if ($page>$max) $page=$max;
	$sql = $_SESSION[$tag]['page_sql'];
	if ($sql=="") return null;
	$rs = db_exec($conn, $sql." limit 1000");
	db_skip_row($rs, ($page-1) * $PAGE_SIZE);
	return $rs;
}
function paging_list_loop() {
	global $page_line, $PAGE_SIZE;

	$page_line++;
	if ($page_line>$PAGE_SIZE) return true;
	return false;
}
function echo_paging_list_header($tag="") {
	global $page, $PAGE_SIZE, $select_arg, $rn;
	global $auth1;

	$tag = get_page_tag($tag);
	$records = $_SESSION[$tag]['records'];
/*
	if ($records=="" or $records==0) {
		echo "レコードはありません。";
		return false;
	}
*/
	$start = ($page-1) * $PAGE_SIZE + 1;
	if ($start<0) $start=0;
	$end = ($page) * $PAGE_SIZE;
	$max = ceil($records / $PAGE_SIZE);
	if ($max>100) {
		$max=100;
		$msg = "１００ページを超えています。条件を絞り込んでください。";
	}
	if ($end>$records) $end = $records;
?>
<div class="frame_paging_header">
<table bgcolor="#0000aa" width=100% height=23><tr>
<td>
対象件数：<?=$records?>件&nbsp;&nbsp;&nbsp;
現在表示件数：<?=$start?>～<?=$end?>&nbsp;&nbsp;&nbsp;
ページ：
<?php
	if ($page>1) {
		echo ' <a href="?page='.($page-1).'&'.$select_arg.'">(前へ)</a> ';
	} else {
		echo " (前へ) ";
	}
	$start_page = 1;
	if ($page>5) $start_page = $page-5;
	$end_page = $page + 4;
	if ($page<=5) $end_page = 10;
	for ($p=$start_page; $p<=$end_page; $p++) {
		if ($p>$max) break;
		if ($p==$page) {
			echo " $p ";
		} else {
			echo " <a href='?page=$p&$select_arg'>$p</a> ";
		}
	}
	if ($page<$max) {
		echo ' <a href="?page='.($page+1).'&'.$select_arg.'">(次へ)</a> ';
	} else {
		echo " (次へ) ";
	}
?>
</td>
<?php if ($auth1==3) { ?>
<td><a href="../csv/export_list.php?page_tag=<?=$tag?>">CSV出力</a></td>
<?php } ?>
</tr></table>
</div>

<div class="frame_paging_list">
<?php
}
function echo_paging_list_footer($new_search='', $tag="") {
?>
</div>
<?php
}
function paging_list_fetch($id, $tag) {
	$tag = get_page_tag($tag);
	$_SESSION[$tag]['current_id'] = $id;
	if ($id=="") $_SESSION[$tag]['id_list']=null;
	$id_list = $_SESSION[$tag]['id_list'];
	$r = array();
	if (is_array($id_list)) {
		$i = array_search($id, $id_list);
		if ($i===false) {
			$r['pre'] = "";
			$r['next'] = "";
			$r['no'] = 1;
			$r['records'] = 1;
		} else {
			$r['pre'] = $id_list[$i-1];
			$r['next'] = $id_list[$i+1];
			$r['no'] = $i+1;
			$r['records'] = count($id_list);
		}
	}
	return $r;
}
function echo_csv_header($filename) {
	header("Content-Type: text/csv");
//	header("Content-Length: " . filesize($filename));
	header('Content-Disposition: attachment; filename="'.$filename.'"');
}
function echo_confirm($title="") {
	echo "<p>$title</p>";
	$confirm = $_REQUEST['confirm'];
	if ($confirm<>'実行') {
?>
<form action="#" method="post">
<input type="submit" name="confirm" value="実行">
<p><a href="javascript:history.back()">戻る</a></p>
</form>
<?php
		require "footer.php";
		exit();
	}
?>
<p>実行しました。</p>
<p><a href="javascript:history.go(-2)">戻る</a></p>
<?php
}
function echo_button_frame($title="", $frame="frame_line") {
	if ($frame<>"") echo "<div class='$frame'>";
?>
<div class="frame_button">
<table border="2" cellpadding="0" cellspacing="1">
<tr><td><table bgcolor="silver">
<?php
}
function echo_button_frame_end($title="", $frame="frame_line") {
?>
</table></td></tr>
</table></div>
<?php
	if ($frame<>"") echo "</div>";
}
function echo_menu_tab($tab_title, $tab_no, $id) {
	$back = $_REQUEST['back'];
	echo '<br><p>';
	for ($i=1; $i<=count($tab_title); $i++) {
		if ($i==$tab_no) {
			echo '<span class="tab_selected">';
			echo $tab_title[$i];
			echo '</span>';
		} else {
			echo '<span class="tab">';
//			echo '<a href="javascript:location.replace(\'form'.$i.'.php?id='.$id.'\')">'.$tab_title[$i].'</a>';
			echo "<a href='form$i.php?id=$id&back=".($back+1)."'>".$tab_title[$i]."</a>";
			echo '</span>';
		}
	}
	echo '</p>';
}
function echo_frame_br() {
	echo '<br clear=all>';
}
function echo_option($list, $default) {
	if (!is_array($list)) return;
	$n = count($list[0]);
	foreach ($list as $v) {
		if ($n==1) {
			echo '<option value="'.$v[0].'"';
			if ($v[0] == $default) echo ' selected';
			echo '>';
			echo $v[0].'</option>';
		} else {
			echo '<option value="'.$v[0].'"';
			if ($v[0] == $default) echo ' selected';
			echo '>';
			echo $v[1].'</option>';
		}
	}
}
function echo_report_db($rs, $field, $type) {
	$value = db_result($rs, $field);
	echo_report($value, $type);
	return $value;
}
function echo_report_td($rs, $field, $type, $default="", $mode="echo") {
	global $rn;

	switch ($type) {
	case "int":
	case "cur":
		echo "<td align=right>";
		break;
	case "mid":
		echo "<td align=center>";
		$type = "int";
		break;
	default:
		echo "<td>";
		break;
	}
	switch ($mode) {
	default:
		$r = echo_report_db($rs, $field, $type);
		break;
	case "null":	//DB非参照
	case "default":	//デフォルト値表示
		echo_report($default, $type);
		$r = $default;
		break;
	}
	echo "</td>$rn";
	return $r;
}
function init_common_report_group($field) {
	global $group, $is_first_line;
	$group[]['field'] = $field;
	$is_first_line = true;
}
function echo_common_report_group($rs, $field_list="") {
	global $group, $cols, $is_first_line, $rn;

	$color[0] = "#b0b0b0";
	$color[1] = "#d0d0d0";
	$color[2] = "#F0F0F0";
	$n = count($group);
	for ($i=0; $i<$n; $i++) {
		$group[$i]['value'] = db_result($rs, $group[$i]['field']);
	}
	$fs = explode(",", $field_list);
	$fn = count($fs);
	if ($is_first_line) {
		$is_first_line=false;
	} else {
		for ($i=0; $i<$n; $i++) {
			if ($group[$i]['value']<>$group[$i]['pre'] or $rs==null) {
				for ($j=$n-1; $j>=$i; $j--) {
					if ($j==2) continue;
					if ($j==1) $sum_title = "小計";
					if ($j==0) $sum_title = "合計";
					echo '<tr bgcolor="'.$color[$j].'">';
					if ($j>0) echo '<td colspan='.$j.'><br></td>';
					echo '<td colspan='.($cols-$j-$fn).' align=left>'.$sum_title.'</td>';
					for ($k=0; $k<$fn; $k++) {
						echo '<td align=right>'.html_format($group[$j]['sum'.$k], "int").'</td>';
						$group[$j]['sum'.$k] = 0;
					}
					echo '</tr>'.$rn;
				}
				break;
			}
		}
	}
	if ($rs==null) return;
	for ($i=0; $i<$n; $i++) {
		if ($group[$i]['value']<>$group[$i]['pre']) {
			for ($j=$i; $j<$n; $j++) {
				echo '<tr bgcolor="'.$color[$j].'">';
				if ($j>0) echo '<td colspan='.$j.'><br></td>';
				echo '<td colspan='.($cols-$j).'>'.$group[$j]['value'].'<br></td>';
				echo '</tr>'.$rn;
			}
			break;
		}
	}
	for ($i=0; $i<$n; $i++) {
		$group[$i]['pre'] = $group[$i]['value'];
		for ($k=0; $k<$fn; $k++) {
			$group[$i]['sum'.$k] += db_result($rs, $fs[$k]);
		}
	}
}
?>