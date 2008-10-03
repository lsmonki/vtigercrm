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

 $fldmodule=$_REQUEST['fld_module'];
 $fldType= $_REQUEST['fieldType'];
 $parenttab=$_REQUEST['parenttab'];
 $tabid = getTabid($fldmodule);
//print_r($_REQUEST[field_assignid]);
//exit;
if(isset($_REQUEST[field_assignid]))
{
	//to get the sequence of the field after which the new field will add
	$sql_seq="select * from vtiger_field where tabid='".$_REQUEST[tabid]."' and block='".$_REQUEST[blockid]."' order by sequence desc limit 0,1";
	$res_seq= $adb->query($sql_seq);
    $row_seq=$adb->fetch_array($res_seq);
	$fld_sequence=$row_seq[sequence];
	$newfld_sequence=$fld_sequence+1;
	$fieldselect=$_REQUEST[fieldselect];
	//end
	//print_r($_REQUEST[field_assignid]);
	$field_assignid=explode(',',$_REQUEST[field_assignid]);
	foreach($field_assignid as $field_id)
	{
		if($field_id!='')
		{
			$sql="update vtiger_field set block='".$_REQUEST[blockid]."',sequence='".$newfld_sequence."' where fieldid='".$field_id."'";	
			$adb->query($sql);
		 	$newfld_sequence++;
		}//check if blank
	}
}	

$url='modules/'.$_REQUEST['fld_module'].'/language/'.$current_language.'.lang.php';
$text = implode('', file($url));
$takecopy=$text;

$sql="select blocklabel from vtiger_blocks where blockid='".$_REQUEST['deleteblockid']."'";
$res= $adb->query($sql);
$row= $adb->fetch_array($res);
$replace_which="'".$row[blocklabel]."'=>+[']+[[:alnum:]*[:space:]*]+[.*?']+[,]";

$replace_by=" ";
$text=ereg_replace($replace_which,$replace_by,$text);

//echo "<br>".$text;
$fh = fopen($url, 'w') or die("can't open file");
fwrite($fh, $text);
fclose($fh);
/*$filename='en_gb.lang'.date('Y_m_d_H_i_s').'.php';
$myFile ='modules/'.$_REQUEST['fld_module'].'/language/'.$filename;
$fh = fopen($myFile, 'w') or die("can't open file");
fwrite($fh,$takecopy);
fclose($fh);

$sql="INSERT INTO ontap_languagefile SET module='".$_REQUEST['fld_module']."',language='en_gb',filename='".$filename."'";
$res=$adb->query($sql);

echo $sql="SELECT module,id,filename from ontap_languagefile where language='en_gb' and module='".$_REQUEST['fld_module']."' order by id desc";
$res=$adb->query($sql);
echo "morows=".$norows=$adb->num_rows($res);
if($norows>5)
{
for($i=5;$i<=$norows;$i++)
{
$id = $adb->query_result($res,$i,'id');
echo $sql_del="DELETE from ontap_languagefile where id='".$id."'";
$res_del=$adb->query($sql_del);
$filepath='modules/'.$_REQUEST['fld_module'].'/language/';
$filename=$adb->query_result($res,$i,'filename');

fileDelete($filepath,$filename);

}
}
/////for en_us.lang.php file///////

$url='modules/'.$_REQUEST['fld_module'].'/language/en_us.lang.php';
$text = implode('', file($url));
$takecopy=$text;

$text=ereg_replace($replace_which,$replace_by,$text);

$fh = fopen($url, 'w') or die("can't open file");
fwrite($fh, $text);
fclose($fh);
$filename='en_us.lang'.date('Y_m_d_H_i_s').'.php';
$myFile ='modules/'.$_REQUEST['fld_module'].'/language/'.$filename;
$fh = fopen($myFile, 'w') or die("can't open file");
fwrite($fh,$takecopy);
fclose($fh);

$sql="INSERT INTO ontap_languagefile SET module='".$_REQUEST['fld_module']."',language='en_us',filename='".$filename."'";
$res=$adb->query($sql);
///end of en_ud.lang.php file
echo $sql="SELECT module,id,filename from ontap_languagefile where language='en_us' and module='".$_REQUEST['fld_module']."' order by id desc";
$res=$adb->query($sql);
$norows=$adb->num_rows($res);
if($norows>5)
{
for($i=5;$i<=$norows;$i++)
{
$id = $adb->query_result($res,$i,'id');
echo $sql_del="DELETE from ontap_languagefile where id='".$id."'";
$res_del=$adb->query($sql_del);
$filepath='modules/'.$_REQUEST['fld_module'].'/language/';
$filename=$adb->query_result($res,$i,'filename');

fileDelete($filepath,$filename);

}
}
*/
////to delete file that are old ////

 function fileDelete($filepath,$filename) {  
        $success = FALSE;  
       if (file_exists($filepath.$filename)&&$filename!=""&&$filename!="n/a") {  
         unlink ($filepath.$filename);  
         $success = TRUE;  
         }  
     return $success;      
   }  
   
///end of delete///////////  
$sql="delete from vtiger_blocks where blockid='".$_REQUEST[deleteblockid]."'";
$adb->query($sql);
	header("Location:index.php?module=Settings&action=LayoutBlockList&fld_module=".$fldmodule."&parenttab=".$parenttab);
?>
