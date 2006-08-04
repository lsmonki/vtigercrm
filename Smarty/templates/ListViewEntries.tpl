{*<!--
/*********************************************************************************
  ** The contents of this file are subject to the vtiger CRM Public License Version 1.0
   * ("License"); You may not use this file except in compliance with the License
   * The Original Code is:  vtiger CRM Open Source
   * The Initial Developer of the Original Code is vtiger.
   * Portions created by vtiger are Copyright (C) vtiger.
   * All Rights Reserved.
  *
 ********************************************************************************/
-->*}
&#&#&#{$ERROR}&#&#&#

     <form name="massdelete" method="POST">
     <input name='search_url' id="search_url" type='hidden' value='{$SEARCH_URL}'>
     <input name='allids' type='hidden' value='{$ALLIDS}'>
     <input name="idlist" id="idlist" type="hidden">
     <input name="change_owner" type="hidden">
     <input name="change_status" type="hidden">
	<table border=0 cellspacing=1 cellpadding=0 width=100% class="lvtBg">
	            <tr style="background-color:#efefef">
		      <td>
		         <table border=0 cellspacing=0 cellpadding=2 width=100% class="small">
			      <tr>
				 <td style="padding-right:20px" nowrap>
                                 {foreach key=button_check item=button_label from=$BUTTONS}
                                        {if $button_check eq 'del'}
                                             <input class="crmbuttom small delete" type="button" value="{$button_label}" onclick="return massDelete('{$MODULE}')"/>
                                        {elseif $button_check eq 's_mail'}
                                             <input class="crmbuttom small edit" type="button" value="{$button_label}" onclick="return eMail('{$MODULE}',this)"/>
                                        {elseif $button_check eq 's_cmail'}
                                             <input class="crmbuttom small edit" type="button" value="{$button_label}" onclick="return massMail()"/>
                                        {elseif $button_check eq 'c_status'}
                                             <input class="crmbuttom small edit" type="button" value="{$button_label}" onclick="return change(this,'changestatus')"/>
					{elseif $button_check eq 'c_owner'}
                                                {if $MODULE neq 'Notes' && $MODULE neq 'Products' && $MODULE neq 'Faq' && $MODULE neq 'Vendors' && $MODULE neq 'PriceBooks'}
                                                     <input class="crmbuttom small edit" type="button" value="{$button_label}" onclick="return change(this,'changeowner')"/>
                                                {/if}
                                        {/if}

                                 {/foreach}
                                 </td>
				 <td style="padding-right:20px" class="small" nowrap>{$RECORD_COUNTS}</td>
		        	 <td nowrap >
					<table border=0 cellspacing=0 cellpadding=0 class="small">
					     <tr>{$NAVIGATION}</tr>
					</table>
                                 </td>
				 <td width=100% align="right">
				 <!-- This if condition is added to hide the Custom View Links in Imported ListView -->
				 {if $HIDE_CUSTOM_LINKS neq 1}
				   <table border=0 cellspacing=0 cellpadding=0 class="small">
					<tr>
						<td>{$APP.LBL_VIEW}</td>
						<td style="padding-left:5px;padding-right:5px">
						    <SELECT id="viewname" NAME="viewname" class="small" onchange="showDefaultCustomView(this,'{$MODULE}')">{$CUSTOMVIEW_OPTION}</SELECT></td>
						    {if $ALL eq 'All'}
							<td><a href="index.php?module={$MODULE}&action=CustomView">{$APP.LNK_CV_CREATEVIEW}</a>
							<span class="small">|</span>
                                                        <span class="small" disabled>{$APP.LNK_CV_EDIT}</span>
							<span class="small">|</span>
                                                        <span class="small" disabled>{$APP.LNK_CV_DELETE}</span></td>
						    {else}
							<td><a href="index.php?module={$MODULE}&action=CustomView">{$APP.LNK_CV_CREATEVIEW}</a>
							<span class="small">|</span>
							<a href="index.php?module={$MODULE}&action=CustomView&record={$VIEWID}">{$APP.LNK_CV_EDIT}</a>
							<span class="small">|</span>
							<a href="index.php?module=CustomView&action=Delete&dmodule={$MODULE}&record={$VIEWID}">{$APP.LNK_CV_DELETE}</a></td>
						    {/if}	
					</tr>
				   </table>
				 {/if}
				 </td>	
               		      </tr>
			 </table>
                         <div  style="width:100%;border-top:1px solid #999999;border-bottom:1px solid #999999;">
			 <table border=0 cellspacing=1 cellpadding=3 width=100% style="background-color:#cccccc;" class="small">
			      <tr>
	             		 <td class="lvtCol"><input type="checkbox"  name="selectall" onClick=toggleSelect(this.checked,"selected_id")></td>
				 {foreach item=header name=listviewforeach from=$LISTHEADER}
        			 <td class="lvtCol">{$header}</td>
			         {/foreach}
			      </tr>
			      {foreach item=entity key=entity_id from=$LISTENTITY}
			      <tr bgcolor=white onMouseOver="this.className='lvtColDataHover'" onMouseOut="this.className='lvtColData'"  >
				 <td width="2%"><input type="checkbox" NAME="selected_id" value= '{$entity_id}' onClick=toggleSelectAll(this.name,"selectall")></td>
				 {foreach item=data from=$entity}	
				 <td>{$data}</td>
	                         {/foreach}
			      </tr>
				{foreachelse}
				<tr><td style="background-color:#efefef;height:340px" align="center" colspan="{$smarty.foreach.listviewforeach.iteration+1}">
						<div style="border: 3px solid rgb(153, 153, 153); background-color: rgb(255, 255, 255); width: 45%; position: relative; z-index: 10000000;">
							{assign var=vowel_conf value='LBL_A'}
							{if $MODULE eq 'Accounts' || $MODULE eq 'Invoice'}
								{assign var=vowel_conf value='LBL_AN'}
							{/if}
							{assign var=MODULE_CREATE value=$SINGLE_MOD}
							{if $MODULE eq 'HelpDesk'}
								{assign var=MODULE_CREATE value='Ticket'}
							{/if}
							{if $CHECK.EditView eq 'yes' && $MODULE neq 'Emails' && $MODULE neq 'Webmails'}
							
							<table border="0" cellpadding="5" cellspacing="0" width="98%">
							<tr>
								<td rowspan="2" width="25%"><img src="{$IMAGE_PATH}empty.jpg" height="60" width="61"></td>
								<td style="border-bottom: 1px solid rgb(204, 204, 204);" nowrap="nowrap" width="75%"><span class="genHeaderSmall">{$APP.LBL_NO} {$APP.$MODULE_CREATE}s {$APP.LBL_FOUND} !</span></td>
							</tr>
							<tr>
							<td class="small" align="left" nowrap="nowrap">{$APP.LBL_YOU_CAN_CREATE} {$APP.$vowel_conf} {$APP.$MODULE_CREATE} {$APP.LBL_NOW}. {$APP.LBL_CLICK_THE_LINK}:<br>
								   {if $MODULE neq 'Activities'}	
						  			&nbsp;&nbsp;-<a href="index.php?module={$MODULE}&action=EditView&return_action=DetailView&parenttab={$CATEGORY}">{$APP.LBL_CREATE} {$APP.$vowel_conf} {$APP.$MODULE_CREATE}</a><br>
								   {else}
									&nbsp;&nbsp;-<a href="index.php?module={$MODULE}&amp;action=EditView&amp;return_module=Activities&amp;activity_mode=Events&amp;return_action=DetailView&amp;parenttab={$CATEGORY}">{$APP.LBL_CREATE} {$APP.LBL_AN} {$APP.Event}</a><br>
									&nbsp;&nbsp;-<a href="index.php?module={$MODULE}&amp;action=EditView&amp;return_module=Activities&amp;activity_mode=Task&amp;return_action=DetailView&amp;parenttab={$CATEGORY}">{$APP.LBL_CREATE} {$APP.LBL_A} {$APP.Task}</a>
								   {/if}
								</td>
							</tr>
							</table> 
							{else}
							<table border="0" cellpadding="5" cellspacing="0" width="98%">
							<tr>
								<td rowspan="2" width="25%"><img src="{$IMAGE_PATH}denied.gif"></td>
								<td style="border-bottom: 1px solid rgb(204, 204, 204);" nowrap="nowrap" width="75%"><span class="genHeaderSmall">{$APP.LBL_NO} {$APP.$MODULE_CREATE}s {$APP.LBL_FOUND} !</span></td>
							</tr>
							<tr>
								<td class="small" align="left" nowrap="nowrap">{$APP.LBL_YOU_ARE_NOT_ALLOWED_TO_CREATE} {$APP.$vowel_conf} {$APP.$MODULE_CREATE}<br>
								</td>
							</tr>
							</table>
							{/if}
						</div>					
				</td></tr>	
			      {/foreach}
			 </table>
			 </div>
			 <table border=0 cellspacing=0 cellpadding=2 width=100%>
			      <tr>
				 <td style="padding-right:20px" nowrap>
                                 {foreach key=button_check item=button_label from=$BUTTONS}
                                        {if $button_check eq 'del'}
                                             <input class="crmbuttom small delete" type="button" value="{$button_label}" onclick="return massDelete('{$MODULE}')"/>
                                        {elseif $button_check eq 's_mail'}
                                             <input class="crmbuttom small edit" type="button" value="{$button_label}" onclick="return eMail('{$MODULE}',this)"/>
                                        {elseif $button_check eq 's_cmail'}
                                             <input class="crmbuttom small edit" type="submit" value="{$button_label}" onclick="return massMail()"/>
                                        {elseif $button_check eq 'c_status'}
                                             <input class="crmbuttom small edit" type="button" value="{$button_label}" onclick="return change(this,'changestatus')"/>
					{elseif $button_check eq 'c_owner'}
                                                {if $MODULE neq 'Notes' && $MODULE neq 'Products' && $MODULE neq 'Faq' && $MODULE neq 'Vendors' && $MODULE neq 'PriceBooks'}
                                                     <input class="crmbuttom small edit" type="button" value="{$button_label}" onclick="return change(this,'changeowner')"/>
                                                {/if}
                                        {/if}

                                 {/foreach}
                                 </td>
				 <td style="padding-right:20px" class="small" nowrap>{$RECORD_COUNTS}</td>
				 <td nowrap >
				    <table border=0 cellspacing=0 cellpadding=0 class="small">
				         <tr>{$NAVIGATION}</tr>
				     </table>
				 </td>
				 <td align="right" width=100%>
				   <table border=0 cellspacing=0 cellpadding=0 class="small">
					<tr>
                                           {$WORDTEMPLATEOPTIONS}{$MERGEBUTTON}
					</tr>
				   </table>
				 </td>
			      </tr>
              		 </table>
		       </td>
		   </tr>
		   
	    </table>
  </form>	
{$SELECT_SCRIPT}
