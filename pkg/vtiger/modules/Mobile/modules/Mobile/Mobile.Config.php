<?php
/*+**********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 ************************************************************************************/

$Module_Mobile_Configuration = array(

	'Default.Skin'     => 'default.css', // Available in resources/skins
	'Navigation.Limit' => 25,

	// Control number of records sent out through API (SyncModuleRecords, Query...) which supports paging.	
	'API_RECORD_FETCH_LIMIT' => 99, // NOTE: vtws_query internally limits fetch to 100 and give room to perform 1 extra fetch to determine paging

	// NOTE: Please configure the following path and make sure it is readable for the Apache process owner
	'TESSERACT_EXECUTABLE' => '/root/prasad/install/bin/tesseract',
	'TESSERACT_TESSDATA'   => '/root/prasad/', // Should end with / or \ & tessdata should be under it (like /tesseractdir/share/) 
	
);

?>