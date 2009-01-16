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

{assign var="fromlink" value=""}
<!-- Added this file to display the fields in Create Entity page based on ui types  -->
{foreach key=label item=subdata from=$data}
	{if $header eq 'Product Details'}
		<tr>
	{else}
		<tr style="height:25px">
	{/if}
	{foreach key=mainlabel item=maindata from=$subdata}
		{include file='EditViewUI.tpl'}
	{/foreach}
   </tr>
{/foreach}

<script language="javascript">
	function fnshowHide(currObj,txtObj)
	{ldelim}
			if(currObj.checked == true)
				document.getElementById(txtObj).style.visibility = 'visible';
			else
				document.getElementById(txtObj).style.visibility = 'hidden';
	{rdelim}
	
	function fntaxValidation(txtObj)
	{ldelim}
			if (!numValidate(txtObj,"Tax","any"))
				document.getElementById(txtObj).value = 0;
	{rdelim}	
	
	function fnpriceValidation(txtObj)
	{ldelim}
		if (!numValidate(txtObj,"Price","any"))
			document.getElementById(txtObj).value = 0;
	{rdelim}	

function delimage(id)
{ldelim}
	new Ajax.Request(
		'index.php',
		{ldelim}queue: {ldelim}position: 'end', scope: 'command'{rdelim},
			method: 'post',
			postBody: 'module=Contacts&action=ContactsAjax&file=DelImage&recordid='+id,
			onComplete: function(response)
				    {ldelim}
					if(response.responseText.indexOf("SUCCESS")>-1)
						$("replaceimage").innerHTML='{$APP.LBL_IMAGE_DELETED}';
					else
						alert("{$APP.ERROR_WHILE_EDITING}")
				    {rdelim}
		{rdelim}
	);

{rdelim}

// Function to enable/disable related elements based on whether the current object is checked or not
function fnenableDisable(currObj,enableId)
{ldelim}
	var disable_flag = true;
	if(currObj.checked == true)
		disable_flag = false;
	
	document.getElementById('curname'+enableId).disabled = disable_flag;
	document.getElementById('cur_reset'+enableId).disabled = disable_flag;
	document.getElementById('base_currency'+enableId).disabled = disable_flag;	
{rdelim}

// Update current value with current value of base currency and the conversion rate
function updateCurrencyValue(currObj,txtObj,base_curid,conv_rate)
{ldelim}
	var unit_price = $(base_curid).value;
	//if(currObj.checked == true)
	//{ldelim}
		document.getElementById(txtObj).value = unit_price * conv_rate;
	//{rdelim}
{rdelim}

// Synchronize between Unit price and Base currency value.
function updateUnitPrice(from_cur_id, to_cur_id)
{ldelim}
    var from_ele = document.getElementById(from_cur_id);
    if (from_ele == null) return;
    
    var to_ele = document.getElementById(to_cur_id);
    if (to_ele == null) return;

    to_ele.value = from_ele.value;
{rdelim}

// Update hidden base currency value, everytime the base currency value is changed in multi-currency UI
function updateBaseCurrencyValue()
{ldelim}
    var cur_list = document.getElementsByName('base_currency_input');
    if (cur_list == null) return;
    
    var base_currency_ele = document.getElementById('base_currency');
    if (base_currency_ele == null) return;
    
    for(var i=0; i<cur_list.length; i++) 
    {ldelim}	
		var cur_ele = cur_list[i];
		if (cur_ele != null && cur_ele.checked == true)
    		base_currency_ele.value = cur_ele.value;
	{rdelim}
{rdelim}

function FileAdd(obj,Lay,return_action,crm_id)
{ldelim}
	fnvshobj(obj,Lay);
	window.frames['AddFile'].document.getElementById('divHeader').innerHTML="Add file";
	window.frames['AddFile'].document.FileAdd.return_action.value=return_action;
	window.frames['AddFile'].document.FileAdd.crm_id.value=crm_id;
	positionDivToCenter(Lay);
{rdelim}

</script>

