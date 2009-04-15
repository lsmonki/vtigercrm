<?php
//A collection of util functions for the workflow module

class VTWorkflowUtils{

	function __construct(){
		global $current_user;
		$this->userStack = array();
	}
	/**
	 * Check whether the given identifier is valid.
	 */
	function validIdentifier($identifier){
		if(is_string($identifier)){
			return pref_match("/^[a-zA-Z][a-zA-Z_0-9]+$/", $identifier);
		}else{
			return false;
		}
	}


	/**
	 * Push the admin user on to the user stack
	 * and make it the $current_user
	 *
	 */
	function adminUser(){
		$user = new Users();
		$user->retrieveCurrentUserInfoFromFile(1);
		global $current_user;
		array_push($this->userStack, $current_user);
		$current_user = $user;
		return $user;
	}

	/**
	 * Revert to the previous use on the user stack
	 */
	function revertUser(){
		global $current_user;
		if(count($this->userStack)!=0){
			$current_user = array_pop($this->userStack);
		}else{
			$current_user = null;
		}
		return $current_user;
	}

	/**
	 * Get the current user
	 */
	function currentUser(){
		return $current_user;
	}

	/**
	 * The the webservice entity type of an EntityData object
	 */
	function toWSModuleName($entityData){
		$moduleName = $entityData->getModuleName();
		if($moduleName == 'Activity'){
			$arr = array('Task' => 'Calendar', 'Email' => 'Emails');
			$moduleName = $arr[$entityData->get('activitytype')];
			if($moduleName == null){
				$moduleName = 'Events';
			}
		}
		return $moduleName;
	}

	/**
	 * Get a wsEntityId for and entityData object
	 */
	function wsEnitityId($entityData){
		$moduleName = $this->toWSModuleName($entityData);
		return vtws_getWebserviceEntityId($wsModuleName, $entityData->getId());
	}

	/**
	 * Insert redirection script
	 */
	function redirectTo($to, $message){
				?>
		<script type="text/javascript" charset="utf-8">
			window.location="<?=$to?>";
		</script>
		<a href="<?=$to?>"><?=$message?></a>
		<?php
	}

	/**
	 * Check if the current user is admin
	 */
	function checkAdminAccess(){
		global $current_user;
		return strtolower($current_user->is_admin)==='on';
	}
}

?>