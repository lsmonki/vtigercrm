<?php
	require_once("webservices/WebServiceErrorCode.php");
	class WebServiceError{
		
		public $errorCode;
		public $errorMsg;
		
		function WebServiceError($errCode,$msg){
			$this->errorCode = $errCode;
			$this->errorMsg = $msg;
		}
		
	}
	
?>