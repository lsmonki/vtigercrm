<?php
/***************************************************************************
 *                               weblogs.php
 *                            ------------------
 *   begin                : Saturday, Feb 13, 2001
 *   copyright            : (C) 2001 The phpBB Group
 *   email                : support@phpbb.com
 *
 *   $Id$
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

define('IN_PHPBB', true);
//$phpbb_root_path = './';
include($phpbb_root_path . 'extension.inc');
include($phpbb_root_path . 'common.'.$phpEx);
include($phpbb_root_path . 'includes/weblogs_common.'.$phpEx);

# Added by Godwin -->

include_once ($phpbb_root_path . 'mods/phpbb_fetch_all/common.' . $phpEx);
include_once ($phpbb_root_path . 'mods/phpbb_fetch_all/stats.' . $phpEx);
include_once ($phpbb_root_path . 'mods/phpbb_fetch_all/users.' . $phpEx);
include_once ($phpbb_root_path . 'mods/phpbb_fetch_all/polls.' . $phpEx);
include_once ($phpbb_root_path . 'mods/phpbb_fetch_all/posts.' . $phpEx);
include_once ($phpbb_root_path . 'mods/phpbb_fetch_all/forums.' . $phpEx);

# Godwin <--

//
// Start session management
//
$userdata = session_pagestart($user_ip, PAGE_WEBLOGS);
init_userprefs($userdata);
//
// End session management
//
//Asign date
$TODAY_DATE = date ("Y-m-d H:i:s");
//
// Sorting stuff
//
$count = ( isset($HTTP_GET_VARS['count']) ) ? intval($HTTP_GET_VARS['count']) : 15;
$start = ( isset($HTTP_GET_VARS['start']) ) ? intval($HTTP_GET_VARS['start']) : 0;
$sort = ( isset($HTTP_GET_VARS['sort']) ) ? intval($HTTP_GET_VARS['sort']) : 0;

if ( !empty($HTTP_POST_VARS['numweblogs']) || !empty($HTTP_GET_VARS['numweblogs']) )
{
	$count = ( !empty($HTTP_POST_VARS['numweblogs']) ) ? $HTTP_POST_VARS['numweblogs'] : $HTTP_GET_VARS['numweblogs'];
}
else
{
	$count = $weblog_config['weblogs_per_page'];
}

if ( !empty($HTTP_POST_VARS['sortmethod']) || !empty($HTTP_GET_VARS['sortmethod']) )
{
	$sort = ( !empty($HTTP_POST_VARS['sort']) ) ? $HTTP_POST_VARS['sort'] : $HTTP_GET_VARS['sort'];
}
else
{
	$sort = $weblog_config['weblog_sort'];
}

if ( !empty($HTTP_POST_VARS['sortorder']) || !empty($HTTP_GET_VARS['sortorder']) )
{
	$order = ( !empty($HTTP_POST_VARS['sortorder']) ) ? $HTTP_POST_VARS['sortorder'] : $HTTP_GET_VARS['sortorder'];
}
else
{
	$order = $weblog_config['weblog_order'];
}

//
// Start page proper
//

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
	// Statistics
	//
	if ( $weblog_config['display_stats'] == 1 )
	{
		$template->assign_block_vars('stats', array());

	/*	$sql = "SELECT f.*, p.post_time, p.post_username, u.*
			FROM (( " . CATEGORIES_TABLE . " c
				INNER JOIN " . FORUMS_TABLE . " f ON f.cat_id = c.cat_id
		      	INNER JOIN " . USERS_TABLE . " u2 ON u2.user_weblog = f.forum_id
				LEFT JOIN " . POSTS_TABLE . " p ON p.post_id = f.forum_last_post_id )
				LEFT JOIN " . USERS_TABLE . " u ON u.user_id = p.poster_id )
			WHERE c.cat_allow_weblogs = 1
				AND u.weblog_visible <> 0
			ORDER BY f.cat_id, f.forum_id
			LIMIT 10";
	*/

# Query modified - To hide hidden post in all places except users myblog page - "and t.topic_auth <> 3 added" - Godwin -->

             	$sql = "SELECT f.*, u.*, p.post_time , t.topic_title , t.topic_views , t.topic_id , t.topic_time from " . FORUMS_TABLE ." f, ". USERS_TABLE ." u , ". POSTS_TABLE ." p , ". TOPICS_TABLE ." t where f.forum_id=u.user_weblog and p.post_id = f.forum_last_post_id and t.topic_id = p.topic_id and u.weblog_visible <> 0 ORDER BY topic_time";

# Godwin <--

		if ( !($result = $db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, 'Could not query forums information', '', __LINE__, __FILE__, $sql);
		}

		while( $row = $db->sql_fetchrow($result) )
		{
			$weblog_data_stat[] = $row;
		}
		$total_forums_stat = count($weblog_data_stat);

		if ( count($weblog_data_stat) == 0 )
		{
			message_die(GENERAL_MESSAGE, $lang['No_weblogs']);
		}

		for ($i = 0; $i < $total_forums_stat; $i++)
		{
			$weblog_id = $weblog_data_stat[$i]['forum_id'];

			//for Popular Articles

# Query modified - To hide hidden post in all places except users myblog page - "and t.topic_auth <> 3 added" - Godwin -->

                        $top_sql="select topic_id from " . TOPICS_TABLE . " where forum_id = $weblog_id and topic_time > UNIX_TIMESTAMP(DATE_SUB(CURRENT_DATE, INTERVAL 7 DAY)) ORDER BY topic_views DESC limit 1";

# Godwin <--

			if ( !($result = $db->sql_query($top_sql)) )
			{
				message_die(GENERAL_ERROR, 'Could not query forum topic information', '', __LINE__, __FILE__, $top_sql);
			}
			while( $row = $db->sql_fetchrow($result) )
			{
				$weblog_late_topics_stat[] = $row;
			}
			//Popular Article ends
		}
		$total_topics = count($weblog_late_topics_stat);

		//get the Popular Article
		for ($j=0; $j < $total_topics; $j++)
		{
			$top_id = $weblog_late_topics_stat[$j]['topic_id'];
			echo $topic_id;

# Query modified - To hide hidden post in all places except users myblog page - "and t.topic_auth <> 3 added" - Godwin -->

                        $topic_sql="select t.forum_id, t.topic_id, t.topic_views, t.topic_title, u.username from " . TOPICS_TABLE . " t, " . USERS_TABLE . " u where t.topic_id = $top_id and t.topic_poster = u.user_id and t.topic_auth <> 3";

# Godwin <--

			if ( !($result = $db->sql_query($topic_sql)) )
			{
				message_die(GENERAL_ERROR, 'Could not query forum topic information', '', __LINE__, __FILE__, $sql_qry);
			}
			while( $row = $db->sql_fetchrow($result) )
			{
				$weblog_latest_topics_stat[$j] = $row;
			}
		}
		//
# Query modified - To hide hidden post in all places except users myblog page - "and t.topic_auth <> 3
# and u.weblog_visible <> 0 added" - Godwin -->

                $sql_lst_qry= "select t.topic_id, t.topic_title, t.topic_time, u.username from " . TOPICS_TABLE . " t, " . USERS_TABLE . " u where t.topic_poster=u.user_id and t.forum_id = u.user_weblog and t.topic_auth <> 3 and u.weblog_visible <> 0 order by topic_time desc limit 15";

# Godwin <--

		if ( !($result = $db->sql_query($sql_lst_qry)) )
		{
			message_die(GENERAL_ERROR, 'Could not query forum latest topic information', '', __LINE__, __FILE__, $sql_lst_qry);
		}
		while( $row = $db->sql_fetchrow($result) )
		{
			$weblog_newest_topics_stat[] = $row;
		}

		$total_newest_topics = count($weblog_newest_topics_stat);

		//
		// Find which forums are visible for this user
		//
		$is_auth_ary = array();
		$is_auth_ary = auth(AUTH_READ, AUTH_LIST_ALL, $userdata, $weblog_data_stat);

		$weblog_owner_data_stat = array();
		$topic_data_stat = array();
		for ($i = 0; $i < $total_forums_stat; $i++)
		{
			$weblog_id = $weblog_data_stat[$i]['forum_id'];

			// Get Weblog Owner Data
			$sql = "SELECT u.user_id, u.username, u.weblog_start_date
				FROM " . AUTH_ACCESS_TABLE . " aa, " . USER_GROUP_TABLE . " ug, " . GROUPS_TABLE . " g, " . USERS_TABLE . " u
				WHERE aa.forum_id = $weblog_id
					AND aa.auth_mod = " . TRUE . "
					AND g.group_single_user = 1
					AND ug.group_id = aa.group_id
					AND g.group_id = aa.group_id
					AND u.user_id = ug.user_id
				GROUP BY u.user_id, u.username
				ORDER BY u.user_id";
			if ( !($result = $db->sql_query($sql)) )
			{
				message_die(GENERAL_ERROR, 'Could not query forum moderator information', '', __LINE__, __FILE__, $sql);
			}

			while( $row = $db->sql_fetchrow($result) )
			{
				$weblog_owner_data_stat[$i] = $row;
			}

			$sql = "SELECT f.forum_id, t.topic_time, t.topic_id
				FROM " . TOPICS_TABLE . " t, " . FORUMS_TABLE . " f
				WHERE t.forum_id = $weblog_id
					AND f.forum_id = t.forum_id
				ORDER BY f.forum_id";
			if ( !($result = $db->sql_query($sql)) )
			{
				message_die(GENERAL_ERROR, 'Could not query forums information', '', __LINE__, __FILE__, $sql);
			}

			while( $row = $db->sql_fetchrow($result) )
			{
				$topic_data_stat[$i] = $row;
			}
		}
		$stat_mostpopular = array();
		$stat_entries = array();
		$stat_lastupdated = array();
		$stat_newest = array();

		$stat_mostpopular_name = array();
		$stat_entries_name = array();
		$stat_lastupdated_name = array();
		$stat_newest_name = array();

		$stat_mostpopular_owner = array();
		$stat_mostpopular_views = array();
		$stat_entries_owner = array();
		$stat_lastupdated_owner = array();
		$stat_newest_owner = array();

		// Weblog IDs for linking
		$stat_mostpopular_id = array();
		$stat_entries_id = array();
		$stat_lastupdated_id = array();
		$stat_newest_id = array();
		//Added to display Newest topics
		for ($i = 0; $i < $total_newest_topics; $i++)
		{
			$topic_id = $weblog_newest_topics_stat[$i]['topic_id'];
			$stat_lastupdated[$i] = $weblog_newest_topics_stat[$i]['topic_time'];
			$stat_lastupdated_name[$i] = $weblog_newest_topics_stat[$i]['topic_title'];
			$stat_lastupdated_owner[$i] = $weblog_newest_topics_stat[$i]['username'];
			$stat_lastupdated_id[$i] = $weblog_newest_topics_stat[$i]['topic_id'];

		}
		// Bleh Bubble Sort (Order of n squared!). Too tired to think :-P
		/* Bubble sort commented as it is already captured in the query - Rajkumar
		for ($i = 0; $i < $total_newest_topics; $i++)
		{
			for ( $j = 0; $j < $total_newest_topics; $j++ )
			{
				// Most Popular
				if ($stat_lastupdated[$i]  > $stat_lastupdated[$j])
				{
					$temp = $stat_lastupdated[$j];
					$stat_lastupdated[$j] = $stat_lastupdated[$i];
					$stat_lastupdated[$i] = $temp;
					$temp = $stat_lastupdated_name[$j];
					$stat_lastupdated_name[$j] = $stat_lastupdated_name[$i];
					$stat_lastupdated_name[$i] = $temp;
					$temp = $stat_lastupdated_views[$j];
					$stat_lastupdated_views[$j] = $stat_lastupdated_views[$i];
					$stat_lastupdated_views[$i] = $temp;
					$temp = $stat_lastupdated_owner[$j];
					$stat_lastupdated_owner[$j] = $stat_lastupdated_owner[$i];
					$stat_lastupdated_owner[$i] = $temp;
					$temp = $stat_lastupdated_id[$j];
					$stat_lastupdated_id[$j] = $stat_lastupdated_id[$i];
					$stat_lastupdated_id[$i] = $temp;
				}
			}
		} */
		//Added to display most popular topics
		for ($i = 0; $i < $total_topics; $i++)
		{
			$forum_id = $weblog_latest_topics_stat[$i]['forum_id'];
			$stat_mostpopular[$i] = $weblog_latest_topics_stat[$i]['topic_views'];
			$stat_mostpopular_name[$i] = $weblog_latest_topics_stat[$i]['topic_title'];
			$stat_mostpopular_owner[$i] = $weblog_latest_topics_stat[$i]['username'];
			$stat_mostpopular_id[$i] = $weblog_latest_topics_stat[$i]['topic_id'];
			$stat_mostpopular_views[$i] = $weblog_latest_topics_stat[$i]['topic_views'];

		}
		// Bleh Bubble Sort (Order of n squared!). Too tired to think :-P
		for ($i = 0; $i < $total_topics; $i++)
		{
			for ( $j = 0; $j < $total_topics; $j++ )
			{
				// Most Popular
				if ($stat_mostpopular[$i]  > $stat_mostpopular[$j])
				{
					$temp = $stat_mostpopular[$j];
					$stat_mostpopular[$j] = $stat_mostpopular[$i];
					$stat_mostpopular[$i] = $temp;
					$temp = $stat_mostpopular_name[$j];
					$stat_mostpopular_name[$j] = $stat_mostpopular_name[$i];
					$stat_mostpopular_name[$i] = $temp;
					$temp = $stat_mostpopular_views[$j];
					$stat_mostpopular_views[$j] = $stat_mostpopular_views[$i];
					$stat_mostpopular_views[$i] = $temp;
					$temp = $stat_mostpopular_owner[$j];
					$stat_mostpopular_owner[$j] = $stat_mostpopular_owner[$i];
					$stat_mostpopular_owner[$i] = $temp;
					$temp = $stat_mostpopular_id[$j];
					$stat_mostpopular_id[$j] = $stat_mostpopular_id[$i];
					$stat_mostpopular_id[$i] = $temp;
				}
			}
		}

		for ($i = 0; $i < $total_forums_stat; $i++)
		{
			$weblog_id = $weblog_data_stat[$i]['forum_id'];

			$stat_entries[$i] = $weblog_data_stat[$i]['forum_topics'];
			$stat_entries_name[$i] = $weblog_data_stat[$i]['forum_name'];
			$stat_entries_owner[$i] = $weblog_owner_data_stat[$i]['username'];
			$stat_entries_id[$i] = $weblog_data_stat[$i]['forum_id'];

			$stat_newest[$i] = $weblog_data_stat[$i]['forum_views'];
			$stat_newest_name[$i] = $weblog_data_stat[$i]['forum_name'];
			$stat_newest_owner[$i] = $weblog_owner_data_stat[$i]['username'];
			$stat_newest_id[$i] = $weblog_data_stat[$i]['forum_id'];
		}

		//
		// Insertion Sort
		//

		// Most number of Entries
		$numSorted = 1;
		$index = 0;

		while ($numSorted < $total_forums_stat)
		{
			$temp1 = $stat_entries[$numSorted];
			$temp2 = $stat_entries_name[$numSorted];
			$temp3 = $stat_entries_owner[$numSorted];
			$temp4 = $stat_entries_id[$numSorted];

			for ( $index = $numSorted; $index > 0; $index-- )
			{
				if ($temp1 > $stat_entries[$index-1])
				{
					$stat_entries[$index] = $stat_entries[$index-1];
					$stat_entries_name[$index] = $stat_entries_name[$index-1];
					$stat_entries_owner[$index] = $stat_entries_owner[$index-1];
					$stat_entries_id[$index] = $stat_entries_id[$index-1];
				}
				else
				{
					break;
				}
			}
			$stat_entries[$index] = $temp1;
			$stat_entries_name[$index] = $temp2;
			$stat_entries_owner[$index] = $temp3;
			$stat_entries_id[$index] = $temp4;
			$numSorted++;
		}


	/*	// Most Popular
		$numSorted = 1;
		$index = 0;

		while ($numSorted < $total_forums_stat)
		{
			$temp1 = $stat_mostpopular[$numSorted];
			$temp2 = $stat_mostpopular_name[$numSorted];
			$temp3 = $stat_mostpopular_owner[$numSorted];
			$temp4 = $stat_mostpopular_id[$numSorted];

			for ( $index = $numSorted; $index > 0; $index-- )
			{
				if ($temp1 < $stat_mostpopular[$index-1])
				{
					$stat_mostpopular[$index] = $stat_mostpopular[$index-1];
					$stat_mostpopular_name[$index] = $stat_mostpopular_name[$index-1];
					$stat_mostpopular_owner[$index] = $stat_mostpopular_owner[$index-1];
					$stat_mostpopular_id[$index] = $stat_mostpopular_id[$index-1];
				}
				else
				{
					break;
				}
			}
			$stat_mostpopular[$index] = $temp1;
			$stat_mostpopular_name[$index] = $temp2;
			$stat_mostpopular_owner[$index] = $temp3;
			$stat_mostpopular_id[$index] = $temp4;
			$numSorted++;
		}
*/
/*		// Last Updated
		$numSorted = 1;
		$index = 0;

		while ($numSorted < $total_forums_stat)
		{
			$temp1 = $stat_lastupdated[$numSorted];
			$temp2 = $stat_lastupdated_name[$numSorted];
			$temp3 = $stat_lastupdated_owner[$numSorted];
			$temp4 = $stat_lastupdated_id[$numSorted];

			for ( $index = $numSorted; $index > 0; $index-- )
			{
				if ($temp1 > $stat_lastupdated[$index-1])
				{
					$stat_lastupdated[$index] = $stat_lastupdated[$index-1];
					$stat_lastupdated_name[$index] = $stat_lastupdated_name[$index-1];
					$stat_lastupdated_owner[$index] = $stat_lastupdated_owner[$index-1];
					$stat_lastupdated_id[$index] = $stat_lastupdated_id[$index-1];
				}
				else
				{
					break;
				}
			}
			$stat_lastupdated[$index] = $temp1;
			$stat_lastupdated_name[$index] = $temp2;
			$stat_lastupdated_owner[$index] = $temp3;
			$stat_lastupdated_id[$index] = $temp4;
			$numSorted++;
		}
*/
		// Newest Weblog
		$numSorted = 1;
		$index = 0;

		while ($numSorted < $total_forums_stat)
		{
			$temp1 = $stat_newest[$numSorted];
			$temp2 = $stat_newest_name[$numSorted];
			$temp3 = $stat_newest_owner[$numSorted];
			$temp4 = $stat_newest_id[$numSorted];

			for ( $index = $numSorted; $index > 0; $index-- )
			{
				if ($temp1 > $stat_newest[$index-1])
				{
					$stat_newest[$index] = $stat_newest[$index-1];
					$stat_newest_name[$index] = $stat_newest_name[$index-1];
					$stat_newest_owner[$index] = $stat_newest_owner[$index-1];
					$stat_newest_id[$index] = $stat_newest_id[$index-1];
				}
				else
				{
					break;
				}
			}
			$stat_newest[$index] = $temp1;
			$stat_newest_name[$index] = $temp2;
			$stat_newest_owner[$index] = $temp3;
			$stat_newest_id[$index] = $temp4;
			$numSorted++;
		}

		//
		// Output all the statistics
		//
		for ($i = 0; $i < 15; $i++)
		{
			//$row_class = ( !($i % 2) ) ? $theme['td_class1'] : $theme['td_class2'];
			$row_class = ( !($i % 2) ) ? altrow1 : altrow2;

			if ( $stat_lastupdated[$i] )
			{
				$template->assign_block_vars('stats.lastupdatedrow', array(
					'NAME' => '<a href="' . append_sid("weblog_entry.$phpEx?" . POST_ENTRY_URL . "=$stat_lastupdated_id[$i]") . '" class="nav">' . $stat_lastupdated_name[$i] . '</a>',
					'OWNER' => $stat_lastupdated_owner[$i],
					'DATE' => create_date($board_config['default_dateformat'], $stat_lastupdated[$i], $board_config['board_timezone']),
					'CLASS' => $row_class)
				);
			}
			else
			{
				$template->assign_block_vars('stats.lastupdatedrow', array(
					'NAME' => '&nbsp;',
					'OWNER' => '&nbsp;',
					'DATE' => '&nbsp;',
					'CLASS' => $row_class)
				);
			}
			if ( $stat_newest[$i] )
			{
				$template->assign_block_vars('stats.newestrow', array(
					'NAME' => '<a href="' . append_sid("weblog.$phpEx?" . POST_WEBLOG_URL . "=$stat_newest_id[$i]") . '" class="nav">' . $stat_newest_name[$i] . '</a>',
					'OWNER' => $stat_newest_owner[$i],
					//'DATE' => create_date($board_config['default_dateformat'], $stat_newest[$i], $board_config['board_timezone']),
					'VIEWS' => $stat_newest[$i],
					'CLASS' => $row_class)
				);
			}
			else
			{
				$template->assign_block_vars('stats.newestrow', array(
					'NAME' => '&nbsp;',
					'OWNER' => '&nbsp;',
					'DATE' => '&nbsp;',
					'CLASS' => $row_class)
				);
			}

			if ( $stat_entries[$i] )
			{
				$template->assign_block_vars('stats.mostentriesrow', array(
					'NAME' => '<a href="' . append_sid("weblog.$phpEx?" . POST_WEBLOG_URL . "=$stat_entries_id[$i]") . '" class="nav">' . $stat_entries_name[$i] . '</a>',
					'OWNER' => $stat_entries_owner[$i],
					'ENTRIES' => $stat_entries[$i],
					'CLASS' => $row_class)
				);
			}
			else
			{
				$template->assign_block_vars('stats.mostentriesrow', array(
					'NAME' => '&nbsp;',
					'OWNER' => '&nbsp;',
					'ENTRIES' => '&nbsp;',
					'CLASS' => $row_class)
				);
			}

			if ( $stat_mostpopular[$i] )
			{
				$template->assign_block_vars('stats.mostpopularrow', array(
					'NAME' => '<a href="' . append_sid("weblog_entry.$phpEx?" . POST_ENTRY_URL . "=$stat_mostpopular_id[$i]") . '" class="nav">' . $stat_mostpopular_name[$i] . '</a>',
					'OWNER' => $stat_mostpopular_owner[$i],
					'VISITS' => $stat_mostpopular_views[$i],
					'CLASS' => $row_class)
				);
			}
			else
			{
				$template->assign_block_vars('stats.mostpopularrow', array(
					'NAME' => '&nbsp;',
					'OWNER' => '&nbsp;',
					'VISITS' => '&nbsp;',
					'CLASS' => $row_class)
				);
			}
		}
	}

	//
	// Weblog Listing Code
	//
	$num_weblogs = array(1, 2, 3, 4, 5, 10, 20, 30, 40, 50, 100);
	$select_num_weblogs = '<select name="numweblogs">';
	for($i = 0; $i < count($num_weblogs); $i++)
	{
		$selected = ($count == $num_weblogs[$i]) ? ' selected="selected"' : '';
		$select_num_weblogs .= '<option value="' . $num_weblogs[$i] . '"' . $selected . '>' . $num_weblogs[$i] . '</option>';
	}
	$select_num_weblogs .= '</select>';

	$sort_method = array(1, 2, 3, 4);
	$sort_method_text = array($lang['Weblog_post_time'], $lang['Weblog_started'], $lang['Weblog_forum_name'], $lang['Weblog_username']);
	$select_sort = '<select name="sortmethod">';
	for($i = 0; $i < count($sort_method); $i++)
	{
		$selected = ($sort == $sort_method[$i]) ? ' selected="selected"' : '';
		$select_sort .= '<option value="' . $sort_method[$i] . '"' . $selected . '>' . $sort_method_text[$i] . '</option>';
	}
	$select_sort .= '</select>';

	$sort_order = array('desc', 'asc');
	$sort_order_text = array($lang['Weblog_descending'], $lang['Weblog_ascending']);
	$select_asc_desc = '<select name="sortorder">';
	for($i = 0; $i < count($sort_order); $i++)
	{
		$selected = ($order == $sort_order[$i]) ? ' selected="selected"' : '';
		$select_asc_desc .= '<option value="' . $sort_order[$i] . '"' . $selected . '>' . $sort_order_text[$i] . '</option>';
	}
	$select_asc_desc .= '</select>';

	switch ( $order )
	{
		case 'desc':
			$asc_desc = 'desc';
			break;
		default:
			$asc_desc = '';
			break;
	}

	switch ( $sort )
	{
		case 1:
			$sort = 'p.post_time';
			break;
		case 2:
			$sort = 'u.weblog_start_date';
			break;
		case 3:
			$sort = 'f.forum_name';
			break;
		case 4:
			$sort = 'u.username';
			break;
		default:
			$sort = 'p.post_time';
			break;
	}


	//
	// Find which forums are visible for this user
	//
	$is_auth_ary = array();
	$is_auth_ary = auth(AUTH_READ, AUTH_LIST_ALL, $userdata, $weblog_data);

	//
	// Get Weblog Data
	//
	$sql = "SELECT f.*, p.post_time, p.post_username, u.*
		FROM (( " . CATEGORIES_TABLE . " c
			INNER JOIN " . FORUMS_TABLE . " f ON f.cat_id = c.cat_id
	      	INNER JOIN " . USERS_TABLE . " u2 ON u2.user_weblog = f.forum_id
			LEFT JOIN " . POSTS_TABLE . " p ON p.post_id = f.forum_last_post_id )
			LEFT JOIN " . USERS_TABLE . " u ON u.user_id = p.poster_id )
		WHERE c.cat_allow_weblogs = 1
			AND u.weblog_visible <> 0
		ORDER BY " . $sort . " " . $order . "
		LIMIT " . $start . ", " . $count;

	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Could not query forums information', '', __LINE__, __FILE__, $sql);
	}

	while( $row = $db->sql_fetchrow($result) )
	{
		$weblog_data[] = $row;
	}
	$total_forums = count($weblog_data);
	$template->assign_vars(array(
		'PAGINATION' => generate_pagination("weblogs.$phpEx?count=$count&sort=$sort&order=$order", $total_forums_stat, $count, $start))
	);

	$weblog_owner_data = array();
	$topic_data = array();

	for ($i = 0; $i < $total_forums; $i++)
	{
		$weblog_id = $weblog_data[$i]['forum_id'];
		//
		// Get Weblog Owner Data
		//
		$sql = "SELECT u.user_avatar_type, u.user_allowavatar, u.user_avatar, u.user_id, u.username
			FROM " . AUTH_ACCESS_TABLE . " aa, " . USER_GROUP_TABLE . " ug, " . GROUPS_TABLE . " g, " . USERS_TABLE . " u
			WHERE aa.forum_id = $weblog_id
				AND aa.auth_mod = " . TRUE . "
				AND g.group_single_user = 1
				AND ug.group_id = aa.group_id
				AND g.group_id = aa.group_id
				AND u.user_id = ug.user_id
			ORDER BY aa.forum_id";
		if ( !($result = $db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, 'Could not query forum moderator information', '', __LINE__, __FILE__, $sql);
		}

		while( $row = $db->sql_fetchrow($result) )
		{
			$weblog_owner_data[$i] = $row;
		}

		$access_level = 0;
		if ( $userdata['session_logged_in'] )
		{
			$access_level++;
			$group_id = intval($weblog_owner_data[$i]['weblog_group']);
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
				$access_level++;
			if ( $weblog_owner_data[$i]['user_id'] == $userdata['user_id'] || $userdata['user_level'] == ADMIN )
				$access_level++;
		}
		else
                {
                        $lang['view_post_blog']='';
                        $new_posts_blog['total']='';
                }

		//
		// Get Topic Data
		//
		$sql = "SELECT f.forum_id, t.forum_id, t.topic_time, t.topic_mood, t.topic_currently1, t.topic_currently2, t.topic_time, t.topic_id
			FROM " . TOPICS_TABLE . " t, " . FORUMS_TABLE . " f, " . POSTS_TABLE . " p
			WHERE t.forum_id = $weblog_id
				AND t.forum_id = f.forum_id
				AND t.topic_auth <= " . $access_level . "
			ORDER BY f.forum_id LIMIT 15";
		if ( !($result = $db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, 'Could not query forums information', '', __LINE__, __FILE__, $sql);
		}

		while( $row = $db->sql_fetchrow($result) )
		{
			$topic_data[$i] = $row;
		}

		$avatar_img[$i] = '';
		if ( $weblog_owner_data[$i]['user_avatar_type'] && $weblog_owner_data[$i]['user_allowavatar'] )
		{
			switch( $weblog_owner_data[$i]['user_avatar_type'] )
			{
				case USER_AVATAR_UPLOAD:
					$avatar_img[$i] = ( $board_config['allow_avatar_upload'] ) ? '<img src="' . $board_config['avatar_path'] . '/' . $weblog_owner_data[$i]['user_avatar'] . '" alt="" border="0" />' : '';
					break;
				case USER_AVATAR_REMOTE:
					$avatar_img[$i] = ( $board_config['allow_avatar_remote'] ) ? '<img src="' . $weblog_owner_data[$i]['user_avatar'] . '" alt="" border="0" />' : '';
					break;
				case USER_AVATAR_GALLERY:
					$avatar_img[$i] = ( $board_config['allow_avatar_local'] ) ? '<img src="' . $board_config['avatar_gallery_path'] . '/' . $weblog_owner_data[$i]['user_avatar'] . '" alt="" border="0" />' : '';
					break;
			}
		}

	}

	switch ( $sort )
	{
		case 'f.cat_id, f.forum_id':
			$sort = 1;
			break;
		case 'f.forum_name':
			$sort = 2;
			break;
		case 'u.username':
			$sort = 3;
			break;
		default:
			$sort = 0;
			break;
	}

	if ( !$count )
		$count = 15;

	if ( !$start )
		$start = 0;

	$template->assign_vars(array(
		'FORUM_IMG' => $images['forum'],
		'FORUM_NEW_IMG' => $images['forum_new'],
		'FORUM_LOCKED_IMG' => $images['forum_locked'],

		'S_SELECT_PERPAGE' => $select_num_weblogs,
		'S_SELECT_SORT' => $select_sort,
		'S_SELECT_ASC_DESC' => $select_asc_desc,
		'S_WEBLOG_SORT_ACTION' => append_sid("weblogs.$phpEx?count=$count&sort=$sort&order=$order&start=$start"),

		'L_WEBLOG_STATS' => $lang['Weblog_stats'],
		'L_WEBLOG_LAST_UPDATED' => $lang['Weblog_last_updated'],
		'L_WEBLOG_DATE' => $lang['Weblog_date'],
		//'L_WEBLOG_NEWEST' => $lang['Weblog_newest'],
		'L_WEBLOG_POPULAR_AUTHORS' => $lang['Weblog_popular_authors'],
		'L_WEBLOG_MOST_ENTRIES' => $lang['Weblog_most_entries'],
		'L_WEBLOG_MOST_POPULAR' => $lang['Weblog_most_popular'],
		'L_WEBLOG_NAME' => $lang['Weblog_name'],
		'L_WEBLOG_NUMBER' => $lang['Weblog_number'],
		'L_WEBLOG_OWNER' => $lang['Weblog_owner2'],
		'L_WEBLOG_VISITS' => $lang['Weblog_visits'],
		'L_WEBLOG_VISITS' => $lang['Weblog_visits'],

		'L_PERPAGE' => $lang['Weblog_per_page'],
		'L_GO' => $lang['Weblog_go'],
		'L_SORT' => $lang['Weblog_sort'],

		'MOD_VERSION' => $mod_version,

                'L_VIEW_POST_BLOG' => $lang['view_post_blog'],          // Godwin
                'ROOT_PATH' => $phpbb_root_path,                        // Godwin
                'POST_BLOG' => $new_posts_blog['total'],                // Godwin

		'L_LASTPOST' => $lang['Last_Post'],
		'L_WEBLOGS' => $lang['Weblogs'],
		'L_WEBLOG' => $lang['Weblog'],
		'L_LASTENTRY' => $lang['Weblog_last_entry'],
		'L_LATESTMOOD' => $lang['Weblog_latest_mood'],
		'L_LASTSEEN' => $lang['Weblog_last_seen'],
		'L_VIEW' => $lang['View'])
	);

	//
	// Start output of page
	//
	define('IN_WEBLOGS', true);      //added by isaac for blog_header
	$page_title = $lang['Weblog_Index'];
	include($phpbb_root_path . 'includes/page_header.'.$phpEx);

	$template->set_filenames(array(
		'body' => 'weblog_index_body.tpl')
	);

	//
	// Okay, let's build the index
	//
	for($i = 0; $i < $total_categories; $i++)
	{
		$cat_id = $category_rows[$i]['cat_id'];

		//
		// Should we display this category/forum set?
		//
		$display_forums = false;
		for($j = 0; $j < $total_forums; $j++)
		{
			if ( $weblog_data[$j]['cat_id'] == $cat_id )
			{
				$display_forums = true;
			}
		}

		//
		// Yes, we should, so first dump out the category
		// title, then, if appropriate the forum list
		//
		if ( $display_forums )
		{
			$template->assign_block_vars('catrow', array(
				'CAT_ID' => $cat_id,
				'CAT_DESC' => $category_rows[$i]['cat_title'],
                )
            );

		for($j = 0; $j < $total_forums; $j++)
		{
			if ( $weblog_data[$j]['cat_id'] == $cat_id )
			{
				$weblog_id = $weblog_data[$j]['forum_id'];

					if ( $weblog_data[$j]['forum_status'] == FORUM_LOCKED )
					{
						$folder_image = $images['forum_locked'];
						$folder_alt = $lang['Weblog_locked'];
					}
					else
					{
						$unread_topics = false;
						if ( $userdata['session_logged_in'] )
						{
							if ( !empty($new_topic_data[$weblog_id]) )
							{
								$forum_last_post_time = 0;

								while( list($check_topic_id, $check_post_time) = @each($new_topic_data[$weblog_id]) )
								{
									if ( empty($tracking_topics[$check_topic_id]) )
									{
										$unread_topics = true;
										$forum_last_post_time = max($check_post_time, $forum_last_post_time);
									}
									else
									{
										if ( $tracking_topics[$check_topic_id] < $check_post_time )
										{
											$unread_topics = true;
											$forum_last_post_time = max($check_post_time, $forum_last_post_time);
										}
									}
								}

								if ( !empty($tracking_forums[$weblog_id]) )
								{
									if ( $tracking_forums[$weblog_id] > $forum_last_post_time )
									{
										$unread_topics = false;
									}
								}

								if ( isset($HTTP_COOKIE_VARS[$board_config['cookie_name'] . '_f_all']) )
								{
									if ( $HTTP_COOKIE_VARS[$board_config['cookie_name'] . '_f_all'] > $forum_last_post_time )
									{
										$unread_topics = false;
									}
								}
							}
						}
						$folder_image = ( $unread_topics ) ? $images['forum_new'] : $images['forum'];
						$folder_alt = ( $unread_topics ) ? $lang['New_posts'] : $lang['No_new_posts'];
					}

					$mood = $topic_data[$j]['topic_mood'] - 1;

					if ( $mood_data[$mood]['mood'] != '(none)' && $mood_data[$mood]['mood'] != '' && $mood > 0 )
					{
						$icon = '<img src="images/weblogs/' . $mood_data[$mood]['image_url'] . '" alt=' . $mood_data[$mood]['mood'] . ' border="0"> ' . $mood_data[$mood]['mood'];
					}
					else
					{
						$icon = '(none)';
					}

					$currently1 = $topic_data[$j]['topic_currently1'] - 1;

					if ( $currently_data[$currently1]['currently'] != '(none)' && $currently_data[$currently1]['currently'] != '' && $currently1 > 0 )
					{
						$last_seen = '<img src="images/weblogs/' . $currently_data[$currently1]['image_url'] . '" border="0">&nbsp;' . $currently_data[$currently1]['currently'] . ' ' . $topic_data[$j]['topic_currently2'];
					}
					else
					{
						$last_seen = $currently_data[$currently1]['currently'];
					}

					$newest_post_img = '';
					if( $userdata['session_logged_in'] )
					{
						if( $topic_data[$j]['post_time'] > $userdata['user_lastvisit'] )
						{
							if( !empty($tracking_topics) || !empty($tracking_forums) || isset($HTTP_COOKIE_VARS[$board_config['cookie_name'] . '_f_all']) )
							{
								$unread_topics = true;

								$entry_id[$i] = $topic_data[$i]['topic_id'];
								if( !empty($tracking_topics[$entry_id[$j]]) )
								{
									if( $tracking_topics[$entry_id[$j]] >= $topic_data[$j]['post_time'] )
									{
										$unread_topics = false;
									}
								}

								if( !empty($tracking_forums[$weblog_id]) )
								{
									if( $tracking_forums[$weblog_id] >= $topic_data[$j]['post_time'] )
									{
										$unread_topics = false;
									}
								}

								if( isset($HTTP_COOKIE_VARS[$board_config['cookie_name'] . '_f_all']) )
								{
									if( $HTTP_COOKIE_VARS[$board_config['cookie_name'] . '_f_all'] >= $post_time[$j] )
									{
										$unread_topics = false;
									}
								}

								if( $unread_topics )
								{
									$newest_post_img = '<a href="' . append_sid("weblog_entry.$phpEx?" . POST_ENTRY_URL . "=$entry_id[$j]") . '"><img src="' . $images['icon_newest_reply'] . '" alt="' . $lang['View_newest_post'] . '" title="' . $lang['View_newest_post'] . '" border="0" /></a> ';
								}
							}
							else
							{
								$newest_post_img = '<a href="' . append_sid("weblog_entry.$phpEx?" . POST_ENTRY_URL . "=$entry_id[$j]") . '"><img src="' . $images['icon_newest_reply'] . '" alt="' . $lang['View_newest_post'] . '" title="' . $lang['View_newest_post'] . '" border="0" /></a> ';
							}
						}
					}
					$posts = $weblog_data[$j]['forum_posts'];
					$topics = $weblog_data[$j]['forum_topics'];

					if ( $weblog_data[$j]['forum_last_post_id'] )
					{
						$last_post_time = create_date($board_config['default_dateformat'], $weblog_data[$j]['post_time'], $board_config['board_timezone']);

							//
							// MOD - TODAY AT - BEGIN
							//
							if ( $board_config['time_today'] < $weblog_data[$j]['post_time'])
							{
								$last_post = sprintf($lang['Today_at'], create_date($board_config['default_timeformat'], $weblog_data[$j]['post_time'], $board_config['board_timezone'])) . '';
							}
							else if ( $board_config['time_yesterday'] < $weblog_data[$j]['post_time'])
							{
								$last_post = sprintf($lang['Yesterday_at'], create_date($board_config['default_timeformat'], $weblog_data[$j]['post_time'], $board_config['board_timezone'])) . '';
							}
							else
							{
								$last_post = $last_post_time;
							}
							// MOD - TODAY AT - END

							$last_post .= $newest_post_img;

						}
						else
						{
							$last_post = $lang['No_Posts'];
						}

						if ( $weblog_data[$j]['forum_last_post_id'] )
						{
							$last_entry_time = create_date($board_config['default_dateformat'], $topic_data[$j]['topic_time'], $board_config['board_timezone']);
							//
							// MOD - TODAY AT - BEGIN
							//
							if ( $board_config['time_today'] < $topic_data[$j]['topic_time'])
							{
								$last_entry = sprintf($lang['Today_at'], create_date($board_config['default_timeformat'], $topic_data[$j]['topic_time'], $board_config['board_timezone'])) . '';
							}
							else if ( $board_config['time_yesterday'] < $topic_data[$j]['topic_time'])
							{
								$last_entry = sprintf($lang['Yesterday_at'], create_date($board_config['default_timeformat'], $topic_data[$j]['topic_time'], $board_config['board_timezone'])) . '';
							}
							else
							{
								$last_entry = $last_entry_time;
							}
							// MOD - TODAY AT - END
						}
						else
						{
							$last_entry = $lang['Weblog_no_entries'];
						}

						if ( file_exists("weblogs/face_" . $weblog_owner_data[$j]['user_id'] . ".cfg") )
							$file1 = file("weblogs/face_" . $weblog_owner_data[$j]['user_id'] . ".cfg");

						$weblog_face = '';
						for ($k=0;$k<count($file1);$k++)
						{
							$weblog_face .= $file1[$k];
						}

						// I'll take care of parsing myself!
						$weblog_face = str_replace('{catrow.forumrow.FORUM_NAME}', $weblog_data[$j]['forum_name'], $weblog_face);
						$weblog_face = str_replace('{catrow.forumrow.AVATAR_IMG}', $avatar_img[$j], $weblog_face);
						$weblog_face = str_replace('{catrow.forumrow.USERNAME}', $weblog_owner_data[$j]['username'], $weblog_face);

						if ( $is_auth_ary[$weblog_id]['auth_read'] )
						{
							$weblog_face = str_replace('{catrow.forumrow.FORUM_DESC}', $weblog_data[$j]['forum_desc'], $weblog_face);
							$weblog_face = str_replace('{catrow.forumrow.POSTS}', $weblog_data[$j]['forum_posts'], $weblog_face);
							$weblog_face = str_replace('{catrow.forumrow.TOPICS}', $weblog_data[$j]['forum_topics'], $weblog_face);
							$weblog_face = str_replace('{catrow.forumrow.ICON}', $icon, $weblog_face);
							$weblog_face = str_replace('{catrow.forumrow.LAST_POST}', $last_post, $weblog_face);
							$weblog_face = str_replace('{catrow.forumrow.LAST_ENTRY}', $last_entry, $weblog_face);
							$weblog_face = str_replace('{catrow.forumrow.LAST_SEEN}', $last_seen, $weblog_face);
							$weblog_face = str_replace('{catrow.forumrow.WEBLOG_DESC}', $weblog_data[$j]['forum_desc'], $weblog_face);
							$weblog_face = str_replace('{catrow.forumrow.WEBLOG_POSTS}', $weblog_data[$j]['forum_posts'], $weblog_face);
							$weblog_face = str_replace('{catrow.forumrow.WEBLOG_ENTRIES}', $weblog_data[$j]['forum_topics'], $weblog_face);
							$weblog_face = str_replace('{catrow.forumrow.U_PAST}', append_sid("weblog.$phpEx?" . POST_WEBLOG_URL . '=' . $weblog_data[$j]['forum_id']), $weblog_face);
							$weblog_face = str_replace('{catrow.forumrow.U_VIEWFORUM}', append_sid("weblog.$phpEx?" . POST_WEBLOG_URL . "=$weblog_id"), $weblog_face);
							$weblog_face = str_replace('{L_WEBLOG}', $lang['Weblog'], $weblog_face);
							$weblog_face = str_replace('{L_WEBLOG_DESC}', $lang['Weblog_Description'], $weblog_face);
							$weblog_face = str_replace('{L_LASTENTRY}', $lang['Weblog_last_entry'], $weblog_face);
							$weblog_face = str_replace('{L_LASTPOST}', $lang['Weblog_last_post'], $weblog_face);
							$weblog_face = str_replace('{L_LATESTMOOD}', $lang['Weblog_latest_mood'], $weblog_face);
							$weblog_face = str_replace('{L_LASTSEEN}', $lang['Weblog_last_seen'], $weblog_face);
							$weblog_face = str_replace('{L_VIEW}', $lang['View'], $weblog_face);
						}
						else
						{
							$weblog_face = str_replace('{catrow.forumrow.POSTS}', '', $weblog_face);
							$weblog_face = str_replace('{catrow.forumrow.TOPICS}', '', $weblog_face);
							$weblog_face = str_replace('{catrow.forumrow.ICON}', '-', $weblog_face);
							$weblog_face = str_replace('{catrow.forumrow.LAST_POST}', '-', $weblog_face);
							$weblog_face = str_replace('{catrow.forumrow.LAST_ENTRY}', '-', $weblog_face);
							$weblog_face = str_replace('{catrow.forumrow.LAST_SEEN}', '-', $weblog_face);
							$weblog_face = str_replace('{catrow.forumrow.WEBLOG_DESC}', $lang['Weblog_not_allowed'], $weblog_face);
							$weblog_face = str_replace('{catrow.forumrow.WEBLOG_POSTS}', '-', $weblog_face);
							$weblog_face = str_replace('{catrow.forumrow.WEBLOG_ENTRIES}', '-', $weblog_face);
							$weblog_face = str_replace('{catrow.forumrow.U_PAST}', 'weblogs.' . $phpEx, $weblog_face);
							$weblog_face = str_replace('{catrow.forumrow.U_VIEWFORUM}', '', $weblog_face);
							$weblog_face = str_replace('{L_WEBLOG}', $lang['Weblog'], $weblog_face);
							$weblog_face = str_replace('{L_WEBLOG_DESC}', $lang['Weblog_Description'], $weblog_face);
							$weblog_face = str_replace('{L_LASTENTRY}', $lang['Weblog_last_entry'], $weblog_face);
							$weblog_face = str_replace('{L_LASTPOST}', $lang['Weblog_last_post'], $weblog_face);
							$weblog_face = str_replace('{L_LATESTMOOD}', $lang['Weblog_latest_mood'], $weblog_face);
							$weblog_face = str_replace('{L_LASTSEEN}', $lang['Weblog_last_seen'], $weblog_face);
							$weblog_face = str_replace('{L_VIEW}', $lang['View'], $weblog_face);

						}
						$template->assign_block_vars('catrow.forumrow',	array(
							'WEBLOG_FACE' => $weblog_face)
						);
					}
			}
		}
	} // for ... categories
}// if ... total_categories
else
{
	message_die(GENERAL_MESSAGE, $lang['No_weblogs']);
}

//
// Generate the page
//
$template->pparse('body');

include($phpbb_root_path . 'includes/page_tail.'.$phpEx);

?>
