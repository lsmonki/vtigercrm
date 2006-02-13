<?php

require_once('database/DatabaseConnection.php');
//
// phpBB2 - MySQL schema
//
function create_populate_phpbb()
{
	$query = "CREATE TABLE phpbb_attach_quota (
		user_id mediumint(8) unsigned NOT NULL default '0',
	group_id mediumint(8) unsigned NOT NULL default '0',
	quota_type smallint(2) NOT NULL default '0',
	quota_limit_id mediumint(8) unsigned NOT NULL default '0',
	KEY quota_type (quota_type)
		)";

	mysql_query($query);
	//
	// Table structure for table `phpbb_attachments`
	//

	$query = "CREATE TABLE phpbb_attachments (
		attach_id mediumint(8) unsigned NOT NULL default '0',
	post_id mediumint(8) unsigned NOT NULL default '0',
	privmsgs_id mediumint(8) unsigned NOT NULL default '0',
	user_id_1 mediumint(8) NOT NULL default '0',
	user_id_2 mediumint(8) NOT NULL default '0',
	KEY attach_id_post_id (attach_id,post_id),
	KEY attach_id_privmsgs_id (attach_id,privmsgs_id)
		)";

	mysql_query($query);
	//
	// Dumping data for table `phpbb_attachments`
	//

	mysql_query("INSERT INTO phpbb_attachments VALUES (1,588,0,242,0)");
	mysql_query("INSERT INTO phpbb_attachments VALUES (2,618,0,88,0)");
	mysql_query("INSERT INTO phpbb_attachments VALUES (3,622,0,229,0)");
	mysql_query("INSERT INTO phpbb_attachments VALUES (4,678,0,443,0)");
	mysql_query("INSERT INTO phpbb_attachments VALUES (5,705,0,443,0)");
	mysql_query("INSERT INTO phpbb_attachments VALUES (6,708,0,443,0)");
	mysql_query("INSERT INTO phpbb_attachments VALUES (7,757,0,314,0)");
	mysql_query("INSERT INTO phpbb_attachments VALUES (8,765,0,2,0)");
	mysql_query("INSERT INTO phpbb_attachments VALUES (9,927,0,552,0)");
	mysql_query("INSERT INTO phpbb_attachments VALUES (10,933,0,2,0)");

	//
	// Table structure for table `phpbb_attachments_config`
	//

	$query = "CREATE TABLE phpbb_attachments_config (
		config_name varchar(255) NOT NULL default '',
	config_value varchar(255) NOT NULL default '',
	PRIMARY KEY  (config_name)
		)";
	mysql_query($query);

	//
	// Table structure for table `phpbb_attachments_desc`
	//

	$query = "CREATE TABLE phpbb_attachments_desc (
		attach_id mediumint(8) unsigned NOT NULL auto_increment,
	physical_filename varchar(255) NOT NULL default '',
	real_filename varchar(255) NOT NULL default '',
	download_count mediumint(8) unsigned NOT NULL default '0',
	comment varchar(255) default NULL,
	extension varchar(100) default NULL,
	mimetype varchar(100) default NULL,
	filesize int(20) NOT NULL default '0',
	filetime int(11) NOT NULL default '0',
	thumbnail tinyint(1) NOT NULL default '0',
	PRIMARY KEY  (attach_id),
	KEY filetime (filetime),
	KEY physical_filename (physical_filename(10)),
	KEY filesize (filesize)
		)";
	mysql_query($query);

	//
	// Table structure for table `phpbb_auth_access`
	//

	$query = "CREATE TABLE phpbb_auth_access (
		group_id mediumint(8) NOT NULL default '0',
	forum_id smallint(5) unsigned NOT NULL default '0',
	auth_view tinyint(1) NOT NULL default '0',
	auth_read tinyint(1) NOT NULL default '0',
	auth_post tinyint(1) NOT NULL default '0',
	auth_reply tinyint(1) NOT NULL default '0',
	auth_edit tinyint(1) NOT NULL default '0',
	auth_delete tinyint(1) NOT NULL default '0',
	auth_sticky tinyint(1) NOT NULL default '0',
	auth_announce tinyint(1) NOT NULL default '0',
	auth_vote tinyint(1) NOT NULL default '0',
	auth_pollcreate tinyint(1) NOT NULL default '0',
	auth_attachments tinyint(1) NOT NULL default '0',
	auth_mod tinyint(1) NOT NULL default '0',
	auth_download tinyint(1) NOT NULL default '0',
	KEY group_id (group_id),
	KEY forum_id (forum_id)
		)";
	mysql_query($query);


	//
	// Table structure for table `phpbb_banlist`
	//

	$query = "CREATE TABLE phpbb_banlist (
		ban_id mediumint(8) unsigned NOT NULL auto_increment,
	ban_userid mediumint(8) NOT NULL default '0',
	ban_ip varchar(8) NOT NULL default '',
	ban_email varchar(255) default NULL,
	PRIMARY KEY  (ban_id),
	KEY ban_ip_user_id (ban_ip,ban_userid)
		)";
	mysql_query($query);

	//
	// Table structure for table `phpbb_categories`
	//

	$query = "CREATE TABLE phpbb_categories (
		cat_id mediumint(8) unsigned NOT NULL auto_increment,
	cat_title varchar(100) default NULL,
	cat_order mediumint(8) unsigned NOT NULL default '0',
	include_on_index tinyint(1) NOT NULL default '1',
	cat_allow_weblogs tinyint(1) unsigned default '0',
	PRIMARY KEY  (cat_id),
	KEY cat_order (cat_order)
		)";
	mysql_query($query);

	//
	// Table structure for table `phpbb_config`
	//

	$query = "CREATE TABLE phpbb_config (
		config_name varchar(255) NOT NULL default '',
	config_value varchar(255) NOT NULL default '',
	PRIMARY KEY  (config_name)
		)";
	mysql_query($query);

	//
	// Table structure for table `phpbb_confirm`
	//

	$query = "CREATE TABLE phpbb_confirm (
		confirm_id char(32) NOT NULL default '',
	session_id char(32) NOT NULL default '',
	code char(6) NOT NULL default '',
	PRIMARY KEY  (session_id,confirm_id)
		)";
	mysql_query($query);

	//
	// Table structure for table `phpbb_disallow`
	//

	$query = "CREATE TABLE phpbb_disallow (
		disallow_id mediumint(8) unsigned NOT NULL auto_increment,
	disallow_username varchar(25) NOT NULL default '',
	PRIMARY KEY  (disallow_id)
		)";
	mysql_query($query);

	//
	// Table structure for table `phpbb_extension_groups`
	//

	$query = "CREATE TABLE phpbb_extension_groups (
		group_id mediumint(8) NOT NULL auto_increment,
	group_name varchar(20) NOT NULL default '',
	cat_id tinyint(2) NOT NULL default '0',
	allow_group tinyint(1) NOT NULL default '0',
	download_mode tinyint(1) unsigned NOT NULL default '1',
	upload_icon varchar(100) default '',
	max_filesize int(20) NOT NULL default '0',
	forum_permissions varchar(255) NOT NULL default '',
	PRIMARY KEY  (group_id)
		)";
	mysql_query($query);
	//
	// Table structure for table `phpbb_extensions`
	//

	$query = "CREATE TABLE phpbb_extensions (
		ext_id mediumint(8) unsigned NOT NULL auto_increment,
	group_id mediumint(8) unsigned NOT NULL default '0',
	extension varchar(100) NOT NULL default '',
	comment varchar(100) default NULL,
	PRIMARY KEY  (ext_id)
		)";
	mysql_query($query);
	//
	// Table structure for table `phpbb_favorites`
	//

	$query = "CREATE TABLE phpbb_favorites (
		fav_id int(11) NOT NULL auto_increment,
	user_id int(11) NOT NULL default '0',
	topic_id int(11) NOT NULL default '0',
	PRIMARY KEY  (fav_id)
		)";
	mysql_query($query);

	//
	// Table structure for table `phpbb_forbidden_extensions`
	//

	$query = "CREATE TABLE phpbb_forbidden_extensions (
		ext_id mediumint(8) unsigned NOT NULL auto_increment,
	extension varchar(100) NOT NULL default '',
	PRIMARY KEY  (ext_id)
		)";
	mysql_query($query);
	//
	// Table structure for table `phpbb_forum_prune`
	//

	$query = "CREATE TABLE phpbb_forum_prune (
		prune_id mediumint(8) unsigned NOT NULL auto_increment,
	forum_id smallint(5) unsigned NOT NULL default '0',
	prune_days smallint(5) unsigned NOT NULL default '0',
	prune_freq smallint(5) unsigned NOT NULL default '0',
	PRIMARY KEY  (prune_id),
	KEY forum_id (forum_id)
		)";
	mysql_query($query);

	//
	// Table structure for table `phpbb_forums`
	//

	$query = "CREATE TABLE phpbb_forums (
		forum_id smallint(5) unsigned NOT NULL default '0',
	cat_id mediumint(8) unsigned NOT NULL default '0',
	forum_name varchar(150) default NULL,
	forum_desc text,
	forum_status tinyint(4) NOT NULL default '0',
	forum_order mediumint(8) unsigned NOT NULL default '1',
	forum_posts mediumint(8) unsigned NOT NULL default '0',
	forum_topics mediumint(8) unsigned NOT NULL default '0',
	forum_last_post_id mediumint(8) unsigned NOT NULL default '0',
	forum_notify tinyint(1) unsigned NOT NULL default '1',
	prune_next int(11) default NULL,
	prune_enable tinyint(1) NOT NULL default '0',
	auth_view tinyint(2) NOT NULL default '0',
	auth_read tinyint(2) NOT NULL default '0',
	auth_post tinyint(2) NOT NULL default '0',
	auth_reply tinyint(2) NOT NULL default '0',
	auth_edit tinyint(2) NOT NULL default '0',
	auth_delete tinyint(2) NOT NULL default '0',
	auth_sticky tinyint(2) NOT NULL default '0',
	auth_announce tinyint(2) NOT NULL default '0',
	auth_vote tinyint(2) NOT NULL default '0',
	auth_pollcreate tinyint(2) NOT NULL default '0',
	auth_attachments tinyint(2) NOT NULL default '0',
	forum_email varchar(100) default NULL,
	auth_download tinyint(2) NOT NULL default '0',
	forum_views int(11) unsigned default '0',
	PRIMARY KEY  (forum_id),
	KEY forums_order (forum_order),
	KEY cat_id (cat_id),
	KEY forum_last_post_id (forum_last_post_id)
		)";
	mysql_query($query);
	//
	// Table structure for table `phpbb_forums_watch`
	//

	$query = "CREATE TABLE phpbb_forums_watch (
		forum_id smallint(5) unsigned NOT NULL default '0',
	user_id mediumint(8) NOT NULL default '0',
	notify_status tinyint(1) NOT NULL default '0',
	KEY forum_id (forum_id),
	KEY user_id (user_id),
	KEY notify_status (notify_status)
		)";
	mysql_query($query);

	//
	// Table structure for table `phpbb_groups`
	//

	$query = "CREATE TABLE phpbb_groups (
		group_id mediumint(8) NOT NULL auto_increment,
	group_type tinyint(4) NOT NULL default '1',
	group_name varchar(40) NOT NULL default '',
	group_description varchar(255) NOT NULL default '',
	group_moderator mediumint(8) NOT NULL default '0',
	group_single_user tinyint(1) NOT NULL default '1',
	PRIMARY KEY  (group_id),
	KEY group_single_user (group_single_user)
		)";
	mysql_query($query);

	//
	// Table structure for table `phpbb_posts`
	//

	$query = "CREATE TABLE phpbb_posts (
		post_id mediumint(8) unsigned NOT NULL auto_increment,
	topic_id mediumint(8) unsigned NOT NULL default '0',
	forum_id smallint(5) unsigned NOT NULL default '0',
	poster_id mediumint(8) NOT NULL default '0',
	post_time int(11) NOT NULL default '0',
	poster_ip varchar(8) NOT NULL default '',
	post_username varchar(25) default NULL,
	enable_bbcode tinyint(1) NOT NULL default '1',
	enable_html tinyint(1) NOT NULL default '0',
	enable_smilies tinyint(1) NOT NULL default '1',
	enable_sig tinyint(1) NOT NULL default '1',
	post_edit_time int(11) default NULL,
	post_edit_count smallint(5) unsigned NOT NULL default '0',
	post_attachment tinyint(1) NOT NULL default '0',
	weblog_cat_id smallint(5) NOT NULL default '0',
	post_mood tinyint(2) unsigned NOT NULL default '0',
	post_currently1 tinyint(2) unsigned NOT NULL default '0',
	post_currently2 varchar(100) NOT NULL default '',
	PRIMARY KEY  (post_id),
	KEY forum_id (forum_id),
	KEY topic_id (topic_id),
	KEY poster_id (poster_id),
	KEY post_time (post_time)
		)";
	mysql_query($query);

	//
	// Table structure for table `phpbb_posts_text`
	//

	$query = "CREATE TABLE phpbb_posts_text (
		post_id mediumint(8) unsigned NOT NULL default '0',
	bbcode_uid varchar(10) NOT NULL default '',
	post_subject varchar(60) default NULL,
	post_text text,
	PRIMARY KEY  (post_id)
		)";
	mysql_query($query);

	//
	// Table structure for table `phpbb_privmsgs`
	//

	$query = "CREATE TABLE phpbb_privmsgs (
		privmsgs_id mediumint(8) unsigned NOT NULL auto_increment,
	privmsgs_type tinyint(4) NOT NULL default '0',
	privmsgs_subject varchar(255) NOT NULL default '0',
	privmsgs_from_userid mediumint(8) NOT NULL default '0',
	privmsgs_to_userid mediumint(8) NOT NULL default '0',
	privmsgs_date int(11) NOT NULL default '0',
	privmsgs_ip varchar(8) NOT NULL default '',
	privmsgs_enable_bbcode tinyint(1) NOT NULL default '1',
	privmsgs_enable_html tinyint(1) NOT NULL default '0',
	privmsgs_enable_smilies tinyint(1) NOT NULL default '1',
	privmsgs_attach_sig tinyint(1) NOT NULL default '1',
	privmsgs_attachment tinyint(1) NOT NULL default '0',
	PRIMARY KEY  (privmsgs_id),
	KEY privmsgs_from_userid (privmsgs_from_userid),
	KEY privmsgs_to_userid (privmsgs_to_userid)
		)";
	mysql_query($query);

	//
	// Table structure for table `phpbb_privmsgs_text`
	//

	$query = "CREATE TABLE phpbb_privmsgs_text (
		privmsgs_text_id mediumint(8) unsigned NOT NULL default '0',
	privmsgs_bbcode_uid varchar(10) NOT NULL default '0',
	privmsgs_text text,
	PRIMARY KEY  (privmsgs_text_id)
		)";
	mysql_query($query);
	//
	// Table structure for table `phpbb_quota_limits`
	//

	$query = "CREATE TABLE phpbb_quota_limits (
		quota_limit_id mediumint(8) unsigned NOT NULL auto_increment,
	quota_desc varchar(20) NOT NULL default '',
	quota_limit bigint(20) unsigned NOT NULL default '0',
	PRIMARY KEY  (quota_limit_id)
		)";
	mysql_query($query);
	//
	// Table structure for table `phpbb_ranks`
	//

	$query = "CREATE TABLE phpbb_ranks (
		rank_id smallint(5) unsigned NOT NULL auto_increment,
	rank_title varchar(50) NOT NULL default '',
	rank_min mediumint(8) NOT NULL default '0',
	rank_special tinyint(1) default '0',
	rank_image varchar(255) default NULL,
	PRIMARY KEY  (rank_id)
		)";
	mysql_query($query);
	//
	// Table structure for table `phpbb_search_results`
	//

	$query = "CREATE TABLE phpbb_search_results (
		search_id int(11) unsigned NOT NULL default '0',
	session_id varchar(32) NOT NULL default '',
	search_array text NOT NULL,
	PRIMARY KEY  (search_id),
	KEY session_id (session_id)
		)";
	mysql_query($query);
	//
	// Table structure for table `phpbb_search_wordlist`
	//

	$query = "CREATE TABLE phpbb_search_wordlist (
		word_text varchar(50) binary NOT NULL default '',
	word_id mediumint(8) unsigned NOT NULL auto_increment,
	word_common tinyint(1) unsigned NOT NULL default '0',
	PRIMARY KEY  (word_text),
	KEY word_id (word_id)
		)";
	mysql_query($query);
	//
	// Table structure for table `phpbb_search_wordmatch`
	//

	$query = "CREATE TABLE phpbb_search_wordmatch (
		post_id mediumint(8) unsigned NOT NULL default '0',
	word_id mediumint(8) unsigned NOT NULL default '0',
	title_match tinyint(1) NOT NULL default '0',
	KEY post_id (post_id),
	KEY word_id (word_id)
		)";
	mysql_query($query);

	//
	// Table structure for table `phpbb_sessions`
	//

	$query = "CREATE TABLE phpbb_sessions (
		session_id char(32) NOT NULL default '',
	session_user_id mediumint(8) NOT NULL default '0',
	session_start int(11) NOT NULL default '0',
	session_time int(11) NOT NULL default '0',
	session_ip char(8) NOT NULL default '0',
	session_page int(11) NOT NULL default '0',
	session_logged_in tinyint(1) NOT NULL default '0',
	PRIMARY KEY  (session_id),
	KEY session_user_id (session_user_id),
	KEY session_id_ip_user_id (session_id,session_ip,session_user_id)
		)";
	mysql_query($query);
	//
	// Table structure for table `phpbb_smilies`
	//

	$query = "CREATE TABLE phpbb_smilies (
		smilies_id smallint(5) unsigned NOT NULL auto_increment,
	code varchar(50) default NULL,
	smile_url varchar(100) default NULL,
	emoticon varchar(75) default NULL,
	PRIMARY KEY  (smilies_id)
		)";
	mysql_query($query);
	//
	// Table structure for table `phpbb_spelling_words`
	//

	$query = "CREATE TABLE phpbb_spelling_words (
		id mediumint(9) NOT NULL auto_increment,
	word varchar(30) binary NOT NULL default '',
	sound varchar(10) NOT NULL default '',
	PRIMARY KEY  (id),
	UNIQUE KEY word (word),
	KEY sound (sound)
		)";
	mysql_query($query);

	//
	// Table structure for table `phpbb_themes`
	//

	$query = "CREATE TABLE phpbb_themes (
		themes_id mediumint(8) unsigned NOT NULL auto_increment,
	template_name varchar(30) NOT NULL default '',
	style_name varchar(30) NOT NULL default '',
	head_stylesheet varchar(100) default NULL,
	body_background varchar(100) default NULL,
	body_bgcolor varchar(6) default NULL,
	body_text varchar(6) default NULL,
	body_link varchar(6) default NULL,
	body_vlink varchar(6) default NULL,
	body_alink varchar(6) default NULL,
	body_hlink varchar(6) default NULL,
	tr_color1 varchar(6) default NULL,
	tr_color2 varchar(6) default NULL,
	tr_color3 varchar(6) default NULL,
	tr_class1 varchar(25) default NULL,
	tr_class2 varchar(25) default NULL,
	tr_class3 varchar(25) default NULL,
	th_color1 varchar(6) default NULL,
	th_color2 varchar(6) default NULL,
	th_color3 varchar(6) default NULL,
	th_class1 varchar(25) default NULL,
	th_class2 varchar(25) default NULL,
	th_class3 varchar(25) default NULL,
	td_color1 varchar(6) default NULL,
	td_color2 varchar(6) default NULL,
	td_color3 varchar(6) default NULL,
	td_class1 varchar(25) default NULL,
	td_class2 varchar(25) default NULL,
	td_class3 varchar(25) default NULL,
	fontface1 varchar(50) default NULL,
	fontface2 varchar(50) default NULL,
	fontface3 varchar(50) default NULL,
	fontsize1 tinyint(4) default NULL,
	fontsize2 tinyint(4) default NULL,
	fontsize3 tinyint(4) default NULL,
	fontcolor1 varchar(6) default NULL,
	fontcolor2 varchar(6) default NULL,
	fontcolor3 varchar(6) default NULL,
	span_class1 varchar(25) default NULL,
	span_class2 varchar(25) default NULL,
	span_class3 varchar(25) default NULL,
	img_size_poll smallint(5) unsigned default NULL,
	img_size_privmsg smallint(5) unsigned default NULL,
	PRIMARY KEY  (themes_id)
		)";
	mysql_query($query);
	//
	// Table structure for table `phpbb_themes_name`
	//

	$query = "CREATE TABLE phpbb_themes_name (
		themes_id smallint(5) unsigned NOT NULL default '0',
	tr_color1_name char(50) default NULL,
	tr_color2_name char(50) default NULL,
	tr_color3_name char(50) default NULL,
	tr_class1_name char(50) default NULL,
	tr_class2_name char(50) default NULL,
	tr_class3_name char(50) default NULL,
	th_color1_name char(50) default NULL,
	th_color2_name char(50) default NULL,
	th_color3_name char(50) default NULL,
	th_class1_name char(50) default NULL,
	th_class2_name char(50) default NULL,
	th_class3_name char(50) default NULL,
	td_color1_name char(50) default NULL,
	td_color2_name char(50) default NULL,
	td_color3_name char(50) default NULL,
	td_class1_name char(50) default NULL,
	td_class2_name char(50) default NULL,
	td_class3_name char(50) default NULL,
	fontface1_name char(50) default NULL,
	fontface2_name char(50) default NULL,
	fontface3_name char(50) default NULL,
	fontsize1_name char(50) default NULL,
	fontsize2_name char(50) default NULL,
	fontsize3_name char(50) default NULL,
	fontcolor1_name char(50) default NULL,
	fontcolor2_name char(50) default NULL,
	fontcolor3_name char(50) default NULL,
	span_class1_name char(50) default NULL,
	span_class2_name char(50) default NULL,
	span_class3_name char(50) default NULL,
	PRIMARY KEY  (themes_id)
		)";
	mysql_query($query);
	//
	// Table structure for table `phpbb_topics`
	//

	$query = "CREATE TABLE phpbb_topics (
		topic_id mediumint(8) unsigned NOT NULL auto_increment,
	forum_id smallint(8) unsigned NOT NULL default '0',
	topic_title varchar(60) NOT NULL default '',
	topic_poster mediumint(8) NOT NULL default '0',
	topic_time int(11) NOT NULL default '0',
	topic_views mediumint(8) unsigned NOT NULL default '0',
	topic_replies mediumint(8) unsigned NOT NULL default '0',
	topic_status tinyint(3) NOT NULL default '0',
	topic_vote tinyint(1) NOT NULL default '0',
	topic_type tinyint(3) NOT NULL default '0',
	topic_first_post_id mediumint(8) unsigned NOT NULL default '0',
	topic_last_post_id mediumint(8) unsigned NOT NULL default '0',
	topic_moved_id mediumint(8) unsigned NOT NULL default '0',
	topic_attachment tinyint(1) NOT NULL default '0',
	weblog_cat_id smallint(5) NOT NULL default '0',
	topic_mood tinyint(2) unsigned NOT NULL default '0',
	topic_currently1 tinyint(2) unsigned NOT NULL default '0',
	topic_currently2 varchar(100) NOT NULL default '',
	topic_auth tinyint(2) unsigned NOT NULL default '0',
	ticket_id mediumint(9) NOT NULL default '0',
	PRIMARY KEY  (topic_id),
	KEY forum_id (forum_id),
	KEY topic_moved_id (topic_moved_id),
	KEY topic_status (topic_status),
	KEY topic_type (topic_type)
		)";
	mysql_query($query);
	//
	// Table structure for table `phpbb_topics_watch`
	//

	$query = "CREATE TABLE phpbb_topics_watch (
		topic_id mediumint(8) unsigned NOT NULL default '0',
	user_id mediumint(8) NOT NULL default '0',
	notify_status tinyint(1) NOT NULL default '0',
	KEY topic_id (topic_id),
	KEY user_id (user_id),
	KEY notify_status (notify_status)
		)";
	mysql_query($query);
	//
	// Table structure for table `phpbb_user_group`
	//

	$query = "CREATE TABLE phpbb_user_group (
		group_id mediumint(8) NOT NULL default '0',
	user_id mediumint(8) NOT NULL default '0',
	user_pending tinyint(1) default NULL,
	KEY group_id (group_id),
	KEY user_id (user_id)
		)";
	mysql_query($query);
	//
	// Table structure for table `phpbb_users`
	//

	$query = "CREATE TABLE phpbb_users (
		user_id mediumint(8) NOT NULL default '0',
	user_active tinyint(1) default '1',
	username varchar(25) NOT NULL default '',
	user_password varchar(32) NOT NULL default '',
	user_session_time int(11) NOT NULL default '0',
	user_session_page smallint(5) NOT NULL default '0',
	user_lastvisit int(11) NOT NULL default '0',
	user_regdate int(11) NOT NULL default '0',
	user_level tinyint(4) default '0',
	user_posts mediumint(8) unsigned NOT NULL default '0',
	user_timezone decimal(5,2) NOT NULL default '0.00',
	user_style tinyint(4) default NULL,
	user_lang varchar(255) default NULL,
	user_dateformat varchar(14) NOT NULL default 'd M Y H:i',
	user_new_privmsg smallint(5) unsigned NOT NULL default '0',
	user_unread_privmsg smallint(5) unsigned NOT NULL default '0',
	user_last_privmsg int(11) NOT NULL default '0',
	user_emailtime int(11) default NULL,
	user_viewemail tinyint(1) default NULL,
	user_attachsig tinyint(1) default NULL,
	user_allowhtml tinyint(1) default '1',
	user_allowbbcode tinyint(1) default '1',
	user_allowsmile tinyint(1) default '1',
	user_allowavatar tinyint(1) NOT NULL default '1',
	user_allow_pm tinyint(1) NOT NULL default '1',
	user_allow_viewonline tinyint(1) NOT NULL default '1',
	user_notify tinyint(1) NOT NULL default '1',
	user_notify_pm tinyint(1) NOT NULL default '0',
	user_popup_pm tinyint(1) NOT NULL default '0',
	user_rank int(11) default '0',
	user_avatar varchar(100) default NULL,
	user_avatar_type tinyint(4) NOT NULL default '0',
	user_email varchar(255) default NULL,
	user_icq varchar(15) default NULL,
	user_website varchar(100) default NULL,
	user_from varchar(100) default NULL,
	user_sig text,
	user_sig_bbcode_uid varchar(10) default NULL,
	user_aim varchar(255) default NULL,
	user_yim varchar(255) default NULL,
	user_msnm varchar(255) default NULL,
	user_occ varchar(100) default NULL,
	user_interests varchar(255) default NULL,
	user_actkey varchar(32) default NULL,
	user_newpasswd varchar(32) default NULL,
	user_weblog smallint(5) default NULL,
	weblog_start_date int(11) unsigned default NULL,
	weblog_show_entries smallint(5) unsigned default '5',
	weblog_access smallint(5) unsigned default '0',
	weblog_access2 smallint(5) default '0',
	weblog_visible tinyint(1) default '1',
	weblog_group smallint(5) unsigned default '0',
	weblog_show_profile tinyint(1) NOT NULL default '1',
	weblog_show_desc tinyint(1) NOT NULL default '1',
	weblog_contact_info tinyint(1) NOT NULL default '1',
	weblog_show_info tinyint(1) NOT NULL default '1',
	weblog_advanced tinyint(1) NOT NULL default '2',
	weblog_style varchar(30) default '',
	weblog_post_comment_name varchar(255) default '(Post your comment)',
	weblog_comment_name varchar(255) default 'Comments: %s',
	PRIMARY KEY  (user_id),
	KEY user_session_time (user_session_time),
	KEY user_weblog (user_weblog)
		)";
	mysql_query($query);
	//
	// Table structure for table `phpbb_vote_desc`
	//

	$query = "CREATE TABLE phpbb_vote_desc (
		vote_id mediumint(8) unsigned NOT NULL auto_increment,
	topic_id mediumint(8) unsigned NOT NULL default '0',
	vote_text text NOT NULL,
	vote_start int(11) NOT NULL default '0',
	vote_length int(11) NOT NULL default '0',
	PRIMARY KEY  (vote_id),
	KEY topic_id (topic_id)
		)";
	mysql_query($query);

	//
	// Table structure for table `phpbb_vote_results`
	//

	$query = "CREATE TABLE phpbb_vote_results (
		vote_id mediumint(8) unsigned NOT NULL default '0',
	vote_option_id tinyint(4) unsigned NOT NULL default '0',
	vote_option_text varchar(255) NOT NULL default '',
	vote_result int(11) NOT NULL default '0',
	KEY vote_option_id (vote_option_id),
	KEY vote_id (vote_id)
		)";
	mysql_query($query);

	//
	// Table structure for table `phpbb_vote_voters`
	//

	$query = "CREATE TABLE phpbb_vote_voters (
		vote_id mediumint(8) unsigned NOT NULL default '0',
	vote_user_id mediumint(8) NOT NULL default '0',
	vote_user_ip char(8) NOT NULL default '',
	KEY vote_id (vote_id),
	KEY vote_user_id (vote_user_id),
	KEY vote_user_ip (vote_user_ip)
		)";
	mysql_query($query);

	//
	// Table structure for table `phpbb_weblog_category`
	//

	$query = "CREATE TABLE phpbb_weblog_category (
		weblog_cat_id smallint(5) unsigned NOT NULL auto_increment,
	forum_id smallint(5) unsigned NOT NULL default '0',
	weblog_cat_name varchar(255) NOT NULL default '',
	weblog_cat_desc varchar(255) NOT NULL default '',
	PRIMARY KEY  (weblog_cat_id)
		)";
	mysql_query($query);

	//
	// Table structure for table `phpbb_weblog_config`
	//

	$query = "CREATE TABLE phpbb_weblog_config (
		config_name varchar(255) NOT NULL default '',
	config_value varchar(255) NOT NULL default '',
	PRIMARY KEY  (config_name)
		)";
	mysql_query($query);

	//
	// Table structure for table `phpbb_weblog_currently`
	//

	$query = "CREATE TABLE phpbb_weblog_currently (
		currently_id smallint(5) unsigned NOT NULL auto_increment,
	image_url varchar(100) default NULL,
	currently varchar(75) default NULL,
	PRIMARY KEY  (currently_id)
		)";
	mysql_query($query);
	//
	// Table structure for table `phpbb_weblog_groups`
	//

	$query = "CREATE TABLE phpbb_weblog_groups (
		group_id smallint(5) unsigned NOT NULL default '0',
	PRIMARY KEY  (group_id)
		)";
	mysql_query($query);
	//
	// Table structure for table `phpbb_weblog_mood`
	//

	$query = "CREATE TABLE phpbb_weblog_mood (
		mood_id smallint(5) unsigned NOT NULL auto_increment,
	image_url varchar(100) default NULL,
	mood varchar(75) default NULL,
	PRIMARY KEY  (mood_id)
		)";
	mysql_query($query);

	//
	// Table structure for table `phpbb_weblog_styles`
	//

	$query = "CREATE TABLE phpbb_weblog_styles (
		style_id smallint(5) unsigned NOT NULL auto_increment,
	style_name varchar(100) NOT NULL default '',
	style_dir varchar(100) NOT NULL default '',
	PRIMARY KEY  (style_id)
		)";
	mysql_query($query);

	//
	// Table structure for table `phpbb_words`
	//

	$query = "CREATE TABLE phpbb_words (
		word_id mediumint(8) unsigned NOT NULL auto_increment,
	word char(100) NOT NULL default '',
	replacement char(100) NOT NULL default '',
	PRIMARY KEY  (word_id)
		)";
	mysql_query($query);

// Basic DB data for phpBB2 devel

mysql_query("INSERT INTO phpbb_config (config_name, config_value) VALUES ('config_id','1')");
mysql_query("INSERT INTO phpbb_config (config_name, config_value) VALUES ('board_disable','0')");
mysql_query("INSERT INTO phpbb_config (config_name, config_value) VALUES ('sitename','yourdomain.com')");
mysql_query("INSERT INTO phpbb_config (config_name, config_value) VALUES ('site_desc','A _little_ text to describe your forum')");
mysql_query("INSERT INTO phpbb_config (config_name, config_value) VALUES ('cookie_name','phpbb2mysql')");
mysql_query("INSERT INTO phpbb_config (config_name, config_value) VALUES ('cookie_path','/')");
mysql_query("INSERT INTO phpbb_config (config_name, config_value) VALUES ('cookie_domain','')");
mysql_query("INSERT INTO phpbb_config (config_name, config_value) VALUES ('cookie_secure','0')");
mysql_query("INSERT INTO phpbb_config (config_name, config_value) VALUES ('session_length','3600')");
mysql_query("INSERT INTO phpbb_config (config_name, config_value) VALUES ('allow_html','0')");
mysql_query("INSERT INTO phpbb_config (config_name, config_value) VALUES ('allow_html_tags','b,i,u,pre')");
mysql_query("INSERT INTO phpbb_config (config_name, config_value) VALUES ('allow_bbcode','1')");
mysql_query("INSERT INTO phpbb_config (config_name, config_value) VALUES ('allow_smilies','1')");
mysql_query("INSERT INTO phpbb_config (config_name, config_value) VALUES ('allow_sig','1')");
mysql_query("INSERT INTO phpbb_config (config_name, config_value) VALUES ('allow_namechange','0')");
mysql_query("INSERT INTO phpbb_config (config_name, config_value) VALUES ('allow_theme_create','0')");
mysql_query("INSERT INTO phpbb_config (config_name, config_value) VALUES ('allow_avatar_local','0')");
mysql_query("INSERT INTO phpbb_config (config_name, config_value) VALUES ('allow_avatar_remote','0')");
mysql_query("INSERT INTO phpbb_config (config_name, config_value) VALUES ('allow_avatar_upload','0')");
mysql_query("INSERT INTO phpbb_config (config_name, config_value) VALUES ('enable_confirm', '0')");
mysql_query("INSERT INTO phpbb_config (config_name, config_value) VALUES ('override_user_style','0')");
mysql_query("INSERT INTO phpbb_config (config_name, config_value) VALUES ('posts_per_page','15')");
mysql_query("INSERT INTO phpbb_config (config_name, config_value) VALUES ('topics_per_page','50')");
mysql_query("INSERT INTO phpbb_config (config_name, config_value) VALUES ('hot_threshold','25')");
mysql_query("INSERT INTO phpbb_config (config_name, config_value) VALUES ('max_poll_options','10')");
mysql_query("INSERT INTO phpbb_config (config_name, config_value) VALUES ('max_sig_chars','255')");
mysql_query("INSERT INTO phpbb_config (config_name, config_value) VALUES ('max_inbox_privmsgs','50')");
mysql_query("INSERT INTO phpbb_config (config_name, config_value) VALUES ('max_sentbox_privmsgs','25')");
mysql_query("INSERT INTO phpbb_config (config_name, config_value) VALUES ('max_savebox_privmsgs','50')");
mysql_query("INSERT INTO phpbb_config (config_name, config_value) VALUES ('board_email_sig','Thanks, The Management')");
mysql_query("INSERT INTO phpbb_config (config_name, config_value) VALUES ('board_email','youraddress@yourdomain.com')");
mysql_query("INSERT INTO phpbb_config (config_name, config_value) VALUES ('smtp_delivery','0')");
mysql_query("INSERT INTO phpbb_config (config_name, config_value) VALUES ('smtp_host','localhost')");
mysql_query("INSERT INTO phpbb_config (config_name, config_value) VALUES ('smtp_username','test')");
mysql_query("INSERT INTO phpbb_config (config_name, config_value) VALUES ('smtp_password','test')");
mysql_query("INSERT INTO phpbb_config (config_name, config_value) VALUES ('sendmail_fix','0')");
mysql_query("INSERT INTO phpbb_config (config_name, config_value) VALUES ('require_activation','0')");
mysql_query("INSERT INTO phpbb_config (config_name, config_value) VALUES ('flood_interval','15')");
mysql_query("INSERT INTO phpbb_config (config_name, config_value) VALUES ('board_email_form','0')");
mysql_query("INSERT INTO phpbb_config (config_name, config_value) VALUES ('avatar_filesize','6144')");
mysql_query("INSERT INTO phpbb_config (config_name, config_value) VALUES ('avatar_max_width','80')");
mysql_query("INSERT INTO phpbb_config (config_name, config_value) VALUES ('avatar_max_height','80')");
mysql_query("INSERT INTO phpbb_config (config_name, config_value) VALUES ('avatar_path','images/avatars')");
mysql_query("INSERT INTO phpbb_config (config_name, config_value) VALUES ('avatar_gallery_path','images/avatars/gallery')");
mysql_query("INSERT INTO phpbb_config (config_name, config_value) VALUES ('smilies_path','images/smiles')");
mysql_query("INSERT INTO phpbb_config (config_name, config_value) VALUES ('default_style','1')");
mysql_query("INSERT INTO phpbb_config (config_name, config_value) VALUES ('default_dateformat','D M d, Y g:i a')");
mysql_query("INSERT INTO phpbb_config (config_name, config_value) VALUES ('board_timezone','0')");
mysql_query("INSERT INTO phpbb_config (config_name, config_value) VALUES ('prune_enable','1')");
mysql_query("INSERT INTO phpbb_config (config_name, config_value) VALUES ('privmsg_disable','0')");
mysql_query("INSERT INTO phpbb_config (config_name, config_value) VALUES ('gzip_compress','0')");
mysql_query("INSERT INTO phpbb_config (config_name, config_value) VALUES ('coppa_fax', '')");
mysql_query("INSERT INTO phpbb_config (config_name, config_value) VALUES ('coppa_mail', '')");
mysql_query("INSERT INTO phpbb_config (config_name, config_value) VALUES ('record_online_users', '0')");
mysql_query("INSERT INTO phpbb_config (config_name, config_value) VALUES ('record_online_date', '0')");
mysql_query("INSERT INTO phpbb_config (config_name, config_value) VALUES ('server_name', 'www.myserver.tld')");
mysql_query("INSERT INTO phpbb_config (config_name, config_value) VALUES ('server_port', '80')");
mysql_query("INSERT INTO phpbb_config (config_name, config_value) VALUES ('script_path', '/phpBB2/')");
mysql_query("INSERT INTO phpbb_config (config_name, config_value) VALUES ('version', '.0.6')");



// -- Categories
mysql_query("INSERT INTO phpbb_categories (cat_id, cat_title, cat_order, include_on_index, cat_allow_weblogs) VALUES (1, 'Test category 1', 10, 1, 0)");
//mysql_query("INSERT INTO phpbb_categories (cat_id, cat_title, cat_order, include_on_index, cat_allow_weblogs) VALUES (2,'Blogs', 20, 0, 1)");


// -- Forums
mysql_query("INSERT INTO phpbb_forums (forum_id, forum_name, forum_desc, cat_id, forum_order, forum_posts, forum_topics, forum_last_post_id, auth_view, auth_read, auth_post, auth_reply, auth_edit, auth_delete, auth_announce, auth_sticky, auth_pollcreate, auth_vote, auth_attachments) VALUES (1, 'Test Forum 1', 'This is just a test forum.', 1, 10, 1, 1, 1, 0, 0, 0, 0, 1, 1, 3, 3, 1, 1, 3)");


// -- Users
mysql_query("INSERT INTO phpbb_users (user_id, username, user_level, user_regdate, user_password, user_email, user_icq, user_website, user_occ, user_from, user_interests, user_sig, user_viewemail, user_style, user_aim, user_yim, user_msnm, user_posts, user_attachsig, user_allowsmile, user_allowhtml, user_allowbbcode, user_allow_pm, user_notify_pm, user_allow_viewonline, user_rank, user_avatar, user_lang, user_timezone, user_dateformat, user_actkey, user_newpasswd, user_notify, user_active) VALUES ( -1, 'Anonymous', 0, 0, '', '', '', '', '', '', '', '', 0, NULL, '', '', '', 0, 0, 1, 0, 1, 0, 1, 1, NULL, '', '', 0, '', '', '', 0, 0)");

// -- username: admin    password: admin (change this or remove it once everything is working!)
mysql_query("INSERT INTO phpbb_users (user_id, username, user_level, user_regdate, user_password, user_email, user_icq, user_website, user_occ, user_from, user_interests, user_sig, user_viewemail, user_style, user_aim, user_yim, user_msnm, user_posts, user_attachsig, user_allowsmile, user_allowhtml, user_allowbbcode, user_allow_pm, user_notify_pm, user_popup_pm, user_allow_viewonline, user_rank, user_avatar, user_lang, user_timezone, user_dateformat, user_actkey, user_newpasswd, user_notify, user_active) VALUES ( 2, 'admin', 1, 0, '21232f297a57a5a743894a0e4a801fc3', 'admin@yourdomain.com', '', '', '', '', '', '', 1, 1, '', '', '', 1, 0, 1, 0, 1, 1, 1, 1, 1, 1, '', 'english', 0, 'd M Y h:i a', '', '', 0, 1)");


// -- Ranks
mysql_query("INSERT INTO phpbb_ranks (rank_id, rank_title, rank_min, rank_special, rank_image) VALUES ( 1, 'Site Admin', -1, 1, NULL)");


// -- Groups
mysql_query("INSERT INTO phpbb_groups (group_id, group_name, group_description, group_single_user) VALUES (1, 'Anonymous', 'Personal User', 1)");
mysql_query("INSERT INTO phpbb_groups (group_id, group_name, group_description, group_single_user) VALUES (2, 'admin', 'Personal User', 1)");


// -- User -> Group
mysql_query("INSERT INTO phpbb_user_group (group_id, user_id, user_pending) VALUES (1, -1, 0)");
mysql_query("INSERT INTO phpbb_user_group (group_id, user_id, user_pending) VALUES (2, 2, 0)");


// -- Demo Topic
mysql_query("INSERT INTO phpbb_topics (topic_id, topic_title, topic_poster, topic_time, topic_views, topic_replies, forum_id, topic_status, topic_type, topic_vote, topic_first_post_id, topic_last_post_id) VALUES (1, 'Welcome to vtiger Message Board, powered by phpBB 2', 2, '972086460', 0, 0, 1, 0, 0, 0, 1, 1)");


// -- Demo Post
mysql_query("INSERT INTO phpbb_posts (post_id, topic_id, forum_id, poster_id, post_time, post_username, poster_ip) VALUES (1, 1, 1, 2, 972086460, NULL, '7F000001')");
mysql_query("INSERT INTO phpbb_posts_text (post_id, post_subject, post_text) VALUES (1, NULL, 'This is an example post in your forum installation. You may delete this post, this topic and even this forum if you like since everything seems to be working!')");


// -- Themes
//mysql_query("INSERT INTO phpbb_themes (themes_id, template_name, style_name, head_stylesheet, body_background, body_bgcolor, body_text, body_link, body_vlink, body_alink, body_hlink, tr_color1, tr_color2, tr_color3, tr_class1, tr_class2, tr_class3, th_color1, th_color2, th_color3, th_class1, th_class2, th_class3, td_color1, td_color2, td_color3, td_class1, td_class2, td_class3, fontface1, fontface2, fontface3, fontsize1, fontsize2, fontsize3, fontcolor1, fontcolor2, fontcolor3, span_class1, span_class2, span_class3) VALUES (1, 'subSilver', 'subSilver', 'subSilver.css', '', 'E5E5E5', '000000', '006699', '5493B4', '', 'DD6900', 'EFEFEF', 'DEE3E7', 'D1D7DC', '', '', '', '98AAB1', '006699', 'FFFFFF', 'cellpic1.gif', 'cellpic3.gif', 'cellpic2.jpg', 'FAFAFA', 'FFFFFF', '', 'row1', 'row2', '', 'Verdana, Arial, Helvetica, sans-serif', 'Trebuchet MS', 'Courier, \'Courier New\', sans-serif', 10, 11, 12, '444444', '006600', 'FFA34F', '', '', '')");
mysql_query("INSERT INTO phpbb_themes (themes_id, template_name, style_name, head_stylesheet, body_background, body_bgcolor, body_text, body_link, body_vlink, body_alink, body_hlink, tr_color1, tr_color2, tr_color3, tr_class1, tr_class2, tr_class3, th_color1, th_color2, th_color3, th_class1, th_class2, th_class3, td_color1, td_color2, td_color3, td_class1, td_class2, td_class3, fontface1, fontface2, fontface3, fontsize1, fontsize2, fontsize3, fontcolor1, fontcolor2, fontcolor3, span_class1, span_class2, span_class3) VALUES (1, 'advSilver', 'advSilver', 'advSilver.css', '', 'FFFFFF', '000000', '993300', 'C4867D', '', 'DD6900', 'F7F7F7', 'E2E2E2', 'EFEFEF', '', '', '', 'CECECE', 'C5C5C5', 'FFFFFF', 'cellpic1.gif', 'cellpic3.gif', 'cellpic2.jpg', 'FAFAFA', 'FFFFFF', '', 'row1', 'row2', '', 'Verdana, Arial, Helvetica, sans-serif', 'Helvetica', 'Courier, \'Courier New\', sans-serif', 10, 11, 12, '444444', '006600', 'BD1C31', '', '', '')");


mysql_query("INSERT INTO phpbb_themes_name (themes_id, tr_color1_name, tr_color2_name, tr_color3_name, tr_class1_name, tr_class2_name, tr_class3_name, th_color1_name, th_color2_name, th_color3_name, th_class1_name, th_class2_name, th_class3_name, td_color1_name, td_color2_name, td_color3_name, td_class1_name, td_class2_name, td_class3_name, fontface1_name, fontface2_name, fontface3_name, fontsize1_name, fontsize2_name, fontsize3_name, fontcolor1_name, fontcolor2_name, fontcolor3_name, span_class1_name, span_class2_name, span_class3_name) VALUES (1, 'The lightest row colour', 'The medium row color', 'The darkest row colour', '', '', '', 'Border round the whole page', 'Outer table border', 'Inner table border', 'Silver gradient picture', 'Blue gradient picture', 'Fade-out gradient on index', 'Background for quote boxes', 'All white areas', '', 'Background for topic posts', '2nd background for topic posts', '', 'Main fonts', 'Additional topic title font', 'Form fonts', 'Smallest font size', 'Medium font size', 'Normal font size (post body etc)', 'Quote & copyright text', 'Code text colour', 'Main table header text colour', '', '', '')");


// -- Smilies
mysql_query("INSERT INTO phpbb_smilies (smilies_id, code, smile_url, emoticon) VALUES ( 1, ':D', 'icon_biggrin.gif', 'Very Happy')");
mysql_query("INSERT INTO phpbb_smilies (smilies_id, code, smile_url, emoticon) VALUES ( 2, ':-D', 'icon_biggrin.gif', 'Very Happy')");
mysql_query("INSERT INTO phpbb_smilies (smilies_id, code, smile_url, emoticon) VALUES ( 3, ':grin:', 'icon_biggrin.gif', 'Very Happy')");
mysql_query("INSERT INTO phpbb_smilies (smilies_id, code, smile_url, emoticon) VALUES ( 4, ':)', 'icon_smile.gif', 'Smile')");
mysql_query("INSERT INTO phpbb_smilies (smilies_id, code, smile_url, emoticon) VALUES ( 5, ':-)', 'icon_smile.gif', 'Smile')");
mysql_query("INSERT INTO phpbb_smilies (smilies_id, code, smile_url, emoticon) VALUES ( 6, ':smile:', 'icon_smile.gif', 'Smile')");
mysql_query("INSERT INTO phpbb_smilies (smilies_id, code, smile_url, emoticon) VALUES ( 7, ':(', 'icon_sad.gif', 'Sad')");
mysql_query("INSERT INTO phpbb_smilies (smilies_id, code, smile_url, emoticon) VALUES ( 8, ':-(', 'icon_sad.gif', 'Sad')");
mysql_query("INSERT INTO phpbb_smilies (smilies_id, code, smile_url, emoticon) VALUES ( 9, ':sad:', 'icon_sad.gif', 'Sad')");
mysql_query("INSERT INTO phpbb_smilies (smilies_id, code, smile_url, emoticon) VALUES ( 10, ':o', 'icon_surprised.gif', 'Surprised')");
mysql_query("INSERT INTO phpbb_smilies (smilies_id, code, smile_url, emoticon) VALUES ( 11, ':-o', 'icon_surprised.gif', 'Surprised')");
mysql_query("INSERT INTO phpbb_smilies (smilies_id, code, smile_url, emoticon) VALUES ( 12, ':eek:', 'icon_surprised.gif', 'Surprised')");
mysql_query("INSERT INTO phpbb_smilies (smilies_id, code, smile_url, emoticon) VALUES ( 13, ':shock:', 'icon_eek.gif', 'Shocked')");
mysql_query("INSERT INTO phpbb_smilies (smilies_id, code, smile_url, emoticon) VALUES ( 14, ':?', 'icon_confused.gif', 'Confused')");
mysql_query("INSERT INTO phpbb_smilies (smilies_id, code, smile_url, emoticon) VALUES ( 15, ':-?', 'icon_confused.gif', 'Confused')");
mysql_query("INSERT INTO phpbb_smilies (smilies_id, code, smile_url, emoticon) VALUES ( 16, ':???:', 'icon_confused.gif', 'Confused')");
mysql_query("INSERT INTO phpbb_smilies (smilies_id, code, smile_url, emoticon) VALUES ( 17, '8)', 'icon_cool.gif', 'Cool')");
mysql_query("INSERT INTO phpbb_smilies (smilies_id, code, smile_url, emoticon) VALUES ( 18, '8-)', 'icon_cool.gif', 'Cool')");
mysql_query("INSERT INTO phpbb_smilies (smilies_id, code, smile_url, emoticon) VALUES ( 19, ':cool:', 'icon_cool.gif', 'Cool')");
mysql_query("INSERT INTO phpbb_smilies (smilies_id, code, smile_url, emoticon) VALUES ( 20, ':lol:', 'icon_lol.gif', 'Laughing')");
mysql_query("INSERT INTO phpbb_smilies (smilies_id, code, smile_url, emoticon) VALUES ( 21, ':x', 'icon_mad.gif', 'Mad')");
mysql_query("INSERT INTO phpbb_smilies (smilies_id, code, smile_url, emoticon) VALUES ( 22, ':-x', 'icon_mad.gif', 'Mad')");
mysql_query("INSERT INTO phpbb_smilies (smilies_id, code, smile_url, emoticon) VALUES ( 23, ':mad:', 'icon_mad.gif', 'Mad')");
mysql_query("INSERT INTO phpbb_smilies (smilies_id, code, smile_url, emoticon) VALUES ( 24, ':P', 'icon_razz.gif', 'Razz')");
mysql_query("INSERT INTO phpbb_smilies (smilies_id, code, smile_url, emoticon) VALUES ( 25, ':-P', 'icon_razz.gif', 'Razz')");
mysql_query("INSERT INTO phpbb_smilies (smilies_id, code, smile_url, emoticon) VALUES ( 26, ':razz:', 'icon_razz.gif', 'Razz')");
mysql_query("INSERT INTO phpbb_smilies (smilies_id, code, smile_url, emoticon) VALUES ( 27, ':oops:', 'icon_redface.gif', 'Embarassed')");
mysql_query("INSERT INTO phpbb_smilies (smilies_id, code, smile_url, emoticon) VALUES ( 28, ':cry:', 'icon_cry.gif', 'Crying or Very sad')");
mysql_query("INSERT INTO phpbb_smilies (smilies_id, code, smile_url, emoticon) VALUES ( 29, ':evil:', 'icon_evil.gif', 'Evil or Very Mad')");
mysql_query("INSERT INTO phpbb_smilies (smilies_id, code, smile_url, emoticon) VALUES ( 30, ':twisted:', 'icon_twisted.gif', 'Twisted Evil')");
mysql_query("INSERT INTO phpbb_smilies (smilies_id, code, smile_url, emoticon) VALUES ( 31, ':roll:', 'icon_rolleyes.gif', 'Rolling Eyes')");
mysql_query("INSERT INTO phpbb_smilies (smilies_id, code, smile_url, emoticon) VALUES ( 32, ':wink:', 'icon_wink.gif', 'Wink')");
mysql_query("INSERT INTO phpbb_smilies (smilies_id, code, smile_url, emoticon) VALUES ( 33, ';)', 'icon_wink.gif', 'Wink')");
mysql_query("INSERT INTO phpbb_smilies (smilies_id, code, smile_url, emoticon) VALUES ( 34, ';-)', 'icon_wink.gif', 'Wink')");
mysql_query("INSERT INTO phpbb_smilies (smilies_id, code, smile_url, emoticon) VALUES ( 35, ':!:', 'icon_exclaim.gif', 'Exclamation')");
mysql_query("INSERT INTO phpbb_smilies (smilies_id, code, smile_url, emoticon) VALUES ( 36, ':?:', 'icon_question.gif', 'Question')");
mysql_query("INSERT INTO phpbb_smilies (smilies_id, code, smile_url, emoticon) VALUES ( 37, ':idea:', 'icon_idea.gif', 'Idea')");
mysql_query("INSERT INTO phpbb_smilies (smilies_id, code, smile_url, emoticon) VALUES ( 38, ':arrow:', 'icon_arrow.gif', 'Arrow')");
mysql_query("INSERT INTO phpbb_smilies (smilies_id, code, smile_url, emoticon) VALUES ( 39, ':|', 'icon_neutral.gif', 'Neutral')");
mysql_query("INSERT INTO phpbb_smilies (smilies_id, code, smile_url, emoticon) VALUES ( 40, ':-|', 'icon_neutral.gif', 'Neutral')");
mysql_query("INSERT INTO phpbb_smilies (smilies_id, code, smile_url, emoticon) VALUES ( 41, ':neutral:', 'icon_neutral.gif', 'Neutral')");
mysql_query("INSERT INTO phpbb_smilies (smilies_id, code, smile_url, emoticon) VALUES ( 42, ':mrgreen:', 'icon_mrgreen.gif', 'Mr. Green')");


// -- wordlist
mysql_query("INSERT INTO phpbb_search_wordlist (word_id, word_text, word_common) VALUES ( 1, 'example', 0 )");
mysql_query("INSERT INTO phpbb_search_wordlist (word_id, word_text, word_common) VALUES ( 2, 'post', 0 )");
mysql_query("INSERT INTO phpbb_search_wordlist (word_id, word_text, word_common) VALUES ( 3, 'phpbb', 0 )");
mysql_query("INSERT INTO phpbb_search_wordlist (word_id, word_text, word_common) VALUES ( 4, 'installation', 0 )");
mysql_query("INSERT INTO phpbb_search_wordlist (word_id, word_text, word_common) VALUES ( 5, 'delete', 0 )");
mysql_query("INSERT INTO phpbb_search_wordlist (word_id, word_text, word_common) VALUES ( 6, 'topic', 0 )");
mysql_query("INSERT INTO phpbb_search_wordlist (word_id, word_text, word_common) VALUES ( 7, 'forum', 0 )");
mysql_query("INSERT INTO phpbb_search_wordlist (word_id, word_text, word_common) VALUES ( 8, 'since', 0 )");
mysql_query("INSERT INTO phpbb_search_wordlist (word_id, word_text, word_common) VALUES ( 9, 'everything', 0 )");
mysql_query("INSERT INTO phpbb_search_wordlist (word_id, word_text, word_common) VALUES ( 10, 'seems', 0 )");
mysql_query("INSERT INTO phpbb_search_wordlist (word_id, word_text, word_common) VALUES ( 11, 'working', 0 )");
mysql_query("INSERT INTO phpbb_search_wordlist (word_id, word_text, word_common) VALUES ( 12, 'welcome', 0 )");


// -- wordmatch
mysql_query("INSERT INTO phpbb_search_wordmatch (word_id, post_id, title_match) VALUES ( 1, 1, 0 )");
mysql_query("INSERT INTO phpbb_search_wordmatch (word_id, post_id, title_match) VALUES ( 2, 1, 0 )");
mysql_query("INSERT INTO phpbb_search_wordmatch (word_id, post_id, title_match) VALUES ( 3, 1, 0 )");
mysql_query("INSERT INTO phpbb_search_wordmatch (word_id, post_id, title_match) VALUES ( 4, 1, 0 )");
mysql_query("INSERT INTO phpbb_search_wordmatch (word_id, post_id, title_match) VALUES ( 5, 1, 0 )");
mysql_query("INSERT INTO phpbb_search_wordmatch (word_id, post_id, title_match) VALUES ( 6, 1, 0 )");
mysql_query("INSERT INTO phpbb_search_wordmatch (word_id, post_id, title_match) VALUES ( 7, 1, 0 )");
mysql_query("INSERT INTO phpbb_search_wordmatch (word_id, post_id, title_match) VALUES ( 8, 1, 0 )");
mysql_query("INSERT INTO phpbb_search_wordmatch (word_id, post_id, title_match) VALUES ( 9, 1, 0 )");
mysql_query("INSERT INTO phpbb_search_wordmatch (word_id, post_id, title_match) VALUES ( 10, 1, 0 )");
mysql_query("INSERT INTO phpbb_search_wordmatch (word_id, post_id, title_match) VALUES ( 11, 1, 0 )");
mysql_query("INSERT INTO phpbb_search_wordmatch (word_id, post_id, title_match) VALUES ( 12, 1, 1 )");
mysql_query("INSERT INTO phpbb_search_wordmatch (word_id, post_id, title_match) VALUES ( 3, 1, 1 )");

// -- attachments_config
mysql_query("INSERT INTO phpbb_attachments_config (config_name, config_value) VALUES ('upload_dir','files')");
mysql_query("INSERT INTO phpbb_attachments_config (config_name, config_value) VALUES ('upload_img','images/icon_clip.gif')");
mysql_query("INSERT INTO phpbb_attachments_config (config_name, config_value) VALUES ('topic_icon','images/icon_clip.gif')");
mysql_query("INSERT INTO phpbb_attachments_config (config_name, config_value) VALUES ('display_order','0')");

mysql_query("INSERT INTO phpbb_attachments_config (config_name, config_value) VALUES ('max_filesize','262144')");
mysql_query("INSERT INTO phpbb_attachments_config (config_name, config_value) VALUES ('attachment_quota','52428800')");
mysql_query("INSERT INTO phpbb_attachments_config (config_name, config_value) VALUES ('max_filesize_pm','262144')");

mysql_query("INSERT INTO phpbb_attachments_config (config_name, config_value) VALUES ('max_attachments','3')");
mysql_query("INSERT INTO phpbb_attachments_config (config_name, config_value) VALUES ('max_attachments_pm','1')");

mysql_query("INSERT INTO phpbb_attachments_config (config_name, config_value) VALUES ('disable_mod','0')");
mysql_query("INSERT INTO phpbb_attachments_config (config_name, config_value) VALUES ('allow_pm_attach','1')");
mysql_query("INSERT INTO phpbb_attachments_config (config_name, config_value) VALUES ('attachment_topic_review','0')");
mysql_query("INSERT INTO phpbb_attachments_config (config_name, config_value) VALUES ('allow_ftp_upload','0')");
mysql_query("INSERT INTO phpbb_attachments_config (config_name, config_value) VALUES ('show_apcp','0')");
mysql_query("INSERT INTO phpbb_attachments_config (config_name, config_value) VALUES ('attach_version','2.3.9')");
mysql_query("INSERT INTO phpbb_attachments_config (config_name, config_value) VALUES ('default_upload_quota', '0')");
mysql_query("INSERT INTO phpbb_attachments_config (config_name, config_value) VALUES ('default_pm_quota', '0')");

mysql_query("INSERT INTO phpbb_attachments_config (config_name, config_value) VALUES ('ftp_server','')");
mysql_query("INSERT INTO phpbb_attachments_config (config_name, config_value) VALUES ('ftp_path','')");
mysql_query("INSERT INTO phpbb_attachments_config (config_name, config_value) VALUES ('download_path','')");
mysql_query("INSERT INTO phpbb_attachments_config (config_name, config_value) VALUES ('ftp_user','')");
mysql_query("INSERT INTO phpbb_attachments_config (config_name, config_value) VALUES ('ftp_pass','')");
mysql_query("INSERT INTO phpbb_attachments_config (config_name, config_value) VALUES ('ftp_pasv_mode','1')");

mysql_query("INSERT INTO phpbb_attachments_config (config_name, config_value) VALUES ('img_display_inlined','1')");
mysql_query("INSERT INTO phpbb_attachments_config (config_name, config_value) VALUES ('img_max_width','0')");
mysql_query("INSERT INTO phpbb_attachments_config (config_name, config_value) VALUES ('img_max_height','0')");
mysql_query("INSERT INTO phpbb_attachments_config (config_name, config_value) VALUES ('img_link_width','0')");
mysql_query("INSERT INTO phpbb_attachments_config (config_name, config_value) VALUES ('img_link_height','0')");
mysql_query("INSERT INTO phpbb_attachments_config (config_name, config_value) VALUES ('img_create_thumbnail','0')");
mysql_query("INSERT INTO phpbb_attachments_config (config_name, config_value) VALUES ('img_min_thumb_filesize','12000')");
mysql_query("INSERT INTO phpbb_attachments_config (config_name, config_value) VALUES ('img_imagick', '')");
mysql_query("INSERT INTO phpbb_attachments_config (config_name, config_value) VALUES ('use_gd2','0')");

mysql_query("INSERT INTO phpbb_attachments_config (config_name, config_value) VALUES ('wma_autoplay','0')");

mysql_query("INSERT INTO phpbb_attachments_config (config_name, config_value) VALUES ('flash_autoplay','0')");

// -- forbidden_extensions
mysql_query("INSERT INTO phpbb_forbidden_extensions (ext_id, extension) VALUES (1,'php')");
mysql_query("INSERT INTO phpbb_forbidden_extensions (ext_id, extension) VALUES (2,'php3')");
mysql_query("INSERT INTO phpbb_forbidden_extensions (ext_id, extension) VALUES (3,'php4')");
mysql_query("INSERT INTO phpbb_forbidden_extensions (ext_id, extension) VALUES (4,'phtml')");
mysql_query("INSERT INTO phpbb_forbidden_extensions (ext_id, extension) VALUES (5,'pl')");
mysql_query("INSERT INTO phpbb_forbidden_extensions (ext_id, extension) VALUES (6,'asp')");
mysql_query("INSERT INTO phpbb_forbidden_extensions (ext_id, extension) VALUES (7,'cgi')");

// -- extension_groups
mysql_query("INSERT INTO phpbb_extension_groups (group_id, group_name, cat_id, allow_group, download_mode, upload_icon, max_filesize, forum_permissions) VALUES (1,'Images',1,1,1,'',0,'')");
mysql_query("INSERT INTO phpbb_extension_groups (group_id, group_name, cat_id, allow_group, download_mode, upload_icon, max_filesize, forum_permissions) VALUES (2,'Archives',0,1,1,'',0,'')");
mysql_query("INSERT INTO phpbb_extension_groups (group_id, group_name, cat_id, allow_group, download_mode, upload_icon, max_filesize, forum_permissions) VALUES (3,'Plain Text',0,0,1,'',0,'')");
mysql_query("INSERT INTO phpbb_extension_groups (group_id, group_name, cat_id, allow_group, download_mode, upload_icon, max_filesize, forum_permissions) VALUES (4,'Documents',0,0,1,'',0,'')");
mysql_query("INSERT INTO phpbb_extension_groups (group_id, group_name, cat_id, allow_group, download_mode, upload_icon, max_filesize, forum_permissions) VALUES (5,'Real Media',0,0,2,'',0,'')");
mysql_query("INSERT INTO phpbb_extension_groups (group_id, group_name, cat_id, allow_group, download_mode, upload_icon, max_filesize, forum_permissions) VALUES (6,'Streams',2,0,1,'',0,'')");
mysql_query("INSERT INTO phpbb_extension_groups (group_id, group_name, cat_id, allow_group, download_mode, upload_icon, max_filesize, forum_permissions) VALUES (7,'Flash Files',3,0,1,'',0,'')");

// -- extensions
mysql_query("INSERT INTO phpbb_extensions (ext_id, group_id, extension, comment) VALUES (1, 1,'gif', '')");
mysql_query("INSERT INTO phpbb_extensions (ext_id, group_id, extension, comment) VALUES (2, 1,'png', '')");
mysql_query("INSERT INTO phpbb_extensions (ext_id, group_id, extension, comment) VALUES (3, 1,'jpeg', '')");
mysql_query("INSERT INTO phpbb_extensions (ext_id, group_id, extension, comment) VALUES (4, 1,'jpg', '')");
mysql_query("INSERT INTO phpbb_extensions (ext_id, group_id, extension, comment) VALUES (5, 1,'tif', '')");
mysql_query("INSERT INTO phpbb_extensions (ext_id, group_id, extension, comment) VALUES (6, 1,'tga', '')");
mysql_query("INSERT INTO phpbb_extensions (ext_id, group_id, extension, comment) VALUES (7, 2,'gtar', '')");
mysql_query("INSERT INTO phpbb_extensions (ext_id, group_id, extension, comment) VALUES (8, 2,'gz', '')");
mysql_query("INSERT INTO phpbb_extensions (ext_id, group_id, extension, comment) VALUES (9, 2,'tar', '')");
mysql_query("INSERT INTO phpbb_extensions (ext_id, group_id, extension, comment) VALUES (10, 2,'zip', '')");
mysql_query("INSERT INTO phpbb_extensions (ext_id, group_id, extension, comment) VALUES (11, 2,'rar', '')");
mysql_query("INSERT INTO phpbb_extensions (ext_id, group_id, extension, comment) VALUES (12, 2,'ace', '')");
mysql_query("INSERT INTO phpbb_extensions (ext_id, group_id, extension, comment) VALUES (13, 3,'txt', '')");
mysql_query("INSERT INTO phpbb_extensions (ext_id, group_id, extension, comment) VALUES (14, 3,'c', '')");
mysql_query("INSERT INTO phpbb_extensions (ext_id, group_id, extension, comment) VALUES (15, 3,'h', '')");
mysql_query("INSERT INTO phpbb_extensions (ext_id, group_id, extension, comment) VALUES (16, 3,'cpp', '')");
mysql_query("INSERT INTO phpbb_extensions (ext_id, group_id, extension, comment) VALUES (17, 3,'hpp', '')");
mysql_query("INSERT INTO phpbb_extensions (ext_id, group_id, extension, comment) VALUES (18, 3,'diz', '')");
mysql_query("INSERT INTO phpbb_extensions (ext_id, group_id, extension, comment) VALUES (19, 4,'xls', '')");
mysql_query("INSERT INTO phpbb_extensions (ext_id, group_id, extension, comment) VALUES (20, 4,'doc', '')");
mysql_query("INSERT INTO phpbb_extensions (ext_id, group_id, extension, comment) VALUES (21, 4,'dot', '')");
mysql_query("INSERT INTO phpbb_extensions (ext_id, group_id, extension, comment) VALUES (22, 4,'pdf', '')");
mysql_query("INSERT INTO phpbb_extensions (ext_id, group_id, extension, comment) VALUES (23, 4,'ai', '')");
mysql_query("INSERT INTO phpbb_extensions (ext_id, group_id, extension, comment) VALUES (24, 4,'ps', '')");
mysql_query("INSERT INTO phpbb_extensions (ext_id, group_id, extension, comment) VALUES (25, 4,'ppt', '')");
mysql_query("INSERT INTO phpbb_extensions (ext_id, group_id, extension, comment) VALUES (26, 5,'rm', '')");
mysql_query("INSERT INTO phpbb_extensions (ext_id, group_id, extension, comment) VALUES (27, 6,'wma', '')");
mysql_query("INSERT INTO phpbb_extensions (ext_id, group_id, extension, comment) VALUES (28, 7,'swf', '')");

// -- default quota limits
mysql_query("INSERT INTO phpbb_quota_limits (quota_limit_id, quota_desc, quota_limit) VALUES (1, 'Low', 262144)");
mysql_query("INSERT INTO phpbb_quota_limits (quota_limit_id, quota_desc, quota_limit) VALUES (2, 'Medium', 2097152)");
mysql_query("INSERT INTO phpbb_quota_limits (quota_limit_id, quota_desc, quota_limit) VALUES (3, 'High', 5242880)");

// -- default weblog config values
mysql_query("INSERT INTO phpbb_weblog_config(config_name, config_value) VALUES ('weblog_create', '1')");
mysql_query("INSERT INTO phpbb_weblog_config(config_name, config_value) VALUES ('config_id', '1')");
mysql_query("INSERT INTO phpbb_weblog_config(config_name, config_value) VALUES ('display_stats', '1')");
mysql_query("INSERT INTO phpbb_weblog_config(config_name, config_value) VALUES ('default_access', '0')");
mysql_query("INSERT INTO phpbb_weblog_config(config_name, config_value) VALUES ('default_entries', '5')");
mysql_query("INSERT INTO phpbb_weblog_config(config_name, config_value) VALUES ('default_name', '%s\'s Blog')");
mysql_query("INSERT INTO phpbb_weblog_config(config_name, config_value) VALUES ('default_desc', 'Welcome to my new blog!')");
mysql_query("INSERT INTO phpbb_weblog_config(config_name, config_value) VALUES ('default_comment', 'Comments: %s')");
mysql_query("INSERT INTO phpbb_weblog_config(config_name, config_value) VALUES ('default_post_comment', '(Post a comment)')");
mysql_query("INSERT INTO phpbb_weblog_config(config_name, config_value) VALUES ('weblogs_per_page', '10')");
mysql_query("INSERT INTO phpbb_weblog_config(config_name, config_value) VALUES ('weblog_sort', '1')");
mysql_query("INSERT INTO phpbb_weblog_config(config_name, config_value) VALUES ('weblog_order', 'desc')");
mysql_query("INSERT INTO phpbb_weblog_config(config_name, config_value) VALUES ('allow_advanced', '0')");
mysql_query("INSERT INTO phpbb_weblog_config(config_name, config_value) VALUES ('attachment_mod', '0')");
mysql_query("INSERT INTO phpbb_weblog_config(config_name, config_value) VALUES ('birthday_mod', '0')");
mysql_query("INSERT INTO phpbb_weblog_config(config_name, config_value) VALUES ('birthday_zodiac_mod', '0')");
mysql_query("INSERT INTO phpbb_weblog_config(config_name, config_value) VALUES ('gender_mod', '0')");
mysql_query("INSERT INTO phpbb_weblog_config(config_name, config_value) VALUES ('last_visit_mod', '0')");

// - default weblog moods
mysql_query("INSERT INTO phpbb_weblog_mood(mood_id, image_url, mood) VALUES (0, 'mood_none.gif', '(none)')");
mysql_query("INSERT INTO phpbb_weblog_mood(mood_id, image_url, mood) VALUES (2, 'mood_angelic.gif', 'Angelic')");
mysql_query("INSERT INTO phpbb_weblog_mood(mood_id, image_url, mood) VALUES (3, 'mood_confused.gif', 'Confused')");
mysql_query("INSERT INTO phpbb_weblog_mood(mood_id, image_url, mood) VALUES (4, 'mood_cool.gif', 'Cool')");
mysql_query("INSERT INTO phpbb_weblog_mood(mood_id, image_url, mood) VALUES (5, 'mood_silly.gif', 'Silly')");
mysql_query("INSERT INTO phpbb_weblog_mood(mood_id, image_url, mood) VALUES (6, 'mood_verysad.gif', 'Very Sad')");
mysql_query("INSERT INTO phpbb_weblog_mood(mood_id, image_url, mood) VALUES (7, 'mood_shocked.gif', 'Shocked')");
mysql_query("INSERT INTO phpbb_weblog_mood(mood_id, image_url, mood) VALUES (8, 'mood_evil.gif', 'Evil')");
mysql_query("INSERT INTO phpbb_weblog_mood(mood_id, image_url, mood) VALUES (9, 'mood_love.gif', 'In Love')");
mysql_query("INSERT INTO phpbb_weblog_mood(mood_id, image_url, mood) VALUES (10, 'mood_amused.gif', 'Amused')");
mysql_query("INSERT INTO phpbb_weblog_mood(mood_id, image_url, mood) VALUES (11, 'mood_angry.gif', 'Angry')");
mysql_query("INSERT INTO phpbb_weblog_mood(mood_id, image_url, mood) VALUES (12, 'mood_neutral.gif', 'Neutral')");
mysql_query("INSERT INTO phpbb_weblog_mood(mood_id, image_url, mood) VALUES (13, 'mood_sick.gif', 'Sick')");
mysql_query("INSERT INTO phpbb_weblog_mood(mood_id, image_url, mood) VALUES (14, 'mood_scared.gif', 'Scared')");
mysql_query("INSERT INTO phpbb_weblog_mood(mood_id, image_url, mood) VALUES (15, 'mood_happy.gif', 'Happy')");
mysql_query("INSERT INTO phpbb_weblog_mood(mood_id, image_url, mood) VALUES (16, 'mood_fedup.gif', 'Fed Up With Life')");
mysql_query("INSERT INTO phpbb_weblog_mood(mood_id, image_url, mood) VALUES (17, 'mood_distorted.gif', 'Distorted')");
mysql_query("INSERT INTO phpbb_weblog_mood(mood_id, image_url, mood) VALUES (18, 'mood_hypnotized.gif', 'Hypnotized')");
mysql_query("INSERT INTO phpbb_weblog_mood(mood_id, image_url, mood) VALUES (19, 'mood_embarrased.gif', 'Embarrased')");
mysql_query("INSERT INTO phpbb_weblog_mood(mood_id, image_url, mood) VALUES (20, 'mood_sleepy.gif', 'Sleepy')");

// - default weblogs currently
mysql_query("INSERT INTO phpbb_weblog_currently(currently_id, image_url, currently) VALUES (1, 'currently_none.gif', '(none)')");
mysql_query("INSERT INTO phpbb_weblog_currently(currently_id, image_url, currently) VALUES (2, 'currently_eat.gif', 'Eating')");
mysql_query("INSERT INTO phpbb_weblog_currently(currently_id, image_url, currently) VALUES (3, 'currently_listen.gif', 'Listening to')");
mysql_query("INSERT INTO phpbb_weblog_currently(currently_id, image_url, currently) VALUES (4, 'currently_play.gif', 'Playing')");
mysql_query("INSERT INTO phpbb_weblog_currently(currently_id, image_url, currently) VALUES (5, 'currently_read.gif', 'Reading')");
mysql_query("INSERT INTO phpbb_weblog_currently(currently_id, image_url, currently) VALUES (6, 'currently_watch.gif', 'Watching')");
mysql_query("INSERT INTO phpbb_weblog_currently(currently_id, image_url, currently) VALUES (7, 'currently_work.gif', 'Working')");
mysql_query("INSERT INTO phpbb_weblog_currently(currently_id, image_url, currently) VALUES (8, 'currently_custom.gif', '(custom)')");


// - default weblogs styles
mysql_query("INSERT INTO phpbb_weblog_styles VALUES (1,'Yellow','simpleYellow')");
mysql_query("INSERT INTO phpbb_weblog_styles VALUES (2,'Green','classicGreen')");
mysql_query("INSERT INTO phpbb_weblog_styles VALUES (3,'Blue','classicBlue')");
mysql_query("INSERT INTO phpbb_weblog_styles VALUES (4,'Lavender','classicLavender')");

}
?>
