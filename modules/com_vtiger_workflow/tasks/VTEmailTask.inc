<?php
class VTEmailTask extends VTTask{

	public function getFieldNames(){
		return array("subject", "content", "recepient");
	}
	public function doTask($module, $data){
		$to_email = $this->recepient;
		$from_email = "sidharth.kuruvila@gmail.com";
		$st = new VTSimpleTemplate($this->subject);
		$subject = $st->render($data);
		$ct = new VTSimpleTemplate($this->content);
		$content = $ct->render($data);
		send_mail($module,$to_email,$from_name,$from_email,$subject,$content);
	}
}
?>