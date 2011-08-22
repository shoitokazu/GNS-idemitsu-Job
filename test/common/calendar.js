/********************************************************************
 * カレンダーによる日付入力スクリプト
 *
 * ( 下記スクリプトは改造も可能ですがまったくいじらずにそのままペース
 *   トするだけでもご利用いただけるように書いてあります )
 *
 *  Syntax : wrtCalendar( formElementObject[,moveMonthFlg][,winOpenFlg] )
 *  例     : wrtCalendar( this )
 *
 *  使いたいINPUT入力タグにonFocus="wrtCalendar(this)"を ペーストし
 *  ます。それぞれのタグに違う名前(NAME属性)を忘れずに付けておいてく
 *  ださい。
 *
 *  Example :受付日:<INPUT NAME=e1 TYPE=text
 *                         onFocus="wrtCalendar(this)">
 *
 * ------------------------------------------------------------------
 * calendar.js Copyright(c)1999 Toshirou Takahashi tato@fureai.or.jp
 * Support http://www.fureai.or.jp/~tato/JS/BOOK/INDEX.HTM
 * ------------------------------------------------------------------
 */

var now    = new Date()
var absnow = now
var Win=navigator.userAgent.indexOf('Win')!=-1
var Mac=navigator.userAgent.indexOf('Mac')!=-1
var X11=navigator.userAgent.indexOf('X11')!=-1
var Moz=navigator.userAgent.indexOf('Gecko')!=-1
var Opera=!!window.opera
var winflg=1

function wrtCalendar(oj,arg1,arg2){

  if(Opera)return
  oj.blur()

  if(!arguments[1])arg1=0
  if(!Moz)
  if(arguments[1]||arguments[1]==0)winflg=0


  //-初期化
  if(arg1==0) {
	now = new Date(oj.value)
	if (isNaN(now)) now=new Date()
	absnow = now
  }

  //-年月日取得
  nowdate  = now.getDate()
  nowmonth = now.getMonth()
  nowyear  = now.getYear()

  //-月移動処理
  if(nowmonth==11 && arg1 > 0){        //12月でarg1が+なら
    nowmonth = -1 + arg1 ; nowyear++   //月はarg1-1;1年加算
  } else if(nowmonth==0 && arg1 < 0){  //1月でarg1が-なら
    nowmonth = 12 + arg1 ; nowyear--   //月はarg1+12;1年減算
  } else {
    nowmonth +=  arg1                  //2-11月なら月は+arg1
  }

  //-2000年問題対応
  if(nowyear<1900)nowyear=1900+nowyear

  //-現在月を確定
  now   = new Date(nowyear,nowmonth,1)

  //-YYYYMM作成
  nowyyyymm=nowyear*100+nowmonth

  //-YYYY/MM作成
  nowtitleyyyymm=nowyear+'/'+(nowmonth + 1)

  //-週設定
  week = new Array('日','月','火','水','木','金','土');

  //-カレンダー表示用サブウインドウオープン
  if(winflg){

    var w=152
    var h=156

    //-calendar用OS別サイズ微調整
    if(Moz)     { w+=15 ; h+=40 }
    else if(Win){ w+=0  ; h+=0  }
    else if(Mac){ w+=8  ; h+=22 }
    else if(X11){ w+=5  ; h+=46 }

    var x=100
    var y=20

    if(document.all){

        x=window.event.screenX+30
        y=window.event.screenY-180

    } else if (document.layers || document.getElementById){

        x+=window.screenX
        y+=window.screenY
    }

    mkSubWin('','calendar',x,y,w,h)

  }

  //-カレンダー構築用基準日の取得
  fstday   = now                                           //今月の1日
  startday = fstday - ( fstday.getDay() * 1000*60*60*24 )  //最初の日曜日
  startday = new Date(startday)

  //-カレンダー構築用HTML
  ddata = ''
  ddata += '<HTML>\n'
  ddata += '<HEAD>'
  if(!Moz)
  ddata += '<meta http-equiv="Content-Type" content="text/html;charset=SHIFT_JIS">\n'
  ddata += '<TITLE>Auto Input Calendar</TITLE>\n'
  ddata += '<STYLE>\n'
  ddata += ' BODY  { font:12px ; line-height:12px ; margin : 7px }\n'
  ddata += ' TH  { font:12px ; line-height:12px ; font-weight : 900 }\n'
  ddata += ' TD  { font:12px ; font-family : Arial; line-height:12px }\n'

  ddata += ' A  { text-decoration:none;color:#000000;font:10px;font-family:Arial;line-height:12px }\n'
  ddata += ' INPUT  { font:10px ; font-family : Arial ; line-height:10px ; padding:0px}\n'
  ddata += '</STYLE>\n'
  ddata += '</HEAD>\n'
  ddata += '<BODY  BGCOLOR=#dddddd>\n'

  ddata += '<FORM>\n'
  ddata += '<TABLE BORDER=0 BGCOLOR=#dddddd  BORDERCOLOR=#dddddd WIDTH=140 HEIGHT=140>\n'

  //-MONTH
  ddata += '   <TR id="trmonth" BGCOLOR=orange BORDERCOLOR=orange WIDTH=140 HEIGHT=14>\n'
  ddata += '   <TH COLSPAN=7 WIDTH=140 HEIGHT=14 ALIGN="right"><NOBR>\n'

  ddata +=       nowtitleyyyymm
  ddata += ' <INPUT TYPE=button VALUE="<<" '
  ddata += '  onClick="self.opener.wrtCalendar(self.opener.document.'
  ddata +=            "getElementById('"+oj.id+"')"+',-1,0)">\n'
  ddata += '<INPUT TYPE=button VALUE="o" '
  ddata += '  onClick="self.opener.wrtCalendar(self.opener.document.'
  ddata +=            "getElementById('"+oj.id+"')"+',0,0)">\n'
  ddata += '<INPUT TYPE=button VALUE=">>" '
  ddata += '  onClick="self.opener.wrtCalendar(self.opener.document.'
  ddata +=            "getElementById('"+oj.id+"')"+',1,0)">\n'
  ddata += '</NOBR></TH>\n'
  ddata += '   </TR>\n'

  //-WEEK
  ddata += '   <TR BGCOLOR=pink WIDTH=140 HEIGHT=14>\n'

  for (i=0;i<7;i++){
    ddata += '   <TH WIDTH=14 HEIGHT=14>\n'
    ddata +=       week[i]
    ddata += '   </TH>\n'
  }
  ddata += '   </TR>\n'

  //-DATE
  for(j=0;j<6;j++){
    ddata += '   <TR BGCOLOR=#ffffff>\n'
    for(i=0;i<7;i++){
      nextday = startday.getTime() + (i * 1000*60*60*24)
      wrtday  = new Date(nextday)

      wrtdate = wrtday.getDate()
      wrtmonth= wrtday.getMonth()
      wrtyear = wrtday.getYear()
      if(wrtyear < 1900)wrtyear=1900 + wrtyear
      wrtyyyymm = wrtyear * 100 + wrtmonth
      wrtyyyymmdd= ''+wrtyear +'/'+ (wrtmonth+1) +'/'+wrtdate

      wrtdateA  = '<A HREF="javascript:function v(){'
      wrtdateA += '   self.opener.document.getElementById('+"'"+oj.id+"')"
      wrtdateA += '.value=(\''+wrtyyyymmdd+'\');self.close()};v()" '
      wrtdateA += '>\n'
      wrtdateA += '<FONT COLOR=#000000>\n'
      wrtdateA += wrtdate
      wrtdateA += '</FONT>\n'
      wrtdateA += '</A>\n'

      if(wrtyyyymm != nowyyyymm){ 
        ddata += ' <TD BGCOLOR=#cccccc WIDTH=14 HEIGHT=14>\n'
        ddata += wrtdateA

      } else if( wrtdate  == absnow.getDate()  && 
                 wrtmonth == absnow.getMonth() && 
                 wrtday.getYear() == absnow.getYear()){
        ddata += ' <TD BGCOLOR=magenta WIDTH=14 HEIGHT=14>\n'
        ddata += '<FONT COLOR="#ffffff">'+wrtdateA+'</FONT>\n'

      } else {
        ddata += ' <TD WIDTH=14 HEIGHT=14>\n'
        ddata += wrtdateA
      }
      ddata += '   </TD>\n'
    }
    ddata += '   </TR>\n'

    startday = new Date(nextday)
    startday = startday.getTime() + (1000*60*60*24)
    startday = new Date(startday)
  }

  //-mac用クローズボタン
  if(Mac){
    ddata += '   <TR>\n'
      ddata += '   <TD COLSPAN=7 ALIGN=center>\n'
       ddata += '   <INPUT TYPE=button VALUE="CLOSE" '
       ddata += '          onClick="self.close();return false">\n'
      ddata += '   </TD>\n'
    ddata += '   </TR>\n'
  }

  ddata += '</TABLE>\n'

  ddata += '</FORM>\n'

  ddata += '</BODY>\n'
  ddata += '</HTML>\n'

  calendarwin.document.write(ddata)
  calendarwin.document.close()
  calendarwin.focus()

  winflg=1
}


/********************************************************************
 * 簡易サブウインドウ開き
 *  Syntax : mkSubWin(URL,winName,x,y,w,h)
 *  例     : mkSubWin(winIndex,'test.htm','win0',100,200,150,300)
 * ------------------------------------------------------------------
 */

var calendarwin;

function mkSubWin(URL,winName,x,y,w,h){

    var para =""
             +" left="        +x
             +",screenX="     +x
             +",top="         +y
             +",screenY="     +y
             +",toolbar="     +0
             +",location="    +0
             +",directories=" +0
             +",status="      +0
             +",menubar="     +0
             +",scrollbars="  +0
             +",resizable="   +1
             +",innerWidth="  +w
             +",innerHeight=" +h
             +",width="       +w
             +",height="      +h

        calendarwin=window.open(URL,winName,para);
        calendarwin.focus()

}
  /*--/////////////ここまで///////////////////////////////////////--*/


