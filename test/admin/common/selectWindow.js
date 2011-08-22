function openSelectWindow(URL){
	var winName = 'select_window'
//	var winName = URL.split('.')[0];
	var para =""
//		+" left="        +x
//		+",screenX="     +x
//		+",top="         +y
//		+",screenY="     +y
		+",toolbar="     +0
		+",location="    +0
		+",directories=" +0
		+",status="      +0
		+",menubar="     +0
		+",scrollbars="  +1
		+",resizable="   +1
//		+",innerWidth="  +w
//		+",innerHeight=" +h
//		+",width="       +w
//		+",height="      +h
	para +=",width=250";
/*
	if (winName=='staff_list') para +=",width=250";
	if (winName=='participant_list') para +=",width=250";
	if (winName=='category_select') para +=",width=250";
*/
	var selectWin = window.open(URL, winName, para);
	selectWin.focus();
}
function openNormalWindow(URL){
	var winName = "_blank";
	var para =""
		+",toolbar="     +0
		+",location="    +0
		+",directories=" +0
		+",status="      +0
		+",menubar="     +0
		+",scrollbars="  +1
		+",resizable="   +1
	var selectWin = window.open(URL, winName, para);
	selectWin.focus();
}
function openFileWindow(URL){
	var winName = 'file_window'
	var para =""
		+",toolbar="     +0
		+",location="    +0
		+",directories=" +0
		+",status="      +0
		+",menubar="     +0
		+",scrollbars="  +0
		+",resizable="   +1
		+",width="       +400
		+",height="      +300
	var selectWin = window.open(URL, winName, para);
	selectWin.focus();
}
