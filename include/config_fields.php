<?php
function jp2db($field_jp) {
	switch ($field_jp) {
	case "整備明細ID":
		return "ID";
	case "整備ID":
		return "hid";
	case "グループ":
		return "group1";
	case "順番":
		return "sort";
	case "部品区分":
		return "itype";
	case "部品番号":
		return "icode";
	case "名称":
		return "iname";
	case "数量":
		return "num";
	case "単価":
		return "price";
	case "振替率":
		return "rate";
	case "金額":
		return "amount";
	case "原価１":
		return "cost1";
	case "原価２":
		return "cost2";
	case "サービスショップID":
		return "ssid";
/*	case "サービス店":
	case "確定":
	case "事業所ID":
		return "";
*/	}
	return $field_jp;
}
function db2jp($field_db) {
	switch ($field_db) {
	case "ID":
		return "整備明細ID";
	case "hid":
		return "整備ID";
	case "company":
		return "";
	case "group1":
		return "グループ";
	case "sort":
		return "順番";
	case "itype":
		return "部品区分";
	case "icode":
		return "部品番号";
	case "iname":
		return "名称";
	case "num":
		return "数量";
	case "price":
		return "単価";
	case "rate":
		return "振替率";
	case "amount":
		return "金額";
	case "cost1":
		return "原価１";
	case "cost2":
		return "原価２";
	case "ssid":
		return "サービスショップID";
	case "old_ID":
		return "";
	}
	return $field_db;
}
?>
