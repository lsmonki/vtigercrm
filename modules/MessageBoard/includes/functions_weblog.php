<?php
/***************************************************************************
 *                           functions_weblog.php
 *                           --------------------
 *
 *   author		: netclectic - http://www.phpbb.com/phpBB/profile.php?mode=viewprofile&u=6288
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
include($phpbb_root_path . 'includes/weblogs_common.'.$phpEx);

function create_user_weblog($userid, $weblog_name, $weblog_desc, $weblog_cat, $weblog_access, $weblog_visible, $weblog_show_entries, $weblog_comment_name, $weblog_post_comment_name, $weblog_advanced, $weblog_style, $weblog_access2)
{
	//        5 0 3 1 1 3 3 3 1 3 0 
	global $db, $board_config;
	
	$auth_access = 0;

	switch ( $weblog_auth_types[$weblog_access] )
	{
		case WEBLOG_ALL:
			$auth_read = AUTH_ALL;
			break;
		case WEBLOG_REGISTERED:
			$auth_read = AUTH_REG;
			break;
		case WEBLOG_USERGROUP:
			$auth_read = AUTH_ACL;
			$auth_access = 1;
			break;
		case WEBLOG_OWNER:
			$auth_read = AUTH_MOD;
			break;
		default:
			message_die(GENERAL_ERROR, 'Invalid Weblog access level.', '', __LINE__, __FILE__, $weblog_access);
			break;
	}
		

	switch ( $weblog_auth_types[$weblog_access2] )
	{
		case WEBLOG_ALL:
			$auth_reply = AUTH_ALL;
			$auth_vote = AUTH_ALL;
			break;
		case WEBLOG_REGISTERED:
			$auth_reply = AUTH_REG;
			$auth_vote = AUTH_REG;
			break;
		case WEBLOG_USERGROUP:
			$auth_reply = AUTH_ACL;
			$auth_vote = AUTH_ACL;
			$auth_access = 1;
			break;
		case WEBLOG_OWNER:
			$auth_reply = AUTH_MOD;
			$auth_vote = AUTH_MOD;
			break;
		default:
			message_die(GENERAL_ERROR, 'Invalid Weblog access level (2).', '', __LINE__, __FILE__, $weblog_access);
			break;
	}
	
	$forum_auth_ary = array(
		"auth_view" => AUTH_ADMIN, 
		"auth_read" => $auth_read, 
		"auth_post" => AUTH_MOD, 
		"auth_reply" => $auth_reply, 
		"auth_edit" => AUTH_REG, 
		"auth_delete" => AUTH_MOD, 
		"auth_sticky" => AUTH_MOD, 
		"auth_announce" => AUTH_MOD, 
		"auth_vote" => $auth_vote, 
		"auth_pollcreate" => AUTH_MOD,
		"auth_attachments" => AUTH_ALL
	);

	//
	// Get the order for the new forum
	//
	$sql = "SELECT MAX(forum_order) AS max_order
			FROM " . FORUMS_TABLE . "
			WHERE cat_id = " . $weblog_cat;
	
	if( !$result = $db->sql_query($sql) )
	{
		message_die(GENERAL_ERROR, "Couldn't get order number from forums table", "", __LINE__, __FILE__, $sql);
	}
	
	$row = $db->sql_fetchrow($result);
	$max_order = $row['max_order'];
	$next_order = $max_order + 10;
	
	
	//
	// get the forumid for the new forum
	//
	$sql = "SELECT MAX(forum_id) AS max_id
			FROM " . FORUMS_TABLE;
	
	if( !$result = $db->sql_query($sql) )
	{
		message_die(GENERAL_ERROR, "Couldn't get order number from forums table", "", __LINE__, __FILE__, $sql);
	}
	
	$row = $db->sql_fetchrow($result);
	$max_id = $row['max_id'];
	$next_id = $max_id + 1;
	
	
	//
	// Setup the persmissions for the new forum
	//
	$field_sql = "";
	$value_sql = "";
	while( list($field, $value) = each($forum_auth_ary) )
	{
		$field_sql .= ", $field";
		$value_sql .= ", $value";
	}
	$weblog_name = str_replace("'", "\'", $weblog_name);
	$weblog_name = stripslashes($weblog_name);
	$weblog_name = htmlspecialchars(trim($weblog_name));

	$weblog_desc = str_replace("'", "\'", $weblog_desc);
	$weblog_desc = stripslashes($weblog_desc);
	$weblog_desc = htmlspecialchars(trim($weblog_desc));


	// There is no problem having duplicate forum names so we won't check for it.
	$sql = "INSERT INTO " . FORUMS_TABLE . " (forum_id, forum_name, cat_id, forum_desc, forum_order, forum_status" . $field_sql . ")
			VALUES ('" . $next_id . "', '$weblog_name', '" . $weblog_cat . "', '$weblog_desc', $next_order, " . intval($HTTP_POST_VARS['forumstatus']) . $value_sql . ");";
		
	if( !$result = $db->sql_query($sql) )
	{
		message_die(GENERAL_ERROR, "Couldn't insert row in forums table", "", __LINE__, __FILE__, $sql);
	}
	
	//
	// Get the current max groupid
	//
	$sql = "SELECT MAX(group_id) as max_id 
			FROM " . GROUPS_TABLE . ";";
	if( !$result = $db->sql_query($sql) )
	{
		message_die(GENERAL_ERROR, "Couldn't get group info from groups table", "", __LINE__, __FILE__, $sql);
	}
	
	$row = $db->sql_fetchrow($result);
	$max_groupid = $row['max_id'];
	$next_groupid = $max_groupid + 1;
	
	//
	// Insert our new group    
	//
	$sql = "INSERT INTO " . GROUPS_TABLE . "(group_id, group_type, group_name, group_description, group_single_user, group_moderator) 
            VALUES ($next_groupid, 2, '$weblog_name', '$weblog_desc', 0, $userid);";
	
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Failed to create new user group for new forum', '', __LINE__, __FILE__, $sql);
	}
	
	//
	// Get the user's single_user groupid
	//
	$sql = "SELECT g.group_id 
			FROM " . GROUPS_TABLE . " g, " . USER_GROUP_TABLE . " u  
			WHERE u.group_id = g.group_id 
				AND u.user_id = $userid 
				AND g.group_single_user = 1;";
	
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Failed to obtain single user group info.', '', __LINE__, __FILE__, $sql);
	}
	
	$row = $db->sql_fetchrow($result);
	$single_user_group = intval($row['group_id']);
	
	//
	// Set the user as moderator of their weblog
	//
	$sql = "INSERT INTO " . AUTH_ACCESS_TABLE . " (group_id, forum_id, auth_mod)
		VALUES ($single_user_group, $next_id, 1);";
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Failed to insert the moderator data for the new forum', '', __LINE__, __FILE__, $sql);
	}
	
	//
	// Set the access rights for the group
	//
	$sql = "INSERT INTO " . AUTH_ACCESS_TABLE . " (group_id, forum_id, auth_read, auth_reply, auth_vote, auth_mod) VALUES ($next_groupid, $next_id, $auth_access, $auth_access, $auth_access, 0);";
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Failed to insert the moderator data for the new forum', '', __LINE__, __FILE__, $sql);
	}
	
	//
	// Add our user to the new group
	//
	$sql = "INSERT INTO " . USER_GROUP_TABLE . "(group_id, user_id) 
		VALUES ($next_groupid, $userid);";
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Failed to add user to new group', '', __LINE__, __FILE__, $sql);
	}
	
	$weblog_personal_space = '';
	
	// 
	// update the users weblog id
	$sql = "UPDATE " . USERS_TABLE . " SET 
		user_weblog=$next_id, 
		weblog_start_date=" . time() . ", 
		weblog_access='$weblog_access',
		weblog_access2='$weblog_access2',
		weblog_visible='$weblog_visible',
		weblog_group=$next_groupid, 
		weblog_show_entries=$weblog_show_entries,
		weblog_advanced = '$weblog_advanced',
		weblog_style = '$weblog_style'
		WHERE user_id=$userid";
	
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Failed to update user weblog info', '', __LINE__, __FILE__, $sql);
	}
	
	return $next_id;
}

function update_user_weblog($weblogid, $weblog_name, $weblog_desc, $weblog_cat, $weblog_access, $weblog_visible, $weblog_group, $weblog_show_entries, $weblog_comment_name, $weblog_post_comment_name, $weblog_show_profile, $weblog_show_desc, $weblog_contact_info, $weblog_show_info, $weblog_advanced, $weblog_style, $weblog_access2)
{
	global $db, $weblog_auth_types;
	
	$auth_access = 0;

	switch ( $weblog_auth_types[$weblog_access] )
	{
		case WEBLOG_ALL:
			$auth_read = AUTH_ALL;
			break;
		case WEBLOG_REGISTERED:
			$auth_read = AUTH_REG;
			break;
		case WEBLOG_USERGROUP:
			$auth_read = AUTH_ACL;
			$auth_access = 1;
			break;
		case WEBLOG_OWNER:
			$auth_read = AUTH_MOD;
			break;
		default:
			message_die(GENERAL_ERROR, 'Invalid Weblog access level.', '', __LINE__, __FILE__, $weblog_access);
			break;
	}
		
	

	switch ( $weblog_auth_types[$weblog_access2] )
	{
		case WEBLOG_ALL:
			$auth_reply = AUTH_ALL;
			$auth_vote = AUTH_ALL;
			break;
		case WEBLOG_REGISTERED:
			$auth_reply = AUTH_REG;
			$auth_vote = AUTH_REG;
			break;
		case WEBLOG_USERGROUP:
			$auth_reply = AUTH_ACL;
			$auth_vote = AUTH_ACL;
			$auth_access = 1;
			break;
		case WEBLOG_OWNER:
			$auth_reply = AUTH_MOD;
			$auth_vote = AUTH_MOD;
			break;
		default:
			message_die(GENERAL_ERROR, 'Invalid Weblog access level (2).', '', __LINE__, __FILE__, $weblog_access);
			break;
	}

	$forum_name = str_replace("'", "\'", $weblog_name);
	$forum_name = stripslashes($forum_name);
	$forum_name = htmlspecialchars(trim($forum_name));
	$forum_desc = str_replace("'", "\'", $weblog_desc);
	$forum_desc = stripslashes($forum_desc);
	$forum_desc = htmlspecialchars(trim($forum_desc));

	$sql = "UPDATE " . FORUMS_TABLE . " SET 
		cat_id = $weblog_cat, 
		forum_name='$forum_name', 
		forum_desc='$forum_desc',  
		auth_read = $auth_read, 
		auth_reply = $auth_reply, 
		auth_vote = $auth_vote
		WHERE forum_id=$weblogid;";
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Failed to update weblog info.', '', __LINE__, __FILE__, $sql);
	}
	
	//
	// Set the access rights for the group
	//
	$sql = "UPDATE " . AUTH_ACCESS_TABLE . " SET 
		auth_read = $auth_access, 
		auth_reply = $auth_access, 
		auth_vote = $auth_access 
		WHERE forum_id = $weblogid AND group_id = $weblog_group;";
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Failed to insert the moderator data for the new forum', '', __LINE__, __FILE__, $sql);
	}

	$weblog_style = ( $weblog_style ) ? $weblog_style : '';

	$sql = "UPDATE " . USERS_TABLE . " SET 
		weblog_access = $weblog_access,
		weblog_access2 = $weblog_access2,
		weblog_group = $weblog_group, 
		weblog_visible = $weblog_visible,
		weblog_show_entries = $weblog_show_entries,
		weblog_comment_name='$weblog_comment_name',
		weblog_post_comment_name='$weblog_post_comment_name',
		weblog_show_entries = $weblog_show_entries,
		weblog_advanced = $weblog_advanced,
		weblog_show_profile = $weblog_show_profile,
		weblog_show_desc = $weblog_show_desc,
		weblog_contact_info = $weblog_contact_info,
		weblog_show_info=$weblog_show_info,
		weblog_style = '$weblog_style'
		WHERE user_weblog = $weblogid";
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Failed to update user weblog info.', '', __LINE__, __FILE__, $sql);
	}

	return true;
}

function delete_user_weblog($weblogid, $weblog_group)
{
	global $db, $phpbb_root_path, $phpEx;
	
	//
	// remove ALL user_group info for the weblog group
	$sql = "DELETE FROM " . USER_GROUP_TABLE . " 
		WHERE group_id = $weblog_group;";
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Failed to delete user_group info.', '', __LINE__, __FILE__, $sql);
	}
	
	//
	// Remove the group created specifically for the weblog
	//
	$sql = "DELETE FROM " . GROUPS_TABLE . " 
		WHERE group_id = $weblog_group;";
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Failed to delete group info.', '', __LINE__, __FILE__, $sql);
	}
	
	include($phpbb_root_path . "includes/prune.$phpEx");
	prune($weblogid, 0); // Delete everything from forum
	
	//
	// Remove the weblog forum
	//
	$sql = "DELETE FROM " . FORUMS_TABLE . " 
		WHERE forum_id = $weblogid;";
	
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Failed to delete forum info.', '', __LINE__, __FILE__, $sql);
	}
	
	//
	// Remove ALL auth_access details for the weblog
	//
	$sql = "DELETE FROM " . AUTH_ACCESS_TABLE . " 
		WHERE forum_id = $weblogid";
	
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Failed to delete auth_access info.', '', __LINE__, __FILE__, $sql);
	}
	
	$sql = "DELETE FROM " . PRUNE_TABLE . " 
		WHERE forum_id = $weblogid";
	if( !$result = $db->sql_query($sql) )
	{
		message_die(GENERAL_ERROR, "Couldn't delete forum prune information!", "", __LINE__, __FILE__, $sql);
	}
	
	// 
	// Finally, update the user to remove all traces of the weblog
	//
	$sql = "UPDATE " . USERS_TABLE . " SET 
		user_weblog = 0, 
		weblog_start_date = 0, 
		weblog_show_entries = 5, 
		weblog_access = 0, 
		weblog_group = 0,
		weblog_show_profile = 1,
		weblog_show_desc = 1,
		weblog_contact_info = 1,
		weblog_show_info = 1,
		weblog_advanced = 0,
		weblog_visible = 1
		WHERE user_weblog = $weblogid";
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Failed to delete forum info.', '', __LINE__, __FILE__, $sql);
	}

	return true;
}

function weblog_preparse ($webpage)
{
	// Let's "pre-parse" some of this stuff to make it less mind-boggling for the average user.
	$webpage = str_replace('\"', '"', $weblog_page);
	$webpage = str_replace("\'", "'", $webpage);
	$webpage = str_replace('{SITENAME}', $board_config['sitename'], $webpage);
	$webpage = str_replace('{SITE_DESCRIPTION}', $board_config['site_desc'], $webpage);
	$webpage = str_replace('{USERNAME}', $userdata['username'], $webpage);
	$webpage = str_replace('{ADMIN_LINK}', '', $webpage);
	$webpage = str_replace('{TRANSLATION_INFO}', '', $webpage);
	$webpage = str_replace('{S_CONTENT_DIRECTION}', $lang['DIRECTION'], $webpage);
	$webpage = str_replace('{S_CONTENT_ENCODING}', $lang['ENCODING'], $webpage);
	$webpage = str_replace('{NAV_LINKS}', $nav_links_html, $webpage);

	$webpage = str_replace('{L_INDEX}', sprintf($lang['Forum_Index'], $board_config['sitename']), $webpage);
	$webpage = str_replace('{L_WEBLOGS}', $lang['Weblogs'], $webpage);
	$webpage = str_replace('{L_FAQ}', $lang['FAQ'], $webpage);
	$webpage = str_replace('{L_SEARCH}', $lang['Search'], $webpage);
	$webpage = str_replace('{L_MEMBERLIST}', $lang['Memberlist'], $webpage);
	$webpage = str_replace('{L_USERGROUPS}', $lang['Usergroups'], $webpage);
	$webpage = str_replace('{L_REGISTER}', $lang['Register'], $webpage);
	$webpage = str_replace('{L_MYWEBLOG}', $lang['Weblog_MyWeblog'], $webpage);
	$webpage = str_replace('{L_PROFILE}', $lang['Profile'], $webpage);
	$webpage = str_replace('{L_LOGIN_LOGOUT}', $l_login_logout, $webpage);
	$webpage = str_replace('{L_JOINED}', $lang['Joined'], $webpage);
	$webpage = str_replace('{L_LOCATION}', $lang['Location'], $webpage);
	$webpage = str_replace('{L_OCCUPATION}', $lang['Occupation'], $webpage);
	$webpage = str_replace('{L_INTERESTS}', $lang['Interests'], $webpage);
	$webpage = str_replace('{L_WEBLOG_STARTED}', $lang['Weblog_started'], $webpage);
	$webpage = str_replace('{L_BIRTHDAY}', $lang['Birthday'], $webpage);
	$webpage = str_replace('{L_AGE}', $lang['Age'], $webpage);
	$webpage = str_replace('{L_ZODIAC}', $lang['Zodiac'], $webpage);
	$webpage = str_replace('{L_LOGON}', $lang['Last_logon'], $webpage);
	$webpage = str_replace('{L_VIEW_ENTRIES}', $lang['View_All_Entries'], $webpage);
	$webpage = str_replace('{L_WEBLOG}', $lang['Weblog'], $webpage);
	$webpage = str_replace('{L_WEBLOG_OWNER}', $lang['Weblog_Owner'], $webpage);
	$webpage = str_replace('{L_CONTACT}', $lang['Contact'], $webpage);
	$webpage = str_replace('{L_EMAIL_ADDRESS}', $lang['Email_address'], $webpage);
	$webpage = str_replace('{L_PM}', $lang['Private_Message'], $webpage);
	$webpage = str_replace('{L_MESSENGER}', $lang['MSNM'], $webpage);
	$webpage = str_replace('{L_YAHOO}', $lang['YIM'], $webpage);
	$webpage = str_replace('{L_AIM}', $lang['AIM'], $webpage);
	$webpage = str_replace('{L_ICQ_NUMBER}', $lang['ICQ'], $webpage);
	$webpage = str_replace('{L_ENTRIES}', $lang['Entries'], $webpage);
	$webpage = str_replace('{L_POSTS}', $lang['Posts'], $webpage);
	$webpage = str_replace('{L_VISITS}', $lang['Weblog_visits'], $webpage);
	$webpage = str_replace('{L_ABOUT_USER}', sprintf($lang['About_user'], $userdata['username']), $webpage);

	$webpage = str_replace('{T_HEAD_STYLESHEET}', $theme_data['head_stylesheet'], $webpage);
	$webpage = str_replace('{T_BODY_BACKGROUND}', $theme_data['body_background'], $webpage);
	$webpage = str_replace('{T_BODY_BGCOLOR}', $theme_data['body_bgcolor'], $webpage);
	$webpage = str_replace('{T_BODY_TEXT}', $theme_data['body_text'], $webpage);
	$webpage = str_replace('{T_BODY_LINK}', $theme_data['body_link'], $webpage);
	$webpage = str_replace('{T_BODY_VLINK}', $theme_data['body_vlink'], $webpage);
	$webpage = str_replace('{T_BODY_ALINK}', $theme_data['body_alink'], $webpage);
	$webpage = str_replace('{T_BODY_HLINK}', $theme_data['body_hlink'], $webpage);
	$webpage = str_replace('{T_TR_COLOR1}', $theme_data['tr_color1'], $webpage);
	$webpage = str_replace('{T_TR_COLOR2}', $theme_data['tr_color2'], $webpage);
	$webpage = str_replace('{T_TR_COLOR3}', $theme_data['tr_color3'], $webpage);
	$webpage = str_replace('{T_TR_CLASS1}', $theme_data['tr_class1'], $webpage);
	$webpage = str_replace('{T_TR_CLASS2}', $theme_data['tr_class2'], $webpage);
	$webpage = str_replace('{T_TR_CLASS3}', $theme_data['tr_class3'], $webpage);
	$webpage = str_replace('{T_TH_CLASS1}', $theme_data['th_class1'], $webpage);
	$webpage = str_replace('{T_TH_CLASS2}', $theme_data['th_class2'], $webpage);
	$webpage = str_replace('{T_TH_CLASS3}', $theme_data['th_class3'], $webpage);
	$webpage = str_replace('{T_TD_COLOR1}', $theme_data['td_color1'], $webpage);
	$webpage = str_replace('{T_TD_COLOR2}', $theme_data['td_color2'], $webpage);
	$webpage = str_replace('{T_TD_COLOR3}', $theme_data['td_color3'], $webpage);
	$webpage = str_replace('{T_TD_CLASS1}', $theme_data['td_class1'], $webpage);
	$webpage = str_replace('{T_TD_CLASS2}', $theme_data['td_class2'], $webpage);
	$webpage = str_replace('{T_TD_CLASS3}', $theme_data['td_class3'], $webpage);
	$webpage = str_replace('{T_FONTFACE1}', $theme_data['fontface1'], $webpage);
	$webpage = str_replace('{T_FONTFACE2}', $theme_data['fontface2'], $webpage);
	$webpage = str_replace('{T_FONTFACE3}', $theme_data['fontface3'], $webpage);
	$webpage = str_replace('{T_FONTSIZE1}', $theme_data['fontsize1'], $webpage);
	$webpage = str_replace('{T_FONTSIZE2}', $theme_data['fontsize2'], $webpage);
	$webpage = str_replace('{T_FONTSIZE3}', $theme_data['fontsize3'], $webpage);
	$webpage = str_replace('{T_FONTCOLOR1}', $theme_data['fontcolor1'], $webpage);
	$webpage = str_replace('{T_FONTCOLOR2}', $theme_data['fontcolor2'], $webpage);
	$webpage = str_replace('{T_FONTCOLOR3}', $theme_data['fontcolor3'], $webpage);
	$webpage = str_replace('{T_SPAN_CLASS1}', $theme_data['span_class1'], $webpage);
	$webpage = str_replace('{T_SPAN_CLASS2}', $theme_data['span_class2'], $webpage);
	$webpage = str_replace('{T_SPAN_CLASS3}', $theme_data['span_class3'], $webpage);

	return $webpage;
}

function mkweblog ($mode, $weblog_style_name, $weblog_style)
{

	global $db;

	if ( $weblog_style >= 1000 )
	{
		$sql = "SELECT style_id, style_name
			FROM " . WEBLOG_STYLE_TABLE . "
				WHERE style_id = " . ($weblog_style-1000);
		if ( !($result = $db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, "Couldn't query weblog styles table", "", __LINE__, __FILE__, $sql);
		}
		$theme_data = $db->sql_fetchrow($result);

		if ( $mode == 2 )
			$file1 = "weblog/custom/" . $theme_data['weblog_file'];
		else if ( $mode == 1 )
			$file1 = "weblog/custom/" . $theme_data['weblog_file_s'];


	}
	else
	{
		$sql = "SELECT *
			FROM " . THEMES_TABLE . "
				WHERE themes_id = $weblog_style";
		if ( !($result = $db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, "Couldn't query themes table", "", __LINE__, __FILE__, $sql);
		}
		$theme_data = $db->sql_fetchrow($result);
		$file1 = file("templates/$weblog_style_name/overall_header.tpl");

		if ( $mode == 2 )
			$file2 = file("templates/$weblog_style_name/weblog_body.tpl");
		else if ( $mode == 1 )
			$file2 = file("templates/$weblog_style_name/weblog_old_body.tpl");
		$file3 = file("templates/$weblog_style_name/overall_footer.tpl");

	}

	$webpage = '';
	for ($i=0; $i<count($file1);$i++)
	{
		$webpage .= $file1[$i];
	}
	for ($i=0; $i<count($file2);$i++)
	{
		$webpage .= $file2[$i];
	}
	for ($i=0; $i<count($file3);$i++)
	{
		$webpage .= $file3[$i];
	}

	$webpage = weblog_preparse($webpage);
	@fputs( $f, $webpage );
	@fclose( $f );
}

function weblog_process_html ($message)
{
	global $board_config, $html_entities_match, $html_entities_replace;
	/*
	$allowed_html_tags = split(',', $board_config['allow_html_tags']);

	$end_html = 0;
	$start_html = 1;
	$tmp_message = '';

	while ($start_html = strpos($message, '<', $start_html))
	{
		$tmp_message .= preg_replace($html_entities_match, $html_entities_replace, substr($message, $end_html + 1, ($start_html - $end_html - 1)));

		if ($end_html = strpos($message, '>', $start_html))
		{
			$length = $end_html - $start_html + 1;
			$hold_string = substr($message, $start_html, $length);

			if (($unclosed_open = strrpos(' ' . $hold_string, '<')) != 1)
			{
				$tmp_message .= preg_replace($html_entities_match, $html_entities_replace, substr($hold_string, 0, $unclosed_open - 1));
				$hold_string = substr($hold_string, $unclosed_open - 1);
			}

			$tagallowed = false;
			for ($i = 0; $i < sizeof($allowed_html_tags); $i++)
			{
				$match_tag = trim($allowed_html_tags[$i]);
				if (preg_match('#^<\/?' . $match_tag . '[> ]#i', $hold_string))
				{
					$tagallowed = (preg_match('#^<\/?' . $match_tag . ' .*?(style[ ]*?=|on[\w]+[ ]*?=)#i', $hold_string)) ? false : true;
				}
			}

			$tmp_message .= ($length && !$tagallowed) ? preg_replace($html_entities_match, $html_entities_replace, $hold_string) : $hold_string;

			$start_html += $length;
		}
		else
		{
			$tmp_message .= preg_replace($html_entities_match, $html_entities_replace, substr($message, $start_html, strlen($message)));

			$start_html = strlen($message);
			$end_html = $start_html;
		}
	}

	if ($end_html != strlen($message) && $tmp_message != '')
	{
		$tmp_message .= preg_replace($html_entities_match, $html_entities_replace, substr($message, $end_html + 1));
	}

	$message = ($tmp_message != '') ? trim($tmp_message) : trim($message);

	$message = str_replace('\"', '"', $message);
	$message = str_replace("\'", "'", $message);
	$message = str_replace('&lt;meta', '<meta', $message);
	$message = str_replace('--&gt;', '-->', $message);
	$message = str_replace('&lt;!--', '<!--', $message);
	$message = str_replace('&lt;span', '<span', $message);
	$message = str_replace('charset={S_CONTENT_ENCODING}"&gt;', 'charset={S_CONTENT_ENCODING}">', $message);
	$message = str_replace('content="text/css"&gt;', 'content="text/css">', $message);
	$message = str_replace('style="line-height:150%"&gt;', 'style="line-height:150%">', $message);
	$message = str_replace('style="line-height:150%" &gt;', 'style="line-height:150%">', $message);
	$message = str_replace('style="line-height: 150%" &gt;', 'style="line-height:150%">', $message);
	$message = str_replace('&lt;!DOCTYPE', '<!DOCTYPE', $message);
	$message = str_replace('Transitional//EN"$gt;', 'Transitional//EN">', $message);
	*/
	$message = str_replace('&gt;', '>', $message);
	$message = str_replace('&lt;', '<', $message);
	$message = str_replace('\"', '"', $message);
	$message = str_replace("\'", "'", $message);
	
	return $message;
}
?>