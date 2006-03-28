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
global $current_user,$mod_strings;
require_once('include/database/PearDatabase.php');
require_once('modules/Calendar/CalendarCommon.php');
 echo get_module_title($mod_strings['LBL_MODULE_NAME'], $mod_strings['LBL_MODULE_NAME'], true);
 echo "\n<BR>\n";
 $t=Date("Ymd");
 $userDetails=getOtherUserName($current_user->id);
 $html = getHeaderTab($t,'shared');
 echo $html;
?>
<table width="90%" align="center" border="0" cellspacing="0" cellpadding="5">
<tr>
	<td class="genHeaderSmall" colspan=3 align="left"><?php echo$mod_strings['LBL_CALENDAR_SHARED']?></td>
</tr>
<form name="SharedList" method="post" action="index.php">
<input name="idlist" type="hidden">
<tr>
         <td colspan=3 style="padding-right:20px" nowrap><input class="small" type="submit" value="Disable Sharing" onclick="return DisableSharing()"/>
	</td>
</tr>
<tr>
<td class="detailedViewHeader" width="3%"><input type="checkbox"  name="selectall" onClick=toggleSelect(this.checked,"selected_id")></td>
<?php
$header = getSharedUserListViewHeader();
foreach($header as $index=>$headerlabel)
{
        echo '<th class="detailedViewHeader">'.$headerlabel.'</th>';
}
echo '</tr><tr>';
$shareduser_entries = array();
$cnt=1;
$shareduser_ids = getSharedUserId($current_user->id);
$output = '';
if($shareduser_ids != '')
{
	foreach($shareduser_ids as $key=>$value)
	{
		$shareduser_entries[$value] = getSharedUserListViewEntries($value);
	}
	foreach($shareduser_entries as $array_key=>$array_value)
	{
		if ($cnt%2==0)
		{
	        	$output .='<tr class="dvtCellLabel">';
			$output .='<td><input type="checkbox" NAME="selected_id" value="'.$array_key.'" onClick=toggleSelectAll(this.name,"selectall")></td>';
		}
		else
		{
			$output .='<tr class="dvtCellInfo">';
			$output .='<td><input type="checkbox" NAME="selected_id" value="'.$array_key.'" onClick=toggleSelectAll(this.name,"selectall")></td>';
		}
		$output .='<td>';
		$output .=$array_value[0].'</td>';
		$output .='<td>';
		$output .=$array_value[1].'</td>';
		$output .='</tr>';
		$cnt++;
	}
	echo $output;
}
else
{
	echo '	<tr style="height: 25px;" bgcolor="white">
		<td><i>None</i></td>

		</tr>';
}
?>
</tr></form><table width="90%" align="center" border="0" cellspacing="0" cellpadding="5">
<tr style="height: 25px;"><td colspan=3>&nbsp;</td></tr>
<tr>
        <td class="genHeaderSmall" colspan=3 align="left"><?php echo$mod_strings['LBL_CALENDAR_SHARING']?></td>
</tr>
<!--User list-->
<form name="SharingForm" method="post" action="index.php">
  <input type="hidden" name="module" value="Calendar">
  <input type="hidden" name="action" value="updateCalendarSharing">
  <input type="hidden" name="current_userid" value="<? echo $current_user->id ?>" >
<table  style="border:1px solid #dddddd;" border=0 align="center" cellspacing=0 cellpadding=2 width=90%>
	<tr>
	<td valign=top>
	<table border=0 cellspacing=0 cellpadding=2 width=100%>
		<tr>
		  <td colspan=3>
		  <ul style="padding-left:20px">
		    <li>To share your calendar, select the users from the "Available Users" list and click the "Add"  button.
		    <li>To remove, select the users in the "Selected Users" list and the click "Remove" button.
		  </ul>
		  </td>
		</tr>
		<tr>
		  <td><b>Available  Users</b></td>
		  <td>&nbsp;</td>
		  <td><b>Selected Users</b></td>
		</tr>
		<tr>
		  <td width=40% align=center valign=top>
		    <select name="available" id="available" class=small size=5 multiple style="height:70px;width:100%">
		    <?php
		    for($i=1;$i<=count($userDetails)+1;$i++){
			if($userDetails[$i] != '')
				echo "<option value=".$i.">".$userDetails[$i]."</option>";
		    }
		    ?>
		    </select>
		  </td>
		  <input type=hidden name="sharedid" value="">
		  <td width=20% align=center valign=top>
                    <input type=button value="Add >>" class=small style="width:100%" onClick="addColumn()"><br>
                    <input type=button value="<< Remove " class=small style="width:100%" onClick="delColumn()">
    		  </td>
		  <td width=40% align=center valign=top>
		    <select name="selectedusers" id="selectedusers" class=small size=5 multiple style="height:70px;width:100%">
		    </select>
		  </td>
		</tr>
	</table>
	</td></tr>
</table>

<tr>
	<td align=center colspan=3>
           <input title='Save [Alt+S]' accessKey='S' type="submit" class=small style="width:90px" onClick="return validate()" value="Save">
           <input type="button" class=small style="width:90px" value="Cancel">
        </td>
</tr>
</form>
<tr style="height: 25px;"><td colspan=3>&nbsp;</td></tr>
</table>
</td></tr></table>
</td></tr></table>
</td></tr></table>

<script language="JavaScript" type="text/JavaScript">

function validate()
{
        formSelectColumnString();

        if(document.SharingForm.sharedid.value.replace(/^\s+/g, '').replace(/\s+$/g, '').length==0)
        {

                alert('Select atleast one user');
                return false;
        }
        return true;
}

setObjects();
</script>
