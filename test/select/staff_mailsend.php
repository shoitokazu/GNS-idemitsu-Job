<?php include 'header.php' ?>
<?php
	$where = "WHERE auth=1";

	$visit_id = $_POST['visit_id'];

	$base = "schedule";
	$table = $base;
	$table = "($table) left join work_h on $base.wcode=work_h.wcode";
	$table = "($table) left join account on $base.uid=account.ID";
	$table = "($table) left join customer on $base.ccode=customer.ccode";
	$where = "schedule.ID=$visit_id";
	$field = "$base.*, work_h.work2 as wname, account.name as sname, customer.cname";
	$sql = "SELECT $field FROM $table WHERE $where";

	$rs = db_exec($conn, $sql);
	while (db_fetch_row($rs)) {
		$staffName = db_result($rs, 'sname');
		$customerName = db_result($rs, 'cname');
		$visitDate = db_result($rs, 'date');
		$visitStartTime = db_result($rs, 'start_time');
		$visitEndTime = db_result($rs, 'end_time');
		$visitKind = db_result($rs, 'schedule3_0').db_result($rs, 'schedule3_1');
		$visitPPRank = db_result($rs, 'schedule4');
		$visitTitle = db_result($rs, 'title');
		$visitTalkMan = db_result($rs, 'schedule1');
		$visitPlace = db_result($rs, 'schedule2');
		$visitContent = db_result($rs, 'contents');
		$visitNote = db_result($rs, 'remarks');
		$matterName = db_result($rs, 'wname');
	}

	$table = "schedule_user left join account on schedule_user.uid=account.ID";
	$where = "hid=$visit_id";
	$sql = "select account.name from $table where $where";
	$rs = db_exec($conn, $sql);
 	while (db_fetch_row($rs)) {
		$entryName .= db_result($rs, 'name') . "\n";
	}

	echo "<br>";
	$i = 0;
	$j = 0;
	$flg = true;
	$snedflg = true;
	$where = "WHERE auth<>0";
	foreach ($_POST['staff_id'] as $staffId) {
		if ( $_POST['pcmail'][$staffId] == 1 Or $_POST['mobile'][$staffId] == 1 ) {
			if ( $flg ) {
				$where .= " And";
				$flg = false;
			} else {
				$where .= " Or";
			}
			$where .= " ID=$staffId";
		}
		$i++;
	}

?>

<br>
<b><font size="3">スタッフへメール送信</font><b>
<br><br>

<?php
	if ( $snedflg ) {
?>
<table id="staff" border="0" cellpadding="0" cellspacing="0">
<form method="post" action="staff_mailsend.php">
<thead>
<tr>
<th>ID</th>
<th class="name" colspan="2">名前</th>
</tr>
</thead>
<tbody>
<?php

		mb_language( "ja");
//		mb_internal_encoding( "Shift_JIS");
		mb_internal_encoding($html_charset);

		$visitDate2 = date("Y年m月d日", strtotime($visitDate));
		$visitStartTime2 = substr($visitStartTime, -8);
		$visitEndTime2 = substr($visitEndTime, -8);

//		$mailfrom = "From:" . $FROM_MAILADDRESS;
		$mailfrom = "From: ylocs";
		$subject = "[来店情報]：{$visitKind}：{$visitTitle}（{$visitDate2} {$visitStartTime2}～{$visitEndTime2}）";

		$content = "\n　【来店情報】\n\n";
		$content .= "------------------------------------------------------------\n";
		$content .= "≪日程≫\n";
		$content .= "{$visitDate2} {$visitStartTime2}～{$visitEndTime2}\n\n";
		$content .= "≪項目区分≫\n";
		$content .= "{$visitKind}\n\n";
		$content .= "≪PPランク≫\n";
		$content .= "{$visitPPRank}\n\n";
		$content .= "≪タイトル≫\n";
		$content .= "{$visitTitle}\n\n";
		$content .= "≪面談者≫\n";
		$content .= "{$visitTalkMan}\n\n";
		$content .= "≪場所≫\n";
		$content .= "{$visitPlace}\n\n";
		$content .= "≪内容≫\n";
		$content .= "{$visitContent}\n\n";
		$content .= "≪備考≫\n";
		$content .= "{$visitNote}\n\n";
		$content .= "≪参加者≫\n";
		$content .= "{$entryName}\n";
		$content .= "≪担当者≫\n";
		$content .= "{$staffName}\n\n";
		$content .= "≪案件名≫\n";
		$content .= "{$matterName}\n\n";
		$content .= "≪顧客≫\n";
		$content .= "{$customerName}\n\n";
		$content .= "------------------------------------------------------------";

		$table = "account";
		$sql = "SELECT * FROM $table $where ORDER BY sort,ID";

		$rs = db_exec($conn, $sql);

		$i = 0;
		while (db_fetch_row($rs)) {
			$id = db_result($rs, 'ID');
			$name = db_result($rs, 'name');
			$emailAddress = db_result($rs, 'pc_mail');
			$mobileEmailAddress = db_result($rs, 'mobile_mail');
			if ($class == "odd") {
				$class = "even";
			} else {
				$class = "odd";
			}
			?><tr class="<?=$class?>"><?
			?><th><?= $id ?></th><?
			?><td><?= $name ?></td><?
			?><td align="right"><?
			?><table border="0" cellpadding="0" cellspacing="0"><?
			?><tr><?
			if ( $_POST['pcmail'][$id] == 1 ) {
				$r = mb_send_mail( $emailAddress, $subject, $content, $mailfrom);
				if ($debug) echo "mailto:$emailAddress,$subject,$content,$mailfrom<br>";
				?><td align="center" width="40">ＰＣ</td><?
			} else {
				?><td align="center" width="40"></td><?
			}
			if ( $_POST['mobile'][$id] == 1 ) {
				$r = mb_send_mail( $mobileEmailAddress, $subject, $content, $mailfrom);
				if ($debug) echo "mailto:$mobileEmailAddress,$subject,$content,$mailfrom<br>";
				?><td align="center" width="40">携帯</td><?
			} else {
				?><td align="center" width="40"></td><?
			}
			?></tr><?
			?></table><?
			?></td><?
		}
		?></tr><?
		$i++;
?>
</tbody>
</table>

<table border="0" cellspacing="0" cellpadding="2" width="100%">
<tr>
<td align="center"><b><font size="2" color="#cc0000">上記のスタッフにメールを送信しました。</font></b></td>
</tr>
</table>
<?
	} else {
?>
<table border="0" cellspacing="0" cellpadding="2" width="100%">
<tr>
<td align="center"><b><font size="2" color="#cc0000">スタッフが一人も選択されていません。</font></b></td>
</tr>
</table>
<br>
<?
	}
?>

<p class="close"><a href="javascript:window.close();">[ 閉じる ]</a></p>

<?php include 'footer.php'; ?>
