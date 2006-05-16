<?php
require_once('include/utils/CommonUtils.php');
require_once('modules/Activities/Activity.php');
global $calpath,$callink;
 $calpath = 'modules/Calendar/';
 $callink = 'index.php?module=Calendar&action=';

 global $theme;
 $theme_path="themes/".$theme."/";
 $image_path=$theme_path."images/";
 require_once ($theme_path."layout_utils.php");
 global $mod_strings,$app_strings,$current_user;
 $focus= new Activity();
 $userDetails=getAllUserName();
?>
       
	<!-- Add Event DIV starts-->
	<script language="JavaScript" type="text/javascript" src="general.js"></script>	

	<link rel="stylesheet" type="text/css" media="all" href="jscalendar/calendar-win2k-cold-1.css">
	<script type="text/javascript" src="jscalendar/calendar.js"></script>
	<script type="text/javascript" src="jscalendar/lang/calendar-<? echo $app_strings['LBL_JSCALENDAR_LANG'] ?>.js"></script>
	<script type="text/javascript" src="jscalendar/calendar-setup.js"></script>

	<div class="calAddEvent" style="display:none" id="addEvent" align=center> 
	<form name="appSave" onSubmit="return check_form()" method="POST" action="index.php">
	<input type="hidden" name="module" value="Activities">
	<input type="hidden" name="activity_mode" value="Events">
	<input type="hidden" name="action" value="Save">
	<input type="hidden" name="record" value="">
	<input type="hidden" name="taskstatus" value="Not Started">
	<input type="hidden" name="duration_hours" value="0">
	<input type="hidden" name="assigned_user_id" value="<? echo $current_user->id ?>">
	<input type="hidden" name="duration_minutes" value="0">
		<table border=0 cellspacing=0 cellpadding=5 width=100% class="addEventHeader">
		<tr>
			<td class="lvtHeaderText"><? echo $mod_strings['LBL_ADD_EVENT']?></b></td>
			<td align=right>[ <a href="#" onClick="ghide('addEvent')">Close</a> ]</td>
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
				        <tr><td>
						<input type="text" name="time_start" id="time_start" value="" class="textbox" style="width:90px"></td><td width=50%><img border=0 src="<?echo $image_path?>btnL3Clock.gif" alt="Set time.." title="Set time..">
					</td></tr>
                                        <tr><td>
						<input type="text" name="date_start" id="jscal_field_date_start" value="" class="textbox" style="width:90px"></td><td width=50%><img border=0 src="<?echo $image_path?>btnL3Calendar.gif" alt="Set date.." title="Set date.." id="jscal_trigger_date_start">
						<script type="text/javascript">
                					Calendar.setup ({
								inputField : "jscal_field_date_start", ifFormat : "%Y-%m-%d", showsTime : false, button : "jscal_trigger_date_start", singleClick : true, step : 1
									})
     						        </script>
					</td></tr>
					</table>
				</td>
				<td width=50% valign=top >
					<table border=0 cellspacing=0 cellpadding=2 width=90%>
					<tr><td><b><?echo $mod_strings['LBL_EVENTEDAT']?></b></td></tr>
				        <tr><td><input type="text" name="time_end" id="time_end" value="" class="textbox" style="width:90px"></td><td width=100%><img border=0 src="<?echo $image_path?>btnL3Clock.gif" alt="Set time.." title="Set time.."></td></tr>
				        <tr><td>
						<input type="text" name="due_date" id="jscal_field_due_date" value="" class="textbox" style="width:90px"></td><td width=100%><img border=0 src="<?echo $image_path?>btnL3Calendar.gif" alt="Set date.." title="Set date.." id="jscal_trigger_due_date">
					<script type="text/javascript">
                                                        Calendar.setup ({
                                                                inputField : "jscal_field_due_date", ifFormat : "%Y-%m-%d", showsTime : false, button : "jscal_trigger_due_date", singleClick : true, step : 1
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
					<td id="cellTabInvite" class="dvtSelectedCell" align=center nowrap><a href="#" onClick="switchClass('cellTabInvite','on');switchClass('cellTabAlarm','off');switchClass('cellTabRepeat','off');ghide('addEventAlarmUI');gshow('addEventInviteUI',document.appSave.date_start.value,document.appSave.due_date.value,document.appSave.time_start.value,document.appSave.time_end.value);ghide('addEventRepeatUI');"><?php echo $mod_strings['LBL_INVITE']?></a></td>
					<td class="dvtTabCache" style="width:10px">&nbsp;</td>
					<td id="cellTabAlarm" class="dvtUnSelectedCell" align=center nowrap><a href="#" onClick="switchClass('cellTabInvite','off');switchClass('cellTabAlarm','on');switchClass('cellTabRepeat','off');gshow('addEventAlarmUI',document.appSave.date_start.value,document.appSave.due_date.value,document.appSave.time_start.value,document.appSave.time_end.value);ghide('addEventInviteUI');ghide('addEventRepeatUI');"><?php echo $mod_strings['LBL_REMINDER']?></a></td>
					<td class="dvtTabCache" style="width:10px">&nbsp;</td>
					<td id="cellTabRepeat" class="dvtUnSelectedCell" align=center nowrap><a href="#" onClick="switchClass('cellTabInvite','off');switchClass('cellTabAlarm','off');switchClass('cellTabRepeat','on');ghide('addEventAlarmUI');ghide('addEventInviteUI');gshow('addEventRepeatUI',document.appSave.date_start.value,document.appSave.due_date.value,document.appSave.time_start.value,document.appSave.time_end.value);"><?php echo $mod_strings['LBL_REPEAT']?></a></td>
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
								<li>To invite, select the users from the "Available Users" list and click the "Add"  button. 
								<li>To remove, select the users in the "Selected Users" list and the click "Remove" button.
								</ul>
							</td>
						</tr>
						<tr>
							<td><b>Available  Users</b></td>
							<td>&nbsp;</td>
							<td><b>Selected Users</b></td>
						</tr>
						<tr>
							<td width=40% align=center valign=top>
							<select name="available" id="available" class=small size=5 multiple style="height:70px;width:100%">
							<?php
								for($i=1;$i<=count($userDetails);$i++){
									echo "<option>".$userDetails[$i]."</option>";
									}
							?>
								</select>
								
							</td>
							<td width=20% align=center valign=top>
								<input type=button value="Add >>" class=small style="width:100%" onClick="addColumn()"><br>
								<input type=button value="<< Remove " class=small style="width:100%" onClick="delColumn()">
							</td>
							<td width=40% align=center valign=top>
								<select name="selectedusers" id="selectedusers" class=small size=5 multiple style="height:70px;width:100%">
								</select>
								<div align=left>
								Selected users will receive an email about the Event.
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
				<table border=0 cellspacing=0 cellpadding=2  width=100%>
				<tr>
					<td nowrap align=right width=20% valign=top>
						<b>Remind on : </b>
					</td>
					<td width=80%>
						<table border=0>
						<tr>
						<td><input type="text" class=textbox style="width:30px"></td>
						<td>
							<select class=small><option>Minutes</option><option>Hours</option><option>Days</option></select>
						</td>
						<td width=100%>
							<select class=small><option>before the event starts</option><option>before the event ends</option></select>
						</td>
						</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td nowrap align=right>
					Send Reminder to :
					</td>
					<td >
						<input type=text class=textbox style="width:90%" value="Type Email ID..">
					</td>
				</tr>
				</table>
				</DIV>
			<!-- Repeat UI -->
				<div id="addEventRepeatUI" style="display:none;width:100%">
				<table border=0 cellspacing=0 cellpadding=2  width=100%>
				<tr>
					<td nowrap align=right width=20% valign=top>
					<strong>Repeat :</strong>
					</td>
					<td nowrap width=80% valign=top>
						<table border=0 cellspacing=0 cellpadding=0>
						<tr>
							<td width=20><input type="checkbox" onClick="showhide('repeatOptions')"></td>
							<td colspan=2>Enable Repeat</td>
						</tr>
						<tr>
							<td colspan=2>
							<div id="repeatOptions" style="display:none">
								<table border=0 cellspacing=0 cellpadding=2>
								<tr>
								<td>Repeat once in every</td>
								<td><input type="text" class="textbox" style="width:20px" value="2" ></td>
								<td><select class=small><option onClick="ghide('repeatWeekUI');ghide('repeatMonthUI');">Day(s)</option><option onClick="gshow('repeatWeekUI');ghide('repeatMonthUI');">Week(s)</option><option onClick="gshow('repeatMonthUI');ghide('repeatWeekUI');">Month(s)</option><option onClick="ghide('repeatWeekUI');ghide('repeatMonthUI');";>Year</option></select></td>
								</tr>
								</table>
								
								<div id="repeatWeekUI" style="display:none;">
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
								</div>
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
						<td><select id="repeatOptions" style="display:none" class=small><option>Every day</option><option>Every week</option><option>Every Month</option></select></td>
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
				<input title='Save [Alt+S]' accessKey='S' type="submit" class=small style="width:90px" value="Save">
				<input type="button" class=small style="width:90px" value="Close" onClick="ghide('addEvent')">
			</td>
		</tr>
		</table>
</form>
	</div>
	<script language="JavaScript" type="text/JavaScript">
setObjects();
	</script>
	<!-- Add Activity DIV stops-->
