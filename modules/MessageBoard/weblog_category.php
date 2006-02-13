<?php
/***************************************************************************
 *                                weblog.php
 *                            -------------------
 *   begin                : Saturday, November 1, 2003
 *   copyright            : (C) 2003 Hyperion
 *   email                : vinng86@hotmail.com
 *
 *   $Id: weblog_category.php,v 1.9 2005/01/04 15:04:13 saraj Exp $
 *
 *   modified from Smartor's portal.php by hyperion -
 *      http://www.phpbb.com/phpBB/profile.php?mode=viewprofile&u=6288 *
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

define('IN_PHPBB', true);
$phpbb_root_path = './';
include($phpbb_root_path . 'extension.inc');
include($phpbb_root_path . 'common.'.$phpEx);
include($phpbb_root_path . 'includes/bbcode.'.$phpEx);
include($phpbb_root_path . 'includes/weblogs_common.'.$phpEx);

//
// Start session management
//
$userdata = session_pagestart($user_ip, PAGE_WEBLOG);
init_userprefs($userdata);
//
// End session management
//

//
// Start initial var setup
//
if( isset($HTTP_GET_VARS[POST_WEBLOG_URL]) || isset($HTTP_POST_VARS[POST_WEBLOG_URL]) )
{
	$weblog_id = ( isset($HTTP_GET_VARS[POST_WEBLOG_URL]) ) ? intval($HTTP_GET_VARS[POST_WEBLOG_URL]) : intval($HTTP_POST_VARS[POST_WEBLOG_URL]);
	$weblog_id = intval($weblog_id);
}
else if( isset($HTTP_GET_VARS[POST_FORUM_URL]) || isset($HTTP_POST_VARS[POST_FORUM_URL]) )
{
	$weblog_id = ( isset($HTTP_GET_VARS[POST_FORUM_URL]) ) ? intval($HTTP_GET_VARS[POST_FORUM_URL]) : intval($HTTP_POST_VARS[POST_FORUM_URL]);
	$weblog_id = intval($weblog_id);
}
else
{
	$weblog_id = "";
}
//ADDED for CATEGORY modifications
if( isset($HTTP_GET_VARS[CAT_MODE]) || isset($HTTP_POST_VARS[CAT_MODE]) )
{
	$cat_mod = ( isset($HTTP_GET_VARS[CAT_MODE]) ) ? $HTTP_GET_VARS[CAT_MODE] : $HTTP_POST_VARS[CAT_MODE];
}
else
{
	$cat_mod = "";
}
if( isset($HTTP_GET_VARS['previous']) || isset($HTTP_POST_VARS['previous']) )
{
	$previous = ( isset($HTTP_GET_VARS['previous']) ) ? intval($HTTP_GET_VARS['previous']) : intval($HTTP_POST_VARS['previous']);
	$previous = intval($previous);
}
else
{
	$previous = 0;
}

if( isset($HTTP_GET_VARS['month']) || isset($HTTP_POST_VARS['month']) )
{
	$month = ( isset($HTTP_GET_VARS['month']) ) ? intval($HTTP_GET_VARS['month']) : intval($HTTP_POST_VARS['month']);
	$month = intval($month);
}
else
{
	$month = create_date("m", time(), $board_config['board_timezone']);
}

if( isset($HTTP_GET_VARS['day']) || isset($HTTP_POST_VARS['day']) )
{
	$day = ( isset($HTTP_GET_VARS['day']) ) ? intval($HTTP_GET_VARS['day']) : intval($HTTP_POST_VARS['day']);
	$day = intval($day);
}

if( isset($HTTP_GET_VARS['year']) || isset($HTTP_POST_VARS['year']) )
{
	$year = ( isset($HTTP_GET_VARS['year']) ) ? intval($HTTP_GET_VARS['year']) : intval($HTTP_POST_VARS['year']);
	$year = intval($year);
}
else
{
	$year = create_date("Y", time(), $board_config['board_timezone']);
}

if ( $year <= 1970 )
{
	$year = create_date("Y", time(), $board_config['board_timezone']);
}

$months = array($lang['datetime']['January'],$lang['datetime']['February'],$lang['datetime']['March'],$lang['datetime']['April'],$lang['datetime']['May'],$lang['datetime']['June'], $lang['datetime']['July'], $lang['datetime']['August'], $lang['datetime']['September'], $lang['datetime']['October'], $lang['datetime']['November'], $lang['datetime']['December']);

//
// Check if the user has actually sent a weblog ID with his/her request
// If not give them a nice error page.
//
if( !empty($weblog_id) )
{
	$sql = "SELECT *
		FROM " . FORUMS_TABLE . "
		WHERE forum_id = $weblog_id";
	if( !$result = $db->sql_query($sql) )
	{
		message_die(GENERAL_ERROR, "Couldn't obtain weblogs information.", "", __LINE__, __FILE__, $sql);
	}
}
else
{
	message_die(GENERAL_MESSAGE, 'Weblog_not_exist');
}

//Added for weblog category by Rajkumar
if( isset($HTTP_GET_VARS['wc']) || isset($HTTP_POST_VARS['wc']) )
{
	$blog_cat = ( isset($HTTP_POST_VARS['wc']) ) ? $HTTP_POST_VARS['wc'] : $HTTP_GET_VARS['wc'];
	$blog_cat_sql = " AND t.weblog_cat_id = $blog_cat ";
}
else
{
	$blog_cat = '';
}

//
// If the query doesn't return any rows this isn't a valid weblog. Inform
// the user.
//
if( !($weblog_row = $db->sql_fetchrow($result)) )
{
	message_die(GENERAL_MESSAGE, 'Weblog_not_exist');
}

//Added for weblog category by Rajkumar
if( isset($HTTP_GET_VARS['wc']) || isset($HTTP_POST_VARS['wc']) )
{
	$blog_cat = ( isset($HTTP_POST_VARS['wc']) ) ? $HTTP_POST_VARS['wc'] : $HTTP_GET_VARS['wc'];
	$blog_cat_sql = " AND t.weblog_cat_id = $blog_cat ";
}

//
// Start auth check
//
$is_auth = array();
$is_auth = auth(AUTH_ALL, $weblog_id, $userdata, $weblog_row);

if ( !$is_auth['auth_read'] )
{
	//
	// The user is not authed to read this weblog ...
	//
	$message = $lang['Weblog_not_allowed'];

	message_die(GENERAL_MESSAGE, $message);
}
//
// End of auth check
//


$sql = "SELECT u.*
	FROM " . AUTH_ACCESS_TABLE . " aa, " . USER_GROUP_TABLE . " ug, " . GROUPS_TABLE . " g, " . USERS_TABLE . " u
	WHERE aa.forum_id = $weblog_id
		AND aa.auth_mod = " . TRUE . "
		AND ug.group_id = aa.group_id
		AND g.group_single_user = 1
		AND u.user_id = ug.user_id
		AND g.group_id = aa.group_id
	GROUP BY u.user_id, u.username
	ORDER BY u.user_id";
if ( !($result = $db->sql_query($sql)) )
{
	message_die(GENERAL_ERROR, "Could not obtain weblog owner information for this weblog.", '', __LINE__, __FILE__, $sql);
}

while( $row = $db->sql_fetchrow($result) )
{
	$weblog_ownerdata = get_userdata($row['user_id']);
}

//
// User authorisation levels output
//
$s_auth_can = ( ( $is_auth['auth_post'] ) ? $lang['Weblog_post_can'] : $lang['Weblog_post_cannot'] ) . "<br />";
$s_auth_can .= ( ( $is_auth['auth_reply'] ) ? $lang['Weblog_reply_can'] : $lang['Weblog_reply_cannot'] ) . "<br />";
$s_auth_can .= ( ( $is_auth['auth_edit'] ) ? $lang['Weblog_edit_can'] : $lang['Weblog_edit_cannot'] ) . "<br />";
$s_auth_can .= ( ( $is_auth['auth_delete'] ) ? $lang['Weblog_delete_can'] : $lang['Weblog_delete_cannot'] ) . "<br />";

if ( defined('ATTACH_VERSION') )
{
	attach_build_auth_levels($is_auth, $s_auth_can);
}
//Category modifications starts
$ca_name='';
// Let the weblog owner see the new entry button
if( $is_auth['auth_mod'] )
{
	if($cat_mod=="remove")
	{
		if( isset($HTTP_GET_VARS[WEBLOG_CAT_ID]) )
		{
			$catid = $HTTP_GET_VARS[WEBLOG_CAT_ID];
			$sql="SELECT weblog_cat_id , weblog_cat_name , weblog_cat_desc from ". WEBLOG_CATEGORIES ." WHERE forum_id=$weblog_id and weblog_cat_id=$catid";
			if( !$result = $db->sql_query($sql) )
			{
			        message_die(GENERAL_ERROR, "Couldn't obtain weblog category information.", "", __LINE__, __FILE__, $sql);
			}
			while( $row = $db->sql_fetchrow($result) )
			{
		        	$blog_cat_dat[] = $row;
			}
			$blog_cat_count = count($blog_cat_dat);
			$catname='';
			for ($i = 0; $i < $blog_cat_count; $i++)
			{			
				 $blogcat_desc = $blog_cat_dat[$i]['weblog_cat_desc'];
		        	 $blogcat_name = $blog_cat_dat[$i]['weblog_cat_name'];
			}
			//Checking for topics in that category
			$sql_top="SELECT topic_title from ". TOPICS_TABLE ." WHERE weblog_cat_id = $catid";
			if( !$result = $db->sql_query($sql_top) )
			{
			        message_die(GENERAL_ERROR, "Couldn't obtain weblog category in Topics table.", "", __LINE__, __FILE__, $sql);
			}
			while( $row = $db->sql_fetchrow($result) )
			{
		        	$blog_cat_top[] = $row;
			}
			if(count($blog_cat_top) >= 1)
			{
				$sql_qry="SELECT weblog_cat_id , weblog_cat_name , weblog_cat_desc from ". WEBLOG_CATEGORIES ." WHERE forum_id=$weblog_id and weblog_cat_id <> $catid";
				if( !$result = $db->sql_query($sql_qry) )
				{
				        message_die(GENERAL_ERROR, "Couldn't obtain weblog Undelete category information.", "", __LINE__, __FILE__, $sql);
				}
				while( $row = $db->sql_fetchrow($result) )
				{
			        	$blog_cat_num[] = $row;
				}
				if(count($blog_cat_num) >= 1)
				{
					$blogcat_selected = array();
					$blogcat_select = '<select name="blogcat">';
					for ($i = 0; $i < count($blog_cat_num); $i++)
					{
		        			$blogcat_select .= '    <option value="' . $blog_cat_num[$i]['weblog_cat_id'] . '" ' . $blogcat_selected[$i] . '>' . $blog_cat_num[$i]['weblog_cat_name'] . '</option>';
					}
					$blogcat_select .= '</select>';
					$MOVE_CONTENTS = '<span style=" font-size: 10pt">Move the contents to another category: ' . $blogcat_select. '</span><br />';
					$DELETE_CONTENTS='<span style=" font-size: 10pt"><font color=red> WARNING: This category is in use. </font>  You must move the weblog entries in the category to another category, use the combo-box to select which category should receive the entries.<br /></span>';
					}
				else
				{
					$DELETE_CONTENTS='<span style=" font-size: 10pt"><font color=red> WARNING: There is only one category it is in use. </font><br /></span>';
				}
			}
		}
		else
		{
			        message_die(GENERAL_ERROR, "Couldn't obtain weblog category information.", "", __LINE__, __FILE__, $sql);
		}
		$COMMENT_START="<!--";
		$COMMENT_END="-->";
		$COMMENT_REMOVE_START="<!--";
		$COMMENT_REMOVE_END="-->";	
		$CATEGORY_TITLE="Remove Weblog Category [".$blogcat_name."]";
		$CATEGORY_REMOVE_TITLE='<span style=" font-size: 10pt; color : 000000; line-height:50%"><b>Are you sure you want to remove this weblog category?</b></span><br /><br />';
		$CATEGORY_BUTTON="Yes";
		$CMO = "rmconfirm";
		$CAT_ID_INPUT="<input type=hidden name=ci value=$catid /><input type=button name=NO value=No onclick='javascript:history.go(-1)'/>";

	}
	if($cat_mod=="edit")
	{
		if( isset($HTTP_GET_VARS[WEBLOG_CAT_ID]) )
		{
			$catid = $HTTP_GET_VARS[WEBLOG_CAT_ID];
			$sql="SELECT weblog_cat_id , weblog_cat_name , weblog_cat_desc from ". WEBLOG_CATEGORIES ." WHERE forum_id=$weblog_id and weblog_cat_id=$catid";
			if( !$result = $db->sql_query($sql) )
			{
			        message_die(GENERAL_ERROR, "Couldn't obtain weblog category information.", "", __LINE__, __FILE__, $sql);
			}
			while( $row = $db->sql_fetchrow($result) )
			{
		        	$blog_cat_dat[] = $row;
			}
			$blog_cat_count = count($blog_cat_dat);
			$catname='';
			for ($i = 0; $i < $blog_cat_count; $i++)
			{			
				 $blogcat_desc = $blog_cat_dat[$i]['weblog_cat_desc'];
		        	 $blogcat_name = $blog_cat_dat[$i]['weblog_cat_name'];
			}

		}
		$COMMENT_START="<!--";
		$COMMENT_END="-->";
		$CATEGORY_TITLE="Edit Weblog Category";
		$CATEGORY_BUTTON="Edit Category";
		$CMO = "update";
		$CAT_ID_INPUT="<input type=hidden name=ci value=$catid />";
	}
	if($cat_mod!="edit" && $cat_mod!="remove") 
	{
		$CMO = "add";
		if($cat_mod=="rmconfirm")
		{
			$del_cat_id = $HTTP_POST_VARS[WEBLOG_CAT_ID];
			if( isset($HTTP_POST_VARS['blogcat']) && isset($HTTP_POST_VARS[WEBLOG_CAT_ID]))
			{
				$blog_cat_id = $HTTP_POST_VARS['blogcat'];
				$sql = "UPDATE " . TOPICS_TABLE . " SET weblog_cat_id=$blog_cat_id WHERE weblog_cat_id=$del_cat_id";
				//message_die(GENERAL_MESSAGE,$sql);
				if ($db->sql_query($sql, END_TRANSACTION) )
                	        {
					$CAT_UPDATE='<span class="gensmall" style=" font-size: 10pt ; color : 006699; line-height:150%" >Category contents has been moved !</span>';
					
                        	}
				else
				{
                                 	message_die(GENERAL_ERROR, "Could not add Category", "", __LINE__, __FILE__, $sql);
				}

			}
			$sql = "DELETE FROM " . WEBLOG_CATEGORIES . " WHERE weblog_cat_id=$del_cat_id";
			if ($db->sql_query($sql, END_TRANSACTION) )
                        {
				$CAT_UPDATE='<span class="gensmall" style=" font-size: 10pt ; color : 006699; line-height:150%" >Category has been Deleted !</span>';
                       	}
			else
			{
                               	message_die(GENERAL_ERROR, "Could not add Category", "", __LINE__, __FILE__, $sql);
			}
		}

		if($cat_mod=="add")
		{
			if( isset($HTTP_POST_VARS['cname']) )
			{
				$ca_name = $HTTP_POST_VARS['cname'];
				$ca_desc = $HTTP_POST_VARS['cdesc'];
					
				$sql = "INSERT INTO " . WEBLOG_CATEGORIES . " (weblog_cat_id, forum_id, weblog_cat_name, weblog_cat_desc) VALUES ('','$weblog_id','$ca_name','$ca_desc')";
				if ($db->sql_query($sql, END_TRANSACTION) )
                	        {
					$CAT_UPDATE='<span class="gensmall" style=" font-size: 10pt ; color : 006699; line-height:150%" >New Category has been added !';
					
                        	}
				else
				{
                                 	message_die(GENERAL_ERROR, "Could not add Category", "", __LINE__, __FILE__, $sql);
				}

			}
			if(!$ca_name)
			{
				$message = $lang['Weblog_Cat_Error'] . '<br /><br />' . sprintf($lang['Click_return_login'], "<a href=\"weblog_category.$phpEx?w=$weblog_id\">", '</a>');
	
				message_die(GENERAL_MESSAGE, $message);
			}
		}
		if($cat_mod=="update")
		{
			if( isset($HTTP_POST_VARS['cname']) && isset($HTTP_POST_VARS[WEBLOG_CAT_ID]))
			{
				$catid = $HTTP_POST_VARS[WEBLOG_CAT_ID];
				$ca_name = $HTTP_POST_VARS['cname'];
				$ca_desc = $HTTP_POST_VARS['cdesc'];
					
				$sql = "UPDATE " . WEBLOG_CATEGORIES . " SET weblog_cat_name = '$ca_name', weblog_cat_desc='$ca_desc' where weblog_cat_id='$catid'";
				if ($db->sql_query($sql, END_TRANSACTION) )
                	        {
					$CAT_UPDATE='<span class="gensmall" style=" font-size: 10pt ; color : 006699; line-height:150%" >Category has been Edited !</span>';
                        	}
				else
				{
                                 	message_die(GENERAL_ERROR, "Could not update Category", "", __LINE__, __FILE__, $sql);
				}

			}
			if(!$ca_name)
			{
				$message = $lang['Weblog_Cat_Error'] . '<br /><br />' . sprintf($lang['Click_return_login'], "<a href=\"weblog_category.$phpEx?w=$weblog_id\">", '</a>');
	
				message_die(GENERAL_MESSAGE, $message);
			}
			$CMO = "update";
		}


		$template->assign_block_vars('switch_owner', array());
	
		//ADDED by Rajkumar for Category
		$sql="SELECT weblog_cat_id , weblog_cat_name from ". WEBLOG_CATEGORIES ." WHERE forum_id=$weblog_id";
		if( !$result = $db->sql_query($sql) )
		{
	        	message_die(GENERAL_ERROR, "Couldn't obtain weblog category information.", "", __LINE__, __FILE__, $sql);
		}
		while( $row = $db->sql_fetchrow($result) )
		{
        		$blog_cat_dat[] = $row;
		}
		$blog_cat_count = count($blog_cat_dat);
		$blog_cat_id = array();
		$blog_cat_name = array();
		$catname='';
		for ($i = 0; $i < $blog_cat_count; $i++)
		{	
			$row_class = ( !($i % 2) ) ? altrow1 : altrow2;
			 $blog_cat_id[$i] = $blog_cat_dat[$i]['weblog_cat_id'];
        		 $blog_cat_name[$i] = $blog_cat_dat[$i]['weblog_cat_name'];
	
			 $template->assign_block_vars('postrow', array(
		        	 'NAME' => $blog_cat_name[$i],
			         'EDIT' => '<a href="' . append_sid("weblog_category.$phpEx?" . POST_WEBLOG_URL . "=$weblog_id"."&" . WEBLOG_CAT_ID . "=$blog_cat_id[$i]"."&" . CAT_MODE . "=edit") . '" class="nav">Edit</a>',
        			 'REMOVE' => '<a href="' . append_sid("weblog_category.$phpEx?" . POST_WEBLOG_URL . "=$weblog_id"."&" . WEBLOG_CAT_ID . "=$blog_cat_id[$i]"."&" . CAT_MODE . "=remove") . '" class="nav">Remove</a>',
				 'CLASS' => $row_class)
			);
		}
		$CATEGORY_TITLE="Add a New Weblog Category";
		$CATEGORY_BUTTON="Add Category";
	}
}
else
{
	$new_entry = '';
	$new_edit  = '';
	$COMMENT_START='<!--';
	$COMMENT_END='--!>';
	message_die(GENERAL_MESSAGE,'Could Not obtain Blog Category Information');

	
}


//
// Start output of page
//
define('IN_WEBLOGS', true);
$page_title = $weblog_row['forum_name'];
include($phpbb_root_path . 'includes/page_header.'.$phpEx);

$template->set_filenames(array(
	'body' => 'weblog_category_body.tpl')
);

if ( $weblog_ownerdata['weblog_show_profile'] == WEBLOG_SHOW )
{
	$template->assign_block_vars('switch_show_profile', array());
}
if ( $weblog_ownerdata['weblog_contact_info'] == WEBLOG_SHOW )
{
	$template->assign_block_vars('switch_contact_user', array());
}
if ( $weblog_ownerdata['weblog_show_info'] == WEBLOG_SHOW )
{
	$template->assign_block_vars('switch_show_info', array());
}
if ( $weblog_ownerdata['weblog_show_desc'] == WEBLOG_SHOW )
{
	$template->assign_block_vars('switch_user_center', array());
}
else if ( $weblog_ownerdata['weblog_show_desc'] == WEBLOG_HIDE )
{
	$template->assign_block_vars('switch_user_right', array());
}

// Prepare Avatar Image
$avatar_img = '';
if ( $weblog_ownerdata['user_avatar_type'] && $weblog_ownerdata['user_allowavatar'] )
{
	switch( $weblog_ownerdata['user_avatar_type'] )
	{
		case USER_AVATAR_UPLOAD:
			$avatar_img = ( $board_config['allow_avatar_upload'] ) ? '<img src="' . $board_config['avatar_path'] . '/' . $weblog_ownerdata['user_avatar'] . '" alt="" border="0" />' : '';
			break;
		case USER_AVATAR_REMOTE:
			$avatar_img = ( $board_config['allow_avatar_remote'] ) ? '<img src="' . $weblog_ownerdata['user_avatar'] . '" alt="" border="0" />' : '';
			break;
		case USER_AVATAR_GALLERY:
			$avatar_img = ( $board_config['allow_avatar_local'] ) ? '<img src="' . $board_config['avatar_gallery_path'] . '/' . $weblog_ownerdata['user_avatar'] . '" alt="" border="0" />' : '';
			break;
	}
}


$temp_url = append_sid("privmsg.$phpEx?mode=post&amp;" . POST_USERS_URL . "=" . $weblog_ownerdata['user_id']);
$pm_img = '<img src="' . $images['icon_pm'] . '" alt="' . $lang['Send_private_message'] . '" title="' . $lang['Send_private_message'] . '" border="0" />';
$pm = $lang['Send_private_message'];
$pm_a = '<a href="' . $temp_url . '">';
$pm_b = '</a>';

if ( !empty($weblog_ownerdata['user_viewemail']) || $userdata['user_level'] == ADMIN )
{
	$email_uri = ( $board_config['board_email_form'] ) ? append_sid("profile.$phpEx?mode=email&amp;" . POST_USERS_URL .'=' . $weblog_ownerdata['user_id']) : 'mailto:' . $weblog_ownerdata['user_email'];

	$email_img = '<img src="' . $images['icon_email'] . '" alt="' . $lang['Send_email'] . '" title="' . $lang['Send_email'] . '" border="0" />';
	$email = $lang['Send_email'];
	$email_a = '<a href="' . $email_uri . '">';
	$email_b = '</a>';
}
else
{
	$email_img = '&nbsp;';
	$email = '&nbsp;';
	$email_a = '';
	$email_b = '';
}

$www_img = ( $weblog_ownerdata['user_website'] ) ? '<a href="' . $weblog_ownerdata['user_website'] . '" target="_userwww"><img src="' . $images['icon_www'] . '" alt="' . $lang['Visit_website'] . '" title="' . $lang['Visit_website'] . '" border="0" /></a>' : '&nbsp;';
$www = ( $weblog_ownerdata['user_website'] ) ? '<a href="' . $weblog_ownerdata['user_website'] . '" target="_userwww">' . $weblog_ownerdata['user_website'] . '</a>' : '&nbsp;';

if ( !empty($weblog_ownerdata['user_icq']) )
{
	$icq_status_img = '<a href="http://wwp.icq.com/' . $weblog_ownerdata['user_icq'] . '#pager"><img src="http://web.icq.com/whitepages/online?icq=' . $weblog_ownerdata['user_icq'] . '&img=5" width="18" height="18" border="0" /></a>';
	$icq_img = '<img src="' . $images['icon_icq'] . '" alt="' . $lang['ICQ'] . '" title="' . $lang['ICQ'] . '" border="0" />';
	$icq =  $lang['ICQ'];
	$icq_a = '<a href="http://wwp.icq.com/scripts/search.dll?to=' . $weblog_ownerdata['user_icq'] . '">';
	$icq_b = '</a>';
}
else
{
	$icq_status_img = '&nbsp;';
	$icq_img = '&nbsp;';
	$icq = '&nbsp;';
	$icq_a = '';
	$icq_b = '';
}

$aim_img = ( $weblog_ownerdata['user_aim'] ) ? '<img src="' . $images['icon_aim'] . '" alt="' . $lang['AIM'] . '" title="' . $lang['AIM'] . '" border="0" />' : '&nbsp;';
$aim = ( $weblog_ownerdata['user_aim'] ) ? $lang['AIM'] : '&nbsp;';
$aim_a = ( $weblog_ownerdata['user_aim'] ) ? '<a href="aim:goim?screenname=' . $weblog_ownerdata['user_aim'] . '&amp;message=Hello+Are+you+there?">' : '';
$aim_b = ( $weblog_ownerdata['user_aim'] ) ? '</a>' : '';

$msn_img = ( $weblog_ownerdata['user_msnm'] ) ? $weblog_ownerdata['user_msnm'] : '&nbsp;';
$msn = $msn_img;

$yim_img = ( $weblog_ownerdata['user_yim'] ) ? '<img src="' . $images['icon_yim'] . '" alt="' . $lang['YIM'] . '" title="' . $lang['YIM'] . '" border="0" />' : '';
$yim = ( $weblog_ownerdata['user_yim'] ) ? $lang['YIM'] : '';
$yim_a = ( $weblog_ownerdata['user_yim'] ) ? '<a href="http://edit.yahoo.com/config/send_webmesg?.target=' . $weblog_ownerdata['user_yim'] . '&amp;.src=pg">' : '';
$yim_b = ( $weblog_ownerdata['user_yim'] ) ? '</a>' : '';

//
// Fetch Posts from Weblog Forum
//

// Get the user's clearence levels
$access_level = 0;
if ( $userdata['session_logged_in'] )
{
	$access_level++;
	$group_id = intval($weblog_ownerdata['weblog_group']);
	//
	// Get user information for this group
	//
	$sql = "SELECT ug.group_id, ug.user_id, ug.user_pending
		FROM " . USER_GROUP_TABLE . " ug
		WHERE ug.group_id = $group_id
			AND ug.user_id = " . $userdata['user_id'] . "
		ORDER BY ug.user_id";
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Error getting user list for group', '', __LINE__, __FILE__, $sql);
	}

	$group_members = $db->sql_fetchrowset($result);

	if ( count($group_members) == 1 || $userdata['user_level'] == ADMIN )
	{
		$access_level++;
	}

	if ( $weblog_ownerdata['user_id'] == $userdata['user_id'] || $userdata['user_level'] == ADMIN )
	{
		$access_level++;
	}
}

$entry_sql = 't.topic_first_post_id, t.topic_poster, t.topic_status, t.topic_mood, t.topic_currently1, t.topic_currently2, t.topic_time, t.topic_title, t.topic_replies, t.topic_id, t.topic_auth, p.post_id, p.enable_smilies, u.username, u.user_id, pt.post_text, pt.post_id, pt.bbcode_uid';

if ( defined('ATTACH_VERSION') )
{
	$entry_sql .= ', t.topic_attachment';
}

//
// Fetch Entries
//

//
$template->assign_vars(array(
	'FORUM_NAME' => $weblog_row['forum_name'],
	'L_ABOUT_USER' => sprintf($lang['About_user'], $weblog_ownerdata['username']),
	'L_JOINED' => $lang['Joined'],
	'L_CONTACT' => $lang['Contact'],
	'L_EMAIL_ADDRESS' => $lang['Email_address'],
	'L_EMAIL' => $lang['Email'],
	'L_PM' => $lang['Private_Message'],
	'L_ICQ_NUMBER' => $lang['ICQ'],
	'L_YAHOO' => $lang['YIM'],
	'L_AIM' => $lang['AIM'],
	'L_MESSENGER' => $lang['MSNM'],
	'L_LOCATION' => $lang['Location'],
	'L_OCCUPATION' => $lang['Occupation'],
	'L_INTERESTS' => $lang['Interests'],
	'L_WEBLOG_STARTED' => $lang['Weblog_started'],
	'U_USERPROFILE' => 'profile.php?mode=viewprofile&amp;u=' . $weblog_ownerdata['user_id'] . '',
	'LOCATION' => ( $weblog_ownerdata['user_from'] ) ? $weblog_ownerdata['user_from'] : '&nbsp;',
	'OCCUPATION' => ( $weblog_ownerdata['user_occ'] ) ? $weblog_ownerdata['user_occ'] : '&nbsp;',
	'INTERESTS' => ( $weblog_ownerdata['user_interests'] ) ? $weblog_ownerdata['user_interests'] : '&nbsp;',
	'AVATAR_IMG' => $avatar_img,

	'L_VIEW_ENTRIES' => $lang['View_All_Entries'],
	'L_WEBLOG_OWNER' => $lang['Weblog_Owner'],
	'L_WEBLOG' => $lang['Weblog'],
	'L_WEBLOGS' => $lang['Weblogs'],
	'L_WEBLOG_CATEGORY' => $lang['Weblog_Categories_currently'],
	'L_WEBLOG_CATEGORY_DESC' => 'Description',
	'L_WEBLOG_CATEGORY_BUTTON' => $CATEGORY_BUTTON,
	'L_WEBLOG_CATEGORY_TITLE' => $CATEGORY_TITLE,
	'L_WEBLOG_CATEGORY_REMOVE_TITLE' => $CATEGORY_REMOVE_TITLE,
	'L_WEBLOG_CATEGORY_NAME' => $lang['Weblog_Categories_name'],
	'L_WEBLOG_CATEGORY_EDIT' => $lang['Weblog_Categories_edit'],
	'L_WEBLOG_CATEGORY_REMOVE' => $lang['Weblog_Categories_remove'],
	'L_CAT_NAME' => $blogcat_name,
	'L_CAT_DESC' => $blogcat_desc,
	'L_MOVE_CONTENTS' => $MOVE_CONTENTS,
	'L_DELETE_CONTENTS' => $DELETE_CONTENTS,
	
	'L_ENTRIES' => $lang['Entries'],
	'L_AGE' => $lang['Weblog_age'],
	'L_DAYS' => $lang['Days'],
	'L_POSTS' => $lang['Posts'],
	'L_VISITS' => $lang['Weblog_visits'],
	'L_NEXT_MONTH' => $lang['Weblog_next_month'],
	'L_NEXT_YEAR' => $lang['Weblog_next_year'],
	'L_PREVIOUS_MONTH' => $lang['Weblog_previous_month'],
	'L_PREVIOUS_YEAR' => $lang['Weblog_previous_year'],
	'L_GO' => $lang['Go'],
	'S_CAT_ACTION' => append_sid("weblog_category.$phpEx?" . POST_WEBLOG_URL . '=' . $weblog_id),
	'CAT_HIDDEN' => '<input type="hidden" name="cm" value="' .$CMO. '" />' . $CAT_ID_INPUT,

	'L_CALENDAR' => $lang['Weblog_calendar'],
	'U_MONTH_B' => 'weblog.' . $phpEx . '?f=' . $weblog_id . '&amp;previous=' . $previous . '&amp;month=' . $lastmonth . '&amp;year=' . $year,
	'U_MONTH_F' => 'weblog.' . $phpEx . '?f=' . $weblog_id . '&amp;previous=' . $previous . '&amp;month=' . $nextmonth . '&amp;year=' . $year,
	'U_YEAR_B' => 'weblog.' . $phpEx . '?f=' . $weblog_id . '&amp;previous=' . $previous . '&amp;month=' . $month . '&amp;year=' . ($year-1),
	'U_YEAR_F' => 'weblog.' . $phpEx . '?f=' . $weblog_id . '&amp;previous=' . $previous . '&amp;month=' . $month . '&amp;year=' . ($year+1),
	'U_PAST' => append_sid("weblog2.$phpEx?" . POST_WEBLOG_URL . '=' . $weblog_id),

	'U_WEBLOG_RSS' => append_sid("weblog_rss.$phpEx?" . POST_WEBLOG_URL . '=' . $weblog_id),

	'USERNAME' => $weblog_ownerdata['username'],
	'JOINED' => create_date($lang['DATE_FORMAT'], $weblog_ownerdata['user_regdate'], $board_config['board_timezone']),
	'START_DATE' => create_date($lang['DATE_FORMAT'], $weblog_ownerdata['weblog_start_date'], $board_config['board_timezone']),
	'WEBLOG_DESC' => $weblog_row['forum_desc'],
	'WEBLOG_POSTS' => $weblog_row['forum_posts'],
	'WEBLOG_ENTRIES' => $weblog_row['forum_topics'],
	'WEBLOG_AGE' => intval((time() - $weblog_ownerdata['weblog_start_date'])/(60*60*24)),
	'WEBLOG_VISITS' => $weblog_row['forum_views'],
	'L_COMMENT_START' => $COMMENT_START,
	'L_COMMENT_END' => $COMMENT_END,
	'L_REMOVE_START' => $COMMENT_REMOVE_START,
	'L_REMOVE_END' => $COMMENT_REMOVE_END,
	'L_COMMENT_UPD' => $CAT_UPDATE,

	'MONTH' => $months[$month-1],
	'YEAR' => $year,
	'FORWARD' => ( $previous != 0 && !$day ) ? '<a href="weblog.php?' . POST_WEBLOG_URL . '=' . $weblog_id . '&amp;previous=' . ($previous - $weblog_ownerdata['weblog_show_entries']) . '" class="nav">' . $lang['Weblog_forward'] . '</a>' : $lang['Weblog_forward'],
	'BACK' => ( ($previous + $weblog_ownerdata['weblog_show_entries']) <= count($entry_data) && !$day ) ? '<a href="weblog.php?' . POST_WEBLOG_URL . '=' . $weblog_id . '&amp;previous=' . ($previous + $weblog_ownerdata['weblog_show_entries']) . '" class="nav">' . $lang['Weblog_back'] . '</a>' : $lang['Weblog_back'],

	'L_LOGON' => $lang['Last_logon'],
	'LAST_LOGON' => $last_logon,

	'MOD_VERSION' => $mod_version,

	'PHPBB_VERSION' => '2' . $board_config['version'],
	'TRANSLATION_INFO' => ( isset($lang['TRANSLATION_INFO']) ) ? $lang['TRANSLATION_INFO'] : '',
	'ADMIN_LINK' => $admin_link,

	//'PM_IMG' => $pm_img,
	'PM' => $pm,
	'PM_A' => $pm_a,
	'PM_B' => $pm_b,
	//'EMAIL_IMG' => $email_img,
	'EMAIL' => $email,
	'EMAIL_A' => $email_a,
	'EMAIL_B' => $email_b,
	//'ICQ_STATUS_IMG' => $icq_status_img,
	//'ICQ_IMG' => $icq_img,
	'ICQ' => $icq,
	'ICQ_A' => $icq_a,
	'ICQ_B' => $icq_b,
	//'AIM_IMG' => $aim_img,
	'AIM' => $aim,
	'AIM_A' => $aim_a,
	'AIM_B' => $aim_b,
	//'MSN_IMG' => $msn_img,
	'MSN' => $msn,
	'MSN_A' => $msn_a,
	'MSN_B' => $msn_b,
	//'YIM_IMG' => $yim_img,
	'YIM' => $yim,
	'YIM_A' => $yim_a,
	'YIM_B' => $yim_b,
	//'WWW_IMG' => $www_img,
	'WWW' => $www,

	'S_AUTH_LIST' => $s_auth_can)
);

//
// Generate the page
//
$template->pparse('body');

//include($phpbb_root_path . 'includes/page_tail.'.$phpEx);

?>
