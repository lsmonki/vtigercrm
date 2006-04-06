<?php
/*********************************************************************************
 * The contents of this file are subject to the SugarCRM Public License Version 1.1.2
 * ("License"); You may not use this file except in compliance with the
 * License. You may obtain a copy of the License at http://www.sugarcrm.com/SPL
 * Software distributed under the License is distributed on an  "AS IS"  basis,
 * WITHOUT WARRANTY OF ANY KIND, either express or implied. See the License for
 * the specific language governing rights and limitations under the License.
 * The Original Code is:  SugarCRM Open Source
 * The Initial Developer of the Original Code is SugarCRM, Inc.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.;
 * All Rights Reserved.
 * Contributor(s): ______________________________________.
 ********************************************************************************/
/*********************************************************************************
 * $Header$
 * Description:  Includes generic helper functions used throughout the application.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

  require_once('include/database/PearDatabase.php');
  require_once('include/ComboUtil.php'); //new
  require_once('include/utils/utils.php'); //new

/**
 * Check if user id belongs to a system admin.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 */
function is_admin($user) {
	if ($user->is_admin == 'on') return true;
	else return false;
}

/**
 * THIS FUNCTION IS DEPRECATED AND SHOULD NOT BE USED; USE get_select_options_with_id()
 * Create HTML to display select options in a dropdown list.  To be used inside
 * of a select statement in a form.
 * param $option_list - the array of strings to that contains the option list
 * param $selected - the string which contains the default value
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 */
function get_select_options (&$option_list, $selected, $advsearch='false') {
	return get_select_options_with_id($option_list, $selected, $advsearch);
}

/**
 * Create HTML to display select options in a dropdown list.  To be used inside
 * of a select statement in a form.   This method expects the option list to have keys and values.  The keys are the ids.  The values is an array of the datas 
 * param $option_list - the array of strings to that contains the option list
 * param $selected - the string which contains the default value
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 */
function get_select_options_with_id (&$option_list, $selected_key, $advsearch='false') {
	return get_select_options_with_id_separate_key($option_list, $option_list, $selected_key, $advsearch);
}

/**
 * Create HTML to display select options in a dropdown list.  To be used inside
 * of a select statement in a form.   This method expects the option list to have keys and values.  The keys are the ids.
 * The values are the display strings.
 */
function get_select_options_array (&$option_list, $selected_key, $advsearch='false') {
        return get_options_array_seperate_key($option_list, $option_list, $selected_key, $advsearch);
}

/**
 * Create HTML to display select options in a dropdown list.  To be used inside
 * of a select statement in a form.   This method expects the option list to have keys and values.  The keys are the ids.  The value is an array of data
 * param $label_list - the array of strings to that contains the option list
 * param $key_list - the array of strings to that contains the values list
 * param $selected - the string which contains the default value
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 */
function get_options_array_seperate_key (&$label_list, &$key_list, $selected_key, $advsearch='false') {
	global $app_strings;
	if($advsearch=='true')
	$select_options = "\n<OPTION value=''>--NA--</OPTION>";
	else
	$select_options = "";

	//for setting null selection values to human readable --None--
	$pattern = "/'0?'></";
	$replacement = "''>".$app_strings['LBL_NONE']."<";
	if (!is_array($selected_key)) $selected_key = array($selected_key);

	//create the type dropdown domain and set the selected value if $opp value already exists
	foreach ($key_list as $option_key=>$option_value) {
		$selected_string = '';
		// the system is evaluating $selected_key == 0 || '' to true.  Be very careful when changing this.  Test all cases.
		// The reported bug was only happening with one of the users in the drop down.  It was being replaced by none.
		if (($option_key != '' && $selected_key == $option_key) || ($selected_key == '' && $option_key == '') || (in_array($option_key, $selected_key)))
		{
			$selected_string = 'selected ';
		}

		$html_value = $option_key;

		$select_options .= "\n<OPTION ".$selected_string."value='$html_value'>$label_list[$option_key]</OPTION>";
		$options[$html_value]=array($label_list[$option_key]=>$selected_string);
	}
	$select_options = preg_replace($pattern, $replacement, $select_options);

	return $options;
}

/**
 * Create HTML to display select options in a dropdown list.  To be used inside
 * of a select statement in a form.   This method expects the option list to have keys and values.  The keys are the ids.
 * The values are the display strings.
 */

function get_select_options_with_id_separate_key(&$label_list, &$key_list, $selected_key, $advsearch='false')
{
    global $app_strings;
    if($advsearch=='true')
    $select_options = "\n<OPTION value=''>--NA--</OPTION>";
    else
    $select_options = "";

    $pattern = "/'0?'></";
    $replacement = "''>".$app_strings['LBL_NONE']."<";
    if (!is_array($selected_key)) $selected_key = array($selected_key);

    foreach ($key_list as $option_key=>$option_value) {
        $selected_string = '';
        if (($option_key != '' && $selected_key == $option_key) || ($selected_key == '' && $option_key == '') || (in_array($option_key, $selected_key)))
        {
            $selected_string = 'selected ';
        }

        $html_value = $option_key;

        $select_options .= "\n<OPTION ".$selected_string."value='$html_value'>$label_list[$option_key]</OPTION>";
    }
    $select_options = preg_replace($pattern, $replacement, $select_options);
    return $select_options;

}

/**
 * Converts localized date format string to jscalendar format
 * Example: $array = array_csort($array,'town','age',SORT_DESC,'name');
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 */
function parse_calendardate($local_format) {
	global $current_user;
	if($current_user->date_format == 'dd-mm-yyyy')
	{
		$dt_popup_fmt = "%d-%m-%Y";
	}
	elseif($current_user->date_format == 'mm-dd-yyyy')
	{
		$dt_popup_fmt = "%m-%d-%Y";
	}
	elseif($current_user->date_format == 'yyyy-mm-dd')
	{
		$dt_popup_fmt = "%Y-%m-%d";
	}
	return $dt_popup_fmt;
	//return "%Y-%m-%d";
}

/**
 * Decodes the given set of special character 
 * input values $string - string to be converted, $encode - flag to decode
 * returns the decoded value in string fromat
 */

function from_html($string, $encode=true){
        global $toHtml;
        //if($encode && is_string($string))$string = html_entity_decode($string, ENT_QUOTES);
        if($encode && is_string($string)){
                $string = str_replace(array_values($toHtml), array_keys($toHtml), $string);
        }
        return $string;
}

/** To get the Currency of the specified user
  * @param $id -- The user Id:: Type integer
  * @returns  currencyid :: Type integer
 */
function fetchCurrency($id)
{
        global $adb;
        $sql = "select currency_id from users where id=" .$id;
        $result = $adb->query($sql);
        $currencyid=  $adb->query_result($result,0,"currency_id");
        return $currencyid;
}

/** Function to get the Currency name from the currency_info
  * @param $currencyid -- currencyid:: Type integer
  * @returns $currencyname -- Currency Name:: Type varchar
  *
 */
function getCurrencyName($currencyid)
{
        global $adb;
        $sql1 = "select * from currency_info where id=".$currencyid;
        $result = $adb->query($sql1);
        $currencyname = $adb->query_result($result,0,"currency_name");
        $curr_symbol = $adb->query_result($result,0,"currency_symbol");
        return $currencyname.' : '.$curr_symbol;
}


/**
 * Function to fetch the list of groups from group table 
 * Takes no value as input 
 * returns the query result set object
 */

function get_group_options()
{
	global $adb,$noof_group_rows;;
	$sql = "select groupname from groups";
	$result = $adb->query($sql);
	$noof_group_rows=$adb->num_rows($result);
	return $result;
}

/**
 * Function to get the tabid 
 * Takes the input as $module - module name
 * returns the tabid, integer type
 */

function getTabid($module)
{

 if (file_exists('tabdata.php') && (filesize('tabdata.php') != 0)) 
	{
		include('tabdata.php');
		$tabid= $tab_info_array[$module];
	}
	else
	{	

        global $log;
        $log->info("module  is ".$module);
        global $adb;
	$sql = "select tabid from tab where name='".$module."'";
	$result = $adb->query($sql);
	$tabid=  $adb->query_result($result,0,"tabid");
	}
	return $tabid;

}

/**
 * Function to get the tabid 
 * Takes the input as $module - module name
 * returns the tabid, integer type
 */

function getSalesEntityType($crmid)
{
	global $log;
	$log->info("in getSalesEntityType ".$crmid);
	global $adb;
	$sql = "select * from crmentity where crmid=".$crmid;
        $result = $adb->query($sql);
	$parent_module = $adb->query_result($result,0,"setype");
	return $parent_module;
}

/**
 * Function to get the AccountName when a account id is given 
 * Takes the input as $acount_id - account id
 * returns the account name in string format.
 */

function getAccountName($account_id)
{
global $log;
$log->info("in getAccountName ".$account_id);

	global $adb;
	if($account_id != '')
	{
		$sql = "select accountname from account where accountid=".$account_id;
        	$result = $adb->query($sql);
		$accountname = $adb->query_result($result,0,"accountname");
	}
	return $accountname;
}

/**
 * Function to get the ProductName when a product id is given 
 * Takes the input as $product_id - product id
 * returns the product name in string format.
 */

function getProductName($product_id)
{

global $log;
$log->info("in getproductname ".$product_id);

	global $adb;
	$sql = "select productname from products where productid=".$product_id;
        $result = $adb->query($sql);
	$productname = $adb->query_result($result,0,"productname");
	return $productname;
}

/**
 * Function to get the Potentail Name when a potential id is given 
 * Takes the input as $potential_id - potential id
 * returns the potential name in string format.
 */

function getPotentialName($potential_id)
{
	global $log;
	$log->info("in getPotentialName ".$potential_id);

	global $adb;
	$potentialname = '';
	if($potential_id != '')
	{
		$sql = "select potentialname from potential where potentialid=".$potential_id;
        	$result = $adb->query($sql);
		$potentialname = $adb->query_result($result,0,"potentialname");
	}
	return $potentialname;
}

/**
 * Function to get the Contact Name when a contact id is given 
 * Takes the input as $contact_id - contact id
 * returns the Contact Name in string format.
 */

function getContactName($contact_id)
{
global $log;
$log->info("in getContactName ".$contact_id);

        global $adb;
        $sql = "select * from contactdetails where contactid=".$contact_id;
        $result = $adb->query($sql);
        $firstname = $adb->query_result($result,0,"firstname");
        $lastname = $adb->query_result($result,0,"lastname");
        $contact_name = $lastname.' '.$firstname;
        return $contact_name;
}

/**
 * Function to get the Vendor Name when a vendor id is given 
 * Takes the input as $vendor_id - vendor id
 * returns the Vendor Name in string format.
 */

function getVendorName($vendor_id)
{
global $log;
$log->info("in getVendorName ".$vendor_id);
        global $adb;
        $sql = "select * from vendor where vendorid=".$vendor_id;
        $result = $adb->query($sql);
        $vendor_name = $adb->query_result($result,0,"vendorname");
        return $vendor_name;
}

/**
 * Function to get the Quote Name when a vendor id is given 
 * Takes the input as $quote_id - quote id
 * returns the Quote Name in string format.
 */

function getQuoteName($quote_id)
{
global $log;
$log->info("in getQuoteName ".$quote_id);
        global $adb;
        $sql = "select * from quotes where quoteid=".$quote_id;
        $result = $adb->query($sql);
        $quote_name = $adb->query_result($result,0,"subject");
        return $quote_name;
}

/**
 * Function to get the PriceBook Name when a pricebook id is given 
 * Takes the input as $pricebook_id - pricebook id
 * returns the PriceBook Name in string format.
 */

function getPriceBookName($pricebookid)
{
global $log;
$log->info("in getPriceBookName ".$pricebookid);
        global $adb;
        $sql = "select * from pricebook where pricebookid=".$pricebookid;
        $result = $adb->query($sql);
        $pricebook_name = $adb->query_result($result,0,"bookname");
        return $pricebook_name;
}

/** This Function returns the  Purchase Order Name.
  * The following is the input parameter for the function
  *  $po_id --> Purchase Order Id, Type:Integer
  */
function getPoName($po_id)
{

global $log;
        $log->info("in getPoName ".$po_id);

        global $adb;
        $sql = "select * from purchaseorder where purchaseorderid=".$po_id;
        $result = $adb->query($sql);
        $po_name = $adb->query_result($result,0,"subject");
        return $po_name;
}
/**
 * Function to get the Sales Order Name when a salesorder id is given 
 * Takes the input as $salesorder_id - salesorder id
 * returns the Salesorder Name in string format.
 */

function getSoName($so_id)
{
        global $log;
$log->info("in getSoName ".$so_id);
	global $adb;
        $sql = "select * from salesorder where salesorderid=".$so_id;
        $result = $adb->query($sql);
        $so_name = $adb->query_result($result,0,"subject");
        return $so_name;
}

/**
 * Function to get the Group Information for a given groupid  
 * Takes the input $id - group id and $module - module name
 * returns the group information in an array format.
 */

function getGroupName($id, $module)
{
	$group_info = Array();
        global $log;
        $log->info("in getGroupName, entityid is ".$id.'  module is    '.$module);
        global $adb;
        if($module == 'Leads')
        {
               $sql = "select leadgrouprelation.groupname,groups.groupid from leadgrouprelation inner join groups on groups.groupname=leadgrouprelation.groupname where leadgrouprelation.leadid=".$id;
        }
        elseif($module == 'Accounts')
        {
               $sql = "select accountgrouprelation.groupname,groups.groupid from accountgrouprelation inner join groups on groups.groupname=accountgrouprelation.groupname where accountgrouprelation.accountid=".$id;
        }
        elseif($module == 'Contacts')
        {
               $sql = "select contactgrouprelation.groupname,groups.groupid from contactgrouprelation inner join groups on groups.groupname=contactgrouprelation.groupname where contactgrouprelation.contactid=".$id;
        }
	elseif($module == 'Potentials')
        {
               $sql = "select potentialgrouprelation.groupname,groups.groupid from potentialgrouprelation inner join groups on groups.groupname=potentialgrouprelation.groupname where potentialgrouprelation.potentialid=".$id;
        }
	elseif($module == 'Quotes')
        {
               $sql = "select quotegrouprelation.groupname,groups.groupid from quotegrouprelation inner join groups on groups.groupname=quotegrouprelation.groupname where quotegrouprelation.quoteid=".$id;
        }
	elseif($module == 'SalesOrder')
        {
               $sql = "select sogrouprelation.groupname,groups.groupid from sogrouprelation inner join groups on groups.groupname=sogrouprelation.groupname where sogrouprelation.salesorderid=".$id;
        }
	elseif($module == 'Invoice')
        {
               $sql = "select invoicegrouprelation.groupname,groups.groupid from invoicegrouprelation inner join groups on groups.groupname=invoicegrouprelation.groupname where invoicegrouprelation.invoiceid=".$id;
        }
	elseif($module == 'PurchaseOrder')
        {
               $sql = "select pogrouprelation.groupname,groups.groupid from pogrouprelation inner join groups on groups.groupname=pogrouprelation.groupname where pogrouprelation.purchaseorderid=".$id;
        }
        elseif($module == 'HelpDesk')
        {
               $sql = "select ticketgrouprelation.groupname,groups.groupid from ticketgrouprelation inner join groups on groups.groupname=ticketgrouprelation.groupname where ticketgrouprelation.ticketid=".$id;
        }
        elseif($module == 'Activities' || $module == 'Emails' || $module == 'Events')
        {
               $sql = "select activitygrouprelation.groupname,groups.groupid from activitygrouprelation inner join groups on groups.groupname=activitygrouprelation.groupname where activitygrouprelation.activityid=".$id;
        }
	$result = $adb->query($sql);
        $group_info[] = $adb->query_result($result,0,"groupname");
        $group_info[] = $adb->query_result($result,0,"groupid");
        return $group_info;

}

/**
 * Get the username by giving the user id.   This method expects the user id
 * param $label_list - the array of strings to that contains the option list
 * param $key_list - the array of strings to that contains the values list
 * param $selected - the string which contains the default value
 */
     
function getUserName($userid)
{
global $log;
$log->info("in getUserName ".$userid);

	global $adb;
	if($userid != '')
	{
		$sql = "select user_name from users where id=".$userid;
		$result = $adb->query($sql);
		$user_name = $adb->query_result($result,0,"user_name");
	}
	return $user_name;	
}

/**
 * Creates and returns database query. To be used for search and other text links.   This method expects the module object.
 * param $focus - the module object contains the column fields
 */
   
function getURLstring($focus)
{
	$qry = "";
	foreach($focus->column_fields as $fldname=>$val)
	{
		if(isset($_REQUEST[$fldname]) && $_REQUEST[$fldname] != '')
		{
			if($qry == '')
			$qry = "&".$fldname."=".$_REQUEST[$fldname];
			else
			$qry .="&".$fldname."=".$_REQUEST[$fldname];
		}
	}
	if(isset($_REQUEST['current_user_only']) && $_REQUEST['current_user_only'] !='')
	{
		$qry .="&current_user_only=".$_REQUEST['current_user_only'];
	}
	if(isset($_REQUEST['advanced']) && $_REQUEST['advanced'] =='true')
	{
		$qry .="&advanced=true";
	}

	if($qry !='')
	{
		$qry .="&query=true";
	}
	return $qry;

}

/** This function returns the date in user specified format.
  * param $cur_date_val - the default date format
 */
    
function getDisplayDate($cur_date_val)
{
	global $current_user;
	$dat_fmt = $current_user->date_format;
	if($dat_fmt == '')
	{
		$dat_fmt = 'dd-mm-yyyy';
	}

		$date_value = explode(' ',$cur_date_val);
		list($y,$m,$d) = split('-',$date_value[0]);
		if($dat_fmt == 'dd-mm-yyyy')
		{
			$display_date = $d.'-'.$m.'-'.$y;
		}
		elseif($dat_fmt == 'mm-dd-yyyy')
		{

			$display_date = $m.'-'.$d.'-'.$y;
		}
		elseif($dat_fmt == 'yyyy-mm-dd')
		{

			$display_date = $y.'-'.$m.'-'.$d;
		}

		if($date_value[1] != '')
		{
			$display_date = $display_date.' '.$date_value[1];
		}
	return $display_date;
 			
}

/** This function returns the date in user specified format.
  * Takes no param, receives the date format from current user object
  */
    
function getNewDisplayDate()
{
	global $log;
        $log->info("in getNewDisplayDate ");

	global $current_user;
	$dat_fmt = $current_user->date_format;
	if($dat_fmt == '')
        {
                $dat_fmt = 'dd-mm-yyyy';
        }
	//echo $dat_fmt;
	//echo '<BR>';
	$display_date='';
	if($dat_fmt == 'dd-mm-yyyy')
	{
		$display_date = date('d-m-Y');
	}
	elseif($dat_fmt == 'mm-dd-yyyy')
	{
		$display_date = date('m-d-Y');
	}
	elseif($dat_fmt == 'yyyy-mm-dd')
	{
		$display_date = date('Y-m-d');
	}
		
	//echo $display_date;
	return $display_date;
}

/** This function returns the default currency information.
  * Takes no param, return type array.
    */
    
function getDisplayCurrency()
{
        global $adb;
        $curr_array = Array();
        $sql1 = "select * from currency_info where currency_status='Active'";
        $result = $adb->query($sql1);
        $num_rows=$adb->num_rows($result);
        for($i=0; $i<$num_rows;$i++)
        {
                $curr_id = $adb->query_result($result,$i,"id");
                $curr_name = $adb->query_result($result,$i,"currency_name");
                $curr_symbol = $adb->query_result($result,$i,"currency_symbol");
                $curr_array[$curr_id] = $curr_name.' : '.$curr_symbol;
        }
        return $curr_array;
}

/** This function returns the amount converted to dollar.
  * param $amount - amount to be converted.
    * param $crate - conversion rate.
      */
      
function convertToDollar($amount,$crate){
                return $amount / $crate;

        }

/** This function returns the amount converted from dollar.
  * param $amount - amount to be converted.
    * param $crate - conversion rate.
      */
function convertFromDollar($amount,$crate){
                return $amount * $crate;
        }

/** This function returns the conversion rate for a given currency.
  * param $id - currency id.
    * param $symbol - currency symbol.
      */
      
function getConversionRate($id,$symbol)
{
        global $adb;
        $sql1 = "select * from currency_info where id=".$id." and currency_symbol='".$symbol."'" ;
        $result = $adb->query($sql1);
        $rate = $adb->query_result($result,0,"conversion_rate");
        return $rate;
}

/** This function returns the conversion rate for a given currency.
  * param $id - currency id.
  * param $symbol - currency symbol.
  */
      
function getCurrencySymbol($id)
{
        global $adb;
        $sql1 = "select * from currency_info where id=".$id;
        $result = $adb->query($sql1);
        $curr_symbol = $adb->query_result($result,0,"currency_symbol");
        return $curr_symbol;
}

/** This function returns the terms and condition from the database.
  * Takes no param and the return type is text.
  */
	    
function getTermsandConditions()
{
        global $adb;
        $sql1 = "select * from inventory_tandc";
        $result = $adb->query($sql1);
        $tandc = $adb->query_result($result,0,"tandc");
        return $tandc;
}

/**
 * Create select options in a dropdown list.  To be used inside
  *  a reminder select statement in a activity form. 
   * param $start - start value
   * param $end - end value
   * param $fldname - field name 
   * param $selvalue - selected value 
   */
    
function getReminderSelectOption($start,$end,$fldname,$selvalue='')
{
	global $mod_strings;
	global $app_strings;
	
	$def_sel ="";
	$OPTION_FLD = "<SELECT name=".$fldname.">";
	for($i=$start;$i<=$end;$i++)
	{
		if($i==$selvalue)
		$def_sel = "SELECTED";
		$OPTION_FLD .= "<OPTION VALUE=".$i." ".$def_sel.">".$i."</OPTION>\n";
		$def_sel = "";
	}
	$OPTION_FLD .="</SELECT>";
	return $OPTION_FLD;
}

/** This function returns the List price of a given product in a given price book.
  * param $productid - product id.
  * param $pbid - pricebook id.
  */
  
function getListPrice($productid,$pbid)
{
	global $log;
        $log->info("in getListPrice productid ".$productid);

	global $adb;
	$query = "select listprice from pricebookproductrel where pricebookid=".$pbid." and productid=".$productid;
	$result = $adb->query($query);
	$lp = $adb->query_result($result,0,'listprice');
	return $lp;
}

/** This function returns a string with removed new line character, single quote, and back slash double quoute.
  * param $str - string to be converted.
  */
      
function br2nl($str) {
   $str = preg_replace("/(\r\n)/", " ", $str);
   $str = preg_replace("/'/", " ", $str);
   $str = preg_replace("/\"/", " ", $str);
   return $str;
}

/** This function returns a text, which escapes the html encode for link tag/ a href tag
*param $text - string/text
*/

function make_clickable($text)
{
   $text = preg_replace('#(script|about|applet|activex|chrome):#is', "\\1&#058;", $text);
   // pad it with a space so we can match things at the start of the 1st line.
   $ret = ' ' . $text;

   // matches an "xxxx://yyyy" URL at the start of a line, or after a space.
   // xxxx can only be alpha characters.
   // yyyy is anything up to the first space, newline, comma, double quote or <
   $ret = preg_replace("#(^|[\n ])([\w]+?://.*?[^ \"\n\r\t<]*)#is", "\\1<a href=\"\\2\" target=\"_blank\">\\2</a>", $ret);

   // matches a "www|ftp.xxxx.yyyy[/zzzz]" kinda lazy URL thing
   // Must contain at least 2 dots. xxxx contains either alphanum, or "-"
   // zzzz is optional.. will contain everything up to the first space, newline,
   // comma, double quote or <.
   $ret = preg_replace("#(^|[\n ])((www|ftp)\.[\w\-]+\.[\w\-.\~]+(?:/[^ \"\t\n\r<]*)?)#is", "\\1<a href=\"http://\\2\" target=\"_blank\">\\2</a>", $ret);

   // matches an email@domain type address at the start of a line, or after a space.
   // Note: Only the followed chars are valid; alphanums, "-", "_" and or ".".
   $ret = preg_replace("#(^|[\n ])([a-z0-9&\-_.]+?)@([\w\-]+\.([\w\-\.]+\.)*[\w]+)#i", "\\1<a href=\"mailto:\\2@\\3\">\\2@\\3</a>", $ret);

   // Remove our padding..
   $ret = substr($ret, 1);

   //remove comma, fullstop at the end of url
   $ret = preg_replace("#,\"|\.\"|\)\"|\)\.\"|\.\)\"#", "\"", $ret);

   return($ret);
}
/**
 * This function returns the blocks and its related information for given module.
 * Input Parameter are $module - module name, $disp_view = display view (edit,detail or create),$mode - edit, $col_fields - * column fields/
 * This function returns an array
 */

function getBlocks($module,$disp_view,$mode,$col_fields='',$info_type='')
{
        global $adb;
        global $mod_strings;
        global $log;
        $tabid = getTabid($module);
        $block_detail = Array();
        $getBlockinfo = "";
        $query="select blockid,blocklabel,show_title from blocks where tabid=$tabid and $disp_view=0 and visible = 0 order by sequence";

        $result = $adb->query($query);
        $noofrows = $adb->num_rows($result);
        $prev_header = "";
        for($i=0; $i<$noofrows; $i++)
        {
		$block_title = $mod_strings[$adb->query_result($result,$i,"blocklabel")];
		$block_label = $adb->query_result($result,$i,"blocklabel");
                if($block_title !='')
                {
                        $prev_header = $block_title;

                        if($disp_view == "detail_view")
                        {
                                if($block_label=='LBL_RELATED_PRODUCTS')
                                {
					$getBlockInfo=getProductDetailsBlockInfo($mode,$module);
                                }
                                else
				 {
                                        $getBlockInfo=getDetailBlockInformation($module,$adb->query_result($result,$i,"blockid"),$col_fields,$tabid);
                                }
                        }
                        else
			{
				if($block_label=='LBL_RELATED_PRODUCTS')
                                {
					$getBlockInfo=getProductDetailsBlockInfo($mode,$module);
                                }
				else
				{
                                	$getBlockInfo=getBlockInformation($module,$adb->query_result($result,$i,"blockid"),$mode,$col_fields,$tabid,$info_type);
				}
                        }

                        if(is_array($getBlockInfo))
                        {
                                $block_detail[$block_title] = $getBlockInfo;
                        }
                }
                else
                {
                        if($disp_view == "detail_view")
                        {
                                $k=sizeof($block_detail[$prev_header]);
                                $temp_headerless_arr=getDetailBlockInformation($module,$adb->query_result($result,$i,"blockid"),$col_fields,$tabid);
                                foreach($temp_headerless_arr as $td_val=>$tr_val)
                                {
                                        $block_detail[$prev_header][$k]=$tr_val;
                                        $k++;
                                }

                        }
                        else
                        {
                                $k=sizeof($block_detail[$prev_header]);
                                $temp_headerless_arr=getBlockInformation($module,$adb->query_result($result,$i,"blockid"),$mode,$col_fields,$tabid,$info_type);
                                foreach($temp_headerless_arr as $td_val=>$tr_val)
				{
                                        $block_detail[$prev_header][$k]=$tr_val;
                                        $k++;
                                }



                        }

                }

        }
        return $block_detail;
}

/**
 * This function is used to get the display type.
 * Takes the input parameter as $mode - edit  (mostly)
 * This returns string type value
 */

function getView($mode)
{
        if($mode=="edit")
        $disp_view = "edit_view";
        else
        $disp_view = "create_view";
        return $disp_view;
}
/**
 * This function is used to get the blockid of the customblock for a given module.
 * Takes the input parameter as $tabid - module tabid and $label - custom label
 * This returns string type value
 */

function getBlockId($tabid,$label)
{
        global $adb;
        $blockid = '';
        $query = "select blockid from blocks where tabid=$tabid and blocklabel = '$label'";
        $result = $adb->query($query);
        $noofrows = $adb->num_rows($result);
        if($noofrows == 1)
        {
                $blockid = $adb->query_result($result,0,"blockid");
        }
        return $blockid;
}

/**
 * This function is used to get the Parent and Child tab relation array.
 * Takes no parameter and get the data from parent_tabdata.php and tabdata.php
 * This returns array type value
 */

function getHeaderArray()
{
	global $adb;
	global $current_user;
	require('user_privileges/user_privileges_'.$current_user->id.'.php');
	include('parent_tabdata.php');
	include('tabdata.php');
	$noofrows = count($parent_tab_info_array);
	foreach($parent_tab_info_array as $parid=>$parval)
	{
		$subtabs = Array();
		$tablist=$parent_child_tab_rel_array[$parid];
		$noofsubtabs = count($tablist);

		foreach($tablist as $childTabId)
		{
			$module = array_search($childTabId,$tab_info_array);
			
			if($is_admin)
			{
				$subtabs[] = $module;
			}	
			elseif($profileGlobalPermission[2]==0 ||$profileGlobalPermission[1]==0 || $profileTabsPermission[$childTabId]==0) 
			{
				$subtabs[] = $module;
			}	
		}

		$parenttab = getParentTabName($parid);

		if($parenttab == 'Settings' && $is_admin)
		{
			$subtabs[] = 'Settings';
		}
		if($parenttab != 'Settings' ||($parenttab == 'Settings' && $is_admin))
		{
			if(!empty($subtabs))
				$relatedtabs[$parenttab] = $subtabs;
		}
	}
	return $relatedtabs;
}

/**
 * This function is used to get the Parent Tab name for a given parent tab id.
 * Takes the input parameter as $parenttabid - Parent tab id
 * This returns value string type 
 */

function getParentTabName($parenttabid)
{
	global $adb;
	if (file_exists('parent_tabdata.php') && (filesize('parent_tabdata.php') != 0))
	{
		include('parent_tabdata.php');
		$parent_tabname= $parent_tab_info_array[$parenttabid];
	}
	else
	{
		$sql = "select parenttab_label from parenttab where parenttabid=".$parenttabid;
		$result = $adb->query($sql);
		$parent_tabname=  $adb->query_result($result,0,"parenttab_label");
	}
	return $parent_tabname;
}

/**
 * This function is used to get the Parent Tab name for a given module.
 * Takes the input parameter as $module - module name
 * This returns value string type 
 */


function getParentTabFromModule($module)
{
	global $adb;
	if (file_exists('tabdata.php') && (filesize('tabdata.php') != 0) && file_exists('parent_tabdata.php') && (filesize('parent_tabdata.php') != 0))
	{
		include('tabdata.php');
		include('parent_tabdata.php');
		$tabid=$tab_info_array[$module];
		foreach($parent_child_tab_rel_array as $parid=>$childArr)
		{
			if(in_array($tabid,$childArr))
			{
				$parent_tabname= $parent_tab_info_array[$parid];
			}
		}
		return $parent_tabname;
	}
	else
	{
		$sql = "select parenttab.* from parenttab inner join parenttabrel on parenttabrel.parenttabid=parenttab.parenttabid inner join tab on tab.tabid=parenttabrel.tabid where tab.name='".$module."'";
		$result = $adb->query($sql);
		$tab =  $adb->query_result($result,0,"parenttab_label");
		return $tab;
	}
}

/**
 * This function is used to get the Parent Tab name for a given module.
 * Takes no parameter but gets the parenttab value from form request
 * This returns value string type 
 */

function getParentTab()
{
    if(isset($_REQUEST['parenttab']) && $_REQUEST['parenttab'] !='')
    {
               return $_REQUEST['parenttab'];
    }
    else
    {
                return getParentTabFromModule($_REQUEST['module']);
    }

}
/**
 * This function is used to get the days in between the current time and the modified time of an entity .
 * Takes the input parameter as $id - crmid  it will calculate the number of days in between the
 * the current time and the modified time from the crmentity table and return the result as a string.
 * The return format is updated <No of Days> day ago <(date when updated)>
 */

function updateInfo($id)
{

    global $adb;
    global $app_strings;
    $query='select modifiedtime from crmentity where crmid ='.$id ;
    $result = $adb->query($query);
    $modifiedtime = $adb->query_result($result,0,'modifiedtime');
    $values=explode(' ',$modifiedtime);
    $date_info=explode('-',$values[0]);
    $time_info=explode(':',$values[1]);
    $date = $date_info[2].' '.date("M", mktime(0, 0, 0, $date_info[1], $date_info[2],$date_info[0])).' '.$date_info[0];
    $time_modified = mktime($time_info[0], $time_info[1], $time_info[2], $date_info[1], $date_info[2],$date_info[0]);
    $time_now = time();
    $days_diff = (int)(($time_now - $time_modified) / (60 * 60 * 24));
    if($days_diff == 0)
        $update_info = $app_strings['LBL_UPDATED_TODAY']." (".$date.")";
    elseif($days_diff == 1)
        $update_info = $app_strings['LBL_UPDATED']." ".$days_diff." ".$app_strings['LBL_DAY_AGO']." (".$date.")";
    else
        $update_info = $app_strings['LBL_UPDATED']." ".$days_diff." ".$app_strings['LBL_DAYS_AGO']." (".$date.")";

    return $update_info;
}


/**
 * This function is used to get the Product Images for the given Product  .
 * It accepts the product id as argument and returns the Images with the script for 
 * rotating the product Images
 */

function getProductImages($id)
{
    global $adb;
	$image_lists=array();
	$script_images=array();
	$script = '<script>var ProductImages = new Array(';
   	$i=0;
	$query='select imagename from products where productid='.$id;
	$result = $adb->query($query);
	$imagename=$adb->query_result($result,0,'imagename');
	$image_lists=explode('###',$imagename);
	for($i=0;$i<count($image_lists);$i++)
	{
		$script_images[] = '"'.$image_lists[$i].'"';
	}
	$script .=implode(',',$script_images).');</script>';
	if($imagename != '')
		return $script;
}	

/**
 * This function is used to save the Images .
 * It acceps the File lists,modulename,id and the mode as arguments  
 * It returns the array details of the upload
 */

function SaveImage($_FILES,$module,$id,$mode)
{
	global $adb;
	global $log;
	$uploaddir = $root_directory."test/".$module."/" ;//set this to which location you need to give the contact image
	$log->info("The Location to Save the Contact Image is ".$uploaddir);
	$file_path_name = $_FILES['imagename']['name'];
	$image_error="false";
	$saveimage="true";
	$file_name = basename($file_path_name);
	if($file_name!="")
	{

		$log->debug("Contact Image is given for uploading");
		$image_name_val=file_exist_fn($file_name,0);

		$encode_field_values="";
		$errormessage="";

		$move_upload_status=move_uploaded_file($_FILES["imagename"]["tmp_name"],$uploaddir.$image_name_val);
		$image_error="false";

		//if there is an error in the uploading of image

		$filetype= $_FILES['imagename']['type'];
		$filesize = $_FILES['imagename']['size'];

		$filetype_array=explode("/",$filetype);

		$file_type_val_image=strtolower($filetype_array[0]);
		$file_type_val=strtolower($filetype_array[1]);
		$log->info("The File type of the Contact Image is :: ".$file_type_val);
		//checking the uploaded image is if an image type or not
		if(!$move_upload_status) //if any error during file uploading
		{
			$log->debug("Error is present in uploading Contact Image.");
			$errorCode =  $_FILES['imagename']['error'];
			if($errorCode == 4)
			{
				$errorcode="no-image";
				$saveimage="false";
				$image_error="true";
			}
			else if($errorCode == 2)
			{
				$errormessage = 2;
				$saveimage="false";
				$image_error="true";
			}
			else if($errorCode == 3 )
			{
				$errormessage = 3;
				$saveimage="false";
				$image_error="true";
			}
		}
		else
		{
			$log->debug("Successfully uploaded the Contact Image.");
			if($filesize != 0)
			{
				if (($file_type_val == "jpeg" ) || ($file_type_val == "png") || ($file_type_val == "jpg" ) || ($file_type_val == "pjpeg" ) || ($file_type_val == "x-png") || ($file_type_val == "gif") ) //Checking whether the file is an image or not
				{
					$saveimage="true";
					$image_error="false";
				}
				else
				{
					$savelogo="false";
					$image_error="true";
					$errormessage = "image";
				}
			}
			else
			{       
				$savelogo="false";
				$image_error="true";
				$errormessage = "invalid";
			}

		}
	}
	else //if image is not given
	{
		$log->debug("Contact Image is not given for uploading.");
		if($mode=="edit" && $image_error=="false" )
		{
			if($module='contact')
			$image_name_val=getContactImageName($id);
			elseif($module='user')
			$image_name_val=getUserImageName($id);
			$saveimage="true";
		}
		else
		{
			$image_name_val="";
		}
	}
	$return_value=array('imagename'=>$image_name_val,
	'imageerror'=>$image_error,
	'errormessage'=>$errormessage,
	'saveimage'=>$saveimage,
	'mode'=>$mode);
	return $return_value;
}

 /**
 * This function is used to generate file name if more than one image with same name is added to a given Product.
 * Param $filename - product file name
 * Param $exist - number time the file name is repeated.
 */

function file_exist_fn($filename,$exist)
{
	global $uploaddir;

	if(!isset($exist))
	{
		$exist=0;
	}
	$filename_path=$uploaddir.$filename;
	if (file_exists($filename_path)) //Checking if the file name already exists in the directory
	{
		if($exist!=0)
		{
			$previous=$exist-1;
			$next=$exist+1;
			$explode_name=explode("_",$filename);
			$implode_array=array();
			for($j=0;$j<count($explode_name); $j++)
			{
				if($j!=0)
				{
					$implode_array[]=$explode_name[$j];
				}
			}
			$implode_name=implode("_", $implode_array);
			$test_name=$implode_name;
		}
		else
		{
			$implode_name=$filename;
		}
		$exist++;
		$filename_val=$exist."_".$implode_name;
		$testfilename = file_exist_fn($filename_val,$exist);
		if($testfilename!="")
		{
			return $testfilename;
		}
	}	
	else
	{
		return $filename;
	}
}

/**
 * This function is used get the User Count.
 * It returns the array which has the total users ,admin users,and the non admin users 
 */

function UserCount()
{
	global $adb;
	$result=$adb->query("select * from users where deleted =0;");
	$user_count=$adb->num_rows($result);
	$result=$adb->query("select * from users where deleted =0 AND is_admin != 'on';");
	$nonadmin_count = $adb->num_rows($result);
	$admin_count = $user_count-$nonadmin_count;
	$count=array('user'=>$user_count,'admin'=>$admin_count,'nonadmin'=>$nonadmin_count);
	return $count;
}

/**
 * This function is used to create folders recursively.
 * Param $dir - directory name
 * Param $mode - directory access mode
 * Param $recursive - create directory recursive, default true
 */

function mkdirs($dir, $mode = 0777, $recursive = true)
{
	if( is_null($dir) || $dir === "" ){
		return FALSE;
	}
	if( is_dir($dir) || $dir === "/" ){
		return TRUE;
	}
	if( mkdirs(dirname($dir), $mode, $recursive) ){
		return mkdir($dir, $mode);
	}
	return FALSE;
}

/**This function returns the module name which has been set as default home view for a given user.
 * Takes no parameter, but uses the user object $current_user.
 */
function DefHomeView()
{
		global $adb;
		global $current_user;
		$query="select defhomeview from users where id = ".$current_user->id;
		$result=$adb->query($query);
		$defaultview=$adb->query_result($result,0,'defhomeview');
		return $defaultview;

}


/**
 * This function is used to set the Object values from the REQUEST values.
 * @param  object reference $focus - reference of the object
 */
function setObjectValuesFromRequest($focus)
{
	if(isset($_REQUEST['record']))
	{
		$focus->id = $_REQUEST['record'];
	}
	if(isset($_REQUEST['mode']))
	{
		$focus->mode = $_REQUEST['mode'];
	}
	foreach($focus->column_fields as $fieldname => $val)
	{
		if(isset($_REQUEST[$fieldname]))
		{
			$value = $_REQUEST[$fieldname];
			$focus->column_fields[$fieldname] = $value;
		}
	}
}

 /**
 * Function to write the tabid and name to a flat file tabdata.txt so that the data
 * is obtained from the file instead of repeated queries
 * returns null
 */

function create_tab_data_file()
{
        global $log;
        $log->info("creating tabdata file");
        global $adb;
        $sql = "select * from tab";
        $result = $adb->query($sql);
        $num_rows=$adb->num_rows($result);
        $result_array=Array();
	$seq_array=Array();
        for($i=0;$i<$num_rows;$i++)
        {
                $tabid=$adb->query_result($result,$i,'tabid');
                $tabname=$adb->query_result($result,$i,'name');
		$presence=$adb->query_result($result,$i,'presence');
                $result_array[$tabname]=$tabid;
		$seq_array[$tabid]=$presence;

        }

        $filename = 'tabdata.php';
	
	
if (file_exists($filename)) {

        if (is_writable($filename))
        {

                if (!$handle = fopen($filename, 'w+')) {
                        echo "Cannot open file ($filename)";
                        exit;
                }
	require_once('modules/Users/CreateUserPrivilegeFile.php');
                $newbuf='';
                $newbuf .="<?php\n\n";
                $newbuf .="\n";
                $newbuf .= "//This file contains the commonly used variables \n";
                $newbuf .= "\n";
                $newbuf .= "\$tab_info_array=".constructArray($result_array).";\n";
                $newbuf .= "\n";
                $newbuf .= "\$tab_seq_array=".constructArray($seq_array).";\n";
                $newbuf .= "?>";
                fputs($handle, $newbuf);
                fclose($handle);

        }
        else
        {
                echo "The file $filename is not writable";
        }

                          }
			else
			{
		echo "The file $filename does not exist";
		return;
			}
}


 /**
 * Function to write the parenttabid and name to a flat file parent_tabdata.txt so that the data
 * is obtained from the file instead of repeated queries
 * returns null
 */

function create_parenttab_data_file()
{
	global $log;
	$log->info("creating parent_tabdata file");
	global $adb;
	$sql = "select parenttabid,parenttab_label from parenttab order by sequence";
	$result = $adb->query($sql);
	$num_rows=$adb->num_rows($result);
	$result_array=Array();
	for($i=0;$i<$num_rows;$i++)
	{
		$parenttabid=$adb->query_result($result,$i,'parenttabid');
		$parenttab_label=$adb->query_result($result,$i,'parenttab_label');
		$result_array[$parenttabid]=$parenttab_label;

	}

	$filename = 'parent_tabdata.php';


	if (file_exists($filename)) {

		if (is_writable($filename))
		{

			if (!$handle = fopen($filename, 'w+'))
			{
				echo "Cannot open file ($filename)";
				exit;
			}
			require_once('modules/Users/CreateUserPrivilegeFile.php');
			$newbuf='';
			$newbuf .="<?php\n\n";
			$newbuf .="\n";
			$newbuf .= "//This file contains the commonly used variables \n";
			$newbuf .= "\n";
			$newbuf .= "\$parent_tab_info_array=".constructSingleStringValueArray($result_array).";\n";
			$newbuf .="\n";
			

			$parChildTabRelArray=Array();

			foreach($result_array as $parid=>$parvalue)
			{
				$childArray=Array();
				$sql = "select * from parenttabrel where parenttabid=".$parid." order by sequence";
				$result = $adb->query($sql);
				$num_rows=$adb->num_rows($result);
				$result_array=Array();
				for($i=0;$i<$num_rows;$i++)
				{
					$tabid=$adb->query_result($result,$i,'tabid');
					$childArray[]=$tabid;
				}
				$parChildTabRelArray[$parid]=$childArray;

			}
			$newbuf .= "\n";
			$newbuf .= "\$parent_child_tab_rel_array=".constructTwoDimensionalValueArray($parChildTabRelArray).";\n";
			$newbuf .="\n";
			 $newbuf .="\n";
                        $newbuf .="\n";
                        $newbuf .= "?>";
                        fputs($handle, $newbuf);
                        fclose($handle);

		}
		else
		{
			echo "The file $filename is not writable";
		}

	}
	else
	{
		echo "The file $filename does not exist";
		return;
	}
}

/**
 * This function is used to get the File Storage Path in the server.
 * @param int $attachmentid - file attachment id ie., crmid of the attachment
 * @param string $filename  - file name
 * return string $filepath  - filepath inwhere the file stored in the server will be return
*/
function getFilePath($attachmentid,$filename)
{
	global $adb;
	global $root_directory;

	$query = 'select crmid, setype, smownerid, users.user_name from crmentity inner join users on crmentity.smownerid=users.id where crmid='.$attachmentid;
	$res = $adb->query($query);

	$user_name = $adb->query_result($res,0,'user_name');

	if(is_file($root_directory.'storage/user_'.$user_name.'/attachments/'.$filename))
		$filepath = $root_directory.'storage/user_'.$user_name.'/attachments/';
	else
		$filepath = $root_directory.'test/upload/';

	return $filepath;
}

/**
 * This function is used to get the Quick create form field parameters for a given module.
 * Param $module - module name 
 * returns the value in array format
 */


function QuickCreate($module)
{
    global $adb;
    global $mod_strings;

$tabid = getTabid($module);
$category = getParentTab();
$quickcreate_query = "select * from field where quickcreate=0 and tabid = ".$tabid." order by quickcreatesequence";
$result = $adb->query($quickcreate_query);
$noofrows = $adb->num_rows($result);
$fieldName_array = Array();
for($i=0; $i<$noofrows; $i++)
{
      $fieldtablename = $adb->query_result($result,$i,'tablename');
      $uitype = $adb->query_result($result,$i,"uitype");
      $fieldname = $adb->query_result($result,$i,"fieldname");
      $fieldlabel = $adb->query_result($result,$i,"fieldlabel");
      $maxlength = $adb->query_result($result,$i,"maximumlength");
      $generatedtype = $adb->query_result($result,$i,"generatedtype");
      $typeofdata = $adb->query_result($result,$i,"typeofdata");

      //to get validationdata
      $fldLabel_array = Array();
      $fldLabel_array[$fieldlabel] = $typeofdata;
      $fieldName_array[$fieldname] = $fldLabel_array;
      $custfld = getOutputHtml($uitype, $fieldname, $fieldlabel, $maxlength, $col_fields,$generatedtype,$module);
      $qcreate_arr[]=$custfld;
}
for ($i=0,$j=0;$i<count($qcreate_arr);$i=$i+2,$j++)
{
       $key1=$qcreate_arr[$i];
       if(is_array($qcreate_arr[$i+1]))
       {
               $key2=$qcreate_arr[$i+1];
       }
       else
       {
                $key2 =array();
       }
                $return_data[$j]=array(0 => $key1,1 => $key2);
}
	$form_data['form'] = $return_data;
	$form_data['data'] = $fieldName_array;
	return $form_data;
}


?>
