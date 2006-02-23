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
<HTML>
<BODY>
<script type='text/javascript' src='modules/Products/multifile.js'></script>
<FORM METHOD="post" action="index.php?module=uploads&action=add2db&return_module=<?php echo $_REQUEST['return_module']?>" enctype="multipart/form-data">
<?php
	$ret_action = $_REQUEST['return_action'];
	$ret_module = $_REQUEST['return_module']; 
	$ret_id = $_REQUEST['return_id'];

	echo get_module_title($ret_module,$ret_module." : ".$mod_strings['LBL_ATTACH_FILE'],true); 
?>
<br>
<INPUT TYPE="hidden" NAME="MAX_FILE_SIZE" VALUE="1000000">
<INPUT TYPE="hidden" NAME="return_module" VALUE="<?php echo $ret_module ?>">
<INPUT TYPE="hidden" NAME="return_action" VALUE="<?php echo $ret_action ?>">
<INPUT TYPE="hidden" NAME="return_id" VALUE="<?php echo $ret_id ?>">
<div align="right" style="width:60%"><font class="required">*</font><?php echo $app_strings['NTC_REQUIRED'] ?></div>

<table width="60%" border="0" cellspacing="0" cellpadding="0" class="formOuterBorder"> 
   <tr> 
	<td class="formSecHeader"> <?php echo $mod_strings['LBL_ATTACH_FILE_INFO'] ?> </td>
   </tr>
   <tr> 
	<td> 
	   <TABLE BORDER="0" cellspacing="1" cellpadding="0">
	      <TR> 
		  <TD class="dataLabel" width="25%">
			<div align="right"><font class="required">*</font> <?php echo $mod_strings['LBL_FILENAME']; ?> </div>
		  </TD>
        	  <TD>
			  <input name="imagelist" type="hidden" value="">
			  <div id="files_list" style="border: 1px solid grey; width: 500px; padding: 5px; background: rgb(255, 255, 255) none repeat scroll 0%; -moz-background-clip: initial; -moz-background-origin: initial; -moz-background-inline-policy: initial; font-size: x-small">Files Maximum 6
			  <input id="my_file_element" type="file" name="file_1" >
			<!--{foreach key=num item=image from=$maindata[3]}
			{if $image neq ''}
			<div align="center">
			<img src="test/product/{$image}" height="50">&nbsp;&nbsp;[{$image}]<input id="file_{$num}" v
			alue="Delete" type="button" onclick='this.parentNode.parentNode.removeChild(this.parentNode);delRowEmt("{$image}")'>
			</div>
			{/if}
			{/foreach}-->
<script>
	<!-- Create an instance of the multiSelector class, pass it the output target and the max number of files -->
	var multi_selector = new MultiSelector( document.getElementById( 'files_list' ), 6 );
	<!-- Pass in the file element -->
	multi_selector.addElement( document.getElementById( 'my_file_element' ) );
</script>

			  <!--<INPUT TYPE="file" NAME="binFile"></TD>-->
	      </TR>
              <TR> 
          	  <TD class="dataLabel" width="25%" valign="top" >
			<div align="right"> <?php echo $mod_strings['LBL_DESCRIPTION']; ?></div>
		  </TD>
	          <TD width="75%"><TEXTAREA NAME="txtDescription" ROWS="3" COLS="50"></TEXTAREA></TD>
              </TR>
	   </TABLE>
	</td>
   </tr>
</table>

<br>

<table width="60%" cellpadding="0" cellspacing="0" border="0">
   <tr> 
	<td><div align="center"><INPUT TYPE="submit" class="button" VALUE="<?php echo $mod_strings['LBL_UPLOAD']; ?>"></div></td>
   </tr>
</table>

</FORM>
</BODY>
</HTML>
