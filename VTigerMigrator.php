<?php

include('include/database/PearDatabase.php');
include('include/utils.php');

class VTigerMigrator
{
       	var $oldconn;
	var $newconn;       

  function setupDBConnections()
  {
		  
    require_once('migrator_connection.php');
	require_once('config.php');
    $this->oldconn = new PearDatabase("mysql",$mysql_host_name_old.":".$mysql_port_old,"vtigercrm3_2",$mysql_username_old,$mysql_password_old);

    $this->oldconn->connect();
   $this->newconn = new PearDatabase("mysql",$dbconfig['db_host_name'],"vtigercrm4",$dbconfig['db_user_name'],$dbconfig['db_password']);
   // $this->newconn = new PearDatabase("mysql","srinivasan:3306","vtigercrm4_beta","root","");
    $this->newconn->connect();
	  
  }

  function preliminarySteps()
  {
    
  }
  
  function proceedStandardMigration()
  {
    
  }
  
  function startMigration()
  {
    $this->setupDBConnections();
    $this->preliminarySteps();
    $this->proceedIdMapping();
    $this->proceedStandardMigration();
    $this->proceedRelationalMigration();
    $this->proceedConstantsMigration();
    $this->clearMigration();
    $this->logMigration();
  }
  
  
  function clearMigration()
  {
    
    
  }


  function logMigration()
  {
    
    
  }



 
  function VTigerMigrator()
  {
  
  }


 
 
}


?>
