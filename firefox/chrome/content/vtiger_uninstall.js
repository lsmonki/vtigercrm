/* ********************************************************************************
* The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
*
******************************************************************************* */

function VTIGER_Uninstall()
{
    try {
		//confirm the unstallation of vtigercrm toolbar
                if(!confirm("Do you want to Uninstall vtigerCRM Toolbar ?"))
			//if No,cancel unstallation
            		return;
				//if yes,start extension manager and remove the extension 
			    var extMan = Components.classes["@mozilla.org/extensions/manager;1"].getService(Components.interfaces.nsIExtensionManager);
				extMan.start(true);
				extMan.uninstallExtension('{C37F0C52-7B57-4df8-8E86-6FA3BA508585}');

               	alert("vtigerCRM Toolbar will be removed once you restart the browser");

        }
    catch(e)
        {
    	}



}
