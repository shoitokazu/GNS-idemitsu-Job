<?php include 'header.php'; ?>

<script src="../common/selectWindow.js" language="JavaScript"></script>
<script src="../common/calendar.js" language="JavaScript"></script>

<form action="update.php" method="POST" name="form1">
<input type="hidden" name="select" value="<?=$select?>">
<?php
$table = "customer";
$id = $_REQUEST['id'];
if ($id==null) {
	$code = $_REQUEST['code'];
	$id = DLookUp("ID", $table, "ccode=".db_value($code, "str"));
	if ($id==null) return_error("存在しません。");
}

$where = "ID=$id";
//$where = limitCompany($where, $table);

$order = "ID";
$sql = "select * from $table where $where order by $order";
$rs = db_exec($conn, $sql);
db_fetch_row($rs);
$code = db_result($rs, "ccode");

if ($page_mode=="shop") lock_form();

if ($page_mode!="shop") $buttons_html .= ' / <a href="../work/list.php?clear=1&ccode='.urlencode($code).'">案件履歴</a>';
$buttons_html .= ' / <a href="../mainte/list.php?clear=1&ccode='.urlencode($code).'">整備履歴</a>';
$buttons_html .= ' / <a href="../article/list.php?clear=1&ccode='.urlencode($code).'">所有物件</a>';

$id_list = paging_list_fetch($id, "customer");
echo_sub_header($buttons_html, "更新", "delete.php?id=$id", $id_list);
init_form_format($rs);

echo_form_frame("基本情報");
echo_form_tr($rs, "顧客コード", "ccode", "str");
echo_html_tr($rs, "登録日", "making_date", "date");
echo_html_tr($rs, "更新日", "update_date", "date");
//echo_form_input("update_date", "hidate", date('Y/m/d'));
echo_form_frame_end();

echo_form_frame("担当者選択");
if ($page_mode<>"shop") {
	$sel = DLookUp("selected", "list_select", "`table`='customer' and rid=$id and uid=$uid");
	echo '<tr><th>チェック</th><td><input type="checkbox" name="selected" value="1"'.($sel[0]==1 ? " checked" : "").'></td></tr>';
}
//	$sel = DLookUp("selected", "list_select", "`table`='customer' and rid=$id and selected=1");
//	echo '<tr><th>使用中</th><td>'.($sel[0]==1 ? "○" : "×").'</td></tr>';
echo_form_tr($rs, "営業担当者", "wstaff", "staff");
echo_form_tr($rs, "サービス担当者", "scstaff", "staff");
echo_form_frame_end();

echo_frame_br();
echo_form_frame("個人住所");
echo_form_tr($rs, "顧客名１", "cname", "str");
echo_form_tr($rs, "顧客名２", "csub", "str");
echo_form_tr($rs, "ふりがな", "kana", "str");
echo_form_tr($rs, "郵便番号", "zip", "zip");
//echo_form_tr($rs, "都道府県", "pref", "str");
//echo_form_tr($rs, "市町村", "city", "str");
echo_form_tr($rs, "住所", "address", "str");
echo_form_tr($rs, "建物名", "building", "str");
echo_form_tr($rs, "電話", "tel", "str");
echo_form_tr($rs, "携帯電話", "mobile", "str");
echo_form_tr($rs, "ファックス", "fax", "str");
echo_form_tr($rs, "電子メール", "email", "str");
echo_form_frame_end();

echo_form_frame("会社住所");
echo_form_tr($rs, "顧客名１", "cname2", "str");
echo_form_tr($rs, "顧客名２", "csub2", "str");
echo_form_tr($rs, "ふりがな", "kana2", "str");
echo_form_tr($rs, "郵便番号", "zip2", "zip");
//echo_form_tr($rs, "都道府県", "pref2", "str");
//echo_form_tr($rs, "市町村", "city2", "str");
echo_form_tr($rs, "住所", "address2", "str");
echo_form_tr($rs, "建物名", "building2", "str");
echo_form_tr($rs, "電話", "tel2", "str");
echo_form_tr($rs, "携帯電話", "mobile2", "str");
echo_form_tr($rs, "ファックス", "fax2", "str");
echo_form_tr($rs, "電子メール", "email2", "str");
echo_form_frame_end();

/*
echo_form_frame("住所３（費用請求先）");
echo_form_tr($rs, "顧客名１", "cname3", "str");
echo_form_tr($rs, "顧客名２", "csub3", "str");
echo_form_tr($rs, "ふりがな", "kana3", "str");
echo_form_tr($rs, "郵便番号", "zip3", "zip");
//echo_form_tr($rs, "都道府県", "pref3", "str");
//echo_form_tr($rs, "市町村", "city3", "str");
echo_form_tr($rs, "住所１", "address3", "str");
echo_form_tr($rs, "住所２", "building3", "str");
echo_form_tr($rs, "電話", "tel3", "str");
echo_form_tr($rs, "携帯電話", "mobile3", "str");
echo_form_tr($rs, "ファックス", "fax3", "str");
echo_form_tr($rs, "電子メール", "email3", "str");
echo_form_frame_end();
*/

echo_frame_br();

if ($page_mode<>"shop") {
	echo_form_frame("宛名印刷設定");
	echo_form_tr($rs, "印刷住所", "print_address", "select2");
	echo_form_tr($rs, "印刷敬称", "honorific", "schar");
	echo_form_frame_end();
}

echo_form_frame("顧客情報");
echo_form_tr($rs, "顧客区分", "customer_type", "select2");
echo_form_tr($rs, "状態", "customer_kind", "select1");
echo_form_tr($rs, "誕生日", "birthday", "date");
echo_form_tr($rs, "役職名", "post", "schar");
//echo_form_tr($rs, "担当エリア", "area", "select1");
echo_form_tr($rs, "備考", "remarks", "txt");
echo '<tr><th>Rカスタマ</th><td>';
echo_form_db($rs, "f1", "bool");
echo 'ランク';
echo_form_db($rs, "f2", "bool");
echo '営業';
echo_form_db($rs, "f3", "bool");
echo 'カウンター';
echo_form_db($rs, "f4", "bool");
echo 'サービス';
echo '</td></tr>';
//echo_form_tr($rs, "オフィス", "company", "table");
echo_form_tr($rs, "DM拒否", "customer1", "bool");
echo_form_frame_end();

$list = DListUp("value,name", "choices", "field='customer_flag'", "sort", true);
if (is_array($list)) {
	echo_form_frame("担当エリア");
	foreach ($list as $v) {
		if ($v[1]<>"") echo_form_tr($rs, $v[1], "group".$v[0], "bool");
	}
	echo_form_frame_end();
}

/*
if ($page_mode<>"shop") {
	$list = DListUp("value,name", "choices", "field='customer_group'", "sort", true);
	if (is_array($list)) {
		echo_form_frame("顧客グループ");
		foreach ($list as $v) {
			if ($v[1]<>"") {
				$sel = DLookUp("selected", "customer_group", "cid=$id and gid=$v[0]");
				echo '<tr><th>'.$v[1].'</th><td>';
				echo '<input type="checkbox" name="g'.$v[0].'" value="1"'.($sel[0]==1 ? " checked" : "").'>';
				echo '</td></tr>';
			}
		}
		echo_form_frame_end();
	}
}
*/


////// 免許情報エリア（冨山_追加：2011/06/21） ////// ここから
echo_list_frame("免許情報");
echo '<tr><th>NO</th><th>免許有効日</th><th>次回更新日</th><th>免許種別</th><th>有／無</th><th>メモ</th><th>削除</th><th>編集</th></tr>';
$sql = "select * from license_limit where cid=$id and del_flg=0 order by lflg, next_date is NULL desc, next_date desc ";
$rs = db_exec($conn, $sql);
while (db_fetch_row($rs)) {
	$lid       = db_result($rs, "lid");
	$able_date = db_result($rs, "able_date");
	$next_date = db_result($rs, "next_date");
	$lclass    = db_result($rs, "lclass");
	$lflg      = db_result($rs, "lflg");
	$lmemo     = db_result($rs, "lmemo");
	echo '<tr>';
	echo '<td name="'.$lid.'">'.$lid;
	echo '</td>';
	echo '<td>';// 免許有効日
    echo '<input class="date" type="text" name="able_date'.$lid.'" value="'.$able_date.'" size=12 id="able_date'.$lid.'" readonly="readonly" style="background-color: #e0e0e0;">';
	echo '</td>';
	echo '<td>';// 次回更新日
	echo '<input class="date" type="text" name="next_date'.$lid.'" value="'.$next_date.'" size=12 id="next_date'.$lid.'" readonly="readonly" style="background-color: #e0e0e0;">';
	echo '</td>';
	echo '<td>';// 免許種別
	echo '<input class="str" type="text" name="lclass'.$lid.'" value="'.$lclass.'" size=24 id="lclass'.$lid.'" readonly="readonly" style="background-color: #e0e0e0;">';
	echo '</td>';
	echo '<td>';// 有効／無効FLG
	echo '<input class="str" type="text" name="lflg'.$lid.'" value="'.(($lflg>0)? "無効":"有効").'" size=4 id="lflg'.$lid.'" readonly="readonly" style="background-color: #e0e0e0;">';
	echo '</td>';
	echo '<td>';// メモ
	echo '<input class="str" type="text" name="lmemo'.$lid.'" value="'.$lmemo.'" size=20 id="lmemo'.$lid.'" readonly="readonly" style="background-color: #e0e0e0;">';
	echo '</td>';
	echo '<td>';// 論理削除指定済マーク
	echo '<input class="str" type="text" name="del_flg'.$lid.'" value="" size=4 id="del_flg'.$lid.'" readonly="readonly" style="background-color: #e0e0e0;color:red;">';
	echo '</td>';
	echo '<td>';// 編集btn
	echo "<input type='button' value='編集' onClick='openSelectWindow(\"../select/date.php?id=$id&lid=$lid \")'>";
	echo '</td>';
	echo '</tr>';
}
echo '<tr>';
echo '<td colspan=8>';
echo "<input type='submit' name='addDate' value='新規'>";
echo '</td>';
echo '</tr>';
echo_list_frame_end();
////// ここまで //////



echo_list_frame("来店履歴");
echo '<tr><th>日付</th><th>内容</th><th>担当者</th><th></th></tr>';
$sql = "select * from schedule where ccode=".db_value($code, "str")." order by date desc limit 10";
$rs = db_exec($conn, $sql);
while (db_fetch_row($rs)) {
	echo '<tr>';
	$sid = db_result($rs, "ID");
	echo_html_td($rs, "date", "datetime");
	echo_html_td($rs, "title", "str");
	echo_html_td($rs, "uid", "account");
	echo '<td>';
	echo '<input type="button" value="詳細" onClick="location.href=\'../schedule/form.php?id='.$sid.'\'">';
	echo '</td>';
	echo '</tr>';
}
echo_list_frame_end();


echo_list_frame("印刷ログ");
echo '<tr><th>宛名印刷日時</th></tr>';
$sql = "select * from customer_log where hid=$id order by timestamp desc limit 10";
$rs = db_exec($conn, $sql);
while (db_fetch_row($rs)) {
	$time = db_result($rs, "timestamp");
	echo '<tr>';
	echo '<td>';
	echo html_format($time, "datetime");
	echo '</td>';
	echo '</tr>';
}
echo_list_frame_end();

?>

</form>

<?php db_free($rs);?>

<?php include 'footer.php'; ?>
