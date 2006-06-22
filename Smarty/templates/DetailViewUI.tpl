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
                                         		      {elseif $keyid eq '71' || $keyid eq '72'}  <!--CurrencySymbol-->
                                         		            {$keycursymb}
                                                        {/if}
                                                       &nbsp;&nbsp;<span id="dtlview_{$label}">{$keyval}</span>
                                              		<div id="editarea_{$label}" style="display:none;">
                                              		  <input class="detailedViewTextBox" onFocus="this.className='detailedViewTextBoxOn'" onBlur="this.className='detailedViewTextBox'" type="text" id="txtbox_{$label}" name="{$keyfldname}" maxlength='100' value="{$keyval}"></input>
                                              		  <br><input name="button_{$label}" type="button" class="small" value="{$APP.LBL_SAVE_LABEL}" onclick="dtlViewAjaxSave('{$label}','{$MODULE}',{$keyid},'{$keytblname}','{$keyfldname}','{$ID}');fnhide('crmspanid');"/> or
                                              		  <a href="javascript:;" onclick="hndCancel('dtlview_{$label}','editarea_{$label}','{$label}')" class="link">{$APP.LBL_CANCEL_BUTTON_LABEL}</a>
                                                       </div>
                                                  </td>
                                             {elseif $keyid eq '13' || $keyid eq '104'} <!--Email-->
                                                  <td width=25% class="dvtCellInfo" align="left" id="mouseArea_{$label}" onmouseover="hndMouseOver({$keyid},'{$label}');" onmouseout="fnhide('crmspanid');">&nbsp;<span id="dtlview_{$label}"><a href="mailto:{$keyval}" target="_blank">{$keyval}</a></span>
                                              		<div id="editarea_{$label}" style="display:none;">
                                              		  <input class="detailedViewTextBox" onFocus="this.className='detailedViewTextBoxOn'" onBlur="this.className='detailedViewTextBox'" type="text" id="txtbox_{$label}" name="{$keyfldname}" maxlength='100' value="{$keyval}"></input>
                                              		  <br><input name="button_{$label}" type="button" class="small" value="{$APP.LBL_SAVE_LABEL}" onclick="dtlViewAjaxSave('{$label}','{$MODULE}',{$keyid},'{$keytblname}','{$keyfldname}','{$ID}');fnhide('crmspanid');"/> or
                                              		  <a href="javascript:;" onclick="hndCancel('dtlview_{$label}','editarea_{$label}','{$label}')" class="link">{$APP.LBL_CANCEL_BUTTON_LABEL}</a>
                                                       </div>
                                                  </td>
                                             {elseif $keyid eq '15' || $keyid eq '16'} <!--ComboBox-->
               							<td width=25% class="dvtCellInfo" align="left" id="mouseArea_{$label}" onmouseover="hndMouseOver({$keyid},'{$label}');" onmouseout="fnhide('crmspanid');">&nbsp;<span id="dtlview_{$label}">{$keyval}</span>
                                              		<div id="editarea_{$label}" style="display:none;">
                    							   <select id="txtbox_{$label}" name="{$keyfldname}">
                    								{foreach item=arr from=$keyoptions}
                    									{foreach key=sel_value item=value from=$arr}
                    										<option value="{$sel_value}" {$value}>{$sel_value}</option>
                    									{/foreach}
                    								{/foreach}
                    							   </select>
                    							   <br><input name="button_{$label}" type="button" class="small" value="{$APP.LBL_SAVE_LABEL}" onclick="dtlViewAjaxSave('{$label}','{$MODULE}',{$keyid},'{$keytblname}','{$keyfldname}','{$ID}');fnhide('crmspanid');"/> or
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
                    							   <br><input name="button_{$label}" type="button" class="small" value="{$APP.LBL_SAVE_LABEL}" onclick="dtlViewAjaxSave('{$label}','{$MODULE}',{$keyid},'{$keytblname}','{$keyfldname}','{$ID}');fnhide('crmspanid');"/> or
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
                    							   <br><input name="button_{$label}" type="button" class="small" value="{$APP.LBL_SAVE_LABEL}" onclick="dtlViewAjaxSave('{$label}','{$MODULE}',{$keyid},'{$keytblname}','{$keyfldname}','{$ID}');fnhide('crmspanid');"/> or
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
                                              		  <br><input name="button_{$label}" type="button" class="small" value="{$APP.LBL_SAVE_LABEL}" onclick="dtlViewAjaxSave('{$label}','{$MODULE}',{$keyid},'{$keytblname}','{$keyfldname}','{$ID}');fnhide('crmspanid');"/> or
                                              		  <a href="javascript:;" onclick="hndCancel('dtlview_{$label}','editarea_{$label}','{$label}')" class="link">{$APP.LBL_CANCEL_BUTTON_LABEL}</a>
                                                       </div>
                                                  </td>
                                             {elseif $keyid eq '19' || $keyid eq '20'} <!--TextArea/Description-->
                                                  <td width=25% class="dvtCellInfo" align="left" id="mouseArea_{$label}" onmouseover="hndMouseOver({$keyid},'{$label}');" onmouseout="fnhide('crmspanid');">&nbsp;<span id="dtlview_{$label}">{$keyval}</span>
                                              		<div id="editarea_{$label}" style="display:none;">
                                              		  <textarea id="txtbox_{$label}" name="{$keyfldname}"  class=detailedViewTextBox onFocus="this.className='detailedViewTextBoxOn'"onBlur="this.className='detailedViewTextBox'" cols="90" rows="8">{$keyval}</textarea>                                            		  
                                              		  <br><input name="button_{$label}" type="button" class="small" value="{$APP.LBL_SAVE_LABEL}" onclick="dtlViewAjaxSave('{$label}','{$MODULE}',{$keyid},'{$keytblname}','{$keyfldname}','{$ID}');fnhide('crmspanid');"/> or
                                              		  <a href="javascript:;" onclick="hndCancel('dtlview_{$label}','editarea_{$label}','{$label}')" class="link">{$APP.LBL_CANCEL_BUTTON_LABEL}</a>
                                                       </div>
                                                  </td>
                                             {elseif $keyid eq '21' || $keyid eq '24' || $keyid eq '22'} <!--TextArea/Street-->
                                                  <td width=25% class="dvtCellInfo" align="left" id="mouseArea_{$label}" onmouseover="hndMouseOver({$keyid},'{$label}');" onmouseout="fnhide('crmspanid');">&nbsp;<span id="dtlview_{$label}">{$keyval}</span>
                                              		<div id="editarea_{$label}" style="display:none;">
                                              		  <textarea id="txtbox_{$label}" name="{$keyfldname}"  class=detailedViewTextBox onFocus="this.className='detailedViewTextBoxOn'"onBlur="this.className='detailedViewTextBox'" rows=2>{$keyval}</textarea>                                            		  
                                              		  <br><input name="button_{$label}" type="button" class="small" value="{$APP.LBL_SAVE_LABEL}" onclick="dtlViewAjaxSave('{$label}','{$MODULE}',{$keyid},'{$keytblname}','{$keyfldname}','{$ID}');fnhide('crmspanid');"/> or
                                              		  <a href="javascript:;" onclick="hndCancel('dtlview_{$label}','editarea_{$label}','{$label}')" class="link">{$APP.LBL_CANCEL_BUTTON_LABEL}</a>
                                                       </div>
                                                  </td>
                                             {elseif $keyid eq '50' || $keyid eq '73' || $keyid eq '51'} <!--AccountPopup-->
                                                  <td width=25% class="dvtCellInfo" align="left" id="mouseArea_{$label}" onmouseover="hndMouseOver({$keyid},'{$label}');" onmouseout="fnhide('crmspanid');">&nbsp;<span id="dtlview_{$label}"><a href="{$keyseclink}">{$keyval}</a></span>
                                              		<div id="editarea_{$label}" style="display:none;">                                              		  
                                                         <input readonly id="popuptxt_{$label}" name="account_name" type="text" value="{$keyval}"><input id="txtbox_{$label}" name="{$keyfldname}" type="hidden" value="{$keysecid}">&nbsp;<img src="{$IMAGE_PATH}select.gif" alt="Select" title="Select" LANGUAGE=javascript onclick='return window.open("index.php?module=Accounts&action=Popup&popuptype=specific&form=TasksEditView&form_submit=false","test","width=600,height=400,resizable=1,scrollbars=1");' align="absmiddle" style='cursor:hand;cursor:pointer'>
                                                         <!--AccountPopup/WithClear-->{if $keyid eq '51'} &nbsp;<input type="image" src="{$IMAGE_PATH}clear_field.gif" alt="Clear" title="Clear" LANGUAGE=javascript onClick="this.form.account_id.value=''; this.form.account_name.value='';return false;" align="absmiddle" style='cursor:hand;cursor:pointer'>{/if} 
                                              		  <br><input name="button_{$label}" type="button" class="small" value="{$APP.LBL_SAVE_LABEL}" onclick="dtlViewAjaxSave('{$label}','{$MODULE}',{$keyid},'{$keytblname}','{$keyfldname}','{$ID}');fnhide('crmspanid');"/> or
                                              		  <a href="javascript:;" onclick="hndCancel('dtlview_{$label}','editarea_{$label}','{$label}')" class="link">{$APP.LBL_CANCEL_BUTTON_LABEL}</a>
                                                       </div>
                                                  </td>
                                             {elseif $keyid eq '57'} <!--ContactPopup-->
                                                  <td width=25% class="dvtCellInfo" align="left" id="mouseArea_{$label}" onmouseover="hndMouseOver({$keyid},'{$label}');" onmouseout="fnhide('crmspanid');">&nbsp;<span id="dtlview_{$label}"><a href="{$keyseclink}">{$keyval}</a></span>
                                              		<div id="editarea_{$label}" style="display:none;">                                              		  
                                                         <input id="popuptxt_{$label}" name="contact_name" readonly type="text" style="border:1px solid #bababa;" value="{$keyval}"><input id="txtbox_{$label}" name="{$keyfldname}" type="hidden" value="{$keysecid}">&nbsp;<img src="{$IMAGE_PATH}select.gif" alt="Select" title="Select" LANGUAGE=javascript onclick='return window.open("index.php?module=Contacts&action=Popup&html=Popup_picker&popuptype=specific&form=EditView","test","width=600,height=400,resizable=1,scrollbars=1");' align="absmiddle" style='cursor:hand;cursor:pointer'>&nbsp;<input type="image" src="{$IMAGE_PATH}clear_field.gif" alt="Clear" title="Clear" LANGUAGE=javascript onClick="this.form.contact_id.value=''; this.form.contact_name.value='';return false;" align="absmiddle" style='cursor:hand;cursor:pointer'>
                                                         <br><input name="button_{$label}" type="button" class="small" value="{$APP.LBL_SAVE_LABEL}" onclick="dtlViewAjaxSave('{$label}','{$MODULE}',{$keyid},'{$keytblname}','{$keyfldname}','{$ID}');fnhide('crmspanid');"/> or
                                              		  <a href="javascript:;" onclick="hndCancel('dtlview_{$label}','editarea_{$label}','{$label}')" class="link">{$APP.LBL_CANCEL_BUTTON_LABEL}</a>
                                                       </div>
                                                  </td>                                                  
                                             {elseif $keyid eq '59'} <!--ProductPopup-->
                                                  <td width=25% class="dvtCellInfo" align="left" id="mouseArea_{$label}" onmouseover="hndMouseOver({$keyid},'{$label}');" onmouseout="fnhide('crmspanid');">&nbsp;<span id="dtlview_{$label}"><a href="{$keyseclink}">{$keyval}</a></span>
                                              		<div id="editarea_{$label}" style="display:none;">                                              		  
                                                         <input id="popuptxt_{$label}" name="product_name" readonly type="text" value="{$keyval}"><input id="txtbox_{$label}" name="{$keyfldname}" type="hidden" value="{$keysecid}">&nbsp;<img src="{$IMAGE_PATH}select.gif" alt="Select" title="Select" LANGUAGE=javascript onclick='return window.open("index.php?module=Products&action=Popup&html=Popup_picker&form=HelpDeskEditView&popuptype=specific","test","width=600,height=400,resizable=1,scrollbars=1,top=150,left=200");' align="absmiddle" style='cursor:hand;cursor:pointer'>&nbsp;<input type="image" src="{$IMAGE_PATH}clear_field.gif" alt="Clear" title="Clear" LANGUAGE=javascript onClick="this.form.product_id.value=''; this.form.product_name.value=''; return false;" align="absmiddle" style='cursor:hand;cursor:pointer'>
                                                         <br><input name="button_{$label}" type="button" class="small" value="{$APP.LBL_SAVE_LABEL}" onclick="dtlViewAjaxSave('{$label}','{$MODULE}',{$keyid},'{$keytblname}','{$keyfldname}','{$ID}');fnhide('crmspanid');"/> or
                                              		  <a href="javascript:;" onclick="hndCancel('dtlview_{$label}','editarea_{$label}','{$label}')" class="link">{$APP.LBL_CANCEL_BUTTON_LABEL}</a>
                                                       </div>
                                                  </td>
                                             {elseif $keyid eq '75' || $keyid eq '81'} <!--VendorPopup-->
                                                  <td width=25% class="dvtCellInfo" align="left" id="mouseArea_{$label}" onmouseover="hndMouseOver({$keyid},'{$label}');" onmouseout="fnhide('crmspanid');">&nbsp;<span id="dtlview_{$label}"><a href="{$keyseclink}">{$keyval}</a></span>
                                              		<div id="editarea_{$label}" style="display:none;">
                                                         <input id="popuptxt_{$label}" name="vendor_name" readonly type="text" style="border:1px solid #bababa;" value="{$keyval}"><input id="txtbox_{$label}" name="{$fldname}" type="hidden" value="{$keysecid}">&nbsp;<img src="{$IMAGE_PATH}select.gif" alt="Select" title="Select" LANGUAGE=javascript onclick='return window.open("index.php?module=Vendors&action=Popup&html=Popup_picker&popuptype=specific&form=EditView","test","width=600,height=400,resizable=1,scrollbars=1");' align="absmiddle" style='cursor:hand;cursor:pointer'>
                                                         {if $uitype eq 75}&nbsp;<input type="image" src="{$IMAGE_PATH}clear_field.gif" alt="Clear" title="Clear" LANGUAGE=javascript onClick="this.form.vendor_id.value='';this.form.vendor_name.value='';return false;" align="absmiddle" style='cursor:hand;cursor:pointer'>{/if}                                              		  
                                                         <br><input name="button_{$label}" type="button" class="small" value="{$APP.LBL_SAVE_LABEL}" onclick="dtlViewAjaxSave('{$label}','{$MODULE}',{$keyid},'{$keytblname}','{$keyfldname}','{$ID}');fnhide('crmspanid');"/> or
                                              		  <a href="javascript:;" onclick="hndCancel('dtlview_{$label}','editarea_{$label}','{$label}')" class="link">{$APP.LBL_CANCEL_BUTTON_LABEL}</a>
                                                       </div>
                                                  </td>
                                             {elseif $keyid eq 76} <!--PotentialPopup-->
                                                  <td width=25% class="dvtCellInfo" align="left" id="mouseArea_{$label}" onmouseover="hndMouseOver({$keyid},'{$label}');" onmouseout="fnhide('crmspanid');">&nbsp;<span id="dtlview_{$label}"><a href="{$keyseclink}">{$keyval}</a></span>
                                              		<div id="editarea_{$label}" style="display:none;">                                              		  
                                                         <input id="popuptxt_{$label}" name="potential_name" readonly type="text" style="border:1px solid #bababa;" value="{$keyval}"><input id="txtbox_{$label}" name="{$keyfldname}" type="hidden" value="{$keysecid}">&nbsp;<img src="{$IMAGE_PATH}select.gif" alt="Select" title="Select" LANGUAGE=javascript onclick='return window.open("index.php?module=Potentials&action=Popup&html=Popup_picker&popuptype=specific_potential_account_address&form=EditView","test","width=600,height=400,resizable=1,scrollbars=1");' align="absmiddle" style='cursor:hand;cursor:pointer'>&nbsp;<input type="image" src="{$IMAGE_PATH}clear_field.gif" alt="Clear" title="Clear" LANGUAGE=javascript onClick="this.form.potential_id.value=''; this.form.potential_name.value='';return false;" align="absmiddle" style='cursor:hand;cursor:pointer'>
                                                         <br><input name="button_{$label}" type="button" class="small" value="{$APP.LBL_SAVE_LABEL}" onclick="dtlViewAjaxSave('{$label}','{$MODULE}',{$keyid},'{$keytblname}','{$keyfldname}','{$ID}');fnhide('crmspanid');"/> or
                                              		  <a href="javascript:;" onclick="hndCancel('dtlview_{$label}','editarea_{$label}','{$label}')" class="link">{$APP.LBL_CANCEL_BUTTON_LABEL}</a>
                                                       </div>
                                                  </td>
                                             {elseif $keyid eq 78} <!--QuotePopup-->
                                                  <td width=25% class="dvtCellInfo" align="left" id="mouseArea_{$label}" onmouseover="hndMouseOver({$keyid},'{$label}');" onmouseout="fnhide('crmspanid');">&nbsp;<span id="dtlview_{$label}"><a href="{$keyseclink}">{$keyval}</a></span>
                                              		<div id="editarea_{$label}" style="display:none;">                                              		  
                                                         <input id="popuptxt_{$label}" name="quote_name" readonly type="text" style="border:1px solid #bababa;" value="{$keyval}"><input id="txtbox_{$label}" name="{$keyfldname}" type="hidden" value="{$keysecid}">&nbsp;<img src="{$IMAGE_PATH}select.gif" alt="Select" title="Select" LANGUAGE=javascript onclick='return window.open("index.php?module=Quotes&action=Popup&html=Popup_picker&popuptype=specific&form=EditView","test","width=600,height=400,resizable=1,scrollbars=1");' align="absmiddle" style='cursor:hand;cursor:pointer'>&nbsp;<input type="image" src="{$IMAGE_PATH}clear_field.gif" alt="Clear" title="Clear" LANGUAGE=javascript onClick="this.form.quote_id.value=''; this.form.quote_name.value='';return false;" align="absmiddle" style='cursor:hand;cursor:pointer'>
                                                         <br><input name="button_{$label}" type="button" class="small" value="{$APP.LBL_SAVE_LABEL}" onclick="dtlViewAjaxSave('{$label}','{$MODULE}',{$keyid},'{$keytblname}','{$keyfldname}','{$ID}');fnhide('crmspanid');"/> or
                                              		  <a href="javascript:;" onclick="hndCancel('dtlview_{$label}','editarea_{$label}','{$label}')" class="link">{$APP.LBL_CANCEL_BUTTON_LABEL}</a>
                                                       </div>
                                                  </td>
                                             {elseif $keyid eq 82} <!--Email Body-->
                                                  <td colspan="3" width=100% class="dvtCellInfo" align="left"><div id="dtlview_{$label}" style="width:100%;height:200px;overflow:hidden;border:1px solid gray" class="detailedViewTextBox" onmouseover="this.className='detailedViewTextBoxOn'" onmouseout="this.className='detailedViewTextBox'">{$keyval}</div>
                                                  </td>
                                             {elseif $keyid eq 80} <!--SalesOrderPopup-->
                                                  <td width=25% class="dvtCellInfo" align="left" id="mouseArea_{$label}" onmouseover="hndMouseOver({$keyid},'{$label}');" onmouseout="fnhide('crmspanid');">&nbsp;<span id="dtlview_{$label}"><a href="{$keyseclink}">{$keyval}</a></span>
                                              		<div id="editarea_{$label}" style="display:none;">                                              		  
                                                         <input id="popuptxt_{$label}" name="salesorder_name" readonly type="text" style="border:1px solid #bababa;" value="{$keyval}"><input id="txtbox_{$label}" name="{$keyfldname}" type="hidden" value="{$keysecid}">&nbsp;<img src="{$IMAGE_PATH}select.gif" alt="Select" title="Select" LANGUAGE=javascript onclick='return window.open("index.php?module=SalesOrder&action=Popup&html=Popup_picker&popuptype=specific&form=EditView","test","width=600,height=400,resizable=1,scrollbars=1");' align="absmiddle" style='cursor:hand;cursor:pointer'>&nbsp;<input type="image" src="{$IMAGE_PATH}clear_field.gif" alt="Clear" title="Clear" LANGUAGE=javascript onClick="this.form.salesorder_id.value=''; this.form.salesorder_name.value='';return false;" align="absmiddle" style='cursor:hand;cursor:pointer'>
                                                         <br><input name="button_{$label}" type="button" class="small" value="{$APP.LBL_SAVE_LABEL}" onclick="dtlViewAjaxSave('{$label}','{$MODULE}',{$keyid},'{$keytblname}','{$keyfldname}','{$ID}');fnhide('crmspanid');"/> or
                                              		  <a href="javascript:;" onclick="hndCancel('dtlview_{$label}','editarea_{$label}','{$label}')" class="link">{$APP.LBL_CANCEL_BUTTON_LABEL}</a>
                                                       </div>
                                                  </td>
					{elseif $keyid eq '53'} <!--Assigned To-->
                    <td width=25% class="dvtCellInfo" align="left" id="mouseArea_{$label}" onmouseover="hndMouseOver({$keyid},'{$label}');" onmouseout="fnhide('crmspanid');">
				
                    &nbsp;<span id="dtlview_{$label}">
                    {if $keyseclink eq ''}
                        {$keyval}
                    {else}
                        <a href="{$keyseclink}">{$keyval}</a>         
                    {/if}
					&nbsp;
                    </span>
                    <div id="editarea_{$label}" style="display:none;">
                   	<input type="hidden" id="hdtxt_{$label}" value="{$keyval}"></input>
                   	<select id="txtbox_{$label}" onchange="setSelectValue('{$label}')" name="{$keyfldname}">
                    {foreach item=arr key=id from=$keyoptions}
                    	{foreach key=sel_value item=value from=$arr}
                       		 <option value="{$id}" {$value}>{$sel_value}</option>
                        {/foreach}
                    {/foreach}
                    </select>
                    <br>
                    <input name="button_{$label}" type="button" class="small" value="{$APP.LBL_SAVE_LABEL}" onclick="dtlViewAjaxSave('{$label}','{$MODULE}',{$keyid},'{$keytblname}','{$keyfldname}','{$ID}');"/> or
                    <a href="javascript:;" onclick="hndCancel('dtlview_{$label}','editarea_{$label}','{$label}')" class="link">{$APP.LBL_CANCEL_BUTTON_LABEL}</a>
                    </div>
                    </td>
					    {elseif $keyid eq '56'} <!--CheckBox--> 
                      <td width=25% class="dvtCellInfo" align="left" id="mouseArea_{$label}" onMouseOver="hndMouseOver({$keyid},'{$label}');" onmouseout="fnhide('crmspanid');">&nbsp;<span id="dtlview_{$label}">{$keyval}&nbsp;</span>
                    	<div id="editarea_{$label}" style="display:none;">
                        {if $keyval eq 'yes'}                                              		  
                            <input id="txtbox_{$label}" name="{$keyfldname}" type="checkbox" style="border:1px solid #bababa;" checked value="1">
                        {else}
                          <input id="txtbox_{$label}" type="checkbox" name="{$keyfldname}" style="border:1px solid #bababa;" value="0">
                       	{/if}
                         <br><input name="button_{$label}" type="button" class="small" value="{$APP.LBL_SAVE_LABEL}" onclick="dtlViewAjaxSave('{$label}','{$MODULE}',{$keyid},'{$keytblname}','{$keyfldname}','{$ID}');"/> or
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
                         <br><input name="button_{$label}" type="button" class="small" value="{$APP.LBL_SAVE_LABEL}" onclick="dtlViewAjaxSave('{$label}','{$MODULE}',{$keyid},'{$keytblname}','{$keyfldname}','{$ID}');"/> or
                          <a href="javascript:;" onclick="hndCancel('dtlview_{$label}','editarea_{$label}','{$label}')" class="link">{$APP.LBL_CANCEL_BUTTON_LABEL}</a>
                        </div>
			{else}
				 <td width=25% class="dvtCellInfo" align="left">{$keyval}
			{/if}
                        </td>    
			 
						{elseif $keyid eq 83}<!-- Handle the Tax in Inventory -->
							<td align="right" class="dvtCellLabel">
							{$APP.LBL_VAT} {$APP.COVERED_PERCENTAGE}
							
							</td>
							<td class="dvtCellInfo" align="left">
							{$VAT_TAX}
							</td>
							<td colspan="2" class="dvtCellInfo">&nbsp;</td>
						   </tr>
		   				   <tr>
							<td align="right" class="dvtCellLabel">
							{$APP.LBL_SALES} {$APP.LBL_TAX} {$APP.COVERED_PERCENTAGE}
							</td> 
							<td class="dvtCellInfo" align="left">
								{$SALES_TAX}
							</td>	
							<td colspan="2" class="dvtCellInfo">&nbsp;</td>
						   </tr>
				   		   <tr>
							<td align="right" class="dvtCellLabel">
								{$APP.LBL_SERVICE} {$APP.LBL_TAX} {$APP.COVERED_PERCENTAGE}
							</td>
							<td class="dvtCellInfo" align="left" >
								{$SERVICE_TAX}
							</td>
	

				{elseif $keyid eq 69}<!-- for Image Reflection -->
                                                  	<td align="left" width=25%">{$keyval}</td>
				{else}									
                                                  	<td class="dvtCellInfo" align="left" width=25%">{$keyval}</td>
				{/if}
