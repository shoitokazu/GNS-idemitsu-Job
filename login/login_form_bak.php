<?php
require_once '../include/config.php';
?>
<HTML>
<HEAD>
<meta http-equiv="Content-Type" content="text/html; charset=<?=$html_charset?>">
<title><?=$header_title?></title>
</HEAD>

<body bgcolor=#DADADA>

<FORM ACTION="../login/login.php" METHOD="POST" name="form1">

<table width="498" height="393" border="1" align="center">
  <tr>
    <td height="261" align="center" valign="middle" background="../common/images/login.gif">
	
      <label></label><label></label>
      <label></label>
      <div align="center">
        <table width="300" border="1" cellpadding="3">
          <tr>
            <td><table width="253" border="0" align="center" cellpadding="3">
              <tr>
              </tr>
              <tr>
                <td width="111"><div align="right">ID</div></td>
                <td width="124"><input type="text" name="user" /></td>
              </tr>
              <tr>
                <td><div align="right">Password</div></td>
                <td><input type="password" name="pass" /></td>
              </tr>
              <tr>
                <td>&nbsp;</td>
                <td><div align="right">
                    <input type="submit" name="Submit" value="　Log In　" />
                </div></td>
              </tr>
            </table></td>
          </tr>
        </table>
	<br>
	<table width=300>
	<tr><td><hr></td></tr>
        <tr><td></td></tr>
	<tr><td><hr></td></tr>
	</table>
      </div></td>
  </tr>
</table>

</form>

</body>
</html>
