<?php

	interface VTEnv{
		function get($var);
	}
	
	function _vt_add($arr){
		if(sizeof($arr)==1){
			return $arr[0];
		}else{
			return $arr[0]+$arr[1];
		}
	}
	
	function _vt_sub($arr){
		if(sizeof($arr)==1){
			return -$arr[0];
		}else{
			return $arr[0]-$arr[1];
		}
	}
	
	function _vt_mul($arr){
		return $arr[0]*$arr[1];
	}
	
	function _vt_div($arr){
		return $arr[0]/$arr[1];
	}
	
	function _vt_equals($arr){
		return $arr[0] == $arr[1];
	}
	
	function _vt_concat($arr){
		return implode($arr);
	}
	
	class VTExpressionEvaluater{
		function __construct($expr){
			
			$this->operators = array(
				'+'=>'_vt_add',
				'-'=>'_vt_sub',
				'*'=>'_vt_mul',
				'/'=>'_vt_div',
				'=='=>'_vt_equals',
			);
			$this->functions = array(
				'concat'=>'_vt_concat'
			);
			
			$this->operations = array_merge($this->functions, $this->operators);
			$this->expr = $expr;
			
		}
		
		function evaluate($env){
			$this->env = $env;
			return $this->exec($this->expr);
		}
		
		function exec($expr){
			if($expr instanceof Symbol){
				return $this->env($expr);
			}else if($expr instanceof VTTreeNode){
				$op = $expr->getName();
				if($op->value=='if'){
					$params = $expr->getParams();
					$cond = $this->exec($params[0]);
					if($cond){
						return $this->exec($params[1]);
					}else{
						return $this->exec($params[2]);
					}
				}else{
					$params = array_map(array($this, 'exec'), $expr->getParams());
					$func = $this->operations[$op->value];
					return $func($params);
				}
			}else{
				return $expr;
			}
		}
		
		function env($sym){
			return $this->env->get($sym->value);
		}
	}
?>