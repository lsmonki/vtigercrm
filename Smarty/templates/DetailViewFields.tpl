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
		{if $keyid eq '1' || $keyid eq 2 || $keyid eq '11' || $keyid eq '7' || $keyid eq '9' || $keyid eq '55' || $keyid eq '71' || $keyid eq '72'} <!--TextBox-->
                                         		<td width=25% class="dvtCellInfo" align="left">&nbsp;
                                         		      {if $keyid eq '55'}<!--SalutationSymbol-->
                                         		            {$keysalut}
                                         		      {*elseif $keyid eq '71' || $keyid eq '72'}  <!--CurrencySymbol-->
                                         		            {$keycursymb*}
                                                        	{/if}
                                                       {$keyval}
                                                  </td>
                                             {elseif $keyid eq '13'} <!--Email-->
                                                  <td width=25% class="dvtCellInfo" align="left">&nbsp;<a href="mailto:{$keyval}" target="_blank">{$keyval}</a>
                                                  </td>
                                             {elseif $keyid eq '15' || $keyid eq '16' || $keyid eq '111'} <!--ComboBox-->
               							<td width=25% class="dvtCellInfo" align="left">&nbsp;{$keyval}
               							</td>
                                             {elseif $keyid eq '17'} <!--WebSite-->
                                                  <td width=25% class="dvtCellInfo" align="left">&nbsp;<a href="http://{$keyval}" target="_blank">{$keyval}</a>
                                                  </td>
                                             {elseif $keyid eq '19' || $keyid eq '20'} <!--TextArea/Description-->
                                                  <td width=100% class="dvtCellInfo" align="left">&nbsp;{$keyval}                   
                                                  </td>
                                             {elseif $keyid eq '21' || $keyid eq '24' || $keyid eq '22'} <!--TextArea/Street-->
                                                  <td width=25% class="dvtCellInfo" align="left">&nbsp;{$keyval}
                                                  </td>
                                             {elseif $keyid eq '50' || $keyid eq '73' || $keyid eq '51' || $keyid eq '57' || $keyid eq '59' || $keyid eq '75' || $keyid eq '81' || $keyid eq '76' || $keyid eq '78' || $keyid eq '80'} <!--AccountPopup-->
                                                  <td width=25% class="dvtCellInfo" align="left">&nbsp;<a href="{$keyseclink}">{$keyval}</a>
                                                  </td>
                                             {elseif $keyid eq 82} <!--Email Body-->
                                                  <td colspan="3" width=100% class="dvtCellInfo" align="left">&nbsp;{$keyval}
                                                  </td>
					{elseif $keyid eq '53'} <!--Assigned To-->
                    <td width=25% class="dvtCellInfo" align="left">&nbsp;
                    {if $keyseclink eq ''}
                        {$keyval}
                    {else}
                        <a href="{$keyseclink}">{$keyval}</a>         
                    {/if}
					&nbsp;
                    
                    </td>
		    {elseif $keyid eq '56'} <!--CheckBox--> 
                      <td width=25% class="dvtCellInfo" align="left">{$keyval}&nbsp;
                        </td>     
		{elseif $keyid eq 83}<!-- Handle the Tax in Inventory -->
							<td align="right" class="dvtCellLabel">
							{$APP.LBL_VAT} {$APP.COVERED_PERCENTAGE}
							
							</td>
							<td class="dvtCellInfo" align="left">&nbsp;
							{$VAT_TAX}
							</td>
							<td colspan="2" class="dvtCellInfo">&nbsp;</td>
						   </tr>
		   				   <tr>
							<td align="right" class="dvtCellLabel">
							{$APP.LBL_SALES} {$APP.LBL_TAX} {$APP.COVERED_PERCENTAGE}
							</td> 
							<td class="dvtCellInfo" align="left">&nbsp;
								{$SALES_TAX}
							</td>	
							<td colspan="2" class="dvtCellInfo">&nbsp;</td>
						   </tr>
				   		   <tr>
							<td align="right" class="dvtCellLabel">
								{$APP.LBL_SERVICE} {$APP.LBL_TAX} {$APP.COVERED_PERCENTAGE}
							</td>
							<td class="dvtCellInfo" align="left" >&nbsp;
								{$SERVICE_TAX}
							</td>
	

				{elseif $keyid eq 69}<!-- for Image Reflection -->
                                                  	<td align="left" width=25%">&nbsp;{$keyval}</td>
				{else}									
                                                  	<td class="dvtCellInfo" align="left" width=25%">&nbsp;{$keyval}</td>
				{/if}
