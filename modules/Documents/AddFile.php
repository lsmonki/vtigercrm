<?php
/*********************************************************************************
** The contents of this file are subject to the vtiger CRM Public License Version 1.0
* ("License"); You may not use this file except in compliance with the License
* * The Original Code is:  vtiger CRM Open Source
* * The Initial Developer of the Original Code is vtiger.
* * Portions created by vtiger are Copyright (C) vtiger.
* * All Rights Reserved.
* *
* ********************************************************************************/
global $current_user;
global $theme;
global $adb;
global $app_strings;
global $mod_strings;
global $image_path;
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
$sql="select foldername,folderid from vtiger_attachmentsfolder order by foldername";
$res=$adb->pquery($sql,array());
for($i=0;$i<$adb->num_rows($res);$i++)
{
	$fid=$adb->query_result($res,$i,"folderid");
	$fldr_name[$fid]=$adb->query_result($res,$i,"foldername");
}
$params=array();
$sqlOS="select os from vtiger_os order by osid";
$res=$adb->pquery($sqlOS,$params);
$OS_array=array();
for($o=0;$o<$adb->num_rows($res);$o++)
{
	$OS_array[]=$adb->query_result($res,$o,"os");
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<style type="text/css">@import url("themes/<?php echo $theme;?>/style.css");</style>
</head>
	<body leftmargin=0 topmargin=0 marginheight=0 marginwidth=0 class=small>
	
<script language="javascript" src="include/js/general.js"></script>	
<script language="javascript" src="include/scriptaculous/prototype.js"></script>	
<form name="FileAdd" method="post" ENCTYPE="multipart/form-data" action="index.php?module=Documents&action=SaveFile&filemode=AddFile" onsubmit="return frmValidate();">
   <input type="hidden" name="filemode" value="AddFile">
   <input type="hidden" name="return_action" value="">
   <input type="hidden" name="folderid" id="f_id">
   <input type="hidden" name="crm_id" value="">
   <table width="100%" border="0" cellpadding="5" cellspacing="0" class="layerHeadingULine small">
       <tr>
	    <td class="layerPopupHeading" align="left" id="divHeader"></td>
	    <td align="right"><a href="javascript:;" onclick="parent.fnhide('fileLay');"><img src="<?php echo $image_path;?>close.gif" border="0"  align="absmiddle" /></a></td>
       </tr>
    </table>
    <br>
    <table border=0 cellspacing=0 cellpadding=5 width=95% align=center>
       <tr>
	   <td class=small >
	   <!-- popup specific content fill in starts -->
		 <table border=0 cellspacing=0 cellpadding=6 width=100% align=center bgcolor=white>
		     <tr id="showrow">
		     <td class="dvtCellLabel"  width="110" align="right"><font color='red'>*</font>&nbsp;<?php echo $mod_strings['LBL_FOLDER_NAME'];?></td>
			<td class="dvtCellInfo" width="300" colspan="2">
				<select name="fldr_name" id="fldrname_id" class="detailedViewTextBox" onfocus="this.className='detailedViewTextBoxOn'" onblur="this.className='detailedViewTextBox'" style="width:60%">
					<option value=''>--Select--</option>
					<?php 
						foreach($fldr_name as $key => $value){?>
							<option value="<?php echo $key;?>"><?php echo $value;?></option>
					<?php
						}	
					?>
				</select>
			</td>
		    </tr>
			<tr>
			<td class="dvtCellLabel"  width="110" align="right"><?php echo $mod_strings['LBL_DOWNLOAD_TYPE'];?></td>
			<td class="dvtCellInfo" colspan="2" width="300">
				<input type="radio" id="dldtype_1" name="filelocationtype" value="E" checked onclick="javascript:changeDldType('E');"><?php echo $mod_strings['LBL_URL'];?>&nbsp;&nbsp;
				<input type="radio" id="dldtype_0" name="filelocationtype" value="I" onclick="javascript:changeDldType('I');"><?php echo $mod_strings['LBL_INTERNAL'];?>
				
			</td>
			</tr>		     
			<tr id="externalfilename_id">
			 <td class="dvtCellLabel"  width="110" align="right"><font color='red'>*</font>&nbsp;<?php echo $mod_strings['LBL_EXTERNAL_FILE_NAME'];?></td>
			 <td class="dvtCellInfo" colspan="2" width="300"><input type="text" name="external_filename" id="external_filename_id" class="detailedViewTextBox" onfocus="this.className='detailedViewTextBoxOn'" onblur="this.className='detailedViewTextBox'" style="width:57%"></td>
		    </tr>
		      <tr>
		      <td class="dvtCellLabel"  width="110" align="right"><font color='red'>*</font>&nbsp;<?php echo $mod_strings['LBL_FILE_LOCATION'];?></td>
			 <td class="dvtCellInfo" colspan="2" width="300" id="fileCol_id"><input type="text" name=filelocation id="location_id" class="detailedViewTextBox" onfocus="this.className='detailedViewTextBoxOn'" onblur="this.className='detailedViewTextBox'" style="width:57%" value="http://"></td>
		      </tr>
		       <tr>
			  <td class="dvtCellLabel"  width="110" align="right"><?php echo $mod_strings['architecture'];?></td>
			  <td class="dvtCellInfo" colspan="2" width="300" nowrap><input type="radio" id="arc_0" name="arc" value="PD" checked onclick="javascript:choosePltfrm('PD')"><?php echo $mod_strings['LBL_PD'];?><input type="radio" id="arc_1" name="arc" value="PI" onclick="javascript:choosePltfrm('PI')"><?php echo $mod_strings['LBL_PIND'];?></td>
		       </tr>
			 <tr id="choosePltfrm_id">
			  <td class="dvtCellLabel"  width="110" align="right"><?php echo $mod_strings['ChoosePlatform'];?></td>
			  <td class="dvtCellInfo" colspan="2" width="300">
				 <select name="os" id="os_id" class="detailedViewTextBox" onfocus="this.className='detailedViewTextBoxOn'" onblur="this.className='detailedViewTextBox'" style="width:60%">
					<?php 
					foreach($OS_array as $os){
					?>
					<option value='<?php echo $os;?>'><?php echo $os;?></option>
					<?php }?>
				</select>	
			  </td>
		       </tr>

			

			<tr>
			<td class="dvtCellLabel"  width="110" align="right"><?php echo $mod_strings['filestatus'];?></td> 
			<td class="dvtCellInfo" colspan="2" width="300"><input type="radio" id="status_0" name="status" value="0"><?php echo $mod_strings['LBL_INACTIVE'];?>&nbsp;&nbsp;<input type="radio" id="status_1" name="status" value="1" checked><?php echo $mod_strings['LBL_ACTIVE'];?></td>
			</tr>
			<tr>
			    <td class="dvtCellLabel"  width="110" align="right"><?php echo $mod_strings['fileversion'];?></td>
			    <td class="dvtCellInfo" colspan="2" width="300"><input type="text" name="version" id="version_id" class="detailedViewTextBox" onfocus="this.className='detailedViewTextBoxOn'" onblur="this.className='detailedViewTextBox'" style="width:57%"></td>
			</tr>
		</table>
	    <!-- popup specific content fill in ends -->
	    </td>
	  </tr>
      </table>
     <table border=0 cellspacing=0 cellpadding=5 width=95% align="center">
	  <tr>
	       <td align="right">
	       <input type="submit" name="save" value=" &nbsp;<?php echo $mod_strings['LBL_UPLOAD_BUTTON'];?>&nbsp; " id="savebtn" class="crmbutton small save"></td>
		<td align="left"><input type="button" name="cancel" value="<?php echo $app_strings['LBL_CANCEL_BUTTON_LABEL'];?>" class="crmbutton small cancel" onclick="parent.fnhide('fileLay');">
		</td>
	  </tr>
    </table>
</form>
</body>
</html>
<script language="javascript">
function frmValidate()
{
	if($("location_id").value == "http://")
	{
		alert("File location cannot be empty");
		$("location_id").focus();
		return false;
	}
	if($("external_filename_id").value == "")
	{
		alert("File name cannot be empty");
		$("external_filename_id").focus();
		return false;
	}		
	if($("showrow").style.display == "")
	{
		if($("fldrname_id").value == "")
		{
			alert("Please select folder name");
		        $("fldrname_id").focus();
		        return false;
		}	
	}
	$("f_id").value=$("fldrname_id").value;
	parent.fnhide('fileLay');
	//parent.$('divId').style.display = "block";
	return true;
}
function changeDldType(type)
{
	var browser = navigator.appName;
	if(type == 'E')	
		$("fileCol_id").innerHTML='<input type="text" name=filelocation id=location_id class="detailedViewTextBox" onfocus="this.className=\'detailedViewTextBoxOn\'" onblur="this.className=\'detailedViewTextBox\'" style="width:57%" value="http://">';
	else
	{
		if(browser == 'Microsoft Internet Explorer')
			$("fileCol_id").innerHTML='<input type="file" name="filelocation" id="location_id" class="detailedViewTextBox" onfocus="this.className=\'detailedViewTextBoxOn\'" onblur="this.className=\'detailedViewTextBox\'" style="width:57%">';

		else
			$("fileCol_id").innerHTML='<input type="file" onclick="this.blur();" name="filelocation" id="location_id" class="detailedViewTextBox" onfocus="this.className=\'detailedViewTextBoxOn\'" onblur="this.className=\'detailedViewTextBox\'" style="width:57%">';
	}
		
	addFileName(type);		

}
function choosePltfrm(pltfrm)
{
	if(pltfrm == 'PD')
		$('choosePltfrm_id').style.display="";
	else
		$('choosePltfrm_id').style.display="none";

}
function addFileName(type)
{
	if(type == 'E')
	{
		$('externalfilename_id').style.display="";
		$('external_filename_id').value="";
	}		
	else
	{
		$('externalfilename_id').style.display="none";
		$('external_filename_id').value="none";
	}

}

</script>
