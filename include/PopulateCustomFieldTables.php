<?php
/*********************************************************************************
** The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 ********************************************************************************/
require_once('database/DatabaseConnection.php');
function create_custom_field_tables () 
{
		$query = 'CREATE TABLE customfieldtypemapping ( ';
		$query .='uitype int(10) NOT NULL';
		$query .=', typdesc varchar(255) NOT NULL';
	        $query .=', PRIMARY KEY ( uitype ) )';

		mysql_query($query);
		echo ("Table customfieldtypemapping created");
		echo ('<BR>');

		mysql_query("insert into customfieldtypemapping values(1,'Text Field not mandatory')");
		mysql_query("insert into customfieldtypemapping values(2,'Text Field mandatory')");
		mysql_query("insert into customfieldtypemapping values(3,'Currency not mandatory')");
		mysql_query("insert into customfieldtypemapping values(4,'Currency mandatory')");
		mysql_query("insert into customfieldtypemapping values(5,'Date not mandatory')");
		mysql_query("insert into customfieldtypemapping values(6,'Date mandatory')");
		mysql_query("insert into customfieldtypemapping values(7,'Number not mandatory')");
		mysql_query("insert into customfieldtypemapping values(8,'Number mandatory')");
		mysql_query("insert into customfieldtypemapping values(9,'Percent not mandatory')");
		mysql_query("insert into customfieldtypemapping values(10,'Percent mandatory')");
		mysql_query("insert into customfieldtypemapping values(11,'Phone not mandatory')");
		mysql_query("insert into customfieldtypemapping values(12,'Phone mandatory')");
		mysql_query("insert into customfieldtypemapping values(13,'email not mandatory')");
		mysql_query("insert into customfieldtypemapping values(14,'email mandatory')");
		mysql_query("insert into customfieldtypemapping values(15,'Picklist not mandatory')");
		mysql_query("insert into customfieldtypemapping values(16,'Picklist mandatory')");
			

		$query = 'CREATE TABLE customfields ( ';
		$query .='fieldid int(10) NOT NULL default \'0\' auto_increment';
		$query .=', column_name varchar(100) NOT NULL';
		$query .=', table_name varchar(50) NOT NULL';
		$query .=', generatedtype int(10) NOT NULL';
		$query .=', uitype int(10) NOT NULL';
		$query .=', fieldlabel varchar(100) NOT NULL';
		$query .=', readonly tinyint(1) NOT NULL default \'0\'';
		$query .=', module varchar(50) NOT NULL';
	        $query .=', PRIMARY KEY ( fieldid ) ';
	        $query .=', UNIQUE KEY CF_UK0( column_name ) ';
	        $query .=', KEY CF_KEY1 ( uitype ) ';
	        $query .=', CONSTRAINT CF_FK FOREIGN KEY ( uitype ) REFERENCES customfieldtypemapping (uitype) ON DELETE CASCADE )';

		mysql_query($query);

		echo ("Table customfields created");
                echo ('<BR>');

		$query = 'CREATE TABLE  leadcf( ';
		$query .='leadid varchar(36) NOT NULL';
	        $query .=', PRIMARY KEY ( leadid ) ';
	        $query .=', CONSTRAINT LCF_FK FOREIGN KEY ( leadid ) REFERENCES leads (id) ON DELETE CASCADE )';
		mysql_query($query);
		echo ("Table leadcf created");
                echo ('<BR>');

		$query = 'CREATE TABLE  accountcf( ';
		$query .='accountid varchar(36) NOT NULL';
	        $query .=', PRIMARY KEY ( accountid ) ';
	        $query .=', CONSTRAINT ACF_FK FOREIGN KEY ( accountid ) REFERENCES accounts (id) ON DELETE CASCADE )';
		mysql_query($query);
		echo ("Table accountcf created");
                echo ('<BR>');

		$query = 'CREATE TABLE  contactcf( ';
		$query .='contactid varchar(36) NOT NULL';
	        $query .=', PRIMARY KEY ( contactid ) ';
	        $query .=', CONSTRAINT CCF_FK FOREIGN KEY ( contactid ) REFERENCES contacts (id) ON DELETE CASCADE )';
		mysql_query($query);
		echo ("Table contactcf created");
                echo ('<BR>');	

		$query = 'CREATE TABLE  opportunitycf( ';
		$query .='opportunityid varchar(36) NOT NULL';
	        $query .=', PRIMARY KEY ( opportunityid ) ';
	        $query .=', CONSTRAINT PCF_FK FOREIGN KEY ( opportunityid ) REFERENCES opportunities (id) ON DELETE CASCADE )';
		mysql_query($query);
		echo ("Table opportunitycf created");
                echo ('<BR>');
	

	// exception handling logic here if the table can't be created.

}
?>
