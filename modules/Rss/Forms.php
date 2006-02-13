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
 * $Header: /cvsroot/vtigercrm/vtiger_crm/modules/Rss/Forms.php,v 1.3 2005/06/29 17:20:13 venkatraj Exp $
 * Description:  Contains a variety of utility functions specific to this module.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

/**
 * Create javascript to validate the data entered into a record.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
*/ 
function get_validate_record_js () {
global $mod_strings;
global $app_strings;
global $image_path;

$the_script='
<script language="JavaScript" type="text/javascript" src="include/general.js"></script>
<script type="text/javascript" language="Javascript">
function toggleRSSFolder(id) {
	if (document.getElementById(id+"_feeds").style.display=="none") {
                document.getElementById(id+"_feeds").style.display="block"
                document.getElementById(id+"_folder").src="'.$image_path.'rss_folder_opn.gif"
                document.getElementById(id+"_toggle").src="'.$image_path.'minus.gif"
        } else {
                document.getElementById(id+"_feeds").style.display="none"
                document.getElementById(id+"_folder").src="'.$image_path.'rss_folder_cls.gif"
                document.getElementById(id+"_toggle").src="'.$image_path.'plus.gif"
        }

}
</script>';
return $the_script;
}


require_once("modules/Rss/Rss.php");

$oRss = new vtigerRSS();
$allrsshtml = $oRss->getAllRssFeeds();
function get_rssfeeds_form() {
global $mod_strings;
global $app_strings;
global $app_list_strings;
global $image_path;

$oRss = new vtigerRSS();
$allrsshtml = $oRss->getRSSCategoryHTML();
$starred_rss_html = $oRss->getStarredRssFolder();

$the_form .= '<form name="rssfolder"><table width="100%" border="0" cellspacing="2" cellpadding="0" style="margin-top:10px">
        <tr>
          <td width="15"><div align="center"><a href="javascript:;" onClick="toggleRSSFolder('."'S'".')"><img id="S_toggle"
src="'.$image_path.'plus.gif" border="0"></a></div></td>
          <td width="20"><div align="center"><img id="S_folder" src="'.$image_path.'rss_folder_cls.gif"></div></td>
          <td nowrap><a href="javascript:;" onClick="toggleRSSFolder('."'S'".')" class="rssFolder">Starred Feeds</a></td>
        </tr>
        <tr>
          <td colspan="3"><div id="S_feeds" style="display:none"><table width="100%" border="0" cellspacing="2" cellpadding=
"2" style="margin:5 0 0 35">'.$starred_rss_html.'</table></div></td>
        </tr>';

$the_form .= $allrsshtml;

$the_form .= "</table>";
$the_form .= get_validate_record_js();
$this_form .= "</form>";
return $the_form;
}

?>
