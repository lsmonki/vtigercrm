<?php
//
// General
//

// Configuration - Change the name of weblogs by editing these entries here.
// Remember that this is a PHP file, so you must use \' instead of '
$lang['Weblog_name'] = 'Blog';
$lang['Weblog_name_plural'] = 'Blogs';
$lang['Webloggers'] = 'Bloggers';

$lang['Weblogger_online_color'] = '%s' . $lang['Webloggers'] . '%s';

$lang['Weblog'] = $lang['Weblog_name'];
$lang['Weblog_Index'] = $lang['Weblog_name'] . ' Index';
$lang['Weblog_Weblogs'] = $lang['Weblog_name_plural'];
$lang['Weblog_MyWeblog'] = 'My ' . $lang['Weblog_name'];
$lang['Weblog_CP'] = $lang['Weblog_name'] . ' CP';
$lang['Weblog_Owner'] = $lang['Weblog_name'] . ' Owner';
$lang['Weblogs'] = $lang['Weblog_name_plural'];

$lang['Weblog_Date_Started'] = 'Date Started';
$lang['Weblog_started'] = $lang['Weblog_name']. ' Started';
$lang['Weblog_no_entries'] = 'No Entries';
$lang['Weblog_age'] = $lang['Weblog_name'] . ' Age';
$lang['Weblog_back'] = 'Back';
$lang['Weblog_forward'] = 'Forward';
$lang['Weblog_next_year'] = 'Next Year';
$lang['Weblog_previous_year'] = 'Previous Year';
$lang['Weblog_next_month'] = 'Next Month';
$lang['Weblog_previous_month'] = 'Previous Month';
$lang['Weblog_calendar'] = 'Calendar';
$lang['Weblog_view_entry'] = 'View Entry';
$lang['Blog_list'] = 'Bloggers List';
$lang['Weblog_AllTopics'] = 'All Topics';

//
// My Weblog
//
$lang['Weblog_Category'] = $lang['Weblog_name'] . ' Category';
$lang['Weblog_Name2'] = $lang['Weblog_name']. ' Name';
$lang['Weblog_Description'] = $lang['Weblog_name'] . ' Description';
$lang['Weblog_Limit'] = 'Number of entries on ' . $lang['Weblog_name'];
$lang['Weblog_Access'] = $lang['Weblog_name'] . ' Accessibility';
$lang['Weblog_access_explain'] = 'This will specify which users can see your ' . $lang['Weblog_name'] . '.';
$lang['Weblog_comments_auth'] = $lang['Weblog_name'] . ' Comments Priviledges';
$lang['Weblog_comments_auth_explain'] = 'This will specify which users can post replies in your ' . $lang['Weblog_name'] . '.<br /> Only you will ever be able to post entries in your ' . $lang['Weblog_name'] . '.';
$lang['Weblog_Visible'] = $lang['Weblog_name'] . ' Visible';
$lang['Weblog_visible_explain'] = 'This will specify whether or not your ' . $lang['Weblog_name'] . ' will be visible in the ' . $lang['Weblog_name'] . ' Index.<br /> Everybody will have to view it via the URL, a link or a bookmark.';
$lang['Weblog_Create'] = 'Create ' . $lang['Weblog_name'];
$lang['Weblog_Create_Explain'] = 'You have not yet created your personal ' . $lang['Weblog_name'] . '. It is very simple to setup your very own personal ' . $lang['Weblog_name'] . ', all you have to do is enter a name and description in the fields below and click the submit button.<br><br>Once you have created your personal ' . $lang['Weblog_name'] . ' you will have your very own ' . $lang['Weblog_name'] . ' where only you will be able to post new topics while other users will be able to read and reply to your posts.';
$lang['Weblog_Update'] = 'Update ' . $lang['Weblog_name'];
$lang['Weblog_Update_Explain'] = 'You have successfully created a personal ' . $lang['Weblog_name'] . '.<br><br>
            <ul><li>You can change the details of your ' . $lang['Weblog_name'] . ' below. Click the \'Update ' . $lang['Weblog_name'] . '\' button to save your changes. </li><br/><br/>
                <li>You can delete your ' . $lang['Weblog_name'] . ' by clicking the \'Delete ' . $lang['Weblog_name'] . '\' button below. This will delete all posts associated with your ' . $lang['Weblog_name'] . ' and <b>cannot</b> be un-done!!!!</li><br /><br />
		    <li><b>Note: Your ' . $lang['Weblog_name'] . ' will <u>not</u> show up on the ' . $lang['Weblog_name'] . ' Index Page until you have made at least one entry.</b></li>
            </ul>';
$lang['Weblog_Manage_Group'] = 'Manage your ' . $lang['Weblog_name'] . ' usergroup';

$lang['Weblog_advanced'] = $lang['Weblog_name'] . ' Mode:</span><br />';
$lang['Weblog_advanced_explain'] = 'Easy Mode is the simplest to modify the appearence of your ' . $lang['Weblog_name'] . '. <br />Advanced Mode allows full customization of your ' . $lang['Weblog_name'] . '.';
$lang['Weblog_advanced_explain2'] = 'Downgrading from Advanced Mode to Easy Mode will remove your customizations to your ' . $lang['Weblog_name'] . '.';
$lang['Weblog_advanced_explain3'] = 'Advanced Mode allows you to use full HTML to customize your ' . $lang['Weblog_name'] . '.';
$lang['Weblog_easy_mode'] = 'Simple Mode';
$lang['Weblog_advanced_mode'] = 'Advanced Mode';
$lang['Weblog_reset'] = 'Reset ' . $lang['Weblog_name'] . ' Style';
$lang['Weblog_reset_explain'] = 'Checking this box will remove all the custom HTML changes that you have made to your ' . $lang['Weblog_name'] . '.<br />This is useful if you want to change your ' . $lang['Weblog_name'] . ' style or undo mistakes that you made when coding.';
$lang['Weblog_comments_label'] = 'Comments Label';
$lang['Weblog_post_comment_label'] = 'Post a Comment Label';
$lang['Weblog_comment_explain'] = 'Use %s to show the number of comments';
$lang['Weblog_show_profile'] = 'Show Profile Info';
$lang['Weblog_display_description'] = 'Display ' . $lang['Weblog_name'] . ' Description';
$lang['Weblog_show_contact'] = 'Show Contact Info';
$lang['Weblog_show_info'] = 'Show ' . $lang['Weblog_name'] . ' Info';
$lang['Weblog_template'] = $lang['Weblog_name'] . ' Template';
$lang['Weblog_template_explain'] = 'Your ' . $lang['Weblog_name'] . ' will use this template as a base.<br/> You can then edit it however you want, or completely replace it with your own designed ' . $lang['Weblog_name'] . ' page.';
$lang['Weblog_page'] = $lang['Weblog_name'] . ' Page HTML';
$lang['Weblog_page_explain'] = 'Feel free to code whatever you want, and how you want it.<br/> Click <a href="weblogs/code.htm" class="nav" target="_phpbb">here</a> to find out what the special code means.';
$lang['Weblog_page_tip'] = 'Tip: Try copying all the stuff in this box to another html editor, then copy it back into this box when you are done.';
$lang['Weblog_face'] = $lang['Weblog_name'] . ' Face';
$lang['Weblog_face_explain'] = 'Feel free to code whatever you want, and how you want it. This is what will be shown on the main ' . $lang['Weblog_name'] . ' index page. <br/> Click <a href="weblogs/code2.htm" class="nav" target="_phpbb">here</a> to find out what the special code means.';
$lang['Weblog_face_tip'] = 'Tip: This is the most crucial customizable part as it will show people what kind of person you are.';
$lang['Weblog_switch_easy'] = 'Switch to Easy Mode';
$lang['Weblog_switch_advanced'] = 'Switch to Advanced Mode';
$lang['Weblog_switch_style'] = 'Reload/Reset Style';
$lang['Keep'] = 'Keep';
$lang['Weblog_style'] = $lang['Weblog_name'] . ' Style/Mode';
$lang['Weblog_style_explain'] = 'Here you can reset your ' . $lang['Weblog_name'] . ' style, or even switch to another ' . $lang['Weblog_name'] . ' style.';
$lang['Weblog_style_explain_advanced'] = 'You can reset your ' . $lang['Weblog_name'] . ' style to undo all the changes you\'ve made to the HTML code in your ' . $lang['Weblog_name'] . '.';
$lang['Weblog_style_switch'] = 'You may also switch from easy mode to advanced mode and vice versa. <br />Doing so however will remove all the custom changes you\'ve made to your ' . $lang['Weblog_name'] . '.';
$lang['Weblog_right'] = 'Right';
$lang['Weblog_center'] = 'Center';

//
// Auth related entries
//
// Note the %s will be replaced with one of the following 'user' arrays
$lang['Weblog_auth_announce'] = 'Sorry, but only %s can post announcements in this ' . $lang['Weblog_name'] . '.';
$lang['Weblog_auth_sticky'] = 'Sorry, but only %s can post sticky messages in this ' . $lang['Weblog_name'] . '.';
$lang['Weblog_auth_read'] = 'Sorry, but only %s can read entries in this ' . $lang['Weblog_name'] . '.';
$lang['Weblog_auth_post'] = 'Sorry, but only %s can post entries in this ' . $lang['Weblog_name'] . '.';
$lang['Weblog_auth_reply'] = 'Sorry, but only %s can reply to posts in this ' . $lang['Weblog_name'] . '.';
$lang['Weblog_auth_edit'] = 'Sorry, but only %s can edit posts in this ' . $lang['Weblog_name'] . '.';
$lang['Weblog_auth_delete'] = 'Sorry, but only %s can delete posts in this ' . $lang['Weblog_name'] . '.';
$lang['Weblog_auth_vote'] = 'Sorry, but only %s can vote in polls in this ' . $lang['Weblog_name'] . '.';

$lang['Weblog_post_can'] = 'You <b>can</b> post new entries in this ' . $lang['Weblog_name'];
$lang['Weblog_post_cannot'] = 'You <b>cannot</b> post new entries in this ' . $lang['Weblog_name'];
$lang['Weblog_reply_can'] = 'You <b>can</b> reply to entries in this ' . $lang['Weblog_name'];
$lang['Weblog_reply_cannot'] = 'You <b>cannot</b> reply to entries in this ' . $lang['Weblog_name'];
$lang['Weblog_edit_can'] = 'You <b>can</b> edit your posts in this ' . $lang['Weblog_name'];
$lang['Weblog_edit_cannot'] = 'You <b>cannot</b> edit your posts in this ' . $lang['Weblog_name'];
$lang['Weblog_delete_can'] = 'You <b>can</b> delete your posts in this ' . $lang['Weblog_name'];
$lang['Weblog_delete_cannot'] = 'You <b>cannot</b> delete your posts in this ' . $lang['Weblog_name'];
$lang['Weblog_vote_can'] = 'You <b>can</b> vote in polls in this ' . $lang['Weblog_name'];
$lang['Weblog_vote_cannot'] = 'You <b>cannot</b> vote in polls in this ' . $lang['Weblog_name'];
$lang['Weblog_moderate'] = 'You <b>can</b> %smanage this ' . $lang['Weblog_name'] . '%s'; // %s replaced by a href links, do not remove!
$lang['Weblog_attach_can'] = 'You <b>can</b> attach files in this ' . $lang['Weblog_name'];
$lang['Weblog_attach_cannot'] = 'You <b>cannot</b> attach files in this ' . $lang['Weblog_name'];
$lang['Weblog_download_can'] = 'You <b>can</b> download files in this ' . $lang['Weblog_name'];
$lang['Weblog_download_cannot'] = 'You <b>cannot</b> download files in this ' . $lang['Weblog_name'];

// Accessibility
$lang['Weblog_auth_all'] = 'All';
$lang['Weblog_auth_reg'] = 'Registered Users';
$lang['Weblog_auth_group'] = 'Your Usergroup';
$lang['Weblog_auth_owner'] = $lang['Weblog_name'] . ' Owner (you)';
$lang['Weblog_auth_entry'] = 'Entry visible to';

//
// Click returns
//
$lang['Weblog_click_return_cp'] = 'Click %sHere%s to return to the ' . $lang['Weblog_name'] .' CP.';
$lang['Click_return_weblog'] = 'Click %sHere%s to return to the ' . $lang['Weblog_name'];
$lang['Click_return_entry'] = 'Click %sHere%s to return to the entry';
$lang['Click_return_weblogs'] = 'Click %sHere%s to return to the ' . $lang['Weblog_name'] . ' Index';


//
// Weblog
//
$lang['Mark_all_weblogs'] = 'Mark all ' . $lang['Weblog_name_plural'] . ' read';
$lang['Weblogs_marked_read'] = 'All ' . $lang['Weblog_name_plural'] . ' have been marked read';
$lang['Mark_all_entries'] = 'Mark all entries read';
$lang['Entries_marked_read'] = 'The entries for this ' . $lang['Weblog_name'] . ' have now been marked read';

$lang['Weblog_created'] = 'Your ' . $lang['Weblog_name'] . ' has been created. <br / ><br />Click %sHere%s to return to the Weblog CP.';
$lang['Weblog_edited'] = 'Your ' . $lang['Weblog_name'] . ' has been updated successfully. <br / ><br />Click %sHere%s to return to the Weblog CP.';
$lang['Weblog_deleted'] = 'Your ' . $lang['Weblog_name'] . ' has been deleted successfully. <br / ><br />Click %sHere%s to return to the Weblog Index.';
$lang['Post_new_entry'] = 'Post new entry';
$lang['Reply_to_entry'] = 'Reply to entry';
$lang['No_entries_guest'] = 'There are no entries in this ' . $lang['Weblog_name'] . '.<br />Please wait for the ' . $lang['Weblog_name'] . '\'s owner to make an entry.';
$lang['No_entries_owner'] = 'There are no entries in this ' . $lang['Weblog_name'] . '.<br />Please make an entry.';
$lang['All_Entries'] = 'All Entries';
$lang['Entries'] = 'Entries';
$lang['Weblog_owner'] = $lang['Weblog_name']. ' Owner';

$lang['No_weblogs'] = 'There are no ' . $lang['Weblog_name_plural'] . '. Please wait for someone to make a ' . $lang['Weblog_name'] . ' or create one yourself.';

$lang['Auth_weblogowner'] = ' the <b>' . $lang['Weblog_name'] . ' owner</b>';

$lang['Stop_watching_entry'] = 'Stop watching this entry';
$lang['Start_watching_entry'] = 'Watch this entry for replies';
$lang['No_longer_watching_entry'] = 'You are no longer watching this entry';
$lang['You_are_watching_entry'] = 'You are now watching this entry';

$lang['Lock_entry'] = 'Lock this entry';
$lang['Unlock_entry'] = 'Unlock this entry';
$lang['Move_entry'] = 'Move this entry';
$lang['Delete_entry'] = 'Delete this entry';
$lang['Split_entry'] = 'Split this entry';

$lang['Today_at'] = '<b>Today</b> at %s'; // %s is the time
$lang['Yesterday_at'] = 'Yesterday at %s'; // %s is the time

$lang['View_All_Entries'] = 'View Past Entries';
$lang['Post_a_new_entry'] = 'Post a new Entry';

$lang['Weblog_memorable_entry'] = 'Memorable Entry';
$lang['Weblog_memorable'] = 'Memorable:';
$lang['Weblog_last_entry'] = 'Last Entry';
$lang['Weblog_last_post'] = 'Last Post/Comment';
$lang['Weblog_latest_mood'] = 'Latest Mood';
$lang['Weblog_last_seen'] = 'Last Seen';
$lang['View'] = 'View';
$lang['No_newer_entries'] = 'There are no newer entries in this ' . $lang['Weblog_name'];
$lang['No_older_entries'] = 'There are no older entries in this ' . $lang['Weblog_name'];
$lang['View_next_entry'] = 'View next entry';
$lang['View_previous_entry'] = 'View previous entry';

$lang['Browsing_weblog'] = 'Users browsing this ' . $lang['Weblog_name'] . ':';

// Sorting
$lang['Weblog_go'] = 'Go';
$lang['Weblog_sort'] = 'Sort:';
$lang['Weblog_per_page'] = $lang['Weblog_name_plural'] . ' per page:';
$lang['Weblog_ascending'] = 'Ascending';
$lang['Weblog_descending'] = 'Descending';
$lang['Weblog_started'] = 'Start Date';
$lang['Weblog_forum_name'] = $lang['Weblog_name'] . ' Name';
$lang['Weblog_username'] = $lang['Weblog_name'] . ' Owner';
$lang['Weblog_post_time'] = 'Last Post Time';

$lang['Weblog_none'] = '(none)';
$lang['Weblog_custom'] = '(custom)';

// Currently
$lang['Weblog_currently'] = 'Currently';

// Mood
$lang['Weblog_mood'] = 'Mood';
$lang['Weblog_mood_none'] = '(none)';

//WebBlog Category
$lang['Weblog_Categories'] = 'Blog Category';
$lang['Weblog_Categories_currently'] = 'Current Weblog Categories';
$lang['Weblog_Categories_name'] = 'Name';
$lang['Weblog_Categories_edit'] = 'Edit';
$lang['Weblog_Categories_remove'] = 'Remove';

// Statistics
$lang['Weblog_stats'] = $lang['Weblog_name'] . ' Statistics';
$lang['Weblog_last_updated'] = 'Latest Topics';
$lang['Weblog_newest'] = 'Newest ' . $lang['Weblog_name_plural'] . '';
$lang['Weblog_popular_authors'] = 'Most Popular Authors';
$lang['Weblog_most_entries'] = 'Most Prolific Bloggers';
$lang['Weblog_most_popular'] = 'Popular Articles for the Week';
$lang['Weblog_number'] = 'Topics';
$lang['Weblog_date'] = 'Date/Time';
$lang['Weblog_owner2'] = 'Owner';
$lang['Weblog_visits'] = 'Visits';

// Error Messages
$lang['Weblog_Cat_Error'] = 'You have not specified any name to the Category';
$lang['Weblog_Not_Authorised'] = 'You are not authorised to create a ' . $lang['Weblog_name'];
$lang['Weblog_already'] = 'You already have a ' . $lang['Weblog_name'];
$lang['Weblog_not_exist'] = 'The ' . $lang['Weblog_name'] . ' you selected does not exist or you have not logged in.';
$lang['Weblog_locked'] = 'This ' . $lang['Weblog_name'] . ' is locked: you cannot post, reply to, or edit entries.';
$lang['Entry_locked'] = 'This entry is locked: you cannot edit posts or make replies.';
$lang['Entry_post_not_exist'] = 'The entry or post you requested does not exist';
$lang['No_entry_id'] = 'You must select an entry to reply to';
$lang['Weblog_is_locked'] = $lang['Weblog_name'] . ' is locked';
$lang['Weblog_create_error'] = 'You must select a category and provide a name and description for your ' . $lang['Weblog_name'] . '.';
$lang['Weblog_not_allowed'] = 'You are not allowed to view this ' . $lang['Weblog_name'] . '.';
$lang['Weblog_no_body'] = 'Could not find ' . $lang['Weblog_name'] . ' body file. Please contact your Board Administrator.';
$lang['Weblog_no_face'] = 'Could not find ' . $lang['Weblog_name'] . ' face file. Please contact your Board Administrator.';
$lang['Weblog_no_styles'] = 'There are no ' . $lang['Weblog_name'] . ' styles installed on this board, thus ' . $lang['Weblog_name_plural'] . ' cannot be created. Please contact your Board Administrator.';
$lang['Weblog_access_error'] = 'Some users are given the ability to reply and yet they can\'t see your ' . $lang['Weblog_name'] . '.<br />Please check your authorization settings.';
$lang['Weblog_no_header'] = 'The ' . $lang['Weblog_name'] . ' header file for this style is missing, so your ' . $lang['Weblog_name'] . ' cannot be created at this time. Please contact your Board Administrator.';
$lang['Weblog_no_footer'] = 'The ' . $lang['Weblog_name'] . ' footer file for this style is missing, so your ' . $lang['Weblog_name'] . ' cannot be created at this time. Please contact your Board Administrator.';
$lang['Weblog_no_body'] = 'The ' . $lang['Weblog_name'] . ' body file for this style is missing, so your ' . $lang['Weblog_name'] . ' cannot be created at this time. Please contact your Board Administrator.';
$lang['Weblog_no_face'] = 'The ' . $lang['Weblog_name'] . ' face file for this style is missing, so your ' . $lang['Weblog_name'] . ' cannot be created at this time. Please contact your Board Administrator.';
?>
