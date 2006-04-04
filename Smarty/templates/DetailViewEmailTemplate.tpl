<script language="JavaScript" type="text/javascript" src="include/js/menu.js"></script>
<style type="text/css">@import url(../themes/blue/style.css);</style>

<table width="100%" border="0" cellpadding="0" cellspacing="0">
<tr>
	{include file='SettingsMenu.tpl'}
<td width="75%" valign="top">
<!-- EMAIL TEMPLATE PAGE STARTS HERE -->
<table width="100%" border="0" cellpadding="0" cellspacing="0" height="100%">
	<tr>
	   <td class="showPanelBg" valign="top" width="90%"  style="padding-left:20px; "><br />
              <span class="lvtHeaderText"><b><a href="index.php?module=Settings&action=index&parenttab=Settings">{$MOD.LBL_SETTINGS}</a> > {$UMOD.LBL_EMAIL_TEMPLATE_INFORMATION} &quot;{$TEMPLATENAME}&quot;</b></span>
	        <hr noshade="noshade" size="1" />
	   </td>
		<td width="10%" class="showPanelBg">&nbsp;</td>
	</tr>
	 <tr>
	     <td width="90%" style="padding-left:20px;" valign="top">
		<table width="100%"  border="0" cellspacing="0" cellpadding="0">
		    <form method="post" action="index.php" name="etemplatedetailview">  
			<input type="hidden" name="action" >
			<input type="hidden" name="module" value="Users">
			<input type="hidden" name="templatename" value="{$TEMPLATENAME}">
			<input type="hidden" name="templateid" value="{$TEMPLATEID}">
			<input type="hidden" name="foldername" value="{$FOLDERNAME}">
		    
			<tr><td colspan="2">&nbsp;</td></tr>
			<tr><td colspan="2">
			    <table width="100%" border="0" cellpadding="0" cellspacing="0">
				<tr><td width="75%" valign="middle" >
					<img src="{$IMAGE_PATH}ViewTemplate.gif" align="left" />
					<span class="genHeaderBig">{$TEMPLATENAME}</span><br />
					<span class="dashMnuUnSel">{$TEMPLATENAME} Template - provided by vtiger CRM Team</span> 
				    </td>
				    <td width="25%" align="right" valign="bottom"><input type="submit" name="Button" value="Edit this template" class="small" onclick="this.form.action.value='editemailtemplate'"/></td>
				</tr>
			    </table>
			    </td>
			</tr>
			  <tr>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
			  </tr>
			  
			  <tr>
			    <td colspan="2" style="border:2px solid #CCCCCC;">
				<table width="100%" border="0" cellpadding="5" cellspacing="0">
			          <tr>
			
    	                		<td width="25%" align="right" bgcolor="#F6F6F6"><b>{$UMOD.LBL_FOLDER}</b></td>
        	            		<td width="75%" class="mnuTab">{$FOLDERNAME}</td>
            	      		  </tr>
					  <tr><td bgcolor="#F6F6F6">&nbsp;</td>
					  <td>&nbsp;</td></tr>
	
	                  	  <tr>
			
    	        		        <td width="25%" align="right" bgcolor="#F6F6F6"><b>{$UMOD.LBL_SUBJECT}</b></td>
        	            		<td width="75%" class="mnuTab">{$DESCRIPTION}</td>
            	      		  </tr>
					  <tr><td bgcolor="#F6F6F6">&nbsp;</td>
					  <td>&nbsp;</td></tr>

	                  	  <tr>
	                    		<td  align="right" bgcolor="#F6F6F6" valign="top"><b>{$UMOD.LBL_MESSAGE}</b></td>
	                    		<td width="75%" valign="top" class="mnuTab">{$BODY}</td>
                      		  </tr>
                		</table>
			   </td>
		         </tr>
		</table>
		</td>
		<td>&nbsp;</td>

</form>	</tr>
</table>
<!-- END -->


</td>
</tr>
</table>

{$JAVASCRIPT}
{include file='SettingsSubMenu.tpl'}


