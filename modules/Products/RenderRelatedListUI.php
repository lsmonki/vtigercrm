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

require_once('include/RelatedListView.php');
require_once('modules/HelpDesk/HelpDesk.php');
require_once('modules/Activities/Activity.php');
require_once('modules/Users/UserInfoUtil.php');

function getHiddenValues($id)
{
        $hidden .= '<form border="0" action="index.php" method="post" name="form" id="form">';
        $hidden .= '<input type="hidden" name="module">';
        $hidden .= '<input type="hidden" name="mode">';
        $hidden .= '<input type="hidden" name="product_id" value="'.$id.'">';
        $hidden .= '<input type="hidden" name="return_module" value="Products">';
        $hidden .= '<input type="hidden" name="return_action" value="DetailView">';
        $hidden .= '<input type="hidden" name="return_id" value="'.$id.'">';
        $hidden .= '<input type="hidden" name="parent_id" value="'.$id.'">';
        $hidden .= '<input type="hidden" name="action">';	
	return $hidden;
}

function getPriceBookHiddenValues($id)
{
        $hidden .= '<form border="0" action="index.php" method="post" name="form" id="form">';
        $hidden .= '<input type="hidden" name="module">';
        $hidden .= '<input type="hidden" name="mode">';
        $hidden .= '<input type="hidden" name="pricebook_id" value="'.$id.'">';
        $hidden .= '<input type="hidden" name="return_module" value="Products">';
        $hidden .= '<input type="hidden" name="return_action" value="PriceBookDetailView">';
        $hidden .= '<input type="hidden" name="return_id" value="'.$id.'">';
        $hidden .= '<input type="hidden" name="parent_id" value="'.$id.'">';
        $hidden .= '<input type="hidden" name="action">';	
	return $hidden;
}

function renderRelatedTickets($query,$id)
{
	global $mod_strings;
        global $app_strings;

        $hidden = getHiddenValues($id);
        echo $hidden;
        $focus = new HelpDesk();

	$button = '';

        if(isPermitted("HelpDesk",1,"") == 'yes')
        {
		$button .= '<input title="New TICKET" accessyKey="F" class="button" onclick="this.form.action.value=\'EditView\';this.form.module.value=\'HelpDesk\';this.form.return_action.value=\'DetailView\';this.form.return_module.value=\'Products\'" type="submit" name="button" value="'.$mod_strings['LBL_NEW_TICKET'].'">&nbsp;';
	}
	$returnset = '&return_module=Products&return_action=DetailView&return_id='.$id;

	$list = GetRelatedList('Products','HelpDesk',$focus,$query,$button,$returnset);
	echo '</form>';
}


function renderRelatedActivities($query,$id)
{
	global $mod_strings;
        global $app_strings;

        $hidden = getHiddenValues($id);
	$hidden .= '<input type="hidden" name="activity_mode">';	
        echo $hidden;

        $focus = new Activity();

	$button = '';

        if(isPermitted("Activities",1,"") == 'yes')
        {
		$button .= '<input title="New Task" accessyKey="F" class="button" onclick="this.form.action.value=\'EditView\';this.form.return_action.value=\'DetailView\';this.form.module.value=\'Activities\';this.form.activity_mode.value=\'Task\';this.form.return_module.value=\'Products\'" type="submit" name="button" value="'.$app_strings['LBL_NEW_TASK'].'">&nbsp;';
		$button .= '<input title="New Event" accessyKey="F" class="button" onclick="this.form.action.value=\'EditView\';this.form.return_action.value=\'DetailView\';;this.form.activity_mode.value=\'Events\';this.form.module.value=\'Activities\';this.form.return_module.value=\'Products\'" type="submit" name="button" value="'.$app_strings['LBL_NEW_EVENT'].'">&nbsp;';
	}
	$returnset = '&return_module=Products&return_action=DetailView&return_id='.$id;

	$list = GetRelatedList('Products','Activities',$focus,$query,$button,$returnset);
	echo '</form>';
}

function renderRelatedAttachments($query,$id)
{
        $hidden = getHiddenValues($id);
        echo $hidden;

        getAttachmentsAndNotes('Products',$query,$id);

        echo '</form>';
}
function renderPriceBookRelatedProducts($query,$id)
{
	require_once('modules/Products/Product.php');
        global $mod_strings;
        global $app_strings;

        $hidden = getPriceBookHiddenValues($id);
        echo $hidden;

        $focus = new Product();
 
	$button = '';


 
		$button .= '<input title="Select Products" accessyKey="F" class="button" onclick="this.form.action.value=\'AddProductsToPriceBook\';this.form.module.value=\'Products\';this.form.return_module.value=\'Products\';this.form.return_action.value=\'PriceBookDetailView\'" type="submit" name="button" value="'.$app_strings['LBL_SELECT_PRODUCT_BUTTON_LABEL'].'">&nbsp;';
		//$button .= '<input title="Change" accessKey="" tabindex="2" type="button" class="button" value="'.$app_strings['LBL_SELECT_PRODUCT_BUTTON_LABEL'].'" name="Button" LANGUAGE=javascript onclick=\'return window.open("index.php?module=Products&action=Popup&return_module=Products&popuptype=detailview&form=EditView&form_submit=false&recordid='.$_REQUEST["record"].'","test","width=600,height=400,resizable=1,scrollbars=1");\'>&nbsp;';
	$returnset = '&return_module=Products&return_action=PriceBookDetailView&return_id='.$id;

	//$list = GetRelatedList('PriceBook','Products',$focus,$query,$button,$returnset,'updatePbListPrice','DeletePbProductRel');
	  $list = getPriceBookRelatedProducts($query,$focus);

		
	echo '</form>';
}

echo get_form_footer();


?>
