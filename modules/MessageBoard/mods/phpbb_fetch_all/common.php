<?php
#######################################################################
##                                                                   ##
## phpBB Fetch All - displays phpBB on any page                      ##
## ----------------------------------------------------------------- ##
## This module contains common functions for all other modules.      ##
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
// URL to your smilie directory without trailing slash.
//

$CFG['smilie_url'] = $phpbb_root_path . 'images/smiles';

//
// URL to your avatar directory without trailing slash.
//

$CFG['avatar_url'] = $phpbb_root_path . 'images/avatars';

//
// URL to your avatar gallery directory without trailing slash.
//

$CFG['avatar_gallery_url'] = $phpbb_root_path . 'images/avatars/gallery';

//
// When set to true the script will check postings and other
// things against the permissions of the user who views the
// script. If no permission for the particular action exists the
// script will not fetch and display the data.
//
// true  = enabled
// false = disabled (default)
//
// IMPORTANT: Using auth check will require these two lines in
//            your script to work properly:
//
// $userdata = session_pagestart($user_ip, PAGE_INDEX, $session_length);
// init_userprefs($userdata);
//

$CFG['auth_check'] = true;

//
// If you want to adjust the time in the output to reflect your
// local time you can set your time zone here. You can use this
// variable later on in the output to adjust all the time and
// date values.
//
// server time 00:00
// local time  01:00
// $CFG['time_zone'] = 1 * 3600;
//
// server time 01:00
// local time  00:00
// $CFG['time_zone'] = -1 * 3600;
//

$CFG['time_zone'] = 0 * 3600;

//
// This lets you specify the date format in the output.
// See http://www.php.net/date for a reference.
//

//$CFG['date_format'] = 'd.m.y';
//Added by Isaac - to remove the year in portal.php
$CFG['date_format'] = 'd/m';

//
// This lets you specify the time format in the output.
// See http://www.php.net/date for a reference.
//

$CFG['time_format'] = 'H:i';

//
// This can be set to determine the behaviour of the script if an
// error occurs.
//
// die      = uses PHP die() function (default)
// phpbb    = uses phpBB message_die() function
// redirect = uses a redirect to another page
//
// Setting this to die will produces a white page, setting this
// to phpbb will give you a phpBB error page and setting this to
// redirect will make your visitors see (for example) a nice
// 'this site is offline' page. This is useful if your database
// is sometimes down. ;)
//

$CFG['on_error'] = 'die';

//
// You can specify the URL to which will be redirected in case of
// an error.
//

$CFG['redirect'] = '';

#######################################################################
## NO CHANGES NEEDED BELOW
#######################################################################

$CFG['version'] = '2.0.3';
$CFG['auth_list'] = array();
$CFG['auth_called'] = false;

//
// prevent hacking attempt
//

if (!defined('IN_PHPBB'))
{
    die ('hacking attempt');
}

#######################################################################
##                                                                   ##
## phpbb_raise_error()                                               ##
## ----------------------------------------------------------------- ##
## Produces an error message or redirects the browser to a given     ##
## URL. You can use this function for your entire site to ensure     ##
## that all scripts exits nicely on an error. The redirect option    ##
## will forward the browser to another page which is very useful     ##
## if (for example) the database is down and you want to show a      ##
## 'site offline' page automatically.                                ##
##                                                                   ##
## PARAMETER                                                         ##
##                                                                   ##
##     message                                                       ##
##         the error message                                         ##
##     file                                                          ##
##         file name (use __FILE__ if you call this function from    ##
##         your own scripts)                                         ##
##     line                                                          ##
##         line number (use __LINE__ if you call this function from  ##
##         your own scripts)                                         ##
##                                                                   ##
#######################################################################

function phpbb_raise_error($message = null, $file = null, $line = null)
{
    global $CFG;

    switch ($CFG['on_error'])
    {
        //
        // page redirect
        //

        case 'redirect':
            if ($CFG['redirect'])
            {
                header ('Location: ' . $CFG['redirect']);
            }
            else
            {
                $CFG['on_error'] = 'die';
                phpbb_raise_error($message, $file, $line);
            }
            break;

        //
        // phpBB's message_die()
        //

        case 'phpbb':
            message_die(GENERAL_MESSAGE,
                        '<b>phpbb Fetch All error:</b> ' . $message, '', __FILE__, __LINE__);
            break;

        //
        // PHP's die()
        //

        default:
            die ('<tt><b>phpbb Fetch All error:</b> ' . $message . ' at ' . $file . ':' . $line . '</tt>');
    }

    exit;
} // end func phpbb_raise_error

#######################################################################
##                                                                   ##
## phpbb_fetch_row()                                                 ##
## ----------------------------------------------------------------- ##
## Performs a SQL database query and returns the result in a single  ##
## array like this                                                   ##
##                                                                   ##
##     $result['field1']                                             ##
##     $result['field2']                                             ##
##                                                                   ##
## PARAMETER                                                         ##
##                                                                   ##
##     sql                                                           ##
##         the SQL statement                                         ##
##                                                                   ##
#######################################################################

function phpbb_fetch_row($sql = null)
{
    global $db;

    if (!$sql)
    {
        return;
    }
    //if (stristr($sql,"LIMIT"))
    //echo $sql;
    $query = phpbb_query($sql);

    $result = $db->sql_fetchrow($query);

    return $result;
} // end func phpbb_fetch_row

#######################################################################
##                                                                   ##
## phpbb_fetch_rows()                                                ##
## ----------------------------------------------------------------- ##
## Performs a SQL database query and returns the result in a multi-  ##
## dimensional array like this                                       ##
##                                                                   ##
##     $result[0]['field1']                                          ##
##     $result[0]['field2']                                          ##
##     $result[1]['field1']                                          ##
##     $result[1]['field2']                                          ##
##                                                                   ##
## PARAMETER                                                         ##
##                                                                   ##
##     sql                                                           ##
##         the SQL statement                                         ##
##                                                                   ##
#######################################################################

function phpbb_fetch_rows($sql = null)
{
    global $db;
    if (!$sql)
    {
        return;
    }
    $query = phpbb_query($sql);

//if(stristr($sql,'LIMIT 0,3'))
//message_die(GENERAL_MESSAGE,$sql);
    $result = array();

    while ($row = $db->sql_fetchrow($query))
    {
        $result[] = $row;
    }
    return $result;
} // end func phpbb_fetch_rows

#######################################################################
##                                                                   ##
## phpbb_query()                                                     ##
## ----------------------------------------------------------------- ##
## Executes a query through the phpBB DB API and returns the result. ##
## On an error phpbb_raise_error() will be called so you can make    ##
## your own error handler with this.                                 ##
##                                                                   ##
## PARAMETER                                                         ##
##                                                                   ##
##     sql                                                           ##
##         the SQL query statement                                   ##
##                                                                   ##
#######################################################################

function phpbb_query($sql = null)
{
  global $db;

    if (!$query = $db->sql_query($sql))
    {
	
       //message_die(GENERAL_MESSAGE, $sql);	
      phpbb_raise_error('database query failed', __FILE__, __LINE__);
        //phpbb_raise_error('database query failed', __FILE__, __LINE__, $query);
    }

    return $query;
} // end func phpbb_query

#######################################################################
##                                                                   ##
## phpbb_numrows()                                                   ##
## ----------------------------------------------------------------- ##
## Returns the number of rows in the result of a query.              ##
##                                                                   ##
## PARAMETER                                                         ##
##                                                                   ##
##     query                                                         ##
##         the DB handle to the query                                ##
##                                                                   ##
#######################################################################

function phpbb_numrows($query = null)
{
    global $db;

    return $db->sql_numrows($query);
} // end func phpbb_numrows

#######################################################################
##                                                                   ##
## phpbb_disconnect()                                                ##
## ----------------------------------------------------------------- ##
## Disconnects from the database using the phpBB DB API.             ##
##                                                                   ##
#######################################################################

function phpbb_disconnect()
{
    global $db;

    $db->sql_close();
} // end func phpbb_disconnect

#######################################################################
##                                                                   ##
## phpbb_parse_text()                                                ##
## ----------------------------------------------------------------- ##
## Parses text according to phpBB (BBCode, smilies, etc).            ##
##                                                                   ##
## PARAMETER                                                         ##
##                                                                   ##
##     text                                                          ##
##         the text to be parsed                                     ##
##     bbcode_uid                                                    ##
##         bbcode identifier                                         ##
##     enable_smilies                                                ##
##         if true all smilies will be parsed with their icon        ##
##         otherwise you will only see the smilie code               ##
##     enable_images                                                 ##
##         if true all images will be left untouched otherwise all   ##
##         images will be replaced with the text in the next         ##
##         parameter                                                 ##
##     replace_images                                                ##
##         text to replace images with                               ##
##                                                                   ##
#######################################################################

function phpbb_parse_text($text = null,
                          $bbcode_uid = null,
                          $enable_smilies = true,
                          $enable_images = true,
                          $replace_images = '')
{
    global $CFG;

    if (!$text)
    {
        return;
    }

    //
    // remove slashes
    //

    stripslashes($text);

    //
    // remove images if requested
    //

    if (!$enable_images)
    {
        if ($replace_images)
        {
            $replacement = '[url=\\1]' . $replace_images . '[/url]';
        }
        else
        {
            $replacement = '';
        }
        $text = preg_replace("#\[img:$bbcode_uid\](.*?)\[/img:$bbcode_uid\]#si",
                             $replacement, $text);
    }

    //
    // parse bbcode
    //

    $text = bbencode_second_pass($text, $bbcode_uid);

    //
    // parse smilies if requested
    //

    if ($enable_smilies == 1)
    {
        $text = smilies_pass($text);

        //
        // need to overwrite the smilie path since we might not be within
        // the phpBB directory
        //

        $text = preg_replace("/images\/smiles/", $CFG['smilie_url'], $text);
    }

    //
    // parse url's
    //

    $text = make_clickable($text);

    //
    // change newlines to HTML
    //

    $text = str_replace("\n", "\n<br />\n", $text);

    return $text;
} // end func phpbb_parse_text

#######################################################################
##                                                                   ##
## phpbb_trim_text()                                                 ##
## ----------------------------------------------------------------- ##
## This function can trim a text by number of characters or the      ##
## first appearence of a character combination.                      ##
##                                                                   ##
## PARAMETER                                                         ##
##                                                                   ##
##     text                                                          ##
##         contains the text which should be trimmed                 ##
##     is_trimmed                                                    ##
##         will be false if no trimming has been done and true if    ##
##         the text has been trimmed                                 ##
##     character                                                     ##
##         a character combination ('<br />')                        ##
##     number                                                        ##
##         a number of characters ('150')                            ##
##                                                                   ##
#######################################################################

function phpbb_trim_text(&$text,
                         &$is_trimmed,
                         $character = null,
                         $number = 0)
{
    //
    // trim by character combination
    //

    if ($character != '' and eregi($character, $text))
    {
        $trimmed    = explode($character, $text);
        $text       = $trimmed[0];
        $is_trimmed = true;
    }

    //
    // trim by number
    //

    if ($number != 0 and strlen($text) > $number)
    {
        $text       = substr($text, 0, $number);
        $is_trimmed = true;
    }

    return true;
} // end func phpbb_trim_text

#######################################################################
##                                                                   ##
## phpbb_span_pages()                                                ##
## ----------------------------------------------------------------- ##
## Calculates the span pages. Returns a string with all available    ##
## pages like                                                        ##
##                                                                   ##
##     Goto page 1,2,3 Next                                          ##
##                                                                   ##
## The 'goto page' is being set by your local language setup. The    ##
## string is being formatted and contains a link on the calling      ##
## script (PHP_SELF).                                                ##
##                                                                   ##
## PARAMETER                                                         ##
##                                                                   ##
##     numrows                                                       ##
##        number of rows of the result query                         ##
##     limit                                                         ##
##        number of entries to show on one page                      ##
##     offset                                                        ##
##        number of entry from which the output starts               ##
##     add_prevnext_text                                             ##
##        true  = adds the previous/next text                        ##
##        false = do not add it                                      ##
##                                                                   ##
#######################################################################

function phpbb_span_pages($numrows = 0,
                          $limit = 0,
                          $offset = 0,
                          $add_prevnext_text = true)
{
    global $PHP_SELF;

    //
    // do a little string replace to fix the original output of the phpBB
    // pagination function (replace '&' with '?')
    //

    $result = eregi_replace('&amp;start',
                            'start',
                            generate_pagination($PHP_SELF . '?',
                                                $numrows,
                                                $limit,
                                                $offset,
                                                $add_prevnext_text));

    return $result;
} // end func phpbb_span_pages

#######################################################################
##                                                                   ##
## phpbb_get_auth_list()                                             ##
## ----------------------------------------------------------------- ##
## This function will fetch the list of forums which the user is     ##
## able to 'view'. The list will be saved within $CFG['auth_list']   ##
## so it is global available for every function. This function will  ##
## run only once to reduce SQL queries.                              ##
##                                                                   ##
#######################################################################

function phpbb_get_auth_list()
{
    global $CFG, $userdata;

    if (!$CFG['auth_called'] and $CFG['auth_check'])
    {
        $is_auth = auth(AUTH_VIEW, AUTH_LIST_ALL, $userdata);

        while (list($k, $v) = each($is_auth))
        {
            if ($v['auth_view'])
            {
                $CFG['auth_list'][] = $k;
            }
        }

        $CFG['auth_called'] = true;
    }

    return true;
} // end func phpbb_get_auth_list

#######################################################################
##                                                                   ##
## phpbb_get_forum_list()                                            ##
## ----------------------------------------------------------------- ##
## This function will create a list of forums based on the forums    ##
## passed as a parameter and the forums which are in                 ##
## $CFG['auth_list']. Basically it will filter the forums passed by  ##
## against the allowed forums (if auth check is enabled).            ##
##                                                                   ##
#######################################################################

function phpbb_get_forum_list($forum_id = null)
{
    global $CFG;

    $result = '';

    if (!$forum_id and $CFG['auth_check'])
    {
        reset($CFG['auth_list']);

        while (list($k, $v) = each($CFG['auth_list']))
        {
            $result .= $v . ',';
        }

        if ($result)
        {
            $result = substr($result, 0, strlen($result) -1);
        }
    }

    if ($forum_id)
    {
        if (!is_array($forum_id))
        {
            if ($CFG['auth_check'])
            {
                if (in_array($forum_id, $CFG['auth_list']))
                {
                    $result = $forum_id;
                }
            }
            else
            {
                $result = $forum_id;
            }
        }
        else
        {
            for ($i = 0; $i < count($forum_id); $i++)
            {
                if ($CFG['auth_check'])
                {
                    if (in_array($forum_id[$i], $CFG['auth_list']))
                    {
                        $result .= $forum_id[$i] . ',';
                    }
                }
                else
                {
                    $result .= $forum_id[$i] . ',';
                }
            }
            if ($result)
            {
                $result = substr($result, 0, strlen($result) -1);
            }
        }
    }

    return $result;
} // end func phpbb_get_forum_list

?>
