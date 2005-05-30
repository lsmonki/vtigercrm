@echo off
set SCH_INSTALL=%1
FOR %%X in (%SCH_INSTALL%) DO SET SCH_INSTALL=%%~sX
schtasks /create /tn "vtigerCRM Notification Scheduler" /tr %SCH_INSTALL%\apache\htdocs\vtigerCRM\cron\intimateTaskStatus.bat /sc daily /st 11:00:00 /RU SYSTEM
schtasks /create /tn "vtigerCRM Email Reminder" /tr %SCH_INSTALL%\apache\htdocs\vtigerCRM\modules\Activities\SendReminder.bat /sc minute /mo 1 /RU SYSTEM
