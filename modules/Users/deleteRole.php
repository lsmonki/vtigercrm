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



if(($_REQUEST["rolename"] == 'administrator') || ($_REQUEST["rolename"] == 'standard_user')) 
{
  //echo "System defined " .$_REQUEST["rolename"] ." role cannot be deleted!";
  header("Location: index.php?module=Users&action=listroles");
}
else
{
  $sql = "delete from role where name='" .$_REQUEST["rolename"] ."'";
  //echo $sql;
  $result=mysql_query($sql);
  
  $sql="delete from role2tab where rolename='" .$_REQUEST["rolename"] ."'";
  //echo $sql;
  $result=mysql_query($sql);
  

  $sql="delete from role2action where rolename='" .$_REQUEST["rolename"] ."'";
  //echo $sql;
  $result=mysql_query($sql);

}

header("Location: index.php?module=Users&action=listroles");




?>
