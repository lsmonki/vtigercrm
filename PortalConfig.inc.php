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

$PortalConfig_Parameters= Array (

/* 
* 'viewall' set to true will show the records that belong to the logged in contact, 
* account to which the contact belong and the those contacts that belong to the same account. 
* Set 'viewall' to false if you want to show only those records that belong to the logged in contact. 
*/

'configModules' => array('HelpDesk'=> 		array('viewall' => true),
						'Faq'=> 			array('viewall' => true),
						'Contacts'=> 		array('viewall' => true),
						'Accounts'=> 		array('viewall' => true),
						'Products'=> 		array('viewall' => true),
						'Services'=>		array('viewall' => true),
						'Invoice'=> 		array('viewall' => true),
						'Quotes'=> 			array('viewall' => true),
						'Documents'=> 		array('viewall' => true)
					),

// Group or the User name, to whom the tickets created from portal are assigned by default.				
'Ticket_Assigned_to' => 'admin',

/* this will give you control on the records that are to be shown in customerportal,
 * if the module is made private the,then only the logged in contact's info are shown
 * By setting this variable to true you can allow the contacts to see the others contacts info also
 * This will basically override the crm 
 **/ 
'overRideCrmPrivacy' => false

);

?>