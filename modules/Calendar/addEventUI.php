<?php
require_once('include/utils/CommonUtils.php');
require_once('modules/Activities/Activity.php');
require_once('modules/Calendar/Calendar.php');

 global $theme;
 $theme_path="themes/".$theme."/";
 $image_path=$theme_path."images/";
 require_once ($theme_path."layout_utils.php");
 global $mod_strings,$app_strings,$current_user;
 $userDetails=getOtherUserName($current_user->id);
 require_once("modules/Emails/mail.php");
 $to_email = getUserEmailId('id',$current_user->id);
 $date_format = parse_calendardate($app_strings['NTC_DATE_FORMAT']);
$mysel= $_GET['view'];
$calendar_arr = Array();
$calendar_arr['IMAGE_PATH'] = $image_path;
if(empty($mysel))
{
        $mysel = 'day';
}
$date_data = array();
if ( isset($_REQUEST['day']))
{

        $date_data['day'] = $_REQUEST['day'];
}

if ( isset($_REQUEST['month']))
{
        $date_data['month'] = $_REQUEST['month'];
}

if ( isset($_REQUEST['week']))
{
        $date_data['week'] = $_REQUEST['week'];
}

if ( isset($_REQUEST['year']))
{
        if ($_REQUEST['year'] > 2037 || $_REQUEST['year'] < 1970)
        {
                print("<font color='red'>Sorry, Year must be between 1970 and 2037</font>");
                exit;
        }
        $date_data['year'] = $_REQUEST['year'];
}


if(empty($date_data))
{
	$data_value=date('Y-m-d H:i:s');
        preg_match('/(\d{4})-(\d{2})-(\d{2}) (\d{2}):(\d{2}):(\d{2})/',$data_value,$value);
        $date_data = Array(
                'day'=>$value[3],
                'month'=>$value[2],
                'year'=>$value[1],
                'hour'=>$value[4],
                'min'=>$value[5],
        );

}
$calendar_arr['calendar'] = new Calendar($mysel,$date_data);
$calendar_arr['view'] = $mysel;
$calendar_arr['calendar']->hour_format = $current_user->hour_format;

 function getTimeCombo($format,$bimode)
 {
	$combo = '';
	if($format == 'am/pm')
	{
		$combo .= '<select class=small name="'.$bimode.'hr" id="'.$bimode.'hr">';
		for($i=1;$i<=12;$i++)
		{
			if($i == $selvalue)
				$selected = 'selected';
			else
				$selected = '';
			if($i <= 9 && strlen(trim($i)) < 2)
                        {
                                $hrvalue= '0'.$i;
                        }
			elseif($i == 12) $hrvalue = '00';
			else $hrvalue= $i;
			$combo .= '<option value="'.$hrvalue.'" '.$selected.'>'.$i.'</option>';
		}
		$combo .= '</select>&nbsp;';
		$combo .= '<select name="'.$bimode.'min" id="'.$bimode.'min" class=small>';
		for($i=0;$i<12;$i++)
                {
			$minvalue = 5;
			if($i == $selmin)
                                $minselected = 'selected';
                        else
                                $minselected = '';
			$value = $i*5;
			if($value <= 9 && strlen(trim($value)) < 2)
                        {
                                $value= '0'.$value;
                        }
			else $value= $value;
			$combo .= '<option value="'.$value.'" '.$minselected.'>'.$value.'</option>';
		}
		$combo .= '</select>&nbsp;';
		$combo .= '<select name="'.$bimode.'fmt" id="'.$bimode.'fmt" class=small>';
		if($selmin == 'am')
		{
			$amselected = 'selected';
			$pmselected = '';
		}
		else
		{
			$pmselected = 'selected';
                        $amselected = '';
		}
		$combo .= '<option value="am" '.$amselected.'>AM</option>';
		$combo .= '<option value="pm" '.$pmselected.'>PM</option>';
		$combo .= '</select>';
		
	}
	else
	{
		$combo .= '<select name="'.$bimode.'hr" id="'.$bimode.'hr" class=small>';
		for($i=0;$i<=23;$i++)
		{
			if($i == $selvalue)
                                $selected = 'selected';
			else
                                $selected = '';
                        if($i <= 9 && strlen(trim($i)) < 2)
                        {
                                $hrvalue= '0'.$i;
                        }
			else $hrvalue = $i;
			$combo .= '<option value="'.$hrvalue.'" '.$selected.'>'.$i.'</option>';
		}
		$combo .= '</select>&nbsp;';
		$combo .= '<select name="'.$bimode.'min" id="'.$bimode.'min" class=small>';
                for($i=0;$i<12;$i++)
                {
                        $minvalue = 5;
                        if($i == $selmin)
                                $minselected = 'selected';
                        else
                                $minselected = '';
                        $value = $i*5;
                        if($value <= 9 && strlen(trim($value)) < 2)
                        {
                                $value= '0'.$value;
                        }
			else $value=$value;
                        $combo .= '<option value="'.$value.'" '.$minselected.'>'.$value.'</option>';
                }
                $combo .= '</select>&nbsp;Hr<input type="hidden" name="'.$bimode.'fmt" id="'.$bimode.'fmt">';
	}
	return $combo;
		
 }

?>
       
	<!-- Add Event DIV starts-->
	<script language="JavaScript" type="text/javascript" src="general.js"></script>	

	<link rel="stylesheet" type="text/css" media="all" href="jscalendar/calendar-win2k-cold-1.css">
	<script type="text/javascript" src="jscalendar/calendar.js"></script>
	<script type="text/javascript" src="jscalendar/lang/calendar-<? echo $app_strings['LBL_JSCALENDAR_LANG'] ?>.js"></script>
	<script type="text/javascript" src="jscalendar/calendar-setup.js"></script>

	<div class="calAddEvent" style="display:none" id="addEvent" align=center> 
	<form name="appSave" onSubmit="return check_form();" method="POST" action="index.php">
	<input type="hidden" name="module" value="Activities">
	<input type="hidden" name="activity_mode" value="Events">
	<input type="hidden" name="action" value="Save">
	<input type="hidden" name="return_action" value="index">
	<input type="hidden" name="return_module" value="Calendar">
	<input type="hidden" name="view" value="<? echo $calendar_arr['view'] ?>">
	<input type="hidden" name="hour" value="<? echo $calendar_arr['calendar']->date_time->hour ?>">
	<input type="hidden" name="day" value="<? echo $calendar_arr['calendar']->date_time->day ?>">
	<input type="hidden" name="month" value="<? echo $calendar_arr['calendar']->date_time->month ?>">
	<input type="hidden" name="year" value="<? echo $calendar_arr['calendar']->date_time->year ?>">
	<input type="hidden" name="record" value="">
	<input type="hidden" name="duration_hours" value="0">
	<input type="hidden" name="assigned_user_id" value="<? echo $current_user->id ?>">
	<input type="hidden" name="assigntype" value="U">
	<input type="hidden" name="duration_minutes" value="0">
	<input type="hidden" name="time_start" id="time_start">
	<input type="hidden" name="time_end" id="time_end">
	<input type="hidden" name="eventstatus" value="Planned">
	<input type="hidden" name="recurringtype" value="">
	<input type="hidden" name="set_reminder" value="">
	<input type=hidden name="inviteesid" id="inviteesid" value="">
		<table border=0 cellspacing=0 cellpadding=5 width=100% class="addEventHeader">
		<tr>
			<td class="lvtHeaderText"><? echo $mod_strings['LBL_ADD_EVENT']?></b></td>
			<td align=right>
				<a href="javascript:ghide('addEvent');"><img src="<?echo $image_path?>close.gif" border="0"  align="absmiddle" /></a></td>
		</tr>
		</table>
		
		<table border=0 cellspacing=0 cellpadding=5 width=90% >
		<tr>
			<td nowrap  width=20%><b><?echo $mod_strings['LBL_EVENTTYPE']?> :</b></td>
			<td width=80%>
				<table>
					<tr>
					<td><input type="radio" name='activitytype' value='Call' onclick='document.appSave.module.value="Activities";' style='vertical-align: middle;' checked></td><td><?echo $mod_strings['LBL_CALL']?></td><td style="width:10px">
					<td><input type="radio" name='activitytype' value='Meeting' style='vertical-align: middle;' onclick='document.appSave.module.value="Activities";'></td><td><?echo $mod_strings['LBL_MEET']?></td><td style="width:20px">
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td nowrap ><b><?echo $mod_strings['LBL_EVENTNAME']?> :</b></td>
			<td><input name="subject" type="text" class="textbox" style="width:90%"></td>
		</tr>
		</table>
		<br>
		<table border=0 cellspacing=0 cellpadding=5 width=90% align=center style="border-top:1px dotted silver">
		<tr>
			<td >
				<table border=0 cellspacing=0 cellpadding=2 width=100%>
				<tr>
				<td width=50% valign=top style="border-right:1px solid #dddddd">
					<table border=0 cellspacing=0 cellpadding=2 width=90%>
					<tr><td colspan=3 ><b><?echo $mod_strings['LBL_EVENTSTAT']?></b></td></tr>
				        <tr><td colspan=3>
						<? echo  getTimeCombo($calendar_arr['calendar']->hour_format,'start');?>
					</td></tr>
                                        <tr><td>
						<input type="text" name="date_start" id="jscal_field_date_start" class="textbox" style="width:90px"></td><td width=50%><img border=0 src="<?echo $image_path?>btnL3Calendar.gif" alt="Set date.." title="Set date.." id="jscal_trigger_date_start">
						<script type="text/javascript">
                					Calendar.setup ({
								inputField : "jscal_field_date_start", ifFormat : "<?php  echo $date_format; ?>", showsTime : false, button : "jscal_trigger_date_start", singleClick : true, step : 1
									})
     						        </script>
					</td></tr>
					</table>
				</td>
				<td width=50% valign=top >
					<table border=0 cellspacing=0 cellpadding=2 width=90%>
					<tr><td colspan=3><b><?echo $mod_strings['LBL_EVENTEDAT']?></b></td></tr>
				        <tr><td colspan=3>
                                                <? echo getTimeCombo($calendar_arr['calendar']->hour_format,'end');?>
					</td></tr>
				        <tr><td>
						<input type="text" name="due_date" id="jscal_field_due_date" class="textbox" style="width:90px"></td><td width=100%><img border=0 src="<?echo $image_path?>btnL3Calendar.gif" alt="Set date.." title="Set date.." id="jscal_trigger_due_date">
					<script type="text/javascript">
                                                        Calendar.setup ({
                                                                inputField : "jscal_field_due_date", ifFormat : "<?php echo $date_format; ?>", showsTime : false, button : "jscal_trigger_due_date", singleClick : true, step : 1
                                                                        })
                                                        </script>
					</td></tr>
					</table>
				</td>
				</tr>
				</table>
			</td>
		</tr>
		</table>


		<!-- Alarm, Repeat, Invite starts-->
		<br>
		<table border=0 cellspacing=0 cellpadding=0 width=95% align=center>
		<tr>
			<td>
				<table border=0 cellspacing=0 cellpadding=3 width=100%>
				<tr>
					<td class="dvtTabCache" style="width:10px" nowrap>&nbsp;</td>
					<td id="cellTabInvite" class="dvtSelectedCell" align=center nowrap><a href="#" onClick="switchClass('cellTabInvite','on');switchClass('cellTabAlarm','off');switchClass('cellTabRepeat','off');ghide('addEventAlarmUI');gshow('addEventInviteUI',document.appSave.date_start.value,document.appSave.due_date.value,document.appSave.starthr.value,document.appSave.startmin.value,document.appSave.startfmt.value,document.appSave.endhr.value,document.appSave.endmin.value,document.appSave.endfmt.value);ghide('addEventRepeatUI');"><?php echo $mod_strings['LBL_INVITE']?></a></td>
					<td class="dvtTabCache" style="width:10px">&nbsp;</td>
					<td id="cellTabAlarm" class="dvtUnSelectedCell" align=center nowrap><a href="#" onClick="switchClass('cellTabInvite','off');switchClass('cellTabAlarm','on');switchClass('cellTabRepeat','off');gshow('addEventAlarmUI',document.appSave.date_start.value,document.appSave.due_date.value,document.appSave.starthr.value,document.appSave.startmin.value,document.appSave.startfmt.value,document.appSave.endhr.value,document.appSave.endmin.value,document.appSave.endfmt.value);ghide('addEventInviteUI');ghide('addEventRepeatUI');"><?php echo $mod_strings['LBL_REMINDER']?></a></td>
					<td class="dvtTabCache" style="width:10px">&nbsp;</td>
					<td id="cellTabRepeat" class="dvtUnSelectedCell" align=center nowrap><a href="#" onClick="switchClass('cellTabInvite','off');switchClass('cellTabAlarm','off');switchClass('cellTabRepeat','on');ghide('addEventAlarmUI');ghide('addEventInviteUI');gshow('addEventRepeatUI',document.appSave.date_start.value,document.appSave.due_date.value,document.appSave.starthr.value,document.appSave.startmin.value,document.appSave.startfmt.value,document.appSave.endhr.value,document.appSave.endmin.value,document.appSave.endfmt.value);"><?php echo $mod_strings['LBL_REPEAT']?></a></td>
					<td class="dvtTabCache" style="width:100%">&nbsp;</td>
				</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td width=100% valign=top align=left class="dvtContentSpace" style="padding:10px;height:120px">
			<!-- Invite UI -->
				
				<DIV id="addEventInviteUI" style="display:block;width:100%">
				<table border>
				<table border=0 cellspacing=0 cellpadding=2 width=100%>
				<tr>
					<td valign=top> 
						<table border=0 cellspacing=0 cellpadding=2 width=100%>
						<tr>
							<td colspan=3>
								<ul style="padding-left:20px">
								<li><?echo $mod_strings['LBL_INVITE_INST1']?> 
								<li><?echo $mod_strings['LBL_INVITE_INST2']?>
								</ul>
							</td>
						</tr>
						<tr>
							<td><b><?echo $mod_strings['LBL_AVL_USERS']?></b></td>
							<td>&nbsp;</td>
							<td><b><?echo $mod_strings['LBL_SEL_USERS']?></b></td>
						</tr>
						<tr>
							<td width=40% align=center valign=top>
							<select name="availableusers" id="availableusers" class=small size=5 multiple style="height:70px;width:100%">
							<?php
								foreach($userDetails as $id=>$name)
								{
									if($id != '')
										echo "<option value=".$id.">".$name."</option>";
									}
							?>
								</select>
								
							</td>
							<td width=20% align=center valign=top>
								<input type=button value="<?php echo $mod_strings['LBL_ADD_BUTTON'] ?> >>" class=small style="width:100%" onClick="addColumn()"><br>
								<input type=button value="<< <?php echo $mod_strings['LBL_RMV_BUTTON'] ?> " class=small style="width:100%" onClick="delColumn()">
							</td>
							<td width=40% align=center valign=top>
								<select name="selectedusers" id="selectedusers" class=small size=5 multiple style="height:70px;width:100%">
								</select>
								<div align=left><?echo $mod_strings['LBL_SELUSR_INFO']?>
								</div>
							
							</td>
						</tr>
						</table>
							
					
					</td>
				</tr>
				</table>
				</DIV>
			
			<!-- Reminder UI -->
				<DIV id="addEventAlarmUI" style="display:none;width:100%">
				<table>
					<tr><td><?echo $mod_strings['LBL_SENDREMINDER']?></td><td><input name="remindercheck" type="checkbox" onClick="showhide('reminderOptions')">
					</td></tr>
				</table>
				<DIV id="reminderOptions" style="display:none;width:100%">
				<table border=0 cellspacing=0 cellpadding=2  width=100%>
				<tr>
					<td nowrap align=right width=20% valign=top>
						<b><?echo $mod_strings['LBL_RMD_ON']?> : </b>
					</td>
					<td width=80%>
						<table border=0>
						<tr>
						<td colspan=2>
							<select class=small name="remdays">
							<?php
								for($m=0;$m<=31;$m++)
								{
							?>
									<option value="<?echo $m?>"><?echo $m?></option>
							<?
								}
							?>
							</select>days 
							<select class=small name="remhrs">
                                                        <?php
                                                                for($h=0;$h<=23;$h++)
                                                                {
                                                        ?>
                                                                        <option value="<?echo $h?>"><?echo $h?></option>
                                                        <?
                                                                }
                                                        ?>
                                                        </select>hours
							<select class=small name="remmin">
                                                        <?php
                                                                for($min=1;$min<=59;$min++)
                                                                {
                                                        ?>
                                                                        <option value="<?echo $min?>"><?echo $min?></option>
                                                        <?
                                                                }
                                                        ?>
                                                        </select><?echo $mod_strings['LBL_BEFORE']?>
						</td>
						</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td nowrap align=right>
					<?echo $mod_strings['LBL_SDRMD']?> :
					</td>
					<td >
					<input type=text class=textbox style="width:90%" value="<? echo $to_email ?>">
					</td>
				</tr>
				</table>
				</DIV>
				</DIV>
			<!-- Repeat UI -->
				<div id="addEventRepeatUI" style="display:none;width:100%">
				<table border=0 cellspacing=0 cellpadding=2  width=100%>
				<tr>
					<td nowrap align=right width=20% valign=top>
					<strong><?echo $mod_strings['LBL_REPEAT']?> :</strong>
					</td>
					<td nowrap width=80% valign=top>
						<table border=0 cellspacing=0 cellpadding=0>
						<tr>
							<td width=20><input type="checkbox" name="recurringcheck" onClick="showhide('repeatOptions')"></td>
							<td colspan=2><?echo $mod_strings['LBL_ENABLE_REPEAT']?></td>
						</tr>
						<tr>
							<td colspan=2>
							<div id="repeatOptions" style="display:none">
								<table border=0 cellspacing=0 cellpadding=2>
								<tr>
								<td><?echo $mod_strings['LBL_REPEATEVENT']?></td>
								<!--td><input type="text" class="textbox" style="width:20px" value="2" ></td-->
								<td><select class=small name="repeat_option">
									<option value="Daily">Daily</option>
									<option value="Weekly">Weekly</option>
									<option value="Monthly">Monthly</option>
									<option value="Yearly">Yearly</option>
								<!--option onClick="ghide('repeatWeekUI');ghide('repeatMonthUI');">Day(s)</option>
								<option onClick="gshow('repeatWeekUI',document.appSave.date_start.value,document.appSave.due_date.value,document.appSave.starthr.value,document.appSave.startmin.value,document.appSave.startfmt.value,document.appSave.endhr.value,document.appSave.endmin.value,document.appSave.endfmt.value);ghide('repeatMonthUI');">Week(s)</option>
								<option onClick="gshow('repeatMonthUI',document.appSave.date_start.value,document.appSave.due_date.value,document.appSave.starthr.value,document.appSave.startmin.value,document.appSave.startfmt.value,document.appSave.endhr.value,document.appSave.endmin.value,document.appSave.endfmt.value);ghide('repeatWeekUI');">Month(s)</option>
								<option onClick="ghide('repeatWeekUI');ghide('repeatMonthUI');";>Year</option-->
								</select></td>
								</tr>
								</table>
								
								<!--div id="repeatWeekUI" style="display:none;">
									<table border=0 cellspacing=0 cellpadding=2>
									<tr>
										<td><input type="checkbox"></td><td>Sun</td>
										<td><input type="checkbox"></td><td>Mon</td>
										<td><input type="checkbox"></td><td>Tue</td>
										<td><input type="checkbox"></td><td>Wed</td>
										<td><input type="checkbox"></td><td>Thu</td>
										<td><input type="checkbox"></td><td>Fri</td>
										<td><input type="checkbox"></td><td>Sat</td>
									</tr>
									</table>
								</div>
								<div id="repeatMonthUI" style="display:none;">
									<table border=0 cellspacing=0 cellpadding=2>
									<tr>
									<td>
										<table border=0 cellspacing=0 cellpadding=2>
										<tr>
										<td><input type="radio" checked name="repeatMonth"></td><td>on</td><td><input type="text" class=textbox style="width:20px" value="2"></td><td>day of the month</td></tr>
										</table>
									</td>
									</tr>
									<tr>
									<td>
										<table border=0 cellspacing=0 cellpadding=2>
										<tr>
										<td><input type="radio" name="repeatMonth"></td><td>on</td><td><select class=small><option>First</option><option>Last</option></td><td><select class=small><option>Monday</option><option>Tuesday</option><option>Wednesday</option><option>Thursday</option><option>Friday</option><option>Saturday</option></select></td></tr>
										</table>
									</td>
									</tr>
									</table>
								</div-->
							</div>
								
							</td>
						</tr>
						</table>
					</td>
				</tr>
				</table>
				</div>
					
	
			</td>
		</tr>
		</table>
		<!-- Alarm, Repeat, Invite stops-->

<br>

		
		
		<div id="addEventAdvanced" style="display:none">
			<!-- more options-->
			<table border=0 cellspacing=0 cellpadding=5 width=90% align=center style="border-top:1px dotted silver">
			<tr>
				<td valign=top>
					<table border=0 cellspacing=0 cellpadding=2>
					<tr>
						<td><input type="checkbox" id="cboxRepeatEvent" name="repeatEvent" onClick="showhideRepeat('cboxRepeatEvent','repeatOptions');showhideRepeat('cboxRepeatEvent','stopRepeatOptions')"></td>
						<td>Repeat this event </td>
						<td><select id="repeatOptions" style="display:none" class=small><option><? echo $mod_strings['LBL_EVERYDAY']?></option><option><? echo $mod_strings['LBL_EVERYWEEK']?></option><option><? echo $mod_strings['LBL_EVERYMON']?></option></select></td>
					</tr>
					</table>
					<table border=0 cellspacing=0 cellpadding=2 id="stopRepeatOptions" style="display:none">
					<tr>
						<td width=40 align=right><input type="checkbox" name="stopRepeat" ></td>
						<td>Stop repeat on </td>
						<td>
							<table border=0 cellspacing=0 cellpadding=0 >
							<tr>
								<td><input type="text" class="textbox" style="width:70px"></td>
								<td><img src="../images/btnL3Calendar.gif" alt="Select date" title="Select date"></td>
							</tr>
							</table>
						</td>
					</tr>
					</table>
				</td>
			</tr>
			</table>
			
		</div>
		
		<table border=0 cellspacing=0 cellpadding=5 width=100% class="addEventFooter">
		<tr>
			<td valign=top></td>
			<td  align=right>
				<input title='Save [Alt+S]' accessKey='S' type="submit" class=small style="width:90px" value="<?echo $mod_strings['LBL_SAVE']?>">
				<input type="button" class=small style="width:90px" value="<?echo $mod_strings['LBL_RESET']?>" onClick="ghide('addEvent')">
			</td>
		</tr>
		</table>
</form>
	</div>
	<script language="JavaScript" type="text/JavaScript">
setObjects();
	</script>

	<!-- Add Activity DIV stops-->

<div id="reportLay" style="width:125px;" onMouseout="fninvsh('reportLay')" onMouseover="fnvshNrm('reportLay')">
	<table width="100%" cellpadding="0" cellspacing="0" border="0" bgcolor="#FFFFFF">
		<tr>
			<td>
				<a href="#" onClick="changeCalstatus();" class="calMnu">- <?php echo $mod_strings['LBL_COMPLETED']?></a>
				<a href="#" onClick="changeCalstatus();" class="calMnu">- <?php echo $mod_strings['LBL_EPENDING']?></a>
				<span style="border-top:1px dashed #CCCCCC;width:99%;display:block;"></span>
				<a href="#" onClick="" class="calMnu">- <?php echo $mod_strings['LBL_POSTPONE']?></a>
				<a href="#" onClick="calchangeowner();" class="calMnu">- <?php echo $mod_strings['LBL_CHANGEOWNER']?></a>
				<a href="#" onClick="changeCalstatus();" class="calMnu">- <?php echo $mod_strings['LBL_DEL']?></a>
			</td>
		</tr>
	</table>
</div>
