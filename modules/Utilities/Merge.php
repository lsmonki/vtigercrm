<html>
<body>
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
require_once('include/database/PearDatabase.php');
//echo 'id is ....... ' .$_REQUEST['record'];

//echo 'merge file name is ...' .$_REQUEST['mergefile'];

$mergeFileName = $_REQUEST['mergefile'];
//get the particular file from db and store it in the local hard disk.
//store the path to the location where the file is stored and pass it  as parameter to the method 
$sql = "select filename,data,filesize from wordtemplatestorage where filename='".$mergeFileName."'";

$result = $adb->query($sql);
$temparray = $adb->fetch_array($result);

$fileContent = $temparray['data'];
$filename=$temparray['filename'];
$filesize=$temparray['filesize'];
$wordtemplatedownloadpath =$_SERVER['DOCUMENT_ROOT'] ."/test/wordtemplatedownload/";

//echo '<br> file name and size is ..'.$filename .'...'.$filesize;
$handle = fopen($wordtemplatedownloadpath .$temparray['filename'],"wb") ;
//chmod("/home/rajeshkannan/test/".$fileContent,0755);
fwrite($handle,base64_decode($fileContent),$filesize);
fclose($handle);


$query = "SELECT * FROM " .$_REQUEST["module"] ." where id = '".$_REQUEST['record'] ."'";
//echo $query;
$result = $adb->query($query);

$y=$adb->num_fields($result);

for ($x=0; $x<$y; $x++)
{
    $columnNames[$x] = "CONTACT_".strtoupper($adb->field_name($result, $x));
} 

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

<OBJECT Name="vtigerVM" codebase="http://<?php echo $_SERVER["HTTP_HOST"] ?>/modules/Settings/vtigerVM.CAB#Version1,0,0,1"
id="objMMPage" classid="clsid:42C50C38-1984-4393-A736-890357E7112B" width=0 height=0></object><!--METADATA TYPE="MsHtmlPageDesigner" endspan-->
<Script>
		if(objMMPage.bDLTempDoc("http://"+"<?php echo $_SERVER["HTTP_HOST"] ?>/test/wordtemplatedownload/"+"<?php echo $filename?>","MMTemplate.doc"))
{
	try
	{
		if(objMMPage.Init())
		{
			objMMPage.vLTemplateDoc();
			//objMMPage.vGetHDSrc(dHdr,dSrc);
			objMMPage.vBulkHDSrc(dHdr,dSrc);
		   //objMMPage.vOpenDoc();
			objMMPage.vBulkOpenDoc();
			objMMPage.UnInit()
			document.write("Template Document Merged with selected contacts data");
		}		
	}catch(errorObject)
	{
	}
}
</Script>
</body>
</html>

