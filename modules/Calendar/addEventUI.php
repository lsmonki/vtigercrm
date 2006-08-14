<?php
/*********************************************************************************
** The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
*
 ********************************************************************************/

require_once('include/utils/CommonUtils.php');
require_once('modules/Calendar/Activity.php');
require_once('modules/Calendar/Calendar.php');
require_once('modules/Calendar/CalendarCommon.php');
require_once("modules/Emails/mail.php");

 global $theme,$mod_strings,$app_strings,$current_user;
 $theme_path="themes/".$theme."/";
 $image_path=$theme_path."images/";
 require_once ($theme_path."layout_utils.php");
 $category = getParentTab();
 $userDetails=getOtherUserName($current_user->id,true);
 //echo '<pre>';print_r($userDetails);echo '</pre>';
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

 function getPriorityCombo()
 {
	 global $adb, $mod_strings;
	 $combo = '';
	 $combo .= '<select name="taskpriority" id="taskpriority" class=small>';
	 $q = "select * from vtiger_taskpriority";
	 $Res = $adb->query($q);
	 $noofrows = $adb->num_rows($Res);

	 for($i = 0; $i < $noofrows; $i++)
	 {
		 $value = $adb->query_result($Res,$i,'taskpriority');
		 $combo .= '<option value="'.$value.'">'.$mod_strings[$value].'</option>';
	 }

	 $combo .= '</select>';
	 return $combo;
 }

?>
       
	<!-- Add Event DIV starts-->
	<script language="JavaScript" type="text/javascript" src="include/js/general.js"></script>
	<link rel="stylesheet" type="text/css" media="all" href="jscalendar/calendar-win2k-cold-1.css">
	<script type="text/javascript" src="jscalendar/calendar.js"></script>
	<script type="text/javascript" src="jscalendar/lang/calendar-<? echo $app_strings['LBL_JSCALENDAR_LANG'] ?>.js"></script>
	<script type="text/javascript" src="jscalendar/calendar-setup.js"></script>
<?php
	if(isset($_REQUEST['return_action']) && $_REQUEST['return_action'] == 'CallRelatedList' && $_REQUEST['activity_mode'] == 'Events')
	{
		include_once 'modules/Calendar/header.php';
		$closelink = '';
?>
	<div class="layerPopup small" style="padding:20px">
	<form name="EditView" onSubmit="return check_form();" method="POST" action="index.php">	
	<input type="hidden" name="return_module" value="<?php $_REQUEST['return_module'] ?>">
	<input type="hidden" name="return_action" value="<?php echo $_REQUEST['return_action'] ?>">
<?php
}
else
{
	$closelink = '<td align=right>
			<a href="javascript:ghide(\'addEvent\');"><img src="'.$image_path.'close.gif" border="0"  align="absmiddle" /></a></td>';
?>
	<div class="calAddEvent layerPopup" style="display:none" id="addEvent" align=center>
	<form name="EditView" onSubmit="return check_form();" method="POST" action="index.php">
	<input type="hidden" name="return_action" value="index">
	<input type="hidden" name="return_module" value="Calendar">
<?php
}
?>
	<input type="hidden" name="module" value="Calendar">
	<input type="hidden" name="activity_mode" value="Events">
	<input type="hidden" name="action" value="Save">
	<input type="hidden" name="view" value="<?php echo $calendar_arr['view'] ?>">
	<input type="hidden" name="hour" value="<?php echo $calendar_arr['calendar']->date_time->hour ?>">
	<input type="hidden" name="day" value="<?php echo $calendar_arr['calendar']->date_time->day ?>">
	<input type="hidden" name="month" value="<?php echo $calendar_arr['calendar']->date_time->month ?>">
	<input type="hidden" name="year" value="<?php echo $calendar_arr['calendar']->date_time->year ?>">
	<input type="hidden" name="record" value="">
	<input type="hidden" name="assigned_user_id" value="<? echo $current_user->id ?>">
	<input type="hidden" name="assigntype" value="U">
	<input type="hidden" name="mode" value="">
	<input type="hidden" name="time_start" id="time_start">
	<input type="hidden" name="time_end" id="time_end">
	<input type="hidden" name="eventstatus" value="Planned">
	<input type="hidden" name="duration_hours" value="0">                                                                      <input type="hidden" name="duration_minutes" value="0">
	<input type=hidden name="inviteesid" id="inviteesid" value="">
	<input type="hidden" name="parenttab" value="<?php echo $category ?>">
	<input type="hidden" name="viewOption" value="">
	<input type="hidden" name="subtab" value="">
	<input type="hidden" name="maintab" value="Calendar">
		<table border=0 cellspacing=0 cellpadding=5 width=100% class="layerHeadingULine">
		<tr>
			<td class="layerPopupHeading"><?php echo $mod_strings['LBL_ADD_EVENT']?></b></td>
			<?php echo $closelink; ?>
		</tr>
		</table>
		
		<table border=0 cellspacing=0 cellpadding=5 width=95% align=center bgcolor="#FFFFFF"> 
			<tr>
		<td class=small >
			<table border=0 celspacing=0 cellpadding=5 width=100% align=center bgcolor=white>
		<tr>
			<td nowrap  width=20%><b><?php echo $mod_strings['LBL_EVENTTYPE']?> :</b></td>
			<td width=80%>
				<table>
					<tr>
					<td><input type="radio" name='activitytype' value='Call' style='vertical-align: middle;' checked></td><td><?php echo $mod_strings['LBL_CALL']?></td><td style="width:10px">
					<td><input type="radio" name='activitytype' value='Meeting' style='vertical-align: middle;'></td><td><?php echo $mod_strings['LBL_MEET']?></td><td style="width:20px">
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td nowrap ><b><?php echo $mod_strings['LBL_EVENTNAME']?> :</b></td>
			<td><input name="subject" type="text" class="textbox" value="" style="width:90%"></td>
		</tr>
		<tr>
			<td colspan="2"><input name="visibility" value="Public" type="checkbox"><?php echo $mod_strings['LBL_PUBLIC']; ?></td>
		</tr>

		</table>
		<br>
		<table border=0 cellspacing=0 cellpadding=5 width=90% align=center bgcolor="#FFFFFF">
		<tr>
			<td >
				<table border=0 cellspacing=0 cellpadding=2 width=100%>
				<tr>
				<td width=50% valign=top style="border-right:1px solid #dddddd">
					<table border=0 cellspacing=0 cellpadding=2 width=90%>
					<tr><td colspan=3 ><b><?php echo $mod_strings['LBL_EVENTSTAT']?></b></td></tr>
				        <tr><td colspan=3>
						<?php echo  getTimeCombo($calendar_arr['calendar']->hour_format,'start');?>
					</td></tr>
                                        <tr><td>
					<input type="text" name="date_start" id="jscal_field_date_start" class="textbox" style="width:90px" value="<?php echo $calendar_arr['calendar']->date_time->get_formatted_date() ?>"></td><td width=50%><img border=0 src="<?echo $image_path?>btnL3Calendar.gif" alt="Set date.." title="Set date.." id="jscal_trigger_date_start">
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
                                                <?php echo getTimeCombo($calendar_arr['calendar']->hour_format,'end');?>
					</td></tr>
				        <tr><td>
					<input type="text" name="due_date" id="jscal_field_due_date" class="textbox" style="width:90px" value="<?php echo $calendar_arr['calendar']->date_time->get_formatted_date() ?>"></td><td width=100%><img border=0 src="<?php echo $image_path?>btnL3Calendar.gif" alt="Set date.." title="Set date.." id="jscal_trigger_due_date">
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
		<tr>
			<td>
			<?php echo $mod_strings['Priority'] ; ?>&nbsp;:&nbsp;<?php echo getPriorityCombo(); ?>
			</td>
		</tr>
		</table>


		<!-- Alarm, Repeat, Invite starts-->
		<br>
		<table border=0 cellspacing=0 cellpadding=0 width=95% align=center bgcolor="#FFFFFF">
		<tr>
			<td>
				<table border=0 cellspacing=0 cellpadding=3 width=100%>
				<tr>
					<td class="dvtTabCache" style="width:10px" nowrap>&nbsp;</td>
					<td id="cellTabInvite" class="dvtSelectedCell" align=center nowrap><a href="javascript:doNothing()" onClick="switchClass('cellTabInvite','on');switchClass('cellTabAlarm','off');switchClass('cellTabRepeat','off');switchClass('cellTabRelatedto','off');ghide('addEventAlarmUI');gshow('addEventInviteUI','',document.EditView.date_start.value,document.EditView.due_date.value,document.EditView.starthr.value,document.EditView.startmin.value,document.EditView.startfmt.value,document.EditView.endhr.value,document.EditView.endmin.value,document.EditView.endfmt.value);ghide('addEventRepeatUI');ghide('addEventRelatedtoUI');"><?php echo $mod_strings['LBL_INVITE']?></a></td>
					<td class="dvtTabCache" style="width:10px">&nbsp;</td>
					<td id="cellTabAlarm" class="dvtUnSelectedCell" align=center nowrap><a href="javascript:doNothing()" onClick="switchClass('cellTabInvite','off');switchClass('cellTabAlarm','on');switchClass('cellTabRepeat','off');switchClass('cellTabRelatedto','off');gshow('addEventAlarmUI','',document.EditView.date_start.value,document.EditView.due_date.value,document.EditView.starthr.value,document.EditView.startmin.value,document.EditView.startfmt.value,document.EditView.endhr.value,document.EditView.endmin.value,document.EditView.endfmt.value);ghide('addEventInviteUI');ghide('addEventRepeatUI');ghide('addEventRelatedtoUI');"><?php echo $mod_strings['LBL_REMINDER']?></a></td>
					<td class="dvtTabCache" style="width:10px">&nbsp;</td>
					<td id="cellTabRepeat" class="dvtUnSelectedCell" align=center nowrap><a href="javascript:doNothing()" onClick="switchClass('cellTabInvite','off');switchClass('cellTabAlarm','off');switchClass('cellTabRepeat','on');switchClass('cellTabRelatedto','off');ghide('addEventAlarmUI');ghide('addEventInviteUI');gshow('addEventRepeatUI','',document.EditView.date_start.value,document.EditView.due_date.value,document.EditView.starthr.value,document.EditView.startmin.value,document.EditView.startfmt.value,document.EditView.endhr.value,document.EditView.endmin.value,document.EditView.endfmt.value);ghide('addEventRelatedtoUI');"><?php echo $mod_strings['LBL_REPEAT']?></a></td>
					<td class="dvtTabCache" style="width:10px">&nbsp;</td>
					<td id="cellTabRelatedto" class="dvtUnSelectedCell" align=center nowrap><a href="javascript:doNothing()" onClick="switchClass('cellTabInvite','off');switchClass('cellTabAlarm','off');switchClass('cellTabRepeat','off');switchClass('cellTabRelatedto','on');ghide('addEventAlarmUI');ghide('addEventInviteUI');gshow('addEventRelatedtoUI','',document.EditView.date_start.value,document.EditView.due_date.value,document.EditView.starthr.value,document.EditView.startmin.value,document.EditView.startfmt.value,document.EditView.endhr.value,document.EditView.endmin.value,document.EditView.endfmt.value);ghide('addEventRepeatUI');"><?php echo $mod_strings['LBL_RELATEDTO']?></a></td>
					<td class="dvtTabCache" style="width:100%">&nbsp;</td>
				</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td width=100% valign=top align=left class="dvtContentSpace" style="padding:10px;height:120px">
			<!-- Invite UI -->
				
				<DIV id="addEventInviteUI" style="display:block;width:100%">
				<table border=0 cellspacing=0 cellpadding=2 width=100% bgcolor="#FFFFFF">
				<tr>
					<td valign=top> 
						<table border=0 cellspacing=0 cellpadding=2 width=100%>
						<tr>
							<td colspan=3>
								<ul style="padding-left:20px">
								<li><?php echo $mod_strings['LBL_INVITE_INST1']?> 
								<li><?php echo $mod_strings['LBL_INVITE_INST2']?>
								</ul>
							</td>
						</tr>
						<tr>
							<td><b><?php echo $mod_strings['LBL_AVL_USERS']?></b></td>
							<td>&nbsp;</td>
							<td><b><?php echo $mod_strings['LBL_SEL_USERS']?></b></td>
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
								<input type=button value="<?php echo $mod_strings['LBL_ADD_BUTTON'] ?> >>" class="crm button small save" style="width:100%" onClick="addColumn()"><br>
								<input type=button value="<< <?php echo $mod_strings['LBL_RMV_BUTTON'] ?> " class="crm button small cancel" style="width:100%" onClick="delColumn()">
							</td>
							<td width=40% align=center valign=top>
								<select name="selectedusers" id="selectedusers" class=small size=5 multiple style="height:70px;width:100%">
								</select>
								<div align=left><?php echo $mod_strings['LBL_SELUSR_INFO']?>
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
				<table bgcolor="#FFFFFF">
					<tr><td><?php echo $mod_strings['LBL_SENDREMINDER']?></td>
						<td>
					<input type="radio" name="set_reminder"value="Yes" onClick="showBlock('reminderOptions')">&nbsp;<?php echo $mod_strings['LBL_YES'] ?>&nbsp;
					<input type="radio" name="set_reminder" value="No" onClick="fnhide('reminderOptions')">&nbsp;<?php echo $mod_strings['LBL_NO'] ?>&nbsp;
							
					</td></tr>
				</table>
				<DIV id="reminderOptions" style="display:none;width:100%">
				<table border=0 cellspacing=0 cellpadding=2  width=100% bgcolor="#FFFFFF">
				<tr>
					<td nowrap align=right width=20% valign=top>
						<b><?php echo $mod_strings['LBL_RMD_ON']?> : </b>
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
									<option value="<?php echo $m ?>"><?php echo $m ?></option>
							<?
								}
							?>
							</select>days 
							<select class=small name="remhrs">
                                                        <?php
                                                                for($h=0;$h<=23;$h++)
                                                                {
                                                        ?>
                                                                        <option value="<?php echo $h ?>"><?php echo $h ?></option>
                                                        <?
                                                                }
                                                        ?>
                                                        </select>hrs
							<select class=small name="remmin">
                                                        <?php
                                                                for($min=1;$min<=59;$min++)
                                                                {
                                                        ?>
                                                                        <option value="<?php echo $min ?>"><?php echo $min ?></option>
                                                        <?
                                                                }
                                                        ?>
                                                        </select>minutes&nbsp;<?php echo $mod_strings['LBL_BEFOREEVENT'] ?>
						</td>
						</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td nowrap align=right>
					<?php echo $mod_strings['LBL_SDRMD'] ?> :
					</td>
					<td >
					<input type=text name="toemail" class=textbox style="width:90%" value="<?php echo $to_email ?>">
					</td>
				</tr>
				</table>
				</DIV>
				</DIV>
			<!-- Repeat UI -->
				<div id="addEventRepeatUI" style="display:none;width:100%">
				<table border=0 cellspacing=0 cellpadding=2  width=100% bgcolor="#FFFFFF">
				<tr>
					<td nowrap align=right width=20% valign=top>
					<strong><?php echo $mod_strings['LBL_REPEAT']?> :</strong>
					</td>
					<td nowrap width=80% valign=top>
						<table border=0 cellspacing=0 cellpadding=0>
						<tr>
							<td width=20><input type="checkbox" name="recurringcheck" onClick="showhide('repeatOptions')"></td>
							<td colspan=2><?php echo $mod_strings['LBL_ENABLE_REPEAT']?></td>
						</tr>
						<tr>
							<td colspan=2>
							<div id="repeatOptions" style="display:none">
								<table border=0 cellspacing=0 cellpadding=2 bgcolor="#FFFFFF">
								<tr>
								<td>
									<?php echo $mod_strings['LBL_REPEATEVENT']; ?>
								</td>
								<td><input type="text" name="repeat_frequency" class="textbox" style="width:20px" value="" ></td>
								<td>
									<select name="recurringtype">
										<option value="Daily" onClick="ghide('repeatMonthUI');"><?php echo $mod_strings['LBL_DAYS']; ?></option>
										<option value="Weekly" onClick="ghide('repeatMonthUI');"><?php echo $mod_strings['LBL_WEEKS']; ?></option>
										<option value="Monthly" onClick="gshow('repeatMonthUI');"><?php echo $mod_strings['LBL_MONTHS']; ?></option>
										<option value="Yearly" onClick="ghide('repeatMonthUI');";><?php echo $mod_strings['LBL_YEAR']; ?></option>
									</select>
								</td>
								</tr>
								</table>

								<!--div id="repeatWeekUI" style="display:none;">
								<table border=0 cellspacing=0 cellpadding=2>
									<tr>
								<td><input name="sun_flag" value="sunday" type="checkbox"></td><td><?php echo $mod_strings['LBL_SM_SUN']; ?></td>
								<td><input name="mon_flag" value="monday" type="checkbox"></td><td><?php echo $mod_strings['LBL_SM_MON']; ?></td>
								<td><input name="tue_flag" value="tuesday" type="checkbox"></td><td><?php echo $mod_strings['LBL_SM_TUE']; ?></td>
								<td><input name="wed_flag" value="wednesday" type="checkbox"></td><td><?php echo $mod_strings['LBL_SM_WED']; ?></td>
								<td><input name="thu_flag" value="thursday" type="checkbox"></td><td><?php echo $mod_strings['LBL_SM_THU']; ?></td>
								<td><input name="fri_flag" value="friday" type="checkbox"></td><td><?php echo $mod_strings['LBL_SM_FRI']; ?></td>
								<td><input name="sat_flag" value="saturday" type="checkbox"></td><td><?php echo $mod_strings['LBL_SM_SAT']; ?></td>
									</tr>
								</table>
								</div-->

								<div id="repeatMonthUI" style="display:none;">
								<table border=0 cellspacing=0 cellpadding=2 bgcolor="#FFFFFF">
									<tr>
										<td>
											<table border=0 cellspacing=0 cellpadding=2>
												<tr>
													<td><input type="radio" checked name="repeatMonth" value="date"></td><td>on</td><td><input type="text" class=textbox style="width:20px" value="2" name="repeatMonth_date" ></td><td>day of the month</td>
												</tr>
											</table>
										</td>
									</tr>
									<tr>
										<td>
											<table border=0 cellspacing=0 cellpadding=2>
												<tr>
													<td>
														<input type="radio" name="repeatMonth" value="day"></td>
													<td>on</td>
													<td>
														<select name="repeatMonth_daytype">
															<option value="first">First</option>
															<option value="last">Last</option>
														</select>
													</td>
													<td>
														<select name="repeatMonth_day">
															<option value=1><?php echo $mod_strings['LBL_DAY1']; ?></option>
															<option value=2><?php echo $mod_strings['LBL_DAY2']; ?></option>
															<option value=3><?php echo $mod_strings['LBL_DAY3']; ?></option>
															<option value=4><?php echo $mod_strings['LBL_DAY4']; ?></option>
															<option value=5><?php echo $mod_strings['LBL_DAY5']; ?></option>
															<option value=6><?php echo $mod_strings['LBL_DAY6']; ?></option>
														</select>
													</td>
												</tr>
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
				<div id="addEventRelatedtoUI" style="display:none;width:100%">
					<table width="100%" cellpadding="5" cellspacing="0" border="0" bgcolor="#FFFFFF">
						<tr>
							<td><b><?php echo $mod_strings['LBL_RELATEDTO']?> :</b></td>
							<td>
								<input name="parent_id" value="" type="hidden">
								<select name="parent_type" class="small" id="parent_type" onChange="document.EditView.parent_name.value='';">
									<option value="Leads"><?php echo $app_strings['Leads']?></option>
									<option value="Accounts"><?php echo $app_strings['Accounts']?></option>
									<option value="Potentials"><?php echo $app_strings['Potentials']?></option>
								</select>
							</td>
							<td>
								<div id="eventrelatedto" align="left">
								<input type="text" readonly="readonly" class="calTxt small" value="" name="parent_name">&nbsp;
								<input type="button" name="selectparent" class="crmButton small edit" value="Change..." onclick="return window.open('index.php?module='+document.EditView.parent_type.value+'&action=Popup','test','width=640,height=602,resizable=0,scrollbars=0,top=150,left=200');">
								</div>
							</td>
						</tr>
						<tr>
						<td><b><?php echo $app_strings['Contacts'] ?>:</b></td>
							<td colspan="2">
								<input name="contactidlist" id="contactidlist" value="" type="hidden">
								<textarea rows="5" name="contactlist" readonly="readonly" class="calTxt"></textarea>&nbsp;
								<input type="button" onclick="return window.open('index.php?module=Contacts&action=Popup&return_module=Calendar&popuptype=detailview&select=enable&form=EditView&form_submit=false','test','width=640,height=602,resizable=0,scrollbars=0');" class="crmButton small edit" name="selectcnt" value="<?php echo $mod_strings['LBL_SELECT_CONTACT'] ; ?>">
							</td>
						</tr>
					</table>
				</div>
					
	
			</td>
		</tr>
		</table>
			<!-- popup specific content fill in ends -->
		
		</td>
	</tr>
	</table>
	<table border=0 cellspacing=0 cellpadding=5 width=100% class="layerPopupTransport">
		<br>
		
		
		<tr>
			<td valign=top></td>
			<td  align=center>
				<input title='Save [Alt+S]' accessKey='S' type="submit" name="eventsave" class="crm button small save" style="width:90px" value="<?php echo $mod_strings['LBL_SAVE']?>">
<?php
if(isset($_REQUEST['return_action']) && $_REQUEST['return_action'] == 'CallRelatedList' && $_REQUEST['activity_mode'] == 'Events')
{
?>
	<input type="button" class="crm button small cancel" style="width:90px" name="eventcancel" value="<?php echo $mod_strings['LBL_RESET']?>" onClick="window.history.back()">
	</td>
	</tr>
	</table>
	</form>
	</div>
</td></tr></table>
</td></tr></table>
</td></tr></table>
</div>
</td>
<td valign=top><img src="<?php echo $image_path ?>showPanelTopRight.gif"></td>
	</tr>
	</table>

<?php } 
else
{
?>
	<input type="button" class="crm button small cancel" style="width:90px" name="eventcancel" value="<?php echo $mod_strings['LBL_RESET']?>" onClick="ghide('addEvent')">
	  </td>
	  </tr>
	</table>
  </form>
  </div>
<?php
}
?>
						  
							 
	<script language="JavaScript" type="text/JavaScript">
setObjects();
	</script>

	<!-- Add Activity DIV stops-->

<div id="eventcalAction" class="calAction" style="width:125px;" onMouseout="fninvsh('eventcalAction')" onMouseover="fnvshNrm('eventcalAction')">
	<table width="100%" cellpadding="0" cellspacing="0" border="0" bgcolor="#FFFFFF">
		<tr>
			<td>
				<a href="" id="complete" onClick="fninvsh('eventcalAction')" class="calMnu">- <?php echo $mod_strings['LBL_HELD']?></a>
				<a href="" id="pending" onClick="fninvsh('eventcalAction')" class="calMnu">- <?php echo $mod_strings['LBL_NOTHELD']?></a>
				<span style="border-top:1px dashed #CCCCCC;width:99%;display:block;"></span>
				<a href="" id="postpone" onClick="fninvsh('eventcalAction')" class="calMnu">- <?php echo $mod_strings['LBL_POSTPONE']?></a>
				<a href="" id="changeowner" onClick="fninvsh('eventcalAction')" class="calMnu">- <?php echo $mod_strings['LBL_CHANGEOWNER']?></a>
				<a href="" id="actdelete" onclick ="fninvsh('eventcalAction');return confirm('Are you sure?');" class="calMnu">- <?php echo $mod_strings['LBL_DEL']?></a>
			</td>
		</tr>
	</table>
</div>

<!-- Dropdown for Add Event -->
<div id='addEventDropDown' style='width:160px' onmouseover='fnShowEvent()' onmouseout='fnRemoveEvent()'>
<table width="100%" cellpadding="0" cellspacing="0" border="0">
	<tr><td><a href='' id="addcall" class='drop_down'><?php echo $mod_strings['LBL_ADDCALL']?></a></td></tr>
	<tr><td><a href='' id="addmeeting" class='drop_down'><?php echo $mod_strings['LBL_ADDMEETING']?></a></td></tr>
	<tr><td><a href='' id="addtodo" class='drop_down'><?php echo $mod_strings['LBL_ADDTODO']?></a></td></tr>
</table>
</div>
<?php
	if(isset($_REQUEST['return_action']) && $_REQUEST['return_action'] == 'CallRelatedList' && $_REQUEST['activity_mode'] == 'Task')
{
	include_once 'modules/Calendar/header.php';
	$closelink = '';
?>
	<div class="small" style="padding:20px">
	<form name="createTodo" onSubmit="task_check_form();return formValidate();" method="POST" action="index.php">
	<input type="hidden" name="return_module" value="<?php $_REQUEST['return_module'] ?>">
	<input type="hidden" name="return_action" value="<?php echo $_REQUEST['return_action'] ?>"> 
<?php
}
else
{
	$closelink = '<td align=right><a href="javascript:ghide(\'createTodo\');"><img src="'.$image_path.'close.gif" border="0"  align="absmiddle" /></a></td>';
?>
<div class="calAddEvent layerPopup" style="display:none" id="createTodo" align=center>
<form name="createTodo" onSubmit="task_check_form();return formValidate();" method="POST" action="index.php">
<input type="hidden" name="return_action" value="index">
<input type="hidden" name="return_module" value="Calendar">
<?php
}
?>
  <input type="hidden" name="module" value="Calendar">
  <input type="hidden" name="activity_mode" value="Task">
  <input type="hidden" name="action" value="TodoSave">
  <input type="hidden" name="view" value="<?php echo $calendar_arr['view'] ?>">
  <input type="hidden" name="hour" value="<?php echo $calendar_arr['calendar']->date_time->hour ?>">
  <input type="hidden" name="day" value="<?php echo $calendar_arr['calendar']->date_time->day ?>">
  <input type="hidden" name="month" value="<?php echo $calendar_arr['calendar']->date_time->month ?>">
  <input type="hidden" name="year" value="<?php echo $calendar_arr['calendar']->date_time->year ?>">
  <input type="hidden" name="record" value="">
  <input type="hidden" name="assigned_user_id" value="<?php echo $current_user->id ?>">
  <input type="hidden" name="parenttab" value="<?php echo $category ?>">
  <input type="hidden" name="assigntype" value="U">
  <input type="hidden" name="mode" value="">
  <input type="hidden" name="task_time_start" id="task_time_start">
  <input type="hidden" name="taskstatus" value="Planned">
  <input type="hidden" name="viewOption" value="">
  <input type="hidden" name="subtab" value="">
  <input type="hidden" name="maintab" value="Calendar">
	<table border=0 cellspacing=0 cellpadding=5 width=100% class="layerHeadingULine">
		<tr>
                	<td class="lvtHeaderText"><?php echo $mod_strings['LBL_ADD_TODO'] ?></b></td>
			<?php echo $closelink; ?>
		</tr>
        </table>
	<table border=0 cellspacing=0 cellpadding=5 width=95% bgcolor="#FFFFFF" >
		<tr>
                        <td width=20%><b><?php echo $mod_strings['LBL_TODONAME'] ?> :</b></td>
                        <td width=80%><input name="task_subject" type="text" value="" class="textbox" style="width:90%"></td>
                </tr>
		<tr>
			<td><b><?php echo $mod_strings['LBL_TODODATETIME'] ?> :</b></td>
			<td>
				<?php echo getTimeCombo($calendar_arr['calendar']->hour_format,'start'); ?>
			</td>		
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td>
			<input type="text" name="task_date_start" id="task_date_start" class="textbox" style="width:90px" value="<?php echo $calendar_arr['calendar']->date_time->get_formatted_date() ?>" >&nbsp;<img border=0 src="<?php echo $image_path ?>btnL3Calendar.gif" alt="Set date.." title="Set date.." id="jscal_trigger_date_start" align="absmiddle">
				<script type="text/javascript">
					Calendar.setup ({
	                                        inputField : "task_date_start", ifFormat : "<?php  echo $date_format; ?>", showsTime : false, button : "jscal_trigger_date_start", singleClick : true, step : 1
					})
				</script>
			</td>
			
		</tr>
		<tr>
		<td><b><?php echo $mod_strings['Priority']; ?>&nbsp;:&nbsp;</b></td>
			<td><?php echo getPriorityCombo(); ?></td>
		</tr>

			
	</table>
       
	<table align="center" border="0" cellpadding="0" cellspacing="0" width="95%" bgcolor="#FFFFFF">
		<tr>
			<td>
				<table border=0 cellspacing=0 cellpadding=3 width=100%>
					<tr>
						<td class="dvtTabCache" style="width:10px" nowrap>&nbsp;</td>
						<td id="cellTabInvite" class="dvtSelectedCell" align=center nowrap><a href="#" onClick="switchClass('cellTabInvite','on');switchClass('cellTabRelatedto','off');gshow('addTaskAlarmUI','todo',document.createTodo.task_date_start.value,'',document.createTodo.starthr.value,document.createTodo.startmin.value,document.createTodo.startfmt.value,'','','',document.createTodo.viewOption.value,document.createTodo.subtab.value);ghide('addTaskRelatedtoUI');"><?php echo $mod_strings['LBL_NOTIFICATION']?></a></td>
						<td class="dvtTabCache" style="width: 10px;" nowrap="nowrap">
						<td id="cellTabRelatedto" class="dvtUnSelectedCell" align=center nowrap><a href="#" onClick="switchClass('cellTabRelatedto','on'); switchClass('cellTabInvite','off');gshow('addTaskRelatedtoUI','todo',document.createTodo.task_date_start.value,'',document.createTodo.starthr.value,document.createTodo.startmin.value,document.createTodo.startfmt.value,'','','',document.createTodo.viewOption.value,document.createTodo.subtab.value);ghide('addTaskAlarmUI');"><?php echo $mod_strings['LBL_RELATEDTO']?></a></td>					
						<td class="dvtTabCache" style="width: 100%;">&nbsp;</td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td width=100% valign=top align=left class="dvtContentSpace" style="padding:10px;height:120px">
		<!-- Reminder UI -->
		<DIV id="addTaskAlarmUI" style="display:block;width:100%">
                <table>
			<tr><td><?php echo $mod_strings['LBL_SENDNOTIFICATION'] ?></td><td>
				<input name="sendnotification" type="checkbox">
			</td></tr>
                </table>
		</DIV>
		<div id="addTaskRelatedtoUI" style="display:none;width:100%">
			<table width="100%" cellpadding="5" cellspacing="0" border="0">
			<tr>
				<td><b><?php echo $mod_strings['LBL_RELATEDTO']?> :</b></td>
				<td>
					<input name="parent_id" type="hidden" value="">
						<select name="parent_type" class="small" id="parent_type" onChange="document.createTodo.parent_name.value='';document.createTodo.parent_id.value=''">
						<option value="Leads"><?php echo $app_strings['Leads']?></option>
						<option value="Accounts"><?php echo $app_strings['Accounts']?></option>
						<option value="Potentials"><?php echo $app_strings['Potentials']?></option>
						<option value="Quotes"><?php echo $app_strings['Quotes']?></option>
						<option value="PurchaseOrder"><?php echo $app_strings['PurchaseOrder']?></option>
						<option value="SalesOrder"><?php echo $app_strings['SalesOrder']?></option>
						<option value="Invoice"><?php echo $app_strings['Invoice']?></option>
						<option value="Campaigns"><?php echo $app_strings['Campaigns']?></option></select>
						</select>
				</td>
				<td>
					<div id="taskrelatedto" align="left">
					<input name="parent_name" readonly type="text" class="calTxt small" value="">
					<input type="button" name="selectparent" class="crmButton small edit" value="<?php echo $mod_strings['LBL_SELECT']; ?>" onclick="return window.open('index.php?module='+document.createTodo.parent_type.value+'&action=Popup&maintab=Calendar','test','width=640,height=602,resizable=0,scrollbars=0,top=150,left=200');">
					</div>
				</td>
			</tr>
			<tr>
			<td><b><?php echo $mod_strings['LBL_CONTACT'] ?> :</b></td>
			<td colspan="2">
				<input name="contact_name" readonly type="text" class="calTxt" value=""><input name="contact_id" type="hidden" value="">&nbsp;
				<input type="button" onclick="return window.open('index.php?module=Contacts&action=Popup&html=Popup_picker&popuptype=specific&form=EditView','test','width=640,height=602,resizable=0,scrollbars=0');" class="crmButton small edit" name="selectcnt" value="<?php echo $mod_strings['LBL_SELECT_CONTACT'] ; ?>">
			</td>
			  </tr>
			                  </table>
					                  </div>
		</td></tr>
                <!-- Repeat UI -->
	</table>
	<br>

                <table border=0 cellspacing=0 cellpadding=5 width=100% class="layerPopupTransport">
                <tr>
                        <td valign=top></td>
                        <td  align=center>
                                <input title='Save [Alt+S]' accessKey='S' type="submit" name="todosave" class="crm button small save" style="width:90px" value="<?php echo $mod_strings['LBL_SAVE'] ?>">
<?php
if(isset($_REQUEST['return_action']) && $_REQUEST['return_action'] == 'CallRelatedList' && $_REQUEST['activity_mode'] == 'Task')
{
?>
				<input type="button" class="crm button small cancel" style="width:90px" name="todocancel" value="<?php echo $mod_strings['LBL_RESET']?>" onClick="window.history.back()">
			</td></tr></table>
		</form>
	<script>                                                                                                                         var fieldname = new Array('task_subject','task_date_start','task_time_start','taskstatus');                                var fieldlabel = new Array('Subject','Date','Time','Status');                                                              var fielddatatype = new Array('V~M','D~M~time_start','T~O','V~O');                                                   </script>
	</div>
	</td></tr></table>
	</td></tr></table>
	</td></tr></table>
	</div>
	</td>
	<td valign=top><img src="<?php echo $image_path ?>showPanelTopRight.gif"></td>
	</tr>
	</table>
<?php }
else
{
?>
		<input type="button" class="crm button small cancel" style="width:90px" name="todocancel" value="<?php echo $mod_strings['LBL_RESET']?>" onClick="ghide('createTodo')">
	</td></tr></table>
  </form>
  <script>
  	var fieldname = new Array('task_subject','task_date_start','task_time_start','taskstatus');
	var fieldlabel = new Array('Subject','Date','Time','Status');
	var fielddatatype = new Array('V~M','D~M~time_start','T~O','V~O');
  </script>
  </div>
<?php
}
?>

<div id="act_changeowner" class="statechange" style="left:250px;top:200px;z-index:5000">
	<form name="change_owner">
	<input type="hidden" value="" name="idlist" id="idlist">
	<input type="hidden" value="" name="action">
	<input type="hidden" value="" name="hour">
	<input type="hidden" value="" name="day">
	<input type="hidden" value="" name="month">
	<input type="hidden" value="" name="year">
	<input type="hidden" value="" name="view">
	<input type="hidden" value="" name="module">
	<input type="hidden" value="" name="subtab">
	<table width="100%" border="0" cellpadding="3" cellspacing="0" >
		<tr>
		<td class="genHeaderSmall" align="left" style="border-bottom:1px solid #CCCCCC;" width="60%"><?php echo $app_strings['LBL_CHANGE_OWNER']; ?></td>
			<td style="border-bottom: 1px solid rgb(204, 204, 204);">&nbsp;</td>
			<td align="right" style="border-bottom:1px solid #CCCCCC;" width="40%"><a href="javascript:fninvsh('act_changeowner')"><img src="<?php echo $image_path; ?>close.gif" align="absmiddle" border="0"></a></td>
		</tr>
		<tr>
		        <td colspan="3">&nbsp;</td>
	</tr>
	<tr>
	<td width="50%"><b><?php echo $app_strings['LBL_TRANSFER_OWNERSHIP']; ?></b></td>
	        <td width="2%"><b>:</b></td>
        	<td width="48%">
	        	<select name="activity_owner" id="activity_owner" class="detailedViewTextBox">
				<?php echo getUserslist(); ?>
		        </select>
        	</td>
	</tr>
	<tr><td colspan="3" style="border-bottom:1px dashed #CCCCCC;">&nbsp;</td></tr>
	<tr>
        	<td colspan="3" align="center">
	        &nbsp;&nbsp;
<input type="button" name="button" class="crm button small save" value="<?php echo $app_strings['LBL_UPDATE_OWNER']; ?>" onClick="calendarChangeOwner();fninvsh('act_changeowner');">
		        <input type="button" name="button" class="crm button small cancel" value="<?php echo $app_strings['LBL_CANCEL_BUTTON_LABEL']; ?>" onClick="fninvsh('act_changeowner')">	
		</td>
	</tr>
	</table>
	</form>
</div>


<div id="taskcalAction" class="calAction" style="width:125px;" onMouseout="fninvsh('taskcalAction')" onMouseover="fnvshNrm('taskcalAction')">
        <table width="100%" cellpadding="0" cellspacing="0" border="0" bgcolor="#FFFFFF">
                <tr>
                        <td>
                                <a href="" id="taskcomplete" onClick="fninvsh('taskcalAction');" class="calMnu">- <?php echo $mod_strings['LBL_COMPLETED']?></a>
                                <a href="" id="taskpending" onClick="fninvsh('taskcalAction');" class="calMnu">- <?php echo $mod_strings['LBL_DEFERRED']?></a>
                                <span style="border-top:1px dashed #CCCCCC;width:99%;display:block;"></span>
                                <a href="" id="taskpostpone" onClick="fninvsh('taskcalAction');" class="calMnu">- <?php echo $mod_strings['LBL_POSTPONE']?></a>
                                <a href="" id="taskchangeowner" onClick="fninvsh('taskcalAction');" class="calMnu">- <?php echo $mod_strings['LBL_CHANGEOWNER']?></a>
                                <a href="" id="taskactdelete" onClick ="fninvsh('taskcalAction');return confirm('Are you sure?');" class="calMnu">- <?php echo $mod_strings['LBL_DEL']?></a>
                        </td>
                </tr>
        </table>
</div>

