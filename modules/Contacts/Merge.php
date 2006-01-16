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
?>
<html>
<body>
<script>
if (document.layers)
{
	document.write("This feature requires IE 5.5 or higher for Windows on Microsoft Windows 2000, Windows NT4 SP6, Windows XP.");
	document.write("<br><br>Click <a href='#' onclick='window.history.back();'>here</a> to return to the previous page");
}	
else if (document.layers || (!document.all && document.getElementById))
{
	document.write("This feature requires IE 5.5 or higher for Windows on Microsoft Windows 2000, Windows NT4 SP6, Windows XP.");
	document.write("<br><br>Click <a href='#' onclick='window.history.back();'>here</a> to return to the previous page");	
}
else if(document.all)
{
	document.write("<OBJECT Name='vtigerCRM' codebase='modules/Settings/vtigerCRM.CAB#version=1,5,0,0' id='objMMPage' classid='clsid:0FC436C2-2E62-46EF-A3FB-E68E94705126' width=0 height=0></object>");
}
</script>
<?php
require_once('include/database/PearDatabase.php');
require_once('config.php');
//echo 'id is ....... ' .$_REQUEST['record'];

//echo 'merge file name is ...' .$_REQUEST['mergefile'];

$templateid = $_REQUEST['mergefile'];
//get the particular file from db and store it in the local hard disk.
//store the path to the location where the file is stored and pass it  as parameter to the method 
$sql = "select filename,data,filesize from wordtemplates where templateid=".$templateid;

$result = $adb->query($sql);
$temparray = $adb->fetch_array($result);

$fileContent = $temparray['data'];
$filename=$temparray['filename'];
$filesize=$temparray['filesize'];
$wordtemplatedownloadpath =$root_directory ."/test/wordtemplatedownload/";

//echo '<br> file name and size is ..'.$filename .'...'.$filesize;
if($templateid == "")
{
die("Select Mail Merge Template");
}
$handle = fopen($wordtemplatedownloadpath .$temparray['filename'],"wb");
fwrite($handle,base64_decode($fileContent),$filesize);
fclose($handle);


//<<<<<<<<<<<<<<<<<<<<<<<<<<<for mass merge>>>>>>>>>>>>>>>>>>>>>>>>
$mass_merge = $_REQUEST['idlist'];
$single_record = $_REQUEST['record'];

if($mass_merge != "")
{	
	$mass_merge = explode(";",$mass_merge);
	$temp_mass_merge = $mass_merge;
	if(array_pop($temp_mass_merge)=="")
		array_pop($mass_merge);
	$mass_merge = implode(",",$mass_merge);
}else if($single_record != "")
{
	$mass_merge = $single_record;	
}else
{
	die("Record Id is not found, cannot merge the document");
}

//<<<<<<<<<<<<<<<<header for csv and select columns for query>>>>>>>>>>>>>>>>>>>>>>>>
$query1="select tab.name,field.tablename,field.columnname,field.fieldlabel from field inner join tab on tab.tabid = field.tabid where field.tabid in (4,6) and field.block <> 4 order by field.tablename";

$result = $adb->query($query1);
$y=$adb->num_rows($result);
	
for ($x=0; $x<$y; $x++)
{ 
  $tablename = $adb->query_result($result,$x,"tablename");
  $columnname = $adb->query_result($result,$x,"columnname");
  $modulename = $adb->query_result($result,$x,"name");
  
	if($tablename == "crmentity")
  {
  	if($modulename == "Accounts")
  	{
  		$tablename = "crmentityAccounts";
  	}
  }
  $querycolumns[$x] = $tablename.".".$columnname;
  if($columnname == "smownerid")
  {
    if($modulename == "Accounts")
    {
			$querycolumns[$x]="concat(usersAccounts.last_name,' ',usersAccounts.first_name) as username";
    }
		if($modulename == "Contacts")
    {
    	$querycolumns[$x]="concat(users.last_name,' ',users.first_name) as usercname,users.first_name,users.last_name,users.user_name,users.yahoo_id,users.title,users.phone_work,users.department,users.phone_mobile,users.phone_other,users.phone_fax,users.email1,users.phone_home,users.email2,users.address_street,users.address_city,users.address_state,users.address_postalcode,users.address_country";
    }
  }
	if($columnname == "parentid")
	{
		$querycolumns[$x] = "accountAccounts.accountname";
	}
	if($columnname == "accountid")
	{
		$querycolumns[$x] = "accountContacts.accountname";
	}
	if($columnname == "reportsto")
	{
		$querycolumns[$x] = "contactdetailsContacts.lastname";
	}
	
	
	if($modulename == "Accounts")
  {
  	$field_label[$x] = "ACCOUNT_".strtoupper(str_replace(" ","",$adb->query_result($result,$x,"fieldlabel")));
  }
	
	if($modulename == "Contacts")
  {
  	$field_label[$x] = "CONTACT_".strtoupper(str_replace(" ","",$adb->query_result($result,$x,"fieldlabel")));
  	if($columnname == "smownerid")
  		{
  			$field_label[$x] = $field_label[$x].",USER_FIRSTNAME,USER_LASTNAME,USER_USERNAME,USER_YAHOOID,USER_TITLE,USER_OFFICEPHONE,USER_DEPARTMENT,USER_MOBILE,USER_OTHERPHONE,USER_FAX,USER_EMAIL,USER_HOMEPHONE,USER_OTHEREMAIL,USER_PRIMARYADDRESS,USER_CITY,USER_STATE,USER_POSTALCODE,USER_COUNTRY";
  		}
  }
    
	
}
$csvheader = implode(",",$field_label);
//echo $csvheader;
//<<<<<<<<<<<<<<<<End>>>>>>>>>>>>>>>>>>>>>>>>
	
if(count($querycolumns) > 0)
{
	$selectcolumns = implode($querycolumns,",");
	

$query = "select ".$selectcolumns." from contactdetails
				inner join crmentity on crmentity.crmid = contactdetails.contactid 
				inner join contactaddress on contactdetails.contactid = contactaddress.contactaddressid 
				inner join contactsubdetails on contactdetails.contactid = contactsubdetails.contactsubscriptionid 
				inner join contactscf on contactdetails.contactid = contactscf.contactid 
				left join contactdetails as contactdetailsContacts on contactdetailsContacts.contactid = contactdetails.reportsto
				left join account as accountContacts on accountContacts.accountid = contactdetails.accountid 
				left join users on users.id = crmentity.smownerid
				left join account on account.accountid = contactdetails.accountid
				left join crmentity as crmentityAccounts on crmentityAccounts.crmid=account.accountid
				left join accountbillads on account.accountid=accountbillads.accountaddressid
				left join accountshipads on account.accountid=accountshipads.accountaddressid
				left join accountscf on account.accountid = accountscf.accountid
				left join account as accountAccounts on accountAccounts.accountid = account.parentid
				left join users as usersAccounts on usersAccounts.id = crmentityAccounts.smownerid 
				where crmentity.deleted=0 and (crmentityAccounts.deleted <> 1) and contactdetails.contactid in(".$mass_merge.")";
				

$result = $adb->query($query);

while($columnValues = $adb->fetch_array($result))
{
	$y=$adb->num_fields($result);
	for($x=0; $x<$y; $x++)
  {
  	$value = $columnValues[$x];
  	//<<<<<<<<<<<<<<< For blank Fields >>>>>>>>>>>>>>>>>>>>>>>>>>>>
  	if($value == "0")
  	{
  		$value = "";
  	}
  	if(trim($value) == "--None--" || trim($value) == "--none--")
  	{
  		$value = "";
  	}
	//<<<<<<<<<<<<<<< End >>>>>>>>>>>>>>>>>>>>>>>>>>>>
		$actual_values[$x] = $value;
		$actual_values[$x] = str_replace('"'," ",$actual_values[$x]);
		//if value contains any line feed or carriage return replace the value with ".value."
		if (preg_match ("/(\r\n)/", $actual_values[$x])) 
		{
			$actual_values[$x] = '"'.$actual_values[$x].'"';
		}
		$actual_values[$x] = str_replace(","," ",$actual_values[$x]);
  }
  
  $mergevalue[] = implode($actual_values,",");  	
}
$csvdata = implode($mergevalue,"###");
}else
{
	die("No fields to do Merge");
}	

$handle = fopen($wordtemplatedownloadpath."datasrc.csv","wb");
fwrite($handle,$csvheader."\r\n");
fwrite($handle,str_replace("###","\r\n",$csvdata));
fclose($handle);

?>
<script>
if (window.ActiveXObject){
	try 
	{
  		ovtigerVM = eval("new ActiveXObject('vtigerCRM.ActiveX');");
  		if(ovtigerVM)
  		{
        	var filename = "<?php echo $filename?>";
        	if(filename != "")
        	{
        		if(objMMPage.bDLTempDoc("<?php echo $site_URL?>/test/wordtemplatedownload/<?php echo $filename?>","MMTemplate.doc"))
        		{
        			try
        			{
        				if(objMMPage.Init())
        				{
        					objMMPage.vLTemplateDoc();
        					objMMPage.bBulkHDSrc("<?php echo $site_URL;?>/test/wordtemplatedownload/datasrc.csv");
        					objMMPage.vBulkOpenDoc();
        					objMMPage.UnInit()
        					window.history.back();
        				}		
        			}catch(errorObject)
        			{
        				document.write("Error while processing mail merge operation");
        			}
        		}else
        		{
        			document.write("Cannot get template document");
        		}
        	}
  		}
		}
	catch(e) {
		document.write("Requires to download ActiveX Control from vtigerCRM. Please, ensure that you have administration privilage");
	}
}
</script>
</body>
</html>
