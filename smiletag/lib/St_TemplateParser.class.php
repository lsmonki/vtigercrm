<?php
	/**
	* Handle parsing template file
	*
	* Parse template.html file and return the result
	*
	* @package Smiletag
	* @author Yuniar Setiawan <yuniarsetiawan@smiletag.com>
	* @since 2.0
	*/

	class St_TemplateParser{
		/**
		* @access private
		* The array containing message to be rendered in the template file
		*/
		var $messageArray;
		var $timeZone;
		var $dateFormat;
		var $timeFormat;
		var $customTextArray;
		
		/**
		* Sets the message array
		* 
		* @param array $input Array which will be rendered to template file
		* @access public
		*/
		function setMessage($input){
			$this->messageArray = $input;
		}
		
		function setDateFormat($dateFormat){
			$this->dateFormat = $dateFormat;
		}
		
		function setTimeFormat($timeFormat){
			$this->timeFormat = $timeFormat;
		}
		
		function setCustomTextPair($pairs){
			$this->customTextArray = $pairs;
		}
		
		/**
		* Sets the time zone, validate the value
		*
		* @access public
		* @param string $timezone example: 'GMT+7' 
		*/
		function setTimeZone($timezone){
			
			$timeNumber =  substr($timezone,3);
			
			if(($timeNumber[0] == '+') or ($timeNumber[0] == '-')){
				$this->timeZone = $timeNumber;	
			}else{
				die("Invalid timezone setting! please make sure you have the correct setting in smiletag-config.xml, example: 'GMT+7'");
			}
					
		}
		
		/**
		* Parse the template file, assign the messageArray and return the result
		* 
		* @access public
		* @return string rendered template
		*/
		function parse(){
			//open template file, store the content into $template
			$file = @fopen('template.html','r') or die("Could not open template file!");
			
			flock($file,LOCK_SH);
			while(!feof($file)){
			   $buffer[] = fgets($file,4096);
			}
			flock($file,LOCK_UN);
			fclose($file);
			
			$template = implode($buffer);
			
			//parse cell portion for the rows template
			if (preg_match('/<!--##START ROWS##-->(.*)<!--##END ROWS##-->/s', $template, $regs)) {
				$cell_template = $regs[1];
			} else {
				die("Template parsing error!");
			}
			
						
			if(!empty($this->messageArray)){
				$cell_result = "";		
				$timeSign = $this->timeZone[0];
				$timeDiff =  (int) substr($this->timeZone,1);
				
						
				//replace the patterns below, found in the cell row template
				$patterns[0] = '<$Name$>';
				$patterns[1] = '<$Message$>';
				$patterns[2] = '<$Date$>';
				$patterns[3] = '<$Time$>';
			    $patterns[4] = '<$NameNoLink$>';
				$patterns[5] = '<$CustomText$>';
				$patterns[6] = '<$IpAddress$>';
				
				$i = 2;
				foreach($this->messageArray as $key=>$value){
				    				    
					$replacements[0] = $value['url']; //contains nickname with hyperlink
					$replacements[1] = $value['message'];
					
					eval("\$datetime = ".$value['datetime']." ".$timeSign." (".$timeDiff."*3600);");
					
					$replacements[2] = gmdate($this->dateFormat,$datetime);
					$replacements[3] = gmdate($this->timeFormat,$datetime);
					
					$replacements[4] = $value['name']; //contains only nickname

					if(is_array($this->customTextArray)){ //rotate the color for each loop
						$replacements[5] = $this->customTextArray[($i%2)];
					}

					$childs = explode('.',$value['ipaddress']);
					$value['ipaddress'] = $childs[0].'.'.$childs[1].'.'.$childs[2].'.'.$childs[3];

					$replacements[6] = $value['ipaddress']; //contains ipaddress
									
					$cell_result .= str_replace($patterns, $replacements, $cell_template);
					$i++;
				}
			}else{
				$cell_result = "<center>You have no messages yet.</center>";
			}
			
						
			$patterns = '/<!--##START ROWS##-->(.*)<!--##END ROWS##-->/s';
			$replacements = $cell_result;
			
			//parse the whole template
			$output = preg_replace($patterns, $replacements, $template);
			
			
			return $output;
		}
	}
	
?>