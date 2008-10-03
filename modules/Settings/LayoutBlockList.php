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
require_once('Smarty_setup.php');
require_once('include/database/PearDatabase.php');
require_once('include/CustomFieldUtil.php');

global $mod_strings;
global $app_strings;
$smarty=new vtigerCRM_Smarty;
$smarty->assign("MOD",$mod_strings);
$smarty->assign("APP",$app_strings);
global $theme;
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
require_once($theme_path.'layout_utils.php');
$smarty->assign("IMAGE_PATH", $image_path);
$module_array=getCustomFieldSupportedModules();

$cfimagecombo = Array($image_path."text.gif",
$image_path."number.gif",
$image_path."percent.gif",
$image_path."currency.gif",
$image_path."date.gif",
$image_path."email.gif",
$image_path."phone.gif",
$image_path."picklist.gif",
$image_path."url.gif",
$image_path."checkbox.gif",
$image_path."text.gif",
$image_path."picklist.gif");

$cftextcombo = Array($mod_strings['Text'],
$mod_strings['Number'],
$mod_strings['Percent'],
$mod_strings['Currency'],
$mod_strings['Date'],
$mod_strings['Email'],
$mod_strings['Phone'],
$mod_strings['PickList'],
$mod_strings['LBL_URL'],
$mod_strings['LBL_CHECK_BOX'],
$mod_strings['LBL_TEXT_AREA'],
$mod_strings['LBL_MULTISELECT_COMBO']
);
$smarty->assign("MODULES",$module_array);
$smarty->assign("CFTEXTCOMBO",$cftextcombo);
$smarty->assign("CFIMAGECOMBO",$cfimagecombo);

if($_REQUEST['fld_module'] !='')
	$fld_module = $_REQUEST['fld_module'];
else
	$fld_module = 'Leads';
$smarty->assign("MODULE",$fld_module);
$smarty->assign("CFENTRIES",getCFListEntries($fld_module));
if(isset($_REQUEST["duplicate"]) && $_REQUEST["duplicate"] == "yes")
{
	$error='Label in the Name '.$_REQUEST["fldlabel"].' already exists. Please specify a different Label';
	$smarty->assign("DUPLICATE_ERROR", $error);
}

///for field order change/////

if(isset($_REQUEST['what_to_do']))
{

if($_REQUEST['what_to_do']=='block_down')
{
$sql="select * from vtiger_blocks where blockid='".$_REQUEST['blockid']."'";
$result = $adb->query($sql);
$row= $adb->fetch_array($result);
$current_sequence=$row[sequence];

$sql_next="select * from vtiger_blocks where sequence >".$current_sequence." and tabid='".$_REQUEST[tabid]."' limit 0,1";
$result_next = $adb->query($sql_next);
$row_next= $adb->fetch_array($result_next);
$next_sequence=$row_next[sequence];
$next_id=$row_next[blockid];


$sql_up_current="update vtiger_blocks  set sequence='".$next_sequence."' where blockid='".$_REQUEST['blockid']."'";
$result_up_current = $adb->query($sql_up_current);


$sql_up_next="update vtiger_blocks  set sequence='".$current_sequence."' where blockid='".$next_id."'";
$result_up_next = $adb->query($sql_up_next);

}

if($_REQUEST['what_to_do']=='block_up')
{
$sql="select * from vtiger_blocks where blockid='".$_REQUEST['blockid']."'";
$result = $adb->query($sql);
$row= $adb->fetch_array($result);
$current_sequence=$row[sequence];

$sql_previous="select * from vtiger_blocks where sequence < ".$current_sequence." and tabid='".$_REQUEST[tabid]."'  order by sequence desc limit 0,1";
$result_previous = $adb->query($sql_previous);
$row_previous= $adb->fetch_array($result_previous);
$previous_sequence=$row_previous[sequence];
$previous_id=$row_previous[blockid];


$sql_up_current="update vtiger_blocks  set sequence='".$previous_sequence."' where blockid='".$_REQUEST['blockid']."'";
$result_up_current = $adb->query($sql_up_current);


$sql_up_previous="update vtiger_blocks  set sequence='".$current_sequence."' where blockid='".$previous_id."'";
$result_up_previous = $adb->query($sql_up_previous);

}

/* ( || $_REQUEST['what_to_do']=='Right') code added to If block by SAKTI on 23 Nov, 2007 */
if($_REQUEST['what_to_do']=='down' || $_REQUEST['what_to_do']=='Right')
{
$sql="select * from vtiger_field where fieldid='".$_REQUEST['fieldid']."'";
$result = $adb->query($sql);
$row= $adb->fetch_array($result);
$current_sequence=$row[sequence];

/* Start of code added/modified by SAKTI on 23 Nov, 2007 */
if($_REQUEST['what_to_do']=='down')
{
	$sql_next="select * from vtiger_field where sequence >".$current_sequence." and block='".$_REQUEST[blockid]."' order by sequence limit 1,2";
}
else
{
	$sql_next="select * from vtiger_field where sequence >".$current_sequence." and block='".$_REQUEST[blockid]."' order by sequence limit 0,1";
}
/* End of code added/modified by SAKTI on 23 Nov, 2007 */

$result_next = $adb->query($sql_next);
$row_next= $adb->fetch_array($result_next);
$next_sequence=$row_next[sequence];
$next_id=$row_next[fieldid];


$sql_up_current="update vtiger_field  set sequence='".$next_sequence."' where fieldid='".$_REQUEST['fieldid']."'";
$result_up_current = $adb->query($sql_up_current);


$sql_up_next="update vtiger_field  set sequence='".$current_sequence."' where fieldid='".$next_id."'";
$result_up_next = $adb->query($sql_up_next);

}

/* ( || $_REQUEST['what_to_do']=='Left') code added to If block by SAKTI on 23 Nov, 2007 */
if($_REQUEST['what_to_do']=='up' || $_REQUEST['what_to_do']=='Left')
{
$sql="select * from vtiger_field where fieldid='".$_REQUEST['fieldid']."'";
$result = $adb->query($sql);
$row= $adb->fetch_array($result);
$current_sequence=$row[sequence];

/* Start of code added/modified by SAKTI on 23 Nov, 2007 */
if($_REQUEST['what_to_do']=='up')
{
	$sql_previous="select * from vtiger_field where sequence < ".$current_sequence." and block='".$_REQUEST[blockid]."' order by sequence desc limit 1,2";
}
else
{
	$sql_previous="select * from vtiger_field where sequence < ".$current_sequence." and block='".$_REQUEST[blockid]."' order by sequence desc limit 0,1";
}
/* End of code added/modified by SAKTI on 23 Nov, 2007 */

$result_previous = $adb->query($sql_previous);
$row_previous= $adb->fetch_array($result_previous);
$previous_sequence=$row_previous[sequence];
$previous_id=$row_previous[fieldid];

$sql_up_current="update vtiger_field  set sequence='".$previous_sequence."' where fieldid='".$_REQUEST['fieldid']."'";
$result_up_current = $adb->query($sql_up_current);


$sql_up_previous="update vtiger_field  set sequence='".$current_sequence."' where fieldid='".$previous_id."'";
$result_up_previous = $adb->query($sql_up_previous);

}

//tanmoy on 6/11/2007
if($_REQUEST['what_to_do']=='show')
{
$sql_up_display="update vtiger_blocks  set display_status='1' where blockid=".$_REQUEST['blockid'];
$result_up_display = $adb->query($sql_up_display);
}

if($_REQUEST['what_to_do']=='hide')
{
$sql_up_display="update vtiger_blocks  set display_status='0' where blockid=".$_REQUEST['blockid'];
$result_up_display = $adb->query($sql_up_display);
}
//exit;
//end of 6/11/2007
}
///end of field order change



if($_REQUEST['mode'] !='')
	$mode = $_REQUEST['mode'];
$smarty->assign("MODE", $mode);
if($_REQUEST['ajax'] != 'true')
	$smarty->display('Settings/LayoutBlockList.tpl');	
else
	$smarty->display('Settings/LayoutBlockEntries.tpl');


function InStrCount($String,$Find,$CaseSensitive = false) {
    $i=0;
    $x=0;
    while (strlen($String)>=$i) {
     unset($substring);
     if ($CaseSensitive) {
      $Find=strtolower($Find);
      $String=strtolower($String);
     }
     $substring=substr($String,$i,strlen($Find));
     if ($substring==$Find) $x++;
     $i++;
    }
    return $x;
   }


	/**
	* Function to get customfield entries
	* @param string $module - Module name
	* return array  $cflist - customfield entries
	*/
	
function getCFListEntries($module)
{
	$tabid = getTabid($module);
	global $adb, $smarty; // $smarty variable added as global variable by SAKTI on 23 Nov, 2007 
	global $theme;
	$theme_path="themes/".$theme."/";
	$image_path=$theme_path."images/";
	
	
	//$dbQuery = "select fieldid,columnname,fieldlabel,uitype,displaytype,vtiger_convertleadmapping.cfmid from vtiger_field left join vtiger_convertleadmapping on  vtiger_convertleadmapping.leadfid = vtiger_field.fieldid where tabid=".$tabid." and generatedtype=2 order by sequence";
	//print_r($_SESSION);
	
	include('modules/'.$module.'/language/'.$_SESSION['authenticated_user_language'].'.lang.php');
	
	$dbQuery = "select blocklabel,blockid,display_status from vtiger_blocks where tabid='".$tabid."' order by sequence";
	$result = $adb->query($dbQuery);
	$row = $adb->fetch_array($result);
	
	$cflist=Array();
	$i=0;
	if($row!='')
	{
		do
		{
			
			$cflist[$i]['customblockflag']=InStrCount($row["blocklabel"],'CUSTOM_LBL_ADD_',true);
			
			/* Start of Code Added by SAKTI on 23 Nov, 2007 */
			if($row["blocklabel"] == 'LBL_CUSTOM_INFORMATION' )
			{
				$smarty->assign("CUSTOMSECTIONID",$row["blockid"]);
			}
			if($row["blocklabel"] == 'LBL_RELATED_PRODUCTS' )
			{
				$smarty->assign("RELPRODUCTSECTIONID",$row["blockid"]);
			}
			if($row["blocklabel"] == 'LBL_COMMENTS' )
			{
				$smarty->assign("COMMENTSECTIONID",$row["blockid"]);
			}
			/* End of Code Added by SAKTI on 23 Nov, 2007 */
			
			if($mod_strings[$row["blocklabel"]])
				$cflist[$i]['blocklabel']=$mod_strings[$row["blocklabel"]];
			else
				$cflist[$i]['blocklabel']=getTranslatedString($row["blocklabel"]);
			$cflist[$i]['blockid']=$row["blockid"];
			$cflist[$i]['display_status']=$row["display_status"];
			$cflist[$i]['tabid']=$tabid;
			$cflist[$i]['blockselect']=$row["blockid"];
			
			if($module=!'Invoices' && $module!='Quotes' && $module1!='SalesOrder' && $module1!='Invoice')
			{
			  $sql_field="select * from  vtiger_field where block='".$row["blockid"]."' and vtiger_field.displaytype IN (1,2,4)  order by sequence"; 
			}else
			{
			  $sql_field="select * from  vtiger_field where block='".$row["blockid"]."' and (vtiger_field.fieldlabel!='Total' && vtiger_field.fieldlabel!='Sub Total' && vtiger_field.fieldlabel!='Tax') and vtiger_field.displaytype IN (1,2,4) order by sequence"; 
			}
			
			$result_field = $adb->query($sql_field);
	        $row_field= $adb->fetch_array($result_field);
			
			if($row_field!='')
			{
			 $cf_element=Array();
			 $count=0;
			 do
			  {
				$cf_element[$count]['fieldselect']=$row_field["fieldid"];
				$cf_element[$count]['blockid']=$row["blockid"];
				$cf_element[$count]['tabid']=$tabid;
				$cf_element[$count]['no']=$count;
				$customfieldflag=InStrCount($row_field["fieldname"],'cf_',true);
				if($customfieldflag!=0)
				$cf_element[$count]['label']=$row_field["fieldlabel"];
				else
				$cf_element[$count]['label']=getTranslatedString($row_field["fieldlabel"]);
				$fld_type_name = getCustomFieldTypeName($row_field["uitype"]);
				$cf_element[$count]['type']=$fld_type_name;
				$cf_element[$count]['uitype']=$row_field["uitype"];
				$cf_element[$count]['columnname']=$row_field["columnname"];
				//$cf_element['tool']='<img src="'.$image_path.'editfield.gif" border="0" style="cursor:pointer;" onClick="fnvshobj(this,\'createcf\');getCreateCustomFieldForm(\''.$module.'\',\''.$row["fieldid"].'\',\''.$tabid.'\',\''.$row["uitype"].'\')" alt="Edit" title="Edit"/>&nbsp;|&nbsp;<img style="cursor:pointer;" onClick="deleteCustomField('.$row["fieldid"].',\''.$module.'\', \''.$row["columnname"].'\', \''.$row["uitype"].'\')" src="'.$image_path.'delete.gif" border="0"  alt="Delete" title="Delete"/></a>';
	
				
				$count++;
				
			  }while($row_field = $adb->fetch_array($result_field));
			
			$cflist[$i]['no']=$count;
			}else
			{
			 $cflist[$i]['no']=0;
			}
			$cflist[$i]['field']= $cf_element;
			unset($cf_element);
			 //$cflist[$i]['field'] = $cf_element;
		$i++;
		}while($row = $adb->fetch_array($result));
	}

	//print_r($cflist);
	return $cflist;
}

/**
* Function to Lead customfield Mapping entries
* @param integer  $cfid   - Lead customfield id
* return array    $label  - customfield mapping
*/
function getListLeadMapping($cfid)
{
	global $adb;
	$sql="select * from vtiger_convertleadmapping where cfmid =".$cfid;
	$result = $adb->query($sql);
	$noofrows = $adb->num_rows($result);
	for($i =0;$i <$noofrows;$i++)
	{
		$leadid = $adb->query_result($result,$i,'leadfid');
		$accountid = $adb->query_result($result,$i,'accountfid');
		$contactid = $adb->query_result($result,$i,'contactfid');
		$potentialid = $adb->query_result($result,$i,'potentialfid');
		$cfmid = $adb->query_result($result,$i,'cfmid');

		$sql2="select fieldlabel from vtiger_field where fieldid ='".$accountid."'";
		$result2 = $adb->query($sql2);
		$accountfield = $adb->query_result($result2,0,'fieldlabel');
		$label['accountlabel'] = $accountfield;
		
		$sql3="select fieldlabel from vtiger_field where fieldid ='".$contactid."'";
		$result3 = $adb->query($sql3);
		$contactfield = $adb->query_result($result3,0,'fieldlabel');
		$label['contactlabel'] = $contactfield;
		$sql4="select fieldlabel from vtiger_field where fieldid ='".$potentialid."'";
		$result4 = $adb->query($sql4);
		$potentialfield = $adb->query_result($result4,0,'fieldlabel');
		$label['potentiallabel'] = $potentialfield;
	}
	return $label;
}

/* function to get the modules supports Custom Fields
*/

function getCustomFieldSupportedModules()
{
	global $adb;
	$sql="select distinct vtiger_field.tabid,name from vtiger_field inner join vtiger_tab on vtiger_field.tabid=vtiger_tab.tabid where vtiger_field.tabid not in(9,10,16,15,8,29)";
	$result = $adb->query($sql);
	while($moduleinfo=$adb->fetch_array($result))
	{
		$modulelist[$moduleinfo['name']] = $moduleinfo['name'];
	}
	return $modulelist;
}
?>
