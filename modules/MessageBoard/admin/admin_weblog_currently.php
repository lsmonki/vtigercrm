<?php
/***************************************************************************
*                               admin_smilies.php
*                              -------------------
*     begin                : Thu May 31, 2001
*     copyright            : (C) 2001 The phpBB Group
*     email                : support@phpbb.com
*
*     $Id: admin_weblog_currently.php,v 1.9 2005/01/04 15:04:14 saraj Exp $
*
****************************************************************************/

/***************************************************************************
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 *
 ***************************************************************************/

/**************************************************************************
*	This file will be used for modifying the weblog's currently settings for a board.
**************************************************************************/

define('IN_PHPBB', 1);

//
// First we do the setmodules stuff for the admin cp.
//
if( !empty($setmodules) )
{
	$filename = basename(__FILE__);
	//$forum_module['Weblogs']['Currently Choices'] = $filename;

	return;
}

$phpbb_root_path = "./../";
require($phpbb_root_path . 'extension.inc');
require('./pagestart.' . $phpEx);

// include the weblog lang file
$use_lang = ( !file_exists($phpbb_root_path . 'language/lang_' . $board_config['default_lang'] . '/lang_weblog_admin.'.$phpEx) ) ? 'english' : $board_config['default_lang'];
include($phpbb_root_path . 'language/lang_' . $use_lang . '/lang_weblog_admin.' . $phpEx);

//
// Check to see what mode we should operate in.
//
if( isset($HTTP_POST_VARS['mode']) || isset($HTTP_GET_VARS['mode']) )
{
	$mode = ( isset($HTTP_POST_VARS['mode']) ) ? $HTTP_POST_VARS['mode'] : $HTTP_GET_VARS['mode'];
}
else
{
	$mode = "";
}

//
// Read a listing of uploaded currently icons for use in the add or edit smliey code...
//
$dir = @opendir($phpbb_root_path . 'images/weblogs/');

while($file = @readdir($dir))
{
	if( !@is_dir(phpbb_realpath($phpbb_root_path . 'images/weblogs/' . $file)) )
	{
		$img_size = @getimagesize($phpbb_root_path . 'images/weblogs/' . $file);

		if( $img_size[0] && $img_size[1] )
		{
			$currently_images[] = $file;
		}
	}
}

@closedir($dir);

//
// Select main mode
//
if( isset($HTTP_POST_VARS['add']) || isset($HTTP_GET_VARS['add']) )
{
	//
	// Admin has selected to add a "currently".
	//

	$template->set_filenames(array(
		"body" => "admin/weblog_currently_edit_body.tpl")
	);

	$filename_list = "";
	for( $i = 0; $i < count($currently_images); $i++ )
	{
		$filename_list .= '<option value="' . $currently_images[$i] . '">' . $currently_images[$i] . '</option>';
	}

	$s_hidden_fields = '<input type="hidden" name="mode" value="savenew" />';

	$template->assign_vars(array(
		"L_CURRENTLY_TITLE" => $lang['Weblog_currently_title'],
		"L_CURRENTLY_CONFIG" => $lang['Weblog_currently_config'],
		"L_CURRENTLY_EXPLAIN" => $lang['Weblog_currently_explain'],
		"L_CURRENTLY_URL" => $lang['Weblog_currently_url'],
		"L_CURRENTLY_CURRENTLY" => $lang['Weblog_currently_currently'],
		"L_SUBMIT" => $lang['Submit'],
		"L_RESET" => $lang['Reset'],

		"CURRENTLY_IMG" => $phpbb_root_path . 'images/weblogs/' . $currently_images[0], 

		"S_CURRENTLY_ACTION" => append_sid("admin_weblog_currently.$phpEx"), 
		"S_HIDDEN_FIELDS" => $s_hidden_fields, 
		"S_FILENAME_OPTIONS" => $filename_list)
	);

	$template->pparse("body");
}
else if ( $mode != "" )
{
	switch( $mode )
	{
		case 'delete':
			//
			// Admin has selected to delete a currently.
			//

			$currently_id = ( !empty($HTTP_POST_VARS['id']) ) ? $HTTP_POST_VARS['id'] : $HTTP_GET_VARS['id'];

			$sql = "DELETE FROM " . WEBLOG_CURRENTLY_TABLE . "
				WHERE currently_id = " . $currently_id;
			$result = $db->sql_query($sql);
			if( !$result )
			{
				message_die(GENERAL_ERROR, "Couldn't delete currently", "", __LINE__, __FILE__, $sql);
			}

			$message = $lang['Weblog_currently_del_success'] . "<br /><br />" . sprintf($lang['Click_return_currentlyadmin'], "<a href=\"" . append_sid("admin_weblog_currently.$phpEx") . "\">", "</a>") . "<br /><br />" . sprintf($lang['Click_return_admin_index'], "<a href=\"" . append_sid("index.$phpEx?pane=right") . "\">", "</a>");

			message_die(GENERAL_MESSAGE, $message);
			break;

		case 'edit':
			//
			// Admin has selected to edit a currently.
			//

			$currently_id = ( !empty($HTTP_POST_VARS['id']) ) ? $HTTP_POST_VARS['id'] : $HTTP_GET_VARS['id'];

			$sql = "SELECT *
				FROM " . WEBLOG_CURRENTLY_TABLE . "
				WHERE currently_id = " . $currently_id;
			$result = $db->sql_query($sql);
			if( !$result )
			{
				message_die(GENERAL_ERROR, 'Could not obtain emoticon information', "", __LINE__, __FILE__, $sql);
			}
			$currently_data = $db->sql_fetchrow($result);

			$filename_list = "";
			for( $i = 0; $i < count($currently_images); $i++ )
			{
				if( $currently_images[$i] == $currently_data['image_url'] )
				{
					$currently_selected = "selected=\"selected\"";
					$currently_edit_img = $currently_images[$i];
				}
				else
				{
					$currently_selected = "";
				}

				$filename_list .= '<option value="' . $currently_images[$i] . '"' . $currently_selected . '>' . $currently_images[$i] . '</option>';
			}

			$template->set_filenames(array(
				"body" => "admin/weblog_currently_edit_body.tpl")
			);

			$s_hidden_fields = '<input type="hidden" name="mode" value="save" /><input type="hidden" name="currently_id" value="' . $currently_data['currently_id'] . '" />';

			$template->assign_vars(array(
				"L_CURRENTLY_TITLE" => $lang['Weblog_currently_title'],
				"L_CURRENTLY_CONFIG" => $lang['Weblog_currently_config'],
				"L_CURRENTLY_EXPLAIN" => $lang['Weblog_currently_explain'],
				"L_CURRENTLY_URL" => $lang['Weblog_currently_url'],
				"L_CURRENTLY_CURRENTLY" => $lang['Weblog_currently_currently'],
				"L_SUBMIT" => $lang['Submit'],
				"L_RESET" => $lang['Reset'],

				"CURRENTLY_IMG" => $phpbb_root_path . 'images/weblogs/' . $currently_edit_img, 
				"CURRENTLY_CURRENTLY" => $currently_data['currently'],

				"S_CURRENTLY_ACTION" => append_sid("admin_weblog_currently.$phpEx"),
				"S_HIDDEN_FIELDS" => $s_hidden_fields, 
				"S_FILENAME_OPTIONS" => $filename_list)
			);

			$template->pparse("body");
			break;

		case "save":
			//
			// Admin has submitted changes while editing a currently.
			//

			//
			// Get the submitted data, being careful to ensure that we only
			// accept the data we are looking for.
			//
			$currently_url = ( isset($HTTP_POST_VARS['currently_url']) ) ? trim($HTTP_POST_VARS['currently_url']) : trim($HTTP_GET_VARS['currently_url']);
			$currently_currently = ( isset($HTTP_POST_VARS['currently_currently']) ) ? trim($HTTP_POST_VARS['currently_currently']) : trim($HTTP_GET_VARS['currently_currently']);
			$currently_id = ( isset($HTTP_POST_VARS['currently_id']) ) ? intval($HTTP_POST_VARS['currently_id']) : intval($HTTP_GET_VARS['currently_id']);

			// If no code was entered complain ...
			if ( $currently_url == '')
			{
				message_die(MESSAGE, $lang['Fields_empty']);
			}

			//
			// Proceed with updating the currently table.
			//
			$sql = "UPDATE " . WEBLOG_CURRENTLY_TABLE . "
				SET image_url = '" . str_replace("\'", "''", $currently_url) . "', currently = '" . str_replace("\'", "''", $currently_currently) . "'
				WHERE currently_id = $currently_id";
			if( !($result = $db->sql_query($sql)) )
			{
				message_die(GENERAL_ERROR, "Couldn't update currently info", "", __LINE__, __FILE__, $sql);
			}

			$message = $lang['Weblog_currently_edit_success'] . "<br /><br />" . sprintf($lang['Click_return_currentlyadmin'], "<a href=\"" . append_sid("admin_weblog_currently.$phpEx") . "\">", "</a>") . "<br /><br />" . sprintf($lang['Click_return_admin_index'], "<a href=\"" . append_sid("index.$phpEx?pane=right") . "\">", "</a>");

			message_die(GENERAL_MESSAGE, $message);
			break;

		case "savenew":
			//
			// Admin has submitted changes while adding a new currently.
			//

			//
			// Get the submitted data being careful to ensure the the data
			// we recieve and process is only the data we are looking for.
			//
			$currently_url = ( isset($HTTP_POST_VARS['currently_url']) ) ? $HTTP_POST_VARS['currently_url'] : $HTTP_GET_VARS['currently_url'];
			$currently_currently = ( isset($HTTP_POST_VARS['currently_currently']) ) ? $HTTP_POST_VARS['currently_currently'] : $HTTP_GET_VARS['currently_currently'];

			// If no code was entered complain ...
			if ($currently_url == '')
			{
				message_die(MESSAGE, $lang['Fields_empty']);
			}

			//
			// Save the data to the currently table.
			//
			$sql = "INSERT INTO " . WEBLOG_CURRENTLY_TABLE . " (image_url, currently)
				VALUES ('" . str_replace("\'", "''", $currently_url) . "', '" . str_replace("\'", "''", $currently_currently) . "')";
			$result = $db->sql_query($sql);
			if( !$result )
			{
				message_die(GENERAL_ERROR, "Couldn't insert new currently", "", __LINE__, __FILE__, $sql);
			}

			$message = $lang['Weblog_currently_add_success'] . "<br /><br />" . sprintf($lang['Click_return_currentlyadmin'], "<a href=\"" . append_sid("admin_weblog_currently.$phpEx") . "\">", "</a>") . "<br /><br />" . sprintf($lang['Click_return_admin_index'], "<a href=\"" . append_sid("index.$phpEx?pane=right") . "\">", "</a>");

			message_die(GENERAL_MESSAGE, $message);
			break;
	}
}
else
{

	//
	// This is the main display of the page before the admin has selected
	// any options.
	//
	$sql = "SELECT *
		FROM " . WEBLOG_CURRENTLY_TABLE;
	$result = $db->sql_query($sql);
	if( !$result )
	{
		message_die(GENERAL_ERROR, "Couldn't obtain currently data from database", "", __LINE__, __FILE__, $sql);
	}
	$currently_data = $db->sql_fetchrowset($result);

	$template->set_filenames(array(
		"body" => "admin/weblog_currently_list_body.tpl")
	);

	$template->assign_vars(array(
		"L_ACTION" => $lang['Action'],
		"L_CURRENTLY_TITLE" => $lang['Weblog_currently_title'],
		"L_CURRENTLY_TEXT" => $lang['Weblog_currently_explain'],
		"L_DELETE" => $lang['Delete'],
		"L_EDIT" => $lang['Edit'],
		"L_CURRENTLY_ADD" => $lang['Weblog_currently_add'],
		"L_IMAGE" => $lang['Weblog_image'],
		"L_CURRENTLY" => $lang['Weblog_currently'],
		
		"S_HIDDEN_FIELDS" => $s_hidden_fields, 
		"S_CURRENTLY_ACTION" => append_sid("admin_weblog_currently.$phpEx"))
	);

	//
	// Loop throuh the rows of currentlys setting block vars for the template.
	//
	for($i = 0; $i < count($currently_data); $i++)
	{
		$row_color = ( !($i % 2) ) ? $theme['td_color1'] : $theme['td_color2'];
		$row_class = ( !($i % 2) ) ? $theme['td_class1'] : $theme['td_class2'];

		$template->assign_block_vars("currently", array(
			"ROW_COLOR" => "#" . $row_color,
			"ROW_CLASS" => $row_class,
			
			"CURRENTLY_IMG" =>  $phpbb_root_path . 'images/weblogs/' . $currently_data[$i]['image_url'], 
			"CURRENTLY" => $currently_data[$i]['currently'],
			
			"U_CURRENTLY_EDIT" => append_sid("admin_weblog_currently.$phpEx?mode=edit&amp;id=" . $currently_data[$i]['currently_id']), 
			"U_CURRENTLY_DELETE" => append_sid("admin_weblog_currently.$phpEx?mode=delete&amp;id=" . $currently_data[$i]['currently_id']))
		);
	}

	//
	// Spit out the page.
	//
	$template->pparse("body");
}

//
// Page Footer
//
include('./page_footer_admin.'.$phpEx);

?>
