<?php
/***************************************************************************
 *                              admin_board.php
 *                            -------------------
 *   begin                : Thursday, Jul 12, 2001
 *   copyright            : (C) 2001 The phpBB Group
 *   email                : support@phpbb.com
 *
 *   $Id: admin_weblog_config.php,v 1.9 2005/01/04 15:04:14 saraj Exp $
 *
 *
 ***************************************************************************/

define('IN_PHPBB', 1);

if( !empty($setmodules) )
{
	$file = basename(__FILE__);
	//$forum_module['Weblogs']['Configuration'] = "$file";
	return;
}

//
// Let's set the root dir for phpBB
//
$phpbb_root_path = "./../";
require($phpbb_root_path . 'extension.inc');
require('./pagestart.' . $phpEx);
include($phpbb_root_path . 'includes/functions_selects.'.$phpEx);

// include the admin weblog lang file
$use_lang = ( !file_exists($phpbb_root_path . 'language/lang_' . $board_config['default_lang'] . '/lang_weblog_admin.'.$phpEx) ) ? 'english' : $board_config['default_lang'];
include($phpbb_root_path . 'language/lang_' . $use_lang . '/lang_weblog_admin.' . $phpEx);

// include the main weblog lang file
$use_lang = ( !file_exists($phpbb_root_path . 'language/lang_' . $board_config['default_lang'] . '/lang_weblog_main.'.$phpEx) ) ? 'english' : $board_config['default_lang'];
include($phpbb_root_path . 'language/lang_' . $use_lang . '/lang_weblog_main.' . $phpEx);

include($phpbb_root_path . 'includes/weblogs_common.'.$phpEx);

if( isset($HTTP_POST_VARS['submit']) )
{
	$display_stats = ( isset($HTTP_POST_VARS['display_stats']) ) ? $HTTP_POST_VARS['display_stats'] : $weblog_config['display_stats'];
	$default_access = ( isset($HTTP_POST_VARS['weblog_access']) ) ? $HTTP_POST_VARS['weblog_access'] : $weblog_config['default_access'];
	$default_entries = ( isset($HTTP_POST_VARS['weblog_entries']) ) ? $HTTP_POST_VARS['weblog_entries'] : $weblog_config['default_entries'];
	$default_name = ( isset($HTTP_POST_VARS['weblog_name']) ) ? $HTTP_POST_VARS['weblog_name'] : $weblog_config['default_name'];
	$default_desc = ( isset($HTTP_POST_VARS['weblog_desc']) ) ? $HTTP_POST_VARS['weblog_desc'] : $weblog_config['default_desc'];
	$default_comment = ( isset($HTTP_POST_VARS['weblog_comment']) ) ? $HTTP_POST_VARS['weblog_comment'] : $weblog_config['default_comment'];
	$default_post_comment = ( isset($HTTP_POST_VARS['weblog_post_comment']) ) ? $HTTP_POST_VARS['weblog_post_comment'] : $weblog_config['default_post_comment'];
	$weblogs_per_page = ( isset($HTTP_POST_VARS['numweblogs']) ) ? $HTTP_POST_VARS['numweblogs'] : $weblog_config['weblogs_per_page'];
	$weblog_sort = ( isset($HTTP_POST_VARS['sortmethod']) ) ? $HTTP_POST_VARS['sortmethod'] : $weblog_config['weblog_sort'];
	$weblog_order = ( isset($HTTP_POST_VARS['display_order']) ) ? $HTTP_POST_VARS['display_order'] : $weblog_config['weblog_order'];
	$weblog_create = ( isset($HTTP_POST_VARS['weblog_create']) ) ? $HTTP_POST_VARS['weblog_create'] : $weblog_config['weblog_create'];
	$weblog_advanced = ( isset($HTTP_POST_VARS['weblog_advanced']) ) ? $HTTP_POST_VARS['weblog_advanced'] : $weblog_config['allow_advanced'];
	$birthday_mod = ( isset($HTTP_POST_VARS['birthday_mod']) ) ? $HTTP_POST_VARS['birthday_mod'] : $weblog_config['birthday_mod'];
	$birthday_zodiac_mod = ( isset($HTTP_POST_VARS['birthday_zodiac_mod']) ) ? $HTTP_POST_VARS['birthday_zodiac_mod'] : $weblog_config['birthday_zodiac_mod'];
	$gender_mod = ( isset($HTTP_POST_VARS['gender_mod']) ) ? $HTTP_POST_VARS['gender_mod'] : $weblog_config['gender_mod'];
	$last_visit_mod = ( isset($HTTP_POST_VARS['last_visit_mod']) ) ? $HTTP_POST_VARS['last_visit_mod'] : $weblog_config['last_visit_mod'];

	$sql = array(
		"UPDATE " . $table_prefix . "weblog_config SET config_value = '" . $display_stats. "' WHERE config_name = 'display_stats'",
		"UPDATE " . $table_prefix . "weblog_config SET config_value = '" . $default_access . "' WHERE config_name = 'default_access'",
		"UPDATE " . $table_prefix . "weblog_config SET config_value = '" . $default_entries . "' WHERE config_name = 'default_entries'",
		"UPDATE " . $table_prefix . "weblog_config SET config_value = '" . $default_name . "' WHERE config_name = 'default_name'",
		"UPDATE " . $table_prefix . "weblog_config SET config_value = '" . $default_desc . "' WHERE config_name = 'default_desc'",
		"UPDATE " . $table_prefix . "weblog_config SET config_value = '" . $default_comment . "' WHERE config_name = 'default_comment'",
		"UPDATE " . $table_prefix . "weblog_config SET config_value = '" . $default_post_comment . "' WHERE config_name = 'default_post_comment'",
		"UPDATE " . $table_prefix . "weblog_config SET config_value = '" . $weblogs_per_page . "' WHERE config_name = 'weblogs_per_page'",
		"UPDATE " . $table_prefix . "weblog_config SET config_value = '" . $weblog_sort . "' WHERE config_name = 'weblog_sort'",
		"UPDATE " . $table_prefix . "weblog_config SET config_value = '" . $weblog_order . "' WHERE config_name = 'weblog_order'",
		"UPDATE " . $table_prefix . "weblog_config SET config_value = '" . $weblog_create . "' WHERE config_name = 'weblog_create'",
		"UPDATE " . $table_prefix . "weblog_config SET config_value = '" . $weblog_advanced . "' WHERE config_name = 'allow_advanced'",
		"UPDATE " . $table_prefix . "weblog_config SET config_value = '" . $birthday_mod . "' WHERE config_name = 'birthday_mod'",
		"UPDATE " . $table_prefix . "weblog_config SET config_value = '" . $birthday_zodiac_mod . "' WHERE config_name = 'birthday_zodiac_mod'",
		"UPDATE " . $table_prefix . "weblog_config SET config_value = '" . $gender_mod . "' WHERE config_name = 'gender_mod'",
		"UPDATE " . $table_prefix . "weblog_config SET config_value = '" . $last_visit_mod . "' WHERE config_name = 'last_visit_mod'",
	);

	for ($i = 0; $i < count($sql); $i++)
	{
		if( !($result = $db->sql_query($sql[$i])) )
		{
			message_die(GENERAL_ERROR, "Failed to update weblog configuration for $config_name", "", __LINE__, __FILE__, $sql[$i]);
		}
	}

	$message = $lang['Weblog_Config_updated'] . "<br /><br />" . sprintf($lang['Click_return_weblog_config'], "<a href=\"" . append_sid("admin_weblog_config.$phpEx") . "\">", "</a>") . "<br /><br />" . sprintf($lang['Click_return_admin_index'], "<a href=\"" . append_sid("index.$phpEx?pane=right") . "\">", "</a>");

	message_die(GENERAL_MESSAGE, $message);
}

$num_weblogs = array(1, 2, 3, 4, 5, 10, 20, 30, 40, 50, 100);
$select_num_weblogs = '<select name="numweblogs">';
for($i = 0; $i < count($num_weblogs); $i++)
{
	$selected = ($weblog_config['weblogs_per_page'] == $num_weblogs[$i]) ? ' selected="selected"' : '';
	$select_num_weblogs .= '<option value="' . $num_weblogs[$i] . '"' . $selected . '>' . $num_weblogs[$i] . '</option>';
}
$select_num_weblogs .= '</select>';

$sort_method = array(1, 2, 3, 4);
$sort_method_text = array($lang['Weblog_post_time'], $lang['Weblog_started'], $lang['Weblog_forum_name'], $lang['Weblog_username']);
$select_sort = '<select name="sortmethod">';
for($i = 0; $i < count($sort_method); $i++)
{
	$selected = ( $weblog_config['weblog_sort'] == $sort_method[$i]) ? ' selected="selected"' : '';
	$select_sort .= '<option value="' . $sort_method[$i] . '"' . $selected . '>' . $sort_method_text[$i] . '</option>';
}
$select_sort .= '</select>';

$rights_select = '<select name="weblog_access">';
for($i = 0; $i < count($weblog_auth_types); $i++)
{
	$selected = ( $weblog_config['default_access'] == $i ) ? ' selected="selected"' : '';
	$rights_select .= '<option value="' . $i . '"' . $selected . '>' . $weblog_auth_desc[$i] . '</option>';
}
$rights_select .= '</select>';

$display_stats_yes = ( $weblog_config['display_stats'] ) ? "checked=\"checked\"" : "";
$display_stats_no = ( !$weblog_config['display_stats'] ) ? "checked=\"checked\"" : "";

$birthday_mod_yes = ( $weblog_config['birthday_mod'] ) ? "checked=\"checked\"" : "";
$birthday_mod_no = ( !$weblog_config['birthday_mod'] ) ? "checked=\"checked\"" : "";

$birthday_zodiac_mod_yes = ( $weblog_config['birthday_zodiac_mod'] ) ? "checked=\"checked\"" : "";
$birthday_zodiac_mod_no = ( !$weblog_config['birthday_zodiac_mod'] ) ? "checked=\"checked\"" : "";

$gender_mod_yes = ( $weblog_config['gender_mod'] ) ? "checked=\"checked\"" : "";
$gender_mod_no = ( !$weblog_config['gender_mod'] ) ? "checked=\"checked\"" : "";

$last_visit_mod_yes = ( $weblog_config['last_visit_mod'] ) ? "checked=\"checked\"" : "";
$last_visit_mod_no = ( !$weblog_config['last_visit_mod'] ) ? "checked=\"checked\"" : "";

$display_order_desc = ( $weblog_config['weblog_order'] == 'desc' ) ? "checked=\"checked\"" : "";
$display_order_asc = ( $weblog_config['weblog_order'] == 'asc' ) ? "checked=\"checked\"" : "";

$weblog_create_group = ( $weblog_config['weblog_create'] == 1 ) ? "checked=\"checked\"" : "";
$weblog_create_all = ( $weblog_config['weblog_create'] == 0 ) ? "checked=\"checked\"" : "";

$weblog_advanced_user = ( $weblog_config['allow_advanced'] == 0 ) ? "checked=\"checked\"" : "";
$weblog_advanced_easy = ( $weblog_config['allow_advanced'] == 1 ) ? "checked=\"checked\"" : "";
$weblog_advanced = ( $weblog_config['allow_advanced'] == 2 ) ? "checked=\"checked\"" : "";

$template->set_filenames(array(
	"body" => "admin/weblog_config_body.tpl")
);

//
// Escape any quotes in the site description for proper display in the text
// box on the admin page 
//
$template->assign_vars(array(
	"S_CONFIG_ACTION" => append_sid("admin_weblog_config.$phpEx"),

	"L_WEBLOG_CONFIG_TITLE" => $lang['Weblog_config_title'],
	"L_WEBLOG_CONFIG_EXPLAIN" => $lang['Weblog_config_explain'],
	"L_WEBLOG_MAIN" => $lang['Weblog_main'],
	"L_WEBLOG_DISPLAY_STATS" => $lang['Weblog_display_stats'],
	"L_WEBLOG_DISPLAY_STATS_EXPLAIN" => $lang['Weblog_display_stats_explain'],
	"L_WEBLOGS_PER_PAGE" => $lang['Weblog_num_per_page'],
	"L_WEBLOG_SORT" => $lang['Weblog_sort'],
	"L_WEBLOG_ORDER" => $lang['Weblog_order'],
	"L_WEBLOG_CREATE" => $lang['Weblog_create'],
	"L_WEBLOG_CREATE_EXPLAIN" => $lang['Weblog_create_explain'],
	"L_WEBLOG_CREATE_GROUP" => $lang['Weblog_create_group'],
	"L_WEBLOG_CREATE_ALL" => $lang['Weblog_create_all'],

	"L_WEBLOG_DESCENDING" => $lang['Weblog_descending'],
	"L_WEBLOG_ASCENDING" => $lang['Weblog_ascending'],
	"L_WEBLOG_MYWEBLOG" => $lang['Weblog_CP'],
	"L_WEBLOG_ACCESS" => $lang['Weblog_access'],
	"L_WEBLOG_ADVANCED" => $lang['Weblog_advanced'],
	"L_WEBLOG_ADVANCED_EXPLAIN" => $lang['Weblog_advanced_explain4'],
	"L_WEBLOG_ENTRIES" => $lang['Weblog_entries'],
	"L_WEBLOG_NAME" => $lang['Weblog_name'],
	"L_WEBLOG_NAME_EXPLAIN" => $lang['Weblog_name_explain'],
	"L_WEBLOG_DESC" => $lang['Weblog_desc'],
	"L_WEBLOG_DESC_EXPLAIN" => $lang['Weblog_desc_explain'],
	"L_WEBLOG_COMMENT" => $lang['Weblog_comment'],
	"L_WEBLOG_POST_COMMENT" => $lang['Weblog_post_comment'],

	"L_EASY" => $lang['Weblog_easy_mode'],
	"L_ADVANCED" => $lang['Weblog_advanced_mode'],
	"L_LET_USER" => $lang['Weblog_let_user_mode'],
	"L_YES" => $lang['Yes'],
	"L_NO" => $lang['No'],
	"L_SUBMIT" => $lang['Submit'], 
	"L_RESET" => $lang['Reset'], 

	"L_MOD_SUPPORT" => $lang['Mod_support'],
	"L_BIRTHDAY_MOD_INSTALLED" => $lang['Birthday_mod_installed'],
	"L_BIRTHDAY_ZODIAC_MOD_INSTALLED" => $lang['Birthday_zodiac_mod_installed'],
	"L_GENDER_MOD_INSTALLED" => $lang['Gender_mod_installed'],
	"L_LAST_VISIT_MOD_INSTALLED" => $lang['Last_visit_mod_installed'],

	"S_DISPLAY_STATS_YES" => $display_stats_yes,
	"S_DISPLAY_STATS_NO" => $display_stats_no,
	"S_BIRTHDAY_MOD_YES" => $birthday_mod_yes,
	"S_BIRTHDAY_MOD_NO" => $birthday_mod_no,
	"S_BIRTHDAY_ZODIAC_MOD_YES" => $birthday_zodiac_mod_yes,
	"S_BIRTHDAY_ZODIAC_MOD_NO" => $birthday_zodiac_mod_no,
	"S_GENDER_MOD_YES" => $gender_mod_yes,
	"S_GENDER_MOD_NO" => $gender_mod_no,
	"S_LAST_VISIT_MOD_YES" => $last_visit_mod_yes,
	"S_LAST_VISIT_MOD_NO" => $last_visit_mod_no,
	"S_DISPLAY_ORDER_DESC" => $display_order_desc,
	"S_DISPLAY_ORDER_ASC" => $display_order_asc,
	"S_WEBLOG_CREATE_GROUP" => $weblog_create_group,
	"S_WEBLOG_CREATE_ALL" => $weblog_create_all,
	"S_WEBLOG_ADVANCED_EASY" => $weblog_advanced_easy,
	"S_WEBLOG_ADVANCED" => $weblog_advanced,
	"S_WEBLOG_ADVANCED_USER" => $weblog_advanced_user,

	"WEBLOG_NAME" => $weblog_config['default_name'],
	"WEBLOG_DESC" => $weblog_config['default_desc'],
	"WEBLOGS_PER_PAGE_SELECT" => $select_num_weblogs,
	"WEBLOG_SORT_SELECT" => $select_sort,
	"WEBLOG_ACCESS_SELECT" => $rights_select,
	"WEBLOG_ENTRIES" => $weblog_config['default_entries'],
	"WEBLOG_COMMENT" => $weblog_config['default_comment'],
	"WEBLOG_POST_COMMENT" => $weblog_config['default_post_comment']
)
);

$template->pparse("body");

include('./page_footer_admin.'.$phpEx);

?>
