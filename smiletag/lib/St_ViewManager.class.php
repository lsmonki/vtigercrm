<?php
	
	/**
	* Handle everything that related to viewing smiletag
	*
	* Loads data from persistence object, and view it, use the ConfigManager class
	*
	* @package Smiletag
	* @author Yuniar Setiawan <yuniarsetiawan@smiletag.com>
	* @since 2.0
	*/
	
	class St_ViewManager{
		/**
		* @access private
		* Hold configuration for everything
		*/
		var $configManager;
		
		function St_ViewManager(){
			$this->configManager =& new St_ConfigManager();
		}
		
		
		/**
		* Load the data from persistence object, assign it to template parser
		* and display the generated views to the screen
		*
		* @access public
		* @return no return value, this function echo the result to output
		*/
		function display(){
			
			//load data from persistence object
			$persistenceManager =& new St_PersistenceManager();
			$storageType 		=  $this->configManager->getStorageType();
			
					
			if(strtolower($storageType) == 'file'){
				$fileName = $this->configManager->getDataDir().'/'.$this->configManager->getMessageFileName();
				$persistenceManager->setStorageType('file');
				$persistenceManager->setMessageFile($fileName);
			}elseif(strtolower($storageType) == 'mysql'){
				die("MySQL storage type, not implemented yet!");
			}else{
				die("Unknown storage type!");
			}
			
			
			//data stored as array
			$messageArray = $persistenceManager->getMessageArray($this->configManager->getMaxMessageNumber());
			
						
			//create template parser and assign the array
			$templateParser =& new St_TemplateParser();
			$templateParser->setMessage($messageArray);
			$templateParser->setTimeZone($this->configManager->getTimeZone());
			$templateParser->setDateFormat($this->configManager->getDateFormat());
			$templateParser->setTimeFormat($this->configManager->getTimeFormat());
			
			//if alternate custom text option enabled
			if($this->configManager->isCustomTextEnabled()){
				$templateParser->setCustomTextPair($this->configManager->getCustomTextPair());
			}
			
			$output = $templateParser->parse();
			
			$this->sendHeader(); 
			echo $output;		
					
		}
		
		function sendHeader(){
			header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");		        // expires in the past
			header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");     // Last modified, right now
			header("Cache-Control: no-cache, must-revalidate");	        // Prevent caching, HTTP/1.1
			header("Pragma: no-cache");
		}
	}
?>