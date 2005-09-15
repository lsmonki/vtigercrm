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
require_once('modules/Users/UserInfoUtil.php');

function getHiddenValues($id)
{
	$hidden .= '<form border="0" action="index.php" method="post" name="form" id="form">';
	$hidden .= '<input type="hidden" name="module">';
	$hidden .= '<input type="hidden" name="mode">';
	$hidden .= '<input type="hidden" name="potential_id" value="'.$id.'">';
	$hidden .= '<input type="hidden" name="contact_id" value="'.$id.'">';
	$hidden .= '<input type="hidden" name="return_module" value="Potentials">';
	$hidden .= '<input type="hidden" name="return_action" value="DetailView">';
	$hidden .= '<input type="hidden" name="return_id" value="'.$id.'">';
	$hidden .= '<input type="hidden" name="parent_id" value="'.$id.'">';
	$hidden .= '<input type="hidden" name="action">';
	return $hidden;
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

		$button .= '<input title="New Task" accessyKey="F" class="button" onclick="this.form.action.value=\'EditView\';this.form.return_action.value=\'DetailView\';this.form.module.value=\'Activities\';this.form.activity_mode.value=\'Task\';this.form.return_module.value=\'Potentials\'" type="submit" name="button" value="'.$mod_strings['LBL_NEW_TASK'].'">&nbsp;';
		$button .= '<input title="New Event" accessyKey="F" class="button" onclick="this.form.action.value=\'EditView\';this.form.return_action.value=\'DetailView\';this.form.module.value=\'Activities\';this.form.return_module.value=\'Potentials\';this.form.activity_mode.value=\'Events\'" type="submit" name="button" value="'.$app_strings['LBL_NEW_EVENT'].'">&nbsp;';
	}
	$returnset = '&return_module=Potentials&return_action=DetailView&return_id='.$id;
	
	$list = GetRelatedList('Potentials','Activities',$focus,$query,$button,$returnset);
	echo '</form>';
}

function renderRelatedContacts($query,$id)
{
        global $mod_strings;
        global $app_strings;

        $hidden = getHiddenValues($id);
	echo $hidden;
	
	$focus = new Contact();
 
	$button = '';

        if(isPermitted("Contacts",3,"") == 'yes')
        {

 
		$button .= '<input title="Change" accessKey="" tabindex="2" type="button" class="button" value="'.$app_strings['LBL_SELECT_CONTACT_BUTTON_LABEL'].'" name="Button" LANGUAGE=javascript onclick=\'return window.open("index.php?module=Contacts&action=Popup&return_module=Potentials&popuptype=detailview&form=EditView&form_submit=false&recordid='.$_REQUEST["record"].'","test","width=600,height=400,resizable=1,scrollbars=1");\'>&nbsp;';
	}
	$returnset = '&return_module=Potentials&return_action=DetailView&return_id='.$id;

	$list = GetRelatedList('Potentials','Contacts',$focus,$query,$button,$returnset);
	echo '</form>';
}


function renderRelatedProducts($query,$id)
{
	require_once('modules/Products/Product.php');
        global $mod_strings;
        global $app_strings;

        $hidden = getHiddenValues($id);
        echo $hidden;

        $focus = new Product();
 
	$button = '';

        if(isPermitted("Products",1,"") == 'yes')
        {

 
		$button .= '<input title="New Product" accessyKey="F" class="button" onclick="this.form.action.value=\'EditView\';this.form.module.value=\'Products\';this.form.return_module.value=\'Potentials\';this.form.return_action.value=\'DetailView\'" type="submit" name="button" value="'.$app_strings['LBL_NEW_PRODUCT'].'">&nbsp;';
	}
	if(isPermitted("Products",3,"") == 'yes')
        {
		$button .= '<input title="Change" accessKey="" tabindex="2" type="button" class="button" value="'.$app_strings['LBL_SELECT_PRODUCT_BUTTON_LABEL'].'" name="Button" LANGUAGE=javascript onclick=\'return window.open("index.php?module=Products&action=Popup&return_module=Potentials&popuptype=detailview&form=EditView&form_submit=false&recordid='.$_REQUEST["record"].'","test","width=600,height=400,resizable=1,scrollbars=1");\'>&nbsp;';
	}
	$returnset = '&return_module=Potentials&return_action=DetailView&return_id='.$id;

	$list = GetRelatedList('Potentials','Products',$focus,$query,$button,$returnset);
	echo '</form>';
}

function renderRelatedAttachments($query,$id)
{
        $hidden = getHiddenValues($id);
        echo $hidden;

        getAttachmentsAndNotes('Potentials',$query,$id);

        echo '</form>';
}

function renderRelatedHistory($query,$id)
{
	getHistory('Potentials',$query,$id);
	echo '<br><br>';
}

function renderRelatedStageHistory($query,$id)
{
  
	global $theme;
	$theme_path="themes/".$theme."/";
	$image_path=$theme_path."images/";
	require_once ($theme_path."layout_utils.php");
  
	global $adb;
	global $mod_strings;
	global $app_strings;
 
	$result=$adb->query($query);
	$noofrows = $adb->num_rows($result);

	echo '<br>';
	echo get_form_header($app_strings['LBL_SALES_STAGE'].' '.$app_strings['LBL_HISTORY'],'', false);

	if($noofrows == 0)
	{
	        echo 'Sales Stage Never Changed';
	}
	else
	{	
		if ($noofrows > 15)
		{
			$list .= '<div style="overflow:auto;height:315px;width:100%;">';
		}

		$list .= '<table border="0" cellpadding="0" cellspacing="0" class="FormBorder" width="100%">';
		$list .= '<tr class="ModuleListTitle" height=20>';

		$list .= '<td WIDTH="1" class="blackLine"><IMG SRC="themes/'.$theme.'/images/blank.gif">';
		$list .= '<td class="moduleListTitle" height="21">';

		$list .= $app_strings['LBL_AMOUNT'].'</td>';
		$list .= '<td WIDTH="1" class="blackLine"><IMG SRC="themes/'.$theme.'/images/blank.gif">';
		$list .= '<td class="moduleListTitle">';

		$list .= $app_strings['LBL_SALES_STAGE'].'</td>';
		$list .= '<td WIDTH="1" class="blackLine"><IMG SRC="themes/'.$theme.'/images/blank.gif">';
		$list .= '<td class="moduleListTitle">';

		$list .= $app_strings['LBL_PROBABILITY'].'</td>';
		$list .= '<td WIDTH="1" class="blackLine"><IMG SRC="themes/'.$theme.'/images/blank.gif">';
		$list .= '<td class="moduleListTitle">';

                $list .= $app_strings['LBL_CLOSE_DATE'].'</td>';
                $list .= '<td WIDTH="1" class="blackLine"><IMG SRC="themes/'.$theme.'/images/blank.gif">';
                $list .= '<td class="moduleListTitle">';

		$list .= $app_strings['LBL_LAST_MODIFIED'].'</td>';
		$list .= '<td WIDTH="1" class="blackLine"><IMG SRC="themes/'.$theme.'/images/blank.gif">';
		$list .= '<td class="moduleListTitle">';

		$list .= '</td>';
		$list .= '</tr>';

		$list .= '<tr><td COLSPAN="12" class="blackLine"><IMG SRC="themes/'.$theme.'/images/blank.gif"></td></tr>';

		$i=1;
		while($row = $adb->fetch_array($result))
		{

			if ($i%2==0)
				$trowclass = 'evenListRow';
			else
				$trowclass = 'oddListRow';

			$list .= '<tr class="'. $trowclass.'">';

			$list .= '<td WIDTH="1" class="blackLine"><IMG SRC="themes/'.$theme.'/images/blank.gif">';
			$list .= '<td width="15%">'.$row['amount'].'</td>';

			$list .= '<td WIDTH="1" class="blackLine"><IMG SRC="themes/'.$theme.'/images/blank.gif">';
			$list .= '<td width="25%" height="21" style="padding:0px 3px 0px 3px;">';
			$list .= $row['stage'];
			$list .= '</td>';

			$list .= '<td WIDTH="1" class="blackLine"><IMG SRC="themes/'.$theme.'/images/blank.gif">';
			$list .= '<td width="15%" height="21" style="padding:0px 3px 0px 3px;">';
			$list .= $row['probability'];
			$list .= '</td>';

                        $list .= '<td WIDTH="1" class="blackLine"><IMG SRC="themes/'.$theme.'/images/blank.gif">';
                        $list .= '<td width="25%" height="21" style="padding:0px 3px 0px 3px;">';
                        $list .= $row['closedate'];
                        $list .= '</td>';

			$list .= '<td WIDTH="1" class="blackLine"><IMG SRC="themes/'.$theme.'/images/blank.gif">';
			$list .= '<td width="20%" height="21" style="padding:0px 3px 0px 3px;">';
			$list .= $row['lastmodified'];
			$list .= '</td>';

			$list .= '</td>';

			$list .= '</tr>';
			$i++;
		}

		$list .= '<tr><td COLSPAN="12" class="blackLine"><IMG SRC="themes/'.$theme.'/images/blank.gif"></td></tr>';
		$list .= '</table>';
		if ($noofrows > 15)
		{
			$list .= '</div>';
		}

		echo $list;
	}
	echo "<BR>\n";
}
function renderRelatedQuotes($query,$id,$acntid='')
{
	global $mod_strings;
	global $app_strings;
	require_once('modules/Quotes/Quote.php');

	$hidden = getHiddenValues($id);
	$hidden .='<input type="hidden" name="account_id" value="'.$acntid.'">';
	echo $hidden;
	if($acntid!='')
	$focus = new Quote();
	
	$button = '';
	if(isPermitted("Quotes",1,"") == 'yes')
        {
		$button .= '<input title="'.$app_strings['LBL_NEW_QUOTE_BUTTON_TITLE'].'" accessyKey="'.$app_strings['LBL_NEW_QUOTE_BUTTON_KEY'].'" class="button" onclick="this.form.action.value=\'EditView\';this.form.module.value=\'Quotes\'" type="submit" name="button" value="'.$app_strings['LBL_NEW_QUOTE_BUTTON'].'">&nbsp;</td>';
	}
	$returnset = '&return_module=Potentials&return_action=DetailView&return_id='.$id;

	$list = GetRelatedList('Potentials','Quotes',$focus,$query,$button,$returnset);
	echo '</form>';
}

function renderRelatedSalesOrders($query,$id,$acntid='')
{
	require_once('modules/Orders/SalesOrder.php');
        global $mod_strings;
        global $app_strings;

        $hidden = getHiddenValues($id);
	$hidden .='<input type="hidden" name="account_id" value="'.$acntid.'">';
        echo $hidden;

        $focus = new SalesOrder();
 
	$button = '';
	if(isPermitted("SalesOrder",1,"") == 'yes')
        {
		$button .= '<input title="'.$app_strings['LBL_NEW_SORDER_BUTTON_TITLE'].'" accessyKey="'.$app_strings['LBL_NEW_SORDER_BUTTON_KEY'].'" class="button" onclick="this.form.action.value=\'SalesOrderEditView\';this.form.module.value=\'Orders\'" type="submit" name="button" value="'.$app_strings['LBL_NEW_SORDER_BUTTON'].'">&nbsp;</td>';
	}

	$returnset = '&return_module=Potentials&return_action=DetailView&return_id='.$id;

	$list = GetRelatedList('Potentials','SalesOrder',$focus,$query,$button,$returnset);
	echo '</form>';
}


echo get_form_footer();


?>
