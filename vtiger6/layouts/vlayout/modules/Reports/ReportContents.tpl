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
{strip}
	<div id="reportDetails" class="marginLeft5px">
		{if !empty($CALCULATION_FIELDS)}
			<table class="table table-bordered" width="100%">
				<thead>
					<tr class="blockHeader">
						<th>{vtranslate('LBL_FIELD_NAMES',$MODULE)}</th>
						<th>{vtranslate('LBL_SUM',$MODULE)}</th>
						<th>{vtranslate('LBL_AVG',$MODULE)}</th>
						<th>{vtranslate('LBL_MIN',$MODULE)}</th>
						<th>{vtranslate('LBL_MAX',$MODULE)}</th>
					</tr>
				</thead>
				{foreach from=$CALCULATION_FIELDS item=CALCULATION_FIELD key=index}
					<tr>
						{assign var=CALCULATION_FIELD_KEYS value=array_keys($CALCULATION_FIELD)}
						{assign var=FIELD_IMPLODE value=explode('_',$CALCULATION_FIELD_KEYS['0'])}
						{assign var=FIELD_LABEL value=$FIELD_IMPLODE['1']|cat:' '|cat:$FIELD_IMPLODE['2']}
						<td>{vtranslate($FIELD_IMPLODE['0'],$MODULE)} {vtranslate($FIELD_LABEL,$MODULE   )}</td>
						{foreach from=$CALCULATION_FIELD item=CALCULATION_VALUE}
							<td width="15%">{$CALCULATION_VALUE}</td>
						{/foreach}
					</tr>
				{/foreach}
			</table>
		{/if}

		{if $DATA neq ''}
			{assign var=HEADERS value=$DATA[0]}
			<table class="table table-bordered">
				<thead>
					<tr class="blockHeader">
						{foreach from=$HEADERS item=HEADER key=NAME}
							<th>{vtranslate($NAME,$MODULE)}</th>
						{/foreach}
					</tr>
				</thead>
				{foreach from=$DATA item=VALUES}
					<tr>
						{foreach from=$VALUES item=VALUE key=NAME}
							<td>{$VALUE}</td>
						{/foreach}
					</tr>
				{/foreach}
			</table>
			{if $LIMIT_EXCEEDED}
				<center>{vtranslate('LBL_LIMIT_EXCEEDED',$MODULE)} <span class="pull-right"><a href="#top" >{vtranslate('LBL_TOP',$MODULE)}</a></span></center>
			{/if}
		{else}
			{vtranslate('LBL_NO_DATA_AVAILABLE',$MODULE)}
		{/if}
	</div>
{/strip}
