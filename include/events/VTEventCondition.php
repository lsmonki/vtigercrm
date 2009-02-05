<?php
	class VTEventCondition{
		function __construct($expr){
			if($expr!=''){
				$parser = $this->getParser($expr);
				$this->expr = $parser->statement();
			}else{
				$this->expr = null;
			}
		}
		
		
		function test($obj){
			$this->data = $obj;
			if($this->expr==null){
				return true;
			}else{
				return $this->evaluate($this->expr);
			}
		}
		
		private function getParser($expr){
			$ass = new ANTLRStringStream($expr);
			$lex = new VTEventConditionParserLexer($ass);
			$cts = new CommonTokenStream($lex);
			$tap = new VTEventConditionParserParser($cts);
			return $tap;
		}
		
		
		private function evaluate($expr){
			if(is_array($expr)){
				$oper = $expr[0];
				if($oper=='.'){
					$out = $this->data;
					$syms = array_slice($expr, 1);
					foreach($syms as $sym){
						$out = $this->get($out, $sym->name);
					}
					return $out;
				}else{
					$evaled = array_map(array($this, 'evaluate'), array_slice($expr, 1));
					switch($oper){
						case "in":
							return in_array($evaled[0], $evaled[1]);
						case "==":
							return $evaled[0] == $evaled[1];
						case "list":
							return $evaled;
						default:
							return false;
					}
				}
			}else{
				if($expr instanceof VTEventConditionSymbol){
					return $this->get($this->data, $expr->name);
				}else{
					return $expr;
				}
			}
		}
		
		private function get($obj, $field){
			if(is_array($yes)){
				return $obj[$field];
			}else{
				$func = "get".ucwords($field);
				return $obj->$func();
			}
			
		}
	}
?>