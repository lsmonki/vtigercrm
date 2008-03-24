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


require_once('include/utils/utils.php');
require_once 'Excel/reader.php';
require_once('modules/Users/Users.php');
require_once('include/database/PearDatabase.php');

$data = new Spreadsheet_Excel_Reader();
$data->setOutputEncoding('CP1251');
global $log;
$filename = $HTTP_GET_VARS['filename'];
$log->info("filename is ".$filename);
//$filename = $_REQUEST['filename'];
$data->read($filename);

//echo 'First Name';

$firstname = $HTTP_POST_VARS['First_Name'];

$log->info("firstname is ".$firstname);
$lastname = $HTTP_POST_VARS['Last_Name'];

$log->info("lastname is ".$lastname);
$phone = $HTTP_POST_VARS['Phone'];
$log->info("phone is ".$phone);
$mobile = $HTTP_POST_VARS['Mobile'];
$log->info("mobile is ".$mobile);

$company = $HTTP_POST_VARS['Company'];
$log->info("company is ".$company);
//echo $company;

$fax = $HTTP_POST_VARS['Fax'];
$log->info("fax is ".$fax);

$designation = $HTTP_POST_VARS['Designation'];

$log->info("designation is ".$designation);
$email = $HTTP_POST_VARS['Email'];

$log->info("email is ".$email);
$salutation = $HTTP_POST_VARS['Salutation'];
//not being used

$log->info("salutation is  ".$salutation);
//echo 'LeadSource';
$leadsource = $HTTP_POST_VARS['LeadSource'];

$log->info("leadsource is ".$leadsource);  
$website = $HTTP_POST_VARS['Website'];
$log->info("website is ".$website);  
//echo 'Industry';
$industry = $HTTP_POST_VARS['Industry'];
$log->info("industry is ".$industry);
//echo $industry;

//echo 'LeadStatus';
$leadstatus = $HTTP_POST_VARS['LeadStatus'];
$log->info("leadstatus is ".$leadstatus);  
//echo $leadstatus;

//echo 'AnnualRevenue';
$annualrevenue = $HTTP_POST_VARS['Annual_Revenue'];
$log->info("annualrevenue is ".$annualrevenue);  
//echo $annualrevenue;


//echo 'Rating';
$rating = $HTTP_POST_VARS['Rating'];
//echo $rating;


//echo 'LicenseKey';
$licensekey = $HTTP_POST_VARS['License_Key'];
//echo $licensekey;

//echo 'EmpCount';
$employeecount = $HTTP_POST_VARS['Number_of_Employees'];
//echo $employeecount;

//echo 'Assto';
$assignedto = $HTTP_POST_VARS['Assigned_To'];
//echo $assignedto;


//echo 'yahooid';
$yahooid = $HTTP_POST_VARS['Yahoo_ID'];
//echo $yahooid;


//echo 'Street';
$street = $HTTP_POST_VARS['Street'];
//echo 'Postal';
$postalcode = $HTTP_POST_VARS['Postal_Code'];
//echo $postalcode;

//echo 'City';
$city = $HTTP_POST_VARS['City'];
//echo $city;

//echo 'COuntry';
$country = $HTTP_POST_VARS['Country'];
//echo $country;
$description = $HTTP_POST_VARS['Description'];
//echo $description;
$stage = $HTTP_POST_VARS['Stage'];
//echo $description;
function deleteFile($filename)
{
	global $log;
	$log->debug("Entering deleteFile(".$filename.") method ...");
   unlink($filename);	
	$log->debug("Exiting deleteFile method ...");
}


for($i=2;$i<=$data->sheets[0]['numRows']; $i++) 
{

			$value_firstname = $data->sheets[0]['cells'][$i][$firstname];
			$value_lastname = $data->sheets[0]['cells'][$i][$lastname];
			$value_company = $data->sheets[0]['cells'][$i][$company];
			$value_salutation = $data->sheets[0]['cells'][$i][$salutation];
			$value_email = $data->sheets[0]['cells'][$i][$email];
			$value_phone = $data->sheets[0]['cells'][$i][$phone];
			$value_fax = $data->sheets[0]['cells'][$i][$fax];
			$value_mobile = $data->sheets[0]['cells'][$i][$mobile];
			//echo 'mobile ' .$value_mobile;
			$value_designation = $data->sheets[0]['cells'][$i][$designation];
			//echo 'designation ' .$value_designation;
			$value_website= $data->sheets[0]['cells'][$i][$website];
			//echo 'websiteval ' .$value_website;
			$value_leadsource = $data->sheets[0]['cells'][$i][$leadsource];
			$value_industry = $data->sheets[0]['cells'][$i][$industry];
			//echo 'industry ' .$value_industry;
			$value_leadstatus = $data->sheets[0]['cells'][$i][$leadstatus];
			//echo 'leadstatus ' .$value_leadstatus;
			$value_annualrevenue = $data->sheets[0]['cells'][$i][$annualrevenue];
			//echo 'annualrevenue ' .$value_annualrevenue;
			$value_rating = $data->sheets[0]['cells'][$i][$rating];
			//echo 'rating ' .$value_rating;
			$value_licensekey = $data->sheets[0]['cells'][$i][$licensekey];
			//echo 'licensekey ' .$value_licensekey;
			$value_employeecount = $data->sheets[0]['cells'][$i][$employeecount];
			//echo 'employees ' .$value_employeecount;
			$value_assignedto = $data->sheets[0]['cells'][$i][$assignedto];
			$value_yahooid = $data->sheets[0]['cells'][$i][$yahooid];
			//echo 'yahooid ' .$value_yahooid;
			$value_street = $data->sheets[0]['cells'][$i][$street];
			//echo 'street ' .$value_street;
			$value_postalcode = $data->sheets[0]['cells'][$i][$postalcode];
			//echo 'postalcode ' .$value_postalcode;
			$value_city = $data->sheets[0]['cells'][$i][$city];
			//echo 'city ' .$value_city;
			$value_country = $data->sheets[0]['cells'][$i][$country];
			//echo 'country ' .$value_country;
			$value_description = $data->sheets[0]['cells'][$i][$description];
			//echo 'description ' .$value_description;
			$value_stage = $data->sheets[0]['cells'][$i][$stage];
			//echo 'stage ' .$value_stage;
			$id = insert2DB($value_salutation,$value_firstname,$value_lastname,$value_company,$value_designation,$value_leadsource,$value_industry,$value_annualrevenue,$value_licensekey,$value_phone,$value_mobile,$value_fax,$value_email,$value_yahooid,$value_website,$value_leadstatus,$value_rating,$value_employeecount);
			//Inserting Custom Field Values
			$dbquery="select * from customfields where module='Leads'";
			$custresult = $adb->pquery($dbquery, array());
			if($adb->num_rows($custresult) != 0)
			{
				$noofrows = $adb->num_rows($custresult);
				$columns='';
				$params=array();
				for($j=0; $j<$noofrows; $j++)
				{
					$fldLabel=$adb->query_result($custresult,$j,"fieldlabel");
					$colName=$adb->query_result($custresult,$j,"column_name");
					$colNameMapping = $HTTP_POST_VARS[$colName];
					$value_colName = $data->sheets[0]['cells'][$i][$colNameMapping];

					if($j == 0)
					{
						$columns='leadid, '.$colName;
						array_push($params, $id, $value_colName);
					}
					else
					{
						$columns .= ', '.$colName;
						array_push($params, $value_colName);
					} 
				}
				$insert_custfld_query = 'insert into leadcf ('.$columns.') values('. generateQuestionMarks($params) .')';
				$adb->pquery($insert_custfld_query, $params);
			}

}

deleteFile($filename);

function insert2DB($salutation,$firstname,$lastname,$company,$designation,$leadsrc,$industry,$annualrevenue,$licensekey,$phone,$mobile,$fax,$email,$yahooid,$website,$leadstatus,$rating,$empct)
{
  global $log;
  $log->debug("Entering insert2DB(".$salutation.",".$firstname.",".$lastname.",".$company.",".$designation.",".$leadsrc.",".$industry.",".$annualrevenue.",".$licensekey.",".$phone.",".$mobile.",".$fax.",".$email.",".$yahooid.",".$website.",".$leadstatus.",".$rating.",".$empct.") method ...");
  $id = create_guid();
  $date_entered = date('YmdHis');
  $date_modified = date('YmdHis');
  global $current_user;	

  $modified_user_id = $current_user->id;
  $assigned_user_id = $current_user->id;
  $sql = "INSERT INTO leads (id,date_entered,date_modified,modified_user_id,assigned_user_id,salutation,first_name,last_name,company,designation,lead_source,industry,annual_revenue,license_key,phone,mobile,fax,email,yahoo_id,website,lead_status,rating,employees) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
  $params = array($id, $adb->formatDate($date_entered, true), $adb->formatDate($date_modified, true), $modified_user_id,$assigned_user_id,$salutation,$firstname,$lastname,$company,$designation,$leadsrc,$industry,$annualrevenue,$licensekey,$phone,$mobile,$fax,$email,$yahooid,$website,$leadstatus,$rating,$empcount);
  $result = $adb->pquery($sql, $params);
  $log->debug("Exiting insert2DB method ...");
  return $id;	

}

header("Location: index.php?module=Leads&action=index");
//	echo 'Thank You! Your data has been stored! <br>
//  <a href="index.php?module=Leads&action=index">Continue</a>';
?>
