<?php
require_once 'include/Webservices/Utils.php';
require_once("include/Zend/Json.php");
require_once("include/Webservices/VtigerCRMObject.php");
require_once("include/Webservices/VtigerCRMObjectMeta.php");
require_once("include/Webservices/WebServiceError.php");
require_once 'include/Webservices/ModuleTypes.php';
require_once("include/Webservices/DescribeObject.php");

global $app_strings;

global $currentModule;
global $theme;

require_once('Smarty_setup.php');

// focus_list is the means of passing data to a ListView.
global $focus_list;

if (!isset($where)) $where = "";

$url_string = '';

$smarty = new vtigerCRM_Smarty;
$smarty->assign("subject",$_REQUEST['subject']);
$smarty->assign("description",$_REQUEST['description']);

Zend_Json::$useBuiltinEncoderDecoder = true;
$json = new Zend_Json();

$elementType = $_REQUEST['module'];
$crmObject = new VtigerCRMObject($elementType);

$types = vtws_listtypes($current_user);
if(!in_array($elementType,$types['types'])){
	return new WebServiceError(WebServiceErrorCode::$ACCESSDENIED,"Permission to perform the operation is denied");
}

$meta = new VtigerCRMObjectMeta($crmObject,$current_user);
if(!$meta->hasAccess()){
	return new WebServiceError(WebServiceErrorCode::$ACCESSDENIED,"Permission to access object type is denied");
}
$fieldColumnMapping = $meta->getFieldColumnMapping();
$column = $fieldColumnMapping['parent_id'];
$wsFieldDetails = vtws_getField($column,$meta,$current_user);

$moduleEntityNameDetails = array();
foreach ($wsFieldDetails['type']['refersTo'] as $type) {
	$moduleEntityNameDetails[$type] = vtws_getEntityNameFields($type);
}

$smarty->assign("types",$wsFieldDetails['type']['refersTo']);
$smarty->assign("entityNameFields",$json->encode($moduleEntityNameDetails));

$smarty->display("modules/Bookmarklet/Bookmarklet.tpl");
?>