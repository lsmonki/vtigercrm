<?php
/***************************************************************************
 *                           watched_topics_list.php
 *                           -----------------------
 *   Author  		: 	netclectic - Adrian Cockburn - adrian@netclectic.com
 *   Created 		: 	Monday, March 24, 2003
 *   Modified 		: 	Monday, August 11, 2003
 *
 *	 Version		: 	1.0.2
 *
 ***************************************************************************/

 /***************************************************************************
 * You can change the number of topics shown per page by altering the value
 * of the $watched_topics_per_page variable below:
 *
 *  e.g. $watched_topics_per_page = 5;
 *
 ***************************************************************************/

define('IN_PHPBB', true);
//$phpbb_root_path = './';
include($phpbb_root_path . 'extension.inc');
include($phpbb_root_path . 'common.'.$phpEx);

//
// Start session management
//
$userdata = session_pagestart($user_ip, PAGE_INDEX);
init_userprefs($userdata);
//
// End session management
//



/***************************************************************************/

$watched_topics_per_page = $board_config['topics_per_page'];

/***************************************************************************/



$start = ( isset($HTTP_GET_VARS['start']) ) ? intval($HTTP_GET_VARS['start']) : 0;

if (!$userdata['session_logged_in'])
{
    $redirect = ( isset($start) ) ? "&start=$start" : '';
    redirect(append_sid("login.$phpEx?redirect=watched_topics.$phpEx" . $redirect, true));
}


// are we un-watching some topics?
if ( isset($HTTP_POST_VARS['unwatch_list']) )
{
    $topic_ids = implode(",", $HTTP_POST_VARS['unwatch_list']);
    $sql = "DELETE FROM " . TOPICS_WATCH_TABLE . "
	    WHERE topic_id IN(" .  $topic_ids . ") 
    	AND user_id = " . $userdata['user_id'];
    if ( !($result = $db->sql_query($sql)) )
    {
        message_die(GENERAL_ERROR, "Could not delete topic watch information", '', __LINE__, __FILE__, $sql);
    }
}


//
// Generate the page
//
$page_title = $lang['Watched_Topics'];
include($phpbb_root_path . 'includes/page_header.'.$phpEx);

$template->set_filenames(array(
    'body' => 'watched_topics_body.tpl')
);

$template->assign_vars(array( 
    'S_FORM_ACTION' => append_sid($phpbb_root_path . "watched_topics.$phpEx"),
    'L_NO_WATCHED_TOPICS' => $lang['No_Watched_Topics'],
    'L_FORUM' => $lang['Forum'],
    'L_REPLIES' => $lang['Replies'],
    'L_STARTED' => $lang['Watched_Topics_Started'],
    'L_LAST_POST' => $lang['Last_Post'],
    'L_STOP_WATCH' => $lang['Watched_Topics_Stop'],
    'L_CHECK_ALL' => $lang['Check_All'],
    'L_UNCHECK_ALL' => $lang['UnCheck_All'],
    )
);


$sql = "SELECT COUNT(*) as watch_count FROM " . TOPICS_WATCH_TABLE . " w WHERE w.user_id = " . $userdata['user_id'];
if ( !($result = $db->sql_query($sql)) )
{
 	message_die(GENERAL_ERROR, 'Could not obtain watch topic information', '', __LINE__, __FILE__, $sql);
}
$row = $db->sql_fetchrow($result);
$watch_count = ( $row['watch_count'] ) ? $row['watch_count'] : 0;
$db->sql_freeresult($result);

if ($watch_count > 0)
{        
    // grab a list of watched topics
    $sql = "SELECT w.*, t.*, p.post_time, p.poster_id, f.forum_name, 
            first.username as author_username, 
            last.username as last_username  
        FROM " . TOPICS_WATCH_TABLE . " w, 
             " . TOPICS_TABLE . " t, 
             " . POSTS_TABLE . " p, 
             " . FORUMS_TABLE . " f, 
             " . USERS_TABLE . " first, 
             " . USERS_TABLE . " last 
        WHERE t.topic_id = w.topic_id 
            AND p.post_id = t.topic_last_post_id  
            AND t.topic_poster = first.user_id
            AND p.poster_id = last.user_id
            AND f.forum_id = t.forum_id 
            AND w.user_id = " . $userdata['user_id'] . " 
        ORDER BY t.topic_last_post_id DESC 
    	LIMIT $start, $watched_topics_per_page";
        
    if ( !($result = $db->sql_query($sql)) )
    {
     	message_die(GENERAL_ERROR, 'Could not obtain watch topic information', '', __LINE__, __FILE__, $sql);
    }
    $watch_rows = $db->sql_fetchrowset($result);

    // are we currently watching any topics?
    if ( $watch_rows )
    {
        $template->assign_block_vars('switch_watched_topics_block', array());
        for ( $i = 0; $i < count($watch_rows); $i++ )
        {
            $last_poster = ($watch_rows[$i]['poster_id'] == ANONYMOUS ) ? ( ($watch_rows[$i]['last_username'] != '' ) ? $watch_rows[$i]['last_username'] . ' ' : $lang['Guest'] . ' ' ) : '<a href="' . append_sid($phpbb_root_path . "profile.$phpEx?mode=viewprofile&amp;" . POST_USERS_URL . '='  . $watch_rows[$i]['poster_id']) . '">' . $watch_rows[$i]['last_username'] . '</a> ';
            $last_poster .= '<a href="' . append_sid($phpbb_root_path . "viewtopic.$phpEx?"  . POST_POST_URL . '=' . $watch_rows[$i]['topic_last_post_id']) . '#' . $watch_rows[$i]['topic_last_post_id'] . '"><img src="' . $images['icon_latest_reply'] . '" border="0" alt="' . $lang['View_latest_post'] . '" title="' . $lang['View_latest_post'] . '" /></a>';
            $topic_poster = ($watch_rows[$i]['topic_poster'] == ANONYMOUS ) ? ( ($watch_rows[$i]['author_username'] != '' ) ? $watch_rows[$i]['author_username'] . ' ' : $lang['Guest'] . ' ' ) : '<a href="' . append_sid($phpbb_root_path . "profile.$phpEx?mode=viewprofile&amp;" . POST_USERS_URL . '='  . $watch_rows[$i]['topic_poster']) . '">' . $watch_rows[$i]['author_username'] . '</a> ';
            
            $replies = $watch_rows[$i]['topic_replies']; 
            if( ( $replies + 1 ) > $board_config['posts_per_page'] ) 
            { 
                $total_pages = ceil( ( $replies + 1 ) / $board_config['posts_per_page'] ); 
                $goto_page = ' [ <img src="' . $images['icon_gotopost'] . '" alt="' . $lang['Goto_page'] . '" title="' . $lang['Goto_page'] . '" />' . $lang['Goto_page'] . ': '; 
            
                $times = 1; 
                for($j = 0; $j < $replies + 1; $j += $board_config['posts_per_page']) 
                { 
                    $goto_page .= '<a href="' . append_sid($phpbb_root_path . "viewtopic.$phpEx?" . POST_TOPIC_URL . "=" . $watch_rows[$i]['topic_id'] . "&start=$j") . '">' . $times . '</a>'; 
                    if( $times == 1 && $total_pages > 4 ) 
                    { 
                        $goto_page .= ' ... '; 
                        $times = $total_pages - 3; 
                        $j += ( $total_pages - 4 ) * $board_config['posts_per_page']; 
                    } 
                    else if ( $times < $total_pages ) 
                    { 
                    $goto_page .= ', '; 
                    } 
                    $times++; 
                } 
                $goto_page .= ' ] '; 
            } 
            else 
            { 
                $goto_page = ''; 
            }            

        	$row_class = ( !($i % 2) ) ? $theme['td_class1'] : $theme['td_class2'];
            $template->assign_block_vars('topic_watch_row', array(
                'ROW_CLASS' => $row_class,
                'S_WATCHED_TOPIC_ID' => $watch_rows[$i]['topic_id'],
                'S_WATCHED_TOPIC' => $watch_rows[$i]['topic_title'],
                'S_WATCHED_TOPIC_REPLIES' => $watch_rows[$i]['topic_replies'],
                'S_WATCHED_TOPIC_START' => create_date($board_config['default_dateformat'], $watch_rows[$i]['topic_time'], $board_config['board_timezone']),
                'S_WATCHED_TOPIC_LAST' => create_date($board_config['default_dateformat'], $watch_rows[$i]['post_time'], $board_config['board_timezone']),
                'S_FORUM_TITLE' => $watch_rows[$i]['forum_name'],

                'TOPIC_POSTER' => $topic_poster,
                'LAST_POSTER' => $last_poster,
                'GOTO_PAGE' => $goto_page,
                
                'U_FORUM_LINK' => append_sid($phpbb_root_path . "viewforum.$phpEx?" . POST_FORUM_URL . '=' . $watch_rows[$i]['forum_id']),
                'U_WATCHED_TOPIC' => append_sid($phpbb_root_path . "viewtopic.$phpEx?"  . POST_TOPIC_URL . '=' . $watch_rows[$i]['topic_id']),
                )
            );    
        }

        $pagination = generate_pagination("watched_topics.$phpEx?mode=watched_topics", $watch_count, $watched_topics_per_page, $start);
        
    	$template->assign_vars(array(
    		'PAGINATION' => $pagination,
    		'PAGE_NUMBER' => sprintf($lang['Page_of'], ( floor( $start / $watched_topics_per_page ) + 1 ), ceil( $topics_count / $watched_topics_per_page )), 
    
    		'L_GOTO_PAGE' => $lang['Goto_page'])
    	);
    }        
   	$db->sql_freeresult($result);
}
else
{
    $template->assign_block_vars('switch_no_watched_topics', array());
}

$template->pparse('body');

include($phpbb_root_path . 'includes/page_tail.'.$phpEx);

?>