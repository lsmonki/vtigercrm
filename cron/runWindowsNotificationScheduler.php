<?php 

$output = shell_exec("schtasks /create /tn 'vtigerCRM Notification Scheduler' /tr INSTALLPATH\intimateTaskStatus.bat /sc daily /st 11:00:00");
echo $output;


?>
