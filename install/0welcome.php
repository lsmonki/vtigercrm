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
 * $Header:  vtiger_crm/sugarcrm/install/0welcome.php,v 1.10 2004/08/26 11:44:30 sarajkumar Exp $
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
<title>vtiger CRM Open Source Installer: Step 1</title>
<link rel="stylesheet" href="install/install.css" type="text/css" />
</head>
<body leftMargin="0" topMargin="0" marginheight="0" marginwidth="0" class="">
<table width="100%" border="0" cellpadding="3" cellspacing="0"><tbody>
  <tr>
      <td align="center"><a href="http://www.vtiger.com" target="_blank" title="vtiger CRM"><IMG alt="vtiger CRM" border="0" src="include/images/vtiger.jpg"/></a></td>
    </tr>
</tbody></table>
<table align="center" border="0" cellpadding="2" cellspacing="1" border="1" width="70%"><tbody>
    <tr> 
      <td width="100%" colspan="3">
		<table width=100% cellpadding="0" cellspacing="0" border="0"><tbody><tr>
			  <td>
			   <table cellpadding="0" cellspacing="0" border="0"><tbody><tr>
				<td class="formHeader" vAlign="top" align="left" height="20"> 
				 <IMG height="5" src="include/images/left_arc.gif" width="5" border="0"></td>
				<td class="formHeader" vAlign="middle" align="left" noWrap width="100%" height="20">Registration</td>
				<td  class="formHeader" vAlign="top" align="right" height="20">
				  <IMG height="5" src="include/images/right_arc.gif" width="5" border="0"></td>
				</tr></tbody></table>
			  </td>
			  <td width="100%" align="right">&nbsp;</td>
			  </tr><tr>
			  <td colspan="2" width="100%" class="formHeader"><IMG width="100%" height="2" src="include/images/blank.gif"></td>
			  </tr>
		</tbody></table>
	  </td>
    </tr>
	<tr><td align="center" colspan="3"><span class="moduleTitle">Welcome to the vtiger CRM Open Source installation</span><P>
  			This installer creates the vtiger CRM database tables and sets the configuration variables that you need to start.   
			The entire process should take about ten minutes. 
			<P>For installation help, please visit the vtiger CRM <A href="http://www.vtiger.com/forums/index.php?c=3" target="_blank">support forums</A>.</td>
	</tr>

	<tr>
		<td width="100">&nbsp;</td>
		<td w><IMG class="formHeader" width="100%" height="1" src="include/images/blank.gif"></td>
		<td width="100">&nbsp;</td>
	</tr>
	<tr align="center">
		<td width="100">&nbsp;</td>
		<td><strong>NOTE:</strong> Official vtiger CRM Open Source distributions are ONLY available from the <A href="http://sourceforge.net/projects/vtigercrm/" target="_blank">vtiger CRM Open Source 
	project</A> located on SourceForge.net.  Please ensure you are installing an official distribution.</td>
		<td width="100">&nbsp;</td>
	</tr>
	<tr>
		<td width="100">&nbsp;</td>
		<td ><IMG class="formHeader" width="100%" height="1" src="include/images/blank.gif"></td>
		<td width="100">&nbsp;</td>
	</tr>
	<tr>
		<td valign='top' align="center" colspan='3'><span class="moduleTitle">vtiger CRM Open Source Registration</span><br>
Please take a moment and register with vtiger CRM. By letting us know a little bit about how your company plans to use vtiger CRM, we can ensure we are always delivering the right product for your business needs. 
<P>Your name and email address are the only required fields for registration. All other fields are optional, but very helpful. We do not sell, rent, share, or otherwise distribute the information collected here to third parties. 
<P>
Please see <a href="http://www.vtigercrm.com" target="_blank">http://www.vtigercrm.com</a> for information on additional functionality, support requests… 
</td></tr>
	<tr> 
       <td colspan="3" align="right">
	    <form action="install.php" method="post" name="form" id="form">
		<input type="hidden" name="file" value="1checkSystem.php" />
		<input class="button" type="submit" name="next" value="Next" /></td>
    </tr>
<tr><td align='center' colspan='3'>  <IFRAME src="http://www.vtiger.com/products/crm/registration.php?option=com_extended_registration&task=register&installer=true" width="100%" height=900
              scrolling='no' frameborder="0">
  [Your user agent does not support frames or is currently configured
  not to display frames. However, you may visit
  <A href="http://www.vtiger.com/products/crm/registration.php?option=com_extended_registration&task=register">the related document.</A>]
  </IFRAME>
</td></tr>
	</tbody> 	
		
</form>
</body>
</html>
