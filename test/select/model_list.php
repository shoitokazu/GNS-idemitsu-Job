<?php include 'header.php' ?>

<?php
$table = $_REQUEST['table'];
if ($table=='') $table = "model";
$field = $_REQUEST['field'];
if ($field=='') $field = "name";
$target = $_REQUEST['target'];
if ($target=='') $target = "form1.text1";
$req_where = $_REQUEST['where'];
?>

<table border="0" cellpadding="0" cellspacing="0">
  <tr> 
    <td bgcolor="#CCFFFF">■艇種リスト</td>
    <td rowspan="2"><img src="../common/images/ssl_02.gif" width="39" height="60"></td>
  </tr>
  <tr> 
    <td valign="bottom" bgcolor="#CCFFFF"><img src="../common/images/privacy_01.gif" width="177" height="17"></td>
  </tr>
  <tr bgcolor="#CCCCCC"> 
    <td colspan="2"> <br>
      <div align="center"> 
        <TABLE width="90%" border="1" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
          <TR> 
            <TD bgcolor="#003366"><div align="center"><font color="#FFFFFF" size="-1">艇種ＣＤ</font></div></TD>
            <TD colspan="2" bgcolor="#003366"><div align="center"><font color="#FFFFFF" size="-1">艇種</font></div></TD>
          </TR>
<?php
$list = DListUp("ID,code,name", "model");
if (is_array($list)) foreach ($list as $v) {
?>
          <TR> 
            <TD><div align="center"><font size="-1"><?=$v[1]?></font></div></TD>
            <TD><font size="-1"><?=$v[2]?></font></TD>
            <TD> <div align="center"> 
<input name="button" type="button" onclick="btn('<?=$v[1]?>')" value="選択"></td>
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

<script language="javascript">
/*
function btn(value) {
	location.href='select.php?target=<?=$target?>&value='+value;
	
}
*/
function btn(v) {
	window.opener.document.getElementById('<?=$target?>').value = v;
	window.opener.getModelValue();
//	window.opener.document.form1.submit();
	window.close()
}
</script>

<?php include 'footer.php'; ?>
