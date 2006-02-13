<?php
/***************************************************************************
*                               admin_smilies.php
*                              -------------------
*     begin                : Thu May 31, 2001
*     copyright            : (C) 2001 The phpBB Group
*     email                : support@phpbb.com
*
*     $Id: admin_weblog_mood.php,v 1.9 2005/01/04 15:04:14 saraj Exp $
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
*	This file will be used for modifying the weblog's mood settings for a board.
**************************************************************************/

define('IN_PHPBB', 1);

//
// First we do the setmodules stuff for the admin cp.
//
if( !empty($setmodules) )
{
	$filename = basename(__FILE__);
	//$forum_module['Weblogs']['Mood Choices'] = $filename;

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
// Read a listing of uploaded mood icons for use in the add or edit smliey code...
//
$dir = @opendir($phpbb_root_path . 'images/weblogs/');

while($file = @readdir($dir))
{
	if( !@is_dir(phpbb_realpath($phpbb_root_path . 'images/weblogs/' . $file)) )
	{
		$img_size = @getimagesize($phpbb_root_path . 'images/weblogs/' . $file);

		if( $img_size[0] && $img_size[1] )
		{
			$mood_images[] = $file;
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
	// Admin has selected to add a mood.
	//

	$template->set_filenames(array(
		"body" => "admin/weblog_mood_edit_body.tpl")
	);

	$filename_list = "";
	for( $i = 0; $i < count($mood_images); $i++ )
	{
		$filename_list .= '<option value="' . $mood_images[$i] . '">' . $mood_images[$i] . '</option>';
	}

	$s_hidden_fields = '<input type="hidden" name="mode" value="savenew" />';

	$template->assign_vars(array(
		"L_MOOD_TITLE" => $lang['Weblog_mood_title'],
		"L_MOOD_CONFIG" => $lang['Weblog_mood_config'],
		"L_MOOD_EXPLAIN" => $lang['Weblog_mood_explain'],
		"L_MOOD_URL" => $lang['Weblog_mood_url'],
		"L_MOOD_MOOD" => $lang['Weblog_mood_mood'],
		"L_SUBMIT" => $lang['Submit'],
		"L_RESET" => $lang['Reset'],

		"MOOD_IMG" => $phpbb_root_path . 'images/weblogs/' . $mood_images[0], 

		"S_MOOD_ACTION" => append_sid("admin_weblog_mood.$phpEx"), 
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
			// Admin has selected to delete a mood.
			//

			$mood_id = ( !empty($HTTP_POST_VARS['id']) ) ? $HTTP_POST_VARS['id'] : $HTTP_GET_VARS['id'];

			$sql = "DELETE FROM " . WEBLOG_MOOD_TABLE . "
				WHERE mood_id = " . $mood_id;
			$result = $db->sql_query($sql);
			if( !$result )
			{
				message_die(GENERAL_ERROR, "Couldn't delete mood", "", __LINE__, __FILE__, $sql);
			}

			$message = $lang['Weblog_mood_del_success'] . "<br /><br />" . sprintf($lang['Click_return_moodadmin'], "<a href=\"" . append_sid("admin_weblog_mood.$phpEx") . "\">", "</a>") . "<br /><br />" . sprintf($lang['Click_return_admin_index'], "<a href=\"" . append_sid("index.$phpEx?pane=right") . "\">", "</a>");

			message_die(GENERAL_MESSAGE, $message);
			break;

		case 'edit':
			//
			// Admin has selected to edit a mood.
			//

			$mood_id = ( !empty($HTTP_POST_VARS['id']) ) ? $HTTP_POST_VARS['id'] : $HTTP_GET_VARS['id'];

			$sql = "SELECT *
				FROM " . WEBLOG_MOOD_TABLE . "
				WHERE mood_id = " . $mood_id;
			$result = $db->sql_query($sql);
			if( !$result )
			{
				message_die(GENERAL_ERROR, 'Could not obtain emoticon information', "", __LINE__, __FILE__, $sql);
			}
			$mood_data = $db->sql_fetchrow($result);

			$filename_list = "";
			for( $i = 0; $i < count($mood_images); $i++ )
			{
				if( $mood_images[$i] == $mood_data['image_url'] )
				{
					$mood_selected = "selected=\"selected\"";
					$mood_edit_img = $mood_images[$i];
				}
				else
				{
					$mood_selected = "";
				}

				$filename_list .= '<option value="' . $mood_images[$i] . '"' . $mood_selected . '>' . $mood_images[$i] . '</option>';
			}

			$template->set_filenames(array(
				"body" => "admin/weblog_mood_edit_body.tpl")
			);

			$s_hidden_fields = '<input type="hidden" name="mode" value="save" /><input type="hidden" name="mood_id" value="' . $mood_data['mood_id'] . '" />';

			$template->assign_vars(array(
				"L_MOOD_TITLE" => $lang['Weblog_mood_title'],
				"L_MOOD_CONFIG" => $lang['Weblog_mood_config'],
				"L_MOOD_EXPLAIN" => $lang['Weblog_mood_explain'],
				"L_MOOD_URL" => $lang['Weblog_mood_url'],
				"L_MOOD_MOOD" => $lang['Weblog_mood_mood'],
				"L_SUBMIT" => $lang['Submit'],
				"L_RESET" => $lang['Reset'],

				"MOOD_IMG" => $phpbb_root_path . 'images/weblogs/' . $mood_edit_img, 
				"MOOD_MOOD" => $mood_data['mood'],

				"S_MOOD_ACTION" => append_sid("admin_weblog_mood.$phpEx"),
				"S_HIDDEN_FIELDS" => $s_hidden_fields, 
				"S_FILENAME_OPTIONS" => $filename_list)
			);

			$template->pparse("body");
			break;

		case "save":
			//
			// Admin has submitted changes while editing a mood.
			//

			//
			// Get the submitted data, being careful to ensure that we only
			// accept the data we are looking for.
			//
			$mood_url = ( isset($HTTP_POST_VARS['mood_url']) ) ? trim($HTTP_POST_VARS['mood_url']) : trim($HTTP_GET_VARS['mood_url']);
			$mood_mood = ( isset($HTTP_POST_VARS['mood_mood']) ) ? trim($HTTP_POST_VARS['mood_mood']) : trim($HTTP_GET_VARS['mood_mood']);
			$mood_id = ( isset($HTTP_POST_VARS['mood_id']) ) ? intval($HTTP_POST_VARS['mood_id']) : intval($HTTP_GET_VARS['mood_id']);

			// If no code was entered complain ...
			if ( $mood_url == '')
			{
				message_die(MESSAGE, $lang['Fields_empty']);
			}

			//
			// Proceed with updating the mood table.
			//
			$sql = "UPDATE " . WEBLOG_MOOD_TABLE . "
				SET image_url = '" . str_replace("\'", "''", $mood_url) . "', mood = '" . str_replace("\'", "''", $mood_mood) . "'
				WHERE mood_id = $mood_id";
			if( !($result = $db->sql_query($sql)) )
			{
				message_die(GENERAL_ERROR, "Couldn't update mood info", "", __LINE__, __FILE__, $sql);
			}

			$message = $lang['Weblog_mood_edit_success'] . "<br /><br />" . sprintf($lang['Click_return_moodadmin'], "<a href=\"" . append_sid("admin_weblog_mood.$phpEx") . "\">", "</a>") . "<br /><br />" . sprintf($lang['Click_return_admin_index'], "<a href=\"" . append_sid("index.$phpEx?pane=right") . "\">", "</a>");

			message_die(GENERAL_MESSAGE, $message);
			break;

		case "savenew":
			//
			// Admin has submitted changes while adding a new mood.
			//

			//
			// Get the submitted data being careful to ensure the the data
			// we recieve and process is only the data we are looking for.
			//
			$mood_url = ( isset($HTTP_POST_VARS['mood_url']) ) ? $HTTP_POST_VARS['mood_url'] : $HTTP_GET_VARS['mood_url'];
			$mood_mood = ( isset($HTTP_POST_VARS['mood_mood']) ) ? $HTTP_POST_VARS['mood_mood'] : $HTTP_GET_VARS['mood_mood'];

			// If no code was entered complain ...
			if ($mood_url == '')
			{
				message_die(MESSAGE, $lang['Fields_empty']);
			}

			//
			// Save the data to the mood table.
			//
			$sql = "INSERT INTO " . WEBLOG_MOOD_TABLE . " (image_url, mood)
				VALUES ('" . str_replace("\'", "''", $mood_url) . "', '" . str_replace("\'", "''", $mood_mood) . "')";
			$result = $db->sql_query($sql);
			if( !$result )
			{
				message_die(GENERAL_ERROR, "Couldn't insert new mood", "", __LINE__, __FILE__, $sql);
			}

			$message = $lang['Weblog_mood_add_success'] . "<br /><br />" . sprintf($lang['Click_return_moodadmin'], "<a href=\"" . append_sid("admin_weblog_mood.$phpEx") . "\">", "</a>") . "<br /><br />" . sprintf($lang['Click_return_admin_index'], "<a href=\"" . append_sid("index.$phpEx?pane=right") . "\">", "</a>");

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
		FROM " . WEBLOG_MOOD_TABLE;
	$result = $db->sql_query($sql);
	if( !$result )
	{
		message_die(GENERAL_ERROR, "Couldn't obtain mood data from database", "", __LINE__, __FILE__, $sql);
	}
	$mood_data = $db->sql_fetchrowset($result);

	$template->set_filenames(array(
		"body" => "admin/weblog_mood_list_body.tpl")
	);

	$template->assign_vars(array(
		"L_ACTION" => $lang['Action'],
		"L_MOOD_TITLE" => $lang['Weblog_mood_title'],
		"L_MOOD_TEXT" => $lang['Weblog_mood_explain'],
		"L_DELETE" => $lang['Delete'],
		"L_EDIT" => $lang['Edit'],
		"L_MOOD_ADD" => $lang['Weblog_mood_add'],
		"L_IMAGE" => $lang['Weblog_image'],
		"L_MOOD" => $lang['Weblog_mood'],
		
		"S_HIDDEN_FIELDS" => $s_hidden_fields, 
		"S_MOOD_ACTION" => append_sid("admin_weblog_mood.$phpEx"))
	);

	//
	// Loop throuh the rows of moods setting block vars for the template.
	//
	for($i = 0; $i < count($mood_data); $i++)
	{
		$row_color = ( !($i % 2) ) ? $theme['td_color1'] : $theme['td_color2'];
		$row_class = ( !($i % 2) ) ? $theme['td_class1'] : $theme['td_class2'];

		$template->assign_block_vars("mood", array(
			"ROW_COLOR" => "#" . $row_color,
			"ROW_CLASS" => $row_class,
			
			"MOOD_IMG" =>  $phpbb_root_path . 'images/weblogs/' . $mood_data[$i]['image_url'], 
			"MOOD" => $mood_data[$i]['mood'],
			
			"U_MOOD_EDIT" => append_sid("admin_weblog_mood.$phpEx?mode=edit&amp;id=" . $mood_data[$i]['mood_id']), 
			"U_MOOD_DELETE" => append_sid("admin_weblog_mood.$phpEx?mode=delete&amp;id=" . $mood_data[$i]['mood_id']))
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
