<?php
/*********************************************************************************
 * The contents of this file are subject to the SugarCRM Public License Version 1.1.2
 * ("License"); You may not use this file except in compliance with the
 * License. You may obtain a copy of the License at http://www.sugarcrm.com/SPL
 * Software distributed under the License is distributed on an  "AS IS"  basis,
 * WITHOUT WARRANTY OF ANY KIND, either express or implied. See the License for
 * the specific language governing rights and limitations under the License.
 * The Original Code is: SugarCRM Open Source
 * The Initial Developer of the Original Code is SugarCRM, Inc.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.;
 * All Rights Reserved.
 * Contributor(s): ______________________________________.
 ********************************************************************************/
/*********************************************************************************
 * $Header: /advent/projects/wesat/vtiger_crm/sugarcrm/install/0welcome.php,v 1.10 2004/08/26 11:44:30 sarajkumar Exp $
 * Description:  Executes a step in the installation process.
 ********************************************************************************/

//get php configuration settings.  requires elaborate parsing of phpinfo() output
ob_start();
phpinfo(INFO_GENERAL);
$string = ob_get_contents();
ob_end_clean();

$pieces = explode("<h2", $string);
$settings = array();
foreach($pieces as $val)
{
   preg_match("/<a name=\"module_([^<>]*)\">/", $val, $sub_key);
   preg_match_all("/<tr[^>]*>
									   <td[^>]*>(.*)<\/td>
									   <td[^>]*>(.*)<\/td>/Ux", $val, $sub);
   preg_match_all("/<tr[^>]*>
									   <td[^>]*>(.*)<\/td>
									   <td[^>]*>(.*)<\/td>
									   <td[^>]*>(.*)<\/td>/Ux", $val, $sub_ext);
   foreach($sub[0] as $key => $val) {
		if (preg_match("/Configuration File \(php.ini\) Path /", $val)) {
	   		$val = preg_replace("/Configuration File \(php.ini\) Path /", '', $val);
			$phpini = strip_tags($val);
	   	}
   }

}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<HTML>
<HEAD>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
<title>vtiger CRM 4.2 Installer: Step 1</title>
<link rel="stylesheet" href="install/install.css" type="text/css" />
</head>
<body leftMargin="0" topMargin="0" marginheight="0" marginwidth="0">
<table width="75%" border="0" cellpadding="3" cellspacing="0" align="center" style="border-bottom: 1px dotted #CCCCCC;"><tbody>
  <tr>
      <td align="left"><a href="http://www.vtiger.com" target="_blank" title="vtiger CRM"><IMG alt="vtiger CRM" border="0" src="include/images/vtiger_crmlogo.gif"/></a></td>
      <td align="right"><h2>Step 1 of 5</h2></td>
      <td align="right"><IMG alt="vtiger CRM" border="0" src="include/images/spacer.gif" width="10" height="1"/></td>
    </tr>
</tbody></table>
<table width="75%" align="center" border="0" cellpadding="10" cellspacing="0" border="0"><tbody>
    <tr>
      <td width="100%" colspan="3">
		<table width="100%" cellpadding="0" cellspacing="0" border="0"><tbody><tr>
			  <td>
			   <table cellpadding="0" cellspacing="0" border="0" width="100%"><tbody><tr>
				<td align="left"><h3>Registration</h3></td>
					</tr></tbody></table>
			  </td>
			  <td align="right">&nbsp;</td>
			  <td width="85%" align="right"><hr width="100%"></td>
			  </tr>
		</tbody></table>
	  </td>
    </tr>
	<tr><td><h4>Welcome to the vtiger CRM installation</h4><P>
  			This installer creates the vtiger CRM 4.2 database tables and sets the configuration variables that you need to start.
			The entire process should take about four minutes.

			<p>

 <font color=red> <b>Kindly note vtiger CRM 4.2 is tested on mysql 4.0.x and PHP 4.3.8 and Apache 2.0.40 . Support for PHP 5 will be provided in future releases </b> </font>

			
			<P>For installation help, please visit the vtiger CRM <A href="http://www.vtiger.com/forums/index.php?c=3" target="_blank">support forums</A>.</td>
	</tr>

	<tr>

		<td><hr></td>
	</tr>
	<tr>
		<td valign='top'><h4>vtiger CRM Registration</h4><br>
Please take a moment and register with vtiger CRM. Your name and email address are the only required fields for registration. All other fields are optional, but very helpful. We do not sell, rent, share, or otherwise distribute the information collected here to third parties.
<P>
Please see <a href="http://www.vtigercrm.com" target="_blank">http://www.vtigercrm.com</a> for information on additional functionality, support requests…
</td></tr>
	<tr>
       <td align="right">
	    <form action="install.php" method="post" name="form" id="form">
		<input type="hidden" name="file" value="1checkSystem.php" />
		<input class="button" type="submit" name="next" value="Next >" /> &nbsp; &nbsp; </td>
    </tr>
<tr><td align='center' colspan='3'>  <IFRAME src="http://www.vtiger.com/products/crm/registration.html" width="100%" height=325 scrolling='no' frameborder="0">
  [Your user agent does not support frames or is currently configured
  not to display frames. However, you may visit
  <A href="http://www.vtiger.com/products/crm/registration.html">the related document.</A>]
  </IFRAME>
</td></tr>
	</tbody>

</form>
</body>
</html>
