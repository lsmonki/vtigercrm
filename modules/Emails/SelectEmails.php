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

require_once('include/database/PearDatabase.php');

$idlist = $_POST['idlist'];
$pmodule=$_REQUEST['return_module'];
$ids=explode(';',$idlist);

if ($pmodule=='Accounts')
{
	$querystr="select fieldid,fieldlabel,columnname,tablename from field where tabid=6 and uitype=13;"; 
}
elseif ($pmodule=='Contacts')
{
	$querystr="select fieldid,fieldlabel,columnname from field where tabid=4 and uitype=13;";
}
elseif ($pmodule=='Leads')
{
	$querystr="select fieldid,fieldlabel,columnname from field where tabid=7 and uitype=13;";
}
$result=$adb->query($querystr);
$numrows = $adb->num_rows($result);
?>
<form name="choosemails" method="post"  action="index.php">
	<input type="hidden" name="emailids" value="">
	<input type="hidden" name="module" value="Emails">
	<input type="hidden" name="action" value="EditView">
	<input type="hidden" name="pmodule" value="<?php echo $pmodule;?>">

	<h4>The following emails are available for the selected record. Please choose the ones you would like to use.</h4>
	<div align="center"><table cellpadding="0" cellspacing="0" border="0">
<?php

for ($i=0;$i<$numrows;$i++)
{
	$temp=$adb->query_result($result,$i,'columnname');
	$temp1=br2nl($myfocus->column_fields[$temp]);
	echo '<tr><td>'.$adb->query_result($result,$i,'fieldlabel').' </td><td>&nbsp;&nbsp;&nbsp;<input name="emails'.$i.'" type="checkbox"></td><td>'.$temp1.'</tr>';
}
?>

<script language="javascript">
function passemail()
{		
	y=new Array();
	<?php  
		foreach ($ids as $id_num => $id)
		{
			print "y.push(\"$id\" );";
		}
		$cnt=count($ids);
		print "idcount=$cnt;";
	?>
	<?php 
	for ($x=0;$x<$numrows;$x++)
	{
	?>
		if (document.choosemails.emails<?php echo $x;?>.checked)
		{
			for (m=0;m<(idcount-1);m++)
			{
				y[m]=y[m]+"@<?php echo $adb->query_result($result,$x,'fieldid');?>";
			}
		}
	<?php 
	} 
	?>
	stry = y.join("|");
	document.choosemails.emailids.value=stry;
	document.choosemails.submit();
}
</script>

</table>
	<input type="button" name="OK" onClick="passemail()" value="OK">
	</div>
</form>
