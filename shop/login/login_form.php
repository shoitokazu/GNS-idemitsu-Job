<?php
require_once '../../include/config.php';
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?=$html_charset?>">
<title>サービスショップ-ログイン</title>
</head>
<body bgcolor="#ffffff">

<h1>新WEB業務システム「MYBS」</h1>

<FORM ACTION="../login/login.php" METHOD="POST" name="form1">
<?php
if ($back<>"") {
	echo '<input type="hidden" name="back" value="'.$back.'">';
}
?>

<center>

<table border="1" cellspacing="0" cellpadding="0" width="640">
<tr>
<td align="center">

<br><br><br>
<table border="0" cellspacing="0" cellpadding="0" width="375">
<tr><td><b><font size="2">■サービスショップ-ログイン</font></b></td></tr>
</table>
<table border="0" cellspacing="0" cellpadding="0" width="350">
<tr><td><b><font size="2">サービスショップIDとパスワードをご入力して下さい。</font></b></td></tr>
</table>
<br>
<table border="0" cellspacing="1" cellpadding="2">
<tr><td colspan="2" bgcolor="#999999"></td></tr>
<tr>
<td align="right" bgcolor="#ffeeee" width="120"><b><font size="2">サービスショップID：</font></b></td>
<td width="260"><input type="text" name="user" size="24" maxlength="10" value=""></td>
</tr>
<tr>
<td align="right" bgcolor="#ffeeee"><b><font size="2">パスワード：</font></b></td>
<td><input type="password" name="pass" size="" maxlength="8" value=""></td>
</tr>
<tr><td colspan="2" bgcolor="#999999"></td></tr>
</table>
　<b><font size="2" color="#cc0000"><?=$errorMessage?></font></b>　
<table border="0" cellspacing="0" cellpadding="0" width="370">
<tr>
<td align="right"><input type="submit" name="submit" value="　ログイン　"></td>
<input type="hidden" name="mode" value="login">
</tr>
</table>
<br><br><br>

</td>
</tr>
</table>

</center>

</form>

</body>
</html>
