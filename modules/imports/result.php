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


require_once('include/utils.php');
require_once 'Excel/reader.php';

$data = new Spreadsheet_Excel_Reader();
$data->setOutputEncoding('CP1251');

$filename = $HTTP_GET_VARS['filename'];
//$filename = $_REQUEST['filename'];
$data->read($filename);

//echo 'First Name';

$firstname = $HTTP_POST_VARS['First_Name'];
//echo $firstname;

////echo 'Last Name';
$lastname = $HTTP_POST_VARS['Last_Name'];
//echo $lastname;

//echo 'Email';
$email = $HTTP_POST_VARS['Email'];
//echo $email;

//echo 'Company';
$company = $HTTP_POST_VARS['Company'];
//echo $company;

//echo 'Phone';
$phone = $HTTP_POST_VARS['Phone'];
//echo $phone;

//echo 'Fax';
$fax = $HTTP_POST_VARS['Fax'];
//echo $fax;

//echo 'Mobile';
$mobile = $HTTP_POST_VARS['Mobile'];
//echo $mobile;

//echo 'Designation';
$designation = $HTTP_POST_VARS['Designation'];
//echo $designation;

//echo 'Website';
$website = $HTTP_POST_VARS['Website'];
//echo $website;

//echo 'LeadSource';
$leadsource = $HTTP_POST_VARS['LeadSource'];
//echo $leadsource;

//echo 'Industry';
$industry = $HTTP_POST_VARS['Industry'];
//echo $industry;

//echo 'LeadStatus';
$leadstatus = $HTTP_POST_VARS['LeadStatus'];
//echo $leadstatus;

//echo 'AnnualRevenue';
$annualrevenue = $HTTP_POST_VARS['Annual_Revenue'];
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
$yahooid = $HTTP_POST_VARS['Yahoo_Id'];
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
//echo 'Descri';
$description = $HTTP_POST_VARS['Description'];
//echo $description;


//echo $filereferer->sheets[0]['numRows'];

//for($i=1;$i<$data->sheets[0]['numRows']; $i++) 
for($i=1;$i<$data->sheets[0]['numRows']; $i++) 
{

			$value_firstname = $data->sheets[0]['cells'][$i][$firstname];
			$value_lastname = $data->sheets[0]['cells'][$i][$lastname];
			$value_company = $data->sheets[0]['cells'][$i][$company];
			$value_salutation = $data->sheets[0]['cells'][$i][$salutation];
			$value_email = $data->sheets[0]['cells'][$i][$email];
			$value_phone = $data->sheets[0]['cells'][$i][$phone];
			$value_fax = $data->sheets[0]['cells'][$i][$fax];
			$value_mobile = $data->sheets[0]['cells'][$i][$mobile];
			$value_designation = $data->sheets[0]['cells'][$i][$designation];
			$value_website= $data->sheets[0]['cells'][$i][$website];
			$value_leadsource = $data->sheets[0]['cells'][$i][$leadsource];
			$value_industry = $data->sheets[0]['cells'][$i][$industry];
			$value_leadstatus = $data->sheets[0]['cells'][$i][$leadstatus];
			$value_annualrevenue = $data->sheets[0]['cells'][$i][$annualrevenue];
			$value_rating = $data->sheets[0]['cells'][$i][$rating];
			$value_licensekey = $data->sheets[0]['cells'][$i][$licensekey];
			$value_employeecount = $data->sheets[0]['cells'][$i][$employeecount];
			$value_assignedto = $data->sheets[0]['cells'][$i][$assignedto];
			$value_yahooid = $data->sheets[0]['cells'][$i][$yahooid];
			$value_street = $data->sheets[0]['cells'][$i][$street];
			$value_postalcode = $data->sheets[0]['cells'][$i][$postalcode];
			$value_city = $data->sheets[0]['cells'][$i][$city];
			$value_country = $data->sheets[0]['cells'][$i][$country];
			$value_description = $data->sheets[0]['cells'][$i][$description];
                        
			insert2DB($value_saluation,$value_firstname,$value_lastname,$value_company,$value_designation,$value_leadsource,$value_industry,$value_annualrevenue,$value_licensekey,$value_phone,$value_mobile,$value_fax,$value_email,$value_yahooid,$value_website,$value_leadstatus,$value_rating,$value_employees);
}


function insert2DB($saluation,$firstname,$lastname,$company,$designation,$leadsrc,$industry,$annualrevenue,$licensekey,$phone,$mobile,$fax,$email,$yahooid,$website,$leadstatus,$rating,$empct)
{
  $id = create_guid();
  $date_entered = date('YmdHis');
  $date_modified = date('YmdHis');
  if (isset($current_user))
  {
	 $modified_user_id = $current_user->id;
	 $assigned_user_id = $current_user->id;
   }	
  $sql = "INSERT INTO leads (id,date_entered,date_modified,modified_user_id,assigned_user_id,salutation,first_name,last_name,company,designation,lead_source,industry,annual_revenue,license_key,phone,mobile,fax,email,yahoo_id,website,lead_status,rating,employees) VALUES ('$id','$date_entered','$date_modified','$modified_user_id','$assigned_user_id','$salutation','$firstname','$lastname','$company','$designation','$leadsource','$industry','$annualrevenue','$licensekey','$phone','$mobile','$fax','$email','$website','$leadstatus','$rating','$empcount','')";
  $result = mysql_query($sql);

}

  echo "Thank you! Information entered.\n";
?>


