<?php
#######################################################################
##                                                                   ##
## phpBB Fetch All - displays phpBB on any page                      ##
## ----------------------------------------------------------------- ##
## A module for fetching user related data.                          ##
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
// This value specifies how many users some functions from this module
// will fetch. Setting this to zero will fetch all particular users.
//

$CFG['users_limit'] = 5;

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
## phpbb_fetch_top_poster()                                          ##
## ----------------------------------------------------------------- ##
## Fetches users ordered by their amount of total posts. The amount  ##
## of users can be limited with $CFG['users_limit'].                 ##
##                                                                   ##
## EXAMPLE                                                           ##
##                                                                   ##
##     $top_poster = phpbb_fetch_top_poster();                       ##
##                                                                   ##
##     if ($top_poster)                                              ##
##     {                                                             ##
##         for ($i = 0; $i < count($top_poster); $i++)               ##
##         {                                                         ##
##             echo ($i+1) . '. ' . $top_poster[$i]['username'];     ##
##             echo ' (' . $top_poster[$i]['user_posts'] . ')<br>';  ##
##         }                                                         ##
##     }                                                             ##
##                                                                   ##
#######################################################################

function phpbb_fetch_top_poster()
{
    global $CFG, $db;

    $sql = 'SELECT
              u.*
            FROM
              ' . USERS_TABLE . ' AS u
            WHERE
              u.user_id <> -1
            ORDER BY
              u.user_posts DESC';

    if ($CFG['users_limit'])
    {
        $sql .= '
            LIMIT
              0,' . $CFG['users_limit'];
    }

    $result = phpbb_fetch_rows($sql);

    return $result;
} // end func phpbb_fetch_top_poster

#######################################################################
##                                                                   ##
## phpbb_fetch_random_user()                                         ##
## ----------------------------------------------------------------- ##
## Fetches a random user with at least one posting.                  ##
##                                                                   ##
## EXAMPLE                                                           ##
##                                                                   ##
##     $random_user = phpbb_fetch_random_user();                     ##
##                                                                   ##
##     echo 'User of the moment is ' . $random_user['username'];     ##
##                                                                   ##
#######################################################################

function phpbb_fetch_random_user()
{
    global $db;

    $sql = 'SELECT
              u.user_id
            FROM
              ' . USERS_TABLE . ' AS u
            WHERE
              u.user_id <> -1 AND
              u.user_posts > 0';

    $result = phpbb_fetch_rows($sql);

    //
    // initialize random generator and determine the lucky one :-)
    //

    srand ((double)microtime()*1000000);
    $the_one = rand(0, count($result) - 1);

    $sql = 'SELECT
              u.*
            FROM
              ' . USERS_TABLE . ' AS u
            WHERE
              u.user_id = ' . $result[$the_one]['user_id'];

    $result = phpbb_fetch_row($sql);

    return $result;
} // end func phpbb_fetch_random_user

#######################################################################
##                                                                   ##
## phpbb_fetch_users()                                               ##
## ----------------------------------------------------------------- ##
## Fetches users of a specific group. If $group_id is emtpy all      ##
## users of the board will be fetched; otherwise only the member of  ##
## the specific group.                                               ##
##                                                                   ##
## EXAMPLE                                                           ##
##                                                                   ##
##     $member = phpbb_fetch_useres();                               ##
##                                                                   ##
##     if ($member)                                                  ##
##     {                                                             ##
##         for ($i = 0; $i < count($member); $i++)                   ##
##         {                                                         ##
##             echo $member['username'] . '<br>';                    ##
##         }                                                         ##
##     }                                                             ##
##                                                                   ##
#######################################################################

function phpbb_fetch_users($group_id = null)
{
    global $db;

    $result = array();

    $sql = 'SELECT';

    if ($group_id)
    {
        $sql .= '
              g.*,
              ug.*,';
    }

    $sql .= '
              u.*
            FROM';

    if ($group_id)
    {
        $sql .= '
              ' . GROUPS_TABLE     . ' AS g,
              ' . USER_GROUP_TABLE . ' AS ug,';
    }

    $sql .= '
              ' . USERS_TABLE      . ' AS u
            WHERE';

    if ($group_id)
    {
        $sql .= '
              g.group_id = '  . $group_id . ' AND
              ug.group_id = ' . $group_id . ' AND
              u.user_id = ug.user_id AND
              ug.user_pending = 0 AND';
    }

    $sql .= '
              u.user_active = 1
             ORDER BY
              u.username';

    $result = phpbb_fetch_rows($sql);

    return $result;
} // end func phpbb_fetch_users

?>