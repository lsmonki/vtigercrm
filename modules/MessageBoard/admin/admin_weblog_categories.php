<?php
/***************************************************************************
 *                             admin_weblog_categories.php
 *                            -------------------
 *   Author  		: 	netclectic - Adrian Cockburn - adrian@netclectic.com
 *   Created 		: 	Monday, Sept 23, 2002
 *	 Last Updated	:	Thursday, Oct 04, 2002
 *
 *	 Version		: 	FIND - 0.9.6 BETA
 *
 ***************************************************************************/

define('IN_PHPBB', 1);

if( !empty($setmodules) )
{
	$filename = basename(__FILE__);
	#$forum_module['Weblogs']['Weblog Categories'] = $filename;

	return;
}

//
// Load default header
//
$no_page_header = TRUE;
$phpbb_root_path = './../';
require($phpbb_root_path . 'extension.inc');
require('./pagestart.' . $phpEx);

// include the weblog lang file
$use_lang = ( !file_exists($phpbb_root_path . 'language/lang_' . $board_config['default_lang'] . '/lang_weblog_admin.'.$phpEx) ) ? 'english' : $board_config['default_lang'];
include($phpbb_root_path . 'language/lang_' . $use_lang . '/lang_weblog_admin.' . $phpEx);

if (isset($HTTP_POST_VARS['add_weblog_cat']))
{
    if (isset($HTTP_GET_VARS[POST_CAT_URL]) || isset($HTTP_POST_VARS[POST_CAT_URL]))
    {
       	$cat_id = (isset($HTTP_POST_VARS[POST_CAT_URL])) ? intval($HTTP_POST_VARS[POST_CAT_URL]) : intval($HTTP_GET_VARS[POST_CAT_URL]);
        $sql = "UPDATE " . CATEGORIES_TABLE . " SET cat_allow_weblogs = 1 WHERE cat_id=$cat_id;";
    	if( !$result = $db->sql_query($sql) )
    	{
            message_die(GENERAL_ERROR, "Could not update row in categories table.", "", __LINE__, __FILE__, $sql);
        }
    }
}
    
if (isset($HTTP_POST_VARS['remove_weblog_cat']))
{
	$cats = ( isset($HTTP_POST_VARS['cat_id_list']) ) ?  $HTTP_POST_VARS['cat_id_list'] : array();
	$cat_id_sql = '';
    if ( count($cats) > 0 )
    {
    	for($i = 0; $i < count($cats); $i++)
    	{
    		$cat_id_sql .= ( ( $cat_id_sql != '' ) ? ', ' : 'IN (' ) . $cats[$i];
    	}
        $cat_id_sql .= ( $cat_id_sql != '' ) ? ')' : '';
    
        $sql = "UPDATE " . CATEGORIES_TABLE . " SET cat_allow_weblogs = 0 WHERE cat_id $cat_id_sql;";
        if( !$result = $db->sql_query($sql) )
        {
            message_die(GENERAL_ERROR, "Could not update row in categories table.", "", __LINE__, __FILE__, $sql);
        }
    }
    else
    {
        message_die(GENERAL_ERROR, "None selected.");
    }
}
    
$template->set_filenames(array(
    'body' => 'admin/weblog_cats_body.tpl')
);

$sql = "SELECT c.* 
    FROM " . CATEGORIES_TABLE . " c 
    WHERE cat_allow_weblogs <> 1   
	ORDER BY c.cat_order ASC";
if ( !($result = $db->sql_query($sql)) )
{
	message_die(GENERAL_ERROR, "Couldn't obtain categories list", "", __LINE__, __FILE__, $sql);
}

$cat_rows = $db->sql_fetchrowset($result);
$db->sql_freeresult($result);

$select_list = '<select name="' . POST_CAT_URL . '">';
for($i = 0; $i < count($cat_rows); $i++)
{
    $select_list .= '<option value="' . $cat_rows[$i]['cat_id'] . '">' . $cat_rows[$i]['cat_title'] . '</option>';
}
$select_list .= '</select>';

$template->assign_vars(array(
    'L_WEBLOG_CATEGORIES' => $lang['Weblog_Categories'],
    'L_WEBLOG_CATEGORIES_EXPLAIN' => $lang['Weblog_Groups_Categories'],
    'L_WEBLOG_ADD_CAT' => $lang['Weblog_Add_Category'],
    'L_WEBLOG_REMOVE_CAT' => $lang['Weblog_Remove_Category'],
    'L_WEBLOG_CAT_SELECT' => $lang['Weblog_Select_Category'],
    'L_WEBLOG_CAT_SELECT_EXPLAIN' => $lang['Weblog_Select_Category_Explain'],
    'L_CHECK_ALL' => $lang['check_all'],
    'L_UNCHECK_ALL' => $lang['uncheck_all'],

    'S_FORM_ACTION' => append_sid("admin_weblog_categories.$phpEx"),
    'S_CAT_SELECT' => $select_list,
    
    )
);

$sql = "SELECT c.cat_id, c.cat_title 
	FROM " . CATEGORIES_TABLE . " c 
    WHERE c.cat_allow_weblogs = 1 
    ORDER BY c.cat_order ASC";
	
if ( !($result = $db->sql_query($sql)) )
{
	message_die(GENERAL_ERROR, "Couldn't obtain weblog categories list", "", __LINE__, __FILE__, $sql);
}

$cat_rows = $db->sql_fetchrowset($result);
for($i = 0; $i < count($cat_rows); $i++)
{
   	$template->assign_block_vars('cat_row', array(
        'CAT_ID' => $cat_rows[$i]['cat_id'],
        'CAT_TITLE' => $cat_rows[$i]['cat_title'],
        'U_WEBLOG_CAT' => append_sid( "admin_forums.$phpEx?mode=editcat&amp;" . POST_CAT_URL . '=' . $cat_rows[$i]['cat_id'] ),
        )
    );
    $cat_id = $cat_rows[$i]['cat_id'];

    $weblog_sql = "SELECT f.forum_id, f.forum_name, f.forum_desc, 
            u.user_id, u.username, u.weblog_start_date 
    	FROM " . FORUMS_TABLE . " f INNER JOIN " . USERS_TABLE . " u ON f.forum_id = u.user_weblog 
        WHERE f.cat_id = $cat_id 
        ORDER BY f.forum_order ASC";
        
    if ( !($weblog_result = $db->sql_query($weblog_sql)) )
    {
    	message_die(GENERAL_ERROR, "Couldn't obtain weblog categories list", "", __LINE__, __FILE__, $weblog_sql);
    }
    
    $weblog_rows = $db->sql_fetchrowset($weblog_result);
    for($j = 0; $j < count($weblog_rows); $j++)
    {
        
    	$template->assign_block_vars('cat_row.weblog_row', array(
    		'FORUM_ID' => $weblog_rows[$j]['forum_id'],	
    		'S_WEBLOG_NAME' => $weblog_rows[$j]['forum_name'],
    		'S_WEBLOG_DESC' => $weblog_rows[$j]['forum_desc'],
    		'S_WEBLOG_START' => create_date($board_config['default_dateformat'], $weblog_rows[$j]['weblog_start_date'], $board_config['board_timezone']),
            'S_USER_ID' => $weblog_rows[$j]['user_id'],
            'S_USER_NAME' => $weblog_rows[$j]['username'],
            'U_WEBLOG_FORUM' => append_sid( "admin_forums.$phpEx?mode=editforum&amp;" . POST_FORUM_URL . '=' . $weblog_rows[$j]['forum_id'] ),
            'U_WEBLOG_PROFILE' => append_sid( "admin_users.$phpEx?mode=edit&amp;" . POST_USERS_URL . '=' . $weblog_rows[$j]['user_id'] ),
    		)
    	);
    }
}
$db->sql_freeresult($result);


include('./page_header_admin.'.$phpEx);

$template->pparse('body');

include('./page_footer_admin.'.$phpEx);
?>
