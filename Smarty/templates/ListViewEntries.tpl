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

     <input name='search_url' id="search_url" type='hidden' value='{$SEARCH_URL}'>
     <input name='allids' type='hidden' value='{$ALLIDS}'>
     <form name="massdelete" method="POST">
     <input name="idlist" type="hidden">
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
                                             <input class="small" type="button" value="{$button_label}" onclick="return massDelete('{$MODULE}')"/>
                                        {elseif $button_check eq 's_mail'}
                                             <input class="small" type="button" value="{$button_label}" onclick="return eMail('{$MODULE}',this)"/>
                                        {elseif $button_check eq 's_cmail'}
                                             <input class="small" type="button" value="{$button_label}" onclick="return massMail()"/>
                                        {elseif $button_check eq 'c_status'}
                                             <input class="small" type="button" value="{$button_label}" onclick="return change(this,'changestatus')"/>
					{elseif $button_check eq 'c_owner'}
                                                {if $MODULE neq 'Notes' && $MODULE neq 'Products' && $MODULE neq 'Faq' && $MODULE neq 'Vendors' && $MODULE neq 'PriceBooks'}
                                                     <input class="small" type="button" value="{$button_label}" onclick="return change(this,'changeowner')"/>
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
						    <SELECT NAME="viewname" class="small" onchange="showDefaultCustomView(this,'{$MODULE}')">{$CUSTOMVIEW_OPTION}</SELECT></td>
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
                         <div  style="width:100%;border-top:1px solid #999999;border-bottom:1px solid #999999">
			 <table border=0 cellspacing=1 cellpadding=3 width=100% style="background-color:#cccccc;" class="small">
			      <tr>
				{if $SHOW_MASS_SELECT neq 'false'}
	             			 <td class="lvtCol"><input type="checkbox"  name="selectall" onClick=toggleSelect(this.checked,"selected_id")></td>
				{/if}
				 {foreach item=header from=$LISTHEADER}
        			 <td class="lvtCol">{$header}</td>
			         {/foreach}
			      </tr>
			      {foreach item=entity key=entity_id from=$LISTENTITY}
			      <tr bgcolor=white onMouseOver="this.className='lvtColDataHover'" onMouseOut="this.className='lvtColData'"  >
				{if $SHOW_MASS_SELECT neq 'false'}
					 <td width="2%"><input type="checkbox" NAME="selected_id" value= '{$entity_id}' onClick=toggleSelectAll(this.name,"selectall")></td>
				{/if}
				 {foreach item=data from=$entity}	
				 <td>{$data}</td>
	                         {/foreach}
			      </tr>
			      {/foreach}
			 </table>
			 </div>
			 <table border=0 cellspacing=0 cellpadding=2 width=100%>
			      <tr>
				 <td style="padding-right:20px" nowrap>
                                 {foreach key=button_check item=button_label from=$BUTTONS}
                                        {if $button_check eq 'del'}
                                             <input class="small" type="button" value="{$button_label}" onclick="return massDelete('{$MODULE}')"/>
                                        {elseif $button_check eq 's_mail'}
                                             <input class="small" type="button" value="{$button_label}" onclick="return eMail('{$MODULE}',this)"/>
                                        {elseif $button_check eq 's_cmail'}
                                             <input class="small" type="submit" value="{$button_label}" onclick="return massMail()"/>
                                        {elseif $button_check eq 'c_status'}
                                             <input class="small" type="button" value="{$button_label}" onclick="return change(this,'changestatus')"/>
					{elseif $button_check eq 'c_owner'}
                                                {if $MODULE neq 'Notes' && $MODULE neq 'Products' && $MODULE neq 'Faq' && $MODULE neq 'Vendors' && $MODULE neq 'PriceBooks'}
                                                     <input class="small" type="button" value="{$button_label}" onclick="return change(this,'changeowner')"/>
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
