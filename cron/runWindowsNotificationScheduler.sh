<?php 

$output = shell_exec("schtasks /create /tn 'vtigerCRM Notification Scheduler' /tr c:\apps\intimateTaskStatus.bat /sc daily /st 11:00:00");
echo $output;


?>
