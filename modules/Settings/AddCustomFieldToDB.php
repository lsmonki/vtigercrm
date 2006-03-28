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

$fldmodule=$_REQUEST['fld_module'];
 $fldlabel=$_REQUEST['fldLabel'];
 $fldType= $_REQUEST['fieldType'];
 $parenttab=$_REQUEST['parenttab'];

$tabid = getTabid($fldmodule);

if(get_magic_quotes_gpc() == 1)
{
	$fldlabel = stripslashes($fldlabel);
}


//checking if the user is trying to create a custom field which already exists  

$checkquery="select * from field where tabid='".$tabid."'and fieldlabel='".$fldlabel."'";
$checkresult=$adb->query($checkquery);

if($adb->num_rows($checkresult) != 0)
{
	
	if(isset($_REQUEST['fldLength']))
	{	
		$fldlength=$_REQUEST['fldLength'];
	}
	else
	{
		 $fldlength='';
	}
	if(isset($_REQUEST['fldDecimal']))
	{
		$flddecimal=$_REQUEST['fldDecimal'];
	}
	else
	{
		$flddecimal='';
	}
	if(isset($_REQUEST['fldPickList']))
	{
		$fldPickList=$_REQUEST['fldPickList'];
	}
	else
	{
		$fldPickList='';
	}
	
	header("Location:index.php?module=Settings&action=CreateCustomField&fld_module=".$fldmodule."&fldType=".$fldType."&fldlabel=".$fldlabel."&fldlength=".$fldlength."&flddecimal=".$flddecimal."&fldPickList=".$fldPickList."&parenttab=".$parenttab."&duplicate=yes");

}
else
{
	if($_REQUEST['fieldid'] == '')
	{
		$max_fieldid = $adb->getUniqueID("field");
		$columnName = 'cf_'.$max_fieldid;
	}
	else
	{
		$max_fieldid = $_REQUEST['column'];
		$columnName = $max_fieldid;
	}
  
	//Assigning the table Name
	$tableName ='';
	if($fldmodule == 'Leads')
	{
		$tableName='leadscf';
	}
	elseif($fldmodule == 'Accounts')
	{

		$tableName='accountscf';
	}
	elseif($fldmodule == 'Contacts')
	{

		$tableName='contactscf';
	}
	elseif($fldmodule == 'Potentials')
	{
		$tableName='potentialscf';
	}
	elseif($fldmodule == 'HelpDesk')
	{
		$tableName='ticketcf';
	}
	elseif($fldmodule == 'Products')
	{
		$tableName='productcf';
	}
	elseif($fldmodule == 'Vendor')
	{
		$tableName='vendorcf';
	}
	elseif($fldmodule == 'PriceBook')
	{
		$tableName='pricebookcf';
	}
	elseif($fldmodule == 'Quotes')
	{
		$tableName='quotescf';
	}
	elseif($fldmodule == 'PurchaseOrder')
	{
		$tableName='purchaseordercf';
	}
	elseif($fldmodule == 'SalesOrder')
	{
		$tableName='salesordercf';
	}
	elseif($fldmodule == 'Invoice')
	{
		$tableName='invoicecf';
	}
	//Assigning the uitype
	$fldlength=$_REQUEST['fldLength'];
	$uitype='';
	$fldPickList='';
	if(isset($_REQUEST['fldDecimal']) && $_REQUEST['fldDecimal'] != '')
	{
		$decimal=$_REQUEST['fldDecimal'];
	}
	else
	{
		$decimal=0;
	}
	$type='';
	$uichekdata='';
	if($fldType == 'Text')
	{
	$uichekdata='V~O~LE~'.$fldlength;
		$uitype = 1;
		$type = "C(".$fldlength.")"; // adodb type
	}
	elseif($fldType == 'Number')
	{
		$uitype = 7;

		//this may sound ridiculous passing decimal but that is the way adodb wants
		$dbfldlength = $fldlength + $decimal + 1;
 
		$type="N(".$dbfldlength.".".$decimal.")";	// adodb type
	$uichekdata='N~O~'.$fldlength .','.$decimal;
	}
	elseif($fldType == 'Percent')
	{
		$uitype = 9;
		$type="N(5.2)"; //adodb type
		$uichekdata='N~O~2~2';
	}
	elseif($fldType == 'Currency')
	{
		$uitype = 71;
		$dbfldlength = $fldlength + $decimal + 1;
		$type="N(".$dbfldlength.".".$decimal.")"; //adodb type
	$uichekdata='N~O~'.$fldlength .','.$decimal;
	}
	elseif($fldType == 'Date')
	{
	$uichekdata='D~O';
		$uitype = 5;
		$type = "D"; // adodb type
		
	}
	elseif($fldType == 'Email')
	{
		$uitype = 13;
		$type = "C(50)"; //adodb type
		$uichekdata='V~O';
	}
	elseif($fldType == 'Phone')
	{
		$uitype = 11;
		$type = "C(30)"; //adodb type
		
		$uichekdata='V~O';
	}
	elseif($fldType == 'Picklist')
	{
		$uitype = 15;
		$type = "C(255)"; //adodb type
		$uichekdata='V~O';
	}
	elseif($fldType == 'URL')
	{
		$uitype = 17;
		$type = "C(255)"; //adodb type
		$uichekdata='V~O';
	}
	elseif($fldType == 'Checkbox')	 
        {	 
                 $uitype = 56;	 
                 $type = "C(3) default 0"; //adodb type	 
                 $uichekdata='C~0';	 
        }
	elseif($fldType == 'TextArea')	 
        {	 
                 $uitype = 21;	 
                 $type = "X"; //adodb type	 
                 $uichekdata='V~0';	 
        }
	elseif($fldType == 'MultiSelectCombo')
	{
		 $uitype = 33;
		 $type = "X"; //adodb type
		 $uichekdata='V~0';
	}
	// No Decimal Pleaces Handling

        


        

        //1. add the customfield table to the field table as Block4
        //2. fetch the contents of the custom field and show in the UI
        
	//retreiving the sequence
	if($_REQUEST['fieldid'] == '')
	{
		$custfld_fieldid=$adb->getUniqueID("field");
	}
	$custfld_sequece=$adb->getUniqueId("customfield_sequence");
    	
	$blockid ='';
        //get the blockid for this custom block
        $blockid = getBlockId($tabid,'LBL_CUSTOM_INFORMATION');

        if(is_numeric($blockid))
        {
		if($_REQUEST['fieldid'] == '')
		{
			$query = "insert into field values(".$tabid.",".$custfld_fieldid.",'".$columnName."','".$tableName."',2,".$uitype.",'".$columnName."','".$fldlabel."',0,0,0,100,".$custfld_sequece.",$blockid,1,'".$uichekdata."',1,0,'ADV')";
			$adb->query($query);
			$adb->alterTable($tableName, $columnName." ".$type, "Add_Column");
		}
		else
		{
			$query = "update field set fieldlabel='".$fldlabel."',typeofdata='".$uichekdata."' where fieldid=".$_REQUEST['fieldid'];
			$adb->query($query);
		}
		//Inserting values into profile2field tables
		$sql1 = "select * from profile";
		$sql1_result = $adb->query($sql1);
		$sql1_num = $adb->num_rows($sql1_result);
		for($i=0; $i<$sql1_num; $i++)
		{
			$profileid = $adb->query_result($sql1_result,$i,"profileid");
			$sql2 = "insert into profile2field values(".$profileid.", ".$tabid.", ".$custfld_fieldid.", 0, 1)";
			$adb->query($sql2);	 	
		}

		//Inserting values into def_org tables
		$sql_def = "insert into def_org_field values(".$tabid.", ".$custfld_fieldid.", 0, 1)";
		$adb->query($sql_def);


		if($fldType == 'Picklist' || $fldType == 'MultiSelectCombo')
		{
			// Creating the PickList Table and Populating Values
			$adb->createTable($columnName, $columnName." C(255)");
			//Adding Primary Key
			$qur = "ALTER table ".$columnName." ADD PRIMARY KEY (". $columnName.")";
			$adb->query($qur);

			$fldPickList =  $_REQUEST['fldPickList'];
			$pickArray = explode("\n",$fldPickList);
			$count = count($pickArray);
			for($i = 0; $i < $count; $i++)
			{
				$pickArray[$i] = trim($pickArray[$i]);
				if($pickArray[$i] != '')
				{
					$query = "insert into ".$columnName." values('".$pickArray[$i]."')";
					$adb->query($query);
				}
			}
		}
		//Inserting into LeadMapping table - Jaguar
		if($fldmodule == 'Leads')
		{

			$sql_def = "insert into convertleadmapping (leadfid) values(".$custfld_fieldid.")";
			$adb->query($sql_def);
		}
	}
	header("Location:index.php?module=Settings&action=CustomFieldList&fld_module=".$fldmodule."&parenttab=".$parenttab);
}
?>
