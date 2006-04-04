<script language="JavaScript" type="text/javascript" src="include/js/menu.js"></script>
<style type="text/css">@import url(themes/blue/style.css);</style>
<table width="100%" border="0" cellpadding="0" cellspacing="0">
<tr>
	{include file='SettingsMenu.tpl'}
<td width="75%" valign="top">

<!--  CREATE WORD TEMPLATES PAGE STARTS HERE -->
<table width="100%" border="0" cellpadding="0" cellspacing="0" height="100%">
<form action="index.php?module=Users&action=add2db" method="post" enctype="multipart/form-data">
<input type="hidden" name="return_module" value="Settings">
<input type="hidden" name="MAX_FILE_SIZE" value="100000">
<input type="hidden" name="action">
<tr>
<td class="showPanelBg" valign="top" width="95%"  style="padding-left:20px; "><br />
<span class="lvtHeaderText"><b><a href="index.php?module=Settings&action=index&parenttab=Settings">{$MOD.LBL_SETTINGS} </a> {$UMOD.LBL_MAILMERGE_TEMPLATES_ATTACHMENT}</b></span>
<hr noshade="noshade" size="1" />
</td>
	<td width="5%" class="showPanelBg">&nbsp;</td>
</tr>
<tr>
<td width="98%" style="padding-left:20px;" valign="top">
<!-- module Select Table -->
<table width="95%"  border="0" cellspacing="0" cellpadding="0" align="center">
<tr>
	<td width="7" height="6" style="font-size:1px;font-family:Arial, Helvetica, sans-serif;"><img src="{$IMAGE_PATH}top_left.jpg" align="top"  /></td>
	<td bgcolor="#EBEBEB" style="font-size:1px;font-family:Arial, Helvetica, sans-serif;height:6px;"></td>
	<td width="8" height="6" style="font-size:1px;font-family:Arial, Helvetica, sans-serif;"><img src="{$IMAGE_PATH}top_right.jpg" width="8" height="6" align="top" /></td>
</tr>
<tr>
	<td bgcolor="#EBEBEB" width="7"></td>
	<td bgcolor="#ECECEC" style="padding-left:10px;padding-top:10px;vertical-align:top;">
		<table width="100%"  border="0" cellspacing="0" cellpadding="10">
                <tr>
                      <td rowspan="11" bgcolor="#FFFFFF"  width="25%" style="background-image:url(include/images/noimage.gif);background-position:center;background-repeat:no-repeat; " >&nbsp;</td>
                      <td colspan="2" class="genHeaderBig" width="75%"><img src="{$IMAGE_PATH}fp1.gif" width="59" height="40" align="absmiddle" />{$UMOD.LBL_TEMPLATE_DETAILS} </td>
               </tr>
		<tr>
                      <td align="right" valign="top"><b>{$UMOD.LBL_DESCRIPTION}  : </b></td>
                      <td><textarea name="txtDescription" class="txtBox" rows="3" value={$textDesc} /></textarea></td>
                </tr>
                <tr><td colspan="2"  width="75%" style="padding-bottom:0px;" ><hr /> </td></tr>

		<tr>
			<td  width="75%" colspan="2"  >
				<img src="{$IMAGE_PATH}fp2.gif" width="59" height="40"  align="left"/>
				<span class="genHeaderBig">{$UMOD.LBL_SELECT_MODULE}</span><br />
				 Select a module to assign this Template
			</td>
		</tr>
		<tr>
                      <td align="right"><b>{$UMOD.LBL_MODULENAMES} : </b></td>

                      <td><select name="target_module" class="txtBox">
			<option value="Leads">{$APP.COMBO_LEADS}</option>	
			<option value="Accounts">{$APP.COMBO_ACCOUNTS}</option>	
			<option value="Contacts">{$APP.COMBO_CONTACTS}</option>	
			<option value="HelpDesk">{$APP.COMBO_HELPDESK}</option>	
                      </select></td>
                </tr>
		 <tr><td colspan="2"  width="75%" style="padding-bottom:0px;" ><hr /> </td></tr>
		<tr>
		      <td  width="75%" colspan="2" style="padding-bottom:0px;padding-top:0px; "  >
			<img src="{$IMAGE_PATH}fp3.gif" width="59" height="40"  align="absmiddle"/>
				<span class="genHeaderBig">{$UMOD.LBL_UPLOAD_FILE} </span>
		      </td>
		</tr>
		 <tr>
                      <td align="right" valign="top"><b>{$UMOD.LBL_FILE_MERGE} : </b></td>
                      <td><input type="file" name="binFile" size="40"  /><br />(Eg - .doc, .rtf files)</td>
		 </tr>
		 <tr>
		      <td colspan="2"  width="75%" style="border-top:1px dashed #CCCCCC;border-bottom:1px dashed #CCCCCC;"  align="center">
			<input title="{$APP.LBL_SAVE_BUTTON_LABEL}" accessKey="{$APP.LBL_SAVE_BUTTON_KEY}" type="submit" name="button" value="{$APP.LBL_SAVE_BUTTON_LABEL}" onclick="this.form.action.value='add2db';" class="classBtn" />&nbsp;
			&nbsp;<input title="{$APP.LBL_CANCEL_BUTTON_LABEL}" accessKey="{$APP.LBL_CANCEL_BUTTON_KEY}" onclick="window.history.back()" type="button" name="button" value="{$APP.LBL_CANCEL_BUTTON_LABEL}" class="classBtn" />
 		      </td>
		 </tr>
		 <tr>
		       <td colspan="2" style="padding-bottom:0px;" >&nbsp;</td>
		 </tr>
                 </table>
		   </td>
			<td bgcolor="#EBEBEB" width="8"></td>
		  </tr>
		  <tr>
				<td width="7" height="8" style="font-size:1px;font-family:Arial, Helvetica, sans-serif;"><img src="{$IMAGE_PATH}bottom_left.jpg" align="bottom"  /></td>
				<td bgcolor="#ECECEC" height="8" style="font-size:1px;" ></td>
				<td width="8" height="8" style="font-size:1px;font-family:Arial, Helvetica, sans-serif;"><img src="{$IMAGE_PATH}bottom_right.jpg" align="bottom" /></td>
		  </tr>
		  </table><br />
		</td>

		<td>&nbsp;</td>
	</tr>
</form>
</table>
</td>
</tr>
</table>
	{include file='SettingsSubMenu.tpl'}
<!-- END -->
