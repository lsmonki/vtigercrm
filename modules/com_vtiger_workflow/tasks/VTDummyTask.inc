<?php
class VTDummyTask extends VTTask{
	public $executeImmediately = true; 
	
	public function getFieldNames(){return array();}
	public function doTask($entity){
		$statement=$this->statement;
		echo "This is a dummy workflow task with $statement";
	}
}
?>