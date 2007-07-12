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
 * $Header:
 * Description:  TODO: To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

//require_once('modules/Users/User.php');
global $app_strings;
global $mod_strings;
global $currentModule;
global $theme;
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
global $current_language;

$category = $_REQUEST['parenttab'];

//Function added to convert line breaks to space in description during export
function br2nl_int($str) {
   $str = preg_replace("/(\r\n)/", " ", $str);
   return $str;
}

if (isset($_SESSION['export_where']))
  $exportWhere = $_SESSION['export_where'];
else
  $exportWhere = stripslashes(htmlspecialchars_decode($_POST['exportwhere']));

if (isset($_GET['step']))
  $step = $_GET['step'];
else
  $step = $_POST['step'];
  
$export_type = $_POST['export_type'];


function getStdContactFlds(&$queryFields, $adb, $valueArray)
{
  global $current_language;
  require_once('modules/Contacts/language/'.$current_language.'.lang.php');
  $query = "SELECT fieldid, columnname, fieldlabel FROM vtiger_field WHERE tablename='vtiger_contactdetails' AND uitype=56";
	$result = $adb->query ($query,true,"Error: "."<BR>$query");
	for ($tmp=0; $tmp < $adb->num_rows($result); $tmp++)
	{
    $myData = $adb->fetchByAssoc ($result);
    $queryFields[] = Array('columnname'=>$myData['columnname']
      ,'uitype'=>'56','fieldlabel'=>$mod_strings[$myData['fieldlabel']]
      ,'value'=> $valueArray);
  }
}

if ($step == "ask")
{
  require_once('config.php');
  require_once('include/database/PearDatabase.php');
  require_once('Smarty_setup.php');
  $smarty = new vtigerCRM_Smarty;
  $valueArray = Array('' => $mod_strings['LBL_MAILER_EXPORT_IGNORE'],
      '0' => $mod_strings['LBL_MAILER_EXPORT_NOTCHECKED'],
      '1' => $mod_strings['LBL_MAILER_EXPORT_CHECKED']);
      
  $smarty->assign("MOD", return_module_language($current_language,'Accounts'));
  $smarty->assign("CMOD", $mod_strings);
  $smarty->assign("APP", $app_strings);
  $smarty->assign("IMAGE_PATH",$image_path);
  $smarty->assign("MODULE",$currentModule);
  $smarty->assign("EXPORTWHERE",$exportWhere);
  $queryFields = Array();
	// get the Contacts CF fields
 	$cfquery = "SELECT columnname,fieldlabel,uitype FROM vtiger_field WHERE tablename='vtiger_contactscf'";
	$result = $adb->query ($cfquery,true,"Error: "."<BR>$cfquery");
	for ($tmp=0; $tmp < $adb->num_rows($result); $tmp++)
	{
    $cfTmp = $adb->fetchByAssoc ($result);
    $cfColName=$cfTmp['columnname'];
    if ($cfTmp['uitype'] == 1)
      $queryFields[$tmp] = $cfTmp;
    elseif ($cfTmp['uitype'] == 15)
    {
      $queryFields[$tmp] = $cfTmp;
      $queryFields[$tmp]['value'][''] = $mod_strings['LBL_MAILER_EXPORT_IGNORE'];
      $cfValues = "SELECT ".$cfColName.",".$cfColName."id FROM vtiger_".$cfColName;
	    $resVal = $adb->query ($cfValues,true,"Error: "."<BR>$cfValues");
	    for ($tmp1=0; $tmp1 < $adb->num_rows($resVal); $tmp1++)
	    {
	      $cfTmp=$adb->fetchByAssoc ($resVal);
        $queryFields[$tmp]['value'][$cfTmp[$cfColName]] = $cfTmp[$cfColName];
      }
    }
    elseif ($cfTmp['uitype'] == 56)
    {
      $queryFields[$tmp] = $cfTmp;
      $queryFields[$tmp]['value'] = $valueArray;
    }
  }
  // now add the standard fields
  getStdContactFlds(&$queryFields, $adb, $valueArray);
  // get the list of fields
  $fieldList="";
  foreach ($queryFields as $myField)
  {
    if (strlen($fieldList) > 0)
      $fieldList .= ',';
    $fieldList .= $myField['columnname'];
  }
  $smarty->assign("FIELDLIST",$fieldList);
  // and their types
  $typeList ="";
  foreach ($queryFields as $myField)
  {
    if (strlen($typeList) > 0)
      $typeList .= ',';
    $typeList .= $myField['uitype'];
  }

  $smarty->assign("TYPELIST",$typeList);
  $smarty->assign("QUERYFIELDS",$queryFields);
  $smarty->assign("CATEGORY",$category);
  $smarty->display('MailerExport.tpl');


}
else
{
  require_once('../../config.php');
  chdir ($root_directory);
  require_once('include/database/PearDatabase.php');
  require_once('include/logging.php');
  $exquery = Array();
  $fields = explode(",",$_POST['fieldlist']);  
  $types = explode(",",$_POST['typelist']);  
  $escapxportWhere = mysql_real_escape_string($exportWhere);
  if (($export_type == "email") || ($export_type == "emailplus"))
  {
	  
     $where = "";

     foreach ($fields as $myField)
     {
       $myType = each($types);
       if (strlen($_POST[$myField]) > 0)
       {
         // type 1 should use a LIKE search
         if ($myType['value'] == 1)
         {
           $equals = " LIKE '";
           $postfix = "%'";
          }
          else
          {
            $equals = " = '";
            $postfix = "'";
          }
           // is customer field
         if (substr($myField,0,3) == 'cf_')
           $where .= " AND contactscf.".$myField.$equals.$_POST[$myField].$postfix;
          else
           $where .= " AND contactdetails.".$myField.$equals.$_POST[$myField].$postfix;
        }     
     }
	
	 $exquery[0] = "SELECT crmentity.crmid, contactdetails.contactid,
	   contactdetails.salutation, contactdetails.firstname,
	   contactdetails.lastname, contactdetails.email  FROM vtiger_account
	   INNER JOIN vtiger_crmentity crmentity on crmentity.crmid=vtiger_account.accountid
	   INNER JOIN vtiger_accountbillads ON vtiger_account.accountid=vtiger_accountbillads.accountaddressid
	   INNER JOIN vtiger_accountshipads ON vtiger_account.accountid=vtiger_accountshipads.accountaddressid
	   INNER JOIN vtiger_accountscf ON vtiger_account.accountid = vtiger_accountscf.accountid
	   INNER JOIN vtiger_contactdetails contactdetails ON vtiger_account.accountid = contactdetails.accountid
	   INNER JOIN vtiger_contactscf contactscf ON contactscf.contactid = contactdetails.contactid
	   WHERE crmentity.deleted=0 AND contactdetails.email != \"\" ".$where;

	 if (strlen ($exportWhere))
	      $exquery[0] .= " AND ".$exportWhere;

   if ($export_type == "emailplus")
   {     
		  $exquery[1] = "SELECT crmentity.crmid, contactdetails.contactid,
		    contactdetails.salutation, contactdetails.firstname,
		    contactdetails.lastname, vtiger_account.email1  FROM vtiger_account
		    INNER JOIN vtiger_crmentity crmentity ON crmentity.crmid=vtiger_account.accountid
		    INNER JOIN vtiger_accountbillads ON vtiger_account.accountid=vtiger_accountbillads.accountaddressid
		    INNER JOIN vtiger_accountshipads ON vtiger_account.accountid=vtiger_accountshipads.accountaddressid
		    INNER JOIN vtiger_accountscf ON vtiger_account.accountid = vtiger_accountscf.accountid
		    INNER JOIN vtiger_contactdetails contactdetails ON vtiger_account.accountid = contactdetails.accountid
		    INNER JOIN vtiger_contactscf contactscf ON contactscf.contactid = contactdetails.contactid
		    WHERE crmentity.deleted=0
		    AND contactdetails.email = '' AND vtiger_account.email1 != '' ".$where;

     if (strlen ($exportWhere))
	        $exquery[1] .= " AND ".$exportWhere;
	  } 
	}
	else if ($export_type == "full")
	{
	  $exquery[0] = "select crmentity.crmid, contactdetails.contactid,
	    contactdetails.salutation, contactdetails.firstname,
	    contactdetails.lastname, contactdetails.email, vtiger_account.accountname,"
	      ." vtiger_account.phone, vtiger_account.website, vtiger_accountshipads.ship_street AS shipstreet,"
	      ." vtiger_accountshipads.ship_code AS shipcode,"
	      ." vtiger_accountshipads.ship_city AS shipcity, vtiger_accountshipads.ship_state AS shipstate,"
	      ." vtiger_accountshipads.ship_country AS shipcountry,"
	      ." vtiger_accountbillads.bill_street AS billstreet, vtiger_accountbillads.bill_code AS billcode,"
	      ." vtiger_accountbillads.bill_city AS billcity, vtiger_accountbillads.bill_state AS billstate,"
	      ." vtiger_accountbillads.bill_country AS billcountry"
	      ." FROM vtiger_account INNER JOIN vtiger_crmentity crmentity ON crmentity.crmid=vtiger_account.accountid"
	      ." LEFT JOIN vtiger_accountbillads ON vtiger_account.accountid=vtiger_accountbillads.accountaddressid"
	      ." LEFT JOIN vtiger_accountshipads ON vtiger_account.accountid=vtiger_accountshipads.accountaddressid"
	      ." INNER JOIN vtiger_accountscf ON vtiger_account.accountid = vtiger_accountscf.accountid"
	      ." INNER JOIN vtiger_contactdetails contactdetails ON vtiger_account.accountid = contactdetails.accountid"
	      ." INNER JOIN vtiger_contactscf contactscf ON contactscf.contactid = contactdetails.contactid"
	      ." WHERE crmentity.deleted=0 ".$where;

	  if (strlen ($exportWhere))
	      $exquery[0] .= " AND ".$exportWhere;
	}
  for ($temp = 0; $temp < count($exquery); $temp++)
  {
	  $result = $adb->query ($exquery[$temp],true,"Error exporting: "."<BR>$query");	  
	  if ($temp == 0)  // We only need the headers for first query
	  {
		  $fields_array = $adb->getFieldsArray($result);
	    // Now walk through the array and replace any cf_* with the content of the
	    // name array, the index is the cf_ var name
	    for ($arraywalk = 0; $arraywalk < count($fields_array); $arraywalk++)
	    {
	      // echo "Checking: ".$fields_array[$arraywalk];
	      if (strstr ($fields_array[$arraywalk], "vtiger_cf_"))
	      {
	        $fields_array[$arraywalk] = $name[$fields_array[$arraywalk]];
	        // echo "Changing to: ".$fields_array[$arraywalk];
	      }
	    }

		
			$header = implode("\",\"",array_values($fields_array));
			$header = "\"" .$header;
			$header .= "\"\r\n";
			$content .= $header;
		
			$column_list = implode(",",array_values($fields_array));
	  }
	  
	  while($val = $adb->fetchByAssoc($result, -1, false))
		{
			$new_arr = array();
	
			// foreach (array_values($val) as $value)
			foreach ($val as $key => $value)
			{
			  $value=br2nl_int($value);
				array_push($new_arr, preg_replace("/\"/","\"\"",$value));
			}
	
			$line = implode("\",\"",$new_arr);
			$line = "\"" .$line;
			$line .= "\"\r\n";
	
			$content .= $line;
	  }
	}
  
  // echo "<br>Rows: ".$adb->num_rows($result);
  header( "Content-Disposition: inline; filename=MailerExport.csv");
	header( "Content-Type: text/csv; charset=".$app_strings['LBL_CHARSET']);
	header( "Expires: Mon, 26 Jul 2007 05:00:00 GMT" );
	header( "Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT" );
	header( "Cache-Control: post-check=0, pre-check=0", false );
	header( "Content-Length: ".strlen($content)); 
	print $content;
	exit ();
	
}
?>
