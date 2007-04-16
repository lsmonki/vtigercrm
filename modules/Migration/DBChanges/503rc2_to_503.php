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


//5.0.3 RC2 to 5.0.3 database changes - added on 29-03-07
global $adb;

$migrationlog->debug("\n\nDB Changes from 5.0.3RC2 to 5.0.3 -------- Starts \n\n");

$query_array = Array(
			//description field added in vtiger_inventoryproductrel
			"alter table vtiger_inventoryproductrel add column description TEXT default NULL after comment",

			//size increased for comment field
			"alter table vtiger_inventoryproductrel change comment comment varchar(250)",

			//size increased for tax and discount related fields in Inventory modules
			"alter table vtiger_purchaseorder change salestax salestax decimal(25,3)",
			"alter table vtiger_purchaseorder change adjustment adjustment decimal(25,3)",
			"alter table vtiger_purchaseorder change salescommission salescommission decimal(25,3)",
			"alter table vtiger_purchaseorder change exciseduty exciseduty decimal(25,3)",
			"alter table vtiger_purchaseorder change total total decimal(25,3)",
			"alter table vtiger_purchaseorder change subtotal subtotal decimal(25,3)",
			"alter table vtiger_purchaseorder change discount_percent discount_percent decimal(25,3)",
			"alter table vtiger_purchaseorder change discount_amount discount_amount decimal(25,3)",
			"alter table vtiger_purchaseorder change s_h_amount s_h_amount decimal(25,3)",

			"alter table vtiger_salesorder change salestax salestax decimal(25,3)",
			"alter table vtiger_salesorder change adjustment adjustment decimal(25,3)",
			"alter table vtiger_salesorder change salescommission salescommission decimal(25,3)",
			"alter table vtiger_salesorder change exciseduty exciseduty decimal(25,3)",
			"alter table vtiger_salesorder change total total decimal(25,3)",
			"alter table vtiger_salesorder change subtotal subtotal decimal(25,3)",
			"alter table vtiger_salesorder change discount_percent discount_percent decimal(25,3)",
			"alter table vtiger_salesorder change discount_amount discount_amount decimal(25,3)",
			"alter table vtiger_salesorder change s_h_amount s_h_amount decimal(25,3)",

			"alter table vtiger_invoice change salestax salestax decimal(25,3)",
			"alter table vtiger_invoice change adjustment adjustment decimal(25,3)",
			"alter table vtiger_invoice change salescommission salescommission decimal(25,3)",
			"alter table vtiger_invoice change exciseduty exciseduty decimal(25,3)",
			"alter table vtiger_invoice change total total decimal(25,3)",
			"alter table vtiger_invoice change subtotal subtotal decimal(25,3)",
			"alter table vtiger_invoice change discount_percent discount_percent decimal(25,3)",
			"alter table vtiger_invoice change discount_amount discount_amount decimal(25,3)",
			"alter table vtiger_invoice change s_h_amount s_h_amount decimal(25,3)",

			"alter table vtiger_quotes change subtotal subtotal decimal(25,3)",
			"alter table vtiger_quotes change tax tax decimal(25,3)",
			"alter table vtiger_quotes change adjustment adjustment decimal(25,3)",
			"alter table vtiger_quotes change total total decimal(25,3)",
			"alter table vtiger_quotes change discount_percent discount_percent decimal(25,3)",
			"alter table vtiger_quotes change discount_amount discount_amount decimal(25,3)",
			"alter table vtiger_quotes change s_h_amount s_h_amount decimal(25,3)",

			"alter table vtiger_pricebookproductrel change listprice listprice decimal(25,3)",

			"alter table vtiger_inventoryproductrel change listprice listprice decimal(25,3)",

			"alter table vtiger_products change unit_price unit_price decimal(25,2)",

			"alter table vtiger_campaign change expectedrevenue expectedrevenue decimal(25,3)",
			"alter table vtiger_campaign change budgetcost budgetcost decimal(25,3)",
			"alter table vtiger_campaign change actualcost actualcost decimal(25,3)",
			"alter table vtiger_campaign change expectedroi expectedroi decimal(25,3)",
			"alter table vtiger_campaign change actualroi actualroi decimal(25,3)",

			//unwanted currency column removed from lead and contact
			"alter table vtiger_leadsubdetails drop column currency",

			"alter table vtiger_contactdetails drop column currency",

			//Amount and probability field datatype modified.(http://forums.vtiger.com/viewtopic.php?t=14006)

			"alter table vtiger_potential change probability probability decimal(5,2)",
			"alter table vtiger_potential change amount amount decimal(12,2)",
			"alter table vtiger_opportunitystage change probability probability decimal(5,2)",
			"alter table vtiger_dealintimation change dealprobability dealprobability decimal(5,2)",
			"alter table vtiger_potstagehistory change probability probability decimal(5,2)",
			"alter table vtiger_potstagehistory change amount amount decimal(12,2)",

			//Homepage order has been changed 
			"update vtiger_users set homeorder = 'HDB,ALVT,PLVT,QLTQ,CVLVT,HLT,OLV,GRT,OLTSO,ILTI,MNL,OLTPO,LTFAQ'",

		    );

foreach($query_array as $query)
{
	ExecuteQuery($query);
}

$migrationlog->debug("\n\nDB Changes from 5.0.3RC2 to 5.0.3 -------- Ends \n\n");



?>
