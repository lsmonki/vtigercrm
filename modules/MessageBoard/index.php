<?php
/***************************************************************************
 *                                index.php
 *                            -------------------
 *   begin                : Saturday, Feb 13, 2001
 *   copyright            : (C) 2001 The phpBB Group
 *   email                : support@phpbb.com
 *
 *   $Id: index.php,v 1.9 2005/01/04 15:04:12 saraj Exp $
 *
 *
 ***************************************************************************/

/***************************************************************************
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 *
 ***************************************************************************/
define('PHPBB_INSTALLED', true);
define('IN_PHPBB', true);

//this is being done so that the headers can be set and we can have the same layout as the CRM pages
$modifiedphp_root='index.php?module=MessageBoard&action=';

//include('extension.inc');
//include('common.php');

	
/*if(isset($_SESSION['authenticated_user_id']))
{
  $result = $current_user->retrieve($_SESSION['authenticated_user_id']);
  
}
*/
//$userdata = session_pagestart($user_ip, PAGE_INDEX);
//init_userprefs($userdata);

if(isset($_SESSION['authenticated_user_theme']) && $_SESSION['authenticated_user_theme'] != '')
{
        $theme = $_SESSION['authenticated_user_theme'];
}
else
{
        $theme = $default_theme;
}



global $theme;
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
require_once ($theme_path."layout_utils.php");
global $mod_strings;

echo get_module_title($mod_strings['LBL_MODULE_NAME'], $mod_strings['LBL_MODULE_TITLE'], true);
echo "\n<BR>\n";
include('modules/MessageBoard/ListView.php');

?>
