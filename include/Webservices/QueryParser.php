<?php
	require_once("include/Webservices/VTQL_Lexer.php");
	require_once("include/Webservices/VTQL_Parser.php");
	
	class Parser{
		
		private $query = "";
		private $out;
		private $meta;
		private $hasError ;
		private $error ;
		private $user; 
		function Parser($user, $q){
			$this->query = $q;
			$this->out = array();
			$this->hasError = false;
			$this->user = $user; 
		}
		
		function parse(){
			
			$lex = new VTQL_Lexer($this->query);
			$parser = new VTQL_Parser($this->user, $lex,$this->out);
			while ($lex->yylex()) {
				$parser->doParse($lex->token, $lex->value);
			}
			$parser->doParse(0, 0);
			
			if($parser->isSuccess()){
				$this->hasError = false;
				$this->query = $parser->getQuery();
				$this->meta = $parser->getObjectMetaData();
			}else{
				$this->hasError = true;
				$this->error = $parser->getErrorMsg();
			}
			
			return $this->hasError;
			
		}
		
		function getSql(){
			return $this->query;
		}
		
		function getObjectMetaData(){
			return $this->meta;
		}
		
		function getError(){
			return $this->error;
		}
		
	}
?>