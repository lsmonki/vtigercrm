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

require_once('include/utils/UserInfoUtil.php');
require_once('include/RelatedListView.php');
require_once('modules/Products/Product.php');

function renderPriceBookRelatedProducts($query,$id)
{
        global $mod_strings;
        global $app_strings;

        $focus = new Product();
 
	$button = '';
 
	$button .= '<input title="Select Products" accessyKey="F" class="button" onclick="this.form.action.value=\'AddProductsToPriceBook\';this.form.module.value=\'Products\';this.form.return_module.value=\'PriceBooks\';this.form.return_action.value=\'DetailView\'" type="submit" name="button" value="'.$app_strings['LBL_SELECT_PRODUCT_BUTTON_LABEL'].'">&nbsp;';

	$returnset = '&return_module=PriceBooks&return_action=DetailView&return_id='.$id;

	return getPriceBookRelatedProducts($query,$focus,$returnset);

}

?>
