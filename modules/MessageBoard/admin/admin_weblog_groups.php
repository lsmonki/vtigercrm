<?php
/***************************************************************************
 *                             admin_weblog_groups.php
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
	//$forum_module['Weblogs']['Weblog Groups'] = $filename;

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

if (isset($HTTP_POST_VARS['add_weblog_group']))
{
    if (isset($HTTP_GET_VARS[POST_GROUPS_URL]) || isset($HTTP_POST_VARS[POST_GROUPS_URL]))
    {
       	$group_id = (isset($HTTP_POST_VARS[POST_GROUPS_URL])) ? intval($HTTP_POST_VARS[POST_GROUPS_URL]) : intval($HTTP_GET_VARS[POST_GROUPS_URL]);
        $sql = "INSERT " . WEBLOG_GROUPS_TABLE . " (group_id) VALUES ($group_id);";
    	if( !$result = $db->sql_query($sql) )
    	{
            message_die(GENERAL_ERROR, "Could not insert row in weblog groups table.", "", __LINE__, __FILE__, $sql);
        }
    }
}
    
if (isset($HTTP_POST_VARS['remove_weblog_group']))
{
	$groups = ( isset($HTTP_POST_VARS['group_id_list']) ) ?  $HTTP_POST_VARS['group_id_list'] : array();
	$group_id_sql = '';
    if ( count($groups) > 0 )
    {
    	for($i = 0; $i < count($groups); $i++)
    	{
    		$group_id_sql .= ( ( $group_id_sql != '' ) ? ', ' : 'IN (' ) . $groups[$i];
    	}
        $group_id_sql .= ( $group_id_sql != '' ) ? ')' : '';
    
        $sql = "DELETE FROM " . WEBLOG_GROUPS_TABLE . " WHERE group_id $group_id_sql;";
        if( !$result = $db->sql_query($sql) )
        {
            message_die(GENERAL_ERROR, "Could not delete row from weblog groups table.", "", __LINE__, __FILE__, $sql);
        }
    }
    else
    {
        message_die(GENERAL_ERROR, "None selected.");
    }
}
    
$template->set_filenames(array(
    'body' => 'admin/weblog_groups_body.tpl')
);

$sql = "SELECT g.* 
    FROM " . GROUPS_TABLE . " g LEFT JOIN " . WEBLOG_GROUPS_TABLE . " w ON w.group_id = g.group_id 
        LEFT JOIN " . USERS_TABLE . " u ON u.weblog_group = g.group_id
    WHERE w.group_id IS NULL
        AND u.weblog_group IS NULL 
        AND g.group_single_user <> 1;";
if ( !($result = $db->sql_query($sql)) )
{
	message_die(GENERAL_ERROR, "Couldn't obtain groups list", "", __LINE__, __FILE__, $sql);
}

$group_rows = $db->sql_fetchrowset($result);
$db->sql_freeresult($result);

$select_list = '<select name="' . POST_GROUPS_URL . '">';
for($i = 0; $i < count($group_rows); $i++)
{
    $select_list .= '<option value="' . $group_rows[$i]['group_id'] . '">' . $group_rows[$i]['group_name'] . '</option>';
}
$select_list .= '</select>';

$template->assign_vars(array(
    'L_WEBLOG_GROUPS' => $lang['Weblog_Groups'],
    'L_WEBLOG_GROUPS_EXPLAIN' => $lang['Weblog_Groups_Explain'],
    'L_WEBLOG_ADD_GROUP' => $lang['Weblog_Add_Group'],
    'L_WEBLOG_REMOVE_GROUP' => $lang['Weblog_Remove_Group'],
    'L_WEBLOG_GROUP_SELECT' => $lang['Weblog_Select_Group'],
    'L_WEBLOG_GROUP_SELECT_EXPLAIN' => $lang['Weblog_Select_Group_Explain'],
    'L_CHECK_ALL' => $lang['check_all'],
    'L_UNCHECK_ALL' => $lang['uncheck_all'],

    'S_FORM_ACTION' => append_sid("admin_weblog_groups.$phpEx"),
    'S_GROUP_SELECT' => $select_list,
    )
);

$sql = "SELECT g.group_id, g.group_name 
	FROM " . GROUPS_TABLE . " g INNER JOIN " . WEBLOG_GROUPS_TABLE . " w ON w.group_id = g.group_id;";
	
if ( !($result = $db->sql_query($sql)) )
{
	message_die(GENERAL_ERROR, "Couldn't obtain weblog groups list", "", __LINE__, __FILE__, $sql);
}

$group_rows = $db->sql_fetchrowset($result);
for($i = 0; $i < count($group_rows); $i++)
{
   	$template->assign_block_vars('group_row', array(
        'GROUP_ID' => $group_rows[$i]['group_id'],
        'GROUP_NAME' => $group_rows[$i]['group_name'],
        'U_WEBLOG_GROUP' => append_sid( "admin_groups.$phpEx?mode=editgroup&amp;" . POST_GROUPS_URL . '=' . $group_rows[$i]['group_id'] ),
        )
    );
}
$db->sql_freeresult($result);


include('./page_header_admin.'.$phpEx);

$template->pparse('body');

include('./page_footer_admin.'.$phpEx);
?>
