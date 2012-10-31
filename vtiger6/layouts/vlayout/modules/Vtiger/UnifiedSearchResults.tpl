{strip}
{assign var="totalCount" value=0}
{assign var="totalModulesSearched" value=count($MATCHING_RECORDS)}
{foreach key=module item=searchRecords from=$MATCHING_RECORDS}
    {assign var=modulesCount value=count($searchRecords)}
    {assign var="totalCount" value=$totalCount+$modulesCount}
{/foreach}
<div class="globalSearchResults">
	<div class="row-fluid">
		<div class="header highlightedHeader padding1per">
			<div class="row-fluid">
				<span class="span6"><strong>{vtranslate('LBL_SEARCH_RESULTS',$MODULE)}&nbsp;({$totalCount})</strong></span>
				{if $IS_ADVANCE_SEARCH }
				<span class="span5">
					<span class="pull-right">
						<a href="javascript:void(0);" id="showFilter">{vtranslate('LBL_SAVE_MODIFY_FILTER',$MODULE)}</a>
					</span>
				</span>
				{/if}
			</div>
		</div>
		<div class="contents">
		{foreach key=module item=searchRecords from=$MATCHING_RECORDS}
			{assign var="modulesCount" value=count($searchRecords)}
			<label>
				<strong>{vtranslate($module)}&nbsp;({$modulesCount})</strong>
			</label>
			<ul class="nav">
			{foreach item=recordObject from=$searchRecords}
				<li><a href="{$recordObject->getDetailViewUrl()}">{$recordObject->getName()}</a></li>
			{foreachelse}
				<li>No records</li>
			{/foreach}
			</ul>
		{/foreach}
		</div>
	</div>
</div>
{/strip}