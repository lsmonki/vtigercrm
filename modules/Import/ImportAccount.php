<?php
/*********************************************************************************
 * The contents of this file are subject to the SugarCRM Public License Version 1.1.2
 * ("License"); You may not use this file except in compliance with the
 * License. You may obtain a copy of the License at http://www.sugarcrm.com/SPL
 * Software distributed under the License is distributed on an  "AS IS"  basis,
 * WITHOUT WARRANTY OF ANY KIND, either express or implied. See the License for
 * the specific language governing rights and limitations under the License.
 * The Original Code is:  SugarCRM Open Source
 * The Initial Developer of the Original Code is SugarCRM, Inc.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.;
 * All Rights Reserved.
 * Contributor(s): ______________________________________.
 ********************************************************************************/
/*********************************************************************************
 * $Header$
 * Description:  Defines the Account SugarBean Account entity with the necessary
 * methods and variables.
 ********************************************************************************/

include_once('config.php');
require_once('include/logging.php');
require_once('database/DatabaseConnection.php');
require_once('data/SugarBean.php');
require_once('modules/Contacts/Contact.php');
require_once('modules/Opportunities/Opportunity.php');
require_once('modules/Cases/Case.php');
require_once('modules/Calls/Call.php');
require_once('modules/Notes/Note.php');
require_once('modules/Emails/Email.php');
require_once('modules/Accounts/Account.php');

global $app_list_strings;

// Account is used to store account information.
class ImportAccount extends Account {
	 var $db;

	// these are fields that may be set on import
	// but are to be processed and incorporated
	// into fields of the parent class


	// This is the list of fields that are required.
	var $required_fields =  array("name"=>1);
	
	// This is the list of the functions to run when importing
	var $special_functions =  array(
	"add_billing_address_streets"
	,"add_shipping_address_streets"
	,"fix_website"
	,"add_industry"
	,"add_type"
	 );


	function fix_website()
	{
		if ( isset($this->website) &&
			preg_match("/^http:\/\//",$this->website) )
		{
			$this->website = substr($this->website,7);
		}	
	}

	
	function add_industry()
	{
		if ( isset($this->industry) &&
			! isset( $app_list_strings['industry_dom'][$this->industry]))
		{
			unset($this->industry);
		}	
	}

	function add_type()
	{
		if ( isset($this->type) &&
			! isset($app_list_strings['account_type_dom'][$this->type]))
		{
			unset($this->type);
		}	
	}

	function add_billing_address_streets() 
	{ 
		if ( isset($this->billing_address_street_2)) 
		{ 
			$this->billing_address_street .= 
				" ". $this->billing_address_street_2; 
		} 

		if ( isset($this->billing_address_street_3)) 
		{  
			$this->billing_address_street .= 
				" ". $this->billing_address_street_3; 
		} 
		if ( isset($this->billing_address_street_4)) 
		{  
			$this->billing_address_street .= 
				" ". $this->billing_address_street_4; 
		}
	}

	function add_shipping_address_streets() 
	{ 
		if ( isset($this->shipping_address_street_2)) 
		{ 
			$this->shipping_address_street .= 
				" ". $this->shipping_address_street_2; 
		} 

		if ( isset($this->shipping_address_street_3)) 
		{  
			$this->shipping_address_street .= 
				" ". $this->shipping_address_street_3; 
		} 

		if ( isset($this->shipping_address_street_4)) 
		{  
			$this->shipping_address_street .= 
				" ". $this->shipping_address_street_4; 
		} 
	}


	// This is the list of fields that are importable.
	// some if these do not map directly to database columns
	var $importable_fields = Array(
		"id"=>1
		,"name"=>1
		,"website"=>1
		,"industry"=>1
		,"type"=>1
		,"ticker_symbol"=>1
		,"parent_name"=>1
		,"employees"=>1
		,"ownership"=>1
		,"phone_office"=>1
		,"phone_fax"=>1
		,"phone_alternate"=>1
		,"email1"=>1
		,"email2"=>1
		,"rating"=>1
		,"sic_code"=>1
		,"annual_revenue"=>1
		,"billing_address_street"=>1
		,"billing_address_street_2"=>1
		,"billing_address_street_3"=>1
		,"billing_address_street_4"=>1
		,"billing_address_city"=>1
		,"billing_address_state"=>1
		,"billing_address_postalcode"=>1
		,"billing_address_country"=>1
		,"shipping_address_street"=>1
		,"shipping_address_street_2"=>1
		,"shipping_address_street_3"=>1
		,"shipping_address_street_4"=>1
		,"shipping_address_city"=>1
		,"shipping_address_state"=>1
		,"shipping_address_postalcode"=>1
		,"shipping_address_country"=>1
		,"description"=>1
		);


	function ImportAccount() {
		$this->log = LoggerManager::getLogger('import_account');
		$this->db = new PearDatabase();
	}

}



?>
