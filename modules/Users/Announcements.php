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

global $adb;
global $current_user;
global $theme;
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
$query="select * from announcement where creatorid=".$current_user->id;
$result=$adb->query($query);
$announcement=$adb->query_result($result,0,'announcement');
$title_prev=$adb->query_result($result,0,'title');
$id=$adb->query_result($result,0,'creatorid');
if($id != $current_user->id)
	$announcement='';

	$html='<script>
	function ajaxSaveResponse(response)
	{
		document.getElementById("announcement").value=response.responseText;
		hide("an_busy");
	}
	function Announcement()
	{
	show("an_busy");	
	var ajaxObj = new Ajax(ajaxSaveResponse);
	//alert(document.getElementById("announcement").value);
	var announcement=document.getElementById("announcement").value;
	var title=document.getElementById("title_announce").value;
	var urlstring = "module=Users&action=UsersAjax&announcement="+announcement+"&announce_save=yes&title_announcement="+title;
	if(announcement != "")
		ajaxObj.process("index.php?",urlstring);
	
	}
	</script>
	<br><br><table align="center"><tbody><tr style="height: 40px;">
	<td class="dvtSelectedCell" align="center" nowrap="nowrap" colspan="4">Announcements<div id="an_busy" style="display:none;float:left;position:relative;"><img src="'.$image_path.'vtbusy.gif" align="right"></div></td>
	</tr>
	<tr style="height: 25px;"><td class="dvtCellLabel" align="right" width="20%">Title</td>
	<td><input name="title_announce" id="title_announce" class="detailedViewTextBox" onfocus="this.className=\'detailedViewTextBoxOn\'" onblur="this.className=\'detailedViewTextBox\'" type="text" value="'.$title_prev.'"></td></tr>
	<tr style="height: 25px;">
	<td class="dvtCellLabel" align="right" width="20%">
	Create Announcement</td>	
	<td colspan="3"><textarea class="detailedViewTextBox" onfocus="this.className=\'detailedViewTextBoxOn\'" id= "announcement" name="announcement" onblur="this.className=\'detailedViewTextBox\'" cols="100" rows="8">'.$announcement.'</textarea>
	</td>
	</tr>
	<tr style="height: 25px;"><td colspan="4">&nbsp;</td></tr>
	<tr>
	<td colspan="4" style="padding: 5px;">
	<div align="center">
	<input title="Save [Alt+S]" accesskey="S" class="small" onclick="javascript:Announcement();" name="button" value="  Save  " style="width: 70px;" type="submit">
	<input title="Cancel [Alt+X]" accesskey="X" class="small" onclick="window.history.back()" name="button" value="  Cancel  " style="width: 70px;" type="button">
	</div>
	</td>
	</tr>
	</tbody></table>';
	echo $html;
?>
