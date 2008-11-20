<?php
class VTSimpleTemplate{
	
	function __construct($templateString){
		$this->template = $templateString;
	}
	
	function render($data){
		$this->data = $data;
		return $this->parseTemplate($this->template);
	}
	
	private function matchHandler($match){
		return $this->data[$match[1]];
	}
	
	function parseTemplate($str){
		return preg_replace_callback('/\\$(\w+)/', array($this,"matchHandler"), $this->template);
	}
}
?>