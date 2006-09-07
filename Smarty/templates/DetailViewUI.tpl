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

<!-- This file is used to display the fields based on the ui type in detailview -->
		{if $keyid eq '1' || $keyid eq 2 || $keyid eq '11' || $keyid eq '7' || $keyid eq '9' || $keyid eq '55' || $keyid eq '71' || $keyid eq '72' || $keyid eq '103'} <!--TextBox-->
                                         		<td width=25% class="dvtCellInfo" align="left" id="mouseArea_{$label}" onmouseover="hndMouseOver({$keyid},'{$label}');" onmouseout="fnhide('crmspanid');">
                                         		      {if $keyid eq '55'}<!--SalutationSymbol-->
                                         		            {$keysalut}
                                         		      {*elseif $keyid eq '71' || $keyid eq '72'}  <!--CurrencySymbol-->
                                         		            {$keycursymb*}
                                                        {/if}
                                                       &nbsp;&nbsp;<span id="dtlview_{$label}">{$keyval}</span>
                                              		<div id="editarea_{$label}" style="display:none;">
                                              		  <input class="detailedViewTextBox" onFocus="this.className='detailedViewTextBoxOn'" onBlur="this.className='detailedViewTextBox'" type="text" id="txtbox_{$label}" name="{$keyfldname}" maxlength='100' value="{$keyval}"></input>
                                              		  <br><input name="button_{$label}" type="button" class="crmbutton small save" value="{$APP.LBL_SAVE_LABEL}" onclick="dtlViewAjaxSave('{$label}','{$MODULE}',{$keyid},'{$keytblname}','{$keyfldname}','{$ID}');fnhide('crmspanid');"/> {$APP.LBL_OR}
                                              		  <a href="javascript:;" onclick="hndCancel('dtlview_{$label}','editarea_{$label}','{$label}')" class="link">{$APP.LBL_CANCEL_BUTTON_LABEL}</a>
                                                       </div>
                                                  </td>
                                             {elseif $keyid eq '13' || $keyid eq '104'} <!--Email-->
                                                  <td width=25% class="dvtCellInfo" align="left" id="mouseArea_{$label}" onmouseover="hndMouseOver({$keyid},'{$label}');" onmouseout="fnhide('crmspanid');">&nbsp;<span id="dtlview_{$label}"><a href="mailto:{$keyval}" target="_blank">{$keyval}</a></span>
                                              		<div id="editarea_{$label}" style="display:none;">
                                              		  <input class="detailedViewTextBox" onFocus="this.className='detailedViewTextBoxOn'" onBlur="this.className='detailedViewTextBox'" type="text" id="txtbox_{$label}" name="{$keyfldname}" maxlength='100' value="{$keyval}"></input>
                                              		  <br><input name="button_{$label}" type="button" class="crmbutton small save" value="{$APP.LBL_SAVE_LABEL}" onclick="dtlViewAjaxSave('{$label}','{$MODULE}',{$keyid},'{$keytblname}','{$keyfldname}','{$ID}');fnhide('crmspanid');"/> {$APP.LBL_OR}
                                              		  <a href="javascript:;" onclick="hndCancel('dtlview_{$label}','editarea_{$label}','{$label}')" class="link">{$APP.LBL_CANCEL_BUTTON_LABEL}</a>
                                                       </div>
                                                  </td>
						<!-- uitype 111 added for noneditable existing picklist values - ahmed -->
                                             {elseif $keyid eq '15' || $keyid eq '16' || $keyid eq '111'} <!--ComboBox-->
               							<td width=25% class="dvtCellInfo" align="left" id="mouseArea_{$label}" onmouseover="hndMouseOver({$keyid},'{$label}');" onmouseout="fnhide('crmspanid');">&nbsp;<span id="dtlview_{$label}">{$keyval}</span>
                                              		<div id="editarea_{$label}" style="display:none;">
                    							   <select id="txtbox_{$label}" name="{$keyfldname}">
                    								{foreach item=arr from=$keyoptions}
                    									{foreach key=sel_value item=value from=$arr}
                    										<option value="{$sel_value}" {$value}>{$sel_value}</option>
                    									{/foreach}
                    								{/foreach}
                    							   </select>
                    							   <br><input name="button_{$label}" type="button" class="crmbutton small save" value="{$APP.LBL_SAVE_LABEL}" onclick="dtlViewAjaxSave('{$label}','{$MODULE}',{$keyid},'{$keytblname}','{$keyfldname}','{$ID}');fnhide('crmspanid');"/> {$APP.LBL_OR}
                                              		   <a href="javascript:;" onclick="hndCancel('dtlview_{$label}','editarea_{$label}','{$label}')" class="link">{$APP.LBL_CANCEL_BUTTON_LABEL}</a>
                    							</div>
               							</td>
                                          {elseif $keyid eq '33'}<!--Multi Select Combo box-->
						<!--code given by Neil start Ref:http://forums.vtiger.com/viewtopic.php?p=31096#31096-->
						{assign var="MULTISELECT_COMBO_BOX_ITEM_SEPARATOR_STRING" value=", "}  {* Separates Multi-Select Combo Box items *}
						{assign var="DETAILVIEW_WORDWRAP_WIDTH" value="70"} {* No. of chars for word wrapping long lines of Multi-Select Combo Box items *}
                                          <td width=25% class="dvtCellInfo" align="left" id="mouseArea_{$label}" onmouseover="hndMouseOver({$keyid},'{$label}');" onmouseout="fnhide('crmspanid');">&nbsp;<span id="dtlview_{$label}">
						{$keyval|replace:$MULTISELECT_COMBO_BOX_ITEM_SEPARATOR_STRING:"\x1"|replace:" ":"\x0"|replace:"\x1":$MULTISELECT_COMBO_BOX_ITEM_SEPARATOR_STRING|wordwrap:$DETAILVIEW_WORDWRAP_WIDTH:"<br>&nbsp;"|replace:"\x0":"&nbsp;"}
						</span>
						<!--code given by Neil End-->
                                          <div id="editarea_{$label}" style="display:none;">
                                          <select MULTIPLE id="txtbox_{$label}" name="{$keyfldname}" size="4" style="width:160px;">
				                                    {foreach item=arr from=$keyoptions}
					                                     {foreach key=sel_value item=value from=$arr}
						                                      <option value="{$sel_value}" {$value}>{$sel_value}</option>
					                                     {/foreach}
				                                    {/foreach}
			                                   </select>
			                                   <br><input name="button_{$label}" type="button" class="crmbutton small save" value="{$APP.LBL_SAVE_LABEL}" onclick="dtlViewAjaxSave('{$label}','{$MODULE}',{$keyid},'{$keytblname}','{$keyfldname}','{$ID}');fnhide('crmspanid');"/> {$APP.LBL_OR}
                                              		   <a href="javascript:;" onclick="hndCancel('dtlview_{$label}','editarea_{$label}','{$label}')" class="link">{$APP.LBL_CANCEL_BUTTON_LABEL}</a>
                    							</div>
               							</td>
						{elseif $keyid eq '115'} <!--ComboBox Status edit only for admin Users-->
								{if $keyadmin eq 1}
               							<td width=25% class="dvtCellInfo" align="left" id="mouseArea_{$label}" onmouseover="hndMouseOver({$keyid},'{$label}');" onmouseout="fnhide('crmspanid');">&nbsp;<span id="dtlview_{$label}">{$keyval}</span>
                                              		<div id="editarea_{$label}" style="display:none;">
                    							   <select id="txtbox_{$label}" name="{$keyfldname}">
                    								{foreach item=arr from=$keyoptions}
                    									{foreach key=sel_value item=value from=$arr}
                    										<option value="{$sel_value}" {$value}>{$sel_value}</option>
                    									{/foreach}
                    								{/foreach}
                    							   </select>
                    							   <br><input name="button_{$label}" type="button" class="crmbutton small save" value="{$APP.LBL_SAVE_LABEL}" onclick="dtlViewAjaxSave('{$label}','{$MODULE}',{$keyid},'{$keytblname}','{$keyfldname}','{$ID}');fnhide('crmspanid');"/> {$APP.LBL_OR}
                                              		   <a href="javascript:;" onclick="hndCancel('dtlview_{$label}','editarea_{$label}','{$label}')" class="link">{$APP.LBL_CANCEL_BUTTON_LABEL}</a>
                    							</div>
								{else}
               							<td width=25% class="dvtCellInfo" align="left">{$keyval}
								{/if}	
								
               							</td>
						{elseif $keyid eq '116'} <!--ComboBox currency id edit only for admin Users-->
								{if $keyadmin eq 1}
               							<td width=25% class="dvtCellInfo" align="left" id="mouseArea_{$label}" onmouseover="hndMouseOver({$keyid},'{$label}');" onmouseout="fnhide('crmspanid');">&nbsp;<span id="dtlview_{$label}">{$keyval}</span>
								<div id="editarea_{$label}" style="display:none;">
                    							   <select id="txtbox_{$label}" name="{$keyfldname}">
									{foreach item=arr key=uivalueid from=$keyoptions}
									{foreach key=sel_value item=value from=$arr}
										<option value="{$uivalueid}" {$value}>{$sel_value}</option>	
									{/foreach}
									{/foreach}
                    							   </select>
                    							   <br><input name="button_{$label}" type="button" class="crmbutton small save" value="{$APP.LBL_SAVE_LABEL}" onclick="dtlViewAjaxSave('{$label}','{$MODULE}',{$keyid},'{$keytblname}','{$keyfldname}','{$ID}');fnhide('crmspanid');"/> {$APP.LBL_OR}
                                              		   <a href="javascript:;" onclick="hndCancel('dtlview_{$label}','editarea_{$label}','{$label}')" class="link">{$APP.LBL_CANCEL_BUTTON_LABEL}</a>
                    							</div>
								{else}
               							<td width=25% class="dvtCellInfo" align="left">{$keyval}
								{/if}	

                                        		
               							</td>
                                             {elseif $keyid eq '17'} <!--WebSite-->
                                                  <td width=25% class="dvtCellInfo" align="left" id="mouseArea_{$label}" onmouseover="hndMouseOver({$keyid},'{$label}');" onmouseout="fnhide('crmspanid');">&nbsp;<span id="dtlview_{$label}"><a href="http://{$keyval}" target="_blank">{$keyval}</a></span>
                                              		<div id="editarea_{$label}" style="display:none;">
                                              		  <input class="detailedViewTextBox" onFocus="this.className='detailedViewTextBoxOn'" onBlur="this.className='detailedViewTextBox'" type="text" id="txtbox_{$label}" name="{$keyfldname}" maxlength='100' value="{$keyval}"></input>
                                              		  <br><input name="button_{$label}" type="button" class="crmbutton small save" value="{$APP.LBL_SAVE_LABEL}" onclick="dtlViewAjaxSave('{$label}','{$MODULE}',{$keyid},'{$keytblname}','{$keyfldname}','{$ID}');fnhide('crmspanid');"/> {$APP.LBL_OR}
                                              		  <a href="javascript:;" onclick="hndCancel('dtlview_{$label}','editarea_{$label}','{$label}')" class="link">{$APP.LBL_CANCEL_BUTTON_LABEL}</a>
                                                       </div>
                                                  </td>
                                             {elseif $keyid eq '19' || $keyid eq '20'} <!--TextArea/Description-->
						<!-- we will empty the value of ticket and faq comment -->
						{if $label eq $MOD.LBL_ADD_COMMENT}
							{assign var=keyval value=''}
						{/if}
							{assign var="DESCRIPTION_SEPARATOR_STRING" value=" "}  {* Separates Description *}
							{assign var="DESCRIPTION_WORDWRAP_WIDTH" value="70"} {* No. of chars for word wrapping long lines of Description *}
                                                  <td width=25% class="dvtCellInfo" align="left" id="mouseArea_{$label}" onmouseover="hndMouseOver({$keyid},'{$label}');" onmouseout="fnhide('crmspanid');">&nbsp;<span id="dtlview_{$label}">
							{$keyval|replace:$DESCRIPTION_SEPARATOR_STRING:"\x1"|replace:" ":"\x0"|replace:"\x1":$DESCRIPTION_SEPARATOR_STRING|wordwrap:$DESCRIPTION_WORDWRAP_WIDTH:"<br>&nbsp;"|replace:"\x0":"&nbsp;"}
							</span>
                                              		<div id="editarea_{$label}" style="display:none;">
                                              		  <textarea id="txtbox_{$label}" name="{$keyfldname}"  class=detailedViewTextBox onFocus="this.className='detailedViewTextBoxOn'"onBlur="this.className='detailedViewTextBox'" cols="90" rows="8">{$keyval}</textarea>                                            		  
                                              		  <br><input name="button_{$label}" type="button" class="crmbutton small save" value="{$APP.LBL_SAVE_LABEL}" onclick="dtlViewAjaxSave('{$label}','{$MODULE}',{$keyid},'{$keytblname}','{$keyfldname}','{$ID}');fnhide('crmspanid');"/> {$APP.LBL_OR}
                                              		  <a href="javascript:;" onclick="hndCancel('dtlview_{$label}','editarea_{$label}','{$label}')" class="link">{$APP.LBL_CANCEL_BUTTON_LABEL}</a>
                                                       </div>
                                                  </td>
                                             {elseif $keyid eq '21' || $keyid eq '24' || $keyid eq '22'} <!--TextArea/Street-->
                                                  <td width=25% class="dvtCellInfo" align="left" id="mouseArea_{$label}" onmouseover="hndMouseOver({$keyid},'{$label}');" onmouseout="fnhide('crmspanid');">&nbsp;<span id="dtlview_{$label}">{$keyval}</span>
                                              		<div id="editarea_{$label}" style="display:none;">
                                              		  <textarea id="txtbox_{$label}" name="{$keyfldname}"  class=detailedViewTextBox onFocus="this.className='detailedViewTextBoxOn'"onBlur="this.className='detailedViewTextBox'" rows=2>{$keyval}</textarea>                                            		  
                                              		  <br><input name="button_{$label}" type="button" class="crmbutton small save" value="{$APP.LBL_SAVE_LABEL}" onclick="dtlViewAjaxSave('{$label}','{$MODULE}',{$keyid},'{$keytblname}','{$keyfldname}','{$ID}');fnhide('crmspanid');"/> {$APP.LBL_OR}
                                              		  <a href="javascript:;" onclick="hndCancel('dtlview_{$label}','editarea_{$label}','{$label}')" class="link">{$APP.LBL_CANCEL_BUTTON_LABEL}</a>
                                                       </div>
                                                  </td>
                                             {elseif $keyid eq '50' || $keyid eq '73' || $keyid eq '51'} <!--AccountPopup-->
                                                  <td width=25% class="dvtCellInfo" align="left" id="mouseArea_{$label}">&nbsp;<a href="{$keyseclink}">{$keyval}</a>
                                                  </td>
                                             {elseif $keyid eq '57'} <!--ContactPopup-->
                                                  <td width=25% class="dvtCellInfo" align="left" id="mouseArea_{$label}" onmouseover="hndMouseOver({$keyid},'{$label}');" onmouseout="fnhide('crmspanid');">&nbsp;<span id="dtlview_{$label}"><a href="{$keyseclink}">{$keyval}</a></span>
                                              		<div id="editarea_{$label}" style="display:none;">                                              		  
                                                         <input id="popuptxt_{$label}" name="contact_name" readonly type="text" style="border:1px solid #bababa;" value="{$keyval}"><input id="txtbox_{$label}" name="{$keyfldname}" type="hidden" value="{$keysecid}">&nbsp;<img src="{$IMAGE_PATH}select.gif" alt="Select" title="Select" LANGUAGE=javascript onclick='return window.open("index.php?module=Contacts&action=Popup&html=Popup_picker&popuptype=specific&form=EditView","test","width=600,height=602,resizable=1,scrollbars=1");' align="absmiddle" style='cursor:hand;cursor:pointer'>&nbsp;<input type="image" src="{$IMAGE_PATH}clear_field.gif" alt="Clear" title="Clear" LANGUAGE=javascript onClick="this.form.contact_id.value=''; this.form.contact_name.value='';return false;" align="absmiddle" style='cursor:hand;cursor:pointer'>
                                                         <br><input name="button_{$label}" type="button" class="crmbutton small save" value="{$APP.LBL_SAVE_LABEL}" onclick="dtlViewAjaxSave('{$label}','{$MODULE}',{$keyid},'{$keytblname}','{$keyfldname}','{$ID}');fnhide('crmspanid');"/> {$APP.LBL_OR}
                                              		  <a href="javascript:;" onclick="hndCancel('dtlview_{$label}','editarea_{$label}','{$label}')" class="link">{$APP.LBL_CANCEL_BUTTON_LABEL}</a>
                                                       </div>
                                                  </td>                                                  
                                             {elseif $keyid eq '59'} <!--ProductPopup-->
                                                  <td width=25% class="dvtCellInfo" align="left" id="mouseArea_{$label}" onmouseover="hndMouseOver({$keyid},'{$label}');" onmouseout="fnhide('crmspanid');">&nbsp;<span id="dtlview_{$label}"><a href="{$keyseclink}">{$keyval}</a></span>
                                              		<div id="editarea_{$label}" style="display:none;">                                              		  
                                                         <input id="popuptxt_{$label}" name="product_name" readonly type="text" value="{$keyval}"><input id="txtbox_{$label}" name="{$keyfldname}" type="hidden" value="{$keysecid}">&nbsp;<img src="{$IMAGE_PATH}select.gif" alt="Select" title="Select" LANGUAGE=javascript onclick='return window.open("index.php?module=Products&action=Popup&html=Popup_picker&form=HelpDeskEditView&popuptype=specific","test","width=600,height=602,resizable=1,scrollbars=1,top=150,left=200");' align="absmiddle" style='cursor:hand;cursor:pointer'>&nbsp;<input type="image" src="{$IMAGE_PATH}clear_field.gif" alt="Clear" title="Clear" LANGUAGE=javascript onClick="this.form.product_id.value=''; this.form.product_name.value=''; return false;" align="absmiddle" style='cursor:hand;cursor:pointer'>
                                                         <br><input name="button_{$label}" type="button" class="crmbutton small save" value="{$APP.LBL_SAVE_LABEL}" onclick="dtlViewAjaxSave('{$label}','{$MODULE}',{$keyid},'{$keytblname}','{$keyfldname}','{$ID}');fnhide('crmspanid');"/> {$APP.LBL_OR}
                                              		  <a href="javascript:;" onclick="hndCancel('dtlview_{$label}','editarea_{$label}','{$label}')" class="link">{$APP.LBL_CANCEL_BUTTON_LABEL}</a>
                                                       </div>
                                                  </td>
                                             {elseif $keyid eq '75' || $keyid eq '81'} <!--VendorPopup-->
                                                  <td width=25% class="dvtCellInfo" align="left" id="mouseArea_{$label}">&nbsp;<a href="{$keyseclink}">{$keyval}</a>
                                                  </td>
                                             {elseif $keyid eq 76} <!--PotentialPopup-->
                                                  <td width=25% class="dvtCellInfo" align="left" id="mouseArea_{$label}">&nbsp;<a href="{$keyseclink}">{$keyval}</a>
                                                  </td>
                                             {elseif $keyid eq 78} <!--QuotePopup-->
                                                  <td width=25% class="dvtCellInfo" align="left" id="mouseArea_{$label}">&nbsp;<a href="{$keyseclink}">{$keyval}</a>
                                                  </td>
                                             {elseif $keyid eq 82} <!--Email Body-->
                                                  <td colspan="3" width=100% class="dvtCellInfo" align="left"><div id="dtlview_{$label}" style="width:100%;height:200px;overflow:hidden;border:1px solid gray" class="detailedViewTextBox" onmouseover="this.className='detailedViewTextBoxOn'" onmouseout="this.className='detailedViewTextBox'">{$keyval}</div>
                                                  </td>
                                             {elseif $keyid eq 80} <!--SalesOrderPopup-->
                                                  <td width=25% class="dvtCellInfo" align="left" id="mouseArea_{$label}">&nbsp;<a href="{$keyseclink}">{$keyval}</a>
                                                  </td>
					     {elseif $keyid eq '53'} <!--Assigned To-->
                    				  <td width=25% class="dvtCellInfo" align="left" id="mouseArea_{$label}" onmouseover="hndMouseOver({$keyid},'{$label}');" onmouseout="fnhide('crmspanid');">&nbsp;<span id="dtlview_{$label}">
                    				{if $keyoptions.0 eq 'User' && $keyadmin eq 1}
			                        	<a href="{$keyseclink.0}">{$keyval}</a>         
			                        {elseif $keyoptions.0 eq 'Group' && $keyadmin eq 1}
                        				<a href="{$keyseclink.1}">{$keyval}</a>         
						{else}	
                        				{$keyval}
                    				{/if}
					&nbsp;</span>
                    				<div id="editarea_{$label}" style="display:none;">
			                   	<input type="hidden" id="hdtxt_{$label}" value="{$keyval}"></input>
						{if $keyoptions.0 eq 'User'}
							<input name="assigntype" id="assigntype" checked="checked" value="U" onclick="toggleAssignType(this.value),setSelectValue('{$label}');" type="radio">&nbsp;{$APP.LBL_USER}
							{if $keyoptions.2 neq ''}
								<input name="assigntype" id="assigntype" value="T" onclick="toggleAssignType(this.value),setSelectValue('{$label}');" type="radio">&nbsp;{$APP.LBL_GROUP_NAME}
							{/if}
							<span id="assign_user" style="display: block;">
						{else}
							<input name="assigntype" id="assigntype" value="U" onclick="toggleAssignType(this.value),setSelectValue('{$label}');" type="radio">&nbsp;{$APP.LBL_USER}
							<input name="assigntype" checked="checked" id="assigntype" value="T" onclick="toggleAssignType(this.value),setSelectValue('{$label}');" type="radio">&nbsp;{$APP.LBL_GROUP_NAME}
							<span id="assign_user" style="display: none;">
						{/if}
                   				<select id="txtbox_U{$label}" onchange="setSelectValue('{$label}')" name="{$keyfldname}">
				                    {foreach item=arr key=id from=$keyoptions.1}
				                    	{foreach key=sel_value item=value from=$arr}
                       						 <option value="{$id}" {$value}>{$sel_value}</option>
				                        {/foreach}
				                    {/foreach}
			                    	</select>
						</span>
					{if $keyoptions.0 eq 'Group'}
						<span id="assign_team" style="display: block;">
					{else}
						<span id="assign_team" style="display: none;">
					{/if}
                   	<select id="txtbox_G{$label}" onchange="setSelectValue('{$label}')" name="{$keyfldname}">
                    {foreach item=arr key=id from=$keyoptions.2}
                    	{foreach key=sel_value item=value from=$arr}
                       		 <option value="{$id}" {$value}>{$sel_value}</option>
                        {/foreach}
                    {/foreach}
                    </select>
					</span>

                    <br>
                    <input name="button_{$label}" type="button" class="crmbutton small save" value="{$APP.LBL_SAVE_LABEL}" onclick="dtlViewAjaxSave('{$label}','{$MODULE}',{$keyid},'{$keytblname}','{$keyfldname}','{$ID}');"/> {$APP.LBL_OR}
                    <a href="javascript:;" onclick="hndCancel('dtlview_{$label}','editarea_{$label}','{$label}')" class="link">{$APP.LBL_CANCEL_BUTTON_LABEL}</a>
                    </div>
                    </td>
						{elseif $keyid eq '99'}<!-- Password Field-->
						<td width=25% class="dvtCellInfo" align="left">{$CHANGE_PW_BUTTON}</td>	
					    {elseif $keyid eq '56'} <!--CheckBox--> 
                      <td width=25% class="dvtCellInfo" align="left" id="mouseArea_{$label}" onMouseOver="hndMouseOver({$keyid},'{$label}');" onmouseout="fnhide('crmspanid');">&nbsp;<span id="dtlview_{$label}">{$keyval}&nbsp;</span>
                    	<div id="editarea_{$label}" style="display:none;">
                        {if $keyval eq 'yes'}                                              		  
                            <input id="txtbox_{$label}" name="{$keyfldname}" type="checkbox" style="border:1px solid #bababa;" checked value="1">
                        {else}
                          <input id="txtbox_{$label}" type="checkbox" name="{$keyfldname}" style="border:1px solid #bababa;" value="0">
                       	{/if}
                         <br><input name="button_{$label}" type="button" class="crmbutton small save" value="{$APP.LBL_SAVE_LABEL}" onclick="dtlViewAjaxSave('{$label}','{$MODULE}',{$keyid},'{$keytblname}','{$keyfldname}','{$ID}');"/> {$APP.LBL_OR}
                          <a href="javascript:;" onclick="hndCancel('dtlview_{$label}','editarea_{$label}','{$label}')" class="link">{$APP.LBL_CANCEL_BUTTON_LABEL}</a>
                        </div>
                        </td>    
			{elseif $keyid eq '156'} <!--CheckBox for is admin-->
			{if $smarty.request.record neq $CURRENT_USERID && $keyadmin eq 1} 
                      <td width=25% class="dvtCellInfo" align="left" id="mouseArea_{$label}" onMouseOver="hndMouseOver({$keyid},'{$label}');" onmouseout="fnhide('crmspanid');">&nbsp;<span id="dtlview_{$label}">{$keyval}&nbsp;</span>
                    	<div id="editarea_{$label}" style="display:none;">
                        {if $keyval eq 'on'}                                              		  
                            <input id="txtbox_{$label}" name="{$keyfldname}" type="checkbox" style="border:1px solid #bababa;" checked value="1">
                        {else}
                          <input id="txtbox_{$label}" type="checkbox" name="{$keyfldname}" style="border:1px solid #bababa;" value="0">
                       	{/if}
                         <br><input name="button_{$label}" type="button" class="crmbutton small save" value="{$APP.LBL_SAVE_LABEL}" onclick="dtlViewAjaxSave('{$label}','{$MODULE}',{$keyid},'{$keytblname}','{$keyfldname}','{$ID}');"/> {$APP.LBL_OR}
                          <a href="javascript:;" onclick="hndCancel('dtlview_{$label}','editarea_{$label}','{$label}')" class="link">{$APP.LBL_CANCEL_BUTTON_LABEL}</a>
                        </div>
			{else}
				 <td width=25% class="dvtCellInfo" align="left">{$keyval}
			{/if}
                        </td>    
			 
						{elseif $keyid eq 83}<!-- Handle the Tax in Inventory -->
							{foreach item=tax key=count from=$TAX_DETAILS}
								<td align="right" class="dvtCellLabel">
									{$tax.taxlabel} {$APP.COVERED_PERCENTAGE}
							
								</td>
								<td class="dvtCellInfo" align="left">
									{$tax.percentage}
								</td>
								<td colspan="2" class="dvtCellInfo">&nbsp;</td>
							   </tr>
							{/foreach}


				{elseif $keyid eq 69}<!-- for Image Reflection -->
                                                  	<td align="left" width=25%">&nbsp;{$keyval}</td>
				{else}									
                                                  	<td class="dvtCellInfo" align="left" width=25%">&nbsp;{$keyval}</td>
				{/if}
