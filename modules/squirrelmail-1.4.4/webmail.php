<?php

/**
 * webmail.php -- Displays the main frameset
 *
 * Copyright (c) 1999-2005 The SquirrelMail development team
 * Licensed under the GNU GPL. For full terms see the file COPYING.
 *
 * This file generates the main frameset. The files that are
 * shown can be given as parameters. If the user is not logged in
 * this file will verify username and password.
 *
 * @version $Id$
 * @package squirrelmail
 */

/**
 * Path for SquirrelMail required files.
 * @ignore
 */

/*
 global $theme;
 $theme_path="themes/".$theme."/";
 $image_path=$theme_path."images/";
 require_once ($theme_path."layout_utils.php");
*/


define('SM_PATH','modules/squirrelmail-1.4.4/');

/* SquirrelMail required files. */
require_once(SM_PATH . 'functions/strings.php');
require_once(SM_PATH . 'config/config.php');
require_once(SM_PATH . 'functions/prefs.php');
require_once(SM_PATH . 'functions/imap.php');
require_once(SM_PATH . 'functions/plugin.php');
require_once(SM_PATH . 'functions/i18n.php');
require_once(SM_PATH . 'functions/auth.php');
require_once(SM_PATH . 'functions/global.php');

if (!function_exists('sqm_baseuri')){
    require_once(SM_PATH . 'functions/display_messages.php');
}
$base_uri = sqm_baseuri();

sqsession_is_active();

sqgetGlobalVar('username', $username, SQ_SESSION);
sqgetGlobalVar('delimiter', $delimiter, SQ_SESSION);
sqgetGlobalVar('onetimepad', $onetimepad, SQ_SESSION);
sqgetGlobalVar('right_frame', $right_frame, SQ_GET);
if (sqgetGlobalVar('sort', $sort)) {
    $sort = (int) $sort;
}

if (sqgetGlobalVar('startMessage', $startMessage)) {
    $startMessage = (int) $startMessage;
}

if (!sqgetGlobalVar('mailbox', $mailbox)) {
    $mailbox = 'INBOX';
}

if ( isset($_SESSION['session_expired_post']) ) {
    sqsession_unregister('session_expired_post');
}

is_logged_in();

//do_hook('webmail_top');

/**
 * We'll need this to later have a noframes version
 *
 * Check if the user has a language preference, but no cookie.
 * Send him a cookie with his language preference, if there is
 * such discrepancy.
 */
$my_language = getPref($data_dir, $username, 'language');
if ($my_language != $squirrelmail_language) {
    setcookie('squirrelmail_language', $my_language, time()+2592000, $base_uri);
}

set_up_language(getPref($data_dir, $username, 'language'));

$output = "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 \">\n".
          "<html><head>\n" .
          "<title>$org_title</title>\n".
          "</head>";
//$output = "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Frameset//EN\">\n".
  //        "<html><head>\n" .
    //      "<title>$org_title</title>\n".
      //    "</head>";

/*
$left_size = getPref($data_dir, $username, 'left_size');
$location_of_bar = getPref($data_dir, $username, 'location_of_bar');

if (isset($languages[$squirrelmail_language]['DIR']) &&
    strtolower($languages[$squirrelmail_language]['DIR']) == 'rtl') {
    $temp_location_of_bar = 'right';
} else {
    $temp_location_of_bar = 'left';
}

if ($location_of_bar == '') {
    $location_of_bar = $temp_location_of_bar;
}
$temp_location_of_bar = '';

if ($left_size == "") {
    if (isset($default_left_size)) {
         $left_size = $default_left_size;
    }
    else {
        $left_size = 200;
    }
}

if ($location_of_bar == 'right') {
    //$output .= "<frameset cols=\"*, $left_size\" id=\"fs1\">\n";
}
else {
    //$output .= "<frameset cols=\"$left_size, *\" id=\"fs1\">\n";
}

*/
/*
 * There are three ways to call webmail.php
 * 1.  webmail.php
 *      - This just loads the default entry screen.
 * 2.  webmail.php?right_frame=right_main.php&sort=X&startMessage=X&mailbox=XXXX
 *      - This loads the frames starting at the given values.
 * 3.  webmail.php?right_frame=folders.php
 *      - Loads the frames with the Folder options in the right frame.
 *
 * This was done to create a pure HTML way of refreshing the folder list since
 * we would like to use as little Javascript as possible.
 */

if (empty($right_frame) || (strpos(urldecode($right_frame), '://'))) {
    $right_frame = '';
}
$right_frame = 'index.php?module=squirrelmail-1.4.4&action=right_main';
if ($right_frame == 'right_main') {
    $urlMailbox = urlencode($mailbox);
    $right_frame_url = "index.php?module=squirrelmail-1.4.4&action=right_main&mailbox=$urlMailbox"
                       . (!empty($sort)?"&amp;sort=$sort":'')
                       . (!empty($startMessage)?"&amp;startMessage=$startMessage":'');
} elseif ($right_frame == 'index.php?module=squirrelmail-1.4.4&action=options') {
    $right_frame_url = 'index.php?module=squirrelmail-1.4.4&action=options';
} elseif ($right_frame == 'index.php?module=squirrelmail-1.4.4&action=folders') {
    $right_frame_url = 'index.php?module=squirrelmail-1.4.4&action=folders';
} else if ($right_frame == '') {
    $right_frame_url = 'index.php?module=squirrelmail-1.4.4&action=right_main';
} else {
    $right_frame_url =  htmlspecialchars($right_frame);
}

/*

if ($location_of_bar == 'right')
{
    $output .= "<frame src=\"$right_frame_url\" name=\"right\" frameborder=\"1\" />\n" .
               "<frame src=\"index.php?module=squirrelmail-1.4.4&action=left_main\" name=\"left\" frameborder=\"1\" />\n";
}
else
{
    $output .= "<frame src=\"index.php?module=squirrelmail-1.4.4&action=left_main\" name=\"left\" frameborder=\"1\" />\n".
               "<frame src=\"$right_frame_url\" name=\"right\" frameborder=\"1\" />\n";
}

*/
$smodule = $_REQUEST['smodule'];
header("Location: index.php?module=squirrelmail-1.4.4&action=right_main&smodule=$smodule");


$ret = concat_hook_function('webmail_bottom', $output);
if($ret != '') {
    $output = $ret;
}
echo $output;
?>
<!--/frameset-->
</html>
