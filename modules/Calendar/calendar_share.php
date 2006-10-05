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
global $current_user,$mod_strings,$app_strings;
global $theme;
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
require_once ($theme_path."layout_utils.php");
require_once('include/database/PearDatabase.php');
require_once('modules/Calendar/CalendarCommon.php');
 $t=Date("Ymd");
 $userDetails=getOtherUserName($current_user->id,true);
 $shareduser_ids = getSharedUserId($current_user->id);
?>
<table border=0 cellspacing=0 cellpadding=5 width=100% class="layerHeadingULine">
	<tr>
		<td class="layerPopupHeading" align="left"><? echo $mod_strings['LBL_CALSETTINGS']?></td>
		<td align=right>
			<a href="javascript:fninvsh('calSettings');"><img src="<?echo $image_path?>close.gif" border="0"  align="absmiddle" /></a>
		</td>
	</tr>
	</table>
<form name="SharingForm" method="post" action="index.php">
<input type="hidden" name="module" value="Calendar">
<input type="hidden" name="action" value="updateCalendarSharing">
<input type="hidden" name="view" value="<?php echo $_REQUEST['view'] ?>">
<input type="hidden" name="hour" value="<?php echo $_REQUEST['hour'] ?>">
<input type="hidden" name="day" value="<?php echo $_REQUEST['day'] ?>">
<input type="hidden" name="month" value="<?php echo $_REQUEST['month'] ?>">
<input type="hidden" name="year" value="<?php echo $_REQUEST['year'] ?>">
<input type="hidden" name="viewOption" value="<?php echo $_REQUEST['viewOption'] ?>">
<input type="hidden" name="subtab" value="<?php echo $_REQUEST['subtab'] ?>">
<input type="hidden" name="parenttab" value="<?php echo $_REQUEST['parenttab'] ?>">
<input type="hidden" name="current_userid" value="<? echo $current_user->id ?>" >
<table border=0 cellspacing=0 cellpadding=5 width=95% align=center> 
	<tr>
		<td class=small >
			<table border=0 celspacing=0 cellpadding=5 width=100% align=center bgcolor=white>
			<tr>
		<td align="right" width="10%" valign="top"><img src="<?echo $image_path?>cal_clock.jpg" align="absmiddle"></td>
		<td align="left" width="90%">
			<b><?echo $mod_strings['LBL_TIMESETTINGS']?></b><br>
			<input type="checkbox" name="sttime_check" <? if($current_user->start_hour != ''){?> checked <? } ?> onClick="enableCalstarttime();">&nbsp;<?echo $mod_strings['LBL_CALSTART']?> 
			<select name="start_hour" <? if($current_user->start_hour == ''){?>disabled <? } ?> >
				<?
					for($i=0;$i<=23;$i++)
					{
						if($i == 0)
							$hour = "12:00 am";
						elseif($i >= 12)
						{
							if($i == 12)
								$hour = $i;
							else 
								$hour = $i - 12;
							$hour = $hour.":00 pm";
						}
						else
               					{
							$hour = $i.":00 am";
       						}
						if($i <= 9 && strlen(trim($i)) < 2)
						{
							$value = '0'.$i.':00';
                       				}
						else
							$value = $i.':00';
							if($value === $current_user->start_hour)
                                                       	        $selected = 'selected';
                                                         else
                                                                $selected = '';
				?>
				<option <?echo $selected?> value="<? echo $value?>"><? echo $hour?></option>
				<?
					}
				?>
			</select><br>
			<input type="checkbox" name="hour_format" <? if($current_user->hour_format == '24'){?> checked <? } ?> value="24">&nbsp;<?echo $mod_strings['LBL_USE24']?>
		</td>
	</tr>
	<tr><td colspan="2" style="border-bottom:1px dotted #CCCCCC;"></td></tr>
	<tr>
		<td align="right" valign="top"><img src="<?echo $image_path?>cal_sharing.jpg" width="45" height="38" align="absmiddle"></td>
		<td align="left">
		<b><?echo $mod_strings['LBL_CALSHARE']?></b><br>
		<?echo $mod_strings['LBL_CALSHAREMESSAGE']?><br><br>
		<div id="cal_shar" style="border:1px solid #666666;width:90%;height:200px;overflow:auto;position:relative;">
			<table width="95%" border="0" cellpadding="5" cellspacing="0" align="center">
				<?php
					$cnt = 1;
					echo '<tr>';
					foreach($userDetails as $id=>$name)
					{
						if(in_array($id,$shareduser_ids))
							$checkbox = "checked";
						else
							$checkbox = "";
						echo '<td width="50%" align="left"><input type="checkbox" name="user[]" value='.$id.' '.$checkbox.'>&nbsp;'.$name.'</td>';
						if($cnt%2 == 0)
							echo '</tr>';
                                                $cnt++;
					}
                    		?>
			</table>
		</div>
		</td>
	</tr>
	</table>
	</td>
	</tr>
	</table>
	<table border=0 cellspacing=0 cellpadding=5 width=100% class="layerPopupTransport">
	<tr>
		<td align="center">
			<input type="submit" name="save" value=" &nbsp;<? echo $app_strings['LBL_SAVE_BUTTON_LABEL'] ?>&nbsp;" class="crmbutton small save" />&nbsp;&nbsp;
			<input type="button" name="cancel" value=" <? echo $app_strings['LBL_CANCEL_BUTTON_LABEL'] ?> " class="crmbutton small cancel" onclick="fninvsh('calSettings');" />
		</td>
	</tr>
	</table>
</form>

