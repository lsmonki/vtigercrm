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
require_once('modules/Contacts/Contact.php');
require_once('modules/Accounts/Account.php');
require_once('modules/Potentials/Opportunity.php');
require_once('modules/Leads/Lead.php');
require_once('modules/Faq/Faq.php');
require_once('modules/Vendors/Vendor.php');
require_once('modules/PriceBooks/PriceBook.php');
require_once('modules/Quotes/Quote.php');
require_once('modules/PurchaseOrder/PurchaseOrder.php');
require_once('modules/SalesOrder/SalesOrder.php');
require_once('modules/Invoice/Invoice.php');
require_once('modules/Campaigns/Campaign.php');
require_once('modules/Home/language/en_us.lang.php');
require_once('include/database/PearDatabase.php');

require_once('Smarty_setup.php');
global $mod_strings;

//echo get_module_title("", "Search Results", true); 
if(isset($_REQUEST['query_string']) && preg_match("/[\w]/", $_REQUEST['query_string'])) {

	//module => object
	$object_array = Array(
				'Potentials'=>'Potential',
				'Accounts'=>'Account',
				'Contacts'=>'Contact',
				'Leads'=>'Lead',
				'Notes'=>'Note',
				'Activities'=>'Activity',
				'Emails'=>'Email',
				'HelpDesk'=>'HelpDesk',
				'Products'=>'Product',
				'Faq'=>'Faq',
				//'Events'=>'',
				'Vendors'=>'Vendor',
				'PriceBooks'=>'PriceBook',
				'Quotes'=>'Quote',
				'PurchaseOrder'=>'Order',
				'SalesOrder'=>'SalesOrder',
				'Invoice'=>'Invoice',
				'Campaigns'=>'Campaign'
			     );
	global $adb;
	global $current_user;
	global $theme;
	$theme_path="themes/".$theme."/";
	$image_path=$theme_path."images/";

	$search_val = $_REQUEST['query_string'];
	$search_module = $_REQUEST['search_module'];

	getComboList($search_module);

	foreach($object_array as $module => $object_name)
	{
		$focus = new $object_name();

		$smarty = new vtigerCRM_Smarty;

		$smarty->assign("MOD", $mod_strings);
		$smarty->assign("APP", $app_strings);
		$smarty->assign("IMAGE_PATH",$image_path);
		$smarty->assign("MODULE",$module);
		$smarty->assign("SEARCH_MODULE",$_REQUEST['search_module']);
		$smarty->assign("SINGLE_MOD",'Account');

		$listquery = getListQuery($module);

		if($search_module != '')//This is for Tag search
		{
			$where = getTagWhere($search_val,$current_user->id);
		}
		else			//This is for Global search
		{
			$where = getUnifiedWhere($listquery,$module,$search_val);
		}

		if($where != '')
			$listquery .= ' and '.$where;
		
		$list_result = $adb->query($listquery);
		$noofrows = $adb->num_rows($list_result);

		if($noofrows >= 1)
			$list_max_entries_per_page = $noofrows;
		//Here we can change the max list entries per page per module
		$navigation_array = getNavigationValues(1, $noofrows, $list_max_entries_per_page);

		$listview_header = getListViewHeader($focus,$module);
		$listview_entries = getListViewEntries($focus,$module,$list_result,$navigation_array,"","","EditView","Delete","");

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

		$smarty->assign("MODULES_LIST", $object_array);

		$smarty->display("GlobalListView.tpl");
	}

}
else {
	echo "<br><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<em>".$mod_strings['ERR_ONE_CHAR']."</em>";
}


function getUnifiedWhere($listquery,$module,$search_val)
{
	global $adb;

	$query = "select * from field where tabid=".getTabid($module);
	$result = $adb->query($query);
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
				$where .= ' or ';
			$where .= $tablename.'.'.$columnname.' like "%'.$search_val.'%"';
		}
	}

	return $where;
}
function getTagWhere($search_val,$current_user_id)
{
	require_once('include/freetag/freetag.class.php');

	$freetag_obj = new freetag();

	$crmid_array = $freetag_obj->get_objects_with_tag_all($search_val,$current_user_id);

	$where = '';
	if(count($crmid_array) > 0)
	{
		$where = ' crmentity.crmid in (';
		foreach($crmid_array as $index => $crmid)
		{
			$where .= $crmid.',';
		}
		$where = trim($where,',').')';
	}

	return $where;
}
function getComboList($search_module)
{
	global $object_array;

	?>
		<script language="JavaScript" type="text/javascript" src="include/js/general.js"></script>
		<script>
		function displayModuleList(selectmodule_view)
		{
			<?php
			foreach($object_array as $module => $object_name)
			{
				?>
				mod = "global_list_"+"<?php echo $module; ?>";
				if(selectmodule_view.options[selectmodule_view.options.selectedIndex].value == "All")
					show(mod);
				else
					hide(mod);
				<?php
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
		        <td>&nbsp;</td>
		        <td nowrap align="right">Show Results in &nbsp;
		                <select name="global_search_module" onChange="displayModuleList(this);">
		                        <option value="All">All</option>
						<?php
						foreach($object_array as $module => $object_name)
						{
							$selected = '';
							if($search_module != '' && $module == $search_module)
								$selected = 'selected';
							if($search_module == '' && $module == 'Contacts')
								$selected = 'selected';
							?>
							<option value="<?php echo $module; ?>" <?php echo $selected; ?> ><?php echo $module; ?></option>
							<?php
						}
						?>
		     		</select>
		        </td>
		     </tr>
		</table>
	<?php
}
?>
