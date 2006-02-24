<?php
set_time_limit(600);

require_once('include/database/PearDatabase.php');

require_once('config.php');

$conn = ADONewConnection($dbconfig['db_type']);
$conn->Connect(
	$dbconfig['db_hostname'],
	$dbconfig['db_username'],
	$dbconfig['db_password'],
	$dbconfig['db_name']);


$schema = new adoSchema( $conn );
$schema->XMLS_DEBUG = true;

//Get schema without data
$xmlresult = $schema->ExtractSchema(false);
header("content-type: text/plain");
#echo "abcd";

if (!$schemaFile = fopen("schema/DatabaseSchema.xml", w)) {
	echo "Cannot open file ($filename)";
	exit;
}

if (fwrite($schemaFile, $xmlresult) === FALSE) {
	echo "Cannot write to file ($schemaFile)";
	exit;
}

echo $xmlresult;
