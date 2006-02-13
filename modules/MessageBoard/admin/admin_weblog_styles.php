<?php
/***************************************************************************
*                               admin_smilies.php
*                              -------------------
*     begin                : Thu May 31, 2001
*     copyright            : (C) 2001 The phpBB Group
*     email                : support@phpbb.com
*
*     $Id: admin_weblog_styles.php,v 1.9 2005/01/04 15:04:14 saraj Exp $
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
*	This file will be used for modifying the weblog's custom styles for a board.
**************************************************************************/

define('IN_PHPBB', 1);

//
// First we do the setmodules stuff for the admin cp.
//
if( !empty($setmodules) )
{
	$filename = basename(__FILE__);
	//$forum_module['Weblogs']['Styles'] = $filename;

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


if( $dir = @opendir($phpbb_root_path. "weblogs/styles/") )
{
	$dir_select = '<select name="style_dir" onchange="update_preview(this.form.style_dir.options[this.form.style_dir.selectedIndex].value)">';
	$selected = ($styles_data['style_dir'] == $sub_dir ) ? ' selected="selected"' : '';

	while( $sub_dir = @readdir($dir) )
	{
		if ( !is_file($phpbb_root_path. "weblogs/styles/" . $sub_dir) && $sub_dir != '.' && $sub_dir != '..' )
		{
			$dir_select .= '<option value="' . $sub_dir . '"' . $selected . '>' . $sub_dir . '</option>';	
		}
	}
	$dir_select .= '</select>';
}

//
// Select main mode
//
if( isset($HTTP_POST_VARS['add']) || isset($HTTP_GET_VARS['add']) )
{

	//
	// Admin has selected to add a style.
	//

	$template->set_filenames(array(
		"body" => "admin/weblog_style_edit_body.tpl")
	);

	$template->assign_vars(array(
		"L_STYLE_TITLE" => $lang['Weblog_style_title'],
		"L_STYLE_CONFIG" => $lang['Weblog_style_config'],
		"L_STYLE_EXPLAIN" => $lang['Weblog_style_explain'],
		"L_STYLE_NAME" => $lang['Weblog_style_name'],
		"L_STYLE_DIR" => $lang['Weblog_style_dir'],

		"L_STYLE_PREVIEW" => $lang['Weblog_style_preview'],
		"STYLE_DIR" => $dir_select,
		"PREVIEW_IMG" => ( $styles_data['style_dir'] ) ? $phpbb_root_path . 'weblogs/styles/' . $styles_data['style_dir'] . '/weblog_preview.gif' : $phpbb_root_path . 'images/spacer.gif',

		"L_SUBMIT" => $lang['Submit'],
		"L_RESET" => $lang['Reset'],

		"S_STYLE_ACTION" => append_sid("admin_weblog_styles.$phpEx?mode=savenew"), 
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
			// Admin has selected to delete a style.
			//

			$style_id = ( !empty($HTTP_POST_VARS['id']) ) ? $HTTP_POST_VARS['id'] : $HTTP_GET_VARS['id'];

			$sql = "DELETE FROM " . WEBLOG_STYLE_TABLE . "
				WHERE style_id = " . $style_id;
			$result = $db->sql_query($sql);
			if( !$result )
			{
				message_die(GENERAL_ERROR, "Couldn't delete style", "", __LINE__, __FILE__, $sql);
			}

			$message = $lang['Weblog_style_del_success'] . "<br /><br />" . sprintf($lang['Click_return_styleadmin'], "<a href=\"" . append_sid("admin_weblog_styles.$phpEx") . "\">", "</a>") . "<br /><br />" . sprintf($lang['Click_return_admin_index'], "<a href=\"" . append_sid("index.$phpEx?pane=right") . "\">", "</a>");

			message_die(GENERAL_MESSAGE, $message);
			break;

		case 'edit':
			//
			// Admin has selected to edit a style.
			//

			$style_id = ( !empty($HTTP_POST_VARS['id']) ) ? $HTTP_POST_VARS['id'] : $HTTP_GET_VARS['id'];

			$sql = "SELECT *
				FROM " . WEBLOG_STYLE_TABLE . "
				WHERE style_id = " . $style_id;
			$result = $db->sql_query($sql);
			if( !$result )
			{
				message_die(GENERAL_ERROR, 'Could not obtain emoticon information', "", __LINE__, __FILE__, $sql);
			}
			$styles_data = $db->sql_fetchrow($result);

			$template->set_filenames(array(
				"body" => "admin/weblog_style_edit_body.tpl")
			);

			$s_hidden_fields = '<input type="hidden" name="mode" value="save" /><input type="hidden" name="style_id" value="' . $styles_data['style_id'] . '" />';

			$template->assign_vars(array(
				"L_STYLE_TITLE" => $lang['Weblog_style_title'],
				"L_STYLE_CONFIG" => $lang['Weblog_style_config'],
				"L_STYLE_EXPLAIN" => $lang['Weblog_style_explain'],
				"L_STYLE_NAME" => $lang['Weblog_style_name'],
				"L_STYLE_PREVIEW" => $lang['Weblog_style_preview'],
				"L_STYLE_DIR" => $lang['Weblog_style_dir'],

				"L_SUBMIT" => $lang['Submit'],
				"L_RESET" => $lang['Reset'],

				"S_PREVIEW_BASEDIR" => $phpbb_root_path . 'weblogs/styles/preview',

				"STYLE_NAME" => $styles_data['style_name'],
				"STYLE_DIR" => $dir_select,
				"PREVIEW_IMG" => ( $styles_data['style_dir'] ) ? $phpbb_root_path . 'weblogs/styles/' . $styles_data['style_dir'] . '/weblog_preview.gif' : $phpbb_root_path . 'images/spacer.gif',

				"S_STYLE_ACTION" => append_sid("admin_weblog_styles.$phpEx?mode=edit&amp;id=$style_id"),
				"S_HIDDEN_FIELDS" => $s_hidden_fields, 
				"S_FILENAME_OPTIONS" => $filename_list)
			);

			$template->pparse("body");
			break;

		case "save":
			//
			// Admin has submitted changes while editing a style.
			//

			$style_id = ( !empty($HTTP_POST_VARS['id']) ) ? $HTTP_POST_VARS['id'] : $HTTP_GET_VARS['id'];

			//
			// Get the submitted data, being careful to ensure that we only
			// accept the data we are looking for.
			//
			$style_name = ( isset($HTTP_POST_VARS['style_name']) ) ? trim($HTTP_POST_VARS['style_name']) : trim($HTTP_GET_VARS['style_name']);
			$style_dir = ( isset($HTTP_POST_VARS['style_dir']) ) ? trim($HTTP_POST_VARS['style_dir']) : trim($HTTP_GET_VARS['style_dir']);

			// If no code was entered complain ...
			if ( $style_name == '' )
			{
				message_die(MESSAGE, $lang['Fields_empty']);
			}

			//
			// Proceed with updating the styles table.
			//
			$sql = "UPDATE " . WEBLOG_STYLE_TABLE . "
				SET style_name = '" . str_replace("\'", "''", $style_name) . "', style_dir = '" . str_replace("\'", "''", $style_dir) . "'
				WHERE style_id = $style_id";
			if( !($result = $db->sql_query($sql)) )
			{
				message_die(GENERAL_ERROR, "Couldn't update style info", "", __LINE__, __FILE__, $sql);
			}

			$message = $lang['Weblog_style_edit_success'] . "<br /><br />" . sprintf($lang['Click_return_styleadmin'], "<a href=\"" . append_sid("admin_weblog_styles.$phpEx") . "\">", "</a>") . "<br /><br />" . sprintf($lang['Click_return_admin_index'], "<a href=\"" . append_sid("index.$phpEx?pane=right") . "\">", "</a>");

			message_die(GENERAL_MESSAGE, $message);
			break;

		case "savenew":
			//
			// Admin has submitted changes while adding a new style.
			//

			//
			// Get the submitted data being careful to ensure the the data
			// we recieve and process is only the data we are looking for.
			//
			$style_name = ( isset($HTTP_POST_VARS['style_name']) ) ? trim($HTTP_POST_VARS['style_name']) : trim($HTTP_GET_VARS['style_name']);
			$style_dir = ( isset($HTTP_POST_VARS['style_dir']) ) ? trim($HTTP_POST_VARS['style_dir']) : trim($HTTP_GET_VARS['style_dir']);

			// If no code was entered complain ...
			if ( !$style_name )
			{
				message_die(MESSAGE, $lang['Fields_empty']);
			}

			//
			// Save the data to the weblog styles table.
			//
			$sql = "INSERT INTO " . WEBLOG_STYLE_TABLE . " (style_name, style_dir)
				VALUES ('" . str_replace("\'", "''", $style_name) . "', '" . str_replace("\'", "''", $style_dir) . "')";
			$result = $db->sql_query($sql);
			if( !$result )
			{
				message_die(GENERAL_ERROR, "Couldn't insert new style", "", __LINE__, __FILE__, $sql);
			}

			$message = $lang['Weblog_style_add_success'] . "<br /><br />" . sprintf($lang['Click_return_styleadmin'], "<a href=\"" . append_sid("admin_weblog_styles.$phpEx") . "\">", "</a>") . "<br /><br />" . sprintf($lang['Click_return_admin_index'], "<a href=\"" . append_sid("index.$phpEx?pane=right") . "\">", "</a>");

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
		FROM " . WEBLOG_STYLE_TABLE;
	$result = $db->sql_query($sql);
	if( !$result )
	{
		message_die(GENERAL_ERROR, "Couldn't obtain weblog styles data from database", "", __LINE__, __FILE__, $sql);
	}
	$styles_data = $db->sql_fetchrowset($result);

	$template->set_filenames(array(
		"body" => "admin/weblog_style_list_body.tpl")
	);

	$template->assign_vars(array(
		"L_ACTION" => $lang['Action'],
		"L_STYLE_TITLE" => $lang['Weblog_style_title'],
		"L_STYLE_TEXT" => $lang['Weblog_style_explain'],
		"L_DELETE" => $lang['Delete'],
		"L_EDIT" => $lang['Edit'],
		"L_STYLE_ADD" => $lang['Weblog_style_add'],
		"L_STYLE_NAME" => $lang['Weblog_style'],
		
		"S_HIDDEN_FIELDS" => $s_hidden_fields, 
		"S_STYLE_ACTION" => append_sid("admin_weblog_styles.$phpEx"))
	);

	//
	// Loop throuh the rows of styles setting block vars for the template.
	//
	for($i = 0; $i < count($styles_data); $i++)
	{
		$row_color = ( !($i % 2) ) ? $theme['td_color1'] : $theme['td_color2'];
		$row_class = ( !($i % 2) ) ? $theme['td_class1'] : $theme['td_class2'];

		$template->assign_block_vars("style", array(
			"ROW_COLOR" => "#" . $row_color,
			"ROW_CLASS" => $row_class,
			
			"STYLE" => $styles_data[$i]['style_name'],
			"STYLE_IMAGE" => '<img src="' . $phpbb_root_path . 'weblogs/styles/' . $styles_data[$i]['style_dir'] . '/weblog_preview.gif" border="0">',

			"U_STYLE_EDIT" => append_sid("admin_weblog_styles.$phpEx?mode=edit&amp;id=" . $styles_data[$i]['style_id']), 
			"U_STYLE_DELETE" => append_sid("admin_weblog_styles.$phpEx?mode=delete&amp;id=" . $styles_data[$i]['style_id']))
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
