<?php
	require_once("VTJsonCondition.inc");
	
	class VTWorkflowManager{
		
		static $ON_FIRST_SAVE=1;
		static $ONCE=2;
		static $ON_EVERY_SAVE=3;
		
		
		function __construct($adb){
			$this->adb = $adb;
		}
		
		function save($workflow){
			$adb=$this->adb;
			if(isset($workflow->id)){
				$wf=$workflow;
				$adb->pquery("update com_vtiger_workflows set
								module_name=?, summary=?, test=?, execution_condition=?
								where workflow_id=?", 
					array($wf->moduleName, $wf->description, $wf->test, $wf->executionCondition, $wf->id));
			}else{
				$workflowId = $adb->getUniqueID("com_vtiger_workflows");
				$workflow->id = $workflowId;
				$wf=$workflow;
				$adb->pquery("insert into com_vtiger_workflows 
								(workflow_id, module_name, summary, test, execution_condition) 
								values (?,?,?,?,?)", 
					array($workflowId, $wf->moduleName, $wf->description, $wf->test,  $wf->executionCondition));
			}
		}
		
		
		function getWorkflows(){
			$adb=$this->adb;
			$result = $adb->query("select workflow_id, module_name, summary, test, execution_condition 
									from com_vtiger_workflows");
			return $this->getWorkflowsForResult($result);
			
		}
		
		function getWorkflowsForModule($moduleName){
			$adb=$this->adb;
			$result = $adb->pquery("select workflow_id, module_name, summary, test, execution_condition 
									from com_vtiger_workflows where module_name=?",array($moduleName));
			return $this->getWorkflowsForResult($result);
		}
		
		private function getWorkflowsForResult($result){
			$adb=$this->adb;
			$it = new SqlResultIterator($adb, $result);
			$workflows=array();
			foreach($it as $row){
				$workflow=new Workflow();
				$workflow->id = $row->workflow_id;
				$workflow->moduleName = $row->module_name;
				$workflow->description = $row->summary;
				$workflow->test = $row->test;
				$workflow->executionCondition = $row->execution_condition;
				$workflows[]=$workflow;
			}
			return $workflows;
		}
		
		function retrieve($id){
			$adb=$this->adb;
			$workflow=new Workflow();
			$result = $adb->pquery("select workflow_id, module_name, summary, test, execution_condition 
									from com_vtiger_workflows where workflow_id=?", 
						array($id));
			$workflow->id = $adb->query_result($result,0,"workflow_id");
			$workflow->moduleName = $adb->query_result($result,0,"module_name");
			$workflow->description = $adb->query_result($result,0,"summary");
			$workflow->test = html_entity_decode($adb->query_result($result,0,"test"));
			$workflow->executionCondition = $adb->query_result($result,0,'execution_condition');
			return $workflow;
		}
		
		function delete($id){
			$adb=$this->adb;
			$adb->pquery("delete from com_vtiger_workflowtasks where workflow_id=?", array($id));
			$adb->pquery("delete from com_vtiger_workflows where workflow_id=?", array($id));
		}
		
		function newWorkflow($moduleName){
			$workflow=new Workflow();
			$workflow->moduleName = $moduleName;
			$workflow->executionCondition = self::$ON_EVERY_SAVE;
			return $workflow;
			
		}
		
		function getEntityCache(){
			return new VTEntityCache($this->adb);
		}
		
	}
	
	class Workflow{
		function __construct(){
			$this->conditionStrategy = new VTJsonCondition();
		}
		
		function evaluate($entityCache, $id){
			if($this->test==""){
				return true;
			}else{
				$cs = $this->conditionStrategy;
				return $cs->evaluate($this->test, 
									 $entityCache, $id);
			}
		}
		
		function executionConditionAsLabel($label=null){
			if($label==null){
				$arr = array('ON_FIRST_SAVE', 'ONCE', 'ON_EVERY_SAVE');
				return $arr[$this->executionCondition-1];
			}else{
				$arr = array('ON_FIRST_SAVE'=>1, 'ONCE'=>2, 'ON_EVERY_SAVE'=>3);
				$this->executionCondition = $arr[$label];
			}
		}
	}
?>