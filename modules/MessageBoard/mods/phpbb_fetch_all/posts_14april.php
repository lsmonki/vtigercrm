<?php
#######################################################################
##                                                                   ##
## phpBB Fetch All - displays phpBB on any page                      ##
## ----------------------------------------------------------------- ##
## A module for fetching post related data.                          ##
##                                                                   ##
#######################################################################
##                                                                   ##
## Authors: Volker 'Ca5ey' Rattel <ca5ey@clanunity.net>              ##
##          http://clanunity.net/portal.php                          ##
##                                                                   ##
## This file is free software; you can redistribute it and/or modify ##
## it under the terms of the GNU General Public License as published ##
## by the Free Software Foundation; either version 2, or (at your    ##
## option) any later version.                                        ##
##                                                                   ##
## This file is distributed in the hope that it will be useful,      ##
## but WITHOUT ANY WARRANTY; without even the implied warranty of    ##
## MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the      ##
## GNU General Public License for more details.                      ##
##                                                                   ##
#######################################################################

#######################################################################
## CONFIGURATION
#######################################################################
//
// limit number of fetched posts
// 0 = unlimited
//

$CFG['posts_limit']               = 25;

//
// hide normal postings
// true / false
//

$CFG['posts_hide_normal']         = false;

//
// hide sticky postings
// true / false
//

$CFG['posts_hide_sticky']         = false;

//
// hide announcements
// true / false
//

$CFG['posts_hide_announcements']  = false;

//
// hide locked postings
// true / false
//

$CFG['posts_hide_locked']         = false;

//
// hide moved postings
// true / false

$CFG['posts_hide_moved']          = false;

//
// hide polls
// true / false
//

$CFG['posts_hide_polls']          = false;

//
// hide user ranks
// true / false
// note: if you enable this option _every_ poster needs
// to have a rank otherwise you will not see any output
//

$CFG['posts_hide_ranks']          = true;

//
// hide images in post text
// true / false
//

$CFG['posts_hide_images']         = false;

//
// replace images with this text
// only works when posts_hide_images = true
//

$CFG['posts_replace_images']      = '[image]';

//
// trim post text after this character combination
// empty = disabled
//

$CFG['posts_trim_text_character'] = '';

//
// trim post text after this amount of characters
// 0 = disabled
//

$CFG['posts_trim_text_number']    = 0;

//
// trim topic title after this amount of characters
// 0 = disabled
//

$CFG['posts_trim_title_number']   = 100;

//
// do not fetch postings which were posted before this date
// empty = disabled
//

$CFG['posts_date_offset_start']   = '';

//
// do not fetch postings which are newer than this date
// time() = fetch right until now
//

$CFG['posts_date_offset_end']     = time();

//
// fetch only postings which contain this search string
// empty = disabled
//

$CFG['posts_search_string']       = '';

//
// use span pages
// true / false
//

$CFG['posts_span_pages']          = false;

#######################################################################
## NO CHANGES NEEDED BELOW
#######################################################################

if (!defined('IN_PHPBB'))
{
    die ('hacking attempt');
}

define ('POSTS_FETCH_FIRST', 0);
define ('POSTS_FETCH_LAST', 1);

$CFG['posts_span_pages_offset']   = 0;
$CFG['posts_span_pages_numrows']  = 0;
$CFG['posts_offset']              = 0;

#######################################################################
##                                                                   ##
## phpbb_fetch_posts()                                               ##
## ----------------------------------------------------------------- ##
## This function will fetch the first or the last posting from one   ##
## or more topics.                                                   ##
##                                                                   ##
#######################################################################

function phpbb_fetch_posts($forum_id = null, $fetch_mode = POSTS_FETCH_FIRST)
{
    global $CFG, $userdata;

    //
    // sanity check for dates
    //

    if ($CFG['posts_date_offset_start'] >= $CFG['posts_date_offset_end'])
    {
        phpbb_raise_error('\'posts_date_offset_start\' has to be smaller than \'posts_date_offset_end\'');
    }

    //
    // create a list of forums with read permission
    // (only takes action when auth_check is enabled)
    //

    phpbb_get_auth_list();

    //
    // determine the forum list based on the user input
    // and/or permissions (depends on auth check)
    //

    $forum_list = phpbb_get_forum_list($forum_id);

    //
    // if read permissions do not allow us to fetch anything
    // we return nicely
    //

    if (!$forum_list and $CFG['auth_check'])
    {
        return;
    }

    $sql = 'SELECT
              f.*,
              p.*,
              pt.*,
              t.*,
              u.*';

    if ($CFG['show_ranks'])
    {
        $sql .= ',
              r.*';
    }

    $sql .= '
            FROM
              ' . TOPICS_TABLE     . ' AS t,
              ' . USERS_TABLE      . ' AS u,
              ' . POSTS_TEXT_TABLE . ' AS pt,
              ' . POSTS_TABLE      . ' AS p,
              ' . FORUMS_TABLE     . ' AS f';

    if (!$CFG['posts_hide_ranks'])
    {
        $sql .= ',
              ' . RANKS_TABLE      . ' AS r';
    }

    $sql .= '
            WHERE';

    if ($forum_list)
    {
        $sql .= '
              t.forum_id IN (' . $forum_list . ') AND';
    }

    if ($fetch_mode == POSTS_FETCH_FIRST)
    {
        $sql .= '
              t.topic_first_post_id = pt.post_id AND
              t.topic_first_post_id = p.post_id AND';
    }
    else
    {
        $sql .= '
              t.topic_last_post_id = pt.post_id AND
              t.topic_last_post_id = p.post_id AND';
    }

    if ($CFG['posts_date_offset_start'])
    {
        $sql .= '
              p.post_time >= ' . $CFG['posts_date_offset_start'] . ' AND';
    }

    if ($CFG['posts_date_offset_end'])
    {
        $sql .= '
              p.post_time <= ' . $CFG['posts_date_offset_end'] . ' AND';
    }

    if ($CFG['posts_hide_normal'])
    {
        $sql .= '
              t.topic_type <> 0 AND';
    }

    if ($CFG['posts_hide_sticky'])
    {
        $sql .= '
              t.topic_type <> 1 AND';
    }

    if ($CFG['posts_hide_announcement'])
    {
        $sql .= '
              t.topic_type <> 2 AND';
    }

    if ($CFG['posts_hide_locked'])
    {
        $sql .= '
              t.topic_status <> 1 AND';
    }

    if ($CFG['posts_hide_moved'])
    {
        $sql .= '
              t.topic_status <> 2 AND';
    }

    if ($CFG['posts_hide_polls'])
    {
        $sql .= '
              t.topic_vote <> 1 AND';
    }

    if ($CFG['posts_search_string'])
    {
        $sql .= '
              (' . $CFG['posts_search_string'] . ') AND';
    }

    $sql .= '
              t.forum_id = f.forum_id AND';

    if (!$CFG['posts_hide_ranks'])
    {
        $sql .= '
              r.rank_id = u.user_rank AND';
    }

    $sql .= '
              u.user_id = p.poster_id';

   //Added By David For Sorting:

	$order_column = $_GET['order_column'];
	$order_type = $_GET['order_type'];


	if ($order_column == "")
	{
	$order_column = "p.post_time";
        $order_type  = "DESC";
	}

	if (isset($_COOKIE['cookie_order']) && (!$_GET['order_column']))
	$order_column = $_COOKIE['cookie_order'];


	if (isset($_COOKIE['cookie_type']) && (!$_GET['order_type']))
	$order_type = $_COOKIE['cookie_type'];

	if ($order_type == "ASEN")
		$order_type = "";

	$sql .= '
              ORDER BY
                '.$order_column.' '.$order_type;
    //end David

    // 3 lines -- commented by David
    // $sql .= '
    //           ORDER BY
    //             p.post_time DESC';



    if ($CFG['posts_span_pages'])
    {
        $CFG['posts_span_pages_numrows'] = phpbb_numrows(phpbb_query($sql));
        if ($CFG['posts_span_pages_offset'] > $CFG['posts_span_pages_numrows'])
        {
            $CFG['posts_span_pages_offset'] = $CFG['posts_span_pages_numrows'] - 1;
        }
        $CFG['posts_offset'] = $CFG['posts_span_pages_offset'];
    }
    else
    {
        $CFG['posts_offset'] = 0;
    }

    if ($CFG['posts_limit'] != 0)
    {
        $sql .= '
              LIMIT ' . $CFG['posts_offset'] . ',' . $CFG['posts_limit'];
    }

    $result = phpbb_fetch_rows($sql);

    if ($result)
    {
        $orig_word        = array();
        $replacement_word = array();
        obtain_word_list($orig_word, $replacement_word);

        for ($i = 0; $i < count($result); $i++)
        {
            $result[$i]['post_time']  = $result[$i]['post_time']  + $CFG['time_zone'];
            $result[$i]['topic_time'] = $result[$i]['topic_time'] + $CFG['time_zone'];

            $result[$i]['date']       = date($CFG['date_format'], $result[$i]['post_time']);
            $result[$i]['time']       = date($CFG['time_format'], $result[$i]['post_time']);

            $result[$i]['edit_date']  = date($CFG['date_format'], $result[$i]['post_edit_time']);
            $result[$i]['edit_time']  = date($CFG['time_format'], $result[$i]['post_edit_time']);

            $result[$i]['post_text']  = phpbb_parse_text($result[$i]['post_text'],
                                                         $result[$i]['bbcode_uid'],
                                                         $result[$i]['enable_smilies']);

            if (count($orig_word))
            {
                $result[$i]['topic_title'] = preg_replace($orig_word,
                                                          $replacement_word,
                                                          $result[$i]['topic_title']);
                $result[$i]['post_text']   = preg_replace($orig_word,
                                                          $replacement_word,
                                                          $result[$i]['post_text']);
            }

            $result[$i]['trimmed'] = false;
            phpbb_trim_text($result[$i]['post_text'],
                            $result[$i]['trimmed'],
                            $CFG['posts_trim_text_character'],
                            $CFG['posts_trim_text_number']);

            $result[$i]['topic_trimmed'] = false;
            phpbb_trim_text($result[$i]['topic_title'],
                            $result[$i]['topic_trimmed'],
                            '',
                            $CFG['posts_trim_topic_number']);
        }
    }

    return $result;
} // end func phpbb_fetch_posts

#######################################################################
##                                                                   ##
## phpbb_fetch_topics()                                              ##
## ----------------------------------------------------------------- ##
## This function will fetch the first or the last posting from one   ##
## or more topics specified by topic id.                             ##
##                                                                   ##
#######################################################################

function phpbb_fetch_topics($topic_id = null, $fetch_mode = FETCH_MODE_FIRST)
{
    global $CFG, $userdata;

    $topic_list = '';

    if (!is_array($topic_id))
    {
        $topic_list = $topic_id;
    }
    else
    {
        for ($i = 0; $i < count($topic_id); $i++)
        {
            $topic_list .= $topic_id[$i] . ',';
        }

        if ($topic_list)
        {
            $topic_list = substr($topic_list, 0, strlen($topic_list) -1);
        }
    }

    $sql = 'SELECT
              f.*,
              p.*,
              pt.*,
              t.*,
              u.*';

    if ($CFG['show_ranks'])
    {
        $sql .= ',
              r.*';
    }

    $sql .= '
            FROM
              ' . TOPICS_TABLE     . ' AS t,
              ' . USERS_TABLE      . ' AS u,
              ' . POSTS_TEXT_TABLE . ' AS pt,
              ' . POSTS_TABLE      . ' AS p,
              ' . FORUMS_TABLE     . ' AS f';

    if (!$CFG['posts_hide_ranks'])
    {
        $sql .= ',
              ' . RANKS_TABLE      . ' AS r';
    }

    $sql .= '
            WHERE
              u.user_id = p.poster_id AND';

    if ($topic_list)
    {
        $sql .= '
              t.topic_id IN (' . $topic_list . ') AND';
    }

    if ($fetch_mode == FETCH_MODE_FIRST)
    {
        $sql .= '
              t.topic_first_post_id = pt.post_id AND
              t.topic_first_post_id = p.post_id AND';
    }
    else
    {
        $sql .= '
              t.topic_last_post_id = pt.post_id AND
              t.topic_last_post_id = p.post_id AND';
    }

    $sql .= '
              t.forum_id = f.forum_id';

    if (!$CFG['posts_hide_ranks'])
    {
        $sql .= '
              AND r.rank_id = u.user_rank';
    }
    if ($CFG['topic_limit'] !='')
    {
      $sql .= '
		LIMIT '.$CFG['topic_limit']; 	
    }
    $result = phpbb_fetch_rows($sql);

    if ($result)
    {
        if ($CFG['auth_check'])
        {
            phpbb_get_auth_list();

            $authed = array();

            for ($i = 0; $i < count($result); $i++)
            {
                if (in_array($result[$i]['forum_id'], $CFG['auth_list']))
                {
                    $authed[] = $result[$i];
                }
            }

            $result = $authed;
        }

        $orig_word = array();
        $replacement_word = array();
        obtain_word_list($orig_word, $replacement_word);

        for ($i = 0; $i < count($result); $i++)
        {
            $result[$i]['post_time']      = $result[$i]['post_time']      + $CFG['time_zone'];
            $result[$i]['topic_time']     = $result[$i]['topic_time']     + $CFG['time_zone'];
            $result[$i]['post_edit_time'] = $result[$i]['post_edit_time'] + $CFG['time_zone'];

            $result[$i]['date'] = date($CFG['date_format'], $result[$i]['post_time']);
            $result[$i]['time'] = date($CFG['time_format'], $result[$i]['post_time']);

            $result[$i]['edit_date'] = date($CFG['date_format'], $result[$i]['post_edit_time']);
            $result[$i]['edit_time'] = date($CFG['time_format'], $result[$i]['post_edit_time']);

            $result[$i]['post_text'] = phpbb_parse_text($result[$i]['post_text'],
                                                        $result[$i]['bbcode_uid'],
                                                        $result[$i]['enable_smilies']);

            if (count($orig_word))
            {
                $result[$i]['topic_title'] = preg_replace($orig_word,
                                                          $replacement_word,
                                                          $result[$i]['topic_title']);
                $result[$i]['post_text'] = preg_replace($orig_word,
                                                        $replacement_word,
                                                        $result[$i]['post_text']);
            }

            $result[$i]['trimmed'] = false;
            phpbb_trim_text($result[$i]['post_text'],
                            $result[$i]['trimmed'],
                            $CFG['posts_trim_text_character'],
                            $CFG['posts_trim_text_number']);

            $result[$i]['topic_trimmed'] = false;
            phpbb_trim_text($result[$i]['topic_title'],
                            $result[$i]['topic_trimmed'],
                            '',
                            $CFG['posts_trim_topic_number']);
        }

        if (is_array($topic_id))
        {
            $sorted = array();

            for ($i = 0; $i < count($topic_id); $i++)
            {
                for ($j = 0; $j < count($result); $j++)
                {
                    if ($topic_id[$i] == $result[$j]['topic_id'])
                    {
                        $sorted[] = $result[$j];
                    }
                }
            }

            $result = $sorted;
        }
    }

    return $result;
} // end func phpbb_fetch_topics

#######################################################################
##                                                                   ##
## phpbb_fetch_new_posts()                                           ##
## ----------------------------------------------------------------- ##
## Fetches the number of new posts since the last visit.             ##
##                                                                   ##
#######################################################################

function phpbb_fetch_new_posts()
{
    global $userdata;

    $result['total'] = 0;

    if ($userdata['session_logged_in'])
    {
        $sql = 'SELECT
                  COUNT(post_id) AS total
                FROM
                  ' . POSTS_TABLE . '
                WHERE
                  post_time >= ' . $userdata['user_lastvisit'];

        $result = phpbb_fetch_row($sql);
    }

    return $result;
}

?>
