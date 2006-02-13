<?php 
/*************************************************************************** 
*                        favorites.php 
*                            ------------------- 
*   begin                : Monday, Jan 20, 2003 
*   copyright            : (C) 2003 Daniel Taylor 
*   email                : danielt@hackermail.com 
*   $Id: favorites.php,v 1.9 2005/01/04 15:04:11 saraj Exp $
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
echo $modifiedphp_root;
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

if( !$userdata['session_logged_in'] ) 
{ 
   header("Location: " . append_sid($modifiedphp_root . "login" . "?redirect=" . $PHP_SELF)); 
   exit; 
} 

if ( isset($HTTP_GET_VARS['mode']) ) 
{ 
   $mode = ($HTTP_GET_VARS['mode']); 

   if ( $mode == 'add' ) 
   { 
      if ( isset($HTTP_GET_VARS['t'])) 
      { 
	 $topic_id = ($HTTP_GET_VARS['t']);
         $user_id = ($userdata['user_id']); 
	   //START // 1.0.2 update // Check For Attepmted Double Fav Adding
	   $sql = "SELECT * FROM " . $table_prefix . "favorites WHERE user_id = '" . $user_id . "' AND topic_id = '" . $topic_id ."'";
	   $result = $db->sql_query($sql);
	   $num_row = $db->sql_numrows($result);
		if ($num_row <= "0" ) {
         		$sql = "INSERT INTO " . $table_prefix . "favorites (fav_id, user_id, topic_id) VALUES (NULL, '$user_id', '$topic_id')"; 
       
         		if ( !($result = $db->sql_query($sql)) ) 
         		{ 
         		   message_die(GENERAL_ERROR, $lang['insert_fav_data'], '', __LINE__, __FILE__, $sql); 
         		} 
		}
		else {
         		   message_die(GENERAL_ERROR, $lang['exist_fav']);
		}
	   //END // 1.0.2 update // Check For Attepmted Double Fav Adding			
      } 
      if ( !(isset($HTTP_GET_VARS['t'])) ) 
      { 
      message_die(GENERAL_MESSAGE, 'fav_no_topic'); 
      exit; 
      } 
   $header_location = ( @preg_match("/Microsoft|WebSTAR|Xitami/", getenv("SERVER_SOFTWARE")) ) ? "Refresh: 0; URL=" : "Location: "; 
   header($header_location . append_sid("favorites." . $phpEx, true)); 
   exit; 
   } 
   if ( $mode == 'remove' ) 
   { 
      if ( isset($HTTP_GET_VARS['t'])) 
      { 
         $topic_id = (intval($HTTP_GET_VARS['t']));          
         $user_id = ($userdata['user_id']); 
         $sql = "DELETE FROM " . $table_prefix . "favorites WHERE user_id = '$user_id' AND topic_id = '$topic_id'"; 
       
         if ( !($result = $db->sql_query($sql)) ) 
         { 
            message_die(GENERAL_ERROR, $lang['remove_fav_data'], '', __LINE__, __FILE__, $sql); 
         } 
      } 
      if ( !(isset($HTTP_GET_VARS['t'])) ) 
      { 
      message_die(GENERAL_MESSAGE, $lang['no_fav_topic']); 
      exit; 
      } 
   $header_location = ( @preg_match("/Microsoft|WebSTAR|Xitami/", getenv("SERVER_SOFTWARE")) ) ? "Refresh: 0; URL=" : "Location: "; 
   header($header_location . append_sid("favorites." . $phpEx, true)); 
   exit;    
   } 
} 
else 
{ 
include($phpbb_root_path . 'includes/page_header.'.$phpEx); 
$user_id = ($userdata['user_id']); 
$template->set_filenames(array( 
   'body' => 'fav_body.tpl') 
); 

$template->assign_vars(array(
	'L_TOPIC' => $lang['Topic'],
	'U_INDEX' => append_sid("index.$phpEx"),
	'L_INDEX' => $lang['Index'],
	'L_REPLIES' => $lang['Replies'],
	'L_LASTPOST' => $lang['Last_Post'], 
	'L_AUTHOR' => $lang['Author'],
	'L_VIEWS' => $lang['Views'],
	'L_DELETE' => $lang['Delete'])
);
$sql = "SELECT " . $table_prefix . "favorites.topic_id, " . $table_prefix . "topics.*
    FROM " . $table_prefix . "favorites LEFT JOIN " . $table_prefix . "topics 
    ON " . $table_prefix . "favorites.topic_id = " . $table_prefix . "topics.topic_id 
    WHERE " . $table_prefix . "favorites.user_id = '" . $userdata['user_id'] . "'";        
$result = $db->sql_query($sql); 
while ( $row = $db->sql_fetchrow($result) ) {
//START // 1.0.2 update // Simple Post Icon
if( $row['topic_type'] == POST_ANNOUNCE )
{
	$folder = $images['folder_announce'];
	//$folder_new = $images['folder_announce_new'];
	$folder_alt = $lang['Post_Announcement'];
	$folder_text = $lang['Topic_Announcement'];
}
else if( $row['topic_type'] == POST_STICKY )
{
	$folder = $images['folder_sticky'];
	//$folder_new = $images['folder_sticky_new'];
	$folder_alt = $lang['Post_Sticky'];
	$folder_text = $lang['Topic_Sticky'];
}
else {
	$folder = $images['folder'];
	$folder_new = $images['folder_new'];
	$folder_alt = $lang['Post_Normal'];
	$folder_text = "";
}
if( $row['topic_status'] == 1 )
{
	$folder = $images['folder_locked'];
	//$folder_new = $images['folder_locked_new'];
	$folder_alt = $lang['Topic_locked'];
	$folder_text = "";
}
//END // 1.0.2 update // Simple Post Icon
   $template->assign_block_vars('topicrow', array(
   	'S_FOLDER' => $folder,
	'S_FOLDER_ALT' => $folder_alt,
	'S_FOLDER_TEXT' => $folder_text,
	'L_TOPIC_TITLE' => $row['topic_title'],
	'VIEWS' => $row['topic_views'],
	'REPLIES' => $row['topic_replies'],
	'U_TOPIC_TITLE' => append_sid("viewtopic.$phpEx?t=" . $row['topic_id']),
	'L_REMOVE' => $lang['Delete'],
	'U_REMOVE' => append_sid("favorites.$phpEx?mode=remove&t=" . $row['topic_id'])) 
   ); 
}
$template->pparse('body'); 
include($phpbb_root_path . 'includes/page_tail.'.$phpEx); 
} 
?>