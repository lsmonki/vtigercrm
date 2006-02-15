<?php
	
	/**
	* DAO class for accessing flat file
	*
	* Handle all operation needed to read/write to flat file
	*
	* @package Smiletag
	* @author Yuniar Setiawan <yuniarsetiawan@smiletag.com>
	* @since 2.3
	*/
	
	class St_FileDao {
		
		/**
		* @access private
		* The message filename for storing data
		*/
		var $messageFile;
		
		/**
		* @access private
		* The ban filename for storing ban list
		*/
		var $banFile;
		
		/**
		* @access private
		* The badword file for storing bad words and replacement 
		*/
		var $badwordFile;
		
		/**
		* @access private
		* The smilie file for storing smilie code and replacement image
		*/
		var $smileyFile;
		
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
		* Sets the ban file
		* 
		* @param string $messageFile Message filename
		*/
		function setBanFile($banFile){
			$this->banFile = $banFile;
		}
		
				
		/**
		* Insert a new message into file
		*
		* @access public
		* @return boolean true on success
		*/
		function insert($newMessage){
			
			//encode all ']' characters, these are reserved chars for CDATA section
			$newMessage['message'] = str_replace(']','&#93',$newMessage['message']);
			
			$xmlParser =& new St_XmlParser();
			$xmlParser->appendMessage($this->messageFile,$this->maxMessageRotation,$newMessage);
						
			return true;
		}
		
		/**
		* Update a message in file
		*
		* @param array $message The timestamp field which used as key
		* @access public
		* @return boolean true on success
		*/
		function updateMessage($message){
			
			$xmlParser =& new St_XmlParser();
			$xmlParser->updateMessage($this->messageFile,$message);
						
			return true;
		}
		
		/**
		* Delete message(s) in file
		*
		* @param array $messageId The timestamp field which used as key
		* @access public
		* @return boolean true on success
		*/
		function deleteMessage($messageId){
			
			$xmlParser =& new St_XmlParser();
			$xmlParser->deleteMessage($this->messageFile,$messageId);
						
			return true;
		}
		
		/**
		* Delete ip address(es) in file
		*
		* @param array $ipaddress The ip address which will be deleted
		* @access public
		* @return boolean true on success
		*/
		function deleteIpAddress($ipaddress){
			
			$xmlParser =& new St_XmlParser();
			$xmlParser->deleteIpAddress($this->banFile,$ipaddress);
						
			return true;
		}
		
		/**
		* Add smilies configuration code 
		*
		* @param string $smilieCode The smilie code
		* @param string $smilieImage The smilie image replacement
		* @access public
		* @return boolean true on success
		*/
		function addSmileyCode($smilieCode,$smilieImage){
			
			$xmlParser =& new St_XmlParser();
			$xmlParser->addSmileyCode($this->smileyFile,$smilieCode,$smilieImage);
						
			return true;
		}
		
		
		/**
		* Add badword
		*
		* @param string $badword The badword to be added
		* @access public
		* @return boolean true on success
		*/
		function addBadword($badword){
			
			$xmlParser =& new St_XmlParser();
			$xmlParser->addBadword($this->badwordFile,$badword);
						
			return true;
		}
		
		/**
		* Delete badword(s)
		*
		* @param array $badwords The badword to be deleted
		* @access public
		* @return boolean true on success
		*/
		function deleteBadword($badwords){
			
			$xmlParser =& new St_XmlParser();
			$xmlParser->deleteBadword($this->badwordFile,$badwords);
						
			return true;
		}
		
		/**
		* Delete nickname(s) in ban file
		*
		* @param array $nickname The nickname(s) which will be deleted
		* @access public
		* @return boolean true on success
		*/
		function deleteNickName($nickname){
			
			$xmlParser =& new St_XmlParser();
			$xmlParser->deleteNickName($this->banFile,$nickname);
						
			return true;
		}
		
		/**
		* Delete selected smilie code from smiley config
		*
		* @param array $smileyCode The nickname which will be deleted
		* @access public
		* @return boolean true if no error
		*/
		function deleteSmileyCode($smileyCode){
			
			$xmlParser =& new St_XmlParser();
			$xmlParser->deleteSmileyCode($this->smileyFile,$smileyCode);
						
			return true;
		}
		
		/**
		* Delete all message(s) in file
		*
		* @access public
		* @return boolean true on success
		*/
		function deleteAllMessage(){
			
			//empty the file
			$buffer = '';
			$file = @fopen($this->messageFile,'w') or die("Could not open file $this->messageFile or permission denied");       
			flock($file,LOCK_EX);
			fwrite($file,$buffer);
			flock($file,LOCK_UN);
	        fclose($file);
						
			return true;
		}
		
		/**
		* Save an ip address entry into ban file
		*
		* @access public
		* @return boolean true on success
		*/
		function banIpAddress($ipAddress){
			
			$xmlParser =& new St_XmlParser();
			$xmlParser->appendIpAddress($this->banFile,$ipAddress);
						
			return true;
		}
		
		/**
		* Save a nickname entry into ban file
		*
		* @access public
		* @return boolean true on success
		*/
		function banNickName($nickName){
			
			$xmlParser =& new St_XmlParser();
			$xmlParser->appendNickname($this->banFile,$nickName);
						
			return true;
		}
		
		/**
		* Get all messages from file, the messages returned as array in descendant order
		*
		* @access public
		* @param integer $size The size of messages to be retrieved
		* @return array
		*/
		function getMessage($size){
			$xmlParser =& new St_XmlParser();
			$messageArray = $xmlParser->parseMessagesToArray($this->messageFile);
			
			if(is_array($messageArray) && ($size != 0)){
				$messageArray = array_slice($messageArray,0,$size);
			}
						
			return $messageArray;
		}
		
		/**
		* Get a messages from file by timestamp id
		*
		* @access public
		* @param id $size The timestamp id of message to be retrieved
		* @return array
		*/
		function getMessageById($id){
			$xmlParser =& new St_XmlParser();
			$messageArray = $xmlParser->parseMessagesToArray($this->messageFile);
			
			if(is_array($messageArray)){
				foreach ($messageArray as $key=>$value){
					if($value['datetime'] == $id){
						$messageFound = $messageArray[$key];
						break;
					}
				}
			}

			if(isset($messageFound)){
				return $messageFound;
			}else{
				return null;
			}		
			
		}
		
		
		/**
		* Gets the timestamp of the latest message
		*
		* @access public
		* @return string
		*/
		function getLatestTimestamp(){
			$xmlParser =& new St_XmlParser();
						
			return $xmlParser->getFirstChildTimestamp($this->messageFile);
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