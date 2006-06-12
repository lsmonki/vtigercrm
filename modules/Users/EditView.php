<?php
/*********************************************************************************
 * The contents of this file are subject to the SugarCRM Public License Version 1.1.2
 * ("License"); You may not use this file except in compliance with the
 * License. You may obtain a copy of the License at http://www.sugarcrm.com/SPL
 * Software distributed under the License is distributed on an  "AS IS"  basis,
 * WITHOUT WARRANTY OF ANY KIND, either express or implied. See the License for
 * the specific language governing rights and limitations under the License.
 * The Original Code is:  SugarCRM Open Source
 * The Initial Developer of the Original Code is SugarCRM, Inc.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.;
 * All Rights Reserved.
 * Contributor(s): ______________________________________.
 ********************************************************************************/
/*********************************************************************************
 * $Header: /advent/projects/wesat/vtiger_crm/sugarcrm/modules/Users/EditView.php,v 1.16 2005/04/19 14:44:02 ray Exp $
 * Description:  TODO: To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

require_once('Smarty_setup.php');
require_once('data/Tracker.php');
require_once('modules/Users/User.php');
require_once('include/utils/UserInfoUtil.php');
require_once('modules/Users/Forms.php');
require_once('include/database/PearDatabase.php');
require_once('modules/Activities/OpenListView.php');
require_once('modules/Leads/ListViewTop.php');

global $app_strings;
global $app_list_strings;
global $mod_strings;


$focus = new User();

if(isset($_REQUEST['record']) && isset($_REQUEST['record'])) {
	$mode='edit';
	if (!is_admin($current_user) && $_REQUEST['record'] != $current_user->id) die ("Unauthorized access to user administration.");
    $focus->retrieve($_REQUEST['record']);
}else
{
	$mode='create';
	$password='<tr>
	   		   <td width="20%" class="dataLabel"><FONT class="required">*</FONT>Password</td>
		       <td width="30%"><input name="new_password" type="password" vtiger_tabindex="1" size="25" maxlength="25"></td>
			   <td width="20%" class="dataLabel"><FONT class="required">*</FONT>Confirm Password</td>
			   <td width="30%"><input name="confirm_new_password" type="password" vtiger_tabindex="2" size="25" maxlength="75"></td>
			  </tr>';
}

if(isset($_REQUEST['isDuplicate']) && $_REQUEST['isDuplicate'] == 'true') {
	$focus->id = "";
	$focus->user_name = "";
	$mode='create';
	$password='<tr>
	   		   <td width="20%" class="dataLabel"><FONT class="required">*</FONT>Password</td>
		       <td width="30%"><input name="new_password" type="password" vtiger_tabindex="1" size="25" maxlength="25"></td>
			   <td width="20%" class="dataLabel"><FONT class="required">*</FONT>Confirm Password</td>
			   <td width="30%"><input name="confirm_new_password" type="password" vtiger_tabindex="2" size="25" maxlength="75"></td>
			  </tr>';
}

global $theme;
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
require_once($theme_path.'layout_utils.php');

$log->info("User edit view");

$smarty=new vtigerCRM_Smarty;

$smarty->assign("UMOD", $mod_strings);
global $current_language;
$smod_strings = return_module_language($current_language,'Settings');
$smarty->assign("MOD", $smod_strings);

$smarty->assign("APP", $app_strings);

if (isset($_REQUEST['error_string'])) $smarty->assign("ERROR_STRING", "<font class='error'>Error: ".$_REQUEST['error_string']."</font>");
if (isset($_REQUEST['return_module']))
{
        $smarty->assign("RETURN_MODULE", $_REQUEST['return_module']);
        $RETURN_MODULE=$_REQUEST['return_module'];
}
if (isset($_REQUEST['return_action']))
{
        $smarty->assign("RETURN_ACTION", $_REQUEST['return_action']);
        $RETURN_ACTION = $_REQUEST['return_action'];
}
if(isset($_REQUEST['activity_mode']))
{
	$smarty->assign("ACTIVITYMODE",$_REQUEST['activity_mode']);
}
if ($_REQUEST['isDuplicate'] != 'true' && isset($_REQUEST['return_id']))
{
        $smarty->assign("RETURN_ID", $_REQUEST['return_id']);
        $RETURN_ID = $_REQUEST['return_id'];
}

$smarty->assign("JAVASCRIPT", get_validate_record_js());

$smarty->assign("IMAGE_PATH", $image_path);$smarty->assign("PRINT_URL", "phprint.php?jt=".session_id().$GLOBALS['request_string']);
$smarty->assign("ID", $focus->id);
$smarty->assign("USER_NAME", $focus->user_name);
$smarty->assign("FIRST_NAME", $focus->first_name);
$smarty->assign("LAST_NAME", $focus->last_name);
$smarty->assign("TITLE", $focus->title);
$smarty->assign("DEPARTMENT", $focus->department);
$smarty->assign("REPORTS_TO_ID", $focus->reports_to_id);
$smarty->assign("REPORTS_TO_NAME", $focus->reports_to_name);
$smarty->assign("PHONE_HOME", $focus->phone_home);
$smarty->assign("PHONE_MOBILE", $focus->phone_mobile);
$smarty->assign("PHONE_WORK", $focus->phone_work);
$smarty->assign("PHONE_OTHER", $focus->phone_other);
$smarty->assign("PHONE_FAX", $focus->phone_fax);
$smarty->assign("EMAIL1", $focus->email1);
$smarty->assign("EMAIL2", $focus->email2);
$smarty->assign("YAHOO_ID", $focus->yahoo_id);
if (isset($focus->yahoo_id) && $focus->yahoo_id !== "") $smarty->assign("YAHOO_MESSENGER", "<a href='http://edit.yahoo.com/config/send_webmesg?.target=".$focus->yahoo_id."'><img border=0 src='http://opi.yahoo.com/online?u=".$focus->yahoo_id."'&m=g&t=2'></a>");
$smarty->assign("ADDRESS_STREET", $focus->address_street);
$smarty->assign("ADDRESS_CITY", $focus->address_city);
$smarty->assign("ADDRESS_STATE", $focus->address_state);
$smarty->assign("ADDRESS_POSTALCODE", $focus->address_postalcode);
$smarty->assign("ADDRESS_COUNTRY", $focus->address_country);
$smarty->assign("SIGNATURE", $focus->signature);
$smarty->assign("DESCRIPTION", $focus->description);
$smarty->assign("USERIMAGE", $focus->imagename);
$smarty->assign("MODE", $mode);
$smarty->assign("MODULE", 'Settings');

$DATE_FORMAT_SELECT_OPTION = '<select name="date_format">';
		
               
if($focus->date_format == 'dd-mm-yyyy')
{
	$selected1 = 'selected';
}
elseif($focus->date_format == 'mm-dd-yyyy')
{
	$selected2 = 'selected';
}
elseif($focus->date_format == 'yyyy-mm-dd')
{
	$selected3 = 'selected';
}
$DATE_FORMAT_SELECT_OPTION .= '<option value="dd-mm-yyyy" '.$selected1.'>';
$DATE_FORMAT_SELECT_OPTION .= 'dd-mm-yyyy';
$DATE_FORMAT_SELECT_OPTION .= '</option>';
$DATE_FORMAT_SELECT_OPTION .= '<option value="mm-dd-yyyy" '.$selected2.'>';
$DATE_FORMAT_SELECT_OPTION .= 'mm-dd-yyyy';
$DATE_FORMAT_SELECT_OPTION .= '</option>';
$DATE_FORMAT_SELECT_OPTION .= '<option value="yyyy-mm-dd" '.$selected3.'>';
$DATE_FORMAT_SELECT_OPTION .= 'yyyy-mm-dd';
$DATE_FORMAT_SELECT_OPTION .= '</option>';	
$DATE_FORMAT_SELECT_OPTION .= ' </select>';
$smarty->assign("DATE_FORMAT", $DATE_FORMAT_SELECT_OPTION);

if (is_admin($current_user)) {
	$status = "<td width='30%'>&nbsp;&nbsp;<select name='status' vtiger_tabindex='1'";
	if (isset($default_user_name)
		&& $default_user_name != ""
		&& $default_user_name == $focus->user_name
		&& isset($lock_default_user_name)
		&& $lock_default_user_name == true ) {
		$status .= " disabled ";
	}
	$status .= ">";
	$status .= get_select_options_with_id($app_list_strings['user_status_dom'], $focus->status);
	$status .= "</select></td>\n";
	$smarty->assign("USER_STATUS_OPTIONS", $status);

}
else
{
		$status = "<td width='30%'>&nbsp;&nbsp;<select name='status' vtiger_tabindex='1' disabled>";
		$status .= get_select_options_with_id($app_list_strings['user_status_dom'], $focus->status);
		$status .= "</select></td>\n";
		$smarty->assign("USER_STATUS_OPTIONS", $status);

}	

if (is_admin($current_user)) {
     
               
		 $opt =	'<a href="javascript:openPopup();"><img src="'.$image_path.'select.gif" border="0" align="absmiddle"></a>';
                $smarty->assign("ROLEPOPUPBUTTON", $opt);
	
	}
		
        if($focus->id != '')
        {

		$roleselectedid=fetchUserRole($focus->id);
		$roleselected=getRoleName($roleselectedid);
                $smarty->assign("USERROLE", $roleselectedid);
                $smarty->assign("ROLENAME", $roleselected);

        }
               
		
		 



if (is_admin($current_user)) {                   
        $GROUP_SELECT_OPTION = '<select name="group_name">';
		$GROUP_SELECT_OPTION .= '<option value="">--None--</option>';
		if($focus->id) {
			$sql = "SELECT groupname
				FROM vtiger_users2group
				INNER JOIN vtiger_groups
					ON vtiger_groups.groupid = vtiger_users2group.groupid
				WHERE userid = '" .$focus->id ."'";
			$result = $adb->query($sql);
			$groupnameArray = $adb->fetch_array($result);
		}
		$groupselected = $groupnameArray["groupname"];
		$sql2 = "SELECT groupname FROM vtiger_groups";
                  $result_name = $adb->query($sql2);
                  $temprow = $adb->fetch_array($result_name);
                   do
                   {
          		  $selected = '';

                    $groupname=$temprow["groupname"];
		       if($groupselected != '' && $groupname == $groupselected)
	        	{
		                $selected = 'selected';
        		}
                    $GROUP_SELECT_OPTION .= '<option value="'.$groupname.'" '.$selected.'>';
                    $GROUP_SELECT_OPTION .= $temprow["groupname"];
                    $GROUP_SELECT_OPTION .= '</option>';
                   }while($temprow = $adb->fetch_array($result_name));
                                  
                   $GROUP_SELECT_OPTION .= ' </select>';
                   
                   $smarty->assign("GROUP_NAME", $GROUP_SELECT_OPTION);
 }

if (is_admin($current_user)) { 
	$CURRENCY_SELECT_OPTION = '<select name="currency_id" class="small">';
	}
else
{
	$CURRENCY_SELECT_OPTION = '<select name="currency_id" class="small" disabled>';
}
	
        if($focus->id != '')
        {
                $currencyselectedid=fetchCurrency($focus->id);
                $currencyselected=getCurrencyName($currencyselectedid);
        }
        $allCurrency=getDisplayCurrency();
        foreach($allCurrency as $id=>$currencyInfoArr)
        {
               $currencyname=$currencyInfoArr;
               $selected = '';
               if($currencyselected != '' && $currencyname == $currencyselected)
               {
                       $selected = 'selected';
               }
               $CURRENCY_SELECT_OPTION .= '<option value="'.$id .'" '.$selected .'>';
               $CURRENCY_SELECT_OPTION .= $currencyname;
               $CURRENCY_SELECT_OPTION .= '</option>';
        }
        $CURRENCY_SELECT_OPTION .= ' </select>';
        $smarty->assign("CURRENCY_NAME", $CURRENCY_SELECT_OPTION);



$smarty->assign("ACTIVITY_VIEW", getActivityVIew($focus->activity_view));
$smarty->assign("CLOUD_TAG", $focus->tagcloud);

$smarty->assign("LEAD_VIEW", getLeadVIew($focus->lead_view));

		if($focus->cal_color == '') $focus->cal_color = '#E6FAD8';

 		$smarty->assign("CAL_COLOR",'<INPUT TYPE="text" readonly NAME="cal_color" SIZE="10" VALUE="'.$focus->cal_color.'" style="background-color:'.$focus->cal_color.';"> <img src="include/images/bgcolor.gif" onClick="cp2.select(document.EditView.cal_color,\'pick2\');return false;" NAME="pick2" ID="pick2" align="middle">');
		
		$hrformat = '<select name="hour_format" class="small">';
		if($focus->hour_format == '24')
		{
			$_24selected = 'selected';
			$_ampmselected = '';
		}
		elseif($focus->hour_format == 'am/pm')
		{
			$_24selected = '';
			$_ampmselected = 'selected';
		}
		$hrformat .= '<option value="24" '.$_24selected.'>23:00</option>';
		$hrformat .= '<option value="am/pm" '.$_ampmselected.'>11:00pm</option>';
		$hrformat .= '</select>';
		$smarty->assign("CAL_HRFORMAT",$hrformat);

		$stend_hour = '<select name="start_hour" class="small">';
		for($i=0;$i<=15;$i++)
		{
			if($i == $focus->start_hour)
				$selected = 'selected';
			else
				$selected = '';
			if($i <= 9 && strlen(trim($i)) < 2)
			{
				$hrvalue= '0'.$i.':00';
			}
			else
				$hrvalue= $i.':00';
			$stend_hour .= '<option value="'.$hrvalue.'" '.$selected.'>'.$hrvalue.'</option>';
		}
		$stend_hour .= '</select>&nbsp;ends at:';
		$stend_hour .= '<select name="end_hour" class="small">';
		for($i=16;$i<=23;$i++)
		{
			if($i == $focus->end_hour)
				$selected = 'selected';
			else
				$selected = '';
			if($i <= 9 && strlen(trim($i)) < 2)
			{
				$hrvalue= '0'.$i.':00';
			}
			else
				$hrvalue= $i.':00';
			$stend_hour .= '<option value="'.$hrvalue.'" '.$selected.'>'.$hrvalue.'</option>';
		}
		$stend_hour .= '</select>&nbsp;';
		$smarty->assign("CAL_HRDURATION",$stend_hour);

if (isset($default_user_name)
	&& $default_user_name != ""
	&& $default_user_name == $focus->user_name
	&& isset($lock_default_user_name)
	&& $lock_default_user_name == true ) {
	$status .= " disabled ";
	$smarty->assign("DISABLED", "disabled");
}

if ($_REQUEST['Edit'] == ' Edit ')
{
	$smarty->assign("READONLY", "readonly");
	$smarty->assign("USERNAME_READONLY", "readonly");
	
}	
if(isset($_REQUEST['record']) && $_REQUEST['isDuplicate'] != 'true')
{
	$smarty->assign("USERNAME_READONLY", "readonly");
}



if (is_admin($current_user) && $focus->is_admin == 'on') $smarty->assign("IS_ADMIN", "checked");
elseif (is_admin($current_user) && $focus->is_admin != 'on') ;
elseif (!is_admin($current_user) && $focus->is_admin == 'on') $smarty->assign("IS_ADMIN", "disabled checked");
else $smarty->assign("IS_ADMIN", "disabled");

//$smarty->assign("",$focus->getUserListViewHeader());

$smarty->assign('PARENTTAB',$_REQUEST['parenttab']);

	$smarty->display('UserEditView.tpl');

?>
