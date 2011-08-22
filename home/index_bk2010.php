<?php require 'header.php';?>

<div class="frame_index">

<table border="1">
 <tr>
  <td colspan="2">
   【更新情報】
  </td>
 </tr>
 <tr>
  <td>
   <font size="-1">更新機能</font>
  </td>
  <td>
   <font size="-1">概要</font>
  </td>
 </tr>
</table>

<table border="1">
 <tr>
  <td colspan="2">
   【お問い合わせ先】
  </td>
 </tr>
 <tr>
  <td>一般窓口</td>
  <td>TEL:0980-83-3591（北井もしくは高橋まで）</td>
 </tr>
 <tr>
  <td>通常のお問い合わせ（メール）</td>
  <td><a href="mailto:kitai@gnsjapan.jp">kitai@gnsjapan.jp </a>(北井）<br>
  <a href="mailto:takahashi@gnsjapan.jp">takahashi@gnsjapan.jp</a>（高橋）</td>
 </tr>
 <tr>
  <td>緊急のお問い合わせ（携帯電話）</td>
  <td>080-3228-1995（北井）<br>
  080-5498-4294（高橋）</td>
 </tr>
 <tr>
  <td>時間・休み</td>
　<td>休み：土・日・祝日<br>時間：10:00～18:00<br>
  ※事情により電話に出られない可能性もあります</td>
 </tr>
</table>


<?php if ($auth1==2) { ?>

<h2>管理者権限でログインしています</h2>

<p><a href="../admin/config/index.php">管理システム</a></p>

<?php } ?>

<?php if ($auth1==3) { ?>

<h2>特権ユーザー（CSV出力ができる）</h2>

<?php } ?>

</div>

<?php require 'footer.php';?>
