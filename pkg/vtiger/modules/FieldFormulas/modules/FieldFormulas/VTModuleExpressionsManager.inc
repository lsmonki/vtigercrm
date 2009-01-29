<?php
	require_once("include/events/SqlResultIterator.inc");
	class VTModuleExpressionsManager{
		function __construct($adb){
			$this->adb = $adb;
		}
		
		function retrieve($moduleName){
			$adb = $this->adb;
			$result = $adb->pquery('select * from vtiger_fieldformulas where modulename=?', array($moduleName));
			if($adb->num_rows($result)==0){
				return new VTModuleExpressions($moduleName, new VTExpressionEngine());
			}else{
				$ee = unserialize(html_entity_decode($adb->query_result($result, 0, "expression_engine")));
				$id = $adb->query_result($result, 0, "expressionid");
				$me = new VTModuleExpressions($moduleName, $ee);
				$me->id = $id;
				return $me;
			}
		}
		
		function save($moduleExpressions){
			$adb = $this->adb;
			if(isset($moduleExpressions->id)){
				$adb->pquery('update vtiger_fieldformulas set expression_engine=? where expressionid=?', 
					array(serialize($moduleExpressions->expressionEngine), $moduleExpressions->id));
			}else{
				$id = $adb->getUniqueId('vtiger_fieldformulas');
				$adb->pquery('insert into vtiger_fieldformulas 
					(expressionid, modulename, expression_engine) values(?,?,?)', 
					array($id, $moduleExpressions->moduleName, 
						serialize($moduleExpressions->expressionEngine)));
				$moduleExpressions->id = $id;
			}
		}
		
		
		function expressionFields($moduleName){
			return $this->fieldNames(
				"select * from vtiger_field 
				where tabid in (select tabid from vtiger_tab 
								where name=? and generatedtype=2) and presence in(0,2)",
				$moduleName);
		}
		
		function fields($moduleName){
			return $this->fieldNames(
				"select * from vtiger_field 
				where tabid in (select tabid from vtiger_tab 
								where name=?) and presence in(0,2)", 
				$moduleName);
		}
		
		function expressionFunctions(){
			return array('concat' => 'concat(<a>,<b>)');
		}
		
		private function fieldNames($query, $moduleName){
			$adb = $this->adb;
			$result = $adb->pquery($query, array($moduleName));
			$it = new SqlResultIterator($adb, $result);
			$arr = array();
			foreach($it as $row){
				$arr[$row->fieldname]=$row->fieldlabel;
			}
			return $arr;
		}
		
		function expressionsForModule($moduleName){
			return $this->retrieve($moduleName)->asArray();
		}
	}
	
	class VTModuleExpressions{
		function __construct($moduleName, $expressionEngine){
			$this->moduleName=$moduleName;
			$this->expressionEngine=$expressionEngine;
		}
		
		function add($fieldName, $expression){
			$this->expressionEngine->loadExpressions(array($fieldName=>$expression));
			$this->state='savable';
		}
		
		function remove($fieldName){
			$this->expressionEngine->removeExpression($fieldName);
		}
		
		function asArray(){
			return $this->expressionEngine->unparsedExpressions;
		}
		
		function parseExpression($expr){
			$parser = new VTParser(new SpaceFilter(new VTTokenizer($expr)));
			return $parser->expression();
		}

		function update($entity){
			$bound = $this->expressionEngine->evaluate($entity->getData());
			foreach($bound as $field => $value){
				$entity->set($field, $value);
			}
		}

		function getFieldsFromExpr($expr){
			function __getFieldsFromExpr($expr, &$arr){
				switch(get_class($expr)){
					case 'VTTreeNode':
						$params = $expr->getParams();
						foreach($params as $param){
							__vtGetFieldsFromExpr($param, $arr);
						}
						return;
					case 'Symbol':
						$arr[$expr->value] = $expr;
						return;
					default:
						return;
				}
			}
			__vtGetFieldsFromExpr($expr, $arr);
			return array_keys($arr);
		}
	}
	
?>
