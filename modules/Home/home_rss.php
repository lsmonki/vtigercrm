<?php
require_once('include/database/PearDatabase.php');
require_once('config.php');
require_once('include/utils/utils.php');
global $current_user;
global $adb;
$db = new PearDatabase();
if (!empty($HTTP_SERVER_VARS['SERVER_SOFTWARE']) && strstr($HTTP_SERVER_VARS['SERVER_SOFTWARE'], 'Apache/2'))
{
	header ('Cache-Control: no-cache, pre-check=0, post-check=0, max-age=0');
}
else
{
	header ('Cache-Control: private, pre-check=0, post-check=0, max-age=0');
}

header ('Expires: ' . gmdate('D, d M Y H:i:s', time()) . ' GMT');
header ('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
header('Content-Type: text/xml');

echo ("<?xml version=\"1.0\" encoding=\"utf-8\" ?>\n");
echo ("  <rss version=\"2.0\">\n");
echo ("	<channel>\n");
echo ("	  <title>vtigerCRM Tickets</title>\n");
echo ("	  <link>".$site_URL."/index.php?module=Home&action=home_rss</link>\n");
echo ("	  <description>test</description>\n");
echo ("	  <managingEditor></managingEditor>\n");
echo ("	  <webMaster>".$current_user->user_name."</webMaster>\n");
echo ("	  <lastBuildDate>" . gmdate('D, d M Y H:i:s', time()) . " GMT</lastBuildDate>\n");
echo ("	  <generator>vtigerCRM</generator>\n");

//retrieving notifications******************************
//<<<<<<<<<<<<<<<< start of owner notify>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
$query = "select crmentity.setype,crmentity.crmid,crmentity.smcreatorid,crmentity.modifiedtime from crmentity inner join ownernotify on crmentity.crmid=ownernotify.crmid";

$result = $adb->query($query);
for($i=0;$i<$adb->num_rows($result);$i++)
{
	    $mod_notify[$i] = $adb->fetch_array($result);
		if($mod_notify[$i]['setype']=='Accounts')
		{
			$tempquery='select accountname from account where accountid='.$mod_notify[$i]['crmid'];
			$tempresult=$adb->query($tempquery);
			$account_name=$adb->fetch_array($tempresult);
			$notify_values[$i]=$account_name['accountname'];	
		}else if($mod_notify[$i]['setype']=='Potentials')
		{
			$tempquery='select potentialname from potential where potentialid='.$mod_notify[$i]['crmid'];
			$tempresult=$adb->query($tempquery);
			$potential_name=$adb->fetch_array($tempresult);
			$notify_values[$i]=$potential_name['potentialname'];
		}else if($mod_notify[$i]['setype']=='Contacts')
		{	
			$tempquery='select lastname from contactdetails where contactid='.$mod_notify[$i]['crmid'];
			$tempresult=$adb->query($tempquery);
			$contact_name=$adb->fetch_array($tempresult);
			$notify_values[$i]=$contact_name['lastname'];

		}else if($mod_notify[$i]['setype']=='Leads')
		{
			$tempquery='select lastname from leaddetails where leadid='.$mod_notify[$i]['crmid'];
			$tempresult=$adb->query($tempquery);
			$lead_name=$adb->fetch_array($tempresult);
			$notify_values[$i]=$lead_name['lastname'];
		}else if($mod_notify[$i]['setype']=='SalesOrder')
		{
			$tempquery='select subject from salesorder where salesorderid='.$mod_notify[$i]['crmid'];
			$tempresult=$adb->query($tempquery);
			$sales_subject=$adb->fetch_array($tempresult);
			$notify_values[$i]=$sales_subject['subject'];

		}else if($mod_notify[$i]['setype']=='Orders')
		{
			$tempquery='select subject from purchaseorder where purchaseorderid='.$mod_notify[$i]['crmid'];
			$tempresult=$adb->query($tempquery);
			$purchase_subject=$adb->fetch_array($tempresult);
			$notify_values[$i]=$purchase_subject['subject'];

		}else if($mod_notify[$i]['setype']=='Products')
		{
			$tempquery='select productname from products where productid='.$mod_notify[$i]['crmid'];
			$tempresult=$adb->query($tempquery);
			$product_name=$adb->fetch_array($tempresult);
			$notify_values[$i]=$product_name['productname'];
		}else if($mod_notify[$i]['setype']=='Emails')
		{
			$tempquery='select subject from activity where activityid='.$mod_notify[$i]['crmid'];
			$tempresult=$adb->query($tempquery);
			$email_subject=$adb->fetch_array($tempresult);
			$notify_values[$i]=$email_subject['subject'];

		}else if($mod_notify[$i]['setype']=='HelpDesk')
		{
			$tempquery='select title from troubletickets where ticketid='.$mod_notify[$i]['crmid'];
			$tempresult=$adb->query($tempquery);
			$HelpDesk_title=$adb->fetch_array($tempresult);
			$notify_values[$i]=$HelpDesk_title['title'];
		}else if($mod_notify[$i]['setype']=='Activities')
		{
			$tempquery='select subject from activity where activityid='.$mod_notify[$i]['crmid'];
			$tempresult=$adb->query($tempquery);
			$Activity_subject=$adb->fetch_array($tempresult);
			$notify_values[$i]=$Activity_subject['subject'];
		}else if($mod_notify[$i]['setype']=='Quotes')
		{
			$tempquery='select subject from quotes where quoteid='.$mod_notify[$i]['crmid'];
			$tempresult=$adb->query($tempquery);
			$quote_subject=$adb->fetch_array($tempresult);
			$notify_values[$i]=$quote_subject['subject'];
		}else if($mod_notify[$i]['setype']=='Invoice')
		{
			$tempquery='select subject from invoice where invoiceid='.$mod_notify[$i]['crmid'];
			$tempresult=$adb->query($tempquery);
			$invoice_subject=$adb->fetch_array($tempresult);
			$notify_values[$i]=$invoice_subject['subject'];
		}




//<<<<<<<<<<<<<<<< end of owner notify>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
  // Variable reassignment and reformatting for author
	$author_id = $db->query_result($result,$i,'smcreatorid');
	$entry_author = getUserName($author_id);
	$entry_author = htmlspecialchars ($entry_author);
	
	$entry_link = $site_URL."/index.php?modules=".$mod_notify[$i]['setype']."&amp;action=DetailView&amp;record=".$mod_notify[$i]['crmid'];
	$entry_link = htmlspecialchars($entry_link);
	$entry_time = $db->query_result($result,$i,'modifiedtime');

	echo ("	  <item>\n");
	echo ("	    <title>".$mod_notify[$i]['setype']."</title>\n");
	echo ("	    <link>".$entry_link."</link>\n");
	echo ("	    <description>".$notify_values[$i]."</description>\n");
	echo ("	    <author>".$entry_author."</author>\n");
	echo ("	    <pubDate>".$entry_time."</pubDate>\n");
	echo ("	  </item>\n");
}
echo ("	</channel>\n");
echo ("  </rss>\n");
?>
