<?php
	
	/**
	* Class for parsing Smiletag specific XML file
	*
	* This class is intended to serialize and deserialize array from/to XML file.
	*
	* @package Smiletag
	* @author Yuniar Setiawan <yuniarsetiawan@smiletag.com>
	* @since 2.3
	*/
	
	class St_XmlParser{
		
		/**
		* Parse XML from the specified configuration filename into array
		* The config file (path-config.xml,smiletag-config.xml) has simple xml structure
		* Apply locking to synchronize operation among users 
		*
		* @access public
		* @return array if the file has contents, null if empty
		*/
		function parseMainConfigToArray($fileName) {
			$file = @fopen($fileName,'r') or die("Could not open file $fileName or permission denied");
			
			flock($file,LOCK_SH);
			while(!feof($file)){
			   $buffer[] = fgets($file,4096);
			}
			flock($file,LOCK_UN);
			fclose($file);
			
			$textData = implode($buffer);
			if(!empty($textData)){
				
				$xmlDoc =& new DOMIT_Lite_Document();
				$xmlDoc->parseXML($textData,false);
				$rootElement =& $xmlDoc->documentElement;
										
				if($rootElement->hasChildNodes()){
					$childNodes =& $rootElement->childNodes;
					$childCount =& $rootElement->childCount;
					
					for($i=0;$i < $childCount;$i++){
						$childArray[trim($childNodes[$i]->nodeName)] = trim($childNodes[$i]->childNodes[0]->nodeValue);
					}
					
					
				}
				
				return $childArray;
			}else{
				return null;
			}
				
		}
		
		/**
		* Updates global configuration
		*
		* @param string $fileName The configuration filename
		* @param array $configuration The configuration which will be updated
		* @access public
		* @return boolean true if no error
		*/
		function updateConfiguration($fileName,$configuration){
			$originalConfig = $this->parseMainConfigToArray($fileName);
			
			
			$textData = '<?xml version="1.0"?>'."\n".'<smiletag_config>'."\n".'</smiletag_config>';
						
			$xmlDoc =& new DOMIT_Lite_Document();
			$xmlDoc->parseXML($textData,false);
												
			$rootElement =& $xmlDoc->documentElement;

			foreach ($originalConfig as $key=>$value){
				if(isset($configuration[$key])){
					$value = $configuration[$key];
				}
				$configElement =& $xmlDoc->createElement($key);
				$configElement->appendChild($xmlDoc->createCDATASection($value));
				$rootElement->appendChild($configElement);
			}		
									
			$buffer = '<?xml version="1.0"?>'."\n".$xmlDoc->toNormalizedString(false);
			
			//save backs to file
			$file = @fopen($fileName,'w') or die("Could not open file $fileName or permission denied");       
			flock($file,LOCK_EX);
			fwrite($file,$buffer);
			flock($file,LOCK_UN);
	        fclose($file);
			
	        return true;
		}
		
		/**
		* Parse XML from the message.xml into array
		* Apply locking to synchronize operation among users 
		*
		* @access public
		* @return array if the file has contents, null if empty
		*/
		function parseMessagesToArray($fileName) {
			$file = @fopen($fileName,'r') or die("Could not open file $fileName or permission denied");
			
			flock($file,LOCK_SH);
			while(!feof($file)){
			   $buffer[] = fgets($file,4096);
			}
			flock($file,LOCK_UN);
			fclose($file);
			//load data from file
			$textData = implode($buffer);
			
			if(!empty($textData)){
				
				$xmlDoc =& new DOMIT_Lite_Document();
				$xmlDoc->parseXML($textData,false);
				$rootElement =& $xmlDoc->documentElement;
				
				//traverse to nodes and save the values into childArray
				if($rootElement->hasChildNodes()){
					$rowNodes =& $rootElement->childNodes;
					$childCount =& $rootElement->childCount;
					
					for($i=0;$i < $childCount;$i++){
									
						$currentNode      =& $rowNodes[$i];
						$currentNodeCount =& $currentNode->childCount;
									
						for($j=0;$j< $currentNodeCount;$j++){
							$childArray[$i][trim($currentNode->childNodes[$j]->nodeName)] = trim($currentNode->childNodes[$j]->childNodes[0]->nodeValue);	
						}
						
						
					}
				}
				return $childArray;
			}else{
				return null;
			}
				
		}  
		
		/**
		* Parse XML from the smiley-config.xml into array
		* Apply locking to synchronize operation among users 
		* Currently this function has the same functional as parseMessagesToArray
		*
		* @access public
		* @return array if the file has contents, null if empty
		*/
		function parseSmiliesToArray($fileName) {
			return $this->parseMessagesToArray($fileName);				
		}  		
		/**
		* Parse the badword list specified in the $fileName into array
		*
		* @access public
		* @param string $fileName badword configuration file
		* @return array containing pattern and its replacement
		*/
		function parseBadwordToArray($fileName){
			
			$file = @fopen($fileName,'r') or die("Could not open file $fileName or permission denied");
			
			flock($file,LOCK_SH);
			while(!feof($file)){
			   $buffer[] = fgets($file,4096);
			}
			flock($file,LOCK_UN);
			fclose($file);
			//load data from file
			$textData = implode($buffer);
			
			if(!empty($textData)){
				
				$xmlDoc =& new DOMIT_Lite_Document();
				$xmlDoc->parseXML($textData,false);
				
				//gets the replacement words
				$replacement   = $xmlDoc->getElementsByTagName('replacement');
				$replacement   = $replacement->item(0);
				$replacement   = $replacement->getText(); //currently replacement is not an array
				
				//gets the bad words
				$badwordList   = $xmlDoc->getElementsByPath('/badword_config/badwords/word');
				$max 		   = $badwordList->getLength();			
				
					
				if($max != 0){
					for($i=0;$i<$max;$i++){
						$currentNode =& $badwordList->item($i);
						$badwords[]  = trim($currentNode->getText());
					}
				}else{
					$badwords = null;
				}
					
				$badwordArray['replacement'] = $replacement;
				$badwordArray['badwords']	 = $badwords;
				
				return $badwordArray;			
				
			}else{
				return null;
			}
		}
		
		
		/**
		* Append input message to the specified XML file
		* 
		* @param string $fileName Message file name
		* @param integer $maxMessageRotation The maximum allowed number of messages stored in file, if set to 0 then unlimited
		* @param array $newMessage The new messages input
		* @return boolean true on succeeded
		*/
		function appendMessage($fileName,$maxMessageRotation,$newMessage){
			
			$file = @fopen($fileName,'r') or die("Could not open file $fileName or permission denied");
			
			flock($file,LOCK_SH);
			while(!feof($file)){
			   $buffer[] = fgets($file,4096); //load data from file
			}
			flock($file,LOCK_UN);
			fclose($file);
			
			$textData = trim(implode($buffer));
			
			if(empty($textData)){
				$textData = '<?xml version="1.0"?>'."\n".'<smiletag_message>'."\n".'</smiletag_message>';
			};
			
			$xmlDoc =& new DOMIT_Lite_Document();
			$xmlDoc->parseXML($textData,false);
												
			$rootElement =& $xmlDoc->documentElement;
			
			//apply message rotation if applied and maximum number reached
			//delete the unwanted childs
			if($maxMessageRotation != 0){
				if(($rootElement->childCount) >= $maxMessageRotation){
					while (($rootElement->childCount) >= $maxMessageRotation) {
						$rootElement->removeChild($rootElement->lastChild);
					}
				}
			}
			
			//create new element, and insert it before the first child				
			$rowElement =& $xmlDoc->createElement('row');
			$nameElement =& $xmlDoc->createElement('name');
			$urlElement =& $xmlDoc->createElement('url');
			$messageElement =& $xmlDoc->createElement('message');
			$datetimeElement =& $xmlDoc->createElement('datetime');
			$ipaddressElement =& $xmlDoc->createElement('ipaddress');
			
			//domit hacks
			//replace all '&amp;' into '&' to support unicode encoding (multilanguage character support)
			$nameElement->appendChild($xmlDoc->createCDATASection(str_replace('&amp;','&',$newMessage['name'])));	
			$urlElement->appendChild($xmlDoc->createCDATASection($newMessage['url']));
			$messageElement->appendChild($xmlDoc->createCDATASection(str_replace('&amp;','&',$newMessage['message'])));
			$datetimeElement->appendChild($xmlDoc->createTextNode($newMessage['datetime']));
			$ipaddressElement->appendChild($xmlDoc->createTextNode($newMessage['ipaddress']));
			
			$rowElement->appendChild($nameElement);
			$rowElement->appendChild($urlElement);
			$rowElement->appendChild($messageElement);
			$rowElement->appendChild($datetimeElement);
			$rowElement->appendChild($ipaddressElement);
				
			$rootElement->insertBefore($rowElement,$rootElement->firstChild);	
						
			
			$buffer = '<?xml version="1.0"?>'."\n".$xmlDoc->toNormalizedString(false);
			
			//save backs to file
			$file = @fopen($fileName,'w') or die("Could not open file $fileName or permission denied");       
			flock($file,LOCK_EX);
			fwrite($file,$buffer);
			flock($file,LOCK_UN);
	        fclose($file);
			
	        return true;
		}
		
		/**
		* Delete selected message
		* 
		* @param string $fileName Message file name
		* @param array $messageId The message timestamp which will be deleted
		* @return boolean true on succeeded
		*/
		function deleteMessage($fileName,$messageId){
			
			$messageArray = array_reverse($this->parseMessagesToArray($fileName));
			
			$textData = '<?xml version="1.0"?>'."\n".'<smiletag_message>'."\n".'</smiletag_message>';
			
			
			$xmlDoc =& new DOMIT_Lite_Document();
			$xmlDoc->parseXML($textData,false);
												
			$rootElement =& $xmlDoc->documentElement;
			
			foreach ($messageArray as $key=>$value){
				if(!in_array($value['datetime'],$messageId)){		
					
					//create new element, and insert it before the first child				
					$rowElement =& $xmlDoc->createElement('row');
					$nameElement =& $xmlDoc->createElement('name');
					$urlElement =& $xmlDoc->createElement('url');
					$messageElement =& $xmlDoc->createElement('message');
					$datetimeElement =& $xmlDoc->createElement('datetime');
					$ipaddressElement =& $xmlDoc->createElement('ipaddress');
					
					//domit hacks
					//replace all '&amp;' into '&' to support unicode encoding (multilanguage character support)
					$nameElement->appendChild($xmlDoc->createCDATASection(str_replace('&amp;','&',$value['name'])));	
					$urlElement->appendChild($xmlDoc->createCDATASection($value['url']));
					$messageElement->appendChild($xmlDoc->createCDATASection(str_replace('&amp;','&',$value['message'])));
					$datetimeElement->appendChild($xmlDoc->createTextNode($value['datetime']));
					$ipaddressElement->appendChild($xmlDoc->createTextNode($value['ipaddress']));
					
					$rowElement->appendChild($nameElement);
					$rowElement->appendChild($urlElement);
					$rowElement->appendChild($messageElement);
					$rowElement->appendChild($datetimeElement);
					$rowElement->appendChild($ipaddressElement);
						
					$rootElement->insertBefore($rowElement,$rootElement->firstChild);	
				}
			}
					
			$rootElement =& $xmlDoc->documentElement;
			if($rootElement->hasChildNodes()){
				$buffer = '<?xml version="1.0"?>'."\n".$xmlDoc->toNormalizedString(false);
			}else{
				$buffer = '';
			}
			//save backs to file
			$file = @fopen($fileName,'w') or die("Could not open file $fileName or permission denied");       
			flock($file,LOCK_EX);
			fwrite($file,$buffer);
			flock($file,LOCK_UN);
	        fclose($file);
			
	        return true;
		}
		
		/**
		* Update selected message
		* 
		* @param string $fileName Message file name
		* @param array $message The message which will be used to update
		* @return boolean true on succeeded
		*/
		function updateMessage($fileName,$message){
			
			$messageArray = array_reverse($this->parseMessagesToArray($fileName));
			
			$textData = '<?xml version="1.0"?>'."\n".'<smiletag_message>'."\n".'</smiletag_message>';
			
			
			$xmlDoc =& new DOMIT_Lite_Document();
			$xmlDoc->parseXML($textData,false);
												
			$rootElement =& $xmlDoc->documentElement;
			
			foreach ($messageArray as $key=>$value){
					
				//if the message we need to update found
				if($value['datetime'] == $message['datetime']){
					$value['name'] = $message['name'];
					$value['url'] = $message['url'];
					$value['message'] = $message['message'];
					$value['ipaddress'] = $message['ipaddress'];
					
				}
					
				//create new element, and insert it before the first child				
				$rowElement =& $xmlDoc->createElement('row');
				$nameElement =& $xmlDoc->createElement('name');
				$urlElement =& $xmlDoc->createElement('url');
				$messageElement =& $xmlDoc->createElement('message');
				$datetimeElement =& $xmlDoc->createElement('datetime');
				$ipaddressElement =& $xmlDoc->createElement('ipaddress');
					
				//domit hacks
				//replace all '&amp;' into '&' to support unicode encoding (multilanguage character support)
				$nameElement->appendChild($xmlDoc->createCDATASection(str_replace('&amp;','&',$value['name'])));	
				$urlElement->appendChild($xmlDoc->createCDATASection($value['url']));
				$messageElement->appendChild($xmlDoc->createCDATASection(str_replace('&amp;','&',$value['message'])));
				$datetimeElement->appendChild($xmlDoc->createTextNode($value['datetime']));
				$ipaddressElement->appendChild($xmlDoc->createTextNode($value['ipaddress']));
					
				$rowElement->appendChild($nameElement);
				$rowElement->appendChild($urlElement);
				$rowElement->appendChild($messageElement);
				$rowElement->appendChild($datetimeElement);
				$rowElement->appendChild($ipaddressElement);
						
				$rootElement->insertBefore($rowElement,$rootElement->firstChild);	
				
			}
					
			$rootElement =& $xmlDoc->documentElement;
			if($rootElement->hasChildNodes()){
				$buffer = '<?xml version="1.0"?>'."\n".$xmlDoc->toNormalizedString(false);
			}else{
				$buffer = '';
			}
			//save backs to file
			$file = @fopen($fileName,'w') or die("Could not open file $fileName or permission denied");       
			flock($file,LOCK_EX);
			fwrite($file,$buffer);
			flock($file,LOCK_UN);
	        fclose($file);
			
	        return true;
		}
		
		/**
		* Append an ip address entry to the ban-list XML file
		* 
		* @param string $fileName Ban list file name
		* @param string $ipAddress The ip address
		* @return boolean true on succeeded
		*/
		function appendIpAddress($fileName,$ipAddress){
			
			$file = @fopen($fileName,'r') or die("Could not open file $fileName or permission denied");
			
			flock($file,LOCK_SH);
			while(!feof($file)){
			   $buffer[] = fgets($file,4096); //load data from file
			}
			flock($file,LOCK_UN);
			fclose($file);
			
			$textData = trim(implode($buffer));
			
			if(empty($textData)){
				$textData = '<?xml version="1.0"?>'."\n".'<ban_list>'."\n".'</ban_list>';
			};
			
			$xmlDoc =& new DOMIT_Lite_Document();
			$xmlDoc->parseXML($textData,false);
			
			$rootElement =& $xmlDoc->documentElement;
			$bannedIpAddressElement =& $rootElement->firstChild;
								
			//create new element, and insert it before the first child				
			$ipAddressElement =& $xmlDoc->createElement('ipaddress');
			$ipAddressElement->appendChild($xmlDoc->createTextNode($ipAddress));
						
			$bannedIpAddressElement->appendChild($ipAddressElement);
						
			$buffer = '<?xml version="1.0"?>'."\n".$xmlDoc->toNormalizedString(false);
						
			//save backs to file
			$file = @fopen($fileName,'w') or die("Could not open file $fileName or permission denied");       
			flock($file,LOCK_EX);
			fwrite($file,$buffer);
			flock($file,LOCK_UN);
	        fclose($file);
			
	        return true;
		}
		
		/**
		* Append a nickname entry to the ban-list XML file
		* 
		* @param string $fileName Ban list file name
		* @param string $ipAddress The ip address
		* @return boolean true on succeeded
		*/
		function appendNickname($fileName,$nickName){
			
			$file = @fopen($fileName,'r') or die("Could not open file $fileName or permission denied");
			
			flock($file,LOCK_SH);
			while(!feof($file)){
			   $buffer[] = fgets($file,4096); //load data from file
			}
			flock($file,LOCK_UN);
			fclose($file);
			
			$textData = trim(implode($buffer));
			
			if(empty($textData)){
				$textData = '<?xml version="1.0"?>'."\n".'<ban_list>'."\n".'</ban_list>';
			};
			
			$xmlDoc =& new DOMIT_Lite_Document();
			$xmlDoc->parseXML($textData,false);
			
			$rootElement =& $xmlDoc->documentElement;
			$bannedNicknameElement =& $rootElement->lastChild;
								
			//create new element, and insert it before the first child				
			$nicknameElement =& $xmlDoc->createElement('name');
			$nicknameElement->appendChild($xmlDoc->createTextNode($nickName));
						
			$bannedNicknameElement->appendChild($nicknameElement);
						
			$buffer = '<?xml version="1.0"?>'."\n".$xmlDoc->toNormalizedString(false);
						
			//save backs to file
			$file = @fopen($fileName,'w') or die("Could not open file $fileName or permission denied");       
			flock($file,LOCK_EX);
			fwrite($file,$buffer);
			flock($file,LOCK_UN);
	        fclose($file);
			
	        return true;
		}
		
		/**
		* Delete ip address(es) entry in ban-list.xml file
		* 
		* @param string $fileName Ban list file name
		* @param array $deletedIpAddress The ip address which will be deleted
		* @return boolean true on succeeded
		*/
		function deleteIpAddress($fileName,$deletedIpAddress){
			
			$file = @fopen($fileName,'r') or die("Could not open file $fileName or permission denied");
			
			flock($file,LOCK_SH);
			while(!feof($file)){
			   $buffer[] = fgets($file,4096); //load data from file
			}
			flock($file,LOCK_UN);
			fclose($file);
			
			$textData = trim(implode($buffer));
						
			$xmlDoc =& new DOMIT_Lite_Document();
			$xmlDoc->parseXML($textData,false);
			
			//get all ip address list
			$ipAddressList =& $xmlDoc->getElementsByTagName('ipaddress');
			$max 		   = $ipAddressList->getLength();
				
			for($i=0;$i<$max;$i++){
				$currentNode 	=& $ipAddressList->item($i);
				$ipAddressArray[] = trim($currentNode->getText());
			}
			//get nickname node
			$rootElement =& $xmlDoc->documentElement;
			$nickNameElement =& $rootElement->lastChild;
			
			//rebuild, excluding the deleted ip address
			$textData = '<?xml version="1.0"?>'."\n".'<ban_list>'."\n".'</ban_list>';
			$xmlDoc2 =& new DOMIT_Lite_Document();
			$xmlDoc2->parseXML($textData,false);
			
			$rootElement2 =& $xmlDoc2->documentElement;
			$bannedIpAddressElement =& $xmlDoc2->createElement('banned_ipaddress');
			
			foreach ($ipAddressArray as $value){
				if(!in_array($value,$deletedIpAddress)){
					//create new ip address element and append it
					$ipAddressElement 		=& $xmlDoc2->createElement('ipaddress');
					$ipAddressElement->appendChild($xmlDoc2->createTextNode($value));
					$bannedIpAddressElement->appendChild($ipAddressElement);
				}
			}
								
			$rootElement2->appendChild($bannedIpAddressElement);
			$rootElement2->appendChild($nickNameElement);
				
			$buffer = '<?xml version="1.0"?>'."\n".$xmlDoc2->toNormalizedString(false);
			
			//save backs to file
			$file = @fopen($fileName,'w') or die("Could not open file $fileName or permission denied");       
			flock($file,LOCK_EX);
			fwrite($file,$buffer);
			flock($file,LOCK_UN);
	        fclose($file);
			
	        return true;
		}
		
		/**
		* Delete nickname(s) entry in ban-list.xml file
		* 
		* @param string $fileName Ban list file name
		* @param array $deletedNickName The nickname which will be deleted
		* @return boolean true on succeeded
		*/
		function deleteNickName($fileName,$deletedNickName){
			
			$file = @fopen($fileName,'r') or die("Could not open file $fileName or permission denied");
			
			flock($file,LOCK_SH);
			while(!feof($file)){
			   $buffer[] = fgets($file,4096); //load data from file
			}
			flock($file,LOCK_UN);
			fclose($file);
			
			$textData = trim(implode($buffer));
						
			$xmlDoc =& new DOMIT_Lite_Document();
			$xmlDoc->parseXML($textData,false);
			
			//get all nickname list
			$nickNameList =& $xmlDoc->getElementsByTagName('name');
			$max 		   = $nickNameList->getLength();
				
			for($i=0;$i<$max;$i++){
				$currentNode 	=& $nickNameList->item($i);
				$nickNameArray[] = trim($currentNode->getText());
			}
			//get ipaddress node
			$rootElement =& $xmlDoc->documentElement;
			$ipAddressElement =& $rootElement->firstChild;
			
			//rebuild, excluding the deleted ip address
			$textData = '<?xml version="1.0"?>'."\n".'<ban_list>'."\n".'</ban_list>';
			$xmlDoc2 =& new DOMIT_Lite_Document();
			$xmlDoc2->parseXML($textData,false);
			
			$rootElement2 =& $xmlDoc2->documentElement;
			$bannedNickNameElement =& $xmlDoc2->createElement('banned_nickname');
			
			foreach ($nickNameArray as $value){
				if(!in_array($value,$deletedNickName)){
					//create new nickname element and append it
					$nickNameElement 		=& $xmlDoc2->createElement('name');
					$nickNameElement->appendChild($xmlDoc2->createTextNode($value));
					$bannedNickNameElement->appendChild($nickNameElement);
				}
			}
						
			$rootElement2->appendChild($ipAddressElement);
			$rootElement2->appendChild($bannedNickNameElement);
						
			$buffer = '<?xml version="1.0"?>'."\n".$xmlDoc2->toNormalizedString(false);
			
			//save backs to file
			$file = @fopen($fileName,'w') or die("Could not open file $fileName or permission denied");       
			flock($file,LOCK_EX);
			fwrite($file,$buffer);
			flock($file,LOCK_UN);
	        fclose($file);
			
	        return true;
		}
		
		/**
		* Gets the timestamp of the latest message
		*
		* @access public
		* @return string
		*/
		function getFirstChildTimestamp($fileName){
			$file = @fopen($fileName,'r') or die("Could not open file $fileName or permission denied");
			
			flock($file,LOCK_SH);
			while(!feof($file)){
			   $buffer[] = fgets($file,4096);
			}
			flock($file,LOCK_UN);
			fclose($file);
			//load data from file
			$textData = implode($buffer);
			
			if(!empty($textData)){
				
				$xmlDoc =& new DOMIT_Lite_Document();
				$xmlDoc->parseXML($textData,false);

				$firstChild =& $xmlDoc->getElementsByPath('/smiletag_message/row/datetime',1);			
						
				return $firstChild->childNodes[0]->nodeValue;
			}else{
				return '0';
			}
		}
		
		/**
		* Parse configuration from ban-list.xml into array
		*
		* @access public
		* @return array if the file has contents, null if empty
		*/
		function parseBanListToArray($fileName){
			$file = @fopen($fileName,'r') or die("Could not open file $fileName or permission denied");
			
			flock($file,LOCK_SH);
			while(!feof($file)){
			   $buffer[] = fgets($file,4096);
			}
			flock($file,LOCK_UN);
			fclose($file);
			//load data from file
			$textData = implode($buffer);
			
			if(!empty($textData)){
				
				$xmlDoc =& new DOMIT_Lite_Document();
				$xmlDoc->parseXML($textData,false);
								
				//gets ipaddress list
				$ipAddressList =& $xmlDoc->getElementsByTagName('ipaddress');
				$max 		   = $ipAddressList->getLength();
				
				if($max == 0){
					return null;
				}
				
				for($i=0;$i<$max;$i++){
					$currentNode =& $ipAddressList->item($i);
					if(trim($currentNode->getText() != '0.0.0.0')){ //this node cant be empty, so force this value to exist
						$banList['ipaddress'][] = trim($currentNode->getText());
					}
				}
				
				//gets nickname list
				$nicknameList  =& $xmlDoc->getElementsByTagName('name');
				$max 		   = $nicknameList->getLength();
				
				for($i=0;$i<$max;$i++){
					$currentNode =& $nicknameList->item($i);
					if(trim($currentNode->getText() != 'smiletag_default')){ //this node cant be empty, so force this value to exist
						$banList['name'][] = strtolower(trim($currentNode->getText()));
					}
				}
				if(!empty($banList)){
					return $banList;
				}else{
					return null;
				}
							
			}else{
				return null;
			}
		}
		
		/**
		* Add smilies configuration code 
		*
		* @param string $fileName The smilie configuration file name
		* @param string $smilieCode The smilie code
		* @param string $smilieImage The smilie image replacement
		* @access public
		* @return boolean true on success
		*/
		function addSmileyCode($fileName,$smilieCode,$smilieImage){
			
			$file = @fopen($fileName,'r') or die("Could not open file $fileName or permission denied");
			
			flock($file,LOCK_SH);
			while(!feof($file)){
			   $buffer[] = fgets($file,4096); //load data from file
			}
			flock($file,LOCK_UN);
			fclose($file);
			
			$textData = trim(implode($buffer));
			
			if(empty($textData)){
				$textData = '<?xml version="1.0"?>'."\n".'<smiley_config>'."\n".'</smiley_config>';
			};
			
			$xmlDoc =& new DOMIT_Lite_Document();
			$xmlDoc->parseXML($textData,false);
												
			$rootElement =& $xmlDoc->documentElement;
			
					
			//create new element, and insert it before the first child				
			$smileyElement =& $xmlDoc->createElement('smiley');
			$patternElement =& $xmlDoc->createElement('pattern');
			$imageElement =& $xmlDoc->createElement('image');
			
			
			//domit hacks
			//replace all '&amp;' into '&' to support unicode encoding (multilanguage character support)
			$patternElement->appendChild($xmlDoc->createCDATASection(str_replace('&amp;','&',$smilieCode)));	
			$imageElement->appendChild($xmlDoc->createCDATASection($smilieImage));
			
			$smileyElement->appendChild($patternElement);
			$smileyElement->appendChild($imageElement);
				
			$rootElement->insertBefore($smileyElement,$rootElement->firstChild);	
						
			
			$buffer = '<?xml version="1.0"?>'."\n".$xmlDoc->toNormalizedString(false);
						
			//save backs to file
			$file = @fopen($fileName,'w') or die("Could not open file $fileName or permission denied");       
			flock($file,LOCK_EX);
			fwrite($file,$buffer);
			flock($file,LOCK_UN);
	        fclose($file);
			
	        return true;
		}
		
		/**
		* Delete selected smilies configuration code 
		*
		* @param string $fileName The smilie configuration file name
		* @param array $smilieCodes The smilie code which will be deleted
		* @access public
		* @return boolean true on success
		*/
		function deleteSmileyCode($fileName,$smilieCodes){
			
			$smiliesArray = $this->parseSmiliesToArray($fileName);
			
			$textData = '<?xml version="1.0"?>'."\n".'<smiley_config>'."\n".'</smiley_config>';
						
			$xmlDoc =& new DOMIT_Lite_Document();
			$xmlDoc->parseXML($textData,false);
												
			$rootElement =& $xmlDoc->documentElement;
			
			
			foreach ($smiliesArray as $key=>$value){		
				if(!in_array($value['pattern'],$smilieCodes)){
					//create new element, and insert it before the first child				
					$smileyElement =& $xmlDoc->createElement('smiley');
					$patternElement =& $xmlDoc->createElement('pattern');
					$imageElement =& $xmlDoc->createElement('image');
								
					//domit hacks
					//replace all '&amp;' into '&' to support unicode encoding (multilanguage character support)
					$patternElement->appendChild($xmlDoc->createCDATASection(str_replace('&amp;','&',$value['pattern'])));	
					$imageElement->appendChild($xmlDoc->createCDATASection($value['image']));
					
					$smileyElement->appendChild($patternElement);
					$smileyElement->appendChild($imageElement);
						
					$rootElement->insertBefore($smileyElement,$rootElement->firstChild);	
				}			
			}
			
			if($rootElement->hasChildNodes()){
				$buffer = '<?xml version="1.0"?>'."\n".$xmlDoc->toNormalizedString(false);
			}else{
				$buffer = '';
			}
			//save backs to file
			$file = @fopen($fileName,'w') or die("Could not open file $fileName or permission denied");       
			flock($file,LOCK_EX);
			fwrite($file,$buffer);
			flock($file,LOCK_UN);
	        fclose($file);
			
	        return true;
		}
		
		
		/**
		* Add badword to the list
		*
		* @param string $fileName The badword configuration file name
		* @param string $badword The badword to be added
		* @access public
		* @return boolean true on success
		*/
		function addBadword($fileName,$badword){
			
			$file = @fopen($fileName,'r') or die("Could not open file $fileName or permission denied");
			
			flock($file,LOCK_SH);
			while(!feof($file)){
			   $buffer[] = fgets($file,4096); //load data from file
			}
			flock($file,LOCK_UN);
			fclose($file);
			
			$textData = trim(implode($buffer));
			
			if(empty($textData)){
				$textData = '<?xml version="1.0"?>'."\n".'<badword_config><replacement>*beep*</replacement>'."\n".'</badword_config>';
			};
			
			$xmlDoc =& new DOMIT_Lite_Document();
			$xmlDoc->parseXML($textData,false);
												
			$rootElement =& $xmlDoc->documentElement;
			$badwordsElement =& $rootElement->lastChild;							
			
			$wordElement =& $xmlDoc->createElement('word');
						
			//domit hacks
			//replace all '&amp;' into '&' to support unicode encoding (multilanguage character support)
			$wordElement->appendChild($xmlDoc->createCDATASection(str_replace('&amp;','&',$badword)));	
			$badwordsElement->appendChild($wordElement);	
				
					
			
			$buffer = '<?xml version="1.0"?>'."\n".$xmlDoc->toNormalizedString(false);
						
			//save backs to file
			$file = @fopen($fileName,'w') or die("Could not open file $fileName or permission denied");       
			flock($file,LOCK_EX);
			fwrite($file,$buffer);
			flock($file,LOCK_UN);
	        fclose($file);
			
	        return true;
		}
		
		/**
		* Delete badwords from the badword file
		*
		* @param string $fileName The badword configuration file name
		* @param string $badword The badword to be added
		* @access public
		* @return boolean true on success
		*/
		function deleteBadword($fileName,$badwords){
			
			$badwordsArray = $this->parseBadwordToArray($fileName);
			
			$file = @fopen($fileName,'r') or die("Could not open file $fileName or permission denied");
			
			flock($file,LOCK_SH);
			while(!feof($file)){
			   $buffer[] = fgets($file,4096); //load data from file
			}
			flock($file,LOCK_UN);
			fclose($file);
			
			$textData = trim(implode($buffer));
						
			$xmlDoc =& new DOMIT_Lite_Document();
			$xmlDoc->parseXML($textData,false);
												
			$rootElement =& $xmlDoc->documentElement;
			$replacementElement =& $rootElement->firstChild;

			
			//rebuild		
			$textData = '<?xml version="1.0"?>'."\n".'<badword_config>'."\n".'</badword_config>';
						
			$xmlDoc2 =& new DOMIT_Lite_Document();
			$xmlDoc2->parseXML($textData,false);
			
			$rootElement2 =& $xmlDoc2->documentElement;
			
			$badwordsElement =& $xmlDoc2->createElement('badwords');
			
			foreach ($badwordsArray['badwords'] as $value){
				if(!in_array($value,$badwords)){
					$wordElement 	 =& $xmlDoc2->createElement('word');
					//domit hacks
					//replace all '&amp;' into '&' to support unicode encoding (multilanguage character support)
					$wordElement->appendChild($xmlDoc2->createCDATASection(str_replace('&amp;','&',$value)));	
					$badwordsElement->appendChild($wordElement);
				}	
			}	
					
			$rootElement2->appendChild($replacementElement);
			$rootElement2->appendChild($badwordsElement);
			
			$buffer = '<?xml version="1.0"?>'."\n".$xmlDoc2->toNormalizedString(false);
						
			//save backs to file
			$file = @fopen($fileName,'w') or die("Could not open file $fileName or permission denied");       
			flock($file,LOCK_EX);
			fwrite($file,$buffer);
			flock($file,LOCK_UN);
	        fclose($file);
			
	        return true;
		}
	}
	
?>