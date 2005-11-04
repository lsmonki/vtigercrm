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

require_once('XTemplate/xtpl.php');
global $mod_strings;
global $app_strings;
global $app_list_strings;

//echo get_module_title($_REQUEST["module"],$mod_strings['INV_TERMSANDCONDITIONS'],false);
//echo '<br><br>';

global $adb;
global $theme;
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
require_once($theme_path.'layout_utils.php');

        $xtpl=new XTemplate ('modules/Users/EditTermsandConditions.html');
        $xtpl->assign("MOD", $mod_strings);
        $xtpl->assign("APP", $app_strings);

        $sql="select * from inventory_tandc";
        $result = $adb->query($sql);
        $inventory_id = $adb->query_result($result,0,'id');
        $inventory_type = $adb->query_result($result,0,'type');
        $inventory_tandc = $adb->query_result($result,0,'tandc');

       $xtpl->assign("RETURN_MODULE","Users");
       $xtpl->assign("RETURN_ACTION","OrganizationTermsandConditions");


        if(isset($inventory_id))
               $xtpl->assign("INVENTORYID",$inventory_id);
        if (isset($inventory_type))
                $xtpl->assign("INVENTORYTYPE",$inventory_type);
        if (isset($inventory_tandc))
                $xtpl->assign("INV_TERMSANDCONDITIONS",$inventory_tandc);
        $xtpl->parse("main");
        $xtpl->out("main");
?>


