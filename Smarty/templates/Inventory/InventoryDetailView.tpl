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
<script language="JavaScript" type="text/javascript" src="include/js/dtlviewajax.js"></script>
<script src="include/scriptaculous/scriptaculous.js" type="text/javascript"></script>
<div id="convertleaddiv" style="display:block;position:absolute;left:225px;top:150px;"></div>
<span id="crmspanid" style="display:none;position:absolute;"  onmouseover="show('crmspanid');">
   <a class="link"  align="right" href="javascript:;">{$APP.LBL_EDIT_BUTTON}</a>
</span>
<script>
function tagvalidate()
{ldelim}
	if(trim(document.getElementById('txtbox_tagfields').value) != '')
		SaveTag('txtbox_tagfields','{$ID}','{$MODULE}');	
	else
	{ldelim}
		alert("{$APP.PLEASE_ENTER_TAG}");
		return false;
	{rdelim}
{rdelim}
function DeleteTag(id,recordid)
{ldelim}
	$("vtbusy_info").style.display="inline";
	Effect.Fade('tag_'+id);
	new Ajax.Request(
		'index.php',
                {ldelim}queue: {ldelim}position: 'end', scope: 'command'{rdelim},
                        method: 'post',
                        postBody: "file=TagCloud&module={$MODULE}&action={$MODULE}Ajax&ajxaction=DELETETAG&recordid="+recordid+"&tagid=" +id,
                        onComplete: function(response) {ldelim}
						getTagCloud();
						$("vtbusy_info").style.display="none";
                        {rdelim}
                {rdelim}
        );
{rdelim}
{literal}
function showHideStatus(sId,anchorImgId,sImagePath)
{
	oObj = eval(document.getElementById(sId));
	if(oObj.style.display == 'block')
	{
		oObj.style.display = 'none';
		eval(document.getElementById(anchorImgId)).src = sImagePath + 'inactivate.gif';
		eval(document.getElementById(anchorImgId)).alt = 'Display';
		eval(document.getElementById(anchorImgId)).title = 'Display';
	}
	else
	{
		oObj.style.display = 'block';
		eval(document.getElementById(anchorImgId)).src = sImagePath + 'activate.gif';
		eval(document.getElementById(anchorImgId)).alt = 'Hide';
		eval(document.getElementById(anchorImgId)).title = 'Hide';
	}
}
function setCoOrdinate()
{
	oBtnObj = eval(document.getElementById('jumpBtnId'));
	var tagName = document.getElementById('lstRecordLayout');
	leftpos  = 0;
	toppos = 0;
	aTag = oBtnObj;
	do 
	{					  
	  leftpos  += aTag.offsetLeft;
	  toppos += aTag.offsetTop;
	} while(aTag = aTag.offsetParent);
	
	tagName.style.top= toppos + 20 + 'px';
	tagName.style.left= leftpos - 276 + 'px';
}

function getListOfRecords(obj, sModule, iId,sParentTab)
{
		new Ajax.Request(
		'index.php',
		{queue: {position: 'end', scope: 'command'},
			method: 'post',
			postBody: 'module=Users&action=getListOfRecords&ajax=true&CurModule='+sModule+'&CurRecordId='+iId+'&CurParentTab='+sParentTab,
			onComplete: function(response) {
				sResponse = response.responseText;
				$("lstRecordLayout").innerHTML = sResponse;
				Lay = 'lstRecordLayout';	
				var tagName = document.getElementById(Lay);
				var leftSide = findPosX(obj);
				var topSide = findPosY(obj);
				var maxW = tagName.style.width;
				var widthM = maxW.substring(0,maxW.length-2);
				var getVal = eval(leftSide) + eval(widthM);
				if(getVal  > document.body.clientWidth ){
					leftSide = eval(leftSide) - eval(widthM);
					tagName.style.left = leftSide + 230 + 'px';
				}
				else
					tagName.style.left= leftSide + 388 + 'px';
				
				setCoOrdinate();
				
				tagName.style.display = 'block';
				tagName.style.visibility = "visible";
			}
		}
	);
}

window.onresize = setCoOrdinate;
{/literal}

</script>

<div id="lstRecordLayout" class="layerPopup" style="display:none;width:325px;height:300px;"></div> <!-- Code added by SAKTI on 16th Jun, 2008 -->

<table width="100%" cellpadding="2" cellspacing="0" border="0">
   <tr>
	<td>
		{include file='Buttons_List1.tpl'}

		<!-- Contents -->
		<table border=0 cellspacing=0 cellpadding=0 width=98% align=center>
		   <tr>
			<td valign=top><img src="{$IMAGE_PATH}showPanelTopLeft.gif"></td>
			<td class="showPanelBg" valign=top width=100%>
			<!-- PUBLIC CONTENTS STARTS-->
			   <div class="small" style="padding:20px" >
		
				<table align="center" border="0" cellpadding="0" cellspacing="0" width="95%">
				   <tr>
					<td>
						<span class="lvtHeaderText"><font color="purple">[ {$ID} ] </font>{$NAME} -  {$MOD[$SINGLE_MOD]} {$APP.LBL_INFORMATION}</span>&nbsp;&nbsp;<span id="vtbusy_info" style="display:none;" valign="bottom"><img src="{$IMAGE_PATH}vtbusy.gif" border="0"></span><span id="vtbusy_info" style="visibility:hidden;" valign="bottom"><img src="{$IMAGE_PATH}vtbusy.gif" border="0"></span>
					</td>
					<td>&nbsp;</td>
				   </tr>
				   <tr height=20>
					<td>{$UPDATEINFO}</td>
				   </tr>
				</table>

				<hr noshade size=1>
		
				<!-- Entity and More information tabs -->
				<table border=0 cellspacing=0 cellpadding=0 width=95% align=center>
				   <tr>
					<td>
						<table border=0 cellspacing=0 cellpadding=3 width=100% class="small">
						   <tr>
							<td class="dvtTabCache" style="width:10px" nowrap>&nbsp;</td>
							<td class="dvtSelectedCell" align=center nowrap>{$MOD[$SINGLE_MOD]} {$APP.LBL_INFORMATION}</td>	
							<td class="dvtTabCache" style="width:10px">&nbsp;</td>
							{if $SinglePane_View eq 'false'}
								<td class="dvtUnSelectedCell" align=center nowrap><a href="index.php?action=CallRelatedList&module={$MODULE}&record={$ID}&parenttab={$CATEGORY}">{$APP.LBL_MORE} {$APP.LBL_INFORMATION}</a></td>
							{/if}
							<td class="dvtTabCache" style="width:100%">&nbsp;</td>
						   </tr>
						</table>
					</td>
				   </tr>
				   <tr>
					<td valign=top align=left >
						<table border=0 cellspacing=0 cellpadding=3 width=100% class="dvtContentSpace">
						   <tr>

							<td align=left style="padding:10px;">
							<!-- content cache -->
								<!-- Entity informations display - starts -->	
								<table border=0 cellspacing=0 cellpadding=0 width=100%>
			                			   <tr>
									<td style="padding:10px;border-right:1px dashed #CCCCCC;" width="80%">



<!-- The following table is used to display the buttons -->
   <form action="index.php" method="post" name="DetailView" id="form">
					{include file='DetailViewHidden.tpl'}
<table border=0 cellspacing=0 cellpadding=0 width=100%>
   {strip}
   <tr>
	<td  colspan=4 style="padding:5px">
		<table border=0 cellspacing=0 cellpadding=0 width=100%>
                   <tr>
            <td width=35%>
				{if $EDIT_DUPLICATE eq 'permitted'}
				<input title="{$APP.LBL_EDIT_BUTTON_TITLE}" accessKey="{$APP.LBL_EDIT_BUTTON_KEY}" class="crmbutton small edit" onclick="this.form.return_module.value='{$MODULE}'; this.form.return_action.value='DetailView'; this.form.return_id.value='{$ID}';this.form.module.value='{$MODULE}';this.form.action.value='EditView'" type="submit" name="Edit" value="{$APP.LBL_EDIT_BUTTON_LABEL}">&nbsp;
				{/if}
			</td>
			<td width=30% align=center>
					{if $privrecord neq ''}
						<img title="{$APP.LNK_LIST_PREVIOUS}" accessKey="{$APP.LNK_LIST_PREVIOUS}" onclick="location.href='index.php?module={$MODULE}&viewtype={$VIEWTYPE}&action=DetailView&record={$privrecord}&parenttab={$CATEGORY}'" name="privrecord" value="{$APP.LNK_LIST_PREVIOUS}" src="{$IMAGE_PATH}b_left.gif">&nbsp;
					{else}
						<img title="{$APP.LNK_LIST_PREVIOUS}" src="{$IMAGE_PATH}b_left_disable.gif">
					{/if}
					&nbsp;
					{if $nextrecord neq ''}
						<img title="{$APP.LNK_LIST_NEXT}" accessKey="{$APP.LNK_LIST_NEXT}" onclick="location.href='index.php?module={$MODULE}&viewtype={$VIEWTYPE}&action=DetailView&record={$nextrecord}&parenttab={$CATEGORY}'" name="nextrecord" src="{$IMAGE_PATH}b_right.gif">&nbsp;
					{else}
						<img title="{$APP.LNK_LIST_NEXT}" src="{$IMAGE_PATH}b_right_disable.gif">&nbsp;
					{/if}
			</td>
			<td width=35% align=right>
					{if $privrecord neq '' || $nextrecord neq ''}
						<input title="{$APP.LBL_JUMP_BTN}" accessKey="{$APP.LBL_JUMP_BTN}" class="crmbutton small create" onclick="location.href='javascript:getListOfRecords(this, \'{$MODULE}\',{$ID},\'{$CATEGORY}\');'" type="button" name="jumpBtnId" id="jumpBtnId" value="{$APP.LBL_JUMP_BTN}">
					{/if}
				{if $EDIT_DUPLICATE eq 'permitted'}
				<input title="{$APP.LBL_DUPLICATE_BUTTON_TITLE}" accessKey="{$APP.LBL_DUPLICATE_BUTTON_KEY}" class="crmbutton small create" onclick="this.form.return_module.value='{$MODULE}'; this.form.return_action.value='DetailView'; this.form.isDuplicate.value='true';this.form.module.value='{$MODULE}'; this.form.action.value='EditView'" type="submit" name="Duplicate" value="{$APP.LBL_DUPLICATE_BUTTON_LABEL}">&nbsp;
				{/if}
				{if $DELETE eq 'permitted'}
				<input title="{$APP.LBL_DELETE_BUTTON_TITLE}" accessKey="{$APP.LBL_DELETE_BUTTON_KEY}" class="crmbutton small delete" onclick="this.form.return_module.value='{$MODULE}'; this.form.return_action.value='index'; this.form.action.value='Delete'; {if $MODULE eq 'Vendors'} return confirm('{$APP.NTC_VENDOR_DELETE_CONFIRMATION}')" {else} return confirm('{$APP.NTC_DELETE_CONFIRMATION}')" {/if} type="submit" name="Delete" value="{$APP.LBL_DELETE_BUTTON_LABEL}">&nbsp;
				{/if}
			</td>
                   </tr>
                </table>
		<!-- Commented the buttons in DetailView because these buttons have been given as links
		{if $MODULE eq 'Quotes' || $MODULE eq 'PurchaseOrder' || $MODULE eq 'SalesOrder' || $MODULE eq 'Invoice'}
			{if $CREATEPDF eq 'permitted'}
				<input title="Export To PDF" accessKey="Alt+e" class="small" onclick="this.form.return_module.value='{$MODULE}'; this.form.return_action.value='DetailView'; this.form.return_id.value='{$ID}'; this.form.module.value='{$MODULE}'; {if $MODULE eq 'SalesOrder'} this.form.action.value='CreateSOPDF'" {else} this.form.action.value='CreatePDF'" {/if} type="submit" name="Export To PDF" value="{$APP.LBL_EXPORT_TO_PDF}">&nbsp;
			{/if}
		{/if}
		{if $MODULE eq 'Quotes'}
			{if $CONVERTSALESORDER eq 'permitted'}
				<input title="{$APP.LBL_CONVERTSO_BUTTON_TITLE}" accessKey="{$APP.LBL_CONVERTSO_BUTTON_KEY}" class="crmbutton small create" onclick="this.form.return_module.value='SalesOrder'; this.form.return_action.value='DetailView'; this.form.convertmode.value='quotetoso';this.form.module.value='SalesOrder'; this.form.action.value='EditView'" type="submit" name="Convert To SalesOrder" value="{$APP.LBL_CONVERTSO_BUTTON_LABEL}">&nbsp;
			{/if}
		{/if}
		{if $MODULE eq 'Potentials' || $MODULE eq 'Quotes' || $MODULE eq 'SalesOrder'}
			{if $CONVERTINVOICE eq 'permitted'}
				<input title="{$APP.LBL_CONVERTINVOICE_BUTTON_TITLE}" accessKey="{$APP.LBL_CONVERTINVOICE_BUTTON_KEY}" class="crmbutton small create" onclick="this.form.return_module.value='{$MODULE}'; this.form.return_action.value='DetailView'; this.form.return_id.value='{$ID}';this.form.convertmode.value='{$CONVERTMODE}';this.form.module.value='Invoice'; this.form.action.value='EditView'" type="submit" name="Convert To Invoice" value="{$APP.LBL_CONVERTINVOICE_BUTTON_LABEL}">&nbsp;
			{/if}
		{/if}
		-->
	</td>
   </tr>
   {/strip}
</table>
<!-- Button displayed - finished-->
							  <!-- Start of File Include by SAKTI on 10th Apr, 2008 -->
							 {include_php file="./include/DetailViewBlockStatus.php"}
							 <!-- Start of File Include by SAKTI on 10th Apr, 2008 -->

<!-- Entity information(blocks) display - start -->
{foreach key=header item=detail from=$BLOCKS}
	<table border=0 cellspacing=0 cellpadding=0 width=100% class="small">
	   <tr>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td align=right>
		</td>
	   </tr>
	   <tr>
		{strip}
		<td colspan=4 class="dvInnerHeader" >
							
							<!-- Start Of Code modified by SAKTI on 10th Apr, 2008 -->
							<div style="float:left;font-weight:bold;"><div style="float:left;"><a href="javascript:showHideStatus('tbl{$header|replace:' ':''}','aid{$header|replace:' ':''}','{$IMAGE_PATH}');">
							{if $BLOCKINITIALSTATUS[$header] eq 1}
								<img id="aid{$header|replace:' ':''}" src="{$IMAGE_PATH}activate.gif" style="border: 0px solid #000000;" alt="Hide" title="Hide"/>
							{else}
							<img id="aid{$header|replace:' ':''}" src="{$IMAGE_PATH}inactivate.gif" style="border: 0px solid #000000;" alt="Display" title="Display"/>
							{/if}
								</a></div><b>&nbsp;
						        	{$header}
	  			     			</b></div>
		</td>
		{/strip}
	   </tr>
							</table>
							{if $BLOCKINITIALSTATUS[$header] eq 1}
							<div style="width:auto;display:block;" id="tbl{$header|replace:' ':''}" >
							{else}
							<div style="width:auto;display:none;" id="tbl{$header|replace:' ':''}" >
							{/if}
							<table border=0 cellspacing=0 cellpadding=0 width="100%" class="small">
							<!-- End Of Code modified by SAKTI on 10th Apr, 2008-->

	   {foreach item=detail from=$detail}
	   <tr style="height:25px">
		{foreach key=label item=data from=$detail}
			{assign var=keyid value=$data.ui}
			{assign var=keyval value=$data.value}
			{assign var=keytblname value=$data.tablename}
			{assign var=keyfldname value=$data.fldname}
			{assign var=keyfldid value=$data.fldid}
			{assign var=keyoptions value=$data.options}
			{assign var=keysecid value=$data.secid}
			{assign var=keyseclink value=$data.link}
			{assign var=keycursymb value=$data.cursymb}
			{assign var=keysalut value=$data.salut}
			{assign var=keycntimage value=$data.cntimage}
			   {assign var=keyadmin value=$data.isadmin}
							   

				{if $label ne ''}
					{if $keycntimage ne ''}
						<td class="dvtCellLabel" align=right width=25%><input type="hidden" id="hdtxt_IsAdmin" value={$keyadmin}></input>{$keycntimage}</td>
					{elseif $label neq 'Tax Class'}<!-- Avoid to display the label Tax Class -->
						{if $keyid eq '71' || $keyid eq '72'}  <!--CurrencySymbol-->
							<td class="dvtCellLabel" align=right width=25%><input type="hidden" id="hdtxt_IsAdmin" value={$keyadmin}></input>{$label} ({$keycursymb})</td>
						{else}
							<td class="dvtCellLabel" align=right width=25%><input type="hidden" id="hdtxt_IsAdmin" value={$keyadmin}></input>{$label}</td>
						{/if}
					{/if}  
					{if $EDIT_PERMISSION eq 'yes'}
						{include file="DetailViewUI.tpl"}
					{else}
						{include file="DetailViewFields.tpl"}
					{/if}
				{/if}
		{/foreach}
	   </tr>	
	   {/foreach}	
	</table>
							 </div> <!-- Line added by SAKTI on 10th Apr, 2008 -->
{/foreach}
{*-- End of Blocks--*} 
<!-- Entity information(blocks) display - ends -->

									<br>

										<!-- Product Details informations -->
										{$ASSOCIATED_PRODUCTS}

									</td>
<!-- The following table is used to display the buttons -->
								<table border=0 cellspacing=0 cellpadding=0 width=100%>
			                			   <tr>
									<td style="padding:10px;border-right:1px dashed #CCCCCC;" width="80%">
			{if $SinglePane_View eq 'false'}
<table border=0 cellspacing=0 cellpadding=0 width=100%>
   {strip}
   <tr>
	<td  colspan=4 style="padding:5px">
		<table border=0 cellspacing=0 cellpadding=0 width=100%>
                   <tr>
            <td width=35%>
				{if $EDIT_DUPLICATE eq 'permitted'}
				<input title="{$APP.LBL_EDIT_BUTTON_TITLE}" accessKey="{$APP.LBL_EDIT_BUTTON_KEY}" class="crmbutton small edit" onclick="this.form.return_module.value='{$MODULE}'; this.form.return_action.value='DetailView'; this.form.return_id.value='{$ID}';this.form.module.value='{$MODULE}';this.form.action.value='EditView'" type="submit" name="Edit" value="{$APP.LBL_EDIT_BUTTON_LABEL}">&nbsp;
				{/if}
			</td>
			<td width=30% align=center>
					{if $privrecord neq ''}
						<img title="{$APP.LNK_LIST_PREVIOUS}" accessKey="{$APP.LNK_LIST_PREVIOUS}" onclick="location.href='index.php?module={$MODULE}&viewtype={$VIEWTYPE}&action=DetailView&record={$privrecord}&parenttab={$CATEGORY}'" name="privrecord" value="{$APP.LNK_LIST_PREVIOUS}" src="{$IMAGE_PATH}b_left.gif">&nbsp;
					{else}
						<img title="{$APP.LNK_LIST_PREVIOUS}" src="{$IMAGE_PATH}b_left_disable.gif">
					{/if}
					&nbsp;
					{if $nextrecord neq ''}
						<img title="{$APP.LNK_LIST_NEXT}" accessKey="{$APP.LNK_LIST_NEXT}" onclick="location.href='index.php?module={$MODULE}&viewtype={$VIEWTYPE}&action=DetailView&record={$nextrecord}&parenttab={$CATEGORY}'" name="nextrecord" src="{$IMAGE_PATH}b_right.gif">&nbsp;
					{else}
						<img title="{$APP.LNK_LIST_NEXT}" src="{$IMAGE_PATH}b_right_disable.gif">&nbsp;
					{/if}
			</td>
			<td width=35% align=right>
					{if $privrecord neq '' || $nextrecord neq ''}
						<input title="{$APP.LBL_JUMP_BTN}" accessKey="{$APP.LBL_JUMP_BTN}" class="crmbutton small create" onclick="location.href='javascript:getListOfRecords(this, \'{$MODULE}\',{$ID},\'{$CATEGORY}\');'" type="button" name="jumpBtnId" id="jumpBtnId" value="{$APP.LBL_JUMP_BTN}">
					{/if}
				{if $EDIT_DUPLICATE eq 'permitted'}
				<input title="{$APP.LBL_DUPLICATE_BUTTON_TITLE}" accessKey="{$APP.LBL_DUPLICATE_BUTTON_KEY}" class="crmbutton small create" onclick="this.form.return_module.value='{$MODULE}'; this.form.return_action.value='DetailView'; this.form.isDuplicate.value='true';this.form.module.value='{$MODULE}'; this.form.action.value='EditView'" type="submit" name="Duplicate" value="{$APP.LBL_DUPLICATE_BUTTON_LABEL}">&nbsp;
				{/if}
				{if $DELETE eq 'permitted'}
				<input title="{$APP.LBL_DELETE_BUTTON_TITLE}" accessKey="{$APP.LBL_DELETE_BUTTON_KEY}" class="crmbutton small delete" onclick="this.form.return_module.value='{$MODULE}'; this.form.return_action.value='index'; this.form.action.value='Delete'; {if $MODULE eq 'Vendors'} return confirm('{$APP.NTC_VENDOR_DELETE_CONFIRMATION}')" {else} return confirm('{$APP.NTC_DELETE_CONFIRMATION}')" {/if} type="submit" name="Delete" value="{$APP.LBL_DELETE_BUTTON_LABEL}">&nbsp;
				{/if}
			</td>
                   </tr>
                </table>

		<!-- Commented the buttons in DetailView because these buttons have been given as links
		{if $MODULE eq 'Quotes' || $MODULE eq 'PurchaseOrder' || $MODULE eq 'SalesOrder' || $MODULE eq 'Invoice'}
			{if $CREATEPDF eq 'permitted'}
				<input title="Export To PDF" accessKey="Alt+e" class="small" onclick="this.form.return_module.value='{$MODULE}'; this.form.return_action.value='DetailView'; this.form.return_id.value='{$ID}'; this.form.module.value='{$MODULE}'; {if $MODULE eq 'SalesOrder'} this.form.action.value='CreateSOPDF'" {else} this.form.action.value='CreatePDF'" {/if} type="submit" name="Export To PDF" value="{$APP.LBL_EXPORT_TO_PDF}">&nbsp;
			{/if}
		{/if}
		{if $MODULE eq 'Quotes'}
			{if $CONVERTSALESORDER eq 'permitted'}
				<input title="{$APP.LBL_CONVERTSO_BUTTON_TITLE}" accessKey="{$APP.LBL_CONVERTSO_BUTTON_KEY}" class="crmbutton small create" onclick="this.form.return_module.value='SalesOrder'; this.form.return_action.value='DetailView'; this.form.convertmode.value='quotetoso';this.form.module.value='SalesOrder'; this.form.action.value='EditView'" type="submit" name="Convert To SalesOrder" value="{$APP.LBL_CONVERTSO_BUTTON_LABEL}">&nbsp;
			{/if}
		{/if}
		{if $MODULE eq 'Potentials' || $MODULE eq 'Quotes' || $MODULE eq 'SalesOrder'}
			{if $CONVERTINVOICE eq 'permitted'}
				<input title="{$APP.LBL_CONVERTINVOICE_BUTTON_TITLE}" accessKey="{$APP.LBL_CONVERTINVOICE_BUTTON_KEY}" class="crmbutton small create" onclick="this.form.return_module.value='{$MODULE}'; this.form.return_action.value='DetailView'; this.form.return_id.value='{$ID}';this.form.convertmode.value='{$CONVERTMODE}';this.form.module.value='Invoice'; this.form.action.value='EditView'" type="submit" name="Convert To Invoice" value="{$APP.LBL_CONVERTINVOICE_BUTTON_LABEL}">&nbsp;
			{/if}
		{/if}
		-->
	</td>
   </tr>
   {/strip}
</table>
{/if}
</form>
		<table border=0 cellspacing=0 cellpadding=0 width=100%>
		  <tr>
			<td style="border-right:1px dashed #CCCCCC;" width="100%">
			{if $SinglePane_View eq 'true'}
				{include file= 'RelatedListNew.tpl'}
			{/if}
		</td></tr></table>
</td></tr></table>
<!-- Button displayed - finished-->
									<!-- Inventory Actions - ends -->	
									<td width=22% valign=top style="padding:10px;">
										<!-- right side InventoryActions -->
										{include file="Inventory/InventoryActions.tpl"}

										<br>
										<!-- To display the Tag Clouds -->
										<div>
										      {include file="TagCloudDisplay.tpl"}
										</div>
									</td>
								   </tr>
								</table>
							</td>
						   </tr>
						</table>
					<!-- PUBLIC CONTENTS STOPS-->
					</td>
					<td align=right valign=top>
						<img src="{$IMAGE_PATH}showPanelTopRight.gif">
					</td>
				   </tr>
				</table>
			   </div>
			</td>
		   </tr>
		</table>
		<!-- Contents - end -->

<script>
function getTagCloud()
{ldelim}
new Ajax.Request(
        'index.php',
        {ldelim}queue: {ldelim}position: 'end', scope: 'command'{rdelim},
        method: 'post',
        postBody: 'module={$MODULE}&action={$MODULE}Ajax&file=TagCloud&ajxaction=GETTAGCLOUD&recordid={$ID}',
        onComplete: function(response) {ldelim}
                                $("tagfields").innerHTML=response.responseText;
                                $("txtbox_tagfields").value ='';
                        {rdelim}
        {rdelim}
);
{rdelim}
getTagCloud();
</script>

	</td>
   </tr>
</table>
<script language="javascript">
  var fieldname = new Array({$VALIDATION_DATA_FIELDNAME});
  var fieldlabel = new Array({$VALIDATION_DATA_FIELDLABEL});
  var fielddatatype = new Array({$VALIDATION_DATA_FIELDDATATYPE});
</script>

