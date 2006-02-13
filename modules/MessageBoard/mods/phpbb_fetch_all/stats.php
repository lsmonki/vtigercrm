<?php
#######################################################################
##                                                                   ##
## phpBB Fetch All - displays phpBB on any page                      ##
## ----------------------------------------------------------------- ##
## A module for fetching board statistic related data.               ##
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
## phpbb_fetch_stats()                                               ##
## ----------------------------------------------------------------- ##
## Fetches user online, total posts, total users and newest user.    ##
## The time span of the online status is measured with five minutes. ##
## Have a look at the SQL statement if you want a different period   ##
## and change the value '300' (time in seconds) to whatever you find ##
## suitable.                                                         ##
##                                                                   ##
## EXAMPLE                                                           ##
##                                                                   ##
##     $stats = phpbb_fetch_stats();                                 ##
##                                                                   ##
##     echo 'We have ' . $stats['total_posts'] . ' articles.';       ##
##     echo 'We have ' . $stats['total_users'] . ' users.';          ##
##     echo 'The newest user is ' . $stats['username'] . '.';        ##
##     echo 'There are ' . $stats['user_online'] . ' user online.';  ##
##                                                                   ##
#######################################################################

function phpbb_fetch_stats()
{
    global $db;

    $result = array();

    $result['total_posts'] = get_db_stat('postcount');
    $result['total_users'] = get_db_stat('usercount');

    $newest_user           = get_db_stat('newestuser');

    $result['user_id']     = $newest_user['user_id'];
    $result['username']    = $newest_user['username'];

    $sql = 'SELECT
              session_id
            FROM
              ' . SESSIONS_TABLE . '
            WHERE
              session_time >= ' . (time() - 300);

    $user_online = phpbb_fetch_rows($sql);

    $result['user_online'] = count($user_online);

    return $result;
} // end func phpbb_fetch_stats

?>