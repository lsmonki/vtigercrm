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
//require_once('adodb/adodb.php');

$fldmodule=$_REQUEST['fld_module'];
 $fldlabel=$_REQUEST['fldLabel'];
 $fldType= $_REQUEST['fieldType'];

/*

echo 'module is  ' .$fldmodule;
echo 'label is    '. $fldlabel;
echo 'field type is  ' .$fldType;

*/


function fetchTabIDVal($fldmodule)
{

  global $adb;
  $query = "select tabid from tab where tablabel='" .$fldmodule ."'";
  $tabidresult = $adb->query($query);
  return $adb->query_result($tabidresult,0,"tabid");
}

$tabid = fetchTabIDVal($fldmodule);

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
	
	header("Location:index.php?module=Settings&action=CreateCustomField&fld_module=".$fldmodule."&fldType=".$fldType."&fldlabel=".$fldlabel."&fldlength=".$fldlength."&flddecimal=".$flddecimal."&fldPickList=".$fldPickList."&duplicate=yes");

}
else
{
  /*
	//Creating the ColumnName
  $sql = "select max(fieldid) fieldid from customfields";
	$result = $adb->query($sql);
	if($adb->num_rows($result) != 0)
	{
		$row = $adb->fetch_array($result);
		$max_fieldid = $row["fieldid"];
		$max_fieldid++;
	}
	else
	{
		$max_fieldid = "1";
	}
  */
  
  $max_fieldid = $adb->getUniqueID("field");
  
	$columnName = 'cf_'.$max_fieldid;
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
	if($fldType == 'Text')
	{
		$uitype = 1;
		//$type = "varchar(".$fldlength.")";
		$type = "C(".$fldlength.")"; // adodb type
	}
	elseif($fldType == 'Number')
	{
		$uitype = 7;

		//$type="double(".$fldlength.",".$decimal.")";	
		$type="N(".$fldlength.",".$decimal.")";	// adodb type
	}
	elseif($fldType == 'Percent')
	{
		$uitype = 9;
		//$type="double(".$fldlength.",".$decimal.")";
		$type="N(".$fldlength.",".$decimal.")"; //adodb type
	}
	elseif($fldType == 'Currency')
	{
		$uitype = 3;
		//$type="double(".$fldlength.",".$decimal.")";
		$type="N(".$fldlength.",".$decimal.")"; //adodb type
	}
	elseif($fldType == 'Date')
	{
		$uitype = 5;
		//$type = "date";
		$type = "T"; // adodb type
		
	}
	elseif($fldType == 'Email')
	{
		$uitype = 13;
		//$type = "varchar(50)";
		$type = "C(50)"; //adodb type
		
	}
	elseif($fldType == 'Phone')
	{
		$uitype = 11;
		//$type = "varchar(30)";
		$type = "C(30)"; //adodb type
		
	}
	elseif($fldType == 'Picklist')
	{
		$uitype = 15;
		//$type = "varchar(255)";
		$type = "C(255)"; //adodb type
	}
	elseif($fldType == 'URL')
	{
		$uitype = 17;
		//$type = "varchar(255)";
		$type = "C(255)"; //adodb type
	}
	// No Decimal Pleaces Handling

        


        

        //1. add the customfield table to the field table as Block4
        //2. fetch the contents of the custom field and show in the UI
        
        //$query = "insert into customfields values('','".$columnName."','".$tableName."',2,".$uitype.",'".$fldlabel."','0','".$fldmodule."')";
	//retreiving the sequence
	$custfld_sequece=$adb->getUniqueId("customfield_sequence");
    
        $query = "insert into field values(".$tabid.",".$adb->getUniqueID("field").",'".$columnName."','".$tableName."',2,".$uitype.",'".$columnName."','".$fldlabel."',0,0,0,100,".$custfld_sequece.",5,1)";
	
        $adb->query($query);
	
        $adb->alterTable($tableName, $columnName." ".$type, "Add_Column");
        




          
	if($fldType == 'Picklist')
	{
		// Creating the PickList Table and Populating Values
		/*$query = "create table ".$fldmodule."_".$columnName." (".$columnName." varchar(255) NOT NULL)";
		mysql_query($query);*/
		$adb->createTable($columnName, $columnName." C(255)");
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
	header("Location:index.php?module=Settings&action=CustomFieldList&fld_module=".$fldmodule);
}
?>
