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
//Establishing the database connection
require_once('database/DatabaseConnection.php');
$fldmodule=$_REQUEST['fld_module'];
$fldlabel=$_REQUEST['fldLabel'];
$fldType= $_REQUEST['fieldType'];
/*echo $fldmodule;
echo $fldlabel;
echo $fldType;*/
if(get_magic_quotes_gpc() == 1)
{
	$fldlabel = stripslashes($fldlabel);
}
$checkquery='select * from customfields where module="'.$fldmodule.'" and fieldlabel="'.$fldlabel.'"';
$checkresult=mysql_query($checkquery);
if(mysql_num_rows($checkresult) != 0)
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
	//Creating the ColumnName
	$sql = "select max(fieldid) as fieldid from customfields";
	$result = mysql_query($sql);
	if(mysql_num_rows($result) != 0)
	{
		$row = mysql_fetch_array($result);
		$max_fieldid = $row["fieldid"];
		$max_fieldid++;
	}
	else
	{
		$max_fieldid = "1";
	}
	$columnName = 'CF_'.$max_fieldid;
	//Assigning the table Name
	$tableName ='';
	if($fldmodule == 'Leads')
	{
		$tableName='leadcf';
	}
	elseif($fldmodule == 'Accounts')
	{

		$tableName='accountcf';
	}
	elseif($fldmodule == 'Contacts')
	{

		$tableName='contactcf';
	}
	elseif($fldmodule == 'Opportunities')
	{
		$tableName='opportunitycf';
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
		$type = "varchar(".$fldlength.")";
	}
	elseif($fldType == 'Number')
	{
		$uitype = 7;

		$type="double(".$fldlength.",".$decimal.")";	
	}
	elseif($fldType == 'Percent')
	{
		$uitype = 9;
		$type="double(".$fldlength.",".$decimal.")";
	}
	elseif($fldType == 'Currency')
	{
		$uitype = 3;
		$type="double(".$fldlength.",".$decimal.")";
	}
	elseif($fldType == 'Date')
	{
		$uitype = 5;
		$type = "date";
	}
	elseif($fldType == 'Email')
	{
		$uitype = 13;
		$type = "varchar(50)";
	}
	elseif($fldType == 'Phone')
	{
		$uitype = 11;
		$type = "varchar(30)";
	}
	elseif($fldType == 'Picklist')
	{
		$uitype = 15;
		$type = "varchar(255)";
	}
	// No Decimal Pleaces Handling


	//Inserting Value into Custom Field Table
	$query = "insert into customfields values('','".$columnName."','".$tableName."',2,".$uitype.",'".$fldlabel."','','".$fldmodule."')";
	mysql_query($query);
	//Altering the tables
	$query = "ALTER TABLE ".$tableName." ADD COLUMN ".$columnName." ".$type;
	mysql_query($query);
	if($fldType == 'Picklist')
	{
		// Creating the PickList Table and Populating Values
		$query = "create table ".$fldmodule."_".$columnName." (".$columnName." varchar(255) NOT NULL)";
		mysql_query($query);
		$fldPickList =  $_REQUEST['fldPickList'];
		$pickArray = explode("\n",$fldPickList);
		$count = count($pickArray);
		for($i = 0; $i < $count; $i++)
		{
			$pickArray[$i] = trim($pickArray[$i]);
			if($pickArray[$i] != '')
			{
				$query = "insert into ".$fldmodule."_".$columnName." values('".$pickArray[$i]."')";
				mysql_query($query);
			}
		}
	}
	header("Location:index.php?module=Settings&action=CustomFieldList&fld_module=".$fldmodule);
}
?>
