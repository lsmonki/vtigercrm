<?php
	require_once("include/Webservices/WebServiceErrorCode.php");
	class WebServiceException extends Exception {
		
		public $code;
		public $message;
		
		function WebServiceException($errCode,$msg){
			$this->code = $errCode;
			$this->message = $msg;
		}
		
	}
	
?>