<?php
	require_once("include/Webservices/WebServiceErrorCode.php");
	class WebServiceError{
		
		public $code;
		public $message;
		
		function WebServiceError($errCode,$msg){
			$this->code = $errCode;
			$this->message = $msg;
		}
		
	}
	
?>