<?php

include('include/database/PearDatabase.php');
include('include/utils.php');

class DataMigration 
{
       	var $oldconn;
	var $newconn;       

  function setupDBConnections()
  {
		  
    require_once('migrator_connection.php');
	require_once('config.php');
    $this->oldconn = new PearDatabase("mysql",$mysql_host_name_old.":".$mysql_port_old,"vtigercrm_4_0_bkp",$mysql_username_old,$mysql_password_old);

    $this->oldconn->connect();
   
	//$this->newconn = new PearDatabase("mysql",$dbconfig['db_host_name'],"vtigercrm4",$dbconfig['db_user_name'],$dbconfig['db_password']);
    //$this->newconn->connect();
	  
  }


  function preliminarySteps()
  {


    echo '<br>+++++++++++++++++++++++++++++++++++++<br>';
    echo '<br><br>';
    echo '<br>++PRELIMINARY STEPS FOR DATA MIGRATION INITIATED++<br>';
    echo '<br><br>';
    echo '<br>+++++++++++++++++++++++++++++++++++<br>';

    echo '<br> set time limit to 600 <br>';
    set_time_limit(0);
    ini_set("display_errors",'0');

   }

  function makechanges()
  {
	//Changes to User table
	$sql1 = "ALTER TABLE users CHANGE user_name user_name VARCHAR(100)";
	$this->oldconn->query($sql1);

	$sql2 = "ALTER TABLE users CHANGE user_password user_password VARCHAR(100)";
	$this->oldconn->query($sql2);

	$sql3 = "ALTER TABLE users ADD date_format VARCHAR(30) AFTER weekstart";
	$this->oldconn->query($sql3);

	$sql4 = "update users set date_format='yyyy-mm-dd'";
	$this->oldconn->query($sql4);

	//Changes to Accounts Table
	$sql5 = "ALTER TABLE account CHANGE email1 email1 VARCHAR(100)";
	$this->oldconn->query($sql5);
        $sql6 = "ALTER TABLE account CHANGE email2 email2 VARCHAR(100)";
	$this->oldconn->query($sql6);

	//Changes to activity table
	$sql7 = "show columns from activity like 'eventstatus'";
	$res7 = $this->oldconn->query($sql7);
	if($this->oldconn->num_rows($res7) == 0)
	{
		$sql8 = "ALTER TABLE activity ADD eventstatus VARCHAR(100) AFTER status";
		$this->oldconn->query($sql8);
		//Insert into field table
		$fieldid = $this->oldconn->getUniqueID("field");
		$sql81 = "insert into field values (16,".$fieldid.",'eventstatus','activity',1,'15','eventstatus','Status',1,0,0,100,9,1,1,'V~O')";
		$this->oldconn->query($sql81);
		$sql82 = "insert into field values(9,".$this->oldconn->getUniqueID("field").",'eventstatus','activity',1,'15','eventstatus','Status',1,0,0,100,9,1,1,'V~O')";
		$this->oldconn->query($sql82);
		//Insert into profile2 field table
		$sql83="select * from profile";
		$res_83=$this->oldconn->query($sql83);
		$num_83=$this->oldconn->num_rows($res_83);
		for($i=0; $i<$num_83; $i++)
		{
			$prof_id = $this->oldconn->query_result($res_83,$i,'profileid');
			$sql_84 = "insert into profile2field values(".$prof_id.",16,".$fieldid.",0,1)";
			$this->oldconn->query($sql_84);
		}

	}

	//Create Table Currency Info
	$sql9 = "CREATE TABLE currency_info (
		  currency_name varchar(100) NOT NULL default '',
		  currency_code varchar(100) default NULL,
		  currency_symbol varchar(30) default NULL,
		  PRIMARY KEY  (currency_name)
		) TYPE=InnoDB";
	$this->oldconn->query($sql9);

	$sql10 = "INSERT INTO currency_info VALUES ('U.S Dollar','USD','$')";
	$this->oldconn->query($sql10);

	//check for login history table
	$sql11 = "show columns from loginhistory like '%_time'";
	$res11 =  $this->oldconn->query($sql11);
	$fld_name = $this->oldconn->query_result($res11,0,'field');
	echo '>>>>>>>>>>>>>>>>>>>>>>>>>>>>> '.$fld_name;
	if($fld_name == 'login_time')
	{
		$sql12 = "alter table loginhistory change logout_time lo_time timestamp(14)";
		$this->oldconn->query($sql12);
		$sql13 = "alter table loginhistory change login_time logout_time timestamp(14)";
		$this->oldconn->query($sql13);
		$sql14 = "alter table loginhistory change lo_time login_time timestamp(14)";
		$this->oldconn->query($sql14);
	}

	//Updating field table
	$sql15 = "update field set typeofdata='V~M',fieldname='ticket_title' where tabid = 13 and fieldname='title'";
	$this->oldconn->query($sql15);
	$sql16 = "update field set fieldname='subject' where tabid = 10 and fieldname='name'";
	$this->oldconn->query($sql16);

	/* Changes made for 4.0.1 release */
	$sql17 = "alter table products change unit_price unit_price decimal(11,2)";
	$this->oldconn->query($sql17);
	$sql18 = "alter table products change qty_per_unit qty_per_unit decimal(11,2)";
	$this->oldconn->query($sql18);
	$sql19 = "alter table products change weight weight decimal(11,3)";
	$this->oldconn->query($sql19);
	$sql20 = "alter table products change commissionrate commissionrate decimal(2,3)";
	$this->oldconn->query($sql20);

	$sql21 = "alter table emailtemplates add column templateid int(11) auto_increment Primary key NOT NULL";
	$this->oldconn->query($sql21);

	//Changes of uitype for created and modified time
	$sql24 = "update field set uitype=70 where fieldname='modifiedtime' and tablename='crmentity'";
	$this->oldconn->query($sql24);
	$sql25 = "update field set uitype=70 where fieldname='createdtime' and tablename='crmentity'";
	$this->oldconn->query($sql25);

	//Making the changes for amount related fields
	$sql26 = "update field set uitype=71 where fieldname='annual_revenue' and tabid='6'";
	$this->oldconn->query($sql26);
	$sql27 = "update field set uitype=71 where fieldname='annualrevenue' and tabid='7'";
	$this->oldconn->query($sql27);
	$sql28 = "update field set uitype=71 where fieldname='amount' and tabid='2'";
	$this->oldconn->query($sql28);
	$sql31 = "update field set uitype=71 where fieldname='unit_price' and tabid='14'";
	$this->oldconn->query($sql31);
	

	# Default Org Table
	$sql22= "CREATE TABLE def_org_field (
	  tabid int(10) default NULL,
	  fieldid int(19) default NULL,
	  visible int(19) default NULL,
	  readonly int(19) default NULL,
	  KEY idx_def_org_field (tabid,fieldid)
	) TYPE=InnoDB";
	$this->oldconn->query($sql22);
	$sql23 = "insert into def_org_field(tabid, fieldid,visible, readonly) (select tabid,fieldid,0,1 from profile2field group by fieldid)";
	$this->oldconn->query($sql23);	

  }	

  
  function startMigration()
  {
    $this->setupDBConnections();
    $this->preliminarySteps();
    $this->makechanges();	
    //$this->clearMigration();
    //$this->logMigration();
  }
  
 
}


?>
