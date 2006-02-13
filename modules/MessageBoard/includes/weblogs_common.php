<?php

/***************************************************************************
 *                             weblog_common.php
 *                            -------------------
 *   begin                : Saturday', Feb 13', 2001
 *   copyright            : ('C) 2001 The phpBB Group
 *   email                : support@phpbb.com
 *
 *   $Id: weblogs_common.php,v 1.9 2005/01/04 15:04:16 saraj Exp $
 *
 *
 ***************************************************************************/

/***************************************************************************
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License', or
 *   ('at your option) any later version.
 *
 ***************************************************************************/

if ( !defined('IN_PHPBB') )
{
	die("Hacking attempt");
}

//define('IN_WEBLOG', 1);

// Weblog Constants (Constants used all over the place)
define('WEBLOG_ALL', 0);
define('WEBLOG_REGISTERED', 1);
define('WEBLOG_USERGROUP', 2);
define('WEBLOG_OWNER', 3);

define('POST_WEBLOG_URL', 'w');
define('CAT_MODE', 'cm');
define('POST_ENTRY_URL', 'e');

define('WEBLOG_STYLE_KEEP', 0);
define('WEBLOG_STYLE_SWITCH', 1);
define('WEBLOG_STYLE_SWITCH_MODE', 2);

define('WEBLOG_ADVANCED', 2);
define('WEBLOG_SIMPLE', 1);

define('WEBLOG_ALLOW_CHOOSE', 0);
define('WEBLOG_ALLOW_SIMPLE', 1);
define('WEBLOG_ALLOW_ADVANCED', 2);

define('WEBLOG_SHOW', 1);
define('WEBLOG_HIDE', 0);

$use_lang = ( !file_exists($phpbb_root_path . 'language/lang_' . $board_config['default_lang'] . '/lang_weblog_main.'.$phpEx) ) ? 'english' : $board_config['default_lang'];
include($phpbb_root_path . 'language/lang_' . $use_lang . '/lang_weblog_main.' . $phpEx);

//
// Weblog Config
//
$sql = "SELECT *
	FROM " . WEBLOG_CONFIG_TABLE;
if( !($result = $db->sql_query($sql)) )
{
	message_die(CRITICAL_ERROR, "Could not query config information", "", __LINE__, __FILE__, $sql);
}

while ( $row = $db->sql_fetchrow($result) )
{
	$weblog_config[$row['config_name']] = $row['config_value'];
}

$sql = "SELECT *
	FROM " . WEBLOG_MOOD_TABLE;
$result = $db->sql_query($sql);
if( !$result )
{
	message_die(GENERAL_ERROR, "Couldn't obtain mood data from database", "", __LINE__, __FILE__, $sql);
}
$mood_data = $db->sql_fetchrowset($result);

$sql = "SELECT *
	FROM " . WEBLOG_CURRENTLY_TABLE;
$result = $db->sql_query($sql);
if( !$result )
{
	message_die(GENERAL_ERROR, "Couldn't obtain currently data from database", "", __LINE__, __FILE__, $sql);
}
$currently_data = $db->sql_fetchrowset($result);

//Added by Rajkumar
 if ( !empty($HTTP_POST_VARS['w']) || !empty($HTTP_GET_VARS['w']) )
{
	$fid = ( !empty($HTTP_POST_VARS['w']) ) ? intval($HTTP_POST_VARS['w']) : intval($HTTP_GET_VARS['w']);
}
else
{
	$fid = '';
}
if ($fid)
{
	$sql = "SELECT *
		FROM " . WEBLOG_CATEGORIES . " WHERE forum_id = $fid";
	$result = $db->sql_query($sql);
	if( !$result )
	{
		message_die(GENERAL_ERROR, "Couldn't obtain currently data from database", "", __LINE__, __FILE__, $sql);
	}
	$blog_cat_data = $db->sql_fetchrowset($result);
}

//

//
// Weblog Auth
//
$weblog_auth_desc[WEBLOG_ALL] = $lang['Weblog_auth_all'];
$weblog_auth_desc[WEBLOG_REGISTERED] = $lang['Weblog_auth_reg'];
$weblog_auth_desc[WEBLOG_USERGROUP] = $lang['Weblog_auth_group'];
$weblog_auth_desc[WEBLOG_OWNER] = $lang['Weblog_auth_owner'];

$weblog_auth_types = array(WEBLOG_ALL, WEBLOG_REGISTERED, WEBLOG_USERGROUP, WEBLOG_OWNER );

$mod_version = '0.4.0';

?>
