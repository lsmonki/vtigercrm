<?php
/********************************************************************************
** The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
* 
 ********************************************************************************/

$filename = $_REQUEST['filename'];
$fileType = $_REQUEST['filetype'];
$fileid = $_REQUEST['fileid'];
$filesize = $_REQUEST['filesize'];

$contentname = $fileid.'_filecontents';
$fileContent = $_SESSION[$contentname];

@header("Content-type: $fileType");
@header("Content-length: $filesize");
@header("Cache-Control: private");
@header("Content-Disposition: attachment; filename=$filename");
@header("Content-Description: PHP Generated Data");
echo base64_decode($fileContent);



?>
