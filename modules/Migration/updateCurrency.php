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


require_once("include/database/PearDatabase.php");
$conn = new PearDatabase();

$ajax_val = $_REQUEST['ajax'];

if($ajax_val == 1)
{
	$crate = $_REQUEST['crate'];
	$conn->println('conversion rate = '.$crate);
	
	$query = "update vtiger_currency_info set conversion_rate='".$_REQUEST['crate']."' where id=1";
	$result = $conn->query($query);

	//array should be id || vtiger_fieldname => vtiger_tablename
	$modules_array = Array(
				"accountid||annualrevenue"	=>	"account",
				
				"leadid||annualrevenue"		=>	"leaddetails",

				"potentialid||amount"		=>	"potential",

				"productid||unit_price"		=>	"products",

				"salesorderid||salestax"	=>	"salesorder",
				"salesorderid||adjustment"	=>	"salesorder",
				"salesorderid||total"		=>	"salesorder",
				"salesorderid||subtotal"	=>	"salesorder",

				"purchaseorderid||salestax"	=>	"purchaseorder",
				"purchaseorderid||adjustment"	=>	"purchaseorder",
				"purchaseorderid||total"	=>	"purchaseorder",
				"purchaseorderid||subtotal"	=>	"purchaseorder",

				"quoteid||tax"			=>	"quotes",
				"quoteid||adjustment"		=>	"quotes",
				"quoteid||total"		=>	"quotes",
				"quoteid||subtotal"		=>	"quotes",

				"invoiceid||salestax"		=>	"invoice",
				"invoiceid||adjustment"		=>	"invoice",
				"invoiceid||total"		=>	"invoice",
				"invoiceid||subtotal"		=>	"invoice",
			      );

	foreach($modules_array as $fielddetails => $table)
	{
		$temp = explode("||",$fielddetails);
		$id_name = $temp[0];
		$fieldname = $temp[1];

		$res = $conn->query("select $id_name, $fieldname from $table");
		$record_count = $conn->num_rows($res);
		
		for($i=0;$i<$record_count;$i++)
		{
			$recordid = $conn->query_result($res,$i,$id_name);
			$old_value = $conn->query_result($res,$i,$fieldname);

			//calculate the new value
			$new_value = $old_value/$crate;//convertToDollar($old_value,$crate);
			$conn->println("old value = $old_value && new value = $new_value");

			$update_query = "update $table set $fieldname='".$new_value."' where $id_name=$recordid";
			$update_result = $conn->query($update_query);
		}
	}
}


?>
