<?php

require_once('include/database/PearDatabase.php');

$id = $_REQUEST['id'];
if(!is_numeric($id)) {
	print 'Invalid Record';
	exit;
}

// determine record type
$db = new PearDatabase();
$sql = "SELECT setype, smownerid FROM crmentity WHERE crmid = ".$id;
$res = $db->query($sql);
$type = $db->query_result($res, 0, 'setype');
$smownerid = $db->query_result($res, 0, 'smownerid');

// determine destination address
switch($type) {
	case 'Leads':
		$query = "SELECT lane, city, state, code FROM leadaddress WHERE leadaddressid = ".$id;
		$result = $db->query($query, true, "Error:");
		$daddrt = $db->query_result($result,0,"code");
		if(!$daddrt) {
			$daddrt = $db->query_result($result,0,"lane")." ";
			$daddrt .= $db->query_result($result,0,"city")." ";
			$daddrt .= $db->query_result($result,0,"state");
		}
		break;
	case 'Accounts':
		$query = "SELECT street, city, state, code FROM accountbillads WHERE accountaddressid = ".$id;
		$result = $db->query($query, true, "Error:");
		$daddrt = $db->query_result($result,0,"code");
		if(!$daddrt) {
			$daddrt = $db->query_result($result,0,"street")." ";
			$daddrt .= $db->query_result($result,0,"city")." ";
			$daddrt .= $db->query_result($result,0,"state");
		}
		break;
	case 'Contacts':
		$query = "SELECT mailingstreet, mailingcity, mailingstate, mailingzip FROM contactaddress WHERE contactaddressid = ".$id;
		$result = $db->query($query, true, "Error:");
		$daddrt = $db->query_result($result,0,"mailingzip");
		if(!$daddrt) {
			$daddrt .= $db->query_result($result,0,"mailingstreet")." ";
			$daddrt .= $db->query_result($result,0,"mailingcity")." ";
			$daddrt .= $db->query_result($result,0,"mailingstate");
		}
		break;
}

// determine source address if any
$saddrt = '';
$query = "SELECT map_source, address_street, address_city, address_postalcode FROM users WHERE id = ".$smownerid;
$result = $db->query($query, true, "Error:");
$map_source = $db->query_result($result, 0, "map_source");
if($map_source == 'Work') {
	// from orgainization
	$query = "SELECT address, city, state, code FROM organizationdetails";
	$result = $db->query($query, true, "Error:");
	$saddrt = $db->query_result($result,0,"code");
	if(!$saddrt) {
		$saddrt = $db->query_result($result,0,"address")." ";
		$saddrt .= $db->query_result($result,0,"city")." ";
		$saddrt .= $db->query_result($result,0,"state");
	}
} elseif($map_source == 'Home' && $smownerid) {
	// from user's address
	$saddrt = $db->query_result($result,0,"address_postalcode");
	if(!$saddrt) {
		$saddrt = $db->query_result($result,0,"address_street")." ";
		$saddrt .= $db->query_result($result,0,"address_city")." ";
		$saddrt .= $db->query_result($result,0,"address_state");
	}
}

$daddr = ereg_replace(" ", "+",$daddrt);
if($saddrt) {
	$saddr = ereg_replace(" ", "+",$saddrt);
	header("Location: http://maps.google.com/maps?saddr=$saddrt&daddr=$daddr");
} else {
	header("Location: http://maps.google.com/maps?q=$daddr");
}
