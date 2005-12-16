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
 * $Header: /advent/projects/wesat/vtiger_crm/sugarcrm/themes/orange/footer.php,v 1.10 2005/03/02 20:08:13 jack Exp $
 * Description:  Contains a variety of utility functions used to display UI 
 * components such as form headers and footers.  Intended to be modified on a per 
 * theme basis.
 ********************************************************************************/
global $app_strings;
?>
<!--end body panes-->
</td></tr>
<tr><td colspan="2" align="center">
	<table CELLSPACING=3 border=0><tr>
      <td align=center noWrap colSpan=4>
	  <A href="index.php?module=Home&action=index"><?php echo $app_list_strings['moduleList']['Home']; ?></A> |
	  <A href="index.php?module=Dashboard&action=index"><?php echo $app_list_strings['moduleList']['Dashboard']; ?></A> |
	  <A href="index.php?module=Leads&action=index"><?php echo $app_list_strings['moduleList']['Leads']; ?></A> |
	  <A href="index.php?module=Contacts&action=index"><?php echo $app_list_strings['moduleList']['Contacts']; ?></A> |
	  <A href="index.php?module=Accounts&action=index"><?php echo $app_list_strings['moduleList']['Accounts']; ?></A> |
	  <A href="index.php?module=Potentials&action=index"><?php echo $app_list_strings['moduleList']['Potentials']; ?></A> |
	  <A href="index.php?module=Notes&action=index"><?php echo $app_list_strings['moduleList']['Notes']; ?></A> |
	  <A href="index.php?module=Emails&action=index"><?php echo $app_list_strings['moduleList']['Emails']; ?></A> |
	  <A href="index.php?module=Activities&action=index"><?php echo $app_list_strings['moduleList']['Activities']; ?></A> |
	  <A href="index.php?module=HelpDesk&action=index"><?php echo $app_list_strings['moduleList']['HelpDesk']; ?></A> |
          <A href="index.php?module=Products&action=index"><?php echo $app_list_strings['moduleList']['Products']; ?></A> |
          <A href="index.php?module=Calendar&action=index"><?php echo $app_list_strings['moduleList']['Calendar']; ?></A>	      <br>	
 	  <A href="index.php?module=Quotes&action=index"><?php echo $app_list_strings['moduleList']['Quotes']; ?></A> |
	  <A href="index.php?module=PurchaseOrder&action=index"><?php echo $app_list_strings['moduleList']['PurchaseOrder']; ?></A> |
          <A href="index.php?module=SalesOrder&action=index"><?php echo $app_list_strings['moduleList']['SalesOrder']; ?></A> |
          <A href="index.php?module=Invoice&action=index"><?php echo $app_list_strings['moduleList']['Invoice']; ?></A> |

          <A href="index.php?module=Rss&action=index"><?php echo $app_list_strings['moduleList']['Rss']; ?></A> |
          <A href="index.php?module=Reports&action=index"><?php echo $app_list_strings['moduleList']['Reports']; ?></A>

	  </td>
    </tr></table>
</td></tr></table>
</body>
</html>
