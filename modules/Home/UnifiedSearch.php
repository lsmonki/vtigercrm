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
 * $Header: /advent/projects/wesat/vtiger_crm/sugarcrm/modules/Home/UnifiedSearch.php,v 1.4 2005/02/21 07:02:49 jack Exp $
 * Description:  TODO: To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

require_once('include/logging.php');
require_once('include/database/PearDatabase.php');
require_once('modules/CustomView/CustomView.php');

require_once('Smarty_setup.php');
global $mod_strings, $current_language;

require_once('modules/Home/language/'.$current_language.'.lang.php');

$total_record_count = 0;

$query_string = trim($_REQUEST['query_string']);

if(isset($query_string) && $query_string != '')//preg_match("/[\w]/", $_REQUEST['query_string'])) 
{

	//module => object
	$object_array = getSearchModules();
	foreach($object_array as $curr_module=>$curr_object)
	{
		require_once("modules/$curr_module/$curr_object.php");
	}

	global $adb;
	global $current_user;
	global $theme;
	$theme_path="themes/".$theme."/";
	$image_path=$theme_path."images/";

	$search_val = $query_string;
	$search_module = $_REQUEST['search_module'];

	getSearchModulesComboList($search_module);

	foreach($object_array as $module => $object_name)
	{
		if(isPermitted($module,"index") == "yes")
		{
			$focus = new $object_name();

			$smarty = new vtigerCRM_Smarty;

			require_once("modules/$module/language/".$current_language.".lang.php");
			global $mod_strings;
			global $app_strings;

			$smarty->assign("MOD", $mod_strings);
			$smarty->assign("APP", $app_strings);
			$smarty->assign("IMAGE_PATH",$image_path);
			$smarty->assign("MODULE",$module);
			$smarty->assign("SEARCH_MODULE",$_REQUEST['search_module']);
			$smarty->assign("SINGLE_MOD",$module);

	
			$listquery = getListQuery($module);
			$oCustomView = '';

			$oCustomView = new CustomView($module);
			//Instead of getting current customview id, use cvid of All so that all entities will be found
			//$viewid = $oCustomView->getViewId($module);
			$cv_res = $adb->pquery("select cvid from vtiger_customview where viewname='All' and entitytype=?", array($module));
			$viewid = $adb->query_result($cv_res,0,'cvid');
			
			$listquery = $oCustomView->getModifiedCvListQuery($viewid,$listquery,$module);
                        if ($module == "Calendar"){
                                if (!isset($oCustomView->list_fields['Close'])) $oCustomView->list_fields['Close']=array ( 'activity' => 'status' );
                                if (!isset($oCustomView->list_fields_name['Close'])) $oCustomView->list_fields_name['Close']='status';
                        }

			if($search_module != '')//This is for Tag search
			{
		
				$where = getTagWhere($search_val,$current_user->id);
				$search_msg =  $app_strings['LBL_TAG_SEARCH'];
				$search_msg .=	"<b>".to_html($search_val)."</b>";
			}
			else			//This is for Global search
			{
				$where = getUnifiedWhere($listquery,$module,$search_val);
				$search_msg = $app_strings['LBL_SEARCH_RESULTS_FOR'];
				$search_msg .=	"<b>".to_html($search_val)."</b>";
			}

			if($where != '')
				$listquery .= ' and ('.$where.')';
			
			if($module == "Calendar")
				$listquery .= ' group by vtiger_activity.activityid having vtiger_activity.activitytype != "Emails"';
				
			$list_result = $adb->query($listquery);
			$noofrows = $adb->num_rows($list_result);

			if($noofrows >= 1)
				$list_max_entries_per_page = $noofrows;
			//Here we can change the max list entries per page per module
			$navigation_array = getNavigationValues(1, $noofrows, $list_max_entries_per_page);

			$listview_header = getListViewHeader($focus,$module,"","","","global",$oCustomView);
			$listview_entries = getListViewEntries($focus,$module,$list_result,$navigation_array,"","","","",$oCustomView);

			//Do not display the Header if there are no entires in listview_entries
			if(count($listview_entries) > 0)
			{
				$display_header = 1;
			}
			else
			{
				$display_header = 0;
			}
		
			$smarty->assign("LISTHEADER", $listview_header);
			$smarty->assign("LISTENTITY", $listview_entries);
			$smarty->assign("DISPLAYHEADER", $display_header);
			$smarty->assign("HEADERCOUNT", count($listview_header));

			$total_record_count = $total_record_count + $noofrows;

			$smarty->assign("SEARCH_CRITERIA","( $noofrows )".$search_msg);
			$smarty->assign("MODULES_LIST", $object_array);

			$smarty->display("GlobalListView.tpl");
			unset($_SESSION['lvs'][$module]);
		}
	}

	//Added to display the Total record count
?>
	<script>
document.getElementById("global_search_total_count").innerHTML = " <?php echo $app_strings['LBL_TOTAL_RECORDS_FOUND'] ?><b><?php echo $total_record_count; ?></b>";
	</script>
<?php

}
else {
	echo "<br><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<em>".$mod_strings['ERR_ONE_CHAR']."</em>";
}

/**	Function to get the where condition for a module based on the field table entries
  *	@param  string $listquery  -- ListView query for the module 
  *	@param  string $module     -- module name
  *	@param  string $search_val -- entered search string value
  *	@return string $where      -- where condition for the module based on field table entries
  */
function getUnifiedWhere($listquery,$module,$search_val)
{
	global $adb, $current_user;
	require('user_privileges/user_privileges_'.$current_user->id.'.php');
		
	$search_val = mysql_real_escape_string($search_val);
	if($is_admin == true || $profileGlobalPermission[1] == 0 || $profileGlobalPermission[2] ==0)
	{
		$query = "SELECT columnname, tablename FROM vtiger_field WHERE tabid = ?";
		$qparams = array(getTabid($module));
	}
	else
	{
		$profileList = getCurrentUserProfileList();
		$query = "SELECT columnname, tablename FROM vtiger_field INNER JOIN vtiger_profile2field ON vtiger_profile2field.fieldid = vtiger_field.fieldid INNER JOIN vtiger_def_org_field ON vtiger_def_org_field.fieldid = vtiger_field.fieldid WHERE vtiger_field.tabid = ? AND vtiger_profile2field.visible = 0 AND vtiger_profile2field.profileid IN (". generateQuestionMarks($profileList) . ") AND vtiger_def_org_field.visible = 0 GROUP BY vtiger_field.fieldid";
		$qparams = array(getTabid($module), $profileList);
	}
	$result = $adb->pquery($query, $qparams);
	$noofrows = $adb->num_rows($result);

	$where = '';
	for($i=0;$i<$noofrows;$i++)
	{
		$columnname = $adb->query_result($result,$i,'columnname');
		$tablename = $adb->query_result($result,$i,'tablename');

		//Before form the where condition, check whether the table for the field has been added in the listview query
		if(strstr($listquery,$tablename))
		{
			if($where != '')
				$where .= " OR ";
			$where .= $tablename.".".$columnname." LIKE '". formatForSqlLike($search_val) ."'";
		}
	}

	return $where;
}

/**	Function to get the Tags where condition
  *	@param  string $search_val -- entered search string value
  *	@param  string $current_user_id     -- current user id
  *	@return string $where      -- where condition with the list of crmids, will like vtiger_crmentity.crmid in (1,3,4,etc.,)
  */
function getTagWhere($search_val,$current_user_id)
{
	require_once('include/freetag/freetag.class.php');

	$freetag_obj = new freetag();

	$crmid_array = $freetag_obj->get_objects_with_tag_all($search_val,$current_user_id);

	$where = '';
	if(count($crmid_array) > 0)
	{
		$where = " vtiger_crmentity.crmid IN (";
		foreach($crmid_array as $index => $crmid)
		{
			$where .= $crmid.',';
		}
		$where = trim($where,',').')';
	}

	return $where;
}


/**	Function to get the the List of Searchable Modules as a combo list which will be displayed in right corner under the Header
  *	@param  string $search_module -- search module, this module result will be shown defaultly 
  */
function getSearchModulesComboList($search_module)
{
	global $object_array;
	global $app_strings;
	global $mod_strings;
	
	?>
		<script>
		function displayModuleList(selectmodule_view)
		{
			<?php
			foreach($object_array as $module => $object_name)
			{
				if(isPermitted($module,"index") == "yes")
				{
			?>
				   mod = "global_list_"+"<?php echo $module; ?>";
				   if(selectmodule_view.options[selectmodule_view.options.selectedIndex].value == "All")
				   show(mod);
				   else
				   hide(mod);
				<?php
				}
			}
			?>
			
			if(selectmodule_view.options[selectmodule_view.options.selectedIndex].value != "All")
			{
				selectedmodule="global_list_"+selectmodule_view.options[selectmodule_view.options.selectedIndex].value;
				show(selectedmodule);
			}
		}
		</script>
		 <table border=0 cellspacing=0 cellpadding=0 width=98% align=center>
		     <tr>
		        <td colspan="3" id="global_search_total_count" style="padding-left:30px">&nbsp;</td>
		<td nowrap align="right"><?php echo $app_strings['LBL_SHOW_RESULTS'] ?>&nbsp;
		                <select id="global_search_module" name="global_search_module" onChange="displayModuleList(this);">
			<option value="All"><?php echo $app_strings['COMBO_ALL'] ?></option>
						<?php
						foreach($object_array as $module => $object_name)
						{
							$selected = '';
							if($search_module != '' && $module == $search_module)
								$selected = 'selected';
							if($search_module == '' && $module == 'All')
								$selected = 'selected';
							?>
							<?php if(isPermitted($module,"index") == "yes")
							{
							?> 
							<option value="<?php echo $module; ?>" <?php echo $selected; ?> ><?php echo $app_strings[$module]; ?></option>
							<?php
							}
						}	
						?>
		     		</select>
		        </td>
		     </tr>
		</table>
	<?php
}

/*To get the modules allowed for global search this function returns all the 
 * modules which supports global search as an array in the following structure 
 * array($module_name1=>$object_name1,$module_name2=>$object_name2,$module_name3=>$object_name3,$module_name4=>$object_name4,-----);
 */
 function getSearchModules()
 {
	 global $adb;
	 // vtlib customization: Ignore disabled modules.
	 //$sql = 'select distinct vtiger_field.tabid,name from vtiger_field inner join vtiger_tab on vtiger_tab.tabid=vtiger_field.tabid where vtiger_tab.tabid not in (16,29)';
	 $sql = 'select distinct vtiger_field.tabid,name from vtiger_field inner join vtiger_tab on vtiger_tab.tabid=vtiger_field.tabid where vtiger_tab.tabid not in (16,29) and vtiger_tab.presence != 1';
	 // END
	$result = $adb->pquery($sql, array());
	while($module_result = $adb->fetch_array($result))
	{
		$modulename = $module_result['name'];
		if($modulename != 'Calendar')
		{
			$return_arr[$modulename] = $modulename;
		}else
		{
			$return_arr[$modulename] = 'Activity';
		}
	}
	return $return_arr;
 }

?>
