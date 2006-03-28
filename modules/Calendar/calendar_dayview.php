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
 require_once('modules/Calendar/CalendarCommon.php');

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
 $html = getHeaderTab($t,'day');
 echo $html;
 
 include_once $calpath .'webelements.p3';
 include_once $calpath .'permission.p3';
 include_once $calpath .'preference.pinc';
 include_once $calpath .'appointment.pinc';
 include_once $calpath .'addEventUI.php';
require_once('modules/Calendar/UserCalendar.php');

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
   }
  
   /**
     * Function to get UI&Activities for each hour
     * @param $hour -- hour(in am/pm) :: Type string
     * @param $maxcol -- no. of events in particular hour :: Type integer
     * @param $table -- events in array format :: Type Array
     * @param $i -- count :: Type integer
     * Constructs UI for each hour in html table format and calls formatted() to get activities for that hour
     * returns the html table in string format
     */
    function getHourList($hour,$maxcol,$table,$i)
     {
                global $adb;
                echo "<a name=".$hour.">";
                echo "
                        <table border=0 cellspacing=0 cellpadding=5 width=100% class=\"calDayHour\">
                        <tr>
                          <td width=10% class=\"calDayHourCell\" valign=top align=right>".$hour."</td>
                          <td width=90% valign=top>
                              <table border=0 cellspacing=0 cellpadding=2 width=100%>
                                <tr>
		 <td onMouseOver=\"gshow('".$hour."')\"  onMouseOut=\"ghide('".$hour."')\"  valign=top style=\"border-left:1px dashed #dadada;height:50px\" width=90%>";
                for ($c = 0 ; $c < $maxcol ; $c++ ) {
                 if ( isset ( $table[$i][$c] ) ) {
                   if ( is_object ( $table[$i][$c] ) ) {
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
                        echo "<table border=\"0\" cellpadding=\"0\" cellspacing=\"2\" class=\"calEventNormal\" width=100%>\n";
                        echo $table[$i][$c]->formatted();
                        echo " </table>";
                       } else if ( $table[$i][$c] = -1 ) {
                          # SKIP occupied by rowspan
			   }
                     }
             }
                echo "<td onMouseOver=\"gshow('".$hour."')\"  onMouseOut=\"ghide('".$hour."')\"  width=10%>
                                 <div id=".$hour." style=\"display:none\">
                                 <table border=\"0\">
                                   <tr>
                                      <td><a onClick=\"gshow('addEvent')\" href=\"javascript:void(0)\"><img src=\"themes/blue/images/cal16x16CallAdd.jpg\" alt=\"Add Call Event\" title=\"Add Call Event\" border=\"0\"></a></td>
                                      <td><a onClick=\"gshow('addEvent')\" href=\"javascript:void(0)\"><img src=\"themes/blue/images/cal16x16MeetingAdd.jpg\" alt=\"Add Meeting Event\" title=\"Add Meeting Event\" border=\"0\"></a></td>
                                      <td><a onClick=\"gshow('addEvent')\" href=\"javascript:void(0)\"><img src=\"themes/blue/images/cal16x16ToDoAdd.jpg\" alt=\"Add To Do\" title=\"Add To Do\" border=0></a></td>
                                   </tr>
                                  </table>
                                 </div>
                               </td></tr></table>
                           </td></tr></table>
                        ";

         }




   /**
    * the data display part
    */
   Function info() {
     global $lang,$tutos,$callink,$calpath,$image_path,$mod_strings,$current_user,$adb;
?>
<script type="text/javascript" language="Javascript" src="include/js/general.js"></script>
<script type="text/javascript" language="Javascript" src="modules/Calendar/script.js"></script>
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
</script>
<?php
	if(isset($_REQUEST['display_date']) && $_REQUEST['display_date'] !="")
        {
                $display_date=$_REQUEST['display_date'];
        }
        else
        {
                $display_date= date("Y-m-d",mktime(0,0,0,date("n"),(date("j")),date("Y")));
        }

        $date_val=explode("-",$display_date);

        $year=$date_val[0];
        $month=$date_val[1];
        $day=$date_val[2];

        $month_in_text=date("F", mktime(0, 0, 0, $month, $day, $year));
        $day_in_words=date("l", mktime(0, 0, 0, $month, $day, $year));

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
     for ($i = -1 ; $i < 24 ; $i++ ) {
       $maxcol[$i] = max($maxcol[$i],count($table[$i]));
     }
	//New UI-integrated by minnie
     $calendarheader = getCalendarHeader($last_day,$next_day,"day",$from->ts,$this->pref);
     echo $calendarheader;
	echo "<tr><td>";
	echo "<!-- calendar list -->
              <table border=\"0\" cellspacing=\"0\" cellpadding=\"10\" width=\"100%\" class=\"calDisplay\">
              <tr><td align=center >";
	echo "<div class=\"calDiv\" >";
	for ($i = 0; $i <24 ; $i++ )
	{
		if($i == 0)
			$this->getHourList('12am',$maxcol[$i],$table,$i);
		if($i>0 && $i<12)
			$this->getHourList($i.'am',$maxcol[$i],$table,$i);
		if($i == 12)
			$this->getHourList('12pm',$maxcol[$i],$table,$i);
		if($i>12 && $i<24)
			$this->getHourList(($i - 12).'pm',$maxcol[$i],$table,$i);
	}
	 echo "</div>";
	 echo "</td></tr></table>\n";
	 echo "</tr></td>\n";
	 echo "<tr><td>
		<table border=0 cellspacing=0 cellpadding=0 width=100% class=\"calBottomBg\">
                 <tr>
                    <td><img src=\"";
	echo $image_path."calBottomLeft.gif\"></td>";
	echo "<td width=100%><img src=\"";
	echo $image_path."calBottomBg.gif\"></td>";
	echo "<td align=right><img src=\"";
	echo $image_path."calBottomRight.gif\"></td>";
	echo "</tr></table>";
	echo "</td></tr></table></form>";
	echo "</tr></table>";
        echo "</td></tr></table>";
	//end
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
   }
 }

 $l = new calendar_day($current_user);
 $l->display();
?>
<!--
    CVS Info:  $Id: calendar_day.php,v 1.22 2005/12/16 18:52:20 jerrydgeorge Exp $
    $Author: jerrydgeorge $
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
