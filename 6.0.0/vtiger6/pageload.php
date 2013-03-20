<?php
/*+**********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 ************************************************************************************/

/**
 * Placeholder file to receive the notification about page-rendering time from the time.
 * AccessLog tracking can help us know the latency etc...(this file is preferable to be at same level as index.php)
 * 
 * Parameters sent in $_REQUEST: page_request, page_xfer, client_now, client_tzoffset, page_load, page_ready
 */ 
 /* 
  * Usage: (Server output on page)
  * var _REQSTARTTIME = $_SERVER['REQUEST_TIME'];
  * 
  * (Client side)
	jQuery(document).ready(function() { window._PAGEREADYAT = new Date(); });
	jQuery(window).load(function() {
		window._PAGELOADAT = new Date();
		// Transmit the information to server about page render time now.
		if (typeof _REQSTARTTIME != 'undefined') {
			// Work with time converting it to GMT (assuming _REQSTARTTIME set by server is also in GMT)
			var _PAGEREADYTIME = _PAGEREADYAT.getTime() / 1000.0; // seconds
			var _PAGELOADTIME = _PAGELOADAT.getTime() / 1000.0;    // seconds
			var data = { page_request: _REQSTARTTIME, page_ready: _PAGEREADYTIME, page_load: _PAGELOADTIME };
			data['page_xfer'] = (_PAGELOADTIME - _REQSTARTTIME).toFixed(3);
			data['client_tzoffset']= -1*_PAGELOADAT.getTimezoneOffset()*60;
			data['client_now'] = JSON.parse(JSON.stringify(new Date())); // Overcome IE8 lack of Date.toISOString();
			jQuery.get('pageload.php', data);
		}
	});
  */ 

/* Send no-cache headers to get this request back every time */
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); header("Cache-Control: no-cache"); header("Pragma: no-cache");

/* TODO: We could track the information and notify if user is on slow-connection */
