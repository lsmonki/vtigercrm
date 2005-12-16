<?php
/**
 * Copyright 2002-2004 by Gero Kohnert
 *
 * a calendar for a single day
 *
 * @modulegroup appointment
 * @module calendar_day
 */
 require_once('modules/Users/User.php');

 global $calpath,$callink;
 $calpath = 'modules/Calendar/';
 $callink = 'index.php?module=Calendar&action=';
 
 global $theme;
 $theme_path="themes/".$theme."/";
 $image_path=$theme_path."images/";
 require_once ($theme_path."layout_utils.php");
 global $mod_strings,$app_strings,$current_user;




 echo get_module_title($mod_strings['LBL_MODULE_NAME'], $mod_strings['LBL_MODULE_NAME'], true); 
echo "\n<BR>\n";
 $t=Date("Ymd");
?>
	<table cellpadding="0" cellspacing="0" border="0"><tr>
        <form name="Calendar" method="GET" action="index.php">
          <input type="hidden" name="module" value="Calendar">
          <input type="hidden" name="action">
          <input type="hidden" name="t">
          <td><input title="<? echo $mod_strings['LBL_DAY_BUTTON_TITLE']?>" accessKey="<? echo $mod_strings['LBL_DAY_BUTTON_KEY']?>" onclick="this.form.action.value='calendar_day';this.form.t.value='<?echo $t?>'" type="image" src="<? echo $image_path ?>day_sel.gif" name="button" value="  <? echo $mod_strings['LBL_DAY']?>  " >
		  <input title="<? echo $mod_strings['LBL_WEEK_BUTTON_TITLE']?>" accessKey="<? echo $mod_strings['LBL_WEEK_BUTTON_KEY']?>" onclick="this.form.action.value='calendar_week';this.form.t.value='<?echo $t?>'" type="image" src="<? echo $image_path ?>week.gif" name="button" value="  <? echo $mod_strings['LBL_WEEK']?>  " >
		  <input title="<? echo $mod_strings['LBL_MON_BUTTON_TITLE']?>" accessKey="<? echo $mod_strings['LBL_MON_BUTTON_KEY']?>" onclick="this.form.action.value='calendar_month';this.form.t.value='<?echo $t?>'" type="image" src="<? echo $image_path ?>month.gif" name="button" value="  <? echo $mod_strings['LBL_MON']?>  " ></td>
		  </tr>
        </form>
      </table>
<?
 include_once $calpath .'webelements.p3';
 include_once $calpath .'permission.p3';
 include_once $calpath .'preference.pinc';
 #include_once $calpath .'task.pinc';
 include_once $calpath .'appointment.pinc';
require_once('modules/Calendar/UserCalendar.php');
 #include_once $calpath .'product.pinc';

 /* Check if user is allowed to use it */
 #check_user();
 loadmodules("appointment","show");
 loadlayout();

 /**
  * display a calendar for one single day
  */
 class calendar_day extends layout {
   Function calendar_day()
   {
   	$this->pref = new preference();
	$this->db = new PearDatabase();
	$calobj = new UserCalendar();
//	$this->tablename = $calobj->table_name;
   }

   /**
    * the data display part
    */
   Function info() {
     global $lang,$tutos,$callink,$calpath,$image_path,$mod_strings,$current_user,$adb;
?>
<script type="text/javascript" language="Javascript" src="include/js/general.js"></script>
<script type="text/javascript" language="Javascript">
function trim(s) {
        while (s.substring(0,1) == " ") {
                s = s.substring(1, s.length);
        }
        while (s.substring(s.length-1, s.length) == ' ') {
                s = s.substring(0,s.length-1);
        }

        return s;
}

function check_form()
{
	if(trim(document.appSave.subject.value) == "")
	{
		alert("Missing Subject");	
		document.appSave.subject.focus()
		return false;
	}
	else
	{
		if (document.appSave.activitytype[0].checked==true)
		{
			document.appSave.duration_minutes.value = "15";
		}
		else if (document.appSave.activitytype[1].checked == true)
		{
			document.appSave.duration_minutes.value = "45";
		}
	        return true;
	}
}
</script>
<div style="position:absolute;top:-1000px;left:-1000px;" id='createBox'> 
  <form name="appSave" onSubmit="return check_form()" method="POST" action="index.php">
    <input type="hidden" name="module" value="Activities">
    <input type="hidden" name="activity_mode" value="Events">
    <input type="hidden" name="action" value="Save">
    <input type="hidden" name="record" value="">
    <input type="hidden" name="taskstatus" value="Not Started">
    <input type="hidden" name="duration_hours" value="0">
    <input type="hidden" name="assigned_user_id" value="<? echo $current_user->id ?>">
    <input type="hidden" name="duration_minutes" value="0">
	<table cellspacing="1" cellpadding="2" border="0" class="event">
      <tr> 
        <td>
          <input type='radio' name='activitytype' value='Call' class='radio' onclick='document.appSave.module.value="Activities";' style='vertical-align: middle;' checked> 
          <?echo $mod_strings['LBL_CALL']?>&nbsp; <input type='radio' name='activitytype' value='Meeting' class='radio' style='vertical-align: middle;' onclick='document.appSave.module.value="Activities";'> 
          <?echo $mod_strings['LBL_MEET']?></td>
        <td valign="top"><div align="right"><a href="javascript:;"><img id="closeicon" src="<? echo $image_path ?>close.gif" border="0"></a></div></td>
      </tr>
      <tr> 
        <td colspan="2"><span class="required">*</span><?echo $mod_strings['LBL_SUBJECT']?>&nbsp;</td>
      </tr>
      <tr> 
        <td valign=top><input name='subject' size='30' maxlength='255' type="text"></td>
        <input name='date_start' id='inlineCal15CallSavejscal_field' maxlength='10' type="hidden" value=""></td>
        <input name='due_date' maxlength='10' type="hidden" value=""></td>

	<input name='time_start' type="hidden" maxlength='5' value="">
	<input name='notime' type="hidden" value="">
	<input name='time_end' type="hidden" maxlength='5' value=""></td>
        <script type="text/javascript">
//		Calendar.setup ({
//			inputField : "inlineCal15CallSavejscal_field", ifFormat : "%Y-%m-%d", showsTime : false, button : "inlineCal15CallSavejscal_trigger", singleClick : true, step : 1
//		});
		</script>
        <td align="left" valign=top><input title='Save [Alt+S]' accessKey='S' class='button' type='submit' name='button' value=' Save ' ></td>
      </tr>
    </table>
  </form>
  <br>
</div>
<?
     if(isset($this->m) && isset($this->d) && isset($this->y))
     {	
	$ts = mktime(12,0,0,$this->m,$this->d,$this->y);
     }
     else
     {
     	$ts = mktime(12,0,0,substr($this->t,4,2),substr($this->t,6,2),substr($this->t,0,4));
     }
     $from = new DateTime();
     $last_day = new DateTime();
     $next_day = new DateTime();

     $from->setDateTimeTS($ts);

     $last_day->setDateTimeTS($ts);
     $next_day->setDateTimeTS($ts);

     $next_day->addDays(1);
     $last_day->addDays(-1);

     $wn = sprintf("%02d", Round( (Date("z",$ts)+7 ) / 7) );
     $yy = Date("y",$ts);
         
     $this->user->callist = array();
     
     appointment::readCal($this->user,$from,$next_day);
#    echo strftime($lang['DateFormatTitle'],$from->ts)." ".$from->ts ."<br />";
     #task::readCal($this->user,$from,$next_day);


     foreach($tutos[activemodules] as $i => $f) {
       $x = &new $tutos[modules][$f][name]($this->dbconn);
       $x->readCal($this->user,$from,$next_day);
     }


     for ($i = -1 ; $i < 24 ; $i++ ) {
       $table[$i] = array();
     }
     foreach ($this->user->callist as $idx => $xx) {
       if ( ! $this->user->callist[$idx]->inside($from)) {
         continue;
       }
       if (!cal_check_against_list($this->user->callist[$idx],$this->uids)) {
         continue;
       }
       if ( $this->user->callist[$idx]->gettype() == "note" ) {
         $table[-1][] = &$this->user->callist[$idx];
         $rowspan[-1][] = 1;
         continue;
       }
       if ( $this->user->callist[$idx]->gettype() == "watchlist" ) {
         $table[-1][] = &$this->user->callist[$idx];
         $rowspan[-1][] = 1;
         continue;
       }
       if ( $this->user->callist[$idx]->gettype() == "task" ) {
         $table[-1][] = &$this->user->callist[$idx];
         $rowspan[-1][] = 1;
         continue;
       }
       if ( $this->user->callist[$idx]->gettype() == "reminder" ) {
         $table[-1][] = &$this->user->callist[$idx];
         $rowspan[-1][] = 1;
         continue;
       }
       if ( $this->user->callist[$idx]->t_ignore == 1) {
         $table[-1][] = &$this->user->callist[$idx];
         $rowspan[-1][] = 1;
         continue;
       }
       if ( ($this->user->callist[$idx]->s_out == 1) && ($this->user->callist[$idx]->e_out == 1) ) {
         $table[-1][] = &$this->user->callist[$idx];
         $rowspan[-1][] = 1;
         continue;
       }
       $x1 = Date("G",$this->user->callist[$idx]->start->getTimeStamp());
       $x2 = Date("G",$this->user->callist[$idx]->end->getTimeStamp());

       if ( $this->user->callist[$idx]->s_out == 1 ) {
         $x1 = 0;
       }
       if ( $this->user->callist[$idx]->e_out == 1 ) {
         $x2 = 23;
       }
       # find a free position
       $pos = -1;
       $found = false;
       while ( $found == false ) {
         $found = true;
         $pos ++;
         for ( $i = $x1; $i <= $x2 ; $i++ ) {
           if (isset($table[$i][$pos]) ) {
             $found = false;
             continue;
           }
         }
       }
       for ( $i = $x1; $i <= $x2 ; $i++ ) {
         if ( $i == $x1 ) {
           $table[$i][$pos] = &$this->user->callist[$idx];
           $rowspan[$i][$pos] = ($x2 - $x1 +1);
         } else {
           $table[$i][$pos] = -1;
         }
       }
     }
     $maxcol = 1;
     for ($i = -1 ; $i < 24 ; $i++ ) {
       $maxcol = max($maxcol,count($table[$i]));
     }

     echo "<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">";
     echo "<form action=\"". $callink ."calendar_day\" method=\"get\">\n";
     echo "<tr><td width=\"65%\">";
	 echo "<table class=\"outer\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\"><tr><td>\n";
     echo "<table class=\"navigate\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">\n";
     //echo " <tr>\n";
     //echo "  <th align=\"left\" nowrap=\"nowrap\" colspan=\"". ($maxcol +1) ."\">&nbsp;". $lang['forphrase'] ."\n";
     //cal_options($this->team,$this->teamname);
     //echo "  </th>\n";
     //echo " </tr>\n";
     echo " <tr height=\"35\">\n";
     echo " <td nowrap=\"nowrap\" width=\"20\" align=\"center\">";
     echo $this->pref->menulink($callink ."calendar_day&t=".$last_day->getYYYYMMDD(),$this->pref->getImage(left,'list'),$last_day->getDate());
	 echo " </td>\n";
	 echo " <td align=\"center\" nowrap=\"nowrap\" class=\"calhead\">\n";
	 //echo "&nbsp;". strftime($mod_strings['LBL_DATE_TITLE'],$from->ts) ."&nbsp;(". $mod_strings['LBL_WEEK']." ". $this->pref->menulink($callink ."calendar_week&t=".Date("Ymd",$from->ts), $wn ."/". $yy, $wn ."/". $yy ) .")&nbsp;";
	 echo "&nbsp;". strftime($mod_strings['LBL_DATE_TITLE'],$from->ts) ."&nbsp;";
	 echo " </td>\n";
	 echo " <td nowrap=\"nowrap\" width=\"20\" align=\"center\">\n";
     echo $this->pref->menulink($callink ."calendar_day&t=".$next_day->getYYYYMMDD(),$this->pref->getImage(right,'list'),$next_day->getDate());
     echo "</td></tr>\n";
	 echo "</table>\n";
	 echo "<tr><td>\n";
	 echo "<table border=\"0\" cellpadding=\"1\" cellspacing=\"0\" width=\"100%\">\n";
     for ($i = -1 ; $i <24 ; $i++ ) {
       echo " <tr>\n";

       
       /*if ( $i == -1 ) {
         echo  $this->pref->menulink($callink . "app_new&t=".$this->t, "NOTIME",$mod_strings['LBL_NEW_APPNT_INFO']);
       } else {
         echo  $this->pref->menulink($callink . "app_new&start=". $this->t.sprintf("%02d",$i)."00&amp;end=".$this->t.sprintf("%02d",$i)."59" ,sprintf("%02d", $i).":00",$mod_strings['LBL_NEW_APPNT_INFO']);
       }*/
	  
       if ( $i == -1 ) {
       echo " <th id=\"time_".$this->t."\" class=\"daytime\" width=\"10%\" align=\"right\" valign=\"top\">&nbsp;\n";
       echo  $this->pref->menulink("javascript:showCreateBox('".$this->t."')", "NOTIME",$mod_strings['LBL_NEW_APPNT_INFO']);
       } else {
	   	 echo " <th id=\"time_".$this->t.sprintf("%02d",$i)."00\" class=\"daytime\" width=\"10%\" align=\"right\" valign=\"top\">\n";
         echo  $this->pref->menulink("javascript:showCreateBox('".$this->t.sprintf("%02d",$i)."00','".$this->t.sprintf("%02d",$i)."59')", sprintf("%02d", $i).":00",$mod_strings['LBL_NEW_APPNT_INFO']);
       }
       echo "&nbsp;</th>\n";
       
       for ($c = 0 ; $c < $maxcol ; $c++ ) {
         if ( isset ( $table[$i][$c] ) ) { 
           if ( is_object ( $table[$i][$c] ) ) {
             echo " <td class=\"line". (1+($i % 2)) ."\" valign=\"top\" rowspan=\"". $rowspan[$i][$c]."\">";
             //echo "<img height=\"1\" width=\"100%\" src=\"". $image_path ."black.png\" alt=\"--------\"/>";
            $color = "";
	          $username=$table[$i][$c]->creator;
	          if ($username!=""){
              $query="SELECT cal_color FROM users where user_name = '$username'";
           
  		        $result=$adb->query($query);
  		        if($adb->getRowCount($result)!=0){
  			         $res = $adb->fetchByAssoc($result, -1, false);
  				       $usercolor = $res['cal_color'];
  				       $color="style=\"background: ".$usercolor.";\"";
  		        }
             }
             echo "<table border=\"0\" cellpadding=\"3\" cellspacing=\"0\" class=\"event\" $color>\n";
             echo $table[$i][$c]->formatted();
             echo " </table></td>\n";
           } else if ( $table[$i][$c] = -1 ) {
             # SKIP occupied by rowspan
           }
         } else {
           echo "<td class=\"line". (1+($i % 2)) ."\" valign=\"top\">";
		   //echo "<img height=\"1\" width=\"100%\" src=\"". $image_path ."black.png\" alt=\"--------\" />";
		   echo "&nbsp;</td>\n";
         }
       }
       echo " </tr>\n";
     }
	 echo " </table>\n";
//	 echo "</td></tr></table>\n";
	 echo "</td></tr>\n";
	 echo "<tr><td>\n";
	 echo "<table class=\"navigate\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">\n";
     //echo " <tr>\n";
     //echo "  <th align=\"left\" nowrap=\"nowrap\" colspan=\"". ($maxcol +1) ."\">&nbsp;". $lang['forphrase'] ."\n";
     //cal_options($this->team,$this->teamname);
     //echo "  </th>\n";
     //echo " </tr>\n";
     echo " <tr height=\"30\">\n";
     echo " <td nowrap=\"nowrap\" width=\"20\" align=\"center\">";
     echo $this->pref->menulink($callink ."calendar_day&t=".$last_day->getYYYYMMDD(),$this->pref->getImage(left,'list'),$last_day->getDate());
	 echo " </td>\n";
	 echo " <td align=\"center\" nowrap=\"nowrap\">&nbsp;\n";
 	 echo " </td>\n";
	 echo " <td nowrap=\"nowrap\" width=\"30\" align=\"center\">\n";
     	 echo $this->pref->menulink($callink ."calendar_day&t=".$next_day->getYYYYMMDD(),$this->pref->getImage(right,'list'),$next_day->getDate());
     	 echo "</td></tr>\n";
	 echo "</table>\n";
	 echo "</td></tr></table>\n";

         echo "</td>\n";
         echo "<td valign=top align=center style=\"padding-left:10px;padding-right:10px\">\n";
	 
         $d = Date("d",$ts);
         $m = Date("n",$ts);
         $y = Date("Y",$ts);
 
         include 'modules/Calendar/minical.php';
         echo "</td></tr></table>\n";
     #echo $this->DataTableEnd();
     hiddenFormElements();
     echo $this->getHidden();
     echo "</form>\n";
   }
   /**
    * navigate
    */
   Function navigate() {
   }
   /**
    * prepare
    */
   Function prepare() {
     global $tutos, $lang,$msg;

     $this->name = $mod_strings['LBL_MODULE_NAME'];
     #if ( ! $this->user->feature_ok(usecalendar,PERM_SEE) ) {
     #  $msg .= sprintf($lang['Err0022'],"'". $this->name ."'");
     #  $this->stop = true;
     #}
     $this->teamname = "";
     $this->t = Date("Ymd");

     if ( isset ($_GET['t']) ) {
       $this->t = $_GET['t'];
     }
     if ( isset ($_GET['m']) ) {
       $this->m = $_GET['m'];
     }
     if ( isset ($_GET['d']) ) {
       $this->d = $_GET['d'];
     }
     if ( isset ($_GET['y']) ) {
       $this->y = $_GET['y'];
     }
     $this->addHidden("t", $this->t);
     $this->uids = cal_parse_options($this->pref,$this->teamname);
     #$this->team = $this->user->get_prefteam();
     # menu
     #$m = appointment::getSelectLink($this->user);
     #$m[category][] = "obj";
     #$this->addmenu($m);
     #$m = appointment::getAddLink($this->user,$this->user);
     #$this->addMenu($m);
   }
 }


# info($t,$this->user->get_prefteam(),$teamname,$uids);

 $l = new calendar_day($current_user);
 $l->display();
 #$dbconn->Close();
?>
<!--
    CVS Info:  $Id$
    $Author$
-->
<script language="JavaScript">
var createBoxObj;
function showCreateBox(start,end) {
	var yyyy=start.substring(0,4)
	var mm=start.substring(4,6)
	var dd=start.substring(6,8)
	<?php
		$a ='';
		$b ='';
		$c ='';
		$dat_fmt = $current_user->date_format;
        	if($dat_fmt == '')
        	{
                	$dat_fmt = 'dd-mm-yyyy';
        	}
		list($a,$b,$c)=split("-",$dat_fmt);
	?>
	var event_st_hr=start.substring(8,10)
	var event_st_min=start.substring(10,12)

	document.appSave.date_start.value=<?php echo $a;?>+"-"+<?php echo $b;?>+"-"+<?php echo $c;?>;
	document.appSave.due_date.value=<?php echo $a;?>+"-"+<?php echo $b;?>+"-"+<?php echo $c;?>;	
	if(!end)
	{
	       document.appSave.time_start.value='';
	       document.appSave.notime.value='1';
	}
	else
	{
	       document.appSave.time_start.value=event_st_hr+":"+event_st_min
	}

	createBoxObj=getObj("createBox")
	var currObj=getObj("time_"+start)
	
	var left=findPosX(currObj)
	var top=findPosY(currObj)
	var width=currObj.offsetWidth
	var height=createBoxObj.offsetHeight
	
	if (browser_ie)	{
		if (top+height>document.body.offsetHeight+document.body.scrollTop)
			document.body.scrollTop+=50
		
		if (left+width>document.body.offsetWidth+document.body.scrollLeft)
			document.body.scrollLeft+=50
	} else if (browser_nn4 || browser_nn6) {
		topHeight=scrY-pgeY
		leftWidth=scrX-pgeX
		bodyHeight=window.screen.height-topHeight
		bodyWidth=window.screen.width-leftWidth
		
		if (top+height>bodyHeight+window.scrollY)
			document.body.scrollTop+=50
		
		if (left+width>bodyHeight+window.scrollX)
			document.body.scrollLeft+=50
	}

	createBoxObj.style.top=top+"px"
	createBoxObj.style.left=parseInt(left+width)+"px"

	if (browser_ie)
		document.appSave.subject.focus()	
	else if (browser_nn4 || browser_nn6)
		window.setTimeout("setCursor()",50)
}
function setCursor() {
	document.appSave.subject.focus()
}
function hideCreateBox(ev) {
	if (browser_ie)	var obj=window.event.srcElement
	else if (browser_nn4 || browser_nn6) var obj=ev.target

	if (obj.id=="closeicon") {
		createBoxObj.style.top="-1000px"
		createBoxObj.style.left="-1000px"
	} else {
		if (createBoxObj) {
			if (createBoxObj.style.left.indexOf("-")<0) {
				var innerElement=false
				if (document.getElementById || document.all) {
					while (obj.offsetParent) {
						if (obj.offsetParent==getObj("createBox"))
							innerElement=true
						obj = obj.offsetParent;
					}
				}
				
				if (innerElement==false) {
					createBoxObj.style.top="-1000px"
					createBoxObj.style.left="-1000px"
				}
			}
		}
	}
}

document.body.onclick=function hideCB(event) {
	hideCreateBox(event,"")
}

var scrX=0,scrY=0,pgeX=0,pgeY=0;
if (browser_nn4 || browser_nn6)
	document.addEventListener("click",popUpListener,true)

function popUpListener(ev) {
	if (browser_nn4 || browser_nn6) {
		scrX=ev.screenX
		scrY=ev.screenY
		pgeX=ev.pageX
		pgeY=ev.pageY
	}
}
</script>
