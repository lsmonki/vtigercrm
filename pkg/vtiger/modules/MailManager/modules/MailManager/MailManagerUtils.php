<?php
	
	function getDocus($userid){
		global $adb;
		$sql = $adb->pquery('SELECT * FROM vtiger_notes INNER JOIN vtiger_crmentity on 
			vtiger_crmentity.crmid=vtiger_notes.notesid WHERE filename !=? AND vtiger_crmentity.smownerid =? 
			AND vtiger_crmentity.deleted = ?',
				array('',$userid,0));
		
		$ret_array = array();
		if($adb->num_rows($sql)){
			for($i=0;$i<$adb->num_rows($sql);$i++){
				$ret_array[$i]['title']= $adb->query_result($sql,$i,'title');
				$ret_array[$i]['filename']= $adb->query_result($sql,$i,'title');
			}
		}
		return $ret_array;
		
	}



?>
