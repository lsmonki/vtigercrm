<?
require_once('include/utils.php');
require_once('include/database/PearDatabase.php');
function getBlockInformation($module, $block, $mode, $col_fields)
{
	//retreive the tabid	
	global $adb;
	$tabid = getTabid($module);
	global $profile_id;

	//retreive the fields from database
	if($block == 5)
	{
	
	 	$sql = "select * from field where tabid=".$tabid." and block=".$block ." and displaytype=1 order by sequence";
	}
	else
	{	

		$sql = "select * from field inner join profile2field on profile2field.fieldid=field.fieldid  where field.tabid=".$tabid." and field.block=".$block ." and field.displaytype=1 and profile2field.visible=0 and profile2field.profileid=".$profile_id." order by sequence";
	}

        $result = $adb->query($sql);
	$noofrows = $adb->num_rows($result);
	$output='';
	if (($module == 'Accounts' || $module == 'Contacts') && $block == 2)
	{
			$mvAdd_flag = true;
			$moveAddress = "<td rowspan='5' valign='middle' align='center'><input title='Copy billing address to shipping address'  class='button' onclick='return copyAddressRight(EditView)'  type='button' name='copyright' value='>>' ><br><br>
				<input title='Copy shipping address to billing address'  class='button' onclick='return copyAddressLeft(EditView)'  type='button' name='copyleft' value='<<' ></td>";
	}
	

	for($i=0; $i<$noofrows; $i++)
	{
		$fieldtablename = $adb->query_result($result,$i,"tablename");	
		$fieldcolname = $adb->query_result($result,$i,"columnname");	
		$uitype = $adb->query_result($result,$i,"uitype");	
		$fieldname = $adb->query_result($result,$i,"fieldname");	
		$fieldlabel = $adb->query_result($result,$i,"fieldlabel");
		$maxlength = $adb->query_result($result,$i,"maxlength");
				
		$output .= '<tr>';
		$custfld = getOutputHtml($uitype, $fieldname, $fieldlabel, $maxlength, $col_fields);
		$output .= $custfld;	
		if ($mvAdd_flag == true)
		$output .= $moveAddress;
		$mvAdd_flag = false;
		$i++;
		if($i<$noofrows)
		{
			$fieldtablename = $adb->query_result($result,$i,"tablename");	
			$fieldcolname = $adb->query_result($result,$i,"columnname");	
			$uitype = $adb->query_result($result,$i,"uitype");	
			$fieldname = $adb->query_result($result,$i,"fieldname");	
			$fieldlabel = $adb->query_result($result,$i,"fieldlabel");
			$maxlength = $adb->query_result($result,$i,"maxlength");
			$output .= '';
			$custfld = getOutputHtml($uitype, $fieldname, $fieldlabel, $maxlength, $col_fields);
			$output .= $custfld;	
		}
		$output .= '</tr>';
			
	}
	return $output;
		
}


function getDetailBlockInformation($module, $block, $col_fields)
{
	//retreive the tabid	
	global $adb;
	$tabid = getTabid($module);
        global $profile_id;

	//retreive the fields from database
	if($block == 5)
	{
	
	 	$sql = "select * from field where tabid=".$tabid." and block=".$block ." and displaytype in (1,2) order by sequence";
	}
	else
	{
		$sql = "select * from field inner join profile2field on profile2field.fieldid=field.fieldid  where field.tabid=".$tabid." and field.block=".$block ." and field.displaytype in (1,2) and profile2field.visible=0 and profile2field.profileid=".$profile_id." order by sequence";
	}
	$result = $adb->query($sql);
	$noofrows = $adb->num_rows($result);
	$output='';
	for($i=0; $i<$noofrows; $i++)
	{
		$fieldtablename = $adb->query_result($result,$i,"tablename");	
		$fieldcolname = $adb->query_result($result,$i,"columnname");	
		$uitype = $adb->query_result($result,$i,"uitype");	
		$fieldname = $adb->query_result($result,$i,"fieldname");	
		$fieldlabel = $adb->query_result($result,$i,"fieldlabel");
		$maxlength = $adb->query_result($result,$i,"maxlength");
		$output .= '<tr>';
		$custfld = getDetailViewOutputHtml($uitype, $fieldname, $fieldlabel, $col_fields);
		$output .= $custfld;
		$i++;
		if($i<$noofrows)
		{
			$fieldtablename = $adb->query_result($result,$i,"tablename");	
			$fieldcolname = $adb->query_result($result,$i,"columnname");	
			$uitype = $adb->query_result($result,$i,"uitype");	
			$fieldname = $adb->query_result($result,$i,"fieldname");	
			$fieldlabel = $adb->query_result($result,$i,"fieldlabel");
			$maxlength = $adb->query_result($result,$i,"maxlength");
			$output .= '';
			$custfld = getDetailViewOutputHtml($uitype, $fieldname, $fieldlabel, $col_fields);
			$output .= $custfld;	
		}
		$output .= '</tr>';

	}
	return $output;

}
?>
