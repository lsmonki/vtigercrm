<?php
	/**
	* Handle everything that related to rule processing in smiletag
	*
	* Checks the flood, ban, max character length in a message,etc 
	*
	* @package Smiletag
	* @author Yuniar Setiawan <yuniarsetiawan@smiletag.com>
	* @since 2.0
	*/
	class St_RuleProcessor{
		
		/**
		* @access private
		* The input data which will be processed by all method, this is read only!
		*/
		var $data;
		
		/**
		* @access private
		* Handle configuration for everything
		*/
		var $configuration;
		
		/**
		* @access private
		* The error message
		*/
		var $errorMessage;
		
		/**
		* Gets the error message
		*
		* @access public
		* @return string the error message
		*/
		function getErrorMessage(){
			return $this->errorMessage;
		}
		
		/**
		* Sets the configuration for rule processing
		* @access public
		* @param St_ConfigManager configuration
		*/
		function setConfiguration(&$config){
			$this->configuration = $config;
		}
		
		
		function setData($data){
			$this->data = $data;
		}
		
		/**
		* Checks if the given input is having the allowed character size
		* including nickname, url and message
		* TODO: this function should handle UTF-8 encoding differently
		*
		* @access public
		* @return boolean true if allowed
		*/
		function isInputLengthAllowed(){
			$input	   = $this->data;
			$maxLength = $this->configuration->getMaxInputLength();
			
			if((strlen($input['message']) <= $maxLength['message']) and
			   (strlen($input['name']) <= $maxLength['name']) and 
			   (strlen($input['url']) <= $maxLength['url'])){
			 	
			   	return true;
			}else{
				$error = '- ';				
				if((strlen($input['message']) > $maxLength['message'])){
					$error .= 'Message ('.$maxLength['message'].' characters) -';	
				}
				
				if ((strlen($input['name']) > $maxLength['name'])){
					$error .= 'Name ('.$maxLength['name'].' characters) -';
				}
				
				if ((strlen($input['url']) > $maxLength['url'])){
					$error .= 'URL ('.$maxLength['url'].' characters) -';
				}
				
				$this->errorMessage = $error;
				
				return false;
			}
		}
		
		/**
		* Checks the interval between posts from a user
		*
		* @access public
		* @param array $prevMessage the array containing previous post data
		* @return boolean true if it is flood
		*/
		function isFloodPost($prevMessage){
			$currentMessage = $this->data;
			$timeDiff 		= $currentMessage['datetime'] - $prevMessage['datetime'];
			$floodInterval	= $this->configuration->getFloodInterval();
			
			if(($timeDiff <= $floodInterval) && ($floodInterval != 0)){
				return true;
			}else {
				return false;
			}
		}
		
		/**
		* Checks the status of the board
		*
		* @access public
		* @return boolean true if enabled
		*/
		function isBoardEnabled(){
			return $this->configuration->isBoardEnabled();
		}
		
		/**
		* Checks whether the current poster is having banned ip address or nickname
		*
		* @access public
		* @return boolean true if enabled
		*/
		function isBanned(){
			$currentIpAddress = trim($this->data['ipaddress']);
			$currentNickname  = strtolower(trim($this->data['name']));
			
			$ipAddressList	= $this->configuration->getBannedIpAddress();
			$nicknameList	= $this->configuration->getBannedNickname();
			
			//checks for ipaddress
			if(is_array($ipAddressList) && in_array($currentIpAddress,$ipAddressList)){
				$this->errorMessage = "Your IP Address ($currentIpAddress) has been banned. Please contact the administrator.";	
				return true;
			}
			
			//checks for nickname
			if(is_array($nicknameList) && in_array($currentNickname,$nicknameList)){
				$this->errorMessage = "Your nickname ($currentNickname) has been banned. Please use another nickname.";	
				return true;
			}
			
			return false;
		}
		
	}
?>