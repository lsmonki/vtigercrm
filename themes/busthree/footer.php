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
 * $Header: /cvsroot/sugarcrm/sugarcrm/themes/busthree/footer.php,v 1.9 2004/08/04 17:48:28 sugarjacob Exp $
 * Description:  Contains a variety of utility functions used to display UI 
 * components such as form headers and footers.  Intended to be modified on a per 
 * theme basis.
 ********************************************************************************/
global $app_strings;
?>
<!--end body panes-->
</td></tr>
<tr><td colspan="2" align="center">
	<table CELLSPACING="3" border="0"><tr>
      <td align=center noWrap colSpan="4">
	  <A href="index.php?module=Home&action=index"><?php echo $app_list_strings['moduleList']['Home']; ?></A> | 
	  <A href="index.php?module=Contacts&action=index"><?php echo $app_list_strings['moduleList']['Contacts']; ?></A> | 
	  <A href="index.php?module=Accounts&action=index"><?php echo $app_list_strings['moduleList']['Accounts']; ?></A> | 
	  <A href="index.php?module=Opportunities&action=index"><?php echo $app_list_strings['moduleList']['Opportunities']; ?></A> | 
	  <A href="index.php?module=Cases&action=index"><?php echo $app_list_strings['moduleList']['Cases']; ?></A> 
	  <BR>  
	  <A href="index.php?module=Tasks&action=index"><?php echo $app_list_strings['moduleList']['Tasks']; ?></A> |  
	  <A href="index.php?module=Calls&action=index"><?php echo $app_list_strings['moduleList']['Calls']; ?></A> |   
	  <A href="index.php?module=Emails&action=index"><?php echo $app_list_strings['moduleList']['Emails']; ?></A> |  
	  <A href="index.php?module=Meetings&action=index"><?php echo $app_list_strings['moduleList']['Meetings']; ?></A>  
	  </td>
    </tr></table>
</td></tr></table>
</body>
</html>
