//$Id:
/* ********************************************************************************
* The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
*
******************************************************************************* */
const APP_DISPLAY_NAME = "vtiger Thunderbird Extension";
const APP_NAME = "vtiger";
const APP_PACKAGE = "/vtiger";
const APP_VERSION = "5.0.0";

const APP_JAR_FILE = "vtiger.jar";
const APP_LOCALE_FOLDER  = "content/vtiger/";
const APP_CONTENT_FOLDER = "content/vtiger/";
const APP_SKIN_FOLDER = "skin/classic/vtiger/";

const INST_TO_PROFILE = "Do you wish to install "+APP_DISPLAY_NAME+" ?";

var instToProfile = confirm(INST_TO_PROFILE);
var InstIt = 0
var inst = instToProfile ? InstIt = 1 : InstIt = 0;
if(InstIt == 1) {
    initInstall(APP_NAME, APP_PACKAGE, APP_VERSION);
    var chromef = instToProfile ? getFolder("Profile", "chrome") : getFolder("chrome");
    var err = addFile(APP_PACKAGE, APP_VERSION, "chrome/" + APP_JAR_FILE, chromef, null)

    if(err == SUCCESS) {
        var jar = getFolder(chromef, APP_JAR_FILE);
	    if(instToProfile) {
            registerChrome(CONTENT | PROFILE_CHROME, jar, APP_CONTENT_FOLDER);
            // registerChrome(LOCALE  | PROFILE_CHROME, jar, APP_LOCALE_FOLDER);
   	        registerChrome(SKIN    | PROFILE_CHROME, jar, APP_SKIN_FOLDER);
        } else {
            registerChrome(CONTENT | DELAYED_CHROME, jar, APP_CONTENT_FOLDER);
   	        registerChrome(SKIN    | DELAYED_CHROME, jar, APP_SKIN_FOLDER);
        }
        err = performInstall();
	  
        if(err == SUCCESS || err == 999) {
            
        } else {
	        alert("Install failed. Error code:" + err);
	        cancelInstall(err);
        }
    } else {
	    alert("Failed to create " +APP_JAR_FILE +"\n"
		+"You probably don't have appropriate permissions \n"
		+"(write access to your profile or chrome directory). \n"
		+"_____________________________\nError code:" + err);
        cancelInstall(err);
    }
} else {
    cancelInstall(-210)
}
