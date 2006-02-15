<?php
	/**
	* Abstraction layer to hide Flat file / MySQL implementation
	*
	* This class is intended to choose the right implementation based on the configuration
	* retrieved from ConfigManager class
	*
	* @package Smiletag
	* @author Yuniar Setiawan <yuniarsetiawan@smiletag.com>
	* @since 2.3
	*/
	class St_PersistenceManager{
		
		/**
		* @access private
		* The message filename for storing data
		*/
		var $messageFile;
		
		/**
		* @access private
		* The ban list filename for storing banned ip address/nickname
		*/
		var $banFile;
		
		/**
		* @access private
		* The smilie file for storing smilie code and replacement image
		*/
		var $smileyFile;
		
		/**
		* @access private
		* The badword file for storing bad words and replacement 
		*/
		var $badwordFile;
		
				
		/**
		* @access private
		* The storage type for saving data,it could be 'mysql' or 'file'
		*/
		var $storageType;
		
		/**
		* @access private
		* The data access object to save/retrieve data, depends on the storage type
		*/
		var $dao;
		
		/**
		* @access private
		* The maximum number of allowed message stored in the file
		*/
		var $maxMessageRotation;
		
		
		/**
		* Sets the message file
		* 
		* @param string $messageFile Message filename
		*/
		function setMessageFile($messageFile){
			$this->messageFile = $messageFile;
		}
		
		/**
		* Sets the ban file
		* 
		* @param string $messageFile Message filename
		*/
		function setBanFile($banFile){
			$this->banFile = $banFile;
		}
		
		/**
		* Sets the badword file
		* 
		* @param string $badwordFile Badword filename
		*/
		function setBadwordFile($badwordFile){
			$this->badwordFile = $badwordFile;
		}
		
		/**
		* Sets the smiley file
		* 
		* @param string $smileyFile Smiley configuration filename
		*/
		function setSmileyFile($smileyFile){
			$this->smileyFile = $smileyFile;
		}
				
		/**
		* Sets the storage type
		*
		* @access public
		* @param string $type the storage type, it could be 'mysql' or 'file'
		*/
		function setStorageType($type){
			$this->storageType = $type;
			
			if(strtolower($type) == 'file'){
				$this->dao =& new St_FileDao();
			}elseif(strtolower($type) == 'mysql'){
				$this->dao =& new St_MysqlDao();
			}
		}
		
		/**
		* Save the message through data access object
		*
		* @access public
		* @return boolean true on success
		*/
		function save($newMessage){
			if(strtolower($this->storageType) == 'file'){
				$this->dao->setMessageFile($this->messageFile);
				$this->dao->setMaxMessageRotation($this->maxMessageRotation);
			}elseif(strtolower($this->storageType) == 'mysql'){
				//set user ,password,host
				//......
			}
			
			$this->dao->insert($newMessage);
		}
		
		/**
		* Delete message(s) through data access object
		*
		* @param array $messageId The timestamp field which used as key
		* @access public
		* @return boolean true on success
		*/
		function deleteMessage($messageId){
			if(strtolower($this->storageType) == 'file'){
				$this->dao->setMessageFile($this->messageFile);
			}elseif(strtolower($this->storageType) == 'mysql'){
				//set user ,password,host
				//......
			}
			
			$this->dao->deleteMessage($messageId);
		}
		
		/**
		* Delete ip address(es) through data access object
		*
		* @param array $ipaddress The ip address array which will be deleted
		* @access public
		* @return boolean true on success
		*/
		function deleteIpAddress($ipaddress){
			if(strtolower($this->storageType) == 'file'){
				$this->dao->setBanFile($this->banFile);	
			}elseif(strtolower($this->storageType) == 'mysql'){
				//set user ,password,host
				//......
			}
			
			$this->dao->deleteIpAddress($ipaddress);
		}
		
		/**
		* Add smilies configuration code through data access object
		*
		* @param string $smilieCode The smilie code
		* @param string $smilieImage The smilie image replacement
		* @access public
		* @return boolean true on success
		*/
		function addSmileyCode($smilieCode,$smilieImage){
			if(strtolower($this->storageType) == 'file'){
				$this->dao->setSmileyFile($this->smileyFile);	
			}elseif(strtolower($this->storageType) == 'mysql'){
				//set user ,password,host
				//......
			}
			
			$this->dao->addSmileyCode($smilieCode,$smilieImage);
		}
		
		/**
		* Add badword
		*
		* @param string $badword The badword to be added
		* @access public
		* @return boolean true on success
		*/
		function addBadword($badword){
			if(strtolower($this->storageType) == 'file'){
				$this->dao->setBadwordFile($this->badwordFile);	
			}elseif(strtolower($this->storageType) == 'mysql'){
				//set user ,password,host
				//......
			}
			
			$this->dao->addBadword($badword);
		}
		
		/**
		* Delete badword
		*
		* @param array $badwords The badwords to be deleted
		* @access public
		* @return boolean true on success
		*/
		function deleteBadword($badwords){
			if(strtolower($this->storageType) == 'file'){
				$this->dao->setBadwordFile($this->badwordFile);	
			}elseif(strtolower($this->storageType) == 'mysql'){
				//set user ,password,host
				//......
			}
			
			$this->dao->deleteBadword($badwords);
		}
		
		/**
		* Delete banned nickname(s) through data access object
		*
		* @param array $nickname The nickname array which will be deleted
		* @access public
		* @return boolean true on success
		*/
		function deleteNickName($nickname){
			if(strtolower($this->storageType) == 'file'){
				$this->dao->setBanFile($this->banFile);	
			}elseif(strtolower($this->storageType) == 'mysql'){
				//set user ,password,host
				//......
			}
			
			$this->dao->deleteNickName($nickname);
		}
		
		/**
		* Delete selected smilie code from smiley config
		*
		* @param array $smileyCode The nickname which will be deleted
		* @access public
		* @return boolean true if no error
		*/
		function deleteSmileyCode($smileyCode){
			if(strtolower($this->storageType) == 'file'){
				$this->dao->setSmileyFile($this->smileyFile);	
			}elseif(strtolower($this->storageType) == 'mysql'){
				//set user ,password,host
				//......
			}
			
			$this->dao->deleteSmileyCode($smileyCode);
		}
		
		
		/**
		* Delete all message(s) through data access object
		*
		* @access public
		* @return boolean true on success
		*/
		function deleteAllMessage(){
			if(strtolower($this->storageType) == 'file'){
				$this->dao->setMessageFile($this->messageFile);
			}elseif(strtolower($this->storageType) == 'mysql'){
				//set user ,password,host
				//......
			}
			
			$this->dao->deleteAllMessage();
		}
		
		/**
		* Update a message as specified
		*
		* @param array $message The new message which will be used to update
		* @access public
		* @return boolean true on success
		*/
		function updateMessage($message){
			if(strtolower($this->storageType) == 'file'){
				$this->dao->setMessageFile($this->messageFile);
			}elseif(strtolower($this->storageType) == 'mysql'){
				//set user ,password,host
				//......
			}
			
			$this->dao->updateMessage($message);
		}
		
		/**
		* Ban an ip address
		*
		* @access public
		* @return boolean true on success
		*/
		function banIpAddress($ipAddress){
			if(strtolower($this->storageType) == 'file'){
				$this->dao->setBanFile($this->banFile);			
			}elseif(strtolower($this->storageType) == 'mysql'){
				//set user ,password,host
				//......
			}
			
			$this->dao->banIpAddress($ipAddress);
		}
		
		/**
		* Ban a nickname
		*
		* @access public
		* @return boolean true on success
		*/
		function banNickName($nickName){
			if(strtolower($this->storageType) == 'file'){
				$this->dao->setBanFile($this->banFile);			
			}elseif(strtolower($this->storageType) == 'mysql'){
				//set user ,password,host
				//......
			}
			
			$this->dao->banNickName($nickName);
		}
		
		/**
		* Gets the messages from data access object as the specified result size
		*
		* @access public
		* @param integer $size The size of messages to be retrieved
		* @return array
		*/
		function getMessageArray($size){
			if(strtolower($this->storageType) == 'file'){
				$this->dao->setMessageFile($this->messageFile);
			
			}elseif(strtolower($this->storageType) == 'mysql'){
				//set user ,password,host
				//......
			}
			
			return $this->dao->getMessage($size);
		}
		
		/**
		* Gets a messages from data access object by id
		*
		* @access public
		* @param integer $id The timestamp id of the message
		* @return array
		*/
		function getMessageById($id){
			if(strtolower($this->storageType) == 'file'){
				$this->dao->setMessageFile($this->messageFile);
			
			}elseif(strtolower($this->storageType) == 'mysql'){
				//set user ,password,host
				//......
			}
			
			return $this->dao->getMessageById($id);
		}
		
		
		/**
		* Gets the timestamp for the latest message
		*
		* @access public
		* @return string
		*/
		function getLatestTimestamp(){
			if(strtolower($this->storageType) == 'file'){
				$this->dao->setMessageFile($this->messageFile);
			
			}elseif(strtolower($this->storageType) == 'mysql'){
				//set user ,password,host
				//......
			}
			return $this->dao->getLatestTimestamp();
		}
		
		/**
		* Sets the maximum message file allowed in a file before gets rotated
		*
		* @access public
		* @param integer $size The size of messages 
		* @return array
		*/
		function setMaxMessageRotation($size){
			$this->maxMessageRotation = $size;
		}
	}
	
?>