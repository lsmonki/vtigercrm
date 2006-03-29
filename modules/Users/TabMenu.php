<?php

/*********************************************************************************
** The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 ********************************************************************************/

include_once('config.php');
require_once('include/logging.php');
require_once('include/database/PearDatabase.php');
require_once('data/SugarBean.php');
require_once('data/CRMEntity.php');

class TabMenu
{
  
  function getTabNames($permittedModuleList="")
  {
    
     global $adb;
     $conn= $adb;
    if($permittedModuleList=="")
    {
      $sql="SELECT name from tab where presence = 0 order by tabsequence";
    }
    else
    {
      $sql="SELECT name from tab where tabid in (" .$permittedModuleList .") and presence = 0 order by tabsequence";
    }
   
    $tabrow=$conn->query($sql);    
    if($conn->num_rows($tabrow) != 0)
    {
      while ($result = $conn->fetch_array($tabrow))
      {
        $tabmenu[]=$result['name'];
      }
    }
    return $tabmenu;
  }



}

?>
