<?php
#######################################################################
##                                                                   ##
## phpBB Fetch All - displays phpBB on any page                      ##
## ----------------------------------------------------------------- ##
## A module for fetching forum related data.                         ##
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
## NO CHANGES NEEDED BELOW
#######################################################################

//
// prevent hacking attempt
//

if (!defined('IN_PHPBB'))
{
    die ('hacking attempt');
}

#######################################################################
##                                                                   ##
## phpbb_fetch_forums()                                              ##
## ----------------------------------------------------------------- ##
## Fetches the board structure with categories and forums. If auth   ##
## checking is enabled the result will be tested against the user    ##
## permissions to ensure that you only see what you are allowed to   ##
## see.                                                              ##
##                                                                   ##
## PARAMETER                                                         ##
##                                                                   ##
##     cat_id                                                        ##
##         this parameter can be either null (just leave it away)    ##
##         like in                                                   ##
##                                                                   ##
##             $forum = phpbb_fetch_forums();                        ##
##                                                                   ##
##         to fetch the entire board structure; you can specify a    ##
##         single category like                                      ##
##                                                                   ##
##             $forum = phpbb_fetch_forums(1);                       ##
##                                                                   ##
##         to fetch only the structure of the category with id 1;    ##
##         you can specify a list of categories like in              ##
##                                                                   ##
##             $forum = phpbb_fetch_forums(array(1,2,3));            ##
##                                                                   ##
##         which would fetch out of these categories                 ##
##                                                                   ##
## EXAMPLE                                                           ##
##                                                                   ##
##     $forum = phpbb_fetch_forums();                                ##
##                                                                   ##
##     if ($forum)                                                   ##
##     {                                                             ##
##         for ($i = 0; $i < count($forum); $i++)                    ##
##         {                                                         ##
##             echo $forum[$i]['forum_name'] . '<br>';               ##
##         }                                                         ##
##     }                                                             ##
##                                                                   ##
#######################################################################

function phpbb_fetch_forums($cat_id = null)
{
    global $CFG, $userdata;

    //
    // build a list of categories if an array has been passed
    //

    $cat_list = '';

    if ($cat_id)
    {
        if (!is_array($cat_id))
        {
            $cat_list = $cat_id;
        }
        else
        {
            for ($i = 0; $i < count($cat_id); $i++)
            {
                $cat_list .= $cat_id[$i] . ',';
            }

            if ($cat_list)
            {
                $cat_list = substr($cat_list, 0, strlen($cat_list) -1);
            }
        }
    }

    $sql = 'SELECT
              c.*,
              f.*
            FROM
              ' . CATEGORIES_TABLE . ' AS c,
              ' . FORUMS_TABLE     . ' AS f
            WHERE';

    if ($cat_list)
    {
        $sql .= '
              f.cat_id IN (' . $cat_list . ') AND';
    }

    $sql .= '
              f.forum_status = 0 AND
              f.cat_id = c.cat_id
            ORDER BY
              c.cat_order,
              f.forum_order';

    $result = phpbb_fetch_rows($sql);
    //
    // auth check if requested
    //

    if ($result and $CFG['auth_check'])
    {
        //
        // create a list of forums with read permission
        // (only takes action when auth_check is enabled)
        //

        phpbb_get_auth_list();

        $authed = array();

        for ($i = 0; $i < count($result); $i++) {
            if (in_array($result[$i]['forum_id'], $CFG['auth_list']))
            {
                $authed[] = $result[$i];
            }
        }

        $result = $authed;
    }

    return $result;
} // end func phpbb_fetch_forums
// start of phpbb_fetch_threetops 
function phpbb_fetch_threetops($for_id = null)
{
    global $CFG, $userdata;

    //
    // build a list of categories if an array has been passed
    //

    $for_list = '';

    if ($for_id)
    {
        if (!is_array($for_id))
        {
            $for_list = $for_id;
        }
        else
        {
            for ($i = 0; $i < count($for_id); $i++)
            {
                $for_list .= $for_id[$i] . ',';
            }

            if ($for_list)
            {
                $for_list = substr($for_list, 0, strlen($for_list) -1);
            }
        }
    }
 //select t.topic_title , u.username, FROM_UNIXTIME(p.post_time) from phpbb_topics t, phpbb_posts p, phpbb_users u where u.user_id=p.poster_id and p.post_id=t.topic_last_post_id and t.forum_id=21 order by topic_last_post_id DESC limit 3;

/*    $sql = 'SELECT
		c.*,
		f.*,
              t.topic_title,
              u.username,
	      p.post_time 	
            FROM
              ' . TOPICS_TABLE . ' t,
            	' . USERS_TABLE . ' u,
              ' . POSTS_TABLE . ' p WHERE
		u.user_id = p.poster_id AND 
		p.post_id = t.topic_last_post_id AND 
		t.forum_id ='. $for_list .' 
	   ORDER BY 
		topic_last_post_id DESC 
	   LIMIT 0,3 ';
*/
	$sql = 'select forum_id,topic_id from '.TOPICS_TABLE.' where forum_id='.$for_list;
//message_die(GENERAL_MESSAGE,$sql);
    $result = phpbb_fetch_rows($sql);
    return $result;

}

 // end func phpbb_fetch_topthree
?>
