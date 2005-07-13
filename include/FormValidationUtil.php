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

/*
 * File containing methods to proceed with the ui validation for all the forms
 *
 */

function getDBValidationData($tablearray,$tabid='')
{
  $sql = '';
  $tab_con = "";
  $numValues = count($tablearray);
  global $adb;

  if($tabid!='') $tab_con = ' and tabid='.$tabid;
	
  for($i=0;$i<$numValues;$i++)
  {

  	if(in_array("emails",$tablearray))
  	{
		if($numValues > 1 && $i != $numValues-1)
    		{
			$sql .= "select fieldlabel,fieldname,typeofdata from field where tablename='".$tablearray[$i] ."'and tabid=10 and displaytype <> 2 union ";
     		}
   		else
    		{
   			$sql  .= "select fieldlabel,fieldname,typeofdata from field where tablename='".$tablearray[$i] ."' and tabid=10 and displaytype <> 2 ";
    		}
  	}
  	else
  	{
    		if($numValues > 1 && $i != $numValues-1)
    		{
      			$sql .= "select fieldlabel,fieldname,typeofdata from field where tablename='".$tablearray[$i] ."'".$tab_con." and displaytype in (1,3) union ";
    		}
    		else
    		{
      			$sql  .= "select fieldlabel,fieldname,typeofdata from field where tablename='".$tablearray[$i] ."'".$tab_con." and displaytype in (1,3)";
    		}
  	}
  }
  $result = $adb->query($sql);
  $noofrows = $adb->num_rows($result);
  $fieldName_array = Array();
  for($i=0;$i<$noofrows;$i++)
  {
    $fieldlabel = $adb->query_result($result,$i,'fieldlabel');
    $fieldname = $adb->query_result($result,$i,'fieldname');
    $typeofdata = $adb->query_result($result,$i,'typeofdata');
   //echo '<br> '.$fieldlabel.'....'.$fieldname.'....'.$typeofdata;
    $fldLabel_array = Array();
    $fldLabel_array[$fieldlabel] = $typeofdata;
    $fieldName_array[$fieldname] = $fldLabel_array;

  }

  
  return $fieldName_array;
  


}
?>
