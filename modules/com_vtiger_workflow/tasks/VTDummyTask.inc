<?php
class VTDummyTask extends VTTask{
	public function getFieldNames(){return array();}
	public function doTask($module, $data){
		$statement=$this->statement;
		echo "Wel du'h. I is too dumb to do anything but say $statement";
	}
}
?>