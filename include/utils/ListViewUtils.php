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
 * $Header: /cvsroot/vtigercrm/vtiger_crm/include/utils/ListViewUtils.php,v 1.32 2006/02/03 06:53:08 mangai Exp $
 * Description:  Includes generic helper functions used throughout the application.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/
  
require_once('include/database/PearDatabase.php');
require_once('include/ComboUtil.php'); //new
require_once('include/utils/CommonUtils.php'); //new

/**This function is used to get the list view header values in a list view
*Param $focus - module object
*Param $module - module name
*Param $sort_qry - sort by value
*Param $sorder - sorting order (asc/desc)
*Param $order_by - order by
*Param $relatedlist - flag to check whether the header is for listvie or related list
*Param $oCv - Custom view object
*Returns the listview header values in an array
*/
function getListViewHeader($focus, $module,$sort_qry='',$sorder='',$order_by='',$relatedlist='',$oCv='',$relatedmodule='')
{
	global $log;
	$log->debug("Entering getListViewHeader(".$focus.",". $module.",".$sort_qry.",".$sorder.",".$order_by.",".$relatedlist.",".$oCv.") method ...");
	global $adb;
	global $theme;
	global $app_strings;
	global $mod_strings;

	$arrow='';
	$qry = getURLstring($focus);
	$theme_path="themes/".$theme."/";
	$image_path=$theme_path."images/";
	$list_header = Array();

	//Get the tabid of the module
	$tabid = getTabid($module);
	global $current_user;
	//added for customview 27/5
	if($oCv)
	{
		if(isset($oCv->list_fields))
		{
			$focus->list_fields = $oCv->list_fields;
		}
	}

	//Added to reduce the no. of queries logging for non-admin user -- by Minnie-start
	$field_list ='(';
	$j=0;
	require('user_privileges/user_privileges_'.$current_user->id.'.php');
	foreach($focus->list_fields as $name=>$tableinfo)
	{
		$fieldname = $focus->list_fields_name[$name];
		if($oCv)
		{
			if(isset($oCv->list_fields_name))
			{
				$fieldname = $oCv->list_fields_name[$name];
			}
		}
		if($j != 0)
		{
			$field_list .= ', ';
		}
		$field_list .= "'".$fieldname."'";
		$j++;
	}
	$field_list .=')';
	if($is_admin==false)
	{
		$profileList = getCurrentUserProfileList();
		$query  = "SELECT DISTINCT field.fieldname
			FROM field
			INNER JOIN profile2field
				ON profile2field.fieldid = field.fieldid
			INNER JOIN def_org_field
				ON def_org_field.fieldid = field.fieldid
			WHERE field.tabid = ".$tabid."
			AND profile2field.visible = 0
			AND def_org_field.visible = 0
			AND profile2field.profileid IN ".$profileList."
			AND field.fieldname IN ".$field_list;
		$result = $adb->query($query);
		$field=Array();
		for($k=0;$k < $adb->num_rows($result);$k++)
		{
			$field[]=$adb->query_result($result,$k,"fieldname");
		}
	}
	//end

	//modified for customview 27/5 - $app_strings change to $mod_strings
	foreach($focus->list_fields as $name=>$tableinfo)
	{
		//added for customview 27/5
		if($oCv)
		{
			if(isset($oCv->list_fields_name))
			{
				$fieldname = $oCv->list_fields_name[$name];
			}else
			{
				$fieldname = $focus->list_fields_name[$name];
			}
		}else
		{
			$fieldname = $focus->list_fields_name[$name];
		}

		if($is_admin == true || $profileGlobalPermission[1] == 0 || $profileGlobalPermission[2] ==0 || in_array($fieldname,$field))
		{
			if(isset($focus->sortby_fields) && $focus->sortby_fields !='')
			{
				//Added on 14-12-2005 to avoid if and else check for every list field for arrow image and change order
				$change_sorder = array('ASC'=>'DESC','DESC'=>'ASC');
				$arrow_gif = array('ASC'=>'arrow_down.gif','DESC'=>'arrow_up.gif');

				foreach($focus->list_fields[$name] as $tab=>$col)
				{
					if(in_array($col,$focus->sortby_fields))
					{
						if($order_by == $col)
						{
							$temp_sorder = $change_sorder[$sorder];
							$arrow = "<img src ='".$image_path.$arrow_gif[$sorder]."' border='0'>";
						}
						else
						{
							$temp_sorder = 'ASC';
						}
					/*	if($relatedlist !='')
						{
							if($app_strings[$name])
							{
								$name = $app_strings[$name];
							}
							else
							{
								$name = $mod_strings[$name];
							}
						}
						else
						{*/
							if($app_strings[$name])
							{
								$lbl_name = $app_strings[$name];
							}
							else
							{
								$lbl_name = $mod_strings[$name];
							}
							//added to display currency symbol in listview header
							if($lbl_name =='Amount')
							{
								$currencyid=fetchCurrency($current_user->id);
								$rate_symbol=getCurrencySymbolandCRate($currencyid);
								$curr_symbol = $rate_symbol['symbol'];
								$lbl_name .=': (in '.$curr_symbol.')';
							}
							if($relatedlist !='')
								$name = "<a href='index.php?module=".$relatedmodule."&action=CallRelatedList&relmodule=".$module."&order_by=".$col."&record=".$relatedlist."&sorder=".$temp_sorder."' class='listFormHeaderLinks'>".$lbl_name."&nbsp;".$arrow."</a>";
							else
								$name = "<a href='javascript:;' onClick='getListViewEntries_js(\"".$module."\",\"order_by=".$col."&sorder=".$temp_sorder."".$sort_qry."\");' class='listFormHeaderLinks'>".$lbl_name."&nbsp;".$arrow."</a>";
							$arrow = '';
						//}
					}
					else
					{       if($app_strings[$name])
						{
							$name = $app_strings[$name];
						}
						elseif($mod_strings[$name])
						{
							$name = $mod_strings[$name];
						}
					}

				}
			}
																	//added to display currency symbol in related listview header
																	if($name =='Amount' && $relatedlist !='' )
		{
			$currencyid=fetchCurrency($current_user->id);
			$rate_symbol=getCurrencySymbolandCRate($currencyid);
			$curr_symbol = $rate_symbol['symbol'];
			$name .=': (in '.$curr_symbol.')';
		}

		//Added condition to hide the close column in Related Lists
		if($name == 'Close' && $relatedlist != '')
		{
			// $list_header[] = '';
		}
		else
		{
			$list_header[]=$name;
		}
	}
     }
	$log->debug("Exiting getListViewHeader method ...");
	return $list_header;
}

/**This function is used to get the list view header in popup 
*Param $focus - module object
*Param $module - module name
*Param $sort_qry - sort by value
*Param $sorder - sorting order (asc/desc)
*Param $order_by - order by
*Returns the listview header values in an array
*/

function getSearchListViewHeader($focus, $module,$sort_qry='',$sorder='',$order_by='')
{
	global $log;
	$log->debug("Entering getSearchListViewHeader(".$focus.",". $module.",".$sort_qry.",".$sorder.",".$order_by.") method ...");
	global $adb;
	global $theme;
	global $app_strings;
        global $mod_strings,$current_user;
        $arrow='';
	$list_header = Array();
	$tabid = getTabid($module);
	//Added to reduce the no. of queries logging for non-admin user -- by Minnie-start
	$field_list ='(';
	$j=0;
	require('user_privileges/user_privileges_'.$current_user->id.'.php');
	foreach($focus->search_fields as $name=>$tableinfo)
	{
		$fieldname = $focus->search_fields_name[$name];
		if($j != 0)
		{
			$field_list .= ', ';
		}
		$field_list .= "'".$fieldname."'";
		$j++;
	}
	$field_list .=')';
	if($is_admin==false)
	{
		$profileList = getCurrentUserProfileList();
		$query  = "SELECT DISTINCT field.fieldname
			FROM field
			INNER JOIN profile2field
				ON profile2field.fieldid = field.fieldid
			INNER JOIN def_org_field
				ON def_org_field.fieldid = field.fieldid
			WHERE field.tabid = ".$tabid."
			AND profile2field.visible=0
			AND def_org_field.visible=0
			AND profile2field.profileid IN ".$profileList."
			AND field.fieldname IN ".$field_list;
		$result = $adb->query($query);
		$field=Array();
		for($k=0;$k < $adb->num_rows($result);$k++)
		{
			$field[]=$adb->query_result($result,$k,"fieldname");
		}
	}
	//end
	$theme_path="themes/".$theme."/";
	$image_path=$theme_path."images/";		
	foreach($focus->search_fields as $name=>$tableinfo)
	{
		$fieldname = $focus->search_fields_name[$name];
		$tabid = getTabid($module);

		global $current_user;
                require('user_privileges/user_privileges_'.$current_user->id.'.php');
		if($is_admin==false)
		{
                	$profileList = getCurrentUserProfileList();
                	$query = "SELECT profile2field.*
				FROM field
				INNER JOIN profile2field
					ON profile2field.fieldid = field.fieldid
				INNER JOIN def_org_field
					ON def_org_field.fieldid = field.fieldid
				WHERE field.tabid = ".$tabid."
				AND profile2field.visible = 0
				AND def_org_field.visible = 0
				AND profile2field.profileid IN ".$profileList."
				AND field.fieldname = '".$fieldname."'";

                	$result = $adb->query($query);
                }

                if($is_admin == true || $profileGlobalPermission[1] == 0 || $profileGlobalPermission[2] ==0 || $adb->num_rows($result) == 1)
                {
		
			if(isset($focus->sortby_fields) && $focus->sortby_fields !='')
                        {
                                foreach($focus->search_fields[$name] as $tab=>$col)
                                {
                                        if(in_array($col,$focus->sortby_fields))
                                        {
                                                if($order_by == $col)
                                                {
                                                        if($sorder == 'ASC')
                                                        {
                                                                $sorder = "DESC";
                                                                $arrow = "<img src ='".$image_path."arrow_down.gif' border='0'>";
                                                         }
                                                        else
                                                        {
                                                                $sorder = 'ASC';
                                                                $arrow = "<img src ='".$image_path."arrow_up.gif' border='0'>";
                                                        }
                                                }
                                                $name = "<a href='index.php?module=".$module."&action=Popup".$sort_qry."&order_by=".$col."&sorder=".$sorder."' class='listFormHeaderLinks'>".$app_strings[$name]."&nbsp;".$arrow."</a>";
                                                $arrow = '';
                                        }
                                        else
                                                $name = $app_strings[$name];
                                }
                        }
			$list_header[]=$name;
		}
	}	
	$log->debug("Exiting getSearchListViewHeader method ...");
	return $list_header;

}

/**This function generates the navigation array in a listview 
*Param $display - start value of the navigation
*Param $noofrows - no of records
*Param $limit - no of entries per page
*Returns an array type
*/

//code contributed by raju for improved pagination
function getNavigationValues($display, $noofrows, $limit)
{
	global $log;
	$log->debug("Entering getNavigationValues(".$display.",".$noofrows.",".$limit.") method ...");
	$navigation_array = Array();   
	global $limitpage_navigation;
	if(isset($_REQUEST['allflag']) && $_REQUEST['allflag'] == 'All'){
		$navigation_array['start'] =1;
		$navigation_array['first'] = 1;
		$navigation_array['end'] = 1;
		$navigation_array['prev'] =0;
		$navigation_array['next'] =0;
		$navigation_array['end_val'] =$noofrows;
		$navigation_array['current'] =1;
		$navigation_array['allflag'] ='Normal';
		$navigation_array['verylast'] =1;
		$log->debug("Exiting getNavigationValues method ...");
		return $navigation_array;
	}
	if($noofrows != 0)
	$start = ((($display * $limit) - $limit)+1);
	else
	$start = 0;
	
	$end = $start + ($limit-1);
	if($end > $noofrows)
	{
		$end = $noofrows;
	}
	$paging = ceil ($noofrows / $limit);
	// Display the navigation
	if ($display > 1) {
		$previous = $display - 1;
	}
	else {
		$previous=0;
	}
	if($noofrows < $limit)
	{
		$first = '';
	}
	elseif ($noofrows != $limit) {
		$last = $paging;
		$first = 1;
		if ($paging > $limitpage_navigation) {
			$first = $display-floor(($limitpage_navigation/2));
			if ($first<1) $first=1;
			$last = ($limitpage_navigation - 1) + $first;
		}
		if ($last > $paging ) {
			$first = $paging - ($limitpage_navigation - 1);
			$last = $paging;
		}
	}
	if ($display < $paging) {
		$next = $display + 1;
	}
	else {
		$next=0;
	}
	$navigation_array['start'] = $start;
	$navigation_array['first'] = $first;
	$navigation_array['end'] = $last;
	$navigation_array['prev'] = $previous;
	$navigation_array['next'] = $next;
	$navigation_array['end_val'] = $end;
	$navigation_array['current'] = $display;
	$navigation_array['allflag'] ='All';
	$navigation_array['verylast'] =$paging;
	$log->debug("Exiting getNavigationValues method ...");
	return $navigation_array;
}


//End of code contributed by raju for improved pagination

/**This function generates the List view entries in a list view 
*Param $focus - module object
*Param $list_result - resultset of a listview query
*Param $navigation_array - navigation values in an array
*Param $relatedlist - check for related list flag
*Param $returnset - list query parameters in url string
*Param $edit_action - Edit action value
*Param $del_action - delete action value
*Param $oCv - customview object
*Returns an array type
*/

//parameter added for customview $oCv 27/5
function getListViewEntries($focus, $module,$list_result,$navigation_array,$relatedlist='',$returnset='',$edit_action='EditView',$del_action='Delete',$oCv='')
{
	global $log;
	$log->debug("Entering getListViewEntries(".$focus.",". $module.",".$list_result.",".$navigation_array.",".$relatedlist.",".$returnset.",".$edit_action.",".$del_action.",".$oCv.") method ...");
	$tabname = getParentTab();
	global $adb,$current_user;
	global $app_strings;
	$noofrows = $adb->num_rows($list_result);
	$list_block = Array();
	global $theme;
	$evt_status;
	$theme_path="themes/".$theme."/";
	$image_path=$theme_path."images/";
	//getting the fieldtable entries from database
	$tabid = getTabid($module);

	//added for customview 27/5
	if($oCv)
	{
		if(isset($oCv->list_fields))
		{
			$focus->list_fields = $oCv->list_fields;
		}
	}
	//Added to reduce the no. of queries logging for non-admin user -- by minnie-start
	$field_list ='(';
	$j=0;
	require('user_privileges/user_privileges_'.$current_user->id.'.php');
	foreach($focus->list_fields as $name=>$tableinfo)
	{
		$fieldname = $focus->list_fields_name[$name];
		if($oCv)
		{
			if(isset($oCv->list_fields_name))
			{
				$fieldname = $oCv->list_fields_name[$name];
			}
		}
		if($j != 0)
		{
			$field_list .= ', ';
		}
		$field_list .= "'".$fieldname."'";
		$j++;
	}
	$field_list .=')';
	if($is_admin==false)
	{
		$profileList = getCurrentUserProfileList();
		$query  = "SELECT DISTINCT field.fieldname
			FROM field
			INNER JOIN profile2field
				ON profile2field.fieldid = field.fieldid
			INNER JOIN def_org_field
				ON def_org_field.fieldid = field.fieldid
			WHERE field.tabid = ".$tabid."
			AND profile2field.visible = 0
			AND def_org_field.visible = 0
			AND profile2field.profileid IN ".$profileList."
			AND field.fieldname IN ".$field_list;
		$result = $adb->query($query);
		$field=Array();
		for($k=0;$k < $adb->num_rows($result);$k++)
		{
			$field[]=$adb->query_result($result,$k,"fieldname");
		}
	}
	//constructing the uitype and columnname array
	$ui_col_array=Array();

	$query = "SELECT uitype, columnname, fieldname
		FROM field
		WHERE tabid = ".$tabid."
		AND fieldname IN".$field_list;
	$result = $adb->query($query);
	$num_rows=$adb->num_rows($result);
	for($i=0;$i<$num_rows;$i++)
	{
		$tempArr=array();
		$uitype=$adb->query_result($result,$i,'uitype');
		$columnname=$adb->query_result($result,$i,'columnname');
		$field_name=$adb->query_result($result,$i,'fieldname');
		$tempArr[$uitype]=$columnname;
		$ui_col_array[$field_name]=$tempArr;
	}
	//end
	if($navigation_array['start'] !=0)
	for ($i=$navigation_array['start']; $i<=$navigation_array['end_val']; $i++)
	{
		$list_header =Array();
		//Getting the entityid
		$entity_id = $adb->query_result($list_result,$i-1,"crmid");
		$owner_id = $adb->query_result($list_result,$i-1,"smownerid");

		// Fredy Klammsteiner, 4.8.2005: changes from 4.0.1 migrated to 4.2
		// begin: Armando Lüscher 05.07.2005 -> §priority
		// Code contri buted by fredy Desc: Set Priority color
		$priority = $adb->query_result($list_result,$i-1,"priority");

		$font_color_high = "color:#00DD00;";
		$font_color_medium = "color:#DD00DD;";
		$P_FONT_COLOR = "";
		switch ($priority)
		{
			case 'High':
				$P_FONT_COLOR = $font_color_high;
				break;
			case 'Medium':
				$P_FONT_COLOR = $font_color_medium;
				break;
			default:
				$P_FONT_COLOR = "";
		}
		//end: Armando Lüscher 05.07.2005 -> §priority

		foreach($focus->list_fields as $name=>$tableinfo)
		{
			$fieldname = $focus->list_fields_name[$name];

			//added for customview 27/5
			if($oCv)
			{
				if(isset($oCv->list_fields_name))
				{
					$fieldname = $oCv->list_fields_name[$name];
				}
			}

			if($is_admin==true || $profileGlobalPermission[1] == 0 || $profileGlobalPermission[2] ==0 || in_array($fieldname,$field))
			{



				if($fieldname == '')
				{
					$table_name = '';
					$column_name = '';
					foreach($tableinfo as $tablename=>$colname)
					{
						$table_name=$tablename;
						$column_name = $colname;
					}
					$value = $adb->query_result($list_result,$i-1,$colname);
				}
				else
				{

					if(($module == 'Activities' || $module == 'Tasks' || $module == 'Meetings' || $module == 'Emails' || $module == 'HelpDesk' || $module == 'Invoice' || $module == 'Leads' || $module == 'Contacts') && (($name=='Related to') || ($name=='Contact Name') || ($name=='Close') || ($name == 'First Name')))
					{
						$status = $adb->query_result($list_result,$i-1,"status");
						if($status == '')
							$status = $adb->query_result($list_result,$i-1,"eventstatus");
						if ($name=='Related to')
							$value=getRelatedTo($module,$list_result,$i-1);
						if($name=='Contact Name')
						{
							$first_name = $adb->query_result($list_result,$i-1,"firstname");
							$last_name = $adb->query_result($list_result,$i-1,"lastname");
							$contact_id = $adb->query_result($list_result,$i-1,"contactid");
							$contact_name = "";
							$value="";
							if($last_name != 'NULL')
								$contact_name .= $last_name;
							if($first_name != 'NULL')
								$contact_name .= " ".$first_name;
							//Added to get the contactname for activities custom view - t=2190
							if($contact_id != '' && $last_name == '')
							{
								$contact_name = getContactName($contact_id);
							}

							if(($contact_name != "") && ($contact_id !='NULL'))
								// Fredy Klammsteiner, 4.8.2005: changes from 4.0.1 migrated to 4.2
								$value =  "<a href='index.php?module=Contacts&action=DetailView&record=".$contact_id."' style='".$P_FONT_COLOR."'>".$contact_name."</a>"; // Armando Lüscher 05.07.2005 -> §priority -> Desc: inserted style="$P_FONT_COLOR"
						}
						if($name == "First Name")
						{
							$first_name = $adb->query_result($list_result,$i-1,"firstname");
							$value = '<a href="index.php?action=DetailView&module='.$module.'&record='.$entity_id.'">'.$first_name.'</a>';

						}

						if ($name == 'Close')
						{
							if($status =='Deferred' || $status == 'Completed' || $status == 'Held' || $status == '')
							{
								$value="";
							}
							else
							{
								$activityid = $adb->query_result($list_result,$i-1,"activityid");
								$activitytype = $adb->query_result($list_result,$i-1,"activitytype");
								if($activitytype=='Task')
									$evt_status='&status=Completed';
								else
									$evt_status='&eventstatus=Held';
								if(isPermitted("Activities",'EditView',$activityid) == 'yes')
								{
									// Fredy Klammsteiner, 4.8.2005: changes from 4.0.1 migrated to 4.2
									$value = "<a href='index.php?return_module=Activities&return_action=index&return_id=".$activityid."&return_viewname=".$oCv->setdefaultviewid."&action=Save&module=Activities&record=".$activityid."&change_status=true".$evt_status."&start=".$navigation_array['current']."&allflag=".$navigation_array['allflag']."' style='".$P_FONT_COLOR."'>X</a>"; // Armando Lüscher 05.07.2005 -> §priority -> Desc: inserted style="$P_FONT_COLOR"
								}
								else
								{
									$value = "";
								}

							}
						}
					}
					elseif($module == "Products" && $name == "Related to")
					{
						$value=getRelatedTo($module,$list_result,$i-1);
					}
					elseif($module == 'Notes' && $name=='Related to')
					{
						$value=getRelatedTo($module,$list_result,$i-1);
					}
					elseif($name=='Account Name')
					{
						//modified for customview 27/5
						if($module == 'Accounts')
						{
							$account_id = $adb->query_result($list_result,$i-1,"crmid");
							$account_name = getAccountName($account_id);
							// Fredy Klammsteiner, 4.8.2005: changes from 4.0.1 migrated to 4.2
							$value = '<a href="index.php?module=Accounts&action=DetailView&record='.$account_id.'&parenttab='.$tabname.'" style="'.$P_FONT_COLOR.'">'.$account_name.'</a>'; // Armando Lüscher 05.07.2005 -> §priority -> Desc: inserted style="$P_FONT_COLOR"
						}else
						{
							$account_id = $adb->query_result($list_result,$i-1,"accountid");
							$account_name = getAccountName($account_id);
							// Fredy Klammsteiner, 4.8.2005: changes from 4.0.1 migrated to 4.2
							$value = '<a href="index.php?module=Accounts&action=DetailView&record='.$account_id.'&parenttab='.$tabname.'" style="'.$P_FONT_COLOR.'">'.$account_name.'</a>'; // Armando Lüscher 05.07.2005 -> §priority -> Desc: inserted style="$P_FONT_COLOR"
						}
					}
					elseif(( $module == 'HelpDesk' || $module == 'PriceBook' || $module == 'Quotes' || $module == 'PurchaseOrder' || $module == 'Faq') && $name == 'Product Name')
					{
						if($module == 'HelpDesk' || $module == 'Faq')
							$product_id = $adb->query_result($list_result,$i-1,"product_id");
						else
							$product_id = $adb->query_result($list_result,$i-1,"productid");

						if($product_id != '')
							$product_name = getProductName($product_id);
						else
							$product_name = '';

						$value = '<a href="index.php?module=Products&action=DetailView&record='.$product_id.'">'.$product_name.'</a>';
					}
					elseif($module == 'Quotes' && $name == 'Potential Name')
					{
						$potential_id = $adb->query_result($list_result,$i-1,"potentialid");
						$potential_name = getPotentialName($potential_id);
						$value = '<a href="index.php?module=Potentials&action=DetailView&record='.$potential_id.'">'.$potential_name.'</a>';
					}
					elseif($owner_id == 0 && $name == 'Assigned To')
					{
						$value=$adb->query_result($list_result,$i-1,"groupname");
					}
					elseif($module =='Emails' && $relatedlist != '' && $name=='Subject')
					{
						$list_result_count = $i-1;
						$tmp_value = getValue($ui_col_array,$list_result,$fieldname,$focus,$module,$entity_id,$list_result_count,"list","",$returnset,$oCv->setdefaultviewid);
						$value = '<a href="javascript:;" onClick="OpenCompose(\''.$entity_id.'\',\'edit\');">'.$tmp_value.'</a>';

					}
					else
					{
						$list_result_count = $i-1;
						$value = getValue($ui_col_array,$list_result,$fieldname,$focus,$module,$entity_id,$list_result_count,"list","",$returnset,$oCv->setdefaultviewid);
					}
				}
				//Added condition to hide the close symbol in Related Lists
				if($name == 'Close' && $relatedlist != '')
				{
					//$list_header[]= '';
				}
				else
				{
					$list_header[] = $value;
				}
				if($fieldname=='filename')
				{
					$filename = $adb->query_result($list_result,$list_result_count,$fieldname);
				}
			}

		}
		$varreturnset = '';
		if($returnset=='')
			$varreturnset = '&return_module='.$module.'&return_action=index';
		else
			$varreturnset = $returnset;


		if($module == 'Activities')
		{
			$actvity_type = $adb->query_result($list_result,$list_result_count,'activitytype');
			if($actvity_type == 'Task')
				$varreturnset .= '&activity_mode=Task';
			else
				$varreturnset .= '&activity_mode=Events';
		}
		$list_block[$entity_id] = $list_header;

	}
	$log->debug("Exiting getListViewEntries method ...");
	return $list_block;
	
}

/**This function generates the List view entries in a popup list view 
*Param $focus - module object
*Param $list_result - resultset of a listview query
*Param $navigation_array - navigation values in an array
*Param $relatedlist - check for related list flag
*Param $returnset - list query parameters in url string
*Param $edit_action - Edit action value
*Param $del_action - delete action value
*Param $oCv - customview object
*Returns an array type
*/


function getSearchListViewEntries($focus, $module,$list_result,$navigation_array)
{
	global $log;
	$log->debug("Entering getSearchListViewEntries(".$focus.",". $module.",".$list_result.",".$navigation_array.") method ...");
	global $adb,$theme,$current_user;
	$noofrows = $adb->num_rows($list_result);
	$list_header = '';
	$theme_path="themes/".$theme."/";
	$image_path=$theme_path."images/";
	$list_block = Array();

	//getting the fieldtable entries from database
	$tabid = getTabid($module);
	require('user_privileges/user_privileges_'.$current_user->id.'.php');
	
	//Added to reduce the no. of queries logging for non-admin user -- by Minnie-start
	$field_list ='(';
	$j=0;
	foreach($focus->search_fields as $name=>$tableinfo)
	{
		$fieldname = $focus->search_fields_name[$name];
		if($j != 0)
		{
			$field_list .= ', ';
		}
		$field_list .= "'".$fieldname."'";
		$j++;
	}
	$field_list .=')';
	if($is_admin==false)
	{
		$profileList = getCurrentUserProfileList();
		$query  = "SELECT DISTINCT field.fieldname
			FROM field
			INNER JOIN profile2field
				ON profile2field.fieldid = field.fieldid
			INNER JOIN def_org_field
				ON def_org_field.fieldid = field.fieldid
			WHERE field.tabid = ".$tabid."
			AND profile2field.visible = 0
			AND def_org_field.visible = 0
			AND profile2field.profileid IN ".$profileList."
			AND field.fieldname IN ".$field_list;
		$result = $adb->query($query);
		$field=Array();
		for($k=0;$k < $adb->num_rows($result);$k++)
		{
			$field[]=$adb->query_result($result,$k,"fieldname");
		}
	}
	//constructing the uitype and columnname array
	$ui_col_array=Array();

	$query = "SELECT uitype, columnname, fieldname
		FROM field
		WHERE tabid=".$tabid."
		AND fieldname IN ".$field_list;
	$result = $adb->query($query);
	$num_rows=$adb->num_rows($result);
	for($i=0;$i<$num_rows;$i++)
	{
		$tempArr=array();
		$uitype=$adb->query_result($result,$i,'uitype');
		$columnname=$adb->query_result($result,$i,'columnname');
		$field_name=$adb->query_result($result,$i,'fieldname');
		$tempArr[$uitype]=$columnname;
		$ui_col_array[$field_name]=$tempArr;
	}
	//end
	if($navigation_array['end_val'] > 0)
	{
		for ($i=$navigation_array['start']; $i<=$navigation_array['end_val']; $i++)
		{

			//Getting the entityid
			$entity_id = $adb->query_result($list_result,$i-1,"crmid");
			$list_header=Array();

			foreach($focus->search_fields as $name=>$tableinfo)
			{
				$fieldname = $focus->search_fields_name[$name];

				if($is_admin==false)
				{
					$profileList = getCurrentUserProfileList();
					$query = "SELECT profile2field.*
						FROM field
						INNER JOIN profile2field
						ON profile2field.fieldid = field.fieldid
						INNER JOIN def_org_field
						ON def_org_field.fieldid = field.fieldid
						WHERE field.tabid = ".$tabid."
						AND profile2field.visible = 0
						AND def_org_field.visible = 0
						AND profile2field.profileid IN ".$profileList."
						AND field.fieldname = '".$fieldname."'";

					$result = $adb->query($query);
				}

				if($is_admin == true || $profileGlobalPermission[1] == 0 || $profileGlobalPermission[2] ==0 || $adb->num_rows($result) == 1)
				{			
					if($fieldname == '')
					{
						$table_name = '';
						$column_name = '';
						foreach($tableinfo as $tablename=>$colname)
						{
							$table_name=$tablename;
							$column_name = $colname;
						}
						$value = $adb->query_result($list_result,$i-1,$colname); 
					}
					else
					{
						if(($module == 'Calls' || $module == 'Tasks' || $module == 'Meetings' || $module == 'Emails') && (($name=='Related to') || ($name=='Contact Name')))
						{
							if ($name=='Related to')
								$value=getRelatedTo($module,$list_result,$i-1);
							if($name=='Contact Name')
							{
								$first_name = $adb->query_result($list_result,$i-1,"firstname");
								$last_name = $adb->query_result($list_result,$i-1,"lastname");
								$contact_id = $adb->query_result($list_result,$i-1,"contactid");
								$contact_name = "";
								$value="";
								if($last_name != 'NULL')
									$contact_name .= $last_name;
								if($first_name != 'NULL')
									$contact_name .= " ".$first_name;
								if(($contact_name != "") && ($contact_id !='NULL'))
									$value =  "<a href='index.php?module=Contacts&action=DetailView&record=".$contact_id."'>".$contact_name."</a>";
							}
						}
						elseif(($module == 'Faq' || $module == 'Notes') && $name=='Related to')
						{
							$value=getRelatedToEntity($module,$list_result,$i-1);
						}
						elseif($name=='Account Name' && ($module == 'Potentials' || $module == 'SalesOrder' || $module == 'Quotes'))
						{
							$account_id = $adb->query_result($list_result,$i-1,"accountid");
							$account_name = getAccountName($account_id);
							$value = $account_name;
						}
						elseif($name=='Quote Name' && $module == 'SalesOrder')
						{
							$quote_id = $adb->query_result($list_result,$i-1,"quoteid");
							$quotename = getQuoteName($quote_id);
							$value = $quotename;
						}
						else
						{
							$list_result_count = $i-1;
							$value = getValue($ui_col_array,$list_result,$fieldname,$focus,$module,$entity_id,$list_result_count,"search",$focus->popup_type);
						}

					}
					$list_header[]=$value;
				}
			}	
			$list_block[$entity_id]=$list_header;
		}
	}
	$log->debug("Exiting getSearchListViewEntries method ...");
	return $list_block;
}


/**This function generates the value for a given field namee 
*Param $field_result - field result in array
*Param $list_result - resultset of a listview query
*Param $fieldname - field name
*Param $focus - module object
*Param $module - module name
*Param $entity_id - entity id
*Param $list_result_count - list result count
*Param $mode - mode type 
*Param $popuptype - popup type
*Param $returnset - list query parameters in url string
*Param $viewid - custom view id
*Returns an string value
*/


function getValue($field_result, $list_result,$fieldname,$focus,$module,$entity_id,$list_result_count,$mode,$popuptype,$returnset='',$viewid='')
{
	global $log;
	$log->debug("Entering getValue(".$field_result.",". $list_result.",".$fieldname.",".$focus.",".$module.",".$entity_id.",".$list_result_count.",".$mode.",".$popuptype.",".$returnset.",".$viewid.") method ...");
	global $adb;
	$tabname = getParentTab();
	$uicolarr=$field_result[$fieldname];
	foreach($uicolarr as $key=>$value)
	{
		$uitype = $key;
	
		$colname = $value;
        }

	//added for getting event status in Custom view - Jaguar
	if($module == 'Activities' && $colname == "status")
	{
		$colname="activitystatus";
	}
	//Ends
	$temp_val = $adb->query_result($list_result,$list_result_count,$colname);

	if(strlen($temp_val) > 40)
        {
                $temp_val = substr($temp_val,0,40).'...';
        }
		
	if($uitype == 52 || $uitype == 53 || $uitype == 77)
	{
		$value = $adb->query_result($list_result,$list_result_count,'user_name');
	}
	elseif($uitype == 5 || $uitype == 6 || $uitype == 23 || $uitype == 70)
	{
		if($temp_val != '' && $temp_val != '0000-00-00')
		{
			$value = getDisplayDate($temp_val);  
		}
		elseif($temp_val == '0000-00-00')
		{
			$value = '';
		}
		else
		{
			$value = $temp_val;
		}
		
	}
	elseif($uitype == 71 || $uitype == 72)
	{
		if($temp_val != '' && $temp_val != 0)
		{       //changes made to remove currency symbol infront of each potential amount
                        $value = $temp_val;
		}
		else
		{
			$value = '';
		}
		
	}
	elseif($uitype == 17)
	{
		$value = '<a href="http://'.$temp_val.'" target="_blank">'.$temp_val.'</a>';
	}
	elseif($uitype == 13)
        {
		if(useInternalMailer() == 1)
                	$value = '<a href="javascript:InternalMailer('.$entity_id.',\'record_id\')">'.$temp_val.'</a>';
		else
                	$value = '<a href="mailto:'.$temp_val.'">'.$temp_val.'</a>';
        }
	elseif($uitype == 56)
	{
		if($temp_val == 1)
		{
			$value = 'yes';
		}
		else
		{
			$value = '';
		}
	}	
	elseif($uitype == 57)
	{
		global $adb;
		if($temp_val != '')
                {
			$sql="SELECT * FROM contactdetails WHERE contactid=".$temp_val;		
			$result=$adb->query($sql);
			$firstname=$adb->query_result($result,0,"firstname");
			$lastname=$adb->query_result($result,0,"lastname");
			$name=$lastname.' '.$firstname;

			$value= '<a href=index.php?module=Contacts&action=DetailView&record='.$temp_val.'>'.$name.'</a>';
		}
		else
			$value='';
	}
	//Added By *Raj* for the Issue ProductName not displayed in CustomView of HelpDesk
	elseif($uitype == 59)
	{
		if($temp_val != '')
		{
			$value = getProductName($temp_val);
		}
		else
		{
			$value = '';
		}
	}
	//End
	elseif($uitype == 61)
	{
			global $adb;

	$attachmentid=$adb->query_result($adb->query("SELECT * FROM seattachmentsrel WHERE crmid = ".$entity_id),0,'attachmentsid');
	$value = '<a href = "index.php?module=uploads&action=downloadfile&return_module='.$module.'&fileid='.$attachmentid.'&filename='.$temp_val.'">'.$temp_val.'</a>';

	}
	elseif($uitype == 62)
	{
		global $adb;

		$parentid = $adb->query_result($list_result,$list_result_count,"parent_id");
		$parenttype = $adb->query_result($list_result,$list_result_count,"parent_type");

		if($parenttype == "Leads")	
		{
			$tablename = "leaddetails";	$fieldname = "lastname";	$idname="leadid";	
		}
		if($parenttype == "Accounts")	
		{
			$tablename = "account";		$fieldname = "accountname";     $idname="accountid";
		}
		if($parenttype == "Products")	
		{
			$tablename = "products";	$fieldname = "productname";     $idname="productid";
		}
		if($parenttype == "HelpDesk")	
		{
			$tablename = "troubletickets";	$fieldname = "title";        	$idname="crmid";
		}
		if($parenttype == "Products")	
		{
			$tablename = "products";	$fieldname = "productname";     $idname="productid";
		}
		if($parenttype == "Invoice")	
		{
			$tablename = "invoice";	$fieldname = "subject";     $idname="invoiceid";
		}


		if($parentid != '')
                {
			$sql="SELECT * FROM ".$tablename." WHERE ".$idname." = ".$parentid;
			$fieldvalue=$adb->query_result($adb->query($sql),0,$fieldname);

			$value='<a href=index.php?module='.$parenttype.'&action=DetailView&record='.$parentid.'&parenttab='.$tabname.'>'.$fieldvalue.'</a>';
		}
		else
			$value='';
	}
	elseif($uitype == 66)
	{
		global $adb;

		$parentid = $adb->query_result($list_result,$list_result_count,"parent_id");
		$parenttype = $adb->query_result($list_result,$list_result_count,"parent_type");

		if($parenttype == "Leads")	
		{
			$tablename = "leaddetails";	$fieldname = "lastname";	$idname="leadid";	
		}
		if($parenttype == "Accounts")	
		{
			$tablename = "account";		$fieldname = "accountname";     $idname="accountid";
		}
		if($parenttype == "HelpDesk")	
		{
			$tablename = "troubletickets";	$fieldname = "title";        	$idname="crmid";
		}
		if($parentid != '')
                {
			$sql="SELECT * FROM ".$tablename." WHERE ".$idname." = ".$parentid;
			$fieldvalue=$adb->query_result($adb->query($sql),0,$fieldname);

			$value='<a href=index.php?module='.$parenttype.'&action=DetailView&record='.$parentid.'&parenttab='.$tabname.'>'.$fieldvalue.'</a>';
		}
		else
			$value='';
	}
	elseif($uitype == 67)
	{
		global $adb;

		$parentid = $adb->query_result($list_result,$list_result_count,"parent_id");
		$parenttype = $adb->query_result($list_result,$list_result_count,"parent_type");

		if($parenttype == "Leads")	
		{
			$tablename = "leaddetails";	$fieldname = "lastname";	$idname="leadid";	
		}
		if($parenttype == "Contacts")	
		{
			$tablename = "contactdetails";		$fieldname = "contactname";     $idname="contactid";
		}
		if($parentid != '')
                {
			$sql="SELECT * FROM ".$tablename." WHERE ".$idname." = ".$parentid;
			$fieldvalue=$adb->query_result($adb->query($sql),0,$fieldname);

			$value='<a href=index.php?module='.$parenttype.'&action=DetailView&record='.$parentid.'&parenttab='.$tabname.'>'.$fieldvalue.'</a>';
		}
		else
			$value='';
	}
	elseif($uitype == 68)
	{
		global $adb;

		$parentid = $adb->query_result($list_result,$list_result_count,"parent_id");
		$parenttype = $adb->query_result($list_result,$list_result_count,"parent_type");

		if($parenttype == '' && $parentid != '')
                        $parenttype = getSalesEntityType($parentid);

		if($parenttype == "Contacts")	
		{
			$tablename = "contactdetails";		$fieldname = "contactname";     $idname="contactid";
		}
		if($parenttype == "Accounts")	
		{
			$tablename = "account";	$fieldname = "accountname";	$idname="accountid";	
		}
		if($parentid != '')
                {
			$sql="SELECT * FROM ".$tablename." WHERE ".$idname." = ".$parentid;
			$fieldvalue=$adb->query_result($adb->query($sql),0,$fieldname);

			$value='<a href=index.php?module='.$parenttype.'&action=DetailView&record='.$parentid.'&parenttab='.$tabname.'>'.$fieldvalue.'</a>';
		}
		else
			$value='';
	}
	elseif($uitype == 78)
        {

		global $adb;
		if($temp_val != '')
                {
			
                        $quote_name = getQuoteName($temp_val);
			$value= '<a href=index.php?module=Quotes&action=DetailView&record='.$temp_val.'&parenttab='.$tabname.'>'.$quote_name.'</a>';
		}
		else
			$value='';
        }
	elseif($uitype == 79)
        {

		global $adb;
		if($temp_val != '')
                {
			
                        $purchaseorder_name = getPoName($temp_val);
			$value= '<a href=index.php?module=PurchaseOrder&action=DetailView&record='.$temp_val.'&parenttab='.$tabname.'>'.$purchaseorder_name.'</a>';
		}
		else
			$value='';
        }
	elseif($uitype == 80)
        {

		global $adb;
		if($temp_val != '')
                {
			
                        $salesorder_name = getSoName($temp_val);
			$value= '<a href=index.php?module=SalesOrder&action=DetailView&record='.$temp_val.'&parenttab='.$tabname.'>'.$salesorder_name.'</a>';
		}
		else
			$value='';
        }
	elseif($uitype == 75 || $uitype == 81)
        {

		global $adb;
		if($temp_val != '')
                {
			
                        $vendor_name = getVendorName($temp_val);
			$value= '<a href=index.php?module=Vendors&action=DetailView&record='.$temp_val.'&parenttab='.$tabname.'>'.$vendor_name.'</a>';
		}
		else
			$value='';
        }
	else
	{
	
		if($fieldname == $focus->list_link_field)
		{
			if($mode == "search")
			{
				if($popuptype == "specific")
				{
					// Added for get the first name of contact in Popup window
                                        if($colname == "lastname" && $module == 'Contacts')
					{
                                               $firstname=$adb->query_result($list_result,$list_result_count,'firstname');
                                        	$temp_val =$temp_val.' '.$firstname;
					}

					$temp_val = str_replace("'",'\"',$temp_val);
			
					//Added to avoid the error when select SO from Invoice through AjaxEdit
					if($module == 'SalesOrder')
						$value = '<a href="a" LANGUAGE=javascript onclick=\'set_return_specific("'.$entity_id.'", "'.br2nl($temp_val).'","'.$_REQUEST['form'].'"); window.close()\'>'.$temp_val.'</a>';
					else
					$value = '<a href="a" LANGUAGE=javascript onclick=\'set_return_specific("'.$entity_id.'", "'.br2nl($temp_val).'"); window.close()\'>'.$temp_val.'</a>';
				}
				elseif($popuptype == "detailview")
                                {
                                        if($colname == "lastname" && $module == 'Contacts')
                                               $firstname=$adb->query_result($list_result,$list_result_count,'firstname');
                                        $temp_val =$temp_val.' '.$firstname;

					$focus->record_id = $_REQUEST['recordid'];
                                        $value = '<a href="a" LANGUAGE=javascript onclick=\'add_data_to_relatedlist("'.$entity_id.'","'.$focus->record_id.'"); window.close()\'>'.$temp_val.'</a>';
                                }
				elseif($popuptype == "formname_specific")
				{
					$value = '<a href="a" LANGUAGE=javascript onclick=\'set_return_formname_specific("'.$_REQUEST['form'].'", "'.$entity_id.'", "'.br2nl($temp_val).'"); window.close()\'>'.$temp_val.'</a>';
				}
				elseif($popuptype == "inventory_prod")
				{
					$row_id = $_REQUEST['curr_row'];

					$vattax = getProductTaxPercentage('VAT',$entity_id,'default');
					$salestax = getProductTaxPercentage('Sales',$entity_id,'default');
					$servicetax = getProductTaxPercentage('Service',$entity_id,'default');

					$unitprice=$adb->query_result($list_result,$list_result_count,'unit_price');
					$qty_stock=$adb->query_result($list_result,$list_result_count,'qtyinstock');
					$value = '<a href="a" LANGUAGE=javascript onclick=\'set_return_inventory("'.$entity_id.'", "'.br2nl($temp_val).'", "'.$unitprice.'", "'.$qty_stock.'", "'.$vattax.'","'.$salestax.'","'.$servicetax.'","'.$row_id.'"); window.close()\'>'.$temp_val.'</a>';
				}
				elseif($popuptype == "inventory_prod_po")
				{
					$row_id = $_REQUEST['curr_row'];

					$vattax = getProductTaxPercentage('VAT',$entity_id,'default');
					$salestax = getProductTaxPercentage('Sales',$entity_id,'default');
					$servicetax = getProductTaxPercentage('Service',$entity_id,'default');

					$unitprice=$adb->query_result($list_result,$list_result_count,'unit_price');
					$value = '<a href="a" LANGUAGE=javascript onclick=\'set_return_inventory_po("'.$entity_id.'", "'.br2nl($temp_val).'", "'.$unitprice.'", "'.$vattax.'","'.$salestax.'","'.$servicetax.'","'.$row_id.'"); window.close()\'>'.$temp_val.'</a>';
				}
				elseif($popuptype == "inventory_pb")
				{

					$prod_id = $_REQUEST['productid'];
					$flname =  $_REQUEST['fldname'];
					$listprice=getListPrice($prod_id,$entity_id);	
					
					$value = '<a href="a" LANGUAGE=javascript onclick=\'set_return_inventory_pb("'.$listprice.'", "'.$flname.'"); window.close()\'>'.$temp_val.'</a>';
				}
				elseif($popuptype == "specific_account_address")
				{
					require_once('modules/Accounts/Account.php');
					$acct_focus = new Account();
					$acct_focus->retrieve_entity_info($entity_id,"Accounts");
					
					$value = '<a href="a" LANGUAGE=javascript onclick=\'set_return_address("'.$entity_id.'", "'.br2nl($temp_val).'", "'.br2nl($acct_focus->column_fields['bill_street']).'", "'.br2nl($acct_focus->column_fields['ship_street']).'", "'.br2nl($acct_focus->column_fields['bill_city']).'", "'.br2nl($acct_focus->column_fields['ship_city']).'", "'.br2nl($acct_focus->column_fields['bill_state']).'", "'.br2nl($acct_focus->column_fields['ship_state']).'", "'.br2nl($acct_focus->column_fields['bill_code']).'", "'.br2nl($acct_focus->column_fields['ship_code']).'", "'.br2nl($acct_focus->column_fields['bill_country']).'", "'.br2nl($acct_focus->column_fields['ship_country']).'","'.br2nl($acct_focus->column_fields['bill_pobox']).'", "'.br2nl($acct_focus->column_fields['ship_pobox']).'"); window.close()\'>'.$temp_val.'</a>';

				}
				elseif($popuptype == "specific_contact_account_address")
                                {
                                        require_once('modules/Accounts/Account.php');
                                        $acct_focus = new Account();
                                        $acct_focus->retrieve_entity_info($entity_id,"Accounts");

                                        $value = '<a href="a" LANGUAGE=javascript onclick=\'set_return_contact_address("'.$entity_id.'", "'.br2nl($temp_val).'", "'.br2nl($acct_focus->column_fields['bill_street']).'", "'.br2nl($acct_focus->column_fields['ship_street']).'", "'.br2nl($acct_focus->column_fields['bill_city']).'", "'.br2nl($acct_focus->column_fields['ship_city']).'", "'.br2nl($acct_focus->column_fields['bill_state']).'", "'.br2nl($acct_focus->column_fields['ship_state']).'", "'.br2nl($acct_focus->column_fields['bill_code']).'", "'.br2nl($acct_focus->column_fields['ship_code']).'", "'.br2nl($acct_focus->column_fields['bill_country']).'", "'.br2nl($acct_focus->column_fields['ship_country']).'","'.br2nl($acct_focus->column_fields['bill_pobox']).'", "'.br2nl($acct_focus->column_fields['ship_pobox']).'"); window.close()\'>'.$temp_val.'</a>';

                                }

				elseif($popuptype == "specific_potential_account_address")
                                {
                                        $acntid = $adb->query_result($list_result,$list_result_count,"accountid");
                                        require_once('modules/Accounts/Account.php');
                                        $acct_focus = new Account();
                                        $acct_focus->retrieve_entity_info($acntid,"Accounts");
                                        $account_name = getAccountName($acntid);

                                        $value = '<a href="a" LANGUAGE=javascript onclick=\'set_return_address("'.$entity_id.'", "'.br2nl($temp_val).'", "'.$acntid.'", "'.br2nl($account_name).'", "'.br2nl($acct_focus->column_fields['bill_street']).'", "'.br2nl($acct_focus->column_fields['ship_street']).'", "'.br2nl($acct_focus->column_fields['bill_city']).'", "'.br2nl($acct_focus->column_fields['ship_city']).'", "'.br2nl($acct_focus->column_fields['bill_state']).'", "'.br2nl($acct_focus->column_fields['ship_state']).'", "'.br2nl($acct_focus->column_fields['bill_code']).'", "'.br2nl($acct_focus->column_fields['ship_code']).'", "'.br2nl($acct_focus->column_fields['bill_country']).'", "'.br2nl($acct_focus->column_fields['ship_country']).'","'.br2nl($acct_focus->column_fields['bill_pobox']).'", "'.br2nl($acct_focus->column_fields['ship_pobox']).'"); window.close()\'>'.$temp_val.'</a>';

                                }
//added by rdhital/Raju for better emails 
				elseif($popuptype == "set_return_emails")
				{	
					if ($module=='Accounts')
					{
						$name = $adb->query_result($list_result,$list_result_count,'accountname');
						$accid =$adb->query_result($list_result,$list_result_count,'accountid');
						//$value = '<a href="javascript: submitform('.$accid.');">'.$temp_val.'</a>';
						$emailaddress=$adb->query_result($list_result,$list_result_count,"email1");
						if($emailaddress == '')
							$emailaddress=$adb->query_result($list_result,$list_result_count,"email2");

						$querystr="SELECT fieldid,fieldlabel,columnname FROM field WHERE tabid=".getTabid($module)." and uitype=13;";
						$queryres = $adb->query($querystr);
						//Change this index 0 - to get the fieldid based on email1 or email2
						$fieldid = $adb->query_result($queryres,0,'fieldid');

						$value = '<a href="a" LANGUAGE=javascript onclick=\'return set_return_emails('.$entity_id.','.$fieldid.',"'.$name.'","'.$emailaddress.'"); \'>'.$name.'</a>';

					}elseif ($module=='Contacts' || $module=='Leads')
					{
						$firstname=$adb->query_result($list_result,$list_result_count,"firstname");
						$lastname=$adb->query_result($list_result,$list_result_count,"lastname");
						$name=$lastname.' '.$firstname;
						$emailaddress=$adb->query_result($list_result,$list_result_count,"email");
						if($emailaddress == '')
							$emailaddress=$adb->query_result($list_result,$list_result_count,"yahooid");

						$querystr="SELECT fieldid,fieldlabel,columnname FROM field WHERE tabid=".getTabid($module)." and uitype=13;";
						$queryres = $adb->query($querystr);
						//Change this index 0 - to get the fieldid based on email or yahooid
						$fieldid = $adb->query_result($queryres,0,'fieldid');

						//$value = '<a href="javascript: submitform('.$entity_id.');">'.$name.'</a>';
						$value = '<a href="a" LANGUAGE=javascript onclick=\'return set_return_emails('.$entity_id.','.$fieldid.',"'.$name.'","'.$emailaddress.'"); \'>'.$name.'</a>';


					}
				}	
				elseif($popuptype == "specific_vendor_address")
				{
					require_once('modules/Vendors/Vendor.php');
					$acct_focus = new Vendor();
					$acct_focus->retrieve_entity_info($entity_id,"Vendors");
					
					$value = '<a href="a" LANGUAGE=javascript onclick=\'set_return_address("'.$entity_id.'", "'.br2nl($temp_val).'", "'.br2nl($acct_focus->column_fields['treet']).'", "'.br2nl($acct_focus->column_fields['city']).'", "'.br2nl($acct_focus->column_fields['state']).'", "'.br2nl($acct_focus->column_fields['postalcode']).'", "'.br2nl($acct_focus->column_fields['country']).'","'.br2nl($acct_focus->column_fields['pobox']).'"); window.close()\'>'.$temp_val.'</a>';

				}
				else
				{
					if($colname == "lastname")
                                                $firstname=$adb->query_result($list_result,$list_result_count,'firstname');
                                        $temp_val =$temp_val.' '.$firstname;

					$temp_val = str_replace("'",'\"',$temp_val);
	
$log->debug("Exiting getValue method ...");
					$value = '<a href="a" LANGUAGE=javascript onclick=\'set_return("'.$entity_id.'", "'.br2nl($temp_val).'"); window.close()\'>'.$temp_val.'</a>';
				}
			}
			else
			{
				if(($module == "Leads" && $colname == "lastname") || ($module == "Contacts" && $colname == "lastname"))
				{
					if($module == "Contacts")
                                        {
                                                 $query="SELECT contactdetails.imagename FROM contactdetails WHERE lastname='".$temp_val."'";
                                                //echo $query;
                                                 $result = $adb->query($query);
                                                 $imagename=$adb->query_result($result,0,'imagename');
                                                 if($imagename != '')
                                                 {
                                                         $imgpath = "test/contact/".$imagename;
                                                         $contact_image='<img align="absmiddle" src="'.$imgpath.'" width="20" height="20" border="0" onMouseover=modifyimage("dynloadarea","'.$imgpath.'"); onMouseOut=fnhide("dynloadarea");>';
                                                 }
						$value = '<table width=100% border=0 cellpadding=0 cellspacing=0><tr><td align="left"><a href="index.php?action=DetailView&module='.$module.'&record='.$entity_id.'&parenttab='.$tabname.'">'.$temp_val.'</a></td><td align="right">'.$contact_image.'</td></tr></table>';
					}else
					{
					//Commented to give link even to the first name - Jaguar
					$value = $contact_image.'<a href="index.php?action=DetailView&module='.$module.'&record='.$entity_id.'&parenttab='.$tabname.'">'.$temp_val.'</a>';
					}
				}
				elseif($module == "Activities")
                                {
                                        $actvity_type = $adb->query_result($list_result,$list_result_count,'activitytype');
                                        if($actvity_type == "Task")
                                        {
                                               $value = '<a href="index.php?action=DetailView&module='.$module.'&record='.$entity_id.'&activity_mode=Task">'.$temp_val.'</a>';
                                        }
                                        else
                                        {
                                                $value = '<a href="index.php?action=DetailView&module='.$module.'&record='.$entity_id.'&activity_mode=Events">'.$temp_val.'</a>';
                                        }
                                }
				elseif($module == "Vendors")
				{
						
                                        $value = '<a href="index.php?action=DetailView&module=Vendors&record='.$entity_id.'&parenttab='.$tabname.'">'.$temp_val.'</a>';
				}
				elseif($module == "PriceBooks")
				{
						
                                        $value = '<a href="index.php?action=DetailView&module=PriceBooks&record='.$entity_id.'&parenttab='.$tabname.'">'.$temp_val.'</a>';
				}
				elseif($module == "SalesOrder")
				{
						
                                        $value = '<a href="index.php?action=DetailView&module=SalesOrder&record='.$entity_id.'&parenttab='.$tabname.'">'.$temp_val.'</a>';
				}
				elseif($module == 'Emails')
				{
					$value = $temp_val;
				}
                else
                {
                   	$value = '<a href="index.php?action=DetailView&module='.$module.'&record='.$entity_id.'&parenttab='.$tabname.'">'.$temp_val.'</a>';
                }
			}
		}
		else
		{
			$value = $temp_val;
		}
	}
	
	// Mike Crowe Mod --------------------------------------------------------Make right justified and currency value
	if ( in_array($uitype,array(71,72,7,9,90)) )
	{
		$value = '<span align="right">'.$value.'</div>';
	}

	$log->debug("Exiting getValue method ...");
	return $value; 
}


function getListQuery($module,$where='')
{
	global $log;
	$log->debug("Entering getListQuery(".$module.",".$where.") method ...");

	global $current_user;
	require('user_privileges/user_privileges_'.$current_user->id.'.php');
	require('user_privileges/sharing_privileges_'.$current_user->id.'.php');
	$tab_id = getTabid($module);	
	if($module == "HelpDesk")
	{
		$query = "SELECT crmentity.crmid, crmentity.smownerid,
				troubletickets.title, troubletickets.status,
				troubletickets.priority, troubletickets.parent_id,
				contactdetails.contactid, contactdetails.firstname,
				contactdetails.lastname, account.accountid,
				account.accountname, ticketcf.*
			FROM troubletickets
			INNER JOIN ticketcf
				ON ticketcf.ticketid = troubletickets.ticketid
			INNER JOIN crmentity
				ON crmentity.crmid = troubletickets.ticketid
			LEFT JOIN ticketgrouprelation
				ON troubletickets.ticketid = ticketgrouprelation.ticketid
			LEFT JOIN groups
				ON groups.groupname = ticketgrouprelation.groupname
			LEFT JOIN contactdetails
				ON troubletickets.parent_id = contactdetails.contactid
			LEFT JOIN account
				ON account.accountid = troubletickets.parent_id
			LEFT JOIN users
				ON crmentity.smownerid = users.id
				AND troubletickets.ticketid = ticketcf.ticketid
			WHERE crmentity.deleted = 0 ";
		if($is_admin==false && $profileGlobalPermission[1] == 1 && $profileGlobalPermission[2] == 1 && $defaultOrgSharingPermission[$tab_id] == 3)
		{
				$sec_parameter=getListViewSecurityParameter($module);
				$query .= $sec_parameter;

		}

	}
	if($module == "Accounts")
	{
		//Query modified to sort by assigned to
		$query = "SELECT crmentity.crmid, crmentity.smownerid,
				account.accountname, account.email1,
				account.email2, account.website, account.phone,
				accountbillads.city,
				accountscf.*
			FROM account
			INNER JOIN crmentity
				ON crmentity.crmid = account.accountid
			INNER JOIN accountbillads
				ON account.accountid = accountbillads.accountaddressid
			INNER JOIN accountshipads
				ON account.accountid = accountshipads.accountaddressid
			INNER JOIN accountscf
				ON account.accountid = accountscf.accountid
			LEFT JOIN accountgrouprelation
				ON accountscf.accountid = accountgrouprelation.accountid
			LEFT JOIN groups
				ON groups.groupname = accountgrouprelation.groupname
			LEFT JOIN users
				ON users.id = crmentity.smownerid
			WHERE crmentity.deleted = 0 ";

	if($is_admin==false && $profileGlobalPermission[1] == 1 && $profileGlobalPermission[2] == 1 && $defaultOrgSharingPermission[$tab_id] == 3)
                {
                    $query .= "AND (crmentity.smownerid IN (".$current_user->id.")
		   		 OR crmentity.smownerid IN (
					 SELECT user2role.userid
					 FROM user2role
					 INNER JOIN users
						 ON users.id = user2role.userid
					 INNER JOIN role
						 ON role.roleid = user2role.roleid
					 WHERE role.parentrole LIKE '".$current_user_parent_role_seq."::%')
					 OR crmentity.smownerid IN (
						 SELECT shareduserid
						 FROM tmp_read_user_sharing_per
						 WHERE userid=".$current_user->id."
						 AND tabid=".$tab_id.")
					 OR (crmentity.smownerid in (0)
					 AND (";

                        if(sizeof($current_user_groups) > 0)
                        {
                              $query .= "accountgrouprelation.groupname IN (
				      		SELECT groupname
						FROM groups
						WHERE groupid IN ".getCurrentUserGroupList().")
					OR ";
                        }
                         $query .= "accountgrouprelation.groupname IN (
				 	SELECT groups.groupname
					FROM tmp_read_group_sharing_per
					INNER JOIN groups
						ON groups.groupid = tmp_read_group_sharing_per.sharedgroupid
					WHERE userid=".$current_user->id."
					AND tabid=".$tab_id.")))) ";
                }

	}
	if ($module == "Potentials")
	{
		//Query modified to sort by assigned to
		$query = "SELECT crmentity.crmid, crmentity.smownerid,
				account.accountname,
				potential.accountid, potential.potentialname,
				potential.sales_stage, potential.amount,
				potential.currency, potential.closingdate,
				potential.typeofrevenue,
				potentialscf.*
			FROM potential
			INNER JOIN crmentity
				ON crmentity.crmid = potential.potentialid
			INNER JOIN account
				ON potential.accountid = account.accountid
			INNER JOIN potentialscf
				ON potentialscf.potentialid = potential.potentialid
			LEFT JOIN potentialgrouprelation
				ON potential.potentialid = potentialgrouprelation.potentialid
			LEFT JOIN groups
				ON groups.groupname = potentialgrouprelation.groupname
			LEFT JOIN users
				ON users.id = crmentity.smownerid
			WHERE crmentity.deleted = 0 ".$where; 

		if($is_admin==false && $profileGlobalPermission[1] == 1 && $profileGlobalPermission[2] == 1 && $defaultOrgSharingPermission[$tab_id] == 3)
		{
			$sec_parameter=getListViewSecurityParameter($module);
			$query .= $sec_parameter;
		}


	}
	if($module == "Leads")
	{
		$query = "SELECT crmentity.crmid, crmentity.smownerid,
				leaddetails.firstname, leaddetails.lastname,
				leaddetails.company, leadaddress.phone,
				leadsubdetails.website, leaddetails.email,
				leadscf.*
			FROM leaddetails
			INNER JOIN crmentity
				ON crmentity.crmid = leaddetails.leadid
			INNER JOIN leadsubdetails
				ON leadsubdetails.leadsubscriptionid = leaddetails.leadid
			INNER JOIN leadaddress
				ON leadaddress.leadaddressid = leadsubdetails.leadsubscriptionid
			INNER JOIN leadscf
				ON leaddetails.leadid = leadscf.leadid
			LEFT JOIN leadgrouprelation
				ON leadscf.leadid = leadgrouprelation.leadid
			LEFT JOIN groups
				ON groups.groupname = leadgrouprelation.groupname
			LEFT JOIN users
				ON users.id = crmentity.smownerid
			WHERE crmentity.deleted = 0
			AND leaddetails.converted = 0 ";
               if($is_admin==false && $profileGlobalPermission[1] == 1 && $profileGlobalPermission[2] == 1 && $defaultOrgSharingPermission[$tab_id] == 3)
                {
			$sec_parameter=getListViewSecurityParameter($module);
			$query .= $sec_parameter;
                }				
	}
	if($module == "Products")
	{
		$query = "SELECT crmentity.crmid, products.*, productcf.*
			FROM products
			INNER JOIN crmentity
				ON crmentity.crmid = products.productid
			LEFT JOIN productcf
				ON products.productid = productcf.productid
			LEFT JOIN seproductsrel
				ON seproductsrel.productid = products.productid
			WHERE crmentity.deleted = 0
			AND ((seproductsrel.crmid IS NULL
					AND (products.contactid = 0
						OR products.contactid IS NULL))
				OR seproductsrel.crmid IN (".getReadEntityIds('Leads').")
				OR seproductsrel.crmid IN (".getReadEntityIds('Accounts').")
				OR seproductsrel.crmid IN (".getReadEntityIds('Potentials').")
				OR products.contactid IN (".getReadEntityIds('Contacts').")) ";
	}
        if($module == "Notes")
        {
		$query = "SELECT crmentity.crmid, crmentity.modifiedtime,
				notes.title, notes.contact_id, notes.filename,
				senotesrel.crmid AS relatedto,
				contactdetails.firstname, contactdetails.lastname,
				notes.*
			FROM notes
			INNER JOIN crmentity
				ON crmentity.crmid = notes.notesid
			LEFT JOIN senotesrel
				ON senotesrel.notesid = notes.notesid
			LEFT JOIN contactdetails
				ON contactdetails.contactid = notes.contact_id
			WHERE crmentity.deleted = 0
			AND ((senotesrel.crmid IS NULL
					AND (notes.contact_id = 0
						OR notes.contact_id IS NULL))
				OR senotesrel.crmid IN (".getReadEntityIds('Leads').")
				OR senotesrel.crmid IN (".getReadEntityIds('Accounts').")
				OR senotesrel.crmid IN (".getReadEntityIds('Potentials').")
				OR senotesrel.crmid IN (".getReadEntityIds('Products').")
				OR senotesrel.crmid IN (".getReadEntityIds('Invoice').")
				OR senotesrel.crmid IN (".getReadEntityIds('PurchaseOrder').")
				OR senotesrel.crmid IN (".getReadEntityIds('SalesOrder').")
				OR notes.contact_id IN (".getReadEntityIds('Contacts').")) ";
        }
	if($module == "Contacts")
        {
		//Query modified to sort by assigned to
		$query = "SELECT contactdetails.firstname, contactdetails.lastname,
				contactdetails.title, contactdetails.accountid,
				contactdetails.email, contactdetails.phone,
				crmentity.smownerid, crmentity.crmid
			FROM contactdetails
			INNER JOIN crmentity
				ON crmentity.crmid = contactdetails.contactid
			INNER JOIN contactaddress
				ON contactdetails.contactid = contactaddress.contactaddressid
			INNER JOIN contactsubdetails
				ON contactaddress.contactaddressid = contactsubdetails.contactsubscriptionid
			INNER JOIN contactscf
				ON contactdetails.contactid = contactscf.contactid
			LEFT JOIN account
				ON account.accountid = contactdetails.accountid
			LEFT JOIN contactgrouprelation
				ON contactscf.contactid = contactgrouprelation.contactid
			LEFT JOIN groups
				ON groups.groupname = contactgrouprelation.groupname
			LEFT JOIN users
				ON users.id = crmentity.smownerid
			WHERE crmentity.deleted = 0 ".$where;

		if($is_admin==false && $profileGlobalPermission[1] == 1 && $profileGlobalPermission[2] == 1 && $defaultOrgSharingPermission[$tab_id] == 3)
		{
			$sec_parameter=getListViewSecurityParameter($module);
			$query .= $sec_parameter;
		}
        }
	if($module == "Activities")
        {
		$query = "SELECT crmentity.crmid, crmentity.smownerid, crmentity.setype,
				activity.*,
				contactdetails.lastname, contactdetails.firstname,
				contactdetails.contactid,
				account.accountid, account.accountname,
				recurringevents.recurringtype
			FROM activity
			INNER JOIN crmentity
				ON crmentity.crmid = activity.activityid
			LEFT JOIN cntactivityrel
				ON cntactivityrel.activityid = activity.activityid
			LEFT JOIN contactdetails
				ON contactdetails.contactid = cntactivityrel.contactid
			LEFT JOIN seactivityrel
				ON seactivityrel.activityid = activity.activityid
			LEFT JOIN activitygrouprelation
				ON activitygrouprelation.activityid = crmentity.crmid
			LEFT JOIN groups
				ON groups.groupname = activitygrouprelation.groupname
			LEFT JOIN users
				ON users.id = crmentity.smownerid
			LEFT OUTER JOIN account
				ON account.accountid = contactdetails.accountid
			LEFT OUTER JOIN recurringevents
				ON recurringevents.activityid = activity.activityid
			WHERE crmentity.deleted = 0
			AND (activity.activitytype = 'Meeting'
				OR activity.activitytype = 'Call'
				OR activity.activitytype = 'Task') ".$where;
		if($is_admin==false && $profileGlobalPermission[1] == 1 && $profileGlobalPermission[2] == 1 && $defaultOrgSharingPermission[$tab_id] == 3)
		{
			$sec_parameter=getListViewSecurityParameter($module);
			$query .= $sec_parameter;		

		}
		//$query .=" group by activity.activityid ";
		//included by Jaguar
        }
	if($module == "Emails")
        {
		$query = "SELECT DISTINCT crmentity.crmid, crmentity.smownerid,
				activity.activityid, activity.subject,
				activity.date_start,
				contactdetails.lastname, contactdetails.firstname,
				contactdetails.contactid
			FROM activity
			INNER JOIN crmentity
				ON crmentity.crmid = activity.activityid
			LEFT JOIN users
				ON users.id = crmentity.smownerid
			LEFT JOIN seactivityrel
				ON seactivityrel.activityid = activity.activityid
			LEFT JOIN contactdetails
				ON contactdetails.contactid = seactivityrel.crmid
			LEFT JOIN cntactivityrel
				ON cntactivityrel.activityid = activity.activityid
				AND cntactivityrel.contactid = cntactivityrel.contactid
			LEFT JOIN activitygrouprelation
				ON activitygrouprelation.activityid = crmentity.crmid
			LEFT JOIN groups
				ON groups.groupname = activitygrouprelation.groupname
			LEFT JOIN salesmanactivityrel
				ON salesmanactivityrel.activityid = activity.activityid
			LEFT JOIN emaildetails
				ON emaildetails.emailid = activity.activityid
			WHERE activity.activitytype = 'Emails'
			AND crmentity.deleted = 0 ";
		if($is_admin==false && $profileGlobalPermission[1] == 1 && $profileGlobalPermission[2] == 1 && $defaultOrgSharingPermission[$tab_id] == 3)
		{
			$sec_parameter=getListViewSecurityParameter($module);
			$query .= $sec_parameter;	
		}
	}

	if($module == "Faq")
	{
		$query = "SELECT crmentity.crmid, crmentity.createdtime, crmentity.modifiedtime,
				faq.*
			FROM faq
			INNER JOIN crmentity
				ON crmentity.crmid = faq.id
			LEFT JOIN products
				ON faq.product_id = products.productid
			WHERE crmentity.deleted = 0";
	}
	
	if($module == "Vendors")
	{
		$query = "SELECT crmentity.crmid, vendor.*
			FROM vendor
			INNER JOIN crmentity
				ON crmentity.crmid = vendor.vendorid
			WHERE crmentity.deleted = 0";
	}
	if($module == "PriceBooks")
	{
		$query = "SELECT crmentity.crmid, pricebook.*
			FROM pricebook
			INNER JOIN crmentity
				ON crmentity.crmid = pricebook.pricebookid
			WHERE crmentity.deleted = 0";
	}
	if($module == "Quotes")
	{
		//Query modified to sort by assigned to
		$query = "SELECT crmentity.*,
				quotes.*,
				quotesbillads.*,
				quotesshipads.*,
				potential.potentialname,
				account.accountname
			FROM quotes
			INNER JOIN crmentity
				ON crmentity.crmid = quotes.quoteid
			INNER JOIN quotesbillads
				ON quotes.quoteid = quotesbillads.quotebilladdressid
			INNER JOIN quotesshipads
				ON quotes.quoteid = quotesshipads.quoteshipaddressid
			LEFT JOIN quotescf
				ON quotes.quoteid = quotescf.quoteid
			LEFT OUTER JOIN account
				ON account.accountid = quotes.accountid
			LEFT OUTER JOIN potential
				ON potential.potentialid = quotes.potentialid
			LEFT JOIN quotegrouprelation
				ON quotes.quoteid = quotegrouprelation.quoteid
			LEFT JOIN groups
				ON groups.groupname = quotegrouprelation.groupname
			LEFT JOIN users
				ON users.id = crmentity.smownerid
			WHERE crmentity.deleted = 0 ".$where;
		if($is_admin==false && $profileGlobalPermission[1] == 1 && $profileGlobalPermission[2] == 1 && $defaultOrgSharingPermission[$tab_id] == 3)
		{
			$sec_parameter=getListViewSecurityParameter($module);
			$query .= $sec_parameter;	
		}
	}
	if($module == "PurchaseOrder")
        {
		//Query modified to sort by assigned to
                $query = "SELECT crmentity.*,
				purchaseorder.*,
				pobillads.*,
				poshipads.*,
				vendor.vendorname
			FROM purchaseorder
			INNER JOIN crmentity
				ON crmentity.crmid = purchaseorder.purchaseorderid
			LEFT OUTER JOIN vendor
				ON purchaseorder.vendorid = vendor.vendorid
			INNER JOIN pobillads
				ON purchaseorder.purchaseorderid = pobillads.pobilladdressid
			INNER JOIN poshipads
				ON purchaseorder.purchaseorderid = poshipads.poshipaddressid
			LEFT JOIN purchaseordercf
				ON purchaseordercf.purchaseorderid = purchaseorder.purchaseorderid
			LEFT JOIN pogrouprelation
				ON purchaseorder.purchaseorderid = pogrouprelation.purchaseorderid
			LEFT JOIN groups
				ON groups.groupname = pogrouprelation.groupname
			LEFT JOIN users
				ON users.id = crmentity.smownerid
			WHERE crmentity.deleted = 0 ";
		if($is_admin==false && $profileGlobalPermission[1] == 1 && $profileGlobalPermission[2] == 1 && $defaultOrgSharingPermission[$tab_id] == 3)
		{
			$sec_parameter=getListViewSecurityParameter($module);
			$query .= $sec_parameter;	
		}
        }
        if($module == "SalesOrder")
        {
		//Query modified to sort by assigned to
                $query = "SELECT crmentity.*,
				salesorder.*,
				sobillads.*,
				soshipads.*,
				quotes.subject AS quotename,
				account.accountname
			FROM salesorder
			INNER JOIN crmentity
				ON crmentity.crmid = salesorder.salesorderid
			INNER JOIN sobillads
				ON salesorder.salesorderid = sobillads.sobilladdressid
			INNER JOIN soshipads
				ON salesorder.salesorderid = soshipads.soshipaddressid
			LEFT JOIN salesordercf
				ON salesordercf.salesorderid = salesorder.salesorderid
			LEFT OUTER JOIN quotes
				ON quotes.quoteid = salesorder.quoteid
			LEFT OUTER JOIN account
				ON account.accountid = salesorder.accountid
			LEFT JOIN sogrouprelation
				ON salesorder.salesorderid = sogrouprelation.salesorderid
			LEFT JOIN groups
				ON groups.groupname = sogrouprelation.groupname
			LEFT JOIN users
				ON users.id = crmentity.smownerid
			WHERE crmentity.deleted = 0 ".$where;
		if($is_admin==false && $profileGlobalPermission[1] == 1 && $profileGlobalPermission[2] == 1 && $defaultOrgSharingPermission[$tab_id] == 3)
		{
			$sec_parameter=getListViewSecurityParameter($module);
			$query .= $sec_parameter;	
		}
        }
	if($module == "Invoice")
	{
		//Query modified to sort by assigned to
		//query modified -Code contribute by Geoff(http://forums.vtiger.com/viewtopic.php?t=3376)
		$query = "SELECT crmentity.*,
				invoice.*,
				invoicebillads.*,
				invoiceshipads.*,
				salesorder.subject AS salessubject
			FROM invoice
			INNER JOIN crmentity
				ON crmentity.crmid = invoice.invoiceid
			INNER JOIN invoicebillads
				ON invoice.invoiceid = invoicebillads.invoicebilladdressid
			INNER JOIN invoiceshipads
				ON invoice.invoiceid = invoiceshipads.invoiceshipaddressid
			LEFT OUTER JOIN salesorder
				ON salesorder.salesorderid = invoice.salesorderid
			INNER JOIN invoicecf
				ON invoice.invoiceid = invoicecf.invoiceid
			LEFT JOIN invoicegrouprelation
				ON invoice.invoiceid = invoicegrouprelation.invoiceid
			LEFT JOIN groups
				ON groups.groupname = invoicegrouprelation.groupname
			LEFT JOIN users
				ON users.id = crmentity.smownerid
			WHERE crmentity.deleted = 0 ".$where;
		if($is_admin==false && $profileGlobalPermission[1] == 1 && $profileGlobalPermission[2] == 1 && $defaultOrgSharingPermission[$tab_id] == 3)
		{
			$sec_parameter=getListViewSecurityParameter($module);
			$query .= $sec_parameter;	
		}
	}
	if($module == "Campaigns")
	{
		//Query modified to sort by assigned to
		//query modified -Code contribute by Geoff(http://forums.vtiger.com/viewtopic.php?t=3376)
		$query = "SELECT crmentity.*,
				campaign.*
			FROM campaign
			INNER JOIN crmentity
				ON crmentity.crmid = campaign.campaignid
			LEFT JOIN campaigngrouprelation
				ON campaign.campaignid = campaigngrouprelation.campaignid
			LEFT JOIN groups
				ON groups.groupname = campaigngrouprelation.groupname
			LEFT JOIN users
				ON users.id = crmentity.smownerid
			WHERE crmentity.deleted = 0 ".$where;
		if($is_admin==false && $profileGlobalPermission[1] == 1 && $profileGlobalPermission[2] == 1 && $defaultOrgSharingPermission[$tab_id] == 3)
		{
			$sec_parameter=getListViewSecurityParameter($module);
			$query .= $sec_parameter;	
		}
	}
	

	$log->debug("Exiting getListQuery method ...");
	return $query;
}


function getReadEntityIds($module)
{
	global $log;
	$log->debug("Entering getReadEntityIds(".$module.") method ...");
	global $current_user;
	require('user_privileges/user_privileges_'.$current_user->id.'.php');
	require('user_privileges/sharing_privileges_'.$current_user->id.'.php');
	$tab_id = getTabid($module);

	if($module == "Leads")
	{
		$query = "SELECT crmentity.crmid
			FROM leaddetails
			INNER JOIN crmentity
				ON crmentity.crmid = leaddetails.leadid
			LEFT JOIN leadgrouprelation
				ON leaddetails.leadid = leadgrouprelation.leadid
			LEFT JOIN groups
                                ON groups.groupname = leadgrouprelation.groupname
			WHERE crmentity.deleted = 0
			AND leaddetails.converted = 0 ";
               if($is_admin==false && $profileGlobalPermission[1] == 1 && $profileGlobalPermission[2] == 1 && $defaultOrgSharingPermission[$tab_id] == 3)
                {
			$sec_parameter=getListViewSecurityParameter($module);
			$query .= $sec_parameter;
                }				

	}


	if($module == "Accounts")
	{
		//Query modified to sort by assigned to
		$query = "SELECT crmentity.crmid
			FROM account
			INNER JOIN crmentity
				ON crmentity.crmid = account.accountid
			LEFT JOIN accountgrouprelation
				ON account.accountid = accountgrouprelation.accountid
			LEFT JOIN groups
                                ON groups.groupname = accountgrouprelation.groupname
			WHERE crmentity.deleted = 0 ";

	if($is_admin==false && $profileGlobalPermission[1] == 1 && $profileGlobalPermission[2] == 1 && $defaultOrgSharingPermission[$tab_id] == 3)
                {
			$sec_parameter=getListViewSecurityParameter($module);
			$query .= $sec_parameter;
		}
                    	
		
	}

	if ($module == "Potentials")
	{
		//Query modified to sort by assigned to
		$query = "SELECT crmentity.crmid
			FROM potential
			INNER JOIN crmentity
				ON crmentity.crmid = potential.potentialid
			LEFT JOIN potentialgrouprelation
				ON potential.potentialid = potentialgrouprelation.potentialid
			LEFT JOIN groups
                                ON groups.groupname = potentialgrouprelation.groupname
			WHERE crmentity.deleted = 0 "; 

		if($is_admin==false && $profileGlobalPermission[1] == 1 && $profileGlobalPermission[2] == 1 && $defaultOrgSharingPermission[$tab_id] == 3)
		{
			$sec_parameter=getListViewSecurityParameter($module);
			$query .= $sec_parameter;
		}


	}

	if($module == "Contacts")
        {
		//Query modified to sort by assigned to

		
		$query = "SELECT crmentity.crmid
			FROM contactdetails
			INNER JOIN crmentity
				ON crmentity.crmid = contactdetails.contactid
			LEFT JOIN contactgrouprelation
				ON contactdetails.contactid = contactgrouprelation.contactid
			LEFT JOIN groups
                                ON groups.groupname = contactgrouprelation.groupname
			WHERE crmentity.deleted = 0 ";

		if($is_admin==false && $profileGlobalPermission[1] == 1 && $profileGlobalPermission[2] == 1 && $defaultOrgSharingPermission[$tab_id] == 3)
		{
			$sec_parameter=getListViewSecurityParameter($module);
			$query .= $sec_parameter;
		}
        }
	if($module == "Products")
	{
		$query = "SELECT DISTINCT crmentity.crmid
			FROM products
			INNER JOIN crmentity
				ON crmentity.crmid = products.productid
			LEFT JOIN seproductsrel
				ON seproductsrel.productid = products.productid
			WHERE crmentity.deleted = 0
			AND ((seproductsrel.crmid IS NULL
					OR products.contactid = 0
					OR products.contactid IS NULL)
				OR seproductsrel.crmid IN (".getReadEntityIds('Leads').")
				OR seproductsrel.crmid IN (".getReadEntityIds('Accounts').")
				OR seproductsrel.crmid IN (".getReadEntityIds('Potentials').")
				OR products.contactid IN (".getReadEntityIds('Contacts').")) ";
	}

	if($module == "PurchaseOrder")
        {
		//Query modified to sort by assigned to
                $query = "SELECT crmentity.crmid
			FROM purchaseorder
			INNER JOIN crmentity
				ON crmentity.crmid = purchaseorder.purchaseorderid
			LEFT JOIN pogrouprelation
				ON purchaseorder.purchaseorderid = pogrouprelation.purchaseorderid
			LEFT JOIN groups
                                ON groups.groupname = pogrouprelation.groupname
			WHERE crmentity.deleted = 0 ";
		if($is_admin==false && $profileGlobalPermission[1] == 1 && $profileGlobalPermission[2] == 1 && $defaultOrgSharingPermission[$tab_id] == 3)
		{
			$sec_parameter=getListViewSecurityParameter($module);
			$query .= $sec_parameter;	
		}
        }
        if($module == "SalesOrder")
        {
		//Query modified to sort by assigned to
                $query = "SELECT crmentity.crmid
			FROM salesorder
			INNER JOIN crmentity
				ON crmentity.crmid = salesorder.salesorderid
			LEFT JOIN sogrouprelation
				ON salesorder.salesorderid = sogrouprelation.salesorderid
			LEFT JOIN groups
                                ON groups.groupname = sogrouprelation.groupname
			WHERE crmentity.deleted = 0 ".$where;
		if($is_admin==false && $profileGlobalPermission[1] == 1 && $profileGlobalPermission[2] == 1 && $defaultOrgSharingPermission[$tab_id] == 3)
		{
			$sec_parameter=getListViewSecurityParameter($module);
			$query .= $sec_parameter;	
		}
        }
	if($module == "Invoice")
	{
		$query = "SELECT crmentity.crmid
			FROM invoice
			INNER JOIN crmentity
				ON crmentity.crmid = invoice.invoiceid
			LEFT JOIN invoicegrouprelation
				ON invoice.invoiceid = invoicegrouprelation.invoiceid
			LEFT JOIN groups
				ON groups.groupname = invoicegrouprelation.groupname
			WHERE crmentity.deleted = 0 ".$where;
		if($is_admin==false && $profileGlobalPermission[1] == 1 && $profileGlobalPermission[2] == 1 && $defaultOrgSharingPermission[$tab_id] == 3)
		{
			$sec_parameter=getListViewSecurityParameter($module);
			$query .= $sec_parameter;	
		}
	}

	$log->debug("Exiting getReadEntityIds method ...");
	return $query;

}


//parameter $viewid added for customview 27/5
function AlphabeticalSearch($module,$action,$fieldname,$query,$type,$popuptype='',$recordid='',$return_module='',$append_url='',$viewid='',$groupid='')
{
	global $log;
	$log->debug("Entering AlphabeticalSearch(".$module.",".$action.",".$fieldname.",".$query.",".$type.",".$popuptype.",".$recordid.",".$return_module.",".$append_url.",".$viewid.",".$groupid.") method ...");
	if($type=='advanced')
		$flag='&advanced=true';

	if($popuptype != '')
		$popuptypevalue = "&popuptype=".$popuptype;

        if($recordid != '')
                $returnvalue = '&recordid='.$recordid;
        if($return_module != '')
                $returnvalue .= '&return_module='.$return_module;

	for($var='A',$i =1;$i<=26;$i++,$var++)
	// Mike Crowe Mod --------------------------------------------------------added groupid to url
		$list .= '<td class="searchAlph" id="alpha_'.$i.'" align="center" onClick=\'alphabetic("'.$module.'","gname='.$groupid.'&query='.$query.'&search_field='.$fieldname.'&searchtype=BasicSearch&search_text='.$var.$flag.$popuptypevalue.$returnvalue.$append_url.'","alpha_'.$i.'")\'>'.$var.'</td>';

	$log->debug("Exiting AlphabeticalSearch method ...");
	return $list;
}


function getRelatedToEntity($module,$list_result,$rset)
{
	global $log;
	$log->debug("Entering getRelatedToEntity(".$module.",".$list_result.",".$rset.") method ...");

	global $adb;
	$seid = $adb->query_result($list_result,$rset,"relatedto");
	$action = "DetailView";

	if(isset($seid) && $seid != '')
	{
		$parent_module = $parent_module = getSalesEntityType($seid);
		if($parent_module == 'Accounts')
		{
		$numrows= $adb->num_rows($evt_result);
		
		$parent_module = $adb->query_result($evt_result,0,'setype');
        $parent_id = $adb->query_result($evt_result,0,'crmid');
		
		if ($numrows>1){
		$parent_module ='Multiple';
		$parent_name=$app_strings['LBL_MULTIPLE'];
        }
        //Raju -- Ends
			$parent_query = "SELECT accountname FROM account WHERE accountid=".$seid;
			$parent_result = $adb->query($parent_query);
			$parent_name = $adb->query_result($parent_result,0,"accountname");
		}
		if($parent_module == 'Leads')
		{
			$parent_query = "SELECT firstname,lastname FROM leaddetails WHERE leadid=".$seid;
			$parent_result = $adb->query($parent_query);
			$parent_name = $adb->query_result($parent_result,0,"lastname")." ".$adb->query_result($parent_result,0,"firstname");
		}
		if($parent_module == 'Potentials')
		{
			$parent_query = "SELECT potentialname FROM potential WHERE potentialid=".$seid;
			$parent_result = $adb->query($parent_query);
			$parent_name = $adb->query_result($parent_result,0,"potentialname");
		}
		if($parent_module == 'Products')
		{
			$parent_query = "SELECT productname FROM products WHERE productid=".$seid;
			$parent_result = $adb->query($parent_query);
			$parent_name = $adb->query_result($parent_result,0,"productname");
		}
		if($parent_module == 'PurchaseOrder')
		{
			$parent_query = "SELECT subject FROM purchaseorder WHERE purchaseorderid=".$seid;
			$parent_result = $adb->query($parent_query);
			$parent_name = $adb->query_result($parent_result,0,"subject");
		}
		if($parent_module == 'SalesOrder')
		{
			$parent_query = "SELECT subject FROM salesorder WHERE salesorderid=".$seid;
			$parent_result = $adb->query($parent_query);
			$parent_name = $adb->query_result($parent_result,0,"subject");
		}
		if($parent_module == 'Invoice')
		{
			$parent_query = "SELECT subject FROM invoice WHERE invoiceid=".$seid;
			$parent_result = $adb->query($parent_query);
			$parent_name = $adb->query_result($parent_result,0,"subject");
		}

		$parent_value = "<a href='index.php?module=".$parent_module."&action=".$action."&record=".$seid."'>".$parent_name."</a>"; 
	}
	else
	{
		$parent_value = '';
	}
	$log->debug("Exiting getRelatedToEntity method ...");
	return $parent_value;

}


//used in home page listTop files
function getRelatedTo($module,$list_result,$rset)
{
	global $log;
	$log->debug("Entering getRelatedTo(".$module.",".$list_result.",".$rset.") method ...");

        global $adb;
		global $app_strings;
	if($module == "Notes")
        {
                $notesid = $adb->query_result($list_result,$rset,"notesid");
                $action = "DetailView";
                $evt_query="SELECT senotesrel.crmid, crmentity.setype
			FROM senotesrel
			INNER JOIN crmentity
				ON  senotesrel.crmid = crmentity.crmid
			WHERE senotesrel.notesid ='".$notesid."'";
	}else if($module == "Products")
	{
		$productid = $adb->query_result($list_result,$rset,"productid");
                $action = "DetailView";
                $evt_query="SELECT seproductsrel.crmid, crmentity.setype
			FROM seproductsrel
			INNER JOIN crmentity
				ON seproductsrel.crmid = crmentity.crmid
			WHERE seproductsrel.productid ='".$productid."'";

	}else
	{
		$activity_id = $adb->query_result($list_result,$rset,"activityid");
		$action = "DetailView";
		$evt_query="SELECT seactivityrel.crmid, crmentity.setype
			FROM seactivityrel
			INNER JOIN crmentity
				ON  seactivityrel.crmid = crmentity.crmid
			WHERE seactivityrel.activityid='".$activity_id."'";

		if($module == 'HelpDesk')
		{
			$activity_id = $adb->query_result($list_result,$rset,"parent_id");
			if($activity_id != '')
				$evt_query = "SELECT * FROM crmentity WHERE crmid=".$activity_id;
		}
	}
	//added by raju to change the related to in emails inot multiple if email is for more than one contact
        $evt_result = $adb->query($evt_query);
		$numrows= $adb->num_rows($evt_result);
		
		$parent_module = $adb->query_result($evt_result,0,'setype');
        $parent_id = $adb->query_result($evt_result,0,'crmid');
		
		if ($numrows>1){
		$parent_module ='Multiple';
		$parent_name=$app_strings['LBL_MULTIPLE'];
        }
        //Raju -- Ends
	if($module == 'HelpDesk' && ($parent_module == 'Accounts' || $parent_module == 'Contacts'))
        {
                global $theme;
                $module_icon = '<img src="themes/'.$theme.'/images/'.$parent_module.'.gif" alt="" border=0 align=center title='.$parent_module.'> ';
        }
	
	$action = "DetailView";
        if($parent_module == 'Accounts')
        {
                $parent_query = "SELECT accountname FROM account WHERE accountid=".$parent_id;
                $parent_result = $adb->query($parent_query);
                $parent_name = $adb->query_result($parent_result,0,"accountname");
        }
        if($parent_module == 'Leads')
        {
                $parent_query = "SELECT firstname,lastname FROM leaddetails WHERE leadid=".$parent_id;
                $parent_result = $adb->query($parent_query);
                $parent_name = $adb->query_result($parent_result,0,"lastname")." ".$adb->query_result($parent_result,0,"firstname");
        }
        if($parent_module == 'Potentials')
        {
                $parent_query = "SELECT potentialname FROM potential WHERE potentialid=".$parent_id;
                $parent_result = $adb->query($parent_query);
                $parent_name = $adb->query_result($parent_result,0,"potentialname");
        }
        if($parent_module == 'Products')
        {
                $parent_query = "SELECT productname FROM products WHERE productid=".$parent_id;
                $parent_result = $adb->query($parent_query);
                $parent_name = $adb->query_result($parent_result,0,"productname");
        }
	if($parent_module == 'Quotes')
        {
                $parent_query = "SELECT subject FROM quotes WHERE quoteid=".$parent_id;
                $parent_result = $adb->query($parent_query);
                $parent_name = $adb->query_result($parent_result,0,"subject");
        }
	if($parent_module == 'PurchaseOrder')
        {
                $parent_query = "SELECT subject FROM purchaseorder WHERE purchaseorderid=".$parent_id;
                $parent_result = $adb->query($parent_query);
                $parent_name = $adb->query_result($parent_result,0,"subject");
        }
	if($parent_module == 'Invoice')
        {
                $parent_query = "SELECT subject FROM invoice WHERE invoiceid=".$parent_id;
                $parent_result = $adb->query($parent_query);
                $parent_name = $adb->query_result($parent_result,0,"subject");
        }
        if($parent_module == 'SalesOrder')
        {
                $parent_query = "SELECT subject FROM salesorder WHERE salesorderid=".$parent_id;
                $parent_result = $adb->query($parent_query);
                $parent_name = $adb->query_result($parent_result,0,"subject");
        }
	if($parent_module == 'Contacts' && ($module == 'Emails' || $module == 'HelpDesk'))
        {
                $parent_query = "SELECT firstname,lastname FROM contactdetails WHERE contactid=".$parent_id;
                $parent_result = $adb->query($parent_query);
                $parent_name = $adb->query_result($parent_result,0,"lastname")." ".$adb->query_result($parent_result,0,"firstname");
        }
	//added by rdhital for better emails - Raju
	if ($parent_module == 'Multiple')
	{
		$parent_value = $parent_name;
	}
	else
	{
		$parent_value = $module_icon."<a href='index.php?module=".$parent_module."&action=".$action."&record=".$parent_id."'>".$parent_name."</a>";
	}
	//code added by raju ends
	$log->debug("Exiting getRelatedTo method ...");
        return $parent_value;


}


function getTableHeaderNavigation($navigation_array, $url_qry,$module='',$action_val='index',$viewid='')
{
	global $log;
	$log->debug("Entering getTableHeaderNavigation(".$navigation_array.",". $url_qry.",".$module.",".$action_val.",".$viewid.") method ...");
	global $theme;
	$theme_path="themes/".$theme."/";
	$image_path=$theme_path."images/";
	$output = '<td align="right" style="padding="5px;">';

	/*    //commented due to usablity conflict -- Philip
	$output .= '<a href="index.php?module='.$module.'&action='.$action_val.$url_qry.'&start=1&viewname='.$viewid.'&allflag='.$navigation_array['allflag'].'" >'.$navigation_array['allflag'].'</a>&nbsp;';
	*/
	if(($navigation_array['prev']) != 0)
	{
		$output .= '<a href="javascript:;" onClick="getListViewEntries_js(\''.$module.'\',\'start=1\');" title="First"><img src="'.$image_path.'start.gif" border="0" align="absmiddle"></a>&nbsp;';
		$output .= '<a href="javascript:;" onClick="getListViewEntries_js(\''.$module.'\',\'start='.$navigation_array['prev'].'\');" title="Previous"><img src="'.$image_path.'previous.gif" border="0" align="absmiddle"></a>&nbsp;';
	}
	else
	{
		$output .= '<img src="'.$image_path.'start_disabled.gif" border="0" align="absmiddle">&nbsp;';
		$output .= '<img src="'.$image_path.'previous_disabled.gif" border="0" align="absmiddle">&nbsp;';
	}
	for ($i=$navigation_array['first'];$i<=$navigation_array['end'];$i++){
		if ($navigation_array['current']==$i){
			$output .='<b>'.$i.'</b>&nbsp;';
		}
		else{
			$output .= '<a href="javascript:;" onClick="getListViewEntries_js(\''.$module.'\',\'start='.$i.'\');" >'.$i.'</a>&nbsp;';
		}
	}
	if(($navigation_array['next']) !=0)
	{
		$output .= '<a href="javascript:;" onClick="getListViewEntries_js(\''.$module.'\',\'start='.$navigation_array['next'].'\');" title="Next"><img src="'.$image_path.'next.gif" border="0" align="absmiddle"></a>&nbsp;';
		$output .= '<a href="javascript:;" onClick="getListViewEntries_js(\''.$module.'\',\'start='.$navigation_array['verylast'].'\');" title="Last"><img src="'.$image_path.'end.gif" border="0" align="absmiddle"></a>&nbsp;';
	}
	else
	{
		$output .= '<img src="'.$image_path.'next_disabled.gif" border="0" align="absmiddle">&nbsp;';
		$output .= '<img src="'.$image_path.'end_disabled.gif" border="0" align="absmiddle">&nbsp;';
	}
	$output .= '</td>';
	$log->debug("Exiting getTableHeaderNavigation method ...");
	if($navigation_array['first']=='')
	return;
	else
	return $output;
} 

/**This function return the entity ids that need to be excluded in popup listview for a given record
Param $currentmodule - modulename of the entity to be selected
Param $returnmodule - modulename for which the entity is assingned
Param $recordid - the record id for which the entity is assigned
Return type string.
*/

function getRelCheckquery($currentmodule,$returnmodule,$recordid)
{
	global $log;
	$log->debug("Entering getRelCheckquery(".$currentmodule.",".$returnmodule.",".$recordid.") method ...");
	global $adb;
	$skip_id = Array();
	$where_relquery = "";

	if($currentmodule=="Contacts" && $returnmodule == "Potentials")
	{
		$query = "SELECT contactid FROM contpotentialrel WHERE potentialid = ".$recordid;
	}
	elseif($currentmodule=="Contacts" && $returnmodule == "Vendors")
	{
		$query = "SELECT contactid FROM vendorcontactrel WHERE vendorid = ".$recordid;
	}

	if($query !='')
	{
		$result = $adb->query($query);
		if($adb->num_rows($result)!=0)
		{
			for($k=0;$k < $adb->num_rows($result);$k++)
			{
				$skip_id[]=$adb->query_result($result,$k,"contactid");
			}
			$skipids = constructList($skip_id,'INTEGER');
			$where_relquery = "and contactdetails.contactid not in ".$skipids;
		}
	}
	$log->debug("Exiting getRelCheckquery method ...");
	return $where_relquery;
}

/**This function stores the variables sent in list view url string.
Param $lv_array - list view session array
Return type void.
*/

function setSessionVar($lv_array,$noofrows,$max_ent,$module='',$related='')
{
	$start = '';
	if($noofrows>=1)
	{
		$lv_array['start']=1;
		$start = 1;
	}
	else
	{
		$lv_array['start']=0;
		$start = 0;
	}

	if(isset($_REQUEST['start']) && $_REQUEST['start'] !='')
	{
		$lv_array['start']=$_REQUEST['start'];
		$start = $_REQUEST['start'];
	}
	if(isset($_REQUEST['viewname']) && $_REQUEST['viewname'] !='')
		$lv_array['viewname']=$_REQUEST['viewname'];

	if($related=='')
		$_SESSION['lvs'][$_REQUEST['module']]=$lv_array;
	else
		$_SESSION['rlvs'][$module][$related] = $lv_array;
		
	if ($start < ceil ($noofrows / $max_ent) && $start !='')
	{
		$start = ceil ($noofrows / $max_ent);
		if($related=='')
			$_SESSION['lvs'][$currentModule]['start'] = $start;
	}
}

//Temp function to be be deleted
function getRelatedTableHeaderNavigation($navigation_array, $url_qry,$module='',$action_val='CallRelatedList',$viewid='')
{
	global $log;
	$log->debug("Entering getTableHeaderNavigation(".$navigation_array.",". $url_qry.",".$module.",".$action_val.",".$viewid.") method ...");
	global $theme;
	$theme_path="themes/".$theme."/";
	$image_path=$theme_path."images/";
	$output = '<td align="right" style="padding="5px;">';
	if(($navigation_array['prev']) != 0)
	{
		$output .= '<a href="index.php?module='.$module.'&action='.$action_val.$url_qry.'&start=1&viewname='.$viewid.'" title="First"><img src="'.$image_path.'start.gif" border="0" align="absmiddle"></a>&nbsp;';
		$output .= '<a href="index.php?module='.$module.'&action='.$action_val.$url_qry.'&start='.$navigation_array['prev'].'&viewname='.$viewid.'"><img src="'.$image_path.'previous.gif" border="0" align="absmiddle"></a>&nbsp;';

	}
	else
	{
		$output .= '<img src="'.$image_path.'start_disabled.gif" border="0" align="absmiddle">&nbsp;';
		$output .= '<img src="'.$image_path.'previous_disabled.gif" border="0" align="absmiddle">&nbsp;';
	}
	for ($i=$navigation_array['first'];$i<=$navigation_array['end'];$i++){
		if ($navigation_array['current']==$i){
			$output .='<b>'.$i.'</b>&nbsp;';
		}
		else{
			$output .= '<a href="index.php?module='.$module.'&action='.$action_val.$url_qry.'&start='.$i.'&viewname='.$viewid.'" >'.$i.'</a>&nbsp;';
		}
	}
	if(($navigation_array['next']) !=0)
	{
			$output .= '<a href="index.php?module='.$module.'&action='.$action_val.$url_qry.'&start='.$navigation_array['next'].'&viewname='.$viewid.'"><img src="'.$image_path.'next.gif" border="0" align="absmiddle"></a>&nbsp;';
			$output .= '<a href="index.php?module='.$module.'&action='.$action_val.$url_qry.'&start='.$navigation_array['verylast'].'&viewname='.$viewid.'"><img src="'.$image_path.'end.gif" border="0" align="absmiddle"></a>&nbsp;';
	}
	else
	{
		$output .= '<img src="'.$image_path.'next_disabled.gif" border="0" align="absmiddle">&nbsp;';
		$output .= '<img src="'.$image_path.'end_disabled.gif" border="0" align="absmiddle">&nbsp;';
	}
	$output .= '</td>';
		$log->debug("Exiting getTableHeaderNavigation method ...");
		if($navigation_array['first']=='')
		return;
		else
		return $output;
}


?>
