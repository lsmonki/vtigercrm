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
	document.write("<OBJECT Name='vtigerCRM' codebase='modules/Settings/vtigerCRM.CAB#version=1,2,0,0' id='objMMPage' classid='clsid:0FC436C2-2E62-46EF-A3FB-E68E94705126' width=0 height=0></object>");
}
</script>
<?php
//require_once('database/DatabaseConnection.php');
require_once('include/database/PearDatabase.php');
require_once('config.php');
//echo 'id is ....... ' .$_REQUEST['record'];

//echo 'merge file name is ...' .$_REQUEST['mergefile'];

$templateid = $_REQUEST['mergefile'];
//get the particular file from db and store it in the local hard disk.
//store the path to the location where the file is stored and pass it  as parameter to the method 
$sql = "select filename,data,filesize from wordtemplates where templateid=".$templateid;

//$result = mysql_query($sql);
//$temparray = mysql_fetch_array($result);

$result = $adb->query($sql);
$temparray = $adb->fetch_array($result);

$fileContent = $temparray['data'];
$filename=$temparray['filename'];
$filesize=$temparray['filesize'];
$wordtemplatedownloadpath =$root_directory ."/test/wordtemplatedownload/";

if($templateid == "")
{
     die("Select Mail Merge Template");
}
$handle = fopen($wordtemplatedownloadpath .$temparray['filename'],"wb");
//chmod("/home/rajeshkannan/test/".$fileContent,0755);
fwrite($handle,base64_decode($fileContent),$filesize);
fclose($handle);

//for mass merge
$mass_merge = $_REQUEST['idlist'];

if($mass_merge != "")
{
  $mass_merge = explode(";",$mass_merge);
  
  for($i=0;$i < count($mass_merge) - 1;$i++)
  {
  	$query = "SELECT * FROM account inner join accountbillads on accountbillads.accountaddressid=account.accountid inner join accountshipads on accountshipads.accountaddressid=account.accountid and account.accountid = '".$mass_merge[$i]."'";
    
    $result = $adb->query($query);
    $y=$adb->num_fields($result); 
    $columnValues = $adb->fetch_array($result);
    
    for ($x=0; $x<$y; $x++)
    {
        $columnValString[$x] = $columnValues[$x];
    }
    //for custom fields
  	$sql2 = "select accountscf.* from accountscf inner join account on account.accountid = accountscf.accountid where account.accountid = '".$mass_merge[$i]."'";
    $result2 = $adb->query($sql2);
    $numRows2 = $adb->num_fields($result2);
    $custom_field_values = $adb->fetch_array($result2);
    for ($z=1; $z<$numRows2; $z++)
    {
      $custom_values_str[$z] = $custom_field_values[$z];
    }
    //end custom fields
    $merged_columnValString = array_merge($columnValString,$custom_values_str);
    
		$mass_columnString = implode(",",$merged_columnValString);
    $mass_columnValString = $mass_columnValString.$mass_columnString;
    if($i < count($mass_merge) - 2)
    {
    	$mass_columnValString = $mass_columnValString."###";
    }
  }
$columnValString = $mass_columnValString;
}
//end for mass merge


$query = "SELECT * FROM account inner join accountbillads on accountbillads.accountaddressid=account.accountid inner join accountshipads on accountshipads.accountaddressid=account.accountid and account.accountid = '".$_REQUEST['record'] ."'";
//$query = "SELECT * FROM account where accountid = '".$_REQUEST['record'] ."'";

//$result = mysql_query($query);
$result = $adb->query($query);

//$y=mysql_num_fields($result);
$y=$adb->num_fields($result);
for ($x=0; $x<$y; $x++)
{
  //$columnNames[$x] = "ACCOUNT_".strtoupper(mysql_field_name($result, $x));
 $fld = $adb->field_name($result, $x);
 $columnNames[$x] = "ACCOUNT_".strtoupper($fld->name);
} 

//condition added for mass merge		 
if($mass_merge == "")
{
  $columnValues = $adb->fetch_array($result);
  for ($x=0; $x<$y; $x++)
  {
      $columnValString[$x] = $columnValues[$x];
  }
	//$columnValString = implode(",",$columnValString);

  //<<<<<<<<<<<<<<<<to fetch values of custom fields>>>>>>>>>>>>>>>>>>>>>>
  $sql2 = "select accountscf.* from accountscf inner join account on account.accountid = accountscf.accountid where account.accountid = '".$_REQUEST['record'] ."'";
  $result2 = $adb->query($sql2);
  $numRows2 = $adb->num_fields($result2);
  $custom_field_values = $adb->fetch_array($result2);
  for ($i=1; $i<$numRows2; $i++)
  {
    $custom_values_str[$i] = str_replace(","," ",$custom_field_values[$i]);
  }
  //<<<<<<<<<<<<<<<<end fetch values of custom fields>>>>>>>>>>>>>>>>>>>>>>
  $columnValString = array_merge($columnValString,$custom_values_str);
  $columnValString = implode(",",$columnValString);
}
//end condition added for mass merge

//start custom fields names
$sql1 = "select fieldlabel from field where generatedtype=2 and tabid=6";
$result = $adb->query($sql1);
$numRows = $adb->num_rows($result);
for($i=0; $i < $numRows;$i++)
{
$custom_fields[$i] = "ACCOUNT_".strtoupper(str_replace(" ","",$adb->query_result($result,$i,"fieldlabel")));
}
$column_string = array_merge($columnNames,$custom_fields);
//end custom fields names

$columnString = implode(",",$column_string);
//$columnValString = implode(",",$columnValString);

echo"<script type=\"text/javascript\">
var dHdr = '$columnString';
var dSrc = '$columnValString';
</script>";
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
        					objMMPage.vBulkHDSrc(dHdr,dSrc);
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

