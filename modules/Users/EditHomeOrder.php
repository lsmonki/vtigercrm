<?php
/*********************************************************************************
** The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
*
 ********************************************************************************/

require_once('XTemplate/xtpl.php');
require_once('include/utils/UserInfoUtil.php');
require_once("include/utils/utils.php");

global $app_strings;
global $app_list_strings;
global $current_language;

if (isset($_REQUEST['tabid'])) $tabid = $_REQUEST['tabid'];
if (isset($_REQUEST['fld_module'])) $fld_module = $_REQUEST['fld_module'];

global $theme;
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
require_once($theme_path.'layout_utils.php');

$mod_strings = return_module_language($current_language,$fld_module);

if ( !isset($_REQUEST['record']) )
{
	echo $mod_strings['ERR_INVALID_USER'];
	return;
}
$record = $_REQUEST['record'];

global $adb;
$query = "SELECT users.homeorder FROM users WHERE id=$record";
$result =& $adb->query($query, false,"Error getting home order");
$row = $adb->fetchByAssoc($result);

$default_home_section_order = array('ALVT','PLVT','QLTQ','CVLVT','HLT','OLV','GRT','OLTSO','ILTI','MNL');

if($row != null)
{
        $home_section_order = $row['homeorder'];
}
else
{
        $home_section_order = $default_home_section_order;
}

$blocks = explode(",",$home_section_order);
$hblocks = array_diff($default_home_section_order,$blocks);

function parse_data($data)
{
	$containers = explode(":", $data);
	foreach($containers AS $container)
	{
		$container = str_replace(")", "", $container);
		$i = 0;
		$lastly = explode("(", $container);
		$values = explode(",", $lastly[1]);
		foreach($values AS $value)
		{
			if($value == '')
			{
				continue;
			}
			$final[$lastly[0]][] = $value;
			$i ++;
		}
	}
    return $final;
}

function update_db($data, $col_check)
{
	global $record,$log,$adb;
	
	$home_section_order = implode(",",$data['block']);
	$sql = "UPDATE users SET homeorder='$home_section_order' WHERE id=$record";
	$log->debug("Updating user $record with SQL=$sql");
	$result =& $adb->query($sql, false,"Error getting home order");
    return $result;
}

// Lets setup Sajax
require_once('include/dd/Sajax.php');
sajax_init();
$sajax_remote_uri = $_SERVER['REQUEST_URI']."?module=Users&action=EditHomeOrder&record=$record";
//$sajax_debug_mode = 1;

function sajax_update($data)
{
	$data = parse_data($data);
	return update_db($data, ""); 	//"AND (`set` = 'sajax1' OR `set` = 'sajax2')");
	return 'y';
}

sajax_export("sajax_update");
sajax_handle_client_request();

if(isset($_POST['order']))
{
	$data = parse_data($_POST['order']);
	update_db($data, ""); 
	// redirect so refresh doesnt reset order to last save
	header("index.php?module=Settings&action=index");
	exit;
}

?>
<!--*********************************************************************************
** The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
*
 ********************************************************************************/
-->
<!-- BEGIN: main -->
<style type="text/css">
#block {    width: 180px;    float: center;    margin-left: 5px; }
#hidden {    width: 180px;    float: center;    margin-left: 5px; }

form {
  clear: left;
}

h2 {
   color: #7DA721;
   font-weight: normal;
   font-size: 14px;
   margin: 20px 0 0 0;
}

br {
        clear: left;
}

</style>
<link rel="stylesheet" href="include/dd/dd_files/lists.css" type="text/css">
<script language="JavaScript" type="text/javascript" src="include/dd/dd_files/coordinates.js"></script>
<script language="JavaScript" type="text/javascript" src="include/dd/dd_files/drag.js"></script>
<script language="JavaScript" type="text/javascript" src="include/dd/dd_files/dragdrop.js"></script>
<script language="JavaScript" type="text/javascript"><!--
<?php
sajax_show_javascript();
?>
	
	function confirm(z)
	{
        if ( z.length < 50 )
    		alert(z);
        else
            alert("Saved!");
	}

	function onDrop() {
		var data = DragDrop.serData('g2'); 
	}

	function loadMe() {        
		var list;
		var panes = new Array("block","hidden");
		
		while ( pane = panes.shift() ) {
			list = document.getElementById(pane);
			DragDrop.makeListContainer( list, 'g2' );
			list.onDragOver = function() { this.style["background"] = "#EEF"; };
			list.onDragOut = function() {this.style["background"] = "none"; };
			list.onDragDrop = function() {onDrop(); };
		}
	};
        
	function getSort()
	{
		order = document.getElementById("order");
		order.value = DragDrop.serData('g2', null);
	}
	
	function showValue()
	{
		order = document.getElementById("order");
		var data = DragDrop.serData('g2'); 
		x_sajax_update(data, confirm);
	}
	//-->
</script>
<link rel="stylesheet" href="include/dd/dd_files/lists.css" type="text/css">
	<br>
	<?php
		echo "<h1><center>".$mod_strings['LBL_HOMEPAGE_ORDER_UPDATE'].'</center></h1><br><br>';
	?>

<script language="JavaScript" type="text/javascript" src="include/dd/dd_files/coordinates.js"></script>
<script language="JavaScript" type="text/javascript" src="include/dd/dd_files/drag.js"></script>
<script language="JavaScript" type="text/javascript" src="include/dd/dd_files/dragdrop.js"></script>
<table class=UserTableClass  align="center" cellspacing="2" cellpadding="2" class="formOuterBorder">
	<tr  cellspacing="2" cellpadding="2" style="leftFormHeader">
		<td width="25%" class="leftFormHeader" border="1">
			<?php echo $mod_strings['LBL_HOMEPAGE_ID']; ?>
		</td>
		<td>
			<table class=UserTableClass  width="100%">
				<tr align="center">
					<td valign="top">
						<ul id="block" class="sortable boxy">
<?php
foreach ( $blocks as $block ) {
	$fblock .= "<li id='$block'>".$mod_strings[$block]."</li>\n";
}
echo $fblock;
?>
						</ul>
						<div style="clear: left;">		
						</div>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr  cellspacing="2" cellpadding="2" style="leftFormHeader">
                <td width="25%" class="leftFormHeader" border="1">
                        <?php echo $mod_strings['LBL_HOMEPAGE_HIDDEN']; ?>
                </td>
                <td>
                        <table class=UserTableClass  width="100%">
                                <tr align="center">
                                        <td valign="top">
                                                <ul id="hidden" class="sortable boxy">
<?php
$fblock = "";
foreach ( $hblocks as $block ) {
        $fblock .= "<li id='$block'>".$mod_strings[$block]."</li>\n";
}
echo $fblock;
?>
                                                </ul>
                                                <div style="clear: left;">
                                                </div>
                                        </td>
                                </tr>
                        </table>
                </td>
        </tr>
</table>
<p><p><p align="center">
<form align="center" action="" method="post">
         <input type="hidden" name="order" id="order" value="">
        <div align="center">
                <input type="button" onclick="showValue()" value="Update System">
        </div>
        <br>
</form>
<p><p>
<script language="JavaScript" type="text/javascript"><!--
        loadMe();
//-->
</script>
