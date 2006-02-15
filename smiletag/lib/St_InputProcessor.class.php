<?php
	/**
	* Handle all input processing, including filtering, smiley translation
	*
	* @package Smiletag
	* @author Yuniar Setiawan <yuniarsetiawan@smiletag.com>
	* @since 2.1
	*/
	class St_InputProcessor{
		/**
		* @access private
		* The input data which will be processed by all method
		*/
		var $data;
		
		
		function St_InputProcessor(){
			/**
			 * Replace str_ireplace()
			 *
			 * @category    PHP
			 * @package     PHP_Compat
			 * @link        http://php.net/function.str_ireplace
			 * @author      Aidan Lister <aidan@php.net>
			 * @version     $Revision: 1.18 $
			 * @since       PHP 5
			 * @require     PHP 4.0.0 (user_error)
			 * @note        count not by returned by reference, to enable
			 *              change '$count = null' to '&$count'
			 */
			 
			 //Taken from http://pear.php.net/package/PHP_Compat
			
			 if (!function_exists('str_ireplace')) {
			    function str_ireplace($search, $replace, $subject, $count = null)
			    {
			        // Sanity check
			        if (is_string($search) && is_array($replace)) {
			            user_error('Array to string conversion', E_USER_NOTICE);
			            $replace = (string) $replace;
			        }
			
			        // If search isn't an array, make it one
			        if (!is_array($search)) {
			            $search = array ($search);
			        }
			        $search = array_values($search);
			
			        // If replace isn't an array, make it one, and pad it to the length of search
			        if (!is_array($replace)) {
			            $replace_string = $replace;
			
			            $replace = array ();
			            for ($i = 0, $c = count($search); $i < $c; $i++) {
			                $replace[$i] = $replace_string;
			            }
			        }
			        $replace = array_values($replace);
			
			        // Check the replace array is padded to the correct length
			        $length_replace = count($replace);
			        $length_search = count($search);
			        if ($length_replace < $length_search) {
			            for ($i = $length_replace; $i < $length_search; $i++) {
			                $replace[$i] = '';
			            }
			        }
			
			        // If subject is not an array, make it one
			        $was_array = false;
			        if (!is_array($subject)) {
			            $was_array = true;
			            $subject = array ($subject);
			        }
			
			        // Loop through each subject
			        $count = 0;
			        foreach ($subject as $subject_key => $subject_value) {
			            // Loop through each search
			            foreach ($search as $search_key => $search_value) {
			                // Split the array into segments, in between each part is our search
			                $segments = explode(strtolower($search_value), strtolower($subject_value));
			
			                // The number of replacements done is the number of segments minus the first
			                $count += count($segments) - 1;
			                $pos = 0;
			
			                // Loop through each segment
			                foreach ($segments as $segment_key => $segment_value) {
			                    // Replace the lowercase segments with the upper case versions
			                    $segments[$segment_key] = substr($subject_value, $pos, strlen($segment_value));
			                    // Increase the position relative to the initial string
			                    $pos += strlen($segment_value) + strlen($search_value);
			                }
			
			                // Put our original string back together
			                $subject_value = implode($replace[$search_key], $segments);
			            }
			
			            $result[$subject_key] = $subject_value;
			        }
			
			        // Check if subject was initially a string and return it as a string
			        if ($was_array === true) {
			            return $result[0];
			        }
			
			        // Otherwise, just return the array
			        return $result;
			    }
			}
			/****************************************************/
		}
		
		
		
		function setData($data){
			$this->data = $data;
		}

				
		/**
		* Removes newlines and perform htmlspecialchars filter
		*
		* @access public
		* @return void
		*/
		function filterForInput(){
			
			$messageArray = $this->data;
			
			foreach ($messageArray as $key=>$value){
				
				//replace all newline with space
				$messageArray[$key] = str_replace( array("\r\n", "\n\r", "\n", "\r"), " ", $value);
				
				if($key == 'message'){
					//filter all slashes and render html tags safely in message
					$messageArray[$key] = htmlspecialchars(stripslashes($messageArray[$key]));
				}else{
					//strictly remove html tags in nickname and url
					$messageArray[$key] = strip_tags(stripslashes($messageArray[$key]));
				}
			
				
			}
			
			$this->data = $messageArray;
						
		}
		
		
		/**
		* Convert all URL found in message into link
		*
		* @access public
		* @return void
		*/
		function parseMessageUrl($urlText){
			
			$messageArray = $this->data;
			
			//hypertext/ftp/file link
			$messageArray['message'] = preg_replace('/\\b(https?|ftp):\/\/[-A-Z0-9+&@#\/%?=~_|!:,.;]*[-A-Z0-9+&@#\/%=~_|]/i', '<a href="\\0" target="_blank" rel="external nofollow">'.$urlText.'</a>', $messageArray['message']);
			
			//mail link
			$messageArray['message'] = preg_replace('/\\b(?:mailto:)?([A-Z0-9._%-]+@[A-Z0-9._%-]+\\.[A-Z]{2,4})\\b/i', '<a href="mailto:\\1">'.$urlText.'</a>', $messageArray['message']);
			
			$this->data = $messageArray;
						
		}
		
		
		/**
		* Convert all URL simple tags [b] [i] and [u] into it's respective html tag
		*
		* @access public
		* @return void
		*/
		function translateSimpleTag(){
			
			$messageArray = $this->data;
			
			//replace this patterns into replacements, this is case sensitive
			$patterns = array('/\[b\](.*?)\[\/b\]/',
							  '/\[u\](.*?)\[\/u\]/',
							  '/\[i\](.*?)\[\/i\]/');
			$replacements = array('<b>\\1</b>',
								  '<u>\\1</u>',
								  '<i>\\1</i>');
			
			$messageArray['message'] = preg_replace($patterns,$replacements, $messageArray['message']);
			
			$this->data = $messageArray;
						
		}
			
		/**
		* Parse the URL given into hyperlink or mailto link
		*
		* @access public
		* @return void
		*/
		function parseNicknameLink(){
			
			$messageArray = $this->data;
			
			$url  = $messageArray['url'];
			$name = $messageArray['name'];
			
			if (preg_match('/^[A-Z0-9._-]+@[A-Z0-9][A-Z0-9.-]{0,61}[A-Z0-9]\.[A-Z.]{2,6}$/i', $url)) {
				//if the URL given is email
				$messageArray['url'] = preg_replace('/\\b(?:mailto:)?([A-Z0-9._%-]+@[A-Z0-9._%-]+\\.[A-Z]{2,4})\\b/i', '<a href="mailto:\\1">'.$name.'</a>', $url);
			} else {
				//if not, assume it's hyperlink
				$messageArray['url'] = preg_replace('/\\b(https?|ftp):\/\/[-A-Z0-9+&@#\/%?=~_|!:,.;]*[-A-Z0-9+&@#\/%=~_|]/i', '<a href="\\0" target="_blank" rel="external nofollow">'.$name.'</a>', $url);
				
				if($url == $messageArray['url']){ //if there's no change, than it's not a valid link, so use original nick name
					$messageArray['url'] = $messageArray['name'];
				}
			}
			
			
			$this->data = $messageArray;
						
		}
		
		
		/**
		* Translate smiley codes into image
		*
		* @access public
		* @return void
		*/
		function translateSmiley($patternArray){
								
			foreach ($patternArray as $value){
				$patterns[] 	  = $value['pattern'];
				$replacements[]   = '<img src="images/smilies/'.$value['image'].'" border="0" alt="'.$value['pattern'].'"/>';
			}
			
			$messageArray = $this->data;
			
			//replace all smiley code into image
			$messageArray['message'] = str_ireplace($patterns,$replacements,$messageArray['message']);
					
			$this->data = $messageArray;
						
		}
		
		/**
		* Translate bad words found in messages as defined in the configuration file
		*
		* @access public
		* @return void
		*/
		function translateBadword($patternArray){
						
			$messageArray = $this->data;
			
			if(is_array($patternArray['badwords']) && !empty($patternArray['replacement'])){
				$messageArray['message'] = str_ireplace($patternArray['badwords'],$patternArray['replacement'],$messageArray['message']);
				$messageArray['name']    = str_ireplace($patternArray['badwords'],$patternArray['replacement'],$messageArray['name']);
				
			}
						
			$this->data = $messageArray;
						
		}
		
		/**
		* Gets the processed input result
		*
		* @access public
		* @return array
		*/
		function getResult(){
			return $this->data;
		}
		
		

	}
?>