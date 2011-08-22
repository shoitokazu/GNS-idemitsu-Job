<?php require 'header.php';?>

<div class="frame_index">
  <table border="1">
 <tr>
  <td colspan="2">
   【お問い合わせ先】グローバルネットワークサービス株  </td>
 </tr>
 <tr>
  <td width="163">一般窓口</td>
  <td width="370">TEL:0980-83-3591　携帯:090-9408-1974
</td>
 </tr>
 <tr>
  <td>お問い合わせ（メール）</td>
  <td><a href="mailto:kitai@gnsjapan.jp">kitai@gnsjapan.jp </a>(北井）</td>
 </tr>
 <tr>
  <td>時間・休み</td>
  <td>休み：土・日・祝日<br />
    時間：10:00～18:00<br />
※緊急時はお手数ですが携帯にご連絡ください
</td>
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
