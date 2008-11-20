<?php
//A collection of util functions for the workflow module

class VTWorkflowUtils{
	/**
	 * Check whether the given identifier is valid.
	 */
	function validIdentifier($identifier){
		if(is_string($identifier)){
			return pref_match("/^[a-zA-Z][a-zA-Z_0-9]+$/", $identifier)
		}else{
			return false;
		}
	}
}

?>