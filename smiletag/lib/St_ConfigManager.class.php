<?php
	
	/**
	* Handle all configuration
	*
	* Handle every configuration for smiletag, it reads the smiletag-config.xml
	*
	* @package Smiletag
	* @author Yuniar Setiawan <yuniarsetiawan@smiletag.com>
	* @since 2.3
	*/
	
	class St_ConfigManager{
		/**
		* @access private
		*/
		var $configParser;
		
		/**
		* @access private
		*/
		var $pathConfig;
		
		/**
		* @access private
		*/
		var $smiletagConfig;
		
		/**
		* @access private
		*/
		var $smileyPattern;
		
		/**
		* @access private
		*/
		var $bannedIpAddress;
		
		/**
		* @access private
		*/
		var $bannedNickname;
		
		
		
		/**
		* This constructor load all configuration from two files below into array
		* path-config.xml.php and smiletag-config.xml.php
		*/
		function St_ConfigManager(){
			//load configuration in path-config.xml.php
			define('CONFIG_DIR', (dirname(__FILE__) . "/../config/"));
			
			$this->configParser =& new St_XmlParser();
			$this->pathConfig = $this->configParser->parseMainConfigToArray(CONFIG_DIR.'path-config.xml');
			
			//load configuration in smiletag-config.xml.php
			$this->smiletagConfig = $this->configParser->parseMainConfigToArray($this->getDataDir().'/smiletag-config.xml');
			
			//load banned ip address and nickname list
			$banList = $this->configParser->parseBanListToArray($this->getDataDir().'/ban-list.xml');
			
			if(!empty($banList['ipaddress'])){
				$this->bannedIpAddress = $banList['ipaddress'];
			}
			if(!empty($banList['name'])){
				$this->bannedNickname = $banList['name'];
			}
		}
		
		/**
		* Gets the data directory where all data stored
		*
		* @access public
		* @return string
		*/
		function getDataDir(){
			$data_dir = trim($this->pathConfig['data_dir']);
			
			//if default value used, find the full path
			if(strtolower($data_dir) == './data'){ 
				$data_dir = dirname(dirname(__FILE__)).'/data';	
			}
			
			return $data_dir;
		}
		
		/**
		* Gets the ban list file
		*
		* @access public
		* @return string
		*/
		function getBanFileName(){
			return trim($this->smiletagConfig['ban_file']);
		}
		
		/**
		* Gets the board status
		*
		* @access public
		* @return boolean true if enabled
		*/
		function isBoardEnabled(){
			$status = trim($this->smiletagConfig['enable_board']);
			if(strtolower($status) == 'true'){
				return true;
			}else{
				return false;
			}
		}
		
		/**
		* Gets the banning status
		*
		* @access public
		* @return boolean true if enabled
		*/
		function isBanEnabled(){
			$status = trim($this->smiletagConfig['enable_ban']);
			if(strtolower($status) == 'true'){
				return true;
			}else{
				return false;
			}
		}
		
		/**
		* Gets the moderation status
		*
		* @access public
		* @return boolean true if enabled
		*/
		function isModerationEnabled(){
			$status = trim($this->smiletagConfig['enable_moderation']);
			if(strtolower($status) == 'true'){
				return true;
			}else{
				return false;
			}
		}
		
	    /**
		* Gets the message filename where the data stored
		*
		* @access public
		* @return string
		*/
		function getMessageFileName(){
			return trim($this->smiletagConfig['message_file']);
		}
		
		/**
		* Gets the message filename where the data stored for moderation mode
		*
		* @access public
		* @return string
		*/
		function getModerationMessageFileName(){
			return trim($this->smiletagConfig['moderation_message_file']);
		}
		
		/**
		* Gets the storage type for smiletag persistence
		*
		* @access public
		* @return string it could be 'file' or 'mysql'
		*/
		function getStorageType(){
			return trim($this->smiletagConfig['storage_type']);
		}
		
		/**
		* Gets the the time zone setting
		*
		* @access public
		* @return string example: 'GMT+7'
		*/
		function getTimeZone(){
			return trim($this->smiletagConfig['time_zone']);
		}
		
		/**
		* Gets the date format
		*
		* @access public
		* @return string 
		*/
		function getDateFormat(){
			return trim($this->smiletagConfig['date_format']);
		}
				
		/**
		* Gets the time format
		*
		* @access public
		* @return string 
		*/
		function getTimeFormat(){
			return trim($this->smiletagConfig['time_format']);
		}
		
		/**
		* Gets the smiley configuration path
		*
		* @access public
		* @return string 
		*/
		function getSmileyConfigFile(){
			return trim($this->smiletagConfig['smiley_config_file']);
		}
				
		/**
		* Gets the badword filter configuration path
		*
		* @access public
		* @return string 
		*/
		function getBadwordFile(){
			return trim($this->smiletagConfig['badword_file']);
		}
		
		/**
		* Gets the smiley translation pattern
		*
		* @access public
		* @return array 
		*/
		function getSmileyPattern(){
			$smileyConfigFile = $this->getDataDir().'/'.$this->getSmileyConfigFile();
			return $this->configParser->parseSmiliesToArray($smileyConfigFile);
		}
		
		/**
		* Gets the bad word filtering pattern
		*
		* @access public
		* @return array 
		*/
		function getBadwordPattern(){
			$badwordFile = $this->getDataDir().'/'.$this->getBadwordFile();
			return $this->configParser->parseBadwordToArray($badwordFile);
		}
		
		/**
		* Gets the smiley status, wheter enabled or disabled
		*
		* @access public
		* @return boolean true or false 
		*/
		function isSmileyEnabled(){
			$status = trim($this->smiletagConfig['enable_smiley']); 
			if(strtolower($status) == 'true'){
				return true;
			}else{
				return false;
			}
		}
		
		/**
		* Gets the maximum number of allowed messages to display in the board
		*
		* @access public
		* @return integer 
		*/
		function getMaxMessageNumber(){
			return trim($this->smiletagConfig['max_message_number']);
		}
		
		/**
		* Gets the allowed maximum character length for a new message (nickname,url,message)
		*
		* @access public
		* @return array contains 'message','name' and 'url' 
		*/
		function getMaxInputLength(){
			
			$maxInputLength['message'] = trim($this->smiletagConfig['max_message_length']); 
			$maxInputLength['name']    = trim($this->smiletagConfig['max_nickname']);
			$maxInputLength['url'] 	   = trim($this->smiletagConfig['max_url']);
			
			return $maxInputLength;
		}
		/**
		* Gets the allowed maximum number of message stored in the file
		* If this limit is reached, the message will be rotated, so the file doesn't
		* getting bigger and bigger
		*
		* @access public
		* @return integer 
		*/
		function getMaxMessageRotation(){
			return trim($this->smiletagConfig['max_message_rotation']);
		}
		
		
		/**
		* Gets the status wether to enable link parsing in each message or not
		*
		* @access public
		* @return boolean true or false
		*/
		function isParseMessageUrlEnabled(){
			$status = trim($this->smiletagConfig['parse_url_inside_message']);
			if(strtolower($status) == 'true'){
				return true;
			}else{
				return false;
			}
		}
		
		
		/**
		* Gets the text replacement for each link found in message
		*
		* @access public
		* @return string 'true' or 'false'
		*/
		function getUrlText(){
			return trim($this->smiletagConfig['url_text']);
		}
		
		/**
		* Gets the status for simple formatting ([b] for bold,[i] for italic, and [u] for underline)
		*
		* @access public
		* @return boolean true or false
		*/
		function isFormattingEnabled(){
			$status = trim($this->smiletagConfig['enable_formatting']);
			if(strtolower($status) == 'true'){
				return true;
			}else{
				return false;
			}
		}
		
		
		/**
		* Gets the status for auto banning feature
		*
		* @access public
		* @return boolean true or false
		*/
		function isAutoBanEnabled(){
			$status = trim($this->smiletagConfig['auto_ban']);
			if(strtolower($status) == 'true'){
				return true;
			}else{
				return false;
			}
		}
		
		/**
		* Gets the status for alternate custom text
		*
		* @access public
		* @return boolean true or false
		*/
		function isCustomTextEnabled(){
			$status = trim($this->smiletagConfig['enable_custom_text']);
			if(strtolower($status) == 'true'){
				return true;
			}else{
				return false;
			}
		}
		
		/**
		* Gets the custom text pair array
		*
		* @access public
		* @return array custom text pairs
		*/
		function getCustomTextPair(){
			$pairs[] = trim($this->smiletagConfig['text_first']); 
			$pairs[] = trim($this->smiletagConfig['text_second']);
			
			return $pairs;
		}
		
		/**
		* Gets the status for badword filtering feature
		*
		* @access public
		* @return boolean true or false
		*/
		function isBadwordFilterEnabled(){
			$status = trim($this->smiletagConfig['enable_badword_filter']);
			if(strtolower($status) == 'true'){
				return true;
			}else{
				return false;
			}
		}
		
		/**
		* Gets the flood interval time
		*
		* @access public
		* @return integer number of seconds
		*/
		function getFloodInterval(){
			return trim($this->smiletagConfig['flood_interval']);
		}
		
		/**
		* Gets the ip address ban list
		*
		* @access public
		* @return array ip address list
		*/
		function getBannedIpAddress(){
			return $this->bannedIpAddress;
		}
		
		
		/**
		* Gets the nickname ban list
		*
		* @access public
		* @return array nick name list
		*/
		function getBannedNickname(){
			return $this->bannedNickname;
		}
		
		/**
		* Gets the  max flood attempt which cause auto ban
		*
		* @access public
		* @return integer number of seconds
		*/
		function getMaxFlood(){
			return trim($this->smiletagConfig['auto_ban_trigger']);
		}
		
		/**
		* Updates global configuration
		*
		* @param array $configuration The configuration which will be updated
		* @access public
		* @return boolean true if no error
		*/
		function updateConfiguration($configuration){
			
			$this->configParser->updateConfiguration($this->getDataDir().'/smiletag-config.xml',$configuration);
			
			return true;							
		}
	}
?>