<?php

include('VTigerMigrator.php');
require_once('include/database/PearDatabase.php');

require_once('modules/Users/Security.php');
require_once('modules/Users/User.php');
require_once('data/CRMEntity.php');
require_once('modules/Leads/Lead.php');
require_once('modules/Accounts/Account.php');
require_once('modules/Activities/Activity.php');
require_once('modules/Contacts/Contact.php');
require_once('modules/Emails/Email.php');
require_once('modules/Potentials/Opportunity.php');
require_once('modules/Notes/Note.php');

require_once('include/utils.php');


/*
 *  File which does the actual implementation of the Migration
 *
 *
 */


class VTiger32to40Migrator extends VTigerMigrator
{
  var $sourcetablearray;
  
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

    $this->dropDataFromTables();
    $this->populateNeededData();

    $flds='oldid C(100),newid I(20) ,createdtime T, modifiedtime T, module C(30) ,assigned_user_id C(100),modified_user_id C(100),parent_id C(100),reports_to_id C(100),contact_id C(100),account_id C(100),deleted I(1)';
    $sqlarray = $this->newconn->createTable("Migrator", $flds,"innodb" );
  }



function getV4TablesList()
{
		
  //need corr method in PearDatabase
  $dbtables = $this->newconn->get_tables();
  return $dbtables;
  	
}



  function dropDataFromTables()
  {
    $v4tables_list = $this->getV4TablesList();
    $dropdownarray = Array("accountdepstatus","accountownership","accountrating","accountregion","accounttype","contacttype","leadstage","event_status","duration_minutes","opportunitystage","priority","businesstype","revenuetype","taskpriority","taskstatus","activitytype","usertype","faqcategories","ticketcategories","ticketpriorities","ticketstatus","activsubtype","downloadpurpose","durationhrs","durationmins","evaluationstatus","productcategory");
    foreach($v4tables_list as $key)
    {
      //if the table name has _seq, the let it pass
      if(strpos($key,"_seq"))
      {
        echo '<br> skipping table '.$key .' for truncation ';
        continue;
      }
      elseif(in_array($key,$dropdownarray))
      {
        echo '<br> skipping table '.$key .' as drop down  ';
        continue;
      }

      $sql = "delete from ".$key;
      echo '<br> deletion query is ..........'.$sql;
      $this->newconn->query($sql);
    }
  }



  function populateNeededData()
  {

    echo '<br> inside populateNeeededData <br>';
    //make a list of tables that you need to populate before the migration begins
    //field and the security related tables seem to be the only cases
    $sec = new Security();
    $sec->create_tables();
  }

  function proceedIdMapping()
  {
    echo '<br>+++++++++++++++++++++++++++++++++++++<br>';
    echo '<br><br>';
    echo '<br>++++++++++++++++IDMAPPING PROCESS INITIATED+++++++++++++++++++++<br>';
    echo '<br><br>';
    echo '<br>+++++++++++++++++++++++++++++++++++++<br>';

	  
    $sourcetablearray1=Array("users","leads","opportunities");
    $sourcetablearray2 = Array("calls","emails","meetings");
    $sourcetablearray3 = Array("notes","tasks");
    $sourcetablearray4 = Array("accounts","contacts");
    $sourcetablearray5 = Array("filestorage","users_last_import");
    
    foreach($sourcetablearray1 as $oldtable)
    {
      $module;
      if($oldtable == 'users')
      {
        $sql = "select id,date_entered,date_modified,modified_user_id,deleted from ".$oldtable;
        $module='Users';
      }
      elseif($oldtable == 'leads')
      {
        $sql = "select id,date_entered,date_modified,assigned_user_id,modified_user_id,deleted from ".$oldtable;
      }
      else
      {
        $sql = "select account_id,opportunities.id,date_entered,date_modified,assigned_user_id,modified_user_id,opportunities.deleted from ".$oldtable ." inner join accounts_opportunities on accounts_opportunities.opportunity_id = opportunities.id";
      }
      echo '<br> ' .$sql .'<br>';
      if($oldtable == 'leads')
      {
        $module = 'Leads';
      }
      elseif($oldtable == 'opportunities')
      {
        $module = 'Potentials';
      }

      
      $result = $this->oldconn->query($sql);
       echo '<BR>>>>>>>    '.$oldtable .'   count is           '.$this->oldconn->num_rows($result); 
      $count = $this->oldconn->num_rows($result); 
      if($count > 0)
      {
        while($row = $this->oldconn->fetchByAssoc($result))
        {
          if($oldtable == 'users')
          {
            $newid = $this->newconn->getUniqueID("users");
          }
          else
          {
            $newid = $this->newconn->getUniqueID("crmentity");
          }
         
          if($oldtable == 'users')
          {
            $sql = "insert into Migrator(oldid,newid,createdtime,modifiedtime,module,modified_user_id,deleted) values ('".$row["id"]."',".$newid.",'".$row["date_entered"]."','".$row["date_modified"]."','".$module."','".$row["modified_user_id"]."',".$row["deleted"] .")";
          }
          elseif($oldtable == 'opportunities')
          {
            $sql = "insert into Migrator(oldid,newid,createdtime,modifiedtime,module,assigned_user_id,modified_user_id,account_id,deleted) values ('".$row["id"]."',".$newid.",'".$row["date_entered"]."','".$row["date_modified"]."','".$module."','".$row["assigned_user_id"]."','".$row["modified_user_id"]."','".$row["account_id"]."',".$row["deleted"] .")";
           }
          else
          {
            $sql = "insert into Migrator(oldid,newid,createdtime,modifiedtime,module,assigned_user_id,modified_user_id,deleted) values ('".$row["id"]."',".$newid.",'".$row["date_entered"]."','".$row["date_modified"]."','".$module."','".$row["assigned_user_id"]."','".$row["modified_user_id"]."',".$row["deleted"] .")";
            
          }
            
          $this->newconn->query($sql);
        }
      }
      else
      {
        echo '<br> ############## no data available in table, proceeding to next table ###############  <br>';
      }
    }


    

    foreach($sourcetablearray2 as $oldtable)
    {
      $module;
      $sql = "select id,date_entered,date_modified,assigned_user_id,parent_id,deleted from ".$oldtable;
      //echo '<br> '.$sql  .'   <br> ';
      if($oldtable == 'calls')
      {
        $module = 'Events';
      }
      elseif($oldtable == 'emails')
      {
        $module = 'Emails';
      }
      elseif($oldtable == 'meetings')
      {
        $module = 'Events';
      }
      
      $result = $this->oldconn->query($sql);
      echo '<br> >>>>>>   '.$oldtable .' count is       '.$this->oldconn->num_rows($result); 
      $count = $this->oldconn->num_rows($result); 
      if($count > 0)
      {
        while($row = $this->oldconn->fetchByAssoc($result))
        {
          $newid = $this->newconn->getUniqueID("crmentity");
          
          $sql = "insert into Migrator(oldid,newid,createdtime,modifiedtime,module,assigned_user_id,parent_id,deleted) values('".$row["id"]."',".$newid.",'".$row["date_entered"]."','".$row["date_modified"]."','".$module."','".$row["assigned_user_id"]."','".$row["parent_id"]."',".$row["deleted"] .")";
          //echo '<br>2  '.$sql;
          $this->newconn->query($sql);
        }
      }
      else
      {
        echo '<br> ############## no data available in table, proceeding to next table ###############  <br>';
      }
    }

    
    foreach($sourcetablearray3 as $oldtable)
    {
$module;
  if($oldtable == 'notes')
      {
      $sql = "select id,date_entered,date_modified,parent_id,contact_id,deleted from ".$oldtable;
      }
      else
      {
      $sql = "select id,assigned_user_id,date_entered,date_modified,parent_id,contact_id,deleted from ".$oldtable;
       }
     
      //echo '<br> '.$sql  .'   <br> ';
      $result = $this->oldconn->query($sql);
      echo '<br> >>>>>>  '.$oldtable .' count is       '.$this->oldconn->num_rows($result); 
      $count = $this->oldconn->num_rows($result); 
      if($oldtable == 'notes')
      {
        $module = 'Notes';
      }
      elseif($oldtable == 'tasks')
      {
        $module = 'Task';
      }
      
      if($count > 0)
      {
        while($row = $this->oldconn->fetchByAssoc($result))
        {
          $newid = $this->newconn->getUniqueID("crmentity");
         if($oldtable == 'notes') 
	 	{
          $sql = "insert into Migrator(oldid,newid,createdtime,modifiedtime,module,parent_id,contact_id,deleted) values('".$row["id"]."',".$newid.",'".$row["date_entered"]."','".$row["date_modified"]."','".$module."','".$row["parent_id"]."','".$row["contact_id"]."',".$row["deleted"] .")";
                }
		else
		{
          $sql = "insert into Migrator(oldid,newid,assigned_user_id,createdtime,modifiedtime,module,parent_id,contact_id,deleted) values('".$row["id"]."',".$newid.",'".$row["assigned_user_id"]."','".$row["date_entered"]."','".$row["date_modified"]."','".$module."','".$row["parent_id"]."','".$row["contact_id"]."',".$row["deleted"] .")";
		}	
          //echo '<br> 3 '.$sql;
          $this->newconn->query($sql);
        }
      }
      else
      {
        echo '<br> ############## no data available in table, proceeding to next table ###############  <br>';
      }
    }
    
    foreach($sourcetablearray4 as $oldtable)
    {
      $module;
      if($oldtable == 'accounts')
      {
        $sql = "select id,date_entered,assigned_user_id,modified_user_id,date_modified,parent_id,deleted from ".$oldtable;
      }
      else
      {
        $sql = "select contacts.id,assigned_user_id,modified_user_id,account_id,contacts.id,date_entered,date_modified,reports_to_id,contacts.deleted from ".$oldtable ." inner join accounts_contacts on accounts_contacts.contact_id = contacts.id";
       }
      echo '<br> '.$sql  .'   <br> ';
      $result = $this->oldconn->query($sql);
      echo '<br> >>>>>> '.$oldtable .' count is       '.$this->oldconn->num_rows($result); 
      $count = $this->oldconn->num_rows($result); 
      if($oldtable == 'accounts')
      {
        $module = 'Accounts';
      }
      else
      {
        $module = 'Contacts';
      }
      
      if($count > 0)
      {
        while($row = $this->oldconn->fetchByAssoc($result))
        {
          $newid = $this->newconn->getUniqueID("crmentity");
          
          if($oldtable == 'accounts')
           {
             //echo 'module is   '.$module;
             $sql = "insert into Migrator(oldid,newid,createdtime,modifiedtime,module,assigned_user_id,modified_user_id,parent_id,deleted) values('".$row["id"]."',".$newid.",'".$row["date_entered"]."','".$row["date_modified"]."','".$module."','".$row["assigned_user_id"]."','".$row["modified_user_id"]."','".$row["parent_id"]."',".$row["deleted"] .")";
             //echo $sql .' here  <br> ';
           }
          else
          {
             $sql = "insert into Migrator (oldid,newid,assigned_user_id,modified_user_id,createdtime,modifiedtime,module,reports_to_id,account_id,deleted) values ('".$row["id"]."',".$newid.",'".$row["assigned_user_id"]."','".$row["modified_user_id"]."','".$row["date_entered"]."','".$row["date_modified"]."','".$module."','".$row["reports_to_id"]."','".$row["account_id"]."',".$row["deleted"] .")";
              //echo $sql .' 22222        here  <br> ';
           }
          
          $this->newconn->query($sql);
        }
      }
      else
      {
        echo '<br> ############## no data available in table, proceeding to next table ###############  <br>';
      }
    }
    
    foreach($sourcetablearray5 as $oldtable)
    {
      $module;
	if($oldtable == 'filestorage')
           {
      $sql = "select fileid from ".$oldtable  ;
           }
          else
          {
      $sql = "select id from ".$oldtable  ;
           }

      $result = $this->oldconn->query($sql);
      echo '<br>>>>>>>  '.$oldtable .' count is                '.$this->oldconn->num_rows($result); 
      $count = $this->oldconn->num_rows($result); 
      if($count > 0)
      {
        while($row = $this->oldconn->fetchByAssoc($result))
        {
          $newid = $this->newconn->getUniqueID("crmentity");

	if($oldtable == 'filestorage')
           {
          $sql = "insert into Migrator (oldid,newid) values ('".$row["fileid"]."',".$newid.")";
  	   }
	   else
	   {
          $sql = "insert into Migrator (oldid,newid) values ('".$row["id"]."',".$newid.")";
	   }
          $this->newconn->query($sql);
        }
      }
      else
      {
        echo '<br> ############## no data available in table, proceeding to next table ###############  <br>';
      }
    }
        
  }








  
  function proceedStandardMigration()
  {

    echo '<br>######### ID MAPPING ENDS   ############### <BR>';
    
    echo '<br>+++++++++++++++++++++++++++++++++++++<br>';
    echo '<br><br>';
    echo '<br>++++++++++++++++STANDARD DATA MIGRATION INITIATED+++++++++++++++++++++<br>';
    echo '<br><br>';
    echo '<br>+++++++++++++++++++++++++++++++++++++<br>';

    echo '<br><font color=blue> proceeding with customfields leading the way </font><br>';

    $sourcetablearray=Array("users","customfields","accounts","contacts","leads","opportunities","calls","cases","emails","meetings","notes","tasks");
    //$sourcetablearray=Array("users","customfields","leads","accounts","contacts","opportunities");
     
    foreach($sourcetablearray as $oldtable)
    {
      $this->$oldtable();      
    }
  }

  function fetchOldData($oldid)
  {
    $sql = "select * from Migrator where oldid='".$oldid ."'";
    //echo '<br>' .$sql .'<br>';
    $result =  $this->newconn->query($sql);
    return $this->newconn->fetch_row($result);
  }


function users()
{
  echo '<br> users <br>';
  $sql = "select * from users";
  //echo '<br> '.$sql  .'   <br> ';
  $result = $this->oldconn->query($sql);
   echo '<br> >>>>>> number of users is  '.$this->oldconn->num_rows($result); 
  while($old_data = $this->oldconn->fetchByAssoc($result))
  {
    $user = new User();
    $retrieved_data = $this->fetchOldData($old_data["id"]);
    //print_r($retrieved_data);
    $fieldName='';
    $fieldValue = '';
    foreach($user->column_fields as $field)
    {
     
      if($fieldName == '')
      {
        $fieldName = $field;
        if($field == 'id')
        {
          $newuser_id = $retrieved_data["newid"];
          $fieldValue = "'".$newuser_id ."'";
        }
        else if($field == 'deleted')
        {
          $deleted = $retrieved_datap["deleted"];
          $fieldValue = "'".$deleted ."'";
        }
      }
      else
      {
        $fieldName .= ",".$field ."";
        $tempFieldNameHolder = $field;
        $fieldValue .= ",'".$old_data[$tempFieldNameHolder]."'";
      }
      //echo '<br>fieldname is ' .$field .'----->  field value is '.$fieldValue;
    }
    $sql_new_insert = "insert into users(".$fieldName .") values (".$fieldValue .")";
    //echo '<br> <br> <br> <br> insert query is ' .$sql_new_insert;
    $this->newconn->query($sql_new_insert);



    //handle population of the user2role table too else the user cannot login into the system



  }
}

function accounts()
{
   echo '<br> Account <br>';
 
  $fieldmap_array=Array("accountname"=>"name","annualrevenue"=>"annual_revenue","siccode"=>"sic_code","tickersymbol"=>"ticker_symbol","email1"=>"email1","email2"=>"email2","phone"=>"phone_office","otherphone"=>"phone_alternate","fax"=>"phone_fax","bill_city"=>"billing_address_city","bill_code"=>"billing_address_postalcode","bill_country"=>"billing_address_country","bill_state"=>"billing_address_state","bill_street"=>"billing_address_street","accounttype"=>"account_type","ship_city"=>"shipping_address_city","ship_code"=>"shipping_address_postalcode","ship_country"=>"shipping_address_country","ship_state"=>"shipping_address_state","ship_street"=>"shipping_address_street");
  $query = "select accounts.*,accountcf.* from accounts left join accountcf on accountcf.accountid=accounts.id";
  $result = $this->oldconn->query($query);
  
  $count = $this->oldconn->num_rows($result); 
  echo '<br> account count is ' .$count;
  $module = "Accounts";
  if($count > 0)
  {
    $this->getAssociatedDataAndSave($module,$fieldmap_array,$result);
  }
  else
  {
         return;
  }
}
 
function getAssociatedDataAndSave($object,$fldmaparray,$result)
{
  $module = $object;
  echo '<br> ------- > module is '.$module;
  $i=0;
  while($old_data = $this->oldconn->fetchByAssoc($result))
  {
    
    if($object == 'Accounts')
    {
      echo '<br> handling -> '.$object .'  here <br>';
      $object = new Account();
    }
    else if($object == 'Contacts')
    {
      echo '<br> handling -> '.$object .'  here <br>';
      $object = new Contact();
    }
    else if($object == 'Leads')
    {
      echo '<br> handling -> '.$object .'  here <br>';
      $object = new Lead();
    }
    else if($object == 'Potentials')
    {
      echo '<br> handling -> '.$object .'  here <br>';
      $object = new Potential();
    }
    else if($object == 'Emails')
    {
      echo '<br> handling -> '.$object .'  here <br>';
      $object = new Email();
    }
    else if($object == 'Calls')
    {
      echo '<br> handling -> '.$object .'  here <br>';
      $object = new Activity();
    }
    else if($object == 'Meetings')
    {
      echo '<br> handling -> '.$object .'  here <br>';
      $object = new Activity();
    }
    else if($object == 'Tasks')
    {
      echo '<br> handling -> '.$object .'  here <br>';
      $object = new Activity();
    }
    else if($object == 'Notes')
    {
      echo '<br> handling -> '.$object .'  here <br>';
      $object = new Note();
    }
    

    $i++;
    $fieldName='';
    $fieldValue='';
  
    foreach($object->column_fields as $field=>$value)
    {
      
      echo '<br> incoming field '.$field .' module     ' .$module;
      /*
      $pos = strrpos($field, "CF_");
      if($pos == true)
      {
        $field = strtolower($field);
      }
      */
      
      if(array_key_exists($field,$fldmaparray))
      {
        $mappedField = $fldmaparray[$field];
		if($mappedField == 'reports_to_id')
		{
			$retrieved_data = $this->fetchOldData($old_data[$mappedField]);
			$object->column_fields[$field] = $retrieved_data["newid"];
		}
		else
		{
        $object->column_fields[$field] = $old_data[$mappedField];
        echo '<br> newfield ' .$field .' oldfield ' .$mappedField .' value '.$old_data[$mappedField]; 
                }
      }
      elseif($module == 'Tasks' && $field == 'parent_id' )
      {
        $retrieved_data = $this->fetchOldData($old_data["id"]);
        $tempid = $retrieved_data["parent_id"];
        
        if($tempid != '')
        {
          //echo '<br> the old parent id is <<<<<<<<<<<<<   ' .$tempid;
          $retrieved_data = $this->fetchOldData($tempid);
        }
        $object->column_fields[$field] = $retrieved_data["newid"];
      }
      elseif($module == 'Events' && $field == 'parent_id' )
      {
        $retrieved_data = $this->fetchOldData($old_data["id"]);
        $tempid = $retrieved_data["parent_id"];
        
        if($tempid != '')
        {
          //echo '<br> the old parent id is <<<<<<<<<<<<<   ' .$tempid;
          $retrieved_data = $this->fetchOldData($tempid);
        }
        $object->column_fields[$field] = $retrieved_data["newid"];
      }
      elseif($field == 'reportsto' )
      {
        //echo '<br>';
        $retrieved_data = $this->fetchOldData($old_data["id"]);
        //echo '<br> the reports_to_id id is >>>>>>>>>>>>   ' .$retrieved_data["reports_to_id"];
        $object->column_fields[$field] = $retrieved_data[$field];
      }
      elseif($module == 'Tasks' && $field == 'contact_id' )
      {
        //echo '<br>the module is ----------------> ' .$module;
        $retrieved_data = $this->fetchOldData($old_data["id"]);
        $tempid = $retrieved_data["contact_id"];
        if($tempid != '')
        {
          //echo '<br> the old contact id is <<<<<<<<<<<<<   ' .$tempid;
          $retrieved_data = $this->fetchOldData($tempid);
        }
        $object->column_fields[$field] = $retrieved_data["newid"];
      }
     elseif($module == 'Notes' && $field == 'contact_id' )
      {
        //echo '<br>the module is ----------------> ' .$module;
        $retrieved_data = $this->fetchOldData($old_data["id"]);
        $tempid = $retrieved_data["contact_id"];
        if($tempid != '')
        {
          //echo '<br> the old contact id is <<<<<<<<<<<<<   ' .$tempid;
          $retrieved_data = $this->fetchOldData($tempid);
        }
        $object->column_fields[$field] = $retrieved_data["newid"];
      }
      elseif($module == 'Notes' && $field == 'parent_id' )
      {
             //echo '<br>the module is ----------------> ' .$module;
             $retrieved_data = $this->fetchOldData($old_data["id"]);
             $tempid = $retrieved_data["parent_id"];
             if($tempid != '')
             {
             $retrieved_data = $this->fetchOldData($tempid);
             }
             $object->column_fields['parent_id'] = $retrieved_data["newid"];
      }
      elseif($module == 'Events' && $field == 'contact_id' )
      {
        //echo '<br>the module is ----------------> ' .$module;
        $retrieved_data = $this->fetchOldData($old_data["id"]);
        $tempid = $retrieved_data["contact_id"];
        if($tempid != '')
        {
          //echo '<br> the old contact id is <<<<<<<<<<<<<   ' .$tempid;
          $retrieved_data = $this->fetchOldData($tempid);
        }
        $object->column_fields[$field] = $retrieved_data["newid"];
      }
      elseif($module == 'Accounts' && $field == 'account_id' )
      {
        //this is to retrieve the parentid of an account
        $retrieved_data = $this->fetchOldData($old_data["id"]);
        $tempid = $retrieved_data["parent_id"];

        
        if($tempid != '')
        {
          //echo '<br> the old parent id is <<<<<<<<<<<<<   ' .$tempid;
          $retrieved_data = $this->fetchOldData($tempid);
        }
        $object->column_fields[$field] = $retrieved_data["newid"];
        //echo '<br> the new parent id is <<<<<<<<<<<<<   ' .$retrieved_data["newid"];
      }
     elseif($module == 'Contacts' && $field == 'account_id' )
      {
        //this is to retrieve the parentid of an account
        $retrieved_data = $this->fetchOldData($old_data["id"]);
        $tempid = $retrieved_data["account_id"];
        if($tempid != '')
        {
          //echo '<br> the old parent id is <<<<<<<<<<<<<   ' .$tempid;
        $retrieved_data = $this->fetchOldData($tempid);
        }
        $object->column_fields[$field] = $retrieved_data["newid"];
        //echo '<br> the new parent id is <<<<<<<<<<<<<   ' .$retrieved_data["newid"];
      }
     elseif($module == 'Potentials' && $field == 'account_id' )
      {
        //this is to retrieve the parentid of an account
        $retrieved_data = $this->fetchOldData($old_data["id"]);
        $tempid = $retrieved_data["account_id"];
        if($tempid != '')
        {
          //echo '<br> the old parent id is <<<<<<<<<<<<<   ' .$tempid;
          $retrieved_data = $this->fetchOldData($tempid);
        }
        $object->column_fields[$field] = $retrieved_data["newid"];
        //echo '<br> the new parent id is <<<<<<<<<<<<<   ' .$retrieved_data["newid"];
      }
     elseif($field == 'annual_revenue' || $field == 'amount')
     {
       $sum = $old_data[$field];
       $sum = str_replace(',','',$sum);
       $object->column_fields[$field] = $sum;
     }
      else
      {
        $object->column_fields[$field] = $old_data[$field];
        $object->id = $old_data["id"];
        
                  if($field == 'activitytype')
		{
			if($module == 'Tasks')
			{
        			$object->column_fields['activitytype'] = 'Task';
			}
			elseif($module == 'Calls')
			{
				
        			$object->column_fields['activitytype'] = 'Call';
			}
			elseif($module == 'Meetings')
			{
        			$object->column_fields['activitytype'] = 'Meeting';
			}	
		}
        echo '<br> unmodified cases ===== '.$field .'  value >>>>> BE CAREFUL WILL SET NULL DATA IF NO MATCH!' .$old_data[$field] . ' <br> ';
      }
    }
    echo '<br> <br> FINAL DATA BEING SENT TO THE SERVER %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%  <BR>';
    print_r($object->column_fields);
    $migration="true";
    
    if($module == 'Tasks')
    {
    $object->saveentity('Activities',$migration); 
    }
    elseif($module == 'Calls' || $module == 'Meetings')
    {
    $object->saveentity('Events',$migration); 
    }
    else
    {
    $object->saveentity($module,$migration); 
    }
    
  }
}




function contacts()
{
  echo '<br> Contact <br>';
  
  $fieldmap_array=Array("firstname"=>"first_name","lastname"=>"last_name","email"=>"email1","homephone"=>"phone_home","fax"=>"phone_fax","mobile"=>"phone_mobile","phone"=>"phone_work","otherphone"=>"phone_other","assistantphone"=>"assistant_phone","contact_id"=>"reports_to_id","otheremail"=>"email2","yahooid"=>"yahoo_id","emailoptout"=>"email_opt_out","birthday"=>"birthdate","leadsource"=>"lead_source","mailingstreet"=>"primary_address_street","otherstreet"=>"alt_address_street","mailingstate"=>"primary_address_state","mailingcity"=>"primary_address_city","mailingzip"=>"primary_address_postalcode","mailingcountry"=>"primary_address_country","otherstate"=>"alt_address_state","othercity"=>"alt_address_city","otherzip"=>"alt_address_postalcode","othercountry"=>"alt_address_country","donotcall"=>"do_not_call","emailoptout"=>"email_opt_out","salutationtype"=>"salutation");

  $query = "select contacts.* ,contactcf.* from contacts inner join accounts_contacts on accounts_contacts.contact_id = contacts.id left join contactcf on contactcf.contactid=contacts.id";
  //select contacts.* ,contactcf.* from contacts inner join accounts_contacts on accounts_contacts.contact_id = contacts.id inner join contactcf on contactcf.contactid=contacts.id";
  $result = $this->oldconn->query($query);
  
  $count = $this->oldconn->num_rows($result); 
  echo '<br> contact count is ' .$count;
  $module = "Contacts";
  if($count > 0)
  {
    $this->getAssociatedDataAndSave($module,$fieldmap_array,$result);
  }
  else
  {
         return;
  }
  
}

function leads()
{

echo '<br> Lead <br>';
 
  $fieldmap_array=Array("leadid"=>"id","firstname"=>"first_name","lastname"=>"last_name","leadsource"=>"lead_source","annualrevenue"=>"annual_revenue","yahooid"=>"yahoo_id","leadstatus"=>"lead_status","noofemployees"=>"employees","lane"=>"address_street","state"=>"address_state","city"=>"address_city","code"=>"address_postalcode","country"=>"address_country");

  $query = "select * from leads ";
  $result = $this->oldconn->query($query);
  
  $count = $this->oldconn->num_rows($result); 
  echo '<br> lead  count is ' .$count;
  $module = "Leads";
  if($count > 0)
  {
    $this->getAssociatedDataAndSave($module,$fieldmap_array,$result);
  }
  else
  {
         return;
  }

  
}

function opportunities()
{
  echo '<br> Opportunity <br>';

  $fieldmap_array=Array("potentialid"=>"id","potentialname"=>"name","nextstep"=>"next_step","closingdate"=>"date_closed","leadsource"=>"lead_source","potentialtype"=>"opportunity_type");

  $query = "select opportunities.*,opportunitycf.* from opportunities left join opportunitycf on opportunitycf.opportunityid=opportunities.id";
  $result = $this->oldconn->query($query);
  
  $count = $this->oldconn->num_rows($result); 
  echo '<br> opportunity  count is ' .$count;
  $module = "Potentials";
  if($count > 0)
  {
    $this->getAssociatedDataAndSave($module,$fieldmap_array,$result);
  }
  else
  {
         return;
  }



}


function emails()
{
  echo '<br> Email <br>';
  
  $fieldmap_array=Array("potentialid"=>"id","potentialname"=>"name","nextstep"=>"next_step","closingdate"=>"date_closed","leadsource"=>"lead_source","potentialtype"=>"opportunity_type");

  $query = "select * from emails ";
  $result = $this->oldconn->query($query);
  
  $count = $this->oldconn->num_rows($result); 
  echo '<br> emails       count is ' .$count;
  $module = "Emails";
  if($count > 0)
  {
    $this->getAssociatedDataAndSave($module,$fieldmap_array,$result);
  }
  else
  {
         return;
  }


}

function notes()
{
  echo '<br> Note <br>';
  
  $fieldmap_array=Array("title"=>"name","notecontent"=>"description");

  $query = "select * from notes ";
  $result = $this->oldconn->query($query);
  
  $count = $this->oldconn->num_rows($result); 
  echo '<br> notes       count is ' .$count;
  $module = "Notes";
  if($count > 0)
  {
    $this->getAssociatedDataAndSave($module,$fieldmap_array,$result);
  }
  else
  {
         return;
  }
}


function meetings()
{
  echo '<br> Meetings <br>';
  
  $fieldmap_array=Array("subject"=>"name","taskstatus"=>"status");
  $query = "select * from meetings ";
  $result = $this->oldconn->query($query);
  
  $count = $this->oldconn->num_rows($result); 
  echo '<br> meetings       count is ' .$count;
  $module = "Meetings";
  if($count > 0)
  {
    $this->getAssociatedDataAndSave($module,$fieldmap_array,$result);
  }
  else
  {
         return;
  }

  

}




function tasks()
{
  echo '<br> Tasks <br>';
  
  $fieldmap_array=Array("subject"=>"name","taskstatus"=>"status","taskpriority"=>"priority","due_date"=>"date_due");
  $query = "select * from tasks ";
  $result = $this->oldconn->query($query);
  
  $count = $this->oldconn->num_rows($result); 
  echo '<br> tasks       count is ' .$count;
  $module = "Tasks";
  if($count > 0)
  {
    $this->getAssociatedDataAndSave($module,$fieldmap_array,$result);
  }
  else
  {
         return;
  }







}

function calls()
{
  echo '<br> Calls <br>';
  
  $fieldmap_array=Array("subject"=>"name","taskstatus"=>"status");

  $query = "select * from calls ";
  $result = $this->oldconn->query($query);
  
  $count = $this->oldconn->num_rows($result); 
  echo '<br> call  count is ' .$count;
  $module = "Calls";
  if($count > 0)
  {
    $this->getAssociatedDataAndSave($module,$fieldmap_array,$result);
  }
  else
  {
         return;
  }



}

function cases()
{
  echo '<br> NOT MIGRATED AS NOT BEING  HANDLED ANY MORE ! <br>';

}

  


function proceedRelationalMigration()
{

  echo '<br>############  STANDARD DATA MIGRATION ENDS ############ <BR>';
  echo '<br>+++++++++++++++++++++++++++++++++++++<br>';
  echo '<br><br>';
  echo '<br>++++++++++++++++RELATIONAL DATA MIGRATION STARTS+++++++++++++++++++++<br>';
  echo '<br><br>';
  echo '<br>+++++++++++++++++++++++++++++++++++++<br>';
  
  $relationaltablearray=Array("opportunities_contacts","meetings_contacts","calls_contacts","meetings_users","calls_users","emails_accounts","emails_contacts","emails_opportunities","emails_users","user2role");
  //fetch data from these tables by referring to the data present in the Migrator table and then add to the relational tables in the new db
  foreach($relationaltablearray as $constantstable)
  {
    $this->$constantstable();      
  }
  

}

  function proceedConstantsMigration()
  {

    echo '<br>############ RELATIONAL DATA MIGRATION ENDS ############ <BR> ';
    echo '<br><br>';
    echo '<br><br>';
    echo '<br>+++++++++++++++++++++++++++++++++++++<br>';
    echo '<br>++++++++++++++++CONSTANTS  DATA MIGRATION STARTS+++++++++++++++++++++<br>';
    echo '<br><br>';
    echo '<br><br>';
    echo '<br>+++++++++++++++++++++++++++++++++++++<br>';


    $constantstablearray=Array("tracker","filestorage","users_last_import","sales_stage","account_type","industry","lead_source","lead_status","license_key","opportunity_type","rating","salutation","loginhistory","files","import_maps");
    //$constantstablearray=Array("tracker","users_last_import","sales_stage","account_type","industry","lead_source","lead_status","license_key","opportunity_type","rating","salutation","loginhistory");
    
    foreach($constantstablearray as $constantstable)
    {
      $this->$constantstable();      
    }

    echo '<br>############ CONSTANTS DATA MIGRATION ENDS ############ <BR> ';
    echo '<br><br>';
    echo '<br><br>';
    echo '<br>+++++++++++++++++++++++++++<br>';
    echo '<br> DATA MIGRATION FROM vtiger CRM 3.2 TO vtiger CRM4 GA COMPLETED SUCCESSFULLY!! :-) <BR> ';

    
  }

/*
  function proceedSpecialHandling()
  {
    echo '<br><br>';
    echo '<br><br>';
    echo '<br>+++++++++++++++++++++++++++<br>';
    echo '<br>SPECIAL CASES HANDLING STARTS<br>';
    echo '<br><br>';
    echo '<br>+++++++++++++++++++++++++++<br>';
    echo '<br> DATA MIGRATION FROM vtiger CRM 3.2 TO vtiger CRM4 GA COMPLETED SUCCESSFULLY!! :-) <BR> ';
 
    $specialhandlingarray=Array("notes1");
    
    foreach($specialhandlingarray as $specialcases)
    {
      $this->$specialcases();      
    }


  }	  


  function notes1()
  {
    $sql = "select * from notes";
    $result = $this->oldconn->query($sql);
    $count = $this->oldconn->num_rows($result);
    echo '<br> notes   count is ' .$count;

    if($count > 0)
    {
      while($old_data = $this->oldconn->fetchByAssoc($result))
      {
        $retrieve_data = $this->fetchOldData($old_data["id"]);
        $sql = "select * from Migrator where oldid='".$retrieve_data["oldid"] ."'";
        $result1 = $this->newconn->query($sql);
        //this is the notes id
        $id = $this->newconn->query_result($result1,0,"newid");
        
        $newattachmententityid = $this->newconn->getUniqueID("crmentity");
        
        //i need to create a crmentity for the case where we have a file present as attachment.
        //then the above id is the attachmentid and an entry is put in the seattachmentsrel table


        $creatorid = $this->newconn->query_result($result1,0,"assigned_user_id");
        $modifierid =$this->newconn->query_result($result1,0,"modified_user_id");
        $createdtime = $this->newconn->query_result($result1,0,"createdtime");
        $modifiedtime = $this->newconn->query_result($result1,0,"modifiedtime");
        $modulename = "NoteAttachments";
        //get the values from this and set to the query below and then relax!
        $sql1 = "insert into crmentity (crmid,smcreatorid,smownerid,setype,description,createdtime,modifiedtime,deleted) values('".$newattachmententityid."','".$creatorid."','".$modifierid."','".$modulename."','Attachments','".$createdtime."','".$modifiedtime ."',0)";
        $this->newconn->query($sql1);

        //ISSUE POSSIBLE FOR BLOB
        $sql2 = "insert into attachments(attachmentsid,name,description,type,attachmentsize,attachmentcontents) values (".$newattachmentsid.",'".$old_data["filename"]."','','','','".$old_data["data"]."')";
        $this->newconn->query($sql2);

      }

    }
  }
*/	  
    function tracker()
      {
        $sql = "select * from tracker";
        $result = $this->oldconn->query($sql);
        $count = $this->oldconn->num_rows($result); 
        echo '<br> tracker  count is ' .$count;
        if($count > 0)
        {
          while($old_data = $this->oldconn->fetchByAssoc($result))
          {

            $retrieve_data = $this->fetchOldData($old_data["user_id"]);
            $retrieve_data2 = $this->fetchOldData($old_data["item_id"]);
            //echo '<br> >>>>>>>>>>> item _id is ' .$retrieve_data2["newid"];
            $sql = "insert into tracker(id,user_id,module_name,item_id,item_summary) values (".$old_data["id"].",'".$retrieve_data["newid"]."','".$old_data["module_name"]."','".$retrieve_data2["newid"]."','".$old_data["item_summary"]."')";
            //echo '<br> tracker insert query is ........... '.$sql;
            $this->newconn->query($sql);
          }
        }
      }

    function filestorage()
      {

        $sql = "select * from filestorage";
        $result = $this->oldconn->query($sql);
        $count = $this->oldconn->num_rows($result); 
        echo '<br> filestorage   count is ' .$count;
  
        if($count > 0)
        {
          while($old_data = $this->oldconn->fetchByAssoc($result))
          {
            $retrieve_data = $this->fetchOldData($old_data["fileid"]);
            //for attachments, the entry is put in the Migrator table but  not in the crmentity table anywhere as there is no saveentity called for attachments. Hence, one has to do the same manually
            $sql = "select * from Migrator where oldid='".$retrieve_data["oldid"] ."'";
            $result1 = $this->newconn->query($sql);
            $id = $this->newconn->query_result($result1,0,"newid");
            $creatorid = $this->newconn->query_result($result1,0,"assigned_user_id");
            $modifierid =$this->newconn->query_result($result1,0,"modified_user_id");
            $createdtime = $this->newconn->query_result($result1,0,"createdtime");
            $modifiedtime = $this->newconn->query_result($result1,0,"modifiedtime");
            $modulename = "Attachments";
            //get the values from this and set to the query below and then relax!
            $sql1 = "insert into crmentity (crmid,smcreatorid,smownerid,setype,description,createdtime,modifiedtime,deleted) values('".$id."','".$creatorid."','".$modifierid."','".$modulename."','Attachments','".$createdtime."','".$modifiedtime ."',0)";
            $this->newconn->query($sql1);

            //ISSUE POSSIBLE FOR BLOB
            $sql2 = "insert into attachments(attachmentsid,name,description,type,attachmentsize,attachmentcontents) values (".$retrieve_data["newid"].",'".$old_data["filename"]."','".$old_data["description"]."','".$old_data["filetype"]."','".$old_data["filesize"]."','".$old_data["data"]."')";
            $this->newconn->query($sql2);


            //retrieve the input from the old table and populate the relational table seattachmentsrel
            $sql_old = "select * from Migrator where oldid='".$old_data["parent_id"] ."'";
            echo "<br><font color='red'> getting the old attachment data >>>>>>> <br>" .$sql_old;
            $result2 = $this->newconn->query($sql_old);
            $seid = $this->newconn->query_result($result2,0,"newid");
            $sql_rel = "insert into seattachmentsrel values (".$seid.",".$retrieve_data["newid"].")";
            echo '<br>>>>>>>>>>>>>>>>>  <br>'.$sql_rel;
            $this->newconn->query($sql_rel);

          }
        }
  

      }

    function users_last_import()
      {
        $sql = "select * from users_last_import";
        $result = $this->oldconn->query($sql);
        $count = $this->oldconn->num_rows($result); 
        echo '<br> users last import  count is ' .$count;
        if($count > 0)
        {
          while($old_data = $this->oldconn->fetchByAssoc($result))
          {

            $retrieve_data = $this->fetchOldData($old_data["id"]);
            $retrieve_data2 = $this->fetchOldData($old_data["assigned_user_id"]);
            $retrieve_data3 = $this->fetchOldData($old_data["bean_id"]);

      
            $sql = "insert into users_last_import(id,assigned_user_id,bean_type,bean_id,deleted) values (".$retrieve_data["newid"].",'".$retrieve_data2["newid"]."','".$old_data["bean_type"]."','".$retrieve_data3["newid"]."',".$old_data["deleted"].")";
            //echo '<br> users_last_import insert query is BLOB........... '.$sql;
            $this->newconn->query($sql);
          }
        }


      }


    function sales_stage()
      {
        $sql = "select * from sales_stage";
        $result = $this->oldconn->query($sql);
        $count = $this->oldconn->num_rows($result); 
        $i=0;
        if($count > 0)
        {
          while($old_data = $this->oldconn->fetchByAssoc($result))
          {
            $i++;
            $sql = "insert into sales_stage(sales_stage_id,sales_stage,SORTORDERID,PRESENCE) values (".$i.",'".$old_data["sales_stage"]."',".$i.",".$i.")";
            //echo '<br> insert query is ........... '.$sql;
            $this->newconn->query($sql);
          }
        }
      }


    function account_type()
      {
        $sql = "select * from account_type";
        $result = $this->oldconn->query($sql);
        $count = $this->oldconn->num_rows($result); 
        $i=0;
        if($count > 0)
        {
          while($old_data = $this->oldconn->fetchByAssoc($result))
          {
            $i++;
            $sql = "insert into accounttype(accounttypeid,accounttype,sortorderid,presence) values (".$i.",'".$old_data["account_type"]."',".$i.",".$i.")";
            //echo '<br> insert query is ........... '.$sql;
            $this->newconn->query($sql);
          }
        }
      }

    function industry()
      {
        $sql = "select * from industry";
        $result = $this->oldconn->query($sql);
        $count = $this->oldconn->num_rows($result); 
        $i=0;
        if($count > 0)
        {
          while($old_data = $this->oldconn->fetchByAssoc($result))
          {
            $i++;
            $sql = "insert into industry(industryid,industry,sortorderid,presence) values (".$i.",'".$old_data["industry"]."',".$i.",".$i.")";
            //echo '<br> insert query is ........... '.$sql;
            $this->newconn->query($sql);
          }
        }
  
      }


    function lead_source()
      {
        $sql = "select * from lead_source";
        $result = $this->oldconn->query($sql);
        $count = $this->oldconn->num_rows($result); 
        $i=0;
        if($count > 0)
        {
          while($old_data = $this->oldconn->fetchByAssoc($result))
          {
            $i++;
            $sql = "insert into leadsource(leadsourceid,leadsource,sortorderid,presence) values (".$i.",'".$old_data["lead_source"]."',".$i.",".$i.")";
            //echo '<br> insert query is ........... '.$sql;
            $this->newconn->query($sql);
          }
        }
      }





    function lead_status()
      {
        $sql = "select * from lead_status";
        $result = $this->oldconn->query($sql);
        $count = $this->oldconn->num_rows($result); 
        $i=0;
        if($count > 0)
        {
          while($old_data = $this->oldconn->fetchByAssoc($result))
          {
            $i++;
            $sql = "insert into leadstatus(leadstatusid,leadstatus,sortorderid,presence) values (".$i.",'".$old_data["lead_status"]."',".$i.",".$i.")";
            //echo '<br> insert query is ........... '.$sql;
            $this->newconn->query($sql);
          }
        }
      }







    function license_key()
      {
        $sql = "select * from license_key";
        $result = $this->oldconn->query($sql);
        $count = $this->oldconn->num_rows($result); 
        $i=0;
        if($count > 0)
        {
          while($old_data = $this->oldconn->fetchByAssoc($result))
          {
            $i++;
            $sql = "insert into licencekeystatus(licencekeystatusid,licencekeystatus,sortorderid,presence) values (".$i.",'".$old_data["license_key"]."',".$i.",".$i.")";
            //echo '<br> insert query is ........... '.$sql;
            $this->newconn->query($sql);
          }
        }
      }



    function opportunity_type()
      {
        $sql = "select * from opportunity_type";
        $result = $this->oldconn->query($sql);
        $count = $this->oldconn->num_rows($result); 
        $i=0;
        if($count > 0)
        {
          while($old_data = $this->oldconn->fetchByAssoc($result))
          {
            $i++;
            $sql = "insert into opportunity_type(opptypeid,opportunity_type,sortorderid,presence) values (".$i.",'".$old_data["opportunity_type"]."',".$i.",".$i.")";
            //echo '<br> insert query is ........... '.$sql;
            $this->newconn->query($sql);
          }
        }
      }


    function rating()
      {
        $sql = "select * from rating";
        $result = $this->oldconn->query($sql);
        $count = $this->oldconn->num_rows($result); 
        $i=0;
        if($count > 0)
        {
          while($old_data = $this->oldconn->fetchByAssoc($result))
          {
            $i++;
            $sql = "insert into rating(rating_id,rating,sortorderid,presence) values (".$i.",'".$old_data["rating"]."',".$i.",".$i.")";
            //echo '<br> insert query is ........... '.$sql;
            $this->newconn->query($sql);
          }
        }
      }


    function salutation()
      {
        $sql = "select * from salutation";
        $result = $this->oldconn->query($sql);
        $count = $this->oldconn->num_rows($result); 
        $i=0;
        if($count > 0)
        {
          while($old_data = $this->oldconn->fetchByAssoc($result))
          {
            $i++;
            $sql = "insert into salutationtype(salutationid,salutationtype,sortorderid,presence) values (".$i.",'".$old_data["salutation"]."',".$i.",".$i.")";
            //echo '<br> insert query is ........... '.$sql;
            $this->newconn->query($sql);
          }
        }
      }


    function loginhistory()
      {
        $sql = "select * from loginhistory";
        $result = $this->oldconn->query($sql);
        $count = $this->oldconn->num_rows($result); 
        $i=0;
        if($count > 0)
        {
          while($old_data = $this->oldconn->fetchByAssoc($result))
          {
            $i++;
            $sql = "insert into loginhistory(login_id,user_name,user_ip,login_time,logout_time,status) values (".$old_data["login_id"].",'".$old_data["user_name"]."','".$old_data["user_ip"]."','".$old_data["login_time"]."','".$old_data["logout_time"]."','".$old_data["status"]."')";
            //echo '<br> insert query is ........... '.$sql;
            $this->newconn->query($sql);
          }
        }
      }


    //BLOB HANDLING ISSUE

    function files()
      {
        $sql = "select * from files";
        $result = $this->oldconn->query($sql);
        $count = $this->oldconn->num_rows($result); 
        $i=0;
        if($count > 0)
        {
          while($old_data = $this->oldconn->fetchByAssoc($result))
          {
            $i++;
            $retrieve_data = $this->fetchOldData($old_data["assigned_user_id"]);
            $sql = "insert into files(id,name,content,deleted,date_entered,assigned_user_id) values (".$i.",".$old_data["content"].",".$old_data["deleted"].",".$old_data["date_entered"].",".$retrieved_data["newid"].")";
            // echo '<br> insert query is ......BLOB..... '.$sql;
            $this->newconn->query($sql);
          }
        }
      }

    //BLOB HANDLING ISSUE

    function import_maps()
      {
        $sql = "select * from import_maps";
        $result = $this->oldconn->query($sql);
        $count = $this->oldconn->num_rows($result); 
        $i=0;
        if($count > 0)
        {
          while($old_data = $this->oldconn->fetchByAssoc($result))
          {
            $i++;
            $retrieve_data = $this->fetchOldData($old_data["assigned_user_id"]);
            $sql = "insert into import_maps(id,name,module,content,has_header,deleted,date_entered,date_modified,assigned_user_id,is_published) values (".$i.",".$old_data["name"].",".$old_data["content"].",".$old_data["has_header"].",".$old_data["deleted"].",".$old_data["date_entered"].",".$old_data["date_modified"].",".$retrieved_data["newid"].",".$old_data["is_published"].")";
            // echo '<br> insert query is ...BLOB ........ '.$sql;
            $this->newconn->query($sql);
          }
        }
      }






    function opportunities_contacts()
      {
        $sql = "select * from opportunities_contacts where deleted !=1";
        $result = $this->oldconn->query($sql);
        $count = $this->oldconn->num_rows($result); 
        echo '<br> opportunities_contacts  count is ' .$count;
        if($count > 0)
        {
          while($old_data = $this->oldconn->fetchByAssoc($result))
          {

            $retrieve_data = $this->fetchOldData($old_data["opportunity_id"]);
            $retrieve_data2 = $this->fetchOldData($old_data["contact_id"]);
            $sql = "insert into contpotentialrel(contactid,potentialid) values (".$retrieve_data2["newid"].",".$retrieve_data["newid"].")";
            //echo '<br> insert query is ...BLOB ........ '.$sql;
            $this->newconn->query($sql);
          }
        }
      }


    function meetings_contacts()
      {
        $sql = "select * from meetings_contacts where deleted !=1";
        $result = $this->oldconn->query($sql);
        $count = $this->oldconn->num_rows($result); 
        echo '<br> meetings_contacts  count is ' .$count;
        if($count > 0)
        {
          while($old_data = $this->oldconn->fetchByAssoc($result))
          {

            $retrieve_data = $this->fetchOldData($old_data["meeting_id"]);
            $retrieve_data2 = $this->fetchOldData($old_data["contact_id"]);
            $sql = "insert into cntactivityrel(contactid,activityid) values (".$retrieve_data2["newid"].",".$retrieve_data["newid"].")";
            //echo '<br> insert query is ........... '.$sql;
            $this->newconn->query($sql);
          }
        }
      }
    function calls_contacts()
      {
        $sql = "select * from calls_contacts where deleted !=1";
        $result = $this->oldconn->query($sql);
        $count = $this->oldconn->num_rows($result); 
        echo '<br> calls_contacts  count is ' .$count;
        if($count > 0)
        {
          while($old_data = $this->oldconn->fetchByAssoc($result))
          {

            $retrieve_data = $this->fetchOldData($old_data["call_id"]);
            $retrieve_data2 = $this->fetchOldData($old_data["contact_id"]);
            $sql = "insert into cntactivityrel(contactid,activityid) values (".$retrieve_data2["newid"].",".$retrieve_data["newid"].")";
            //echo '<br> insert query is ........... '.$sql;
            $this->newconn->query($sql);
          }
        }
      }



    function meetings_users()
      {
        $sql = "select * from meetings_users where deleted !=1";
        $result = $this->oldconn->query($sql);
        $count = $this->oldconn->num_rows($result); 
        echo '<br> meetings_users  count is ' .$count;
        if($count > 0)
        {
          while($old_data = $this->oldconn->fetchByAssoc($result))
          {

            $retrieve_data = $this->fetchOldData($old_data["meeting_id"]);
            $retrieve_data2 = $this->fetchOldData($old_data["user_id"]);
            $sql = "insert into salesmanactivityrel(smid,activityid) values (".$retrieve_data2["newid"].",".$retrieve_data["newid"].")";
            //echo '<br> insert query is ........... '.$sql;
            $this->newconn->query($sql);
          }
        }
      }



    function calls_users()
      {
        $sql = "select * from calls_users where deleted !=1";
        $result = $this->oldconn->query($sql);
        $count = $this->oldconn->num_rows($result); 
        echo '<br> calls_users  count is ' .$count;
        if($count > 0)
        {
          while($old_data = $this->oldconn->fetchByAssoc($result))
          {

            $retrieve_data = $this->fetchOldData($old_data["call_id"]);
            $retrieve_data2 = $this->fetchOldData($old_data["user_id"]);
            $sql = "insert into salesmanactivityrel(smid,activityid) values (".$retrieve_data2["newid"].",".$retrieve_data["newid"].")";
            //echo '<br> insert query is ........... '.$sql;
            $this->newconn->query($sql);
          }
        }
      }















    function emails_accounts()
      {
        $sql = "select * from emails_accounts where deleted !=1";
        $result = $this->oldconn->query($sql);
        $count = $this->oldconn->num_rows($result); 
        echo '<br> emails_accounts  count is ' .$count;
        if($count > 0)
        {
          while($old_data = $this->oldconn->fetchByAssoc($result))
          {

            $retrieve_data = $this->fetchOldData($old_data["email_id"]);
            $retrieve_data2 = $this->fetchOldData($old_data["account_id"]);
            $sql = "insert into seactivityrel(crmid,activityid) values (".$retrieve_data2["newid"].",".$retrieve_data["newid"].")";
            //echo '<br> insert query is ........... '.$sql;
            $this->newconn->query($sql);
          }
        }
      }











    function emails_contacts()
      {
        $sql = "select * from emails_contacts where deleted !=1";
        $result = $this->oldconn->query($sql);
        $count = $this->oldconn->num_rows($result); 
        echo '<br> emails_contacts  count is ' .$count;
        if($count > 0)
        {
          while($old_data = $this->oldconn->fetchByAssoc($result))
          {

            $retrieve_data = $this->fetchOldData($old_data["email_id"]);
            $retrieve_data2 = $this->fetchOldData($old_data["contact_id"]);
            $sql = "insert into cntactivityrel(contactid,activityid) values (".$retrieve_data2["newid"].",".$retrieve_data["newid"].")";
            //echo '<br> insert query is ........... '.$sql;
            $this->newconn->query($sql);
          }
        }
      }















    function emails_opportunities()
      {
        $sql = "select * from emails_opportunities where deleted !=1";
        $result = $this->oldconn->query($sql);
        $count = $this->oldconn->num_rows($result); 
        echo '<br> emails_opportunities  count is ' .$count;
        if($count > 0)
        {
          while($old_data = $this->oldconn->fetchByAssoc($result))
          {

            $retrieve_data = $this->fetchOldData($old_data["email_id"]);
            $retrieve_data2 = $this->fetchOldData($old_data["opportunity_id"]);
            $sql = "insert into seactivityrel(seid,activityid) values (".$retrieve_data2["newid"].",".$retrieve_data["newid"].")";
            //echo '<br> insert query is ........... '.$sql;
            $this->newconn->query($sql);
          }
        }
      }




    function emails_users()
      {
        $sql = "select * from emails_users where deleted !=1";
        $result = $this->oldconn->query($sql);
        $count = $this->oldconn->num_rows($result); 
        echo '<br> emails_users  count is ' .$count;
        if($count > 0)
        {
          while($old_data = $this->oldconn->fetchByAssoc($result))
          {

            $retrieve_data = $this->fetchOldData($old_data["email_id"]);
            $retrieve_data2 = $this->fetchOldData($old_data["user_id"]);
            $sql = "insert into salesmanactivityrel(smid,activityid) values (".$retrieve_data2["newid"].",".$retrieve_data["newid"].")";
            //echo '<br> insert query is ........... '.$sql;
            $this->newconn->query($sql);
          }
        }
      }




    function user2role()
      {
        echo '>>>>>>>>>>>>>> user2role starts >>>>>>>>>>>';
        $sql1 = "select * from users ";
        $sql2 = "select * from role ";
        $result_users = $this->newconn->query($sql1);
        $result_roles = $this->newconn->query($sql2);
        $count = $this->newconn->num_rows($result_users); 
        echo 'count for users is       ' .$count;
        $count2 = $this->newconn->num_rows($result_roles); 
        echo 'count for roles is       ' .$count2;
        if($count > 0)
        {
          $role_data = $this->newconn->fetchByAssoc($result_roles);
          while($old_data = $this->newconn->fetchByAssoc($result_users))
          {
            $sql_insert = "insert into user2role(userid,roleid) values (".$old_data["id"].",".$role_data["roleid"].")";
            echo $sql_insert;
            $this->newconn->query($sql_insert);
          }
        }
      }



    function customfields()
      {
        echo '>>>>>>>>>>>>>> customfields starts >>>>>>>>>>>';
        $sql = "select * from customfields ";
        $result = $this->oldconn->query($sql);
        $count = $this->oldconn->num_rows($result); 
        echo 'count for customfields     ' .$count;
        if($count > 0)
        {
          while($old_data = $this->oldconn->fetchByAssoc($result))
          {
      
            $tabmodule = $old_data["module"]; //now get the tabid
            $tabid = $this->fetchTabIDVal($tabmodule);
            $columnName = $old_data["column_name"]; //now get the tabid
	    //making the columnName to all lower case
	    $columnName = strtolower($columnName); 
            $tableName = $old_data["table_name"]; //now get the tabid
            $uitype = $old_data["uitype"]; //now get the tabid
            $fldlabel = $old_data["fieldlabel"]; //now get the tabid
      
      
            $custfld_fieldid=$this->newconn->getUniqueID("field");
            $custfld_sequece=$this->newconn->getUniqueId("customfield_sequence");

            //echo '++++++++          '.$tabmodule . "------".$tabid."-------".$columnName."---------".$tableName."-------".$uitype."-------".$fldlabel;
      
            $uichekdata='';
            $fldlength=10;
            if($uitype == '1')
            {
              $uichekdata='V~O~LE~'.$fldlength;
              $type = "C(".$fldlength.")"; // adodb type
            }
            elseif($uitype == 7)
            {
              $type="N(".$fldlength.",".$decimal.")";	// adodb type
              $uichekdata='N~O~'.$fldlength .','.$decimal;
            }
            elseif($uitype == 9)
            {
              $type="N(".$fldlength.",".$decimal.")"; //adodb type
              $uichekdata='N~O~'.$fldlength .','.$decimal;
            }
            elseif($uitype == 3)
            {
              $type="N(".$fldlength.",".$decimal.")"; //adodb type
              $uichekdata='N~O~'.$fldlength .','.$decimal;
            }
            elseif($fldType == 5)
            {
              $uichekdata='D~O';
              $type = "T"; // adodb type
                  
            }
            elseif($uitype == 13)
            {
              $type = "C(50)"; //adodb type
              $uichekdata='V~O';
            }
            elseif($uitype == 11)
            {
              $type = "C(30)"; //adodb type
              $uichekdata='V~O';
            }
            elseif($uitype == 15)
            {
              $type = "C(255)"; //adodb type
              $uichekdata='V~O';
            }
      
            if($tableName == 'leadcf')
            {
              $tableName='leadscf';
            }
            elseif($tableName == 'accountcf')
            {
              $tableName='accountscf';
            }
            elseif($tableName == 'potentialcf')
            {
              $tableName='potentialscf';
            }
            elseif($tableName == 'contactcf')
            {
              $tableName='contactscf';
            }
      

            $sql_insert = "insert into field values(".$tabid.",".$custfld_fieldid.",'".$columnName."','".$tableName."',2,".$uitype.",'".$columnName."','".$fldlabel."',0,0,0,100,".$custfld_sequece.",5,1,'".$uichekdata."')";
            echo '<br><bold>custom field insert query is  </bold></br>'.$sql_insert;
            $this->newconn->query($sql_insert);
	    $this->newconn->alterTable($tableName, $columnName." ".$type, "Add_Column");


            echo '<br><b><font color=red>altered the table  </font></b> <br>';
//Inserting values into profile2field tables
$sql1 = "select * from profile";
$sql1_result = $this->newconn->query($sql1);
$sql1_num = $this->newconn->num_rows($sql1_result);
echo '============= > number of rows in profile is '.$sql1_num;

for($i=0; $i<$sql1_num; $i++)
{
	$profileid = $this->newconn->query_result($sql1_result,$i,"profileid");
	$sql2 = "insert into profile2field values(".$profileid.", ".$tabid.", ".$custfld_fieldid.", 0,1)";
	echo '<br><font color=blue> insertion into the profile table in progress</font> <br>';
	$this->newconn->query($sql2);
}





          }
        }
      }


    function fetchTabIDVal($fldmodule)
      {

        global $adb;
        $query = "select tabid from tab where tablabel='" .$fldmodule ."'";
        $tabidresult = $adb->query($query);
        return $adb->query_result($tabidresult,0,"tabid");
      }













    function VTiger32to40Migrator()
      {
      }
  
  }
?>
