<?php
require_once '../include/config.php';
?>
<HTML>
<HEAD>
<meta http-equiv="Content-Type" content="text/html; charset=<?=$html_charset?>">
<title><?=$header_title?> - ログイン画面</title>
</HEAD>

<BODY BGCOLOR="#FFFFFF">

<h1>※※テストラン専用※※<br>　新WEB業務システム「MYBS」</h1>

<FORM ACTION="../login/login.php" METHOD="POST" name="form1">

<CENTER><TABLE BORDER=0 CELLSPACING=0 CELLPADDING=0 WIDTH=500 BACKGROUND="../common/images/bg_login.gif">
   <TR>
      <TD>
         <CENTER><IMG SRC="../common/images/ybs.gif" WIDTH=183 HEIGHT=68 ALIGN=bottom><IMG SRC="../common/images/ylocs.gif"  ALIGN=bottom></CENTER>
      </TD>
      <TD>
         <P ALIGN=right></P>
      </TD>
   </TR>
   <TR>
      <TD COLSPAN=2 HEIGHT=240 BGCOLOR="#000000">
         <P ALIGN=right><IMG SRC="../common/images/seiun.gif" WIDTH=80 HEIGHT=60 ALIGN=bottom></P>
         
         <CENTER><TABLE BORDER=0 CELLSPACING=0 CELLPADDING=0>
            <TR>
               <TD>
                  <P ALIGN=right></P>
               </TD>
               <TD>
                  <CENTER></CENTER>
               </TD>
            </TR>
            <TR>
               <TD>
                  <P ALIGN=right><FONT COLOR="#3399FF">(アカウント)</FONT><FONT COLOR="#FFFFFF">ID：</FONT></P>
               </TD>
               <TD>
                  <P><INPUT TYPE=text NAME="user" VALUE="" SIZE=30></P>
               </TD>
            </TR>
            <TR>
               <TD HEIGHT=18>
                  <P ALIGN=right></P>
               </TD>
               <TD HEIGHT=18>
                  <P></P>
               </TD>
            </TR>
            <TR>
               <TD>
                  <P ALIGN=right><FONT COLOR="#3366FF">（パスワード）</FONT><FONT COLOR="#FFFFFF">ＰＡＳ：</FONT></P>
               </TD>
               <TD>
                  <P><INPUT TYPE=password NAME="pass" VALUE="" SIZE=30></P>
               </TD>
            </TR>
         </TABLE>
         <BR>
         </CENTER>
      </TD>
   </TR>
   <TR>
      <TD>
         <P></P>
      </TD>
      <TD>
         <P><TABLE BORDER=0 CELLSPACING=5>
            <TR>
               <TD>
                  <P><input type="image" src="../common/images/btn_login.gif" name="submit" width="89" height="28" align="bottom" border="0"></P>
               </TD>
               <TD>
                  <P><a href="#reset" onClick="window.document.form1.reset();return false;"><IMG SRC="../common/images/btn_clear.gif" WIDTH=89 HEIGHT=28 BORDER=0 ALIGN=bottom></a></P>
               </TD>
            </TR>
         </TABLE>
      </TD>
   </TR>
</TABLE>
</CENTER>

</FORM>

</BODY>
</HTML>
