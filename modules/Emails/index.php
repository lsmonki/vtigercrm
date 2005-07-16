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
 * $Header: /advent/projects/wesat/vtiger_crm/sugarcrm/modules/Emails/index.php,v 1.3 2005/03/17 20:01:10 samk Exp $
 * Description: TODO:  To be written.
 ********************************************************************************/

global $theme;
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
require_once ($theme_path."layout_utils.php");

if(isset($_REQUEST['mailconnect']))
{
	 echo '<center><font color=red><b>'.$mod_strings['LBL_MAIL_CONNECT_ERROR_INFO'].'</b></color></center><br>';

//       echo '<center><font color=red><b>Unable to connect to mail server! </b></color></center><br>';
}
	

echo get_module_title("Emails", $mod_strings['LBL_MODULE_TITLE'], true); 
/*
$submenu = array('LBL_EMAILS_TITLE'=>'index.php?module=Emails&action=ListView.php','LBL_WEBMAILS_TITLE'=>'index.php?module=squirrelmail-1.4.4&action=redirect');
$sec_arr = array('index.php?module=Emails&action=ListView.php'=>'Emails','index.php?module=squirrelmail-1.4.4&action=redirect'=>'Emails'); 
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
	foreach($submenu as $label=>$filename)
	{
		$cur_mod = $sec_arr[$filename];
		$cur_tabid = getTabid($cur_mod);

		if($tab_per_Data[$cur_tabid] == 0)
		{
			
				list($lbl,$sname,$title)=split("_",$label);
				if(stristr($label,"EMAILS"))
				{
					
				echo '<td class="tabOn" nowrap><a href="index.php?module=Emails&action=ListView" class="tabLink">'.$mod_strings[$label].'</a></td>';

				$listView = $filename;
				$classname = "tabOff";
				}
				elseif(stristr($label,$_REQUEST['smodule']))
				{
				echo '<td class="tabOn" nowrap><a href="index.php?module=squirrelmail-1.4.4&action=redirect&smodule='.$_REQUEST['smodule'].'" class="tabLink">'.$mod_strings[$label].'</a></td>';	
					$listView = $filename;
					$classname = "tabOff";
				}
				else
				{
					echo '<td class="'.$classname.'" nowrap><a href="index.php?module=squirrelmail-1.4.4&action=redirect&smodule='.$sname.'" class="tabLink">'.$mod_strings[$label].'</a></td>';	
				}
				$classname = "tabOff";
			}
			
		}
?>
     <td width="100%" class="tabEnd">&nbsp;</td>
   </tr>
 </table></td>
 </tr>
<?
*/
include ('modules/Emails/ListView.php'); 

echo "<br><table width='250' cellpadding=0 cellspacing=0><tr><td>";
echo get_form_header($mod_strings['LBL_TOOL_FORM_TITLE'], "", false);
echo "</td></tr>";
echo "<tr><td class='formOuterBorder' style='padding: 10px'>";
echo "<ul>";
include('modules/Import/ImportButton.php');
//echo "<li><a href='index.php?module=squirrelmail-1.4.4&action=redirect'>".$mod_strings['LBL_FETCH_WEBMAIL']."</a></li>";
//echo "</ul>";
echo "</td></tr></table>";
//require_once('modules/squirrelmail-1.4.4/right_main.php');
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

