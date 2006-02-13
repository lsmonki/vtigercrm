<?php
#######################################################################
##                                                                   ##
## phpBB Fetch All - displays phpBB on any page                      ##
## ----------------------------------------------------------------- ##
## A portal example file.                                            ##
##                                                                   ##
#######################################################################
##                                                                   ##
## Authors: Volker 'Ca5ey' Rattel <ca5ey@clanunity.net>              ##
##          http://clanunity.net/portal_home.php                     ##
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


//
// This path points to the directory where phpBB is installed. Do
// not enter an URL here. The path must end with a trailing
// slash.
//
// Examples:
// forum in /aaa/bbb/ccc/ and script in /aaa/bbb/ccc/
// --> $phpbb_root_path = './';
// forum in /aaa/bbb/ccc/ and script in /aaa/bbb/
// --> $phpbb_root_path = './ccc/';
// forum in /aaa/bbb/ccc/ and script in /aaa/bbb/ddd/
// --> $phpbb_root_path = '../ccc/';
//

//$phpbb_root_path = './';
$PHP_SELF = 'portal_home.php';
//
// this setting is for the clanunity site - ignore it
//
// $phpbb_root_path = './forum/';

define ('IN_PHPBB', true);

if (!file_exists($phpbb_root_path . 'extension.inc'))
{
    die ('<tt><b>phpBB Fetch All:</b>
          $phpbb_root_path is wrong and does not point to your forum.</tt>');
}

//
// phpBB related files
//

include_once ($phpbb_root_path . 'extension.inc');
include_once ($phpbb_root_path . 'common.' . $phpEx);
include_once ($phpbb_root_path . 'includes/bbcode.' . $phpEx);


//
// Fetch All related files - we do need all these because the portal is a
// huge example
//
include_once ($phpbb_root_path . 'mods/phpbb_fetch_all/common.' . $phpEx);
include_once ($phpbb_root_path . 'mods/phpbb_fetch_all/stats.' . $phpEx);
include_once ($phpbb_root_path . 'mods/phpbb_fetch_all/users.' . $phpEx);
include_once ($phpbb_root_path . 'mods/phpbb_fetch_all/polls.' . $phpEx);
include_once ($phpbb_root_path . 'mods/phpbb_fetch_all/posts.' . $phpEx);
include_once ($phpbb_root_path . 'mods/phpbb_fetch_all/forums.' . $phpEx);

//
// start session management
//

$userdata = session_pagestart($user_ip, PAGE_INDEX, $session_length);
init_userprefs($userdata);

//
// since we are demonstrating span pages we need to set the page offset
//

if (isset($HTTP_GET_VARS['start']) or isset($HTTP_POST_VARS['start'])) {
    $CFG['posts_span_pages_offset'] = isset($HTTP_GET_VARS['start']) ? $HTTP_GET_VARS['start'] : $HTTP_POST_VARS['start'];
}

// sorting variable getting from post or get method, added by mano.
$order_type = $HTTP_POST_VARS['order_type'] ? $HTTP_POST_VARS['order_type'] : $HTTP_GET_VARS['order_type'];
$order_column = $HTTP_POST_VARS['order_column'] ? $HTTP_POST_VARS['order_column'] : $HTTP_GET_VARS['order_column'];
//Added By David For Sorting :
if ($order_column != "")
{
	setcookie("cookie_order", "$order_column", time() + 31536000,"/");
}
else
	$order_column = $_COOKIE['cookie_order'];
if ($order_type != "")
{
	setcookie("cookie_type", "$order_type", time() + 31536000,"/");
}
else
	$order_type = $_COOKIE['cookie_type'];



// fetch new posts since last visit
$new_posts = phpbb_fetch_new_posts();

// fetch user online, total posts, etc
$stats       = phpbb_fetch_stats();

// fetch five users by total posts
$top_poster  = phpbb_fetch_top_poster();

// fetch a random user
$random_user = phpbb_fetch_random_user();

// fetch forum structure
// example for array
//$forums      = phpbb_fetch_forums(array(2,3,4,5,6,7,8,11));
$forums      = phpbb_fetch_forums();


// fetch user of a specific group
// this function is disabled because fetching without a specific
// user group can produces a lot of results (all registered users)
// and this may result in an internal server error. If you want to
// use this feature please specify the group id.
# $member      = phpbb_fetch_users();

// fetch a poll
$poll        = phpbb_fetch_poll();

// fetch a single topic by topic id
$download    = phpbb_fetch_topics(0);

// fetch latest postings
$CFG['posts_trim_topic_number'] = 38;
$recent      = phpbb_fetch_posts(null, POSTS_FETCH_LAST);

// fetch postings
$CFG['posts_trim_topic_number'] = 0;
$CFG['posts_span_pages']        = true;
$news        = phpbb_fetch_posts();

//
// these settings are for the clanunity site - ignore them
//
// $forums      = phpbb_fetch_forums(5);
// $member      = phpbb_fetch_users(83);
// $poll        = phpbb_fetch_poll(12);
// $download    = phpbb_fetch_topics(623);
// $CFG['posts_trim_topic_number'] = 25;
// $recent      = phpbb_fetch_posts(12, POSTS_FETCH_LAST);
// $CFG['posts_trim_topic_number'] = 0;
// $CFG['posts_span_pages'] = true;
// $news        = phpbb_fetch_posts(11);

//
// disconnect from the database
//

include ('includes/page_header.php');
phpbb_disconnect();

?>
<!--
<html>
<head>
<title>Welcome : vtiger Forums</title>
<style type="text/css">
</style>
<script language="javascript">
function SetLastVisit()
{
	if (!document.LastVisitFrm.search_time.options[0].selected)
{
document.LastVisitFrm.submit();
}
	return true;
}
</script>
</head>
<body bgcolor="#E5E5E5" text="#000000" link="#006699" vlink="#5493B4">

<table align="center" border="0" cellpadding="2" cellspacing="0" width="100%">

<tr class="bodyline">

<td>
-->

<!-- USER -->
<?php if ($userdata) { ?>
<center>
<table width="99.5%" cellpadding="3" cellspacing="1" border="0" class="forumline">
<?php if ($userdata['session_logged_in']) { ?>
<span class="gensmall">
<tr>
<td align="right" class="gensmall"><b><a href="<?php echo $phpbb_root_path; ?>privmsg.php?folder=inbox">You have
<?php if ($userdata['user_new_privmsg']) { echo $userdata['user_new_privmsg']; } else { echo 'no'; } ?> new message<?php echo $userdata['user_new_privmsg'] == 1 ? '' : 's' ?></a></b></td>
<td align="right" class="gensmall"><b><a href="<?php echo $phpbb_root_path; ?>search.php?search_id=newposts">View posts since last visit
(<?php echo $new_posts['total']; ?>)</a></b></td>
<td align="right" class="gensmall"><b><a href="<?php echo $phpbb_root_path; ?>search.php?search_id=egosearch">View your posts</a></b></td>
<td align="right" class="gensmall"><b><a href="<?php echo $phpbb_root_path; ?>search.php?search_id=unanswered">View unanswered posts</a></b></td>
</tr>
</span>
</table>
</center>
<?php } ?>
<?php } ?>
<!-- USER -->

<table border="0" cellpadding="2" cellspacing="1" width="100%">
<tr>
<td align="right" width="100%">
<!-- SPAN PAGES -->
<span class="gensmall"><b><?php echo phpbb_span_pages($CFG['posts_span_pages_numrows'], $CFG['posts_limit'], $CFG['posts_span_pages_offset'], false); ?></b></span>
<!-- SPAN PAGES -->
</td>
<tr>
</table>

<table border="0" cellpadding="2" cellspacing="1" width="100%">
<tr>
<td valign="top" width="50%">



<!-- FORUMS -->

<?php if ($forums) { ?>
<?php $bgcolor ="#E7E6E6" ?>
<table width="100%" cellpadding="3" cellspacing="0" border="0" class="forumline">
  <tr>
    <!-- th class="thTop" height="28" colspan="3">Forum</th -->
    <!-- th class="thTop" height="28">Topics</th>
    <th class="thTop" height="28">Posts</th -->
  </tr>
<?php $last_cat = -1; ?>
<?php for ($i = 0; $i < count($forums); $i++) { ?>

<?php
        if ($bgcolor == "#F3F3F3")
		{
			$bgcolor = "#F3F3F3";
    	}
		else
		{
			$bgcolor = "#F3F3F3";
    	} ?>

<?php if ($last_cat != $forums[$i]['cat_id']) { ?>
<?php $last_cat = $forums[$i]['cat_id']; ?>

  <tr>
    <td class="catLeft" colspan="3" height="28"><span class="cattitle"><a href="<?php echo $phpbb_root_path; ?>index.php?c=<?php echo $forums[$i]['cat_id']; ?>"><font color="#000000"><?php echo $forums[$i]['cat_title']; ?></font></a></span></td>
  </tr>
<?php } ?>
  <tr>
  <td bgcolor="<?php echo($bgcolor) ?>" width="5%" nowrap>&nbsp;</td>
  <td bgcolor="<?php echo($bgcolor) ?>" align="left" width="10%" nowrap>

    <span class="forumlink">
      <?php
      $desc=$forums[$i]['forum_desc'];
      $desc=strstr($desc, "<a");
      ?>
      <a href="<?php echo $phpbb_root_path; ?>viewforum.php?f=<?php echo $forums[$i]['forum_id']; ?>" class="gensmall"><font color="#993300"><?php echo $forums[$i]['forum_name']; ?></font><?php if ($forums[$i]['topic_trimmed']) { echo '...'; } ?></a></b>
      </span></td>
      <td bgcolor="<?php echo($bgcolor) ?>" align="left" ><span class="gensmall"><?php echo($desc); ?></span>
      </td>
    </td>
  </tr>
<?php } ?>
</table>

<p/>
<?php } ?>
<!-- FORUMS -->

</td>


<td valign="top" width="50%">

<!-- RECENT -->


<?php

//Added By David For Sorting:

if (($order_type == 'ASEN') || ($order_type == ""))
{
       $order_type = "DESC";
}
else
{
       	$order_type = "ASEN";
}
function swapImage($ocolumn,$orig_ocoloumn,$otype)
{
	$uimage = "<img src=\"images/up.gif\" border=\"0\">";
	$dimage = "<img src=\"images/down.gif\" border=\"0\">";
	if ($otype == "DESC")
	{
		$image = $dimage;
	}
	else
	{
		$image = $uimage;
	}

	if ($ocolumn == "" && $orig_ocoloumn == "p.post_time")
		return $image;
	if ($ocolumn != $orig_ocoloumn)
	{
		$image = "";
	}
	return $image;
}


 if ($recent) { ?>
<?php $bgcolor ="#E7E6E6" ?>
<table width="100%" cellpadding="3" cellspacing="0" border="0" class="forumline">
<tr>
    <!-- th class="forumHeader" height="28" align="center"colspan="4"><span class="thTop">Latest Discussion</span></th -->
</tr>
<tr>
    <th align="left"><a href="portal_home.php?order_column=p.post_time&order_type=<?php echo $order_type; ?>"><font face="verdana" color="#000000">Time</font></a> <a href="portal_home.php?order_column=p.post_time&order_type=<?php echo $order_type; ?>"><?php echo swapImage($order_column,'p.post_time',$order_type); ?></a></font></th>
    <th align="left"><font face="verdana"><a href="portal_home.php?order_column=t.topic_title&order_type=<?php echo $order_type; ?>"><font face="verdana" color="#000000">Latest Posts</font></a> <a href="portal_home.php?order_column=t.topic_title&order_type=<?php echo $order_type; ?>"><?php echo swapImage($order_column,'t.topic_title',$order_type); ?></a></th>
    <th align="left"><font face="verdana"><a href="portal_home.php?order_column=f.forum_name&order_type=<?php echo $order_type; ?>"><font face="verdana" color="#000000">Forum</font> </a><a href="portal_home.php?order_column=f.forum_name&order_type=<?php echo $order_type; ?>"><?php echo swapImage($order_column,'f.forum_name',$order_type); ?></a></th>
    <th align="left"><font face="verdana"><a href="portal_home.php?order_column=username&order_type=<?php echo $order_type; ?>"><font face="verdana" color="#000000">Post By</font></a> <a href="portal_home.php?order_column=username&order_type=<?php echo $order_type; ?>"><?php echo swapImage($order_column,'username',$order_type); ?></a></th>
</tr>

<?php for ($i = 0; $i < count($recent); $i++) { ?>
  <?php
        if ($bgcolor == "bglaternate1")
		{
			$bgcolor = "bglaternate2";
    	}
		else
		{
			$bgcolor = "bglaternate1";
    	} ?>
<tr>
<td class="<?php echo($bgcolor) ?>" align="left" width="1%" nowrap>
<span class="gensmall">
<?php echo date($CFG['date_format']. ' ' . $CFG['time_format'], $recent[$i]['post_time']); ?></td>
<td class="<?php echo($bgcolor) ?>" align="left" width="52%">
<span class="gensmall"><a href="<?php echo $phpbb_root_path; ?>viewtopic.php?p=<?php echo $recent[$i]['post_id']; ?>#<?php echo $recent[$i]['post_id']; ?>"><font color="#535353"><?php echo $recent[$i]['topic_title']; ?><?php if ($recent[$i]['topic_trimmed']) { echo '...'; } ?></font></a></td>
<td class="<?php echo($bgcolor) ?>" align="left" width="30%"><span class="gensmall"><a href="<?php echo $phpbb_root_path; ?>viewforum.php?f=<?php echo $recent[$i]['forum_id']; ?>"><font color="#535353"><?php echo $recent[$i]['forum_name']; ?></font></a></td>
<td class="<?php echo($bgcolor) ?>" align="left" width="16%"><span class="gensmall"><a href="<?php echo $phpbb_root_path; ?>profile&mode=viewprofile&u=<?php echo $recent[$i]['user_id']; ?>"><font color="#535353"><?php echo $recent[$i]['username']; ?></font></a><br /></td>
<?php } ?>
</span>
</td>
</tr>
</table>
<?php } ?>
<!-- RECENT -->

<!-- SPAN PAGES -->
<div align="right">
<span class="gensmall"><b><?php echo phpbb_span_pages($CFG['posts_span_pages_numrows'], $CFG['posts_limit'], $CFG['posts_span_pages_offset'], false); ?></b></span>
</div>
<!-- SPAN PAGES -->


<!-- MEMBER -->
<?php if ($member) { ?>
<table width="100%" cellpadding="3" cellspacing="1" border="0" class="forumline">
  <tr>
    <td class="catHead" height="28"><span class="cattitle"><?php echo $member[0]['group_name']; ?></span></td>
  </tr>
  <tr>
    <td class="row1" align="left" width="100%">
      <span class="gensmall">
<?php for ($i = 0; $i < count($member); $i++) { ?>
<a href="<?php echo $phpbb_root_path; ?>profile.php?mode=viewprofile&amp;u=<?php echo $member[$i]['user_id']; ?>"><b><?php echo $member[$i]['username']; ?></b></a><?php if ($i < (count($member)-1)) { ?>,
<?php } ?>
<?php } ?>
      </span>
    </td>
  </tr>
</table>
<?php } ?>
<!-- MEMBER -->

</td>
</tr>
</table>

<!--div align="center"><span class="copyright"><br />
Powered by <a href="http://www.phpbb.com/" target="_phpbb" class="copyright">phpBB</a> <?php echo '2' . $board_config['version']; ?> &copy; 2001, 2002 phpBB Group<br /></span></div>

</td>
</tr>
</table>

</body>
</html-->
<?php
include($phpbb_root_path . 'includes/page_tail.'.$phpEx);
?>
