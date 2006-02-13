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

 * $Header: /cvsroot/vtigercrm/vtiger_crm/modules/Orders/index.php,v 1.5 2005/07/01 18:23:50 saraj Exp $

 * Description:  TODO To be written.

 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.

 * All Rights Reserved.

 * Contributor(s): ______________________________________..

 ********************************************************************************/



global $theme;

$theme_path="themes/".$theme."/";

$image_path=$theme_path."images/";

require_once ($theme_path."layout_utils.php");

global $mod_strings;



echo get_module_title($mod_strings['LBL_MODULE_NAME'], $mod_strings['LBL_MODULE_TITLE'], true); 

$submenu = array('LBL_PO_FORM_TITLE'=>'ListView.php','LBL_SO_FORM_TITLE'=>'SalesOrderListView.php');
$sec_arr = array('ListView.php'=>'Orders','SalesOrderListView.php'=>'SalesOrder'); 
echo "\n<BR>\n";
?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
 <tr>
   <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
   <tr>
     <td class="tabStart">&nbsp;&nbsp;</td>
<?
	if(isset($_REQUEST['smodule']) && $_REQUEST['smodule'] != '')
	{
		$classname = "tabOff";
	}
	else
	{
		$classname = "tabOn";
	}
	$listView = "ListView.php";
	$profile_id = $_SESSION['authenticated_user_profileid'];
        $tab_per_Data = getAllTabsPermission($profile_id);
        $permissionData = $_SESSION['action_permission_set'];
	foreach($submenu as $label=>$filename)
        {
                $cur_mod = $sec_arr[$filename];
                $cur_tabid = getTabid($cur_mod);

                if($tab_per_Data[$cur_tabid] == 0)
                {

                        if($permissionData[$cur_tabid][3] ==0)
                        {
                                list($lbl,$sname,$frm,$title)=split("_",$label);
                                if(stristr($label,$_REQUEST['smodule']))
                                {
                                        echo '<td class="tabOn" nowrap><a href="index.php?module=Orders&action=index&smodule='.$_REQUEST['smodule'].'" class="tabLink">'.$mod_strings[$label].'</a></td>';
                                        $listView = $filename;
                                        $classname = "tabOff";
                                }
                                else
                                {
                                        echo '<td class="'.$classname.'" nowrap><a href="index.php?module=Orders&action=index&smodule='.$sname.'" class="tabLink">'.$mod_strings[$label].'</a></td>';
                                }
                                $classname = "tabOff";
                        }

                }

        }
	
?>
     <td width="100%" class="tabEnd">&nbsp;</td>
   </tr>
 </table></td>
 </tr>
 <tr>
   <td class="tabContent" style="padding:10"><div id="tabcontent1"><? include ('modules/Orders/'.$listView); ?> </div>
   </td>
 </tr>
</table>
