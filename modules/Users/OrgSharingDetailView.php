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
require_once('XTemplate/xtpl.php');
require_once('include/utils/utils.php');
require_once('include/utils/UserInfoUtil.php');
global $mod_strings;
global $app_strings;
global $app_list_strings;

echo '<form action="index.php" method="post" name="new" id="form">';
echo get_module_title("Security", "Default Organisation Sharing Privileges", true);

global $theme;
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
require_once($theme_path.'layout_utils.php');

$xtpl=new XTemplate ('modules/Users/OrgSharingDetailView.html');

$defSharingPermissionData = getDefaultSharingAction();
$output .= '<input type="hidden" name="module" value="Users">';
$output .= '<input type="hidden" name="action" value="OrgSharingEditView">';
$output .= '<br><input title="Edit" accessKey="C" class="button" type="submit" name="Edit" value="'.$mod_strings['LBL_EDIT_PERMISSIONS'].'"><br><br>';
$output .= '<TABLE width="60%" border=0 cellPadding=5 cellSpacing=1 class="FormBorder">';
$output .= '<tr>';
$output .= '<td class="moduleListTitle" height="20" style="padding:0px 3px 0px 3px;"><b>'.$mod_strings['LBL_ORG_SHARING_PRIVILEGES'].'</b></td>';
$output .= '<td class="moduleListTitle" height="20" style="padding:0px 3px 0px 3px;"><b>Access Privilege</b></td>';
$output .=  '</tr>';

$row=1;
foreach($defSharingPermissionData as $tab_id => $def_perr)
{

	$entity_name = getTabname($tab_id);
	if($tab_id == 6)
        {
                $cont_name = getTabname(4);
                $entity_name .= ' & '.$cont_name;
        }

	$entity_perr = getDefOrgShareActionName($def_perr);
	//if($entity_name != "Notes" && $entity_name != "Products" && $entity_name != "Faq" && $entity_name != "Vendor" && $entity_name != "PriceBook" && $entity_name != 'Events' && $entity_name != 'SalesOrder')
	//{	

		if ($row%2==0)
			$output .=   '<tr class="evenListRow">';
		else
			$output .=   '<tr class="oddListRow">';

		$output .=   '<TD width="40%" height="21" noWrap style="padding:0px 3px 0px 3px;">'.$entity_name.'</TD>';
		$output .=  '<TD width="60%" height="21" noWrap style="padding:0px 3px 0px 3px;">'.$entity_perr.'</TD>';
		$output .=  '</tr>';

		$row++;
	//}
}


$output .=  '</TABLE></form><br>';

$xtpl->assign("DEFAULT_SHARING", $output);
//Lead Sharing
$mod_output = '';
$mod_output .= '<BR><BR>';
$mod_output .= get_module_title("Security", "Lead Sharing Privileges", true);
$mod_output .= '<BR>';
$mod_output .= '<TABLE width="60%" border=0 cellPadding=5 cellSpacing=1 class="FormBorder">';
$mod_output .= '<form action="index.php" method="post" name="Leads" id="form">';
$mod_output .= '<input type="hidden" name="module" value="Users">';
$mod_output .= '<input type="hidden" name="action" value="CreateSharingRule">';
$mod_output .= '<input type="hidden" name="sharing_module" value="Leads">';
$mod_output .= '<input type="hidden" name="mode" value="create">';
$mod_output .= '<tr></td><input title="New" accessKey="E" class="button" type="submit" name="Create" value="New"></td></tr>';
$mod_output .= '</form></table>';
$mod_output .= '<BR>';
$mod_output .= getSharingRuleList('Leads');

//Account Sharing
$mod_output .= '<BR><BR>';
$mod_output .= get_module_title("Security", "Account Sharing Privileges", true);
$mod_output .= '<BR>';
$mod_output .= '<TABLE width="60%" border=0 cellPadding=5 cellSpacing=1 class="FormBorder">';
$mod_output .= '<form action="index.php" method="post" name="Accounts" id="form">';
$mod_output .= '<input type="hidden" name="module" value="Users">';
$mod_output .= '<input type="hidden" name="action" value="CreateSharingRule">';
$mod_output .= '<input type="hidden" name="sharing_module" value="Accounts">';
$mod_output .= '<input type="hidden" name="mode" value="create">';
$mod_output .= '<tr></td><input title="New" accessKey="E" class="button" type="submit" name="Create" value="New"></td></tr>';
$mod_output .= '</form></table>';
$mod_output .= '<BR>';
$mod_output .= getSharingRuleList('Accounts');




//Potential Sharing
$mod_output .= '<BR><BR>';
$mod_output .= get_module_title("Security", "Potential Sharing Privileges", true);
$mod_output .= '<BR>';
$mod_output .= '<TABLE width="60%" border=0 cellPadding=5 cellSpacing=1 class="FormBorder">';
$mod_output .= '<form action="index.php" method="post" name="Potentials" id="form">';
$mod_output .= '<input type="hidden" name="module" value="Users">';
$mod_output .= '<input type="hidden" name="action" value="CreateSharingRule">';
$mod_output .= '<input type="hidden" name="sharing_module" value="Potentials">';
$mod_output .= '<input type="hidden" name="mode" value="create">';
$mod_output .= '<tr></td><input title="New" accessKey="E" class="button" type="submit" name="Create" value="New"></td></tr>';
$mod_output .= '</form></table>';
$mod_output .= '<BR>';
$mod_output .= getSharingRuleList('Potentials');

//HelpDesk Sharing
$mod_output .= '<BR><BR>';
$mod_output .= get_module_title("Security", "HelpDesk Sharing Privileges", true);
$mod_output .= '<BR>';
$mod_output .= '<TABLE width="60%" border=0 cellPadding=5 cellSpacing=1 class="FormBorder">';
$mod_output .= '<form action="index.php" method="post" name="HelpDesk" id="form">';
$mod_output .= '<input type="hidden" name="module" value="Users">';
$mod_output .= '<input type="hidden" name="action" value="CreateSharingRule">';
$mod_output .= '<input type="hidden" name="sharing_module" value="HelpDesk">';
$mod_output .= '<input type="hidden" name="mode" value="create">';
$mod_output .= '<tr></td><input title="New" accessKey="E" class="button" type="submit" name="Create" value="New"></td></tr>';
$mod_output .= '</form></table>';
$mod_output .= '<BR>';
$mod_output .= getSharingRuleList('HelpDesk');



//Email Sharing
$mod_output .= '<BR><BR>';
$mod_output .= get_module_title("Security", "Email Sharing Privileges", true);
$mod_output .= '<BR>';
$mod_output .= '<TABLE width="60%" border=0 cellPadding=5 cellSpacing=1 class="FormBorder">';
$mod_output .= '<form action="index.php" method="post" name="Emails" id="form">';
$mod_output .= '<input type="hidden" name="module" value="Users">';
$mod_output .= '<input type="hidden" name="action" value="CreateSharingRule">';
$mod_output .= '<input type="hidden" name="sharing_module" value="Emails">';
$mod_output .= '<input type="hidden" name="mode" value="create">';
$mod_output .= '<tr></td><input title="New" accessKey="E" class="button" type="submit" name="Create" value="New"></td></tr>';
$mod_output .= '</form></table>';
$mod_output .= '<BR>';
$mod_output .= getSharingRuleList('Emails');


//Quotes Sharing
$mod_output .= '<BR><BR>';
$mod_output .= get_module_title("Security", "Quote Sharing Privileges", true);
$mod_output .= '<BR>';
$mod_output .= '<TABLE width="60%" border=0 cellPadding=5 cellSpacing=1 class="FormBorder">';
$mod_output .= '<form action="index.php" method="post" name="Quotes" id="form">';
$mod_output .= '<input type="hidden" name="module" value="Users">';
$mod_output .= '<input type="hidden" name="action" value="CreateSharingRule">';
$mod_output .= '<input type="hidden" name="sharing_module" value="Quotes">';
$mod_output .= '<input type="hidden" name="mode" value="create">';
$mod_output .= '<tr></td><input title="New" accessKey="E" class="button" type="submit" name="Create" value="New"></td></tr>';
$mod_output .= '</form></table>';
$mod_output .= '<BR>';
$mod_output .= getSharingRuleList('Quotes');

//Purchase Order Sharing
$mod_output .= '<BR><BR>';
$mod_output .= get_module_title("Security", "Purchase Order Sharing Privileges", true);
$mod_output .= '<BR>';
$mod_output .= '<TABLE width="60%" border=0 cellPadding=5 cellSpacing=1 class="FormBorder">';
$mod_output .= '<form action="index.php" method="post" name="Orders" id="form">';
$mod_output .= '<input type="hidden" name="module" value="Users">';
$mod_output .= '<input type="hidden" name="action" value="CreateSharingRule">';
$mod_output .= '<input type="hidden" name="sharing_module" value="PurchaseOrder">';
$mod_output .= '<input type="hidden" name="mode" value="create">';
$mod_output .= '<tr></td><input title="New" accessKey="E" class="button" type="submit" name="Create" value="New"></td></tr>';
$mod_output .= '</form></table>';
$mod_output .= '<BR>';
$mod_output .= getSharingRuleList('PurchaseOrder');

//Sales Order Sharing
$mod_output .= '<BR><BR>';
$mod_output .= get_module_title("Security", "Sales Order Sharing Privileges", true);
$mod_output .= '<BR>';
$mod_output .= '<TABLE width="60%" border=0 cellPadding=5 cellSpacing=1 class="FormBorder">';
$mod_output .= '<form action="index.php" method="post" name="SalesOrder" id="form">';
$mod_output .= '<input type="hidden" name="module" value="Users">';
$mod_output .= '<input type="hidden" name="action" value="CreateSharingRule">';
$mod_output .= '<input type="hidden" name="sharing_module" value="SalesOrder">';
$mod_output .= '<input type="hidden" name="mode" value="create">';
$mod_output .= '<tr></td><input title="New" accessKey="E" class="button" type="submit" name="Create" value="New"></td></tr>';
$mod_output .= '</form></table>';
$mod_output .= '<BR>';
$mod_output .= getSharingRuleList('SalesOrder');

//Invoice Sharing
$mod_output .= '<BR><BR>';
$mod_output .= get_module_title("Security", "Invoice Sharing Privileges", true);
$mod_output .= '<BR>';
$mod_output .= '<TABLE width="60%" border=0 cellPadding=5 cellSpacing=1 class="FormBorder">';
$mod_output .= '<form action="index.php" method="post" name="Invoice" id="form">';
$mod_output .= '<input type="hidden" name="module" value="Users">';
$mod_output .= '<input type="hidden" name="action" value="CreateSharingRule">';
$mod_output .= '<input type="hidden" name="sharing_module" value="Invoice">';
$mod_output .= '<input type="hidden" name="mode" value="create">';
$mod_output .= '<tr></td><input title="New" accessKey="E" class="button" type="submit" name="Create" value="New"></td></tr>';
$mod_output .= '</form></table>';
$mod_output .= '<BR>';
$mod_output .= getSharingRuleList('Invoice');

$xtpl->assign("MODSHARING", $mod_output);
$xtpl->assign("MOD", $mod_strings);
$xtpl->parse("main");
$xtpl->out("main");

function getSharingRuleList($module)
{
	global $adb;
	$output .= '<TABLE width="60%" border=0 cellPadding=5 cellSpacing=1 class="FormBorder">';
	$output .= '<tr>';
	$output .= '<td class="moduleListTitle" height="20" style="padding:0px 3px 0px 3px;"><b>Operation</b></td>';
	$output .= '<td class="moduleListTitle" height="20" style="padding:0px 3px 0px 3px;"><b>Owned By</b></td>';
	$output .= '<td class="moduleListTitle" height="20" style="padding:0px 3px 0px 3px;"><b>Shared With</b></td>';
	$output .= '<td class="moduleListTitle" height="20" style="padding:0px 3px 0px 3px;"><b>'.$module.' Access</b></td>';
	$output .=  '</tr>';

	$tabid=getTabid($module);
	$dataShareTableArray=getDataShareTableandColumnArray();
	
	$i=1;
	foreach($dataShareTableArray as $table_name => $colName)
	{

		$colNameArr=explode("::",$colName);
		$query = "select ".$table_name.".* from ".$table_name." inner join datashare_module_rel on ".$table_name.".shareid=datashare_module_rel.shareid where datashare_module_rel.tabid=".$tabid;
		//echo $query.'<BR>';
		$result=$adb->query($query);
		$num_rows=$adb->num_rows($result);

		$share_colName=$colNameArr[0];
		$share_modType=getEntityTypeFromCol($share_colName);
		//echo '          '.$share_colName.'             '.$share_modType.'<BR>';

		$to_colName=$colNameArr[1];
		$to_modType=getEntityTypeFromCol($to_colName);
		//echo '          '.$to_colName.'             '.$to_modType.'<BR>';

		for($j=0;$j<$num_rows;$j++)
		{
			$shareid=$adb->query_result($result,$j,"shareid");
			$share_id=$adb->query_result($result,$j,$share_colName);
			$to_id=$adb->query_result($result,$j,$to_colName);
			$permission = $adb->query_result($result,$j,'permission');
		//	echo '<BR>';
		//	echo '          '.$shareid.'             '.$share_id.'              '.$to_id.'             '.$permission.'<BR>';
		//	echo '<BR>';

			if ($i%2==0)
				$output .=   '<tr class="evenListRow">';
			else
				$output .=   '<tr class="oddListRow">';

			$edit_del =" <a href='index.php?module=Users&action=CreateSharingRule&returnaction=OrgSharingDetailView&shareid=".$shareid."&mode=edit'> edit </a> | <a href='index.php?module=Users&action=DeleteSharingRule&shareid=".$shareid."'> del </a>";

			$share_ent_disp = getEntityDisplayLink($share_modType,$share_id);
			$to_ent_disp = getEntityDisplayLink($to_modType,$to_id);

			if($permission == 0)
			{
				$perr_out = 'Read Only';
			}
			elseif($permission == 1)
			{
				$perr_out = 'Read Write';
			}

			$output .=   '<TD width="25%" height="21" noWrap style="padding:0px 3px 0px 3px;">'.$edit_del.'</TD>';
			$output .=  '<TD width="25%" height="21" noWrap style="padding:0px 3px 0px 3px;">'.$share_ent_disp.'</TD>';
			$output .=  '<TD width="25%" height="21" noWrap style="padding:0px 3px 0px 3px;">'.$to_ent_disp.'</TD>';
			$output .=  '<TD width="25%" height="21" noWrap style="padding:0px 3px 0px 3px;">'.$perr_out.'</TD>';
			$output .=  '</tr>';

			$i++;
		}

	}

	$output .=  '</TABLE><br>';
	return $output;
}


?>
