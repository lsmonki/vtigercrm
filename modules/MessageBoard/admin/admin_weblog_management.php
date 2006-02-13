<?php
/***************************************************************************
 *                             admin_weblog_groups.php
 *                            -------------------
 *   Author  		: 	netclectic - Adrian Cockburn - adrian@netclectic.com
 *   Created 		: 	Monday, Sept 23, 2002
 *	 Last Updated	:	Thursday, Oct 04, 2002
 *
 *	 Version		: 	FIND - 0.9.6 BETA
 *
 ***************************************************************************/

define('IN_PHPBB', 1);

if( !empty($setmodules) )
{
	$filename = basename(__FILE__);
	//$forum_module['Weblogs']['Management'] = $filename;

	return;
}

//
// Load default header
//
$no_page_header = TRUE;
$phpbb_root_path = './../';
require($phpbb_root_path . 'extension.inc');
require('./pagestart.' . $phpEx);
include($phpbb_root_path . 'includes/functions_weblog.' . $phpEx);

// include the weblog lang file
$use_lang = ( !file_exists($phpbb_root_path . 'language/lang_' . $board_config['default_lang'] . '/lang_weblog_admin.'.$phpEx) ) ? 'english' : $board_config['default_lang'];
include($phpbb_root_path . 'language/lang_' . $use_lang . '/lang_weblog_admin.' . $phpEx);

$weblog_auth_types = array(0, 1, 2, 3 );

//
// Mode setting
//
if( isset($HTTP_POST_VARS['mode']) || isset($HTTP_GET_VARS['mode']) )
{
	$mode = ( isset($HTTP_POST_VARS['mode']) ) ? $HTTP_POST_VARS['mode'] : $HTTP_GET_VARS['mode'];
}
else
{
	$mode = "";
}

if ( isset($HTTP_POST_VARS['editweblog']) )
{
	$mode = 'editweblog';
}

$forum_id = intval($HTTP_GET_VARS[POST_FORUM_URL]);

switch ( $mode )
{
	case 'deleteweblog':

		$sql = "SELECT u.*
			FROM " . AUTH_ACCESS_TABLE . " aa, " . USER_GROUP_TABLE . " ug, " . GROUPS_TABLE . " g, " . USERS_TABLE . " u
			WHERE aa.forum_id = $forum_id 
				AND aa.auth_mod = " . TRUE . " 
				AND g.group_single_user = 1
				AND ug.group_id = aa.group_id 
				AND g.group_id = aa.group_id 
				AND u.user_id = ug.user_id 
			GROUP BY u.user_id, u.username  
			ORDER BY u.user_id";
		if ( !($result = $db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, "Could not obtain post/user information.", '', __LINE__, __FILE__, $sql);
		}

		while( $row = $db->sql_fetchrow($result) )
		{
			$profiledata = get_userdata($row['user_id']);
		}

		delete_user_weblog($forum_id, $profiledata['weblog_group']);

		$message = $lang['Weblog_deleted'] . "<br /><br />" . sprintf($lang['Click_return_weblogmanagement'], "<a href=\"" . append_sid("admin_weblog_management.$phpEx") . "\">", "</a>") . "<br /><br />" . sprintf($lang['Click_return_admin_index'], "<a href=\"" . append_sid("index.$phpEx?pane=right") . "\">", "</a>");
		
		message_die(GENERAL_MESSAGE, $message);

		break;
	default:
		$template->set_filenames(array(
		    'body' => 'admin/weblog_management_body.tpl')
		);

		break;
}


$sql = "SELECT c.cat_id, c.cat_title, c.cat_order
	FROM " . CATEGORIES_TABLE . " c 
	WHERE c.cat_allow_weblogs = 1  
	ORDER BY c.cat_order";
if( !($result = $db->sql_query($sql)) )
{
	message_die(GENERAL_ERROR, 'Could not query categories list', '', __LINE__, __FILE__, $sql);
}

while( $category_rows[] = $db->sql_fetchrow($result) );

if( ( $total_categories = count($category_rows) ) )
{
	//
	// Define appropriate SQL
	//
	switch(SQL_LAYER)
	{
		case 'postgresql':
			$sql = "SELECT f.*, p.post_time, p.post_username, u.username, u.user_id 
				FROM " . CATEGORIES_TABLE . " c, " . FORUMS_TABLE . " f, " . POSTS_TABLE . " p, " . USERS_TABLE . " u, " . USERS_TABLE . " u2
				WHERE f.cat_id = c.cat_id 
                    AND c.cat_allow_weblogs = 1 
                    AND u2.user_weblog = f.forum_id 
                    AND p.post_id = f.forum_last_post_id 
					AND u.user_id = p.poster_id  
					UNION (
						SELECT f.*, NULL, NULL, NULL, NULL
						FROM " . FORUMS_TABLE . " f
						WHERE NOT EXISTS (
							SELECT p.post_time
								FROM " . POSTS_TABLE . " p
							WHERE p.post_id = f.forum_last_post_id  
						)
					)
					ORDER BY cat_id, forum_order";
			break;

		case 'oracle':
			$sql = "SELECT f.*, p.post_time, p.post_username, u.username, u.user_id 
				FROM " . CATEGORIES_TABLE . " c, " . FORUMS_TABLE . " f, " . POSTS_TABLE . " p, " . USERS_TABLE . " u, " . USER_TABLE . " u2
				WHERE f.cat_id = c.cat_id 
                    AND c.cat_allow_weblogs = 1 
                    AND u2.user_weblog = f.forum_id
                    AND p.post_id = f.forum_last_post_id(+)
					AND u.user_id = p.poster_id(+)
				ORDER BY f.cat_id, f.forum_order";
			break;

		default:
			$sql = "SELECT f.*, p.post_time, p.post_username, u.username, u.user_id
				FROM (( " . CATEGORIES_TABLE . " c
	           		INNER JOIN " . FORUMS_TABLE . " f ON f.cat_id = c.cat_id 
        			INNER JOIN " . USERS_TABLE . " u2 ON u2.user_weblog = f.forum_id 
				LEFT JOIN " . POSTS_TABLE . " p ON p.post_id = f.forum_last_post_id )
				LEFT JOIN " . USERS_TABLE . " u ON u.user_id = p.poster_id )
                		WHERE c.cat_allow_weblogs = 1 
				ORDER BY f.cat_id, f.forum_order";
			break;
	}
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Could not query forums information', '', __LINE__, __FILE__, $sql);
	}

	$forum_data = $db->sql_fetchrowset($result);

	if ( !($total_forums = count($forum_data)) )
	{
		message_die(GENERAL_MESSAGE, $lang['No_weblogs']);
	}
}
else
{
	message_die(GENERAL_MESSAGE, $lang['No_weblogs']);
}

$db->sql_freeresult($result);



$template->assign_vars(array(
    'L_WEBLOG_MANAGEMENT' => $lang['Weblog_Management'],
    'L_WEBLOG_MANAGEMENT_EXPLAIN' => $lang['Weblog_Management_Explain'],
    'L_WEBLOGS' => $lang['Weblogs'],
    'L_WEBLOG_REMOVE' => $lang['Weblog_remove'],
    'L_WEBLOG_EDIT' => $lang['Weblog_edit'],
    'L_WEBLOG' => $lang['Weblog'],
    'L_WEBLOG_OWNER' => $lang['Weblog_Owner'],
    'L_CHECK_ALL' => $lang['check_all'],
    'L_UNCHECK_ALL' => $lang['uncheck_all'],
    'S_GROUP_SELECT' => $select_list,
    )
);

for($i = 0; $i < count($forum_data); $i++)
{
	$forum_id = $forum_data[$i]['forum_id'];

	$sql = "SELECT u.*
		FROM " . AUTH_ACCESS_TABLE . " aa, " . USER_GROUP_TABLE . " ug, " . GROUPS_TABLE . " g, " . USERS_TABLE . " u
		WHERE aa.forum_id = " . $forum_id . " 
			AND aa.auth_mod = " . TRUE . " 
			AND g.group_single_user = 1
			AND ug.group_id = aa.group_id 
			AND g.group_id = aa.group_id 
			AND u.user_id = ug.user_id 
		GROUP BY u.user_id, u.username  
		ORDER BY u.user_id";

	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, "Could not obtain post/user information.", '', __LINE__, __FILE__, $sql);
	}

	while( $row = $db->sql_fetchrow($result) )
	{
		$weblog_user_data[] = $row;
	}

   	$template->assign_block_vars('weblog_row', array(
        'WEBLOG_ID' => $forum_data[$i]['forum_id'],
        'WEBLOG_NAME' => $forum_data[$i]['forum_name'],
        'WEBLOG_DESC' => $forum_data[$i]['forum_desc'],
	  'WEBLOG_OWNER' => $weblog_user_data[$i]['username'],
	  'U_OWNER' => $phpbb_root_path . 'profile.' . $phpEx . '?mode=viewprofile&amp;u=' . $weblog_user_data[$i]['user_id'],
        'U_WEBLOG' => $phpbb_root_path . 'weblog.' . $phpEx . '?' . POST_FORUM_URL . '=' . $forum_data[$i]['forum_id'],
	  'U_EDIT' => append_sid("admin_weblog_edit.$phpEx?mode=editweblog&amp;" . POST_FORUM_URL . "=$forum_id"),
	  'U_REMOVE' => append_sid("admin_weblog_management.$phpEx?mode=deleteweblog&amp;" . POST_FORUM_URL . "=$forum_id"),
        )
    );
}
$db->sql_freeresult($result);


include('./page_header_admin.'.$phpEx);

$template->pparse('body');

include('./page_footer_admin.'.$phpEx);
?>
