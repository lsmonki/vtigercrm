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

/* 'allow' set to true will allow the module to be shown in Customer portal. Set it to false if you want to hide the tab/module.
* 'viewall' set to true will show the records that belong to the logged in contact, 
* account to which the contact belong and the those contacts that belong to the same account. 
* Set 'viewall' to false if you want to show only those records that belong to the logged in contact. 
*/
$configModules = array(	'Tickets'=> array('allow' => true, 'viewall' => true),
						'Faq'=> array('allow' => true, 'viewall' => true),
						'Contacts'=> array('allow' => true, 'viewall' => true),
						'Accounts'=> array('allow' => true, 'viewall' => true),
						'Products'=> array('allow' => true, 'viewall' => true),
						'Invoice'=> array('allow' => true, 'viewall' => true),
						'Quotes'=> array('allow' => true, 'viewall' => true),
						'Documents'=> array('allow' => true, 'viewall' => true)
					);

// Group or the User name, to whom the tickets created from portal are assigned by default.				
$Ticket_Assigned_to = 'admin';

?>