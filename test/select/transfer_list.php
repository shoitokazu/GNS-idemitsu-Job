<?php include 'header.php' ?>

<?php
$stamp = $_REQUEST['stamp'];
$category = $_REQUEST['category'];
$sc = $_REQUEST['sc'];
$stamp_index = $_REQUEST['stamp_index'];
$sc_index = $_REQUEST['sc_index'];
?>
<form method="POST" action="transfer_list.php" name="form_stamp">
<table><tr>
<td>発行元</td><td>
<select name="stamp">
<?php
//$list = DListUp("value", "choices", "field='scenter'", "sort,value");
$list = DListUp("name", "stamp", "", "sort");
if ($stamp_index!=null) {
	$stamp = $list[$stamp_index][0];
}
echo_option($list, $stamp);
?>
</select>
</td></tr>
<tr><td colspan=2 bgcolor=aaaaaa></td></tr>
<tr><td>SC</td><td>
<select name="sc" onchange="document.form_stamp.submit();">
<?php
//$list = DListUp("value", "choices", "field='trans_sc'", "sort,value");
$list = DListUp("group1", "transfer", "", "sort");
if ($sc_index!=null) {
	$sc = $list[$sc_index][0];
}
echo_option($list, $sc);
?>
</select>
</td></tr>
<tr><td>カテゴリー</td><td>
<select name="category" onchange="document.form_stamp.submit();">
<option value="">すべて</option>
<?php
$list = DListUp("group2", "transfer", "group1='$sc'");
echo_option($list, $category);
?>
</select>
</td></tr>
<tr><td colspan=2><input type="submit" value="表示"></td></tr>
</table>
</form>

<table border="0" cellpadding="0" cellspacing="0">
  <tr> 
    <td bgcolor="#CCFFFF">■振替先リスト</td>
    <td rowspan="2"><img src="../common/images/ssl_02.gif" width="39" height="60"></td>
  </tr>
  <tr> 
    <td valign="bottom" bgcolor="#CCFFFF"><img src="../common/images/privacy_01.gif" width="177" height="17"></td>
  </tr>
  <tr bgcolor="#CCCCCC"> 
    <td colspan="2"> <br>
      <?php
if ($sc != '') $where = "WHERE group1='$sc'";
if ($category != '') {
	if ($where != '') {
		$where .= " and ";
	} else {
		$where = "WHERE ";
	}
	$where .= "group2='$category'";
}

$table = "transfer";
$field = "ID,name,group1";
$sql = "SELECT $field FROM $table $where ORDER BY sort";
//echo $sql;
$rs = db_exec($conn, $sql);
?>
      <div align="center"> 
        <TABLE width="90%" border="1" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
          <TR> 
            <TD bgcolor="#003366"><div align="center"><font color="#FFFFFF" size="-1"></font></div></TD>
            <TD colspan="2" bgcolor="#003366"><div align="center"><font color="#FFFFFF" size="-1">振替先名称</font></div></TD>
          </TR>
          <?php
while (db_fetch_row($rs)) {
	$id = db_result($rs, 'ID');
	$name = db_result($rs, 'name');
	$v1 = db_result($rs, 'group1');
?>
          <TR> 
            <TD><div align="center"><font size="-1"></font></div></TD>
            <TD nowrap><font size="-1"><?php echo $name ?></font></TD>
            <TD> <div align="center"> 
<input name="button" type="button" onclick="btn_select('<?=$v1?>', '<?=$name?>')" value="選択">
              </div></TD>
          </TR>
          <?php
}
?>
        </TABLE>
        <br>
      </div></td>
  </tr>
</table>

<script language="javaScript">
function btn_select(v1, v2) {
	window.opener.document.form1.trans_sc.value=v1;
	window.opener.document.form1.transfer.value=v2;
	var i = document.form_stamp.stamp.selectedIndex;
	window.opener.document.form1.scenter.value=document.form_stamp.stamp.options[i].value;
	window.close();
}
</script>

<?php include 'footer.php';?>
