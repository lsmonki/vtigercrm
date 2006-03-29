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

include_once('config.php');
require_once('include/logging.php');
require_once('data/SugarBean.php');
require_once('include/logging.php');
require_once('include/ListView/ListView.php');
require_once('include/database/PearDatabase.php');

// Contact is used to store customer information.
class LoginHistory extends SugarBean {
	var $log;
	var $db;

	// Stored fields
	var $login_id;
	var $user_name;
	var $user_ip;
	var $login_time;
	var $logout_time;
	var $status;
	var $module_name = "Users";

	var $table_name = "loginhistory";

	var $object_name = "LoginHistory";
	
	var $new_schema = true;

	var $column_fields = Array("id"
		,"login_id"
		,"user_name"
		,"user_ip"
		,"login_time"
		,"logout_time"
		,"status"
		);

	function LoginHistory() {
		$this->log = LoggerManager::getLogger('loginhistory');
		$this->db = new PearDatabase();
	}
	
	// This is the list of fields that are in the lists.
	var $list_fields = Array('login_id', 'user_name', 'user_ip', 'login_time', 'logout_time', 'status');	
		
	var $default_order_by = "login_id";

	
	/** Records the Login info */
	function user_login(&$usname,&$usip,&$intime)
	{
		$query = "Insert into loginhistory values (null,'$usname','$usip',null,".$this->db->formatDate($intime).",'Signedin')";
		$result = $this->db->query($query)
                        or die("MySQL error: ".mysql_error());
		
		return $result;
	}
	
	function user_logout(&$usname,&$usip,&$outtime)
	{
		$logid_qry = "SELECT max(login_id) login_id from loginhistory where user_name='$usname' and user_ip='$usip'";
		$result = $this->db->query($logid_qry);
		$loginid = $this->db->query_result($result,0,"login_id");
		if ($loginid == '')
                {
                        return;
                }
		// update the user login info.
		$query = "Update loginhistory set logout_time =".$this->db->formatDate($outtime).", status='Signedoff' where login_id = $loginid";
		$result = $this->db->query($query)
                        or die("MySQL error: ".mysql_error());
	}

  	function create_list_query(&$order_by, &$where)
  	{
		// Determine if the account name is present in the where clause.
		global $current_user;
		$query = "SELECT user_name,user_ip,".$this->db->getDBDateString("login_time")." login_time,".$this->db->getDBDateString("logout_time")." logout_time,status FROM $this->table_name ";
		if($where != "")
		{
			if(!is_admin($current_user))
			$where .=" and user_name = '".$current_user->user_name."'";
			$query .= "where ($where)";
		}
		else
		{
			if(!is_admin($current_user))
			$query .= "where user_name = '".$current_user->user_name."'";
		}
		
		if(!empty($order_by))
			$query .= " ORDER BY $order_by";

                return $query;
	}

}



?>
