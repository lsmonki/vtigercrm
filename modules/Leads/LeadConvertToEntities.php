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

require_once('database/DatabaseConnection.php');

//Getting the Parameters from the ConvertLead Form
$id = $_REQUEST["record"];
$module = $_REQUEST["module"];
$assigned_user_id = $_REQUEST["assigned_user_id"];
$createpotential = $_REQUEST["createpotential"];
$potential_name = $_REQUEST["potential_name"];
$close_date = $_REQUEST["closedate"];
$current_user_id = $_REQUEST["current_user_id"];

//Retreiving the lead info from the Database
$sql = "SELECT * from leads where id='".$id."'";
$result = mysql_query($sql);
$row = mysql_fetch_array($result);

//Inserting data in accounts table

$account_id = create_guid();
$date_entered;
$date_modified;

$date_entered = date('YmdHis');
$date_modified = date('YmdHis');

$sql_insert_account = "INSERT INTO accounts (id,date_entered,date_modified,modified_user_id,assigned_user_id,name,industry,annual_revenue,phone_fax,billing_address_street,billing_address_city,billing_address_state,billing_address_postalcode,billing_address_country,description,rating,phone_office,email1,website,employees) VALUES ('$account_id','$date_entered','$date_modified','$current_user_id','$assigned_user_id','" .$row["company"] ."','" .$row["industry"] ."','" .$row["annual_revenue"] ."','" .$row["fax"] ."','" .$row["address_street"]. "','" .$row["address_city"] ."','" .$row["address_state"] ."','" .$row["address_postalcode"] ."','" .$row["address_country"] ."','" .$row["description"] ."','" .$row["rating"] ."','" .$row["phone"] ."','" .$row["email"] ."','" .$row["website"] ."','" .$row["employees"] ."')";

mysql_query($sql_insert_account);

//Inserting data into contacts table
$contact_id = create_guid();

$date_entered = date('YmdHis');
$date_modified = date('YmdHis');

$sql_insert_contact = "INSERT INTO contacts (id,date_entered,date_modified,modified_user_id,assigned_user_id,salutation,first_name,last_name,lead_source,title,phone_mobile,phone_work,phone_fax,email1,primary_address_street,primary_address_city,primary_address_state,primary_address_postalcode,primary_address_country,description) VALUES ('$contact_id','$date_entered','$date_modified','$current_user_id','$assigned_user_id','" .$row["salutation"] ."','" .$row["first_name"] ."','" .$row["last_name"] ."','" .$row["lead_source"] ."','" .$row["designation"]. "','" .$row["mobile"] ."','" .$row["phone"] ."','" .$row["fax"] ."','" .$row["email"] ."','" .$row["address_street"] ."','" .$row["address_city"] ."','" .$row["address_state"] ."','" .$row["address_postalcode"] ."','" .$row["address_country"] ."','" .$row["description"] ."')";

mysql_query($sql_insert_contact);

//Inserting data into accounts_contacts table

$accounts_contacts_id = create_guid();
$sql_insert_accounts_contacts = "INSERT INTO accounts_contacts (id,contact_id,account_id) VALUES ('$accounts_contacts_id','$contact_id','$account_id')";
mysql_query($sql_insert_accounts_contacts);

//Checking for Potential and inserting data into opportunities, accounts_opportunities, opportunities_contacts
if(! isset($createpotential) || ! $createpotential == "on")
{
	$opp_id = create_guid();

	$date_entered = date('YmdHis');
	$date_modified = date('YmdHis');

	$sql_insert_opp = "INSERT INTO opportunities (id,date_entered,date_modified,modified_user_id,assigned_user_id,name,lead_source,date_closed,description) VALUES ('$opp_id','$date_entered','$date_modified','$current_user_id','$assigned_user_id','$potential_name','" .$row["lead_source"] ."','$close_date','" .$row["description"] ."')";

	mysql_query($sql_insert_opp);

	//Inserting data into accounts_contacts table

	$accounts_opportunities_id = create_guid();
	$sql_insert_accounts_opportunities = "INSERT INTO accounts_opportunities (id,opportunity_id,account_id) VALUES ('$accounts_opportunities_id','$opp_id','$account_id')";
	mysql_query($sql_insert_accounts_opportunities);
}

//Deleting from the tracker
	$sql_delete_tracker= "DELETE from tracker where item_id='" .$id ."'";
	mysql_query($sql_delete_tracker);

//Updating the deleted status
	$sql_update_converted = "UPDATE leads SET converted = 1 where id='" .$id ."'";
	mysql_query($sql_update_converted); 

header("Location: index.php?action=DetailView&module=Accounts&record=$account_id");

?>
