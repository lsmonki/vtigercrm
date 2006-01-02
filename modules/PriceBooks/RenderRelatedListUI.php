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


function getPriceBookHiddenValues($id)
{
        $hidden .= '<form border="0" action="index.php" method="post" name="form" id="form">';
        $hidden .= '<input type="hidden" name="module">';
        $hidden .= '<input type="hidden" name="mode">';
        $hidden .= '<input type="hidden" name="pricebook_id" value="'.$id.'">';
        $hidden .= '<input type="hidden" name="return_module" value="PriceBooks">';
        $hidden .= '<input type="hidden" name="return_action" value="DetailView">';
        $hidden .= '<input type="hidden" name="return_id" value="'.$id.'">';
        $hidden .= '<input type="hidden" name="parent_id" value="'.$id.'">';
        $hidden .= '<input type="hidden" name="action">';	
	return $hidden;
}

function renderPriceBookRelatedProducts($query,$id)
{
        global $mod_strings;
        global $app_strings;

        $hidden = getPriceBookHiddenValues($id);
        echo $hidden;

        $focus = new Product();
 
	$button = '';
 
	$button .= '<input title="Select Products" accessyKey="F" class="button" onclick="this.form.action.value=\'AddProductsToPriceBook\';this.form.module.value=\'Products\';this.form.return_module.value=\'PriceBooks\';this.form.return_action.value=\'DetailView\'" type="submit" name="button" value="'.$app_strings['LBL_SELECT_PRODUCT_BUTTON_LABEL'].'">&nbsp;';
	//$button .= '<input title="Change" accessKey="" tabindex="2" type="button" class="button" value="'.$app_strings['LBL_SELECT_PRODUCT_BUTTON_LABEL'].'" name="Button" LANGUAGE=javascript onclick=\'return window.open("index.php?module=Products&action=Popup&return_module=Products&popuptype=detailview&form=EditView&form_submit=false&recordid='.$_REQUEST["record"].'","test","width=600,height=400,resizable=1,scrollbars=1");\'>&nbsp;';
	$returnset = '&return_module=PriceBooks&return_action=DetailView&return_id='.$id;

	//$list = GetRelatedList('PriceBook','Products',$focus,$query,$button,$returnset,'updatePbListPrice','DeletePbProductRel');
	return getPriceBookRelatedProducts($query,$focus,$returnset);

	//echo '</form>';
}

?>
