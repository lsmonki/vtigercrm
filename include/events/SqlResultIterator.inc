<?php
	class SqlResultIterator implements Iterator{
		function __construct($adb, $result){
			$this->result = $result;
			$this->adb = $adb;
			$this->pos = 0;
		}
		
		public function rewind(){
			$this->pos = 0;
		}
		
		function valid(){
			$adb = $this->adb;
			return $this->pos < $adb->num_rows($this->result);
		}
		
		public function next(){
			$this->pos+=1;
		}
		
		public function current(){
			$adb = $this->adb;
			return new SqlResultIteratorRow($adb, $this->pos, $this->result);
		}
		
		public function key(){
			return $this->pos;
		}
		
		
		/**
		 * Return the contents of the resultset as an array. Destroys a running iterator's state.
		 *
		 * This method will reset the iterator using the rewind method.
		 * 
		 * $copyFields specify which fields of the result to copy to the array. 
		 * If not specified the function will return values for all the fields.
		 */
		function toArray($copyFields=null){
			$adb = $this->adb;
			$this->rewind();
			
			if($copyFields===null){
				$columnData = $adb->getFieldsDefinition($this->result);
				$columnNames = array();
				foreach($columnData as $column){
					$columnNames[]=$column->name;
				}
				$copyFields = $columnNames;
			}
			
			$arr=array();
			foreach($this as $row){
				$rowArr = array();
				foreach($copyFields as $name){
					$rowArr[$name]=$row->$name;
				}
				$arr[]=$rowArr;
			}
			return $arr;
			$this->rewind();
		}
	}
	
	class SqlResultIteratorRow{
		function __construct($adb, $row, $result){
			$this->adb = $adb;
			$this->row = $row;
			$this->result = $result;
		}
		
		function get($column){
			$adb = $this->adb;
			$val = $adb->query_result($this->result,$this->row,$column);
			if(is_string($val)){
				$val = html_entity_decode($val);
			}
			return $val;
		}
		
		function __get($column){
			return $this->get($column);
		}
		
	}
	
	
?>