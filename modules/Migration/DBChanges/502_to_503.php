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


//5.0.3 database changes - added on 05-01-07
global $adb;

$query_array = Array(
			"alter table vtiger_entityname add column entityidcolumn varchar(150) NOT NULL",

			"update vtiger_entityname set entityidcolumn='leadid' where tabid=7",
			"update vtiger_entityname set entityidcolumn='account_id' where tabid=6",
			"update vtiger_entityname set entityidcolumn='contact_id' where tabid=4",
			"update vtiger_entityname set entityidcolumn='potential_id' where tabid=2",
			"update vtiger_entityname set entityidcolumn='notesid' where tabid=8",
			"update vtiger_entityname set entityidcolumn='ticketid' where tabid=13",
			"update vtiger_entityname set entityidcolumn='activityid' where tabid=9",
			"update vtiger_entityname set entityidcolumn='activityid' where tabid=10",
			"update vtiger_entityname set entityidcolumn='product_id' where tabid=14",
			"update vtiger_entityname set entityidcolumn='id' where tabid=29",
			"update vtiger_entityname set entityidcolumn='invoiceid' where tabid=23",
			"update vtiger_entityname set entityidcolumn='quote_id' where tabid=20",
			"update vtiger_entityname set entityidcolumn='purchaseorderid' where tabid=21",
			"update vtiger_entityname set entityidcolumn='salesorder_id' where tabid=22",
			"update vtiger_entityname set entityidcolumn='vendor_id' where tabid=18",
			"update vtiger_entityname set entityidcolumn='pricebookid' where tabid=19",
			"update vtiger_entityname set entityidcolumn='campaignid' where tabid=26",
			"update vtiger_entityname set entityidcolumn='id' where tabid=15",
			
			"update vtiger_field set fieldlabel='Part Number' where tabid=14 and fieldname='productcode'",


			"alter table vtiger_tab change customized customized integer(19)",
			"alter table vtiger_tab add column ownedby integer(19)",
			"ALTER TABLE vtiger_blocks ADD CONSTRAINT fk_1_vtiger_blocks FOREIGN KEY (tabid) REFERENCES vtiger_tab(tabid) ON DELETE CASCADE",
			"alter table vtiger_crmentity modify setype varchar(25)",

			"ALTER TABLE vtiger_customview ADD  INDEX customview_entitytype_idx  (entitytype)",
			"ALTER TABLE vtiger_customview ADD CONSTRAINT fk_1_vtiger_customview FOREIGN KEY (entitytype) REFERENCES vtiger_tab (name) ON DELETE CASCADE",

			"alter table vtiger_parenttabrel change parenttabid parenttabid integer(19)",
			"alter table vtiger_parenttabrel change tabid tabid integer(19)",

			"ALTER TABLE vtiger_parenttabrel ADD CONSTRAINT fk_1_vtiger_parenttabrel FOREIGN KEY (tabid) REFERENCES vtiger_tab(tabid) ON DELETE CASCADE",

			"ALTER TABLE vtiger_parenttabrel ADD CONSTRAINT fk_2_vtiger_parenttabrel FOREIGN KEY (parenttabid) REFERENCES vtiger_parenttab(parenttabid) ON DELETE CASCADE",

			"ALTER TABLE vtiger_entityname ADD CONSTRAINT fk_1_vtiger_entityname FOREIGN KEY (tabid) REFERENCES vtiger_tab(tabid) ON DELETE CASCADE",

			"alter table vtiger_parenttab engine=InnoDB",

			"update vtiger_tab set customized=0",
			"update vtiger_tab set ownedby=1",
			"update vtiger_tab set ownedby=0 where tabid in (2,4,6,7,9,13,16,20,21,22,23,26)",
			   
		    );

foreach($query_array as $query)
{
	ExecuteQuery($query);

	/*if($adb->query($query))	$success[] = $query;	else	$failure[] = $query;*/
}


ExecuteQuery("ALTER TABLE vtiger_users MODIFY user_password varchar(32)");

//Changes related to Product - Lead/Account/Contact/Potential relationship - Mickie - 13-01-2007
ExecuteQuery("delete from vtiger_field where tabid=14 and fieldname in ('parent_id','contact_id')");
ExecuteQuery("alter table vtiger_products drop column contactid");

ExecuteQuery("insert into vtiger_relatedlists values(".$adb->getUniqueID('vtiger_relatedlists').",14,7,'get_leads',9,'Leads',0)");
ExecuteQuery("insert into vtiger_relatedlists values(".$adb->getUniqueID('vtiger_relatedlists').",14,6,'get_accounts',10,'Accounts',0)");
ExecuteQuery("insert into vtiger_relatedlists values(".$adb->getUniqueID('vtiger_relatedlists').",14,4,'get_contacts',11,'Contacts',0)");
ExecuteQuery("insert into vtiger_relatedlists values(".$adb->getUniqueID('vtiger_relatedlists').",14,2,'get_opportunities',12,'Potentials',0)");

ExecuteQuery("alter table vtiger_seproductsrel add column setype varchar(100)");


//echo "<br><font color='red'>&nbsp; 5.0.2 ==> 5.0.3 Database changes has been done.</font><br>";





?>
