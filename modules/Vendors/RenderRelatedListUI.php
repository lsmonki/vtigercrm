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
require_once('modules/Contacts/Contact.php');
require_once('modules/Products/Product.php');
require_once('modules/PurchaseOrder/PurchaseOrder.php');


function renderRelatedProducts($query,$id,$sid="product_id")
{
        global $mod_strings;
        global $app_strings;

        $focus = new Product();
 
	$button = '';

        if(isPermitted("Products",1,"") == 'yes')
        {
		$button .= '<input title="New Product" accessyKey="F" class="button" onclick="this.form.action.value=\'EditView\';this.form.module.value=\'Products\';this.form.return_module.value=\'Vendors\';this.form.return_action.value=\'DetailView\'" type="submit" name="button" value="'.$app_strings['LBL_NEW_PRODUCT'].'">&nbsp;';
	}
	if(isPermitted("Products",3,"") == 'yes')
        {
		if($focus->product_novendor() !=0)
		{
			$button .= '<input title="Change" accessKey="" tabindex="2" type="button" class="button" value="'.$app_strings['LBL_SELECT_PRODUCT_BUTTON_LABEL'].'" name="Button" LANGUAGE=javascript onclick=\'return window.open("index.php?module=Products&action=Popup&return_module=Vendors&popuptype=detailview&form=EditView&form_submit=false&recordid='.$_REQUEST["record"].'","test","width=600,height=400,resizable=1,scrollbars=1");\'>&nbsp;';
		}
	}
	$returnset = '&return_module=Vendors&return_action=DetailView&return_id='.$id;

	return GetRelatedList('Vendors','Products',$focus,$query,$button,$returnset);
}

function renderRelatedOrders($query,$id,$sid="product_id")
{
        global $mod_strings;
        global $app_strings;

        $focus = new Order();
 
	$button = '';

        if(isPermitted("PurchaseOrder",1,"") == 'yes')
        {
 
		$button .= '<input title="'.$app_strings['LBL_PORDER_BUTTON_TITLE'].'" accessyKey="O" class="button" onclick="this.form.action.value=\'EditView\';this.form.module.value=\'PurchaseOrder\';this.form.return_module.value=\'Vendors\';this.form.return_action.value=\'DetailView\'" type="submit" name="button" value="'.$app_strings['LBL_PORDER_BUTTON'].'">&nbsp;';
	}
	$returnset = '&return_module=Vendors&return_action=DetailView&return_id='.$id;

	return GetRelatedList('Vendors','PurchaseOrder',$focus,$query,$button,$returnset);
}

function renderRelatedContacts($query,$id)
{
        global $mod_strings;
        global $app_strings;

        $focus = new Contact();

        $button = '';
        if(isPermitted("Contacts",1,"") == 'yes')
        {
                $button .= '<input title="'.$app_strings['LBL_SELECT_CONTACT_BUTTON_TITLE'].'" accessKey="'.$app_strings['LBL_SELECT_CONTACT_BUTTON_KEY'].'" type="button" class="button" value="'.$app_strings['LBL_SELECT_CONTACT_BUTTON_LABEL'].'" name="Button" LANGUAGE=javascript onclick=\'return window.open("index.php?module=Contacts&action=Popup&return_module=Products&smodule=VENDOR&popuptype=detailview&form=EditView&form_submit=false&recordid='.$_REQUEST["record"].'","test","width=600,height=400,resizable=1,scrollbars=1");\'>&nbsp;';
        }
        $returnset = '&return_module=Vendors&return_action=DetailView&return_id='.$id;

        return GetRelatedList('Vendor','Contacts',$focus,$query,$button,$returnset);
}

?>
