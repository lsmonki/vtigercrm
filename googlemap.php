<?php
/*********************************************************************************
  ** The contents of this file are subject to the vtiger CRM Public License Version 1.0
   * ("License"); You may not use this file except in compliance with the License
   * The Original Code is:  vtiger CRM Open Source
   * The Initial Developer of the Original Code is vtiger.
   * Portions created by vtiger are Copyright (C) vtiger.
   * All Rights Reserved.
  *
 ********************************************************************************/
    require('GoogleMapAPI/GoogleMapAPI.class.php');
    require_once('include/database/PearDatabase.php');
    global $adb;
    if(isset($_REQUEST['record']) && $_REQUEST['record']!='')
    {
	switch($_REQUEST['module'])
	{
		case 'Accounts':
			$query = 'select * from accountbillads where accountaddressid='.$_REQUEST['record'];
			$addresslabel = 'street';
			$citylabel = 'city';
			$statelabel = 'state';
			$ziplabel = 'code';
			break;
		case 'Contacts':
			$addresslabel = 'mailingstreet';
			$citylabel = 'mailingcity';
			$statelabel = 'mailingstate';
			$ziplabel = 'mailingzip';
			$query = 'select * from contactaddress where contactaddressid='.$_REQUEST['record'];
			break;
		case 'Leads':
			$addresslabel = 'lane';
			$citylabel = 'city';
			$statelabel = 'state';
			$ziplabel = 'code';
			$query = 'select * from leadaddress where leadaddressid='.$_REQUEST['record'];
			break;
			
	}
    }else
    {
	switch($_REQUEST['module'])
	{
		case 'Accounts':   
			$addresslabel = 'street';
			$citylabel = 'city';
			$statelabel = 'state';
			$ziplabel = 'code';
			$query = 'select accountbillads.* from accountbillads INNER JOIN crmentity ON crmentity.crmid = accountbillads.accountaddressid where crmentity.deleted=0';
			break;
		case 'Contacts':
			$addresslabel = 'mailingstreet';
			$citylabel = 'mailingcity';
			$statelabel = 'mailingstate';
			$ziplabel = 'mailingzip';
			$query = 'select contactaddress.* from contactaddress INNER JOIN crmentity ON crmentity.crmid = contactaddress.contactaddressid where crmentity.deleted=0';	
			break;
		case 'Leads':
			$addresslabel = 'lane';
			$citylabel = 'city';
			$statelabel = 'state';
			$ziplabel = 'code';
			$query = 'select leadaddress.* from leadaddress INNER JOIN crmentity ON crmentity.crmid = leadaddress.leadaddressid where crmentity.deleted=0';
			break;
	}
    }	    
    $result = $adb->query($query);
    //$addressResult = $adb->fetch_array($result);
    $noofrows = $adb->num_rows($result);
    $map = new GoogleMapAPI('vtiger','usegooglemap');
    // setup database for geocode caching[Eg:$map->setDSN('mysql://geo:foobar@localhost/GEOCODES');]
    $map->setDSN('mysql://root: @localhost:9101/vtigercrm5_beta');
    
    // enter YOUR Google Map Key
    $map->setAPIKey('ABQIAAAA1aEZNZl6ypJ3bonIc7bw_xT-c4B5fj384skyXsFRDY0_1FU3xhTcgaRmVZTGJpH9MYJ6CYoKazzKbQ');
    for($i=0;$i<$noofrows;$i++)
    {
	    $address = $adb->query_result($result,$i,$addresslabel);
	    $city = $adb->query_result($result,$i,$citylabel);
	    $state = $adb->query_result($result,$i,$statelabel);
	    $zip = $adb->query_result($result,$i,$ziplabel);
	    $sql = 'select contactaddress.* from contactaddress INNER JOIN crmentity ON crmentity.crmid = contactaddress.contactaddressid where crmentity.deleted=0 and mailingcity="'.$city.'" and mailingstate="'.$state.'"';
	    $res =  $adb->query($sql);
	    $number = $adb->num_rows($res);
	    $tempcity = str_replace(' ', '', $city);
	    // create some map markers
	    $map->addMarkerByAddress($address.' '.$tempcity.' '.$state.' '.$zip,$city,'<b>'.$city.'</b>','No. of '.$_REQUEST['module'].':'.$number);
    }
    
?>
    
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml">
	<head>
		<?php $map->printHeaderJS(); ?>
		<?php $map->printMapJS(); ?>
		<style type="text/css">
			v\:* {	behavior:url(#default#VML);	}
		</style>
	</head>
	<body onload="onLoad()">
	<table border=1>
		<tr><td>
			<?php $map->printMap(); ?>
		</td><td>
			<?php $map->printSidebar(); ?>
		</td></tr>
	</table>
	</body>
</html>
	
