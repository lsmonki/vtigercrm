<?php
	
	/**
	* Handle everything that related to admin control panel
	*
	* Loads data from persistence object, delete, edit, use the ConfigManager class
	*
	* @package Smiletag
	* @author Yuniar Setiawan <yuniarsetiawan@smiletag.com>
	* @since 2.3
	*/
	
	class St_AdminManager{
		/**
		* @access private
		* Hold configuration for everything
		*/
		var $configManager;
		/**
		* @access private
		* Handle persistence operation
		*/
		var $persistenceManager;
		
		function St_AdminManager(){
			//initiate configManager
			$this->configManager =& new St_ConfigManager();
		
			//initiate persistenceManager
			$this->persistenceManager =& new St_PersistenceManager();
		}
		
		/**
		* Delete selected messages from persistence
		*
		* @param array $messageId The timestamp of messages which will be deleted
		* @access public
		* @return boolean true if no error
		*/
		function deleteMessages($messageId){
			//load data from persistence object
			$storageType 		=  $this->configManager->getStorageType();
		
								
			if(strtolower($storageType) == 'file'){
				$fileName = $this->configManager->getDataDir().'/'.$this->configManager->getMessageFileName();
				$this->persistenceManager->setStorageType('file');
				$this->persistenceManager->setMessageFile($fileName);
			}elseif(strtolower($storageType) == 'mysql'){
				die("MySQL storage type, not implemented yet!");
			}else{
				die("Unknown storage type!");
			}
			
			$this->persistenceManager->deleteMessage($messageId);
			
		}		
		
		/**
		* Delete all messages from persistence
		*
		* @access public
		* @return boolean true if no error
		*/
		function deleteAllMessages(){
			
			$storageType 		=  $this->configManager->getStorageType();
										
			if(strtolower($storageType) == 'file'){
				$fileName = $this->configManager->getDataDir().'/'.$this->configManager->getMessageFileName();
				$this->persistenceManager->setStorageType('file');
				$this->persistenceManager->setMessageFile($fileName);
			}elseif(strtolower($storageType) == 'mysql'){
				die("MySQL storage type, not implemented yet!");
			}else{
				die("Unknown storage type!");
			}
			
			$this->persistenceManager->deleteAllMessage();
		}

		/**
		* Update message from persistence
		*
		* @param array $message Current message which need to be updated
		* @access public
		* @return boolean true if no error
		*/
		function updateMessage($message,$mode='normal'){
			
			$storageType 		=  $this->configManager->getStorageType();
		
								
			if(strtolower($storageType) == 'file'){
				if($mode == 'moderation'){
					$fileName = $this->configManager->getDataDir().'/'.$this->configManager->getModerationMessageFileName();
				}else{
					$fileName = $this->configManager->getDataDir().'/'.$this->configManager->getMessageFileName();
				}
				
				$this->persistenceManager->setStorageType('file');
				$this->persistenceManager->setMessageFile($fileName);
			}elseif(strtolower($storageType) == 'mysql'){
				die("MySQL storage type, not implemented yet!");
			}else{
				die("Unknown storage type!");
			}
			
			/* Input processing *************************************************/
			// This block modify input data	
			
			//create input processor object
			$inputProcessor =& new St_InputProcessor();
			$inputProcessor->setData($message);
			
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
			
			
			$message = $inputProcessor->getResult();
			
			$this->persistenceManager->updateMessage($message);
			
		}		
		
		/**
		* Moderate selected messages from persistence
		*
		* @param array $messageId Timestamp array which contain moderated message 
		* @access public
		* @return boolean true if no error
		*/
		function moderateMessages($messageId){
			
			$storageType 		=  $this->configManager->getStorageType();
		
			//separates the timestamp id for approved,deleted and pending message
			//into different array
			foreach ($messageId as $key=>$value){
				if($value == 'approve'){
					$approvedMessageId[] = $key;
				}elseif($value == 'delete'){
					$deletedMessageId[] = $key;
				}
			}
			
			//saves the approved message into messages.xml
			///gets data from messages-moderated.xml
			if(strtolower($storageType) == 'file'){
				$fileName = $this->configManager->getDataDir().'/'.$this->configManager->getModerationMessageFileName();
				$this->persistenceManager->setStorageType('file');
				$this->persistenceManager->setMessageFile($fileName);
			}elseif(strtolower($storageType) == 'mysql'){
				die("MySQL storage type, not implemented yet!");
			}else{
				die("Unknown storage type!");
			}
			
			///gets all moderated messages
			$moderatedMessageArray = $this->persistenceManager->getMessageArray(0);
			
			///filter for the approved only,if exists
			if(isset($approvedMessageId)){
				foreach ($moderatedMessageArray as $value){
					if(in_array($value['datetime'],$approvedMessageId)){
						$approvedMessageArray[] = $value;
					}
				}
			}
			//removes approved & deleted messages from messages-moderated.xml
			if(isset($approvedMessageId) && !isset($deletedMessageId)){
				$approvedAndDeletedId = $approvedMessageId;
			}elseif(!isset($approvedMessageId) && isset($deletedMessageId)){
				$approvedAndDeletedId = $deletedMessageId;
			}elseif(isset($approvedMessageId) && isset($deletedMessageId)){
				$approvedAndDeletedId = array_merge($approvedMessageId,$deletedMessageId);	
			}

			if(isset($approvedAndDeletedId)){		
				$this->persistenceManager->deleteMessage($approvedAndDeletedId);	
			}
			
			///saves approved message into messages.xml, if any
			if(isset($approvedMessageArray)){
				if(strtolower($storageType) == 'file'){
					$fileName = $this->configManager->getDataDir().'/'.$this->configManager->getMessageFileName();
					$this->persistenceManager->setStorageType('file');
					$this->persistenceManager->setMessageFile($fileName);
				}elseif(strtolower($storageType) == 'mysql'){
					die("MySQL storage type, not implemented yet!");
				}else{
					die("Unknown storage type!");
				}
				
				$approvedMessageArray = array_reverse($approvedMessageArray);
				
				foreach ($approvedMessageArray as $value){
					$this->persistenceManager->save($value);
				}
			}
		}

		/**
		* Add an ip address into ban-list.xml
		*
		* @param string $ipaddress The ip address which will be added
		* @access public
		* @return boolean true if no error
		*/
		function banIpAddress($ipaddress){
			//load data from persistence object
			$storageType 		=  $this->configManager->getStorageType();
		
								
			if(strtolower($storageType) == 'file'){
				$fileName = $this->configManager->getDataDir().'/'.$this->configManager->getBanFileName();
				$this->persistenceManager->setStorageType('file');
				$this->persistenceManager->setBanFile($fileName);
			}elseif(strtolower($storageType) == 'mysql'){
				die("MySQL storage type, not implemented yet!");
			}else{
				die("Unknown storage type!");
			}
			
			$this->persistenceManager->banIpAddress($ipaddress);
			
		}

		/**
		* Add a nickname into ban-list.xml
		*
		* @param string $ipaddress The ip address which will be added
		* @access public
		* @return boolean true if no error
		*/
		function banNickName($nickName){
			//load data from persistence object
			$storageType 		=  $this->configManager->getStorageType();
		
								
			if(strtolower($storageType) == 'file'){
				$fileName = $this->configManager->getDataDir().'/'.$this->configManager->getBanFileName();
				$this->persistenceManager->setStorageType('file');
				$this->persistenceManager->setBanFile($fileName);
			}elseif(strtolower($storageType) == 'mysql'){
				die("MySQL storage type, not implemented yet!");
			}else{
				die("Unknown storage type!");
			}
			
			$this->persistenceManager->banNickName($nickName);
			
		}				
		
		/**
		* Delete selected ip address from persistence
		*
		* @param array $ipaddress The ipaddress which will be deleted
		* @access public
		* @return boolean true if no error
		*/
		function deleteIpAddress($ipaddress){
			//load data from persistence object
			$storageType 		=  $this->configManager->getStorageType();
		
								
			if(strtolower($storageType) == 'file'){
				$fileName = $this->configManager->getDataDir().'/'.$this->configManager->getBanFileName();
				$this->persistenceManager->setStorageType('file');
				$this->persistenceManager->setBanFile($fileName);
			}elseif(strtolower($storageType) == 'mysql'){
				die("MySQL storage type, not implemented yet!");
			}else{
				die("Unknown storage type!");
			}
			
			$this->persistenceManager->deleteIpAddress($ipaddress);
			
		}
				
		/**
		* Delete selected nickname from ban-list
		*
		* @param array $nickName The nickname which will be deleted
		* @access public
		* @return boolean true if no error
		*/
		function deleteNickName($nickname){
			//load data from persistence object
			$storageType 		=  $this->configManager->getStorageType();
		
								
			if(strtolower($storageType) == 'file'){
				$fileName = $this->configManager->getDataDir().'/'.$this->configManager->getBanFileName();
				$this->persistenceManager->setStorageType('file');
				$this->persistenceManager->setBanFile($fileName);
			}elseif(strtolower($storageType) == 'mysql'){
				die("MySQL storage type, not implemented yet!");
			}else{
				die("Unknown storage type!");
			}
			
			$this->persistenceManager->deleteNickName($nickname);
			
		}
		
		/**
		* Add smilie code configuration
		*
		* @param string $smilieCode The smilie code
		* @param string $smilieImage The smilie image replacement
		* @access public
		* @return boolean true if no error
		*/
		function addSmilieCode($smilieCode,$smilieImage){
			
			//load data from persistence object
			$storageType 		=  $this->configManager->getStorageType();
										
			if(strtolower($storageType) == 'file'){
				$fileName = $this->configManager->getDataDir().'/'.$this->configManager->getSmileyConfigFile();
				$this->persistenceManager->setStorageType('file');
				$this->persistenceManager->setSmileyFile($fileName);
			}elseif(strtolower($storageType) == 'mysql'){
				die("MySQL storage type, not implemented yet!");
			}else{
				die("Unknown storage type!");
			}
			
			$this->persistenceManager->addSmileyCode($smilieCode,$smilieImage);
			
		}
		
		/**
		* Delete selected smilie code from smiley config
		*
		* @param array $smilieCode The smilie code which will be deleted
		* @access public
		* @return boolean true if no error
		*/
		function deleteSmilieCode($smilieCode){
			//load data from persistence object
			$storageType 		=  $this->configManager->getStorageType();
		
								
			if(strtolower($storageType) == 'file'){
				$fileName = $this->configManager->getDataDir().'/'.$this->configManager->getSmileyConfigFile();
				$this->persistenceManager->setStorageType('file');
				$this->persistenceManager->setSmileyFile($fileName);
			}elseif(strtolower($storageType) == 'mysql'){
				die("MySQL storage type, not implemented yet!");
			}else{
				die("Unknown storage type!");
			}
			
			$this->persistenceManager->deleteSmileyCode($smilieCode);
			
		}
		
		/**
		* Add badword
		*
		* @param string $badword The badword to be added
		* @access public
		* @return boolean true if no error
		*/
		function addBadword($badword){
			
			//load data from persistence object
			$storageType 		=  $this->configManager->getStorageType();
										
			if(strtolower($storageType) == 'file'){
				$fileName = $this->configManager->getDataDir().'/'.$this->configManager->getBadwordFile();
				$this->persistenceManager->setStorageType('file');
				$this->persistenceManager->setBadwordFile($fileName);
			}elseif(strtolower($storageType) == 'mysql'){
				die("MySQL storage type, not implemented yet!");
			}else{
				die("Unknown storage type!");
			}
			
			$this->persistenceManager->addBadword($badword);
			
		}
		
		/**
		* Delete badword
		*
		* @param array $badwords The badword  array to delete
		* @access public
		* @return boolean true if no error
		*/
		function deleteBadword($badwords){
			
			//load data from persistence object
			$storageType 		=  $this->configManager->getStorageType();
										
			if(strtolower($storageType) == 'file'){
				$fileName = $this->configManager->getDataDir().'/'.$this->configManager->getBadwordFile();
				$this->persistenceManager->setStorageType('file');
				$this->persistenceManager->setBadwordFile($fileName);
			}elseif(strtolower($storageType) == 'mysql'){
				die("MySQL storage type, not implemented yet!");
			}else{
				die("Unknown storage type!");
			}
			
			$this->persistenceManager->deleteBadword($badwords);
			
		}
		
		/**
		* Updates global configuration
		*
		* @param array $configuration The configuration which will be updated
		* @access public
		* @return boolean true if no error
		*/
		function updateConfiguration($configuration){
		
			$this->configManager->updateConfiguration($configuration);
			return true;							
		}
		
	}
?>