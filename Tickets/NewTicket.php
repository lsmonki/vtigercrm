<?php
/*********************************************************************************
** The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
*
 ********************************************************************************/

global $mod_strings;
global $client;

$params = Array('id' => "$result[0]");
$result = $client->call('get_combo_values', $params, $Server_Path, $Server_Path);

$_SESSION['combolist'] = $result;
$combolist = $_SESSION['combolist'];
for($i=0;$i<count($result);$i++)
{
	if($result[$i]['productid'] != '')
	{
		$productslist[0] = $result[$i]['productid'];
	}
	if($result[$i]['productname'] != '')
	{
		$productslist[1] = $result[$i]['productname'];
	}
	if($result[$i]['ticketpriorities'] != '')
	{
		$ticketpriorities = $result[$i]['ticketpriorities'];
	}
	if($result[$i]['ticketseverities'] != '')
	{
		$ticketseverities = $result[$i]['ticketseverities'];
	}
	if($result[$i]['ticketcategories'] != '')
	{
		$ticketcategories = $result[$i]['ticketcategories'];
	}
	//Added to display the module -- 10-11-2005
	if($result[$i]['moduleslist'] != '')
	{
		$moduleslist = $result[$i]['moduleslist'];
	}

}

$noofrows = count($productslist[0]);

for($i=0;$i<$noofrows;$i++)
{
	if($i > 0)
		$productarray .= ',';
	$productarray .= "'".$productslist[1][$i]."'";
}


?>
<tr><td colspan="2">
<table border="0" cellpadding="0" cellspacing="0" width="100%">
   <tr>
	<td height="35">&nbsp;</td>
   </tr>
   <tr>
	<td align="left">
	   <span class="lvtHeaderText">&nbsp;&nbsp;New Ticket</span>
	   <hr noshade="noshade" size="1" width="90%" align="left"><br><br>
		<table width="80%"  border="0" cellspacing="0" cellpadding="5" align="center">
		   <form name="Save" method="post" action="index.php">
		   <input type="hidden" name="module">
		   <input type="hidden" name="action">
		   <input type="hidden" name="fun">
		   <tr>
			<td colspan="4" class="detailedViewHeader"><b>New Ticket</b></td></tr>  
		   <tr>
			<td class="dvtCellLabel" align="right"><font color="red">*</font>Title</td>
			<td colspan="3" class="dvtCellInfo">
				<input type="text" name="title" class="detailedViewTextBox"  onFocus="this.className='detailedViewTextBoxOn'" onBlur="this.className='detailedViewTextBox'">
			</td>
		   </tr>
		   <tr>
			<td class="dvtCellLabel" align="right" width="20%">Product Name</td>

			<!-- Product auto drop down - start -->
			<style>                       @import url( css/dropdown.css );                </style>
			<script src="js/modomt.js"></script>
			<script src="js/getobject2.js"></script>
			<script src="js/acdropdown.js"></script>
			<script language="javascript">var products = new Array(<?php echo $productarray; ?>)</script>

			<td class="dvtCellInfo" width="20%">
				<input class="dropdown" autocomplete="off" name="productid" id="inputer2" style="width: 135px;" acdropdown="true" autocomplete_list="array:products" autocomplete_list_sort="false" autocomplete_matchsubstring="true">
			<!-- Product auto drop down - end -->

			</td>
			<td class="dvtCellInfo" width="20%">&nbsp;</td>
			<td class="dvtCellInfo" width="20%">&nbsp;</td>
		   </tr>
		   <tr>
			<td class="dvtCellLabel" align="right">Ticket Priority</td>
			<td class="dvtCellInfo">
				<?php
					echo getComboList('priority',$ticketpriorities);
				?>
			</td>
			<td class="dvtCellLabel" align="right">Ticket Severity</td>
			<td class="dvtCellInfo">
				<!-- select name="select7" size="1" class="detailedViewTextBox"  onFocus="this.className='detailedViewTextBoxOn'" onBlur="this.className='detailedViewTextBox'"></select -->
				<?php
					echo getComboList('severity',$ticketseverities);
				?>
			</td>
		   </tr>
		   <tr>
			<td class="dvtCellLabel" align="right">Ticket Category</td>
			<td class="dvtCellInfo">
				<?php
					echo getComboList('category',$ticketcategories);
				?>
			</td>
			<td class="dvtCellLabel" align="right">Module</td>
			<td class="dvtCellInfo">
				<?php
					echo getComboList('ticket_module',$moduleslist,'General');
				?>
			</td>
		   </tr>
		   <tr>
			<td class="dvtCellLabel" align="right">Description</td>
			<td colspan="3" class="dvtCellInfo">
				<textarea name="description" cols="55" rows="5" class="detailedViewTextBox"  onFocus="this.className='detailedViewTextBoxOn'" onBlur="this.className='detailedViewTextBox'"></textarea>
			</td>
		   </tr>
		   <tr>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
		   </tr>
		   <tr>
			<td>&nbsp;</td>
			<td colspan="2">
			   <div align="center">
				<input title="Save[Alt+S]" accesskey="S" class="small"  name="button" value="Save" style="width: 70px;" type="submit" onclick="this.form.module.value='Tickets';this.form.action.value='index';this.form.fun.value='saveticket'; return formvalidate(this.form)">
				<input title="Cancel[Alt+X]" accesskey="X" class="small" name="button" value="Cancel" style="width: 70px;" type="button" onclick="window.history.back()";>
			   </div>
			</td>
			<td>&nbsp;</td>
		   </tr>
			<tt><td colspan="4">&nbsp;</td></tt>
		   </form>
		</table>
	 </td>
   </tr>
</table>
<script>
function formvalidate(form)
{
	if(trim(form.title.value) == '')
	{
		alert("Ticket Title is empty");
		return false;
	}
	return true;
}
function trim(s) 
{
	while (s.substring(0,1) == " ")
	{
		s = s.substring(1, s.length);
	}
	while (s.substring(s.length-1, s.length) == ' ')
	{
		s = s.substring(0,s.length-1);
	}

	return s;
}
</script>
<?php

?>
