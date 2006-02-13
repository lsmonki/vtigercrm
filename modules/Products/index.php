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

 * $Header: /cvsroot/vtigercrm/vtiger_crm/modules/Products/index.php,v 1.9.2.2 2005/09/12 10:05:33 saraj Exp $

 * Description:  TODO To be written.

 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.

 * All Rights Reserved.

 * Contributor(s): ______________________________________..

 ********************************************************************************/



global $theme;

$theme_path="themes/".$theme."/";

$image_path=$theme_path."images/";

require_once ($theme_path."layout_utils.php");
require_once('include/ComboUtil.php');

global $mod_strings;


echo get_module_title("Products", $mod_strings['LBL_MODULE_NAME'].": Home" , true);
$submenu = array('LBL_PRODUCTS_TITLE'=>'ListView.php','LBL_VENDOR_TITLE'=>'VendorListView.php','LBL_PRICEBOOK_TITLE'=>'PriceBookListView.php');
$sec_arr = array('ListView.php'=>'Products','VendorListView.php'=>'Vendor','PriceBookListView.php'=>'PriceBook'); 
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
				list($lbl,$sname,$title)=split("_",$label);
				if(stristr($label,$_REQUEST['smodule']))
				{
					echo '<td class="tabOn" nowrap><a href="index.php?module=Products&action=index&smodule='.$_REQUEST['smodule'].'" class="tabLink">'.$mod_strings[$label].'</a></td>';	
					$listView = $filename;
					$classname = "tabOff";
				}
				else
				{
					echo '<td class="'.$classname.'" nowrap><a href="index.php?module=Products&action=index&smodule='.$sname.'" class="tabLink">'.$mod_strings[$label].'</a></td>';	
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
   <td class="tabContent" style="padding:10"><div id="tabcontent1"><? include ('modules/Products/'.$listView); ?> </div>
   </td>
 </tr>
</table>

<?
if($_REQUEST['smodule']=='' || $_REQUEST['smodule']=="PRODUCTS")
{
        echo "<br><table width='250' cellpadding=0 cellspacing=0><tr><td>";
        echo get_form_header($mod_strings['LBL_TOOL_FORM_TITLE'], "", false);
        echo "</td></tr>";
        echo "<tr><td class='formOuterBorder' style='padding: 10px'>";
        echo "<ul>";
        include('modules/Import/ImportButton.php');
        echo "</ul>";
        echo "</td></tr></table>";
}
?>


<!--script>
function toggleTab(id) {
   for (i=1;i<=3;i++) {
      if (i==id) {
         getObj("tab"+i).className="tabOn"
         getObj("tabcontent"+i).style.display="block"
	 set_cookie("prod_tab"+i,"block");
      } else {
         getObj("tab"+i).className="tabOff"
         getObj("tabcontent"+i).style.display="none"
	 set_cookie("prod_tab"+i,"none");
      }
   }
}

for(i=1;i<=3;i++)
{
	if(get_cookie("prod_tab"+i)!='' && get_cookie("prod_tab"+i)!=null)
	{
		if (get_cookie("prod_tab"+i) == 'block')
		{
			getObj("tab"+i).className="tabOn"
         		getObj("tabcontent"+i).style.display="block"
		}
		else
		{
			getObj("tab"+i).className="tabOff"
 	        	getObj("tabcontent"+i).style.display="none"
		}
	}
}
</script-->

