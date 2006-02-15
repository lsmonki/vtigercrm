<?php
	/**
	* Handle everything that related to posting a message to smiletag
	*
	* Check permission, rules, filtering and saves the message
	*
	* @package Smiletag
	* @author Yuniar Setiawan <yuniarsetiawan@smiletag.com>
	* @since 2.0
	*/
	class St_PostManager{
		/**
		* @access private
		* input variable
		*/
		var $newMessage;
		/**
		* @access private
		* Handle configuration for everything
		*/
		var $configManager;
		
		/**
		* @access private
		* Handle persistence operation
		*/
		var $persistenceManager;
		
		/**
		* @access private
		* The error message
		*/
		var $errorMessage;
		
		
		function St_PostManager(){
			//initiate configManager
			$this->configManager =& new St_ConfigManager();
		
			//initiate persistenceManager
			$this->persistenceManager =& new St_PersistenceManager();
		}
		
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
		* Main function to post message, this function delegate rules to other function/object
		* If something goes wrong, this function produce errorMessage and return false
		*
		* @access public
		* @return boolean true if no error
		*/
		function doPost($HttpRequest){
			$this->newMessage = $HttpRequest;
			
			//get the GMT timestamp and remote ip address
			$this->newMessage['datetime'] = time();
			$this->newMessage['ipaddress'] = $_SERVER['REMOTE_ADDR'];
			
			$prevMessage 	  = $this->newMessage; //this is used to saved previous post in session
			
			
			/* Rule processing **************************************************/
			// This block only check permission/rule, no modification to input 
			
			// 1 ----------------------------------------------
			//check whether a nickname post the same data twice
			if(!empty($_SESSION['prev_message'])){
				if((trim($_SESSION['prev_message']['message']) == trim($prevMessage['message'])) and
				   (trim($_SESSION['prev_message']['name']) == trim($prevMessage['name']))){
					
				   	$this->errorMessage = "You cannot post the same message twice!";
					return false;
				}
			}
						
			$ruleProcessor =& new St_RuleProcessor();
			$ruleProcessor->setConfiguration($this->configManager);
			$ruleProcessor->setData($this->newMessage);
			
			// 2 ----------------------------------------------
			//check if the board enabled		
			if(!$ruleProcessor->isBoardEnabled()){
				$this->errorMessage = 'This board has been locked by administrator. No more post allowed.';
				return false;
			}
			
			// 3 ----------------------------------------------
			//check whether the poster nickname or ip address is banned
			if($this->configManager->isBanEnabled() && $ruleProcessor->isBanned()){
				$this->errorMessage = $ruleProcessor->getErrorMessage();
				return false;
			}
			
			// 4 ----------------------------------------------
			//check whether this post is a flood or not
			if(!empty($_SESSION['prev_message'])){
				if($ruleProcessor->isFloodPost($_SESSION['prev_message'])){
					$this->errorMessage = 'Flood protection enabled by administrator. You must wait for '.$this->configManager->getFloodInterval().' seconds before posting another message.';
					
					//save it for future reference
					$_SESSION['prev_message'] = $this->newMessage;
					
					//save the flood attempt number
					if(empty($_SESSION['flood_attempt'])){
						$_SESSION['flood_attempt'] = 1;
					}else{
						$_SESSION['flood_attempt'] += 1;
						
						//if the number of flood attempts above the maximum allowed
						//as in auto_ban_trigger parameter, then auto ban this ip address
						if($this->configManager->isAutoBanEnabled()){
							if($_SESSION['flood_attempt'] >= $this->configManager->getMaxFlood()){
								//ban the ip address
								$storage_type = $this->configManager->getStorageType();
								if(strtolower($storage_type) == 'file'){
									$fileName = $this->configManager->getDataDir().'/'.$this->configManager->getBanFileName();
									$this->persistenceManager->setStorageType('file');
									$this->persistenceManager->setBanFile($fileName);
								}elseif(strtolower($storage_type) == 'mysql'){
									die("MySQL storage type, not implemented yet!");
								}else{
									die("Unknown storage type!");
								}
								
								$this->persistenceManager->banIpAddress($this->newMessage['ipaddress']);
							}
						}
					}
					
					return false;
				}
			}

			// 5 ----------------------------------------------		
			//check the maximum input size in a message
			if(!$ruleProcessor->isInputLengthAllowed()){
				$this->errorMessage = 'The administrator has set a maximum of '.$ruleProcessor->getErrorMessage().' per post.';
				return false;
			}
						
			/*******************************************************************/
			
			
			/* Input processing *************************************************/
			// This block modify input data	
			
			//create input processor object
			$inputProcessor =& new St_InputProcessor();
			$inputProcessor->setData($this->newMessage);
			
			// 1 ----------------------------------------------
			//filter user input, for safe html
			//no input processing function should be called before this function!
			$inputProcessor->filterForInput();
			
			// 2 ----------------------------------------------
			//translate smiley code into image, if this option is enabled
			if($this->configManager->isSmileyEnabled()){
				$inputProcessor->translateSmiley($this->configManager->getSmileyPattern());
			}
			
			// 3 ----------------------------------------------
			//translate bold,italic,underline ([b][i][u]) tag, if this option is enabled
			if($this->configManager->isFormattingEnabled()){
				$inputProcessor->translateSimpleTag();
			}
			
			// 4 ----------------------------------------------
			//translate URL found in message into link, if this option is enabled
			if($this->configManager->isParseMessageUrlEnabled()){
				$inputProcessor->parseMessageUrl($this->configManager->getUrlText());
			}
			
			// 5 ----------------------------------------------
			//filter bad words if this option is enabled
			if($this->configManager->isBadwordFilterEnabled()){
				$inputProcessor->translateBadword($this->configManager->getBadwordPattern());
			}
			
			// 6 ----------------------------------------------
			//parse URL/Email for nickname link
			$inputProcessor->parseNicknameLink();
			
			/*******************************************************************/
			
			
			$this->newMessage = $inputProcessor->getResult();
			$this->saveMessage();	
				
			//save the current post message for future needs (ex.double post checking)
			$_SESSION['prev_message'] = $prevMessage;
			
			//if moderation is enabled, show them confirmation message
			if($this->configManager->isModerationEnabled()){
				$this->errorMessage = 'Message submitted. Your message is awaiting moderation.';
				return false;
			}
			
			return true;
		}
		
		/**
		* Save Message into persistent object
		* Persistance manager can be set to save message in XML or MySQL
		*
		* @access private
		*/
		function saveMessage(){
			$storage_type = $this->configManager->getStorageType();
			
			if(strtolower($storage_type) == 'file'){
				
				if($this->configManager->isModerationEnabled()){
					$fileName = $this->configManager->getDataDir().'/'.$this->configManager->getModerationMessageFileName();
				}else{
					$fileName = $this->configManager->getDataDir().'/'.$this->configManager->getMessageFileName();

				}
								
				$this->persistenceManager->setStorageType('file');
				$this->persistenceManager->setMessageFile($fileName);
				$this->persistenceManager->setMaxMessageRotation($this->configManager->getMaxMessageRotation());
				
			}elseif(strtolower($storage_type) == 'mysql'){
				die("MySQL storage type, not implemented yet!");
			}else{
				die("Unknown storage type!");
			}
			
			$this->persistenceManager->save($this->newMessage);
		}
	
	}

?>