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
	document.write("<OBJECT Name='vtigerCRM' codebase='../modules/Settings/vtigerCRM.CAB#Version1,0,0,1' id='objMMPage' classid='clsid:0FC436C2-2E62-46EF-A3FB-E68E94705126' width=0 height=0></object>");
}
</script>
<?php
//require_once('database/DatabaseConnection.php');
require_once('include/database/PearDatabase.php');
require_once('config.php');
//echo 'id is ....... ' .$_REQUEST['record'];

//echo 'merge file name is ...' .$_REQUEST['mergefile'];

$mergeFileName = $_REQUEST['mergefile'];
//get the particular file from db and store it in the local hard disk.
//store the path to the location where the file is stored and pass it  as parameter to the method 
$sql = "select filename,data,filesize from wordtemplates where filename='".$mergeFileName."'";

//$result = mysql_query($sql);
//$temparray = mysql_fetch_array($result);

$result = $adb->query($sql);
$temparray = $adb->fetch_array($result);

$fileContent = $temparray['data'];
$filename=$temparray['filename'];
$filesize=$temparray['filesize'];
$wordtemplatedownloadpath =$root_directory ."/test/wordtemplatedownload/";

if($mergeFileName == "")
{
die("Select Mail Merge Template");
}
$handle = fopen($wordtemplatedownloadpath .$temparray['filename'],"wb");
//chmod("/home/rajeshkannan/test/".$fileContent,0755);
fwrite($handle,base64_decode($fileContent),$filesize);
fclose($handle);


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

//$columnValues = mysql_fetch_array($result);
$columnValues = $adb->fetch_array($result);
for ($x=0; $x<$y; $x++)
{
    $columnValString[$x] = $columnValues[$x];
}

$columnString = implode(",",$columnNames);
$columnValString = implode(",",$columnValString);

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

