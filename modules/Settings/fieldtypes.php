<!--*********************************************************************************
** The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
*
 ********************************************************************************/
-->
<script language="JavaScript" type="text/javaScript" src="include/js/general.js"></script>

<?php
    //global $mod_strings;
	global $theme;
$theme_path="themes/".$theme."/";	
$image_path=$theme_path."images/";
?>

<style>
.fieldType {
	padding: 2;
	width: 100%;
	font-family: Verdana, Arial, Helvetica, Sans-serif;
	font-size: 11px;
	cursor: default;
}
.sel {
	background: #0A246A;
	color: #FFF;
}
.hilite {
	border: 1px dotted #F5DB95;
}
.fieldTypeImgBg {
	background-color: #FFF;
}
</style>
<body onKeyDown="parent.srchFieldType(event)" onKeyUp="parent.setVisible()" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table width="100%" border="0" cellspacing="0" cellpadding="0" onMouseMove="clearTextSelection()" onDblClick="clearTextSelection()">
  <tr> 
    <td width="20"> <div align="center"><img src="<?php echo $image_path."text.gif" ?>" width="20" height="20"></div></td>
    <td nowrap id="field0" class="fieldType" onClick="parent.selFieldType(0)"><?php echo $mod_strings['Text']; ?></td>
  </tr>
  <tr> 
    <td width="20"><div align="center"><img src="<?php echo $image_path."number.gif" ?>"  width="20" height="20"></div></td>
    <td nowrap id="field1" class="fieldType" onClick="parent.selFieldType(1)"><?php echo $mod_strings['Number']; ?></td>
  </tr>
  <tr> 
    <td width="20"><div align="center"><img src="<?php echo $image_path."percent.gif" ?>" width="20" height="20"></div></td>
    <td nowrap id="field2" class="fieldType" onClick="parent.selFieldType(2)"><?php echo $mod_strings['Percent']; ?></td>
  </tr>
  <tr> 
    <td width="20"><div align="center"><img src="<?php echo $image_path."currency.gif" ?>" width="20" height="20"></div></td>
    <td nowrap id="field3" class="fieldType" onClick="parent.selFieldType(3)"><?php echo $mod_strings['Currency']; ?></td>
  </tr>
  <tr> 
    <td width="20"><div align="center"><img src="<?php echo $image_path."date.gif" ?>" width="20" height="20"></div></td>
    <td nowrap id="field4" class="fieldType" onClick="parent.selFieldType(4)"><?php echo $mod_strings['Date']; ?></td>
  </tr>
  <tr> 
    <td width="20"><div align="center"><img src="<?php echo $image_path."email.gif" ?>" width="20" height="20"></div></td>
    <td nowrap id="field5" class="fieldType" onClick="parent.selFieldType(5)"><?php echo $mod_strings['Email']; ?></td>
  </tr>
  <tr> 
    <td width="20"><div align="center"><img src="<?php echo $image_path."phone.gif" ?>" width="20" height="20"></div></td>
    <td nowrap id="field6" class="fieldType" onClick="parent.selFieldType(6)"><?php echo $mod_strings['Phone']; ?></td>
  </tr>
  <tr>
    <td width="20"><div align="center"><img src="<?php echo $image_path."picklist.gif" ?>" width="20" height="20"></div></td>
    <td nowrap id="field7" class="fieldType" onClick="parent.selFieldType(7)"><?php echo $mod_strings['PickList']; ?></td>
  </tr>
  <tr>
    <td width="20"><div align="center"><img src="<?php echo $image_path."url.gif" ?>" width="20" height="20"></div></td>
    <td nowrap id="field8" class="fieldType" onClick="parent.selFieldType(8)"><?php echo $mod_strings['LBL_URL']; ?></td>
  </tr>
  <tr>
    <td width="20"><div align="center"><img src="<?php echo $image_path."checkbox.gif" ?>" width="20" height="20"></div></td>
    <td nowrap id="field9" class="fieldType" onClick="parent.selFieldType(9)"><?php echo $mod_strings['LBL_CHECK_BOX']; ?></td>
  </tr>
  <tr>
    <td width="20"><div align="center"><img src="<?php echo $image_path."text.gif" ?>" width="20" height="20"></div></td>
    <td nowrap id="field10" class="fieldType" onClick="parent.selFieldType(10)"><?php echo $mod_strings['LBL_TEXT_AREA']; ?></td>
  </tr>
  <tr>
    <td width="20"><div align="center"><img src="<?php echo $image_path."picklist.gif" ?>" width="20" height="20"></div></td>
    <td nowrap id="field11" class="fieldType" onClick="parent.selFieldType(11)"><?php echo $mod_strings['LBL_MULTISELECT_COMBO']; ?></td>
  </tr>
</table>
</body>
</html>
<script>
   parent.init()
   parent.currFieldIdx=parent.getObj('fieldType').value;
   parent.selFieldType(parent.getObj('fieldType').value,'',true);
</script>
