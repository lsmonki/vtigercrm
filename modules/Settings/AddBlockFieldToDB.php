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
require_once('Smarty_setup.php');
global $mod_strings;
 $fldmodule=$_REQUEST['fld_module'];
 $fldType= $_REQUEST['fieldType'];
 $parenttab=$_REQUEST['parenttab'];
 $mode=$_REQUEST['mode'];
 $fldlabel = trim($_REQUEST[fldLabel]);
 $tabid = getTabid($fldmodule);

function InStrCount($String,$Find,$CaseSensitive = false) {
    $i=0;
    $x=0;
    while (strlen($String)>=$i) {
     unset($substring);
     if ($CaseSensitive) {
      $Find=strtolower($Find);
      $String=strtolower($String);
     }
     $substring=substr($String,$i,strlen($Find));
     if ($substring==$Find) $x++;
     $i++;
    }
    return $x;
   }
   
if($_REQUEST[mode]=='edit')
{
	
	$dup_check_query = $adb->pquery("SELECT * from vtiger_field WHERE fieldid!=? AND fieldlabel=? AND tabid = ?",array($_REQUEST['fieldselect'],$fldlabel, $tabid));	
	
	if($adb->num_rows($dup_check_query)==0 && trim($fldlabel)!=''){
		$res= $adb->pquery("select fieldlabel,fieldname,tabid from vtiger_field where fieldid = ?",array($_REQUEST['fieldselect']));
		$row= $adb->fetch_array($res);
		if($adb->num_rows($res)!=0)
		{
			echo $sql="update vtiger_field SET fieldlabel='".$fldlabel."' WHERE fieldid=".$_REQUEST['fieldselect'];
			$adb->pquery($sql,array());
			$url='modules/'.$fldmodule.'/language/'.$current_language.'.lang.php';
			require_once($url);
			$text = implode('', file($url));
			$takecopy=$text;
			
			$replace_which="'".$row[fieldlabel]."'=>'".getTranslatedString($row[fieldlabel])."',";
			$replace_by="'".$fldlabel."'=>'".$fldlabel."',";
			$text=str_replace($replace_which,$replace_by,$text);
			
			$old_fldlabel = "LBL_LIST_".strtoupper(str_replace(" ","_",$row[fieldlabel]));
			
			$replace_which="'".$old_fldlabel."'=>+[']+[[:alnum:]*[:space:]*]+[.*?']+[,]";
			$replace_by="'".$old_fldlabel."'=>'".$fldlabel."',";
			$text=ereg_replace($replace_which,$replace_by,$text);

			$fh = fopen($url, 'w') or die("can't open file");
			fwrite($fh, $text);
			fclose($fh);
			
			$old_col_label = $fldmodule."_".str_replace(" ","_",$row[fieldlabel]);
			$customfield_query = $adb->pquery("SELECT columnname from vtiger_cvcolumnlist WHERE columnname LIKE '%".$old_col_label."%'",array());
			
			for($i=0;$i<$adb->num_rows($customfield_query);$i++){
				$new_col_label = $fldmodule."_".str_replace(" ","_",$fldlabel);
				$old_colname = $adb->query_result($customfield_query,$i,'columnname');
				$new_colname = str_replace($old_col_label,$new_col_label,$old_colname);
			$adb->pquery("UPDATE vtiger_cvcolumnlist SET columnname = ? WHERE columnname = ?",array($new_colname,$old_colname));
			}
			$customfield_query = $adb->pquery("SELECT fieldname from vtiger_homemoduleflds WHERE fieldname LIKE '%".$old_col_label."%'",array());
			
			for($i=0;$i<$adb->num_rows($customfield_query);$i++){
				$new_col_label = $fldmodule."_".str_replace(" ","_",$fldlabel);
				$old_colname = $adb->query_result($customfield_query,$i,'fieldname');
				$new_colname = str_replace($old_col_label,$new_col_label,$old_colname);
				$adb->pquery("UPDATE vtiger_homemoduleflds SET fieldname = ? WHERE fieldname = ?",array($new_colname,$old_colname));
			}
		}else{
		
		$url='modules/'.$_REQUEST['fld_module'].'/language/'.$current_language.'.lang.php';
		$text = implode('', file($url));
		$takecopy=$text;
		
		$replace_which="'".$row[fieldlabel]."'=>+[']+[[:alnum:]*[:space:]*]+[.*?']+[,]";
		$replace_by="'".$row[fieldlabel]."'=>'".$fldlabel."',";
		$text=ereg_replace($replace_which,$replace_by,$text);
			
		$old_fldlabel = "LBL_LIST_".strtoupper(str_replace(" ","_",$row[fieldlabel]));
		
		$replace_which="'".$old_fldlabel."'=>+[']+[[:alnum:]*[:space:]*]+[.*?']+[,]";
		$replace_by="'".$old_fldlabel."'=>'".$fldlabel."',";
		$text=str_replace($replace_which,$replace_by,$text);
		
		$fh = fopen($url, 'w') or die("can't open file");
		fwrite($fh, $text);
		fclose($fh);
		
		}///end of inner if
	}
	else{
		$dup_error = 'yes';}
}else{
	if(isset($_REQUEST[field_assignid]))
	{
		//to get the sequence of the field after which the new field will add
		$sql_seq="select * from vtiger_field where fieldid in (".generateQuestionMarks($_REQUEST[field_assignid]).")";
		$res_seq= $adb->pquery($sql_seq,array($_REQUEST[field_assignid]));
	    $row_seq=$adb->fetch_array($res_seq);
		$fld_sequence=$row_seq[sequence];
		$newfld_sequence=$fld_sequence+1;
		$fieldselect=$_REQUEST[fieldselect];
		//end
		foreach($_REQUEST[field_assignid] as $field_id)
		{
		     if($field_id!='')
			 {
				$adb->pquery("update vtiger_field set sequence=sequence+1 WHERE tabid=? and block=?  AND sequence > ?",array($tabid,$_REQUEST[blockid],$fld_sequence));
		        $adb->pquery("update vtiger_field set block=?,sequence = ? WHERE fieldid= ?",array($_REQUEST[blockid],$newfld_sequence,$field_id));
		
			    $fld_sequence++;
				$newfld_sequence++;
			}//check if blank
		}
	}
}	

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
	header("Location:index.php?module=Settings&action=LayoutBlockList&fld_module=".$fldmodule."&parenttab=".$parenttab."&duplicate=".$dup_error);
?>
