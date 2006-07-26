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
require_once('modules/Users/UserInfoUtil.php');
require_once('modules/CustomView/CustomView.php');
require_once("include/utils.php");
include('include/uifromdbutil.php');

global $app_strings;
global $app_list_strings;
global $current_language;

if (isset($_REQUEST['tabid'])) $tabid = $_REQUEST['tabid'];
if (isset($_REQUEST['fld_module'])) $fld_module = $_REQUEST['fld_module'];

$tabName = getTabname($tabid);

global $theme;
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
require_once($theme_path.'layout_utils.php');

$mod_strings = return_module_language($current_language,$fld_module);
$cv = new CustomView();

$saveNames = array();

function getBlocks($tabId) {
	global $adb,$saveNames;

	$b = array();	
	//$sql="select fieldid,fieldlabel,block from field,tab where field.tabid=tab.tabid and tab.tabid=$tabId order by block,sequence";
	$sql = "SELECT DISTINCT field.fieldid, field.fieldlabel, field.block, field.sequence
		FROM field
		INNER JOIN profile2field
			ON profile2field.fieldid = field.fieldid
		INNER JOIN def_org_field
			ON def_org_field.fieldid = field.fieldid
		WHERE field.tabid = $tabId
		AND field.displaytype = 1
		AND def_org_field.visible = 0
		ORDER BY block, sequence;";
	$result = $adb->query($sql);
	$noofrows = $adb->num_rows($result);
	
	for($i=0;$i<$noofrows;$i++)
	{
		$fieldid = $adb->query_result($result,$i,"fieldid");
		$fieldlabel = $adb->query_result($result,$i,"fieldlabel");
		$block = $adb->query_result($result,$i,"block");
		$b[$block][] = array($fieldlabel => $fieldid);
		$saveNames[$fieldid] = $fieldlabel;
	}
	return $b;
}

$blockid = array(0);
foreach ( $cv->module_list[$tabName] as $mname => $key )
	$blockid[] = $key;

$blocks = getBlocks($tabid);
if ( !isset($blocks[0]) )
	$blocks[0][]=array();
//$blockid = array(1,2,3,4,5,6,7,8,9);
$blockids = implode(",",$blockid);

foreach ( $blockid as $b ) {
	$blocklr[] = $blockl[] = "b".$b."l";
	$blocklr[] = $blockr[] = "b".$b."r";
}
$blocklrs = '"'.implode('","',$blocklr).'"';

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
	global $saveNames,$blockid,$adb;
	$data_array = array();
	
	foreach ( $blockid as $block ) {
		$base = "b$block";
		$res = array();
		$col = 0;
		$lc = $data[$base."l"];
		$rc = $data[$base."r"];
		$i = 0;
		$done = false;
		while ( !$done ) {
			$done = true;
			if ( $item=array_shift($lc) ) {
				$res[$i++] = $item;
				$done = false;
			}
			if ( $item=array_shift($rc) ) {
				$res[$i++] = $item;
				$done = false;
			}
		}
		$data_array[$block] = $res;
	}
	
	foreach($data_array AS $block => $items)
	{
		$i = 1;
		foreach($items AS $item)
		{
			$item = mysql_escape_string($item);
			$block  = mysql_escape_string($block);
			$result = $adb->query("UPDATE field SET block = '$block', sequence = '$i' WHERE fieldid = '$item' $col_check;");
			$i ++;
		}
	}
}

// Lets setup Sajax
require_once('include/dd/Sajax.php');
sajax_init();
// $sajax_debug_mode = 1;

function sajax_update($data)
{
	$data = parse_data($data);
	update_db($data, ""); 	//"AND (`set` = 'sajax1' OR `set` = 'sajax2')");
	return 'y';
}

sajax_export("sajax_update");
sajax_handle_client_request();

if(isset($_POST['order']))
{
	$data = parse_data($_POST['order']);
	update_db($data, ""); //"AND (`set` = 'left_col' OR `set` = 'right_col' OR `set` = 'center')");
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
#b0l {    width: 180px;    float: center;    margin-left: 5px; }
#b0r {    width: 180px;    float: center;    margin-left: 5px; }
#b1l {    width: 180px;    float: center;    margin-left: 5px; }
#b1r {    width: 180px;    float: center;    margin-left: 5px; }
#b2l {    width: 180px;    float: center;    margin-left: 5px; }
#b2r {    width: 180px;    float: center;    margin-left: 5px; }
#b3l {    width: 180px;    float: center;    margin-left: 5px; }
#b3r {    width: 180px;    float: center;    margin-left: 5px; }
#b4l {    width: 180px;    float: center;    margin-left: 5px; }
#b4r {    width: 180px;    float: center;    margin-left: 5px; }
#b5l {    width: 180px;    float: center;    margin-left: 5px; }
#b5r {    width: 180px;    float: center;    margin-left: 5px; }
#b6l {    width: 180px;    float: center;    margin-left: 5px; }
#b6r {    width: 180px;    float: center;    margin-left: 5px; }
#b7l {    width: 180px;    float: center;    margin-left: 5px; }
#b7r {    width: 180px;    float: center;    margin-left: 5px; }
#b8l {    width: 180px;    float: center;    margin-left: 5px; }
#b8r {    width: 180px;    float: center;    margin-left: 5px; }
#b9l {    width: 180px;    float: center;    margin-left: 5px; }
#b9r {    width: 180px;    float: center;    margin-left: 5px; }

form {
  clear: left;
  clear: right;
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
		window.status = 'Sajax version updated';
	}

	function onDrop() {
		var data = DragDrop.serData('g2'); 
	}

	function loadMe() {        
		var list;
		var panes = new Array("b0l","b0r","b1l","b1r","b2l","b2r","b3l","b3r","b4l","b4r","b5l","b5r","b6l","b6r","b7l","b7r","b8l","b8r","b9l","b9r");
		
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
		location.href = 'index.php?module=Settings&action=index';
	}
	//-->
</script>
<link rel="stylesheet" href="include/dd/dd_files/lists.css" type="text/css">
	<br>
	<?php
		echo get_module_title($mod_strings['LBL_MODULE_NAME'], $mod_strings['LBL_SETTINGS'].' : Update Field Order', true);
		//echo '<br><br>';
	?>

	<p align="left">
		<b>Please review the notes at the bottom for things to keep in mind!<br></b>
	</p>
<script language="JavaScript" type="text/javascript" src="include/dd/dd_files/coordinates.js"></script>
<script language="JavaScript" type="text/javascript" src="include/dd/dd_files/drag.js"></script>
<script language="JavaScript" type="text/javascript" src="include/dd/dd_files/dragdrop.js"></script>
<table width="75%" align="center" border="1" cellspacing="0" cellpadding="0" class="formOuterBorder">
	<tr>
		<td>
<?php
$rowStart1='<tr style="leftFormHeader"><td width="25%" class="leftFormHeader" border="1">';
$rowStart2='</td><td><table width="100%"><tr align="center">';
$colStart1='<td valign="top"><ul id="b';
$colStart2='" class="sortable boxy">';
$colEnd='</ul><div style="clear: left;">		</div></td>';
$rowEnd='</tr></table></td></tr>';
	
$fblock = "";
foreach ( $blockid as $block ) {
	$desc = "";
	if ( isset($fld_module) ) $desc = $mod_strings["LBL_BLOCK{$block}_HEADER"];
	if ( $desc == "" ) 
		$desc = ($block == 0 ? " Hidden " : " Block $block Information ");
	$fblock .= $rowStart1.$desc.$rowStart2;
	$col = 0;
	$lc = $colStart1.$block."l".$colStart2;
	$rc = $colStart1.$block."r".$colStart2;
	$b = $blocks[$block];
	if ( $b ) {
		foreach ($b as $key => $info ) {
			foreach ( $info as $key => $item ) {
				if ( $col ) 
					$rc .= "<li id='$item'>$key</li>\n";
				else
					$lc .= "<li id='$item'>$key</li>\n";
				$col = 1-$col;
			}
		}
	}
	$fblock .= $lc.$colEnd.$rc.$colEnd.$rowEnd;
}
echo $fblock;
?>
		</td>
	</tr>
</table>
<p align="left">
	1) &nbsp;If you move a field into a currently unused block, you will have to update the appropriate language file in
	<b>modules/<?php echo $fld_module ?>/language/en_us.lang.php</b>.<br>
	2) &nbsp;Please see notes on arranging fields below:
</p>
<div align="left">
	<table border="1" cellpadding="0" cellspacing="0" bordercolordark="white" bordercolorlight="black">
		<tr>
			<td width="325" align="right">
				<p align="center">
					The tabs are arrange in 2 columns as follows:
				</p>
			</td>
			<td width="325" align="right">
				<table border="1" cellpadding="0" cellspacing="0" bordercolordark="white" bordercolorlight="black" align="center">
					<tr><td width="30"><p align="center">A</p></td><td width="30"><p align="center">B</p></td></tr>
					<tr><td width="30"><p align="center">C</p></td><td width="30"><p align="center">D</p></td></tr>
					<tr><td width="30"><p align="center">E</p></td><td width="30"><p align="center">F</p></td></tr>
					<tr><td width="30"><p align="center">G</p></td><td width="30"><p align="center">H</p></td></tr>
					<tr><td width="30"><p align="center">I</p></td><td width="30"><p align="center">J</p></td></tr>
				</table>
			</td>
			<td width="325" align="right">
				<p align="center">
					If you try:
				</p>
			</td>
			<td width="325" align="right">
				<table border="1" cellpadding="0" cellspacing="0" bordercolordark="white" bordercolorlight="black" align="center">
					<tr><td width="30"><p align="center">A</p></td><td width="30"><p align="center">B</p></td></tr>
					<tr><td width="30"><p align="center">C</p></td><td width="30"><p align="center">D</p></td></tr>
					<tr><td width="30"><p align="center">E</p></td><td width="30"><p align="center">F</p></td></tr>
					<tr><td width="30"><p align="center">G</p></td><td width="30"><p align="center">&nbsp;</p></td></tr>
					<tr><td width="30"><p align="center">H</p></td><td width="30"><p align="center">&nbsp;</p></td></tr>
					<tr><td width="30"><p align="center">I</p></td><td width="30"><p align="center">&nbsp;</p></td></tr>
					<tr><td width="30"><p align="center">J</p></td><td width="30"><p align="center">&nbsp;</p></td></tr>
				</table>
			</td>
			<td width="325" align="right">
				<p align="center">
					It will be displayed as
				</p>
			</td>
			<td width="325" align="right">
				<table border="1" cellpadding="0" cellspacing="0" bordercolordark="white" bordercolorlight="black" align="center">
					<tr><td width="30"><p align="center">A</p></td><td width="30"><p align="center">B</p></td></tr>
					<tr><td width="30"><p align="center">C</p></td><td width="30"><p align="center">D</p></td></tr>
					<tr><td width="30"><p align="center">E</p></td><td width="30"><p align="center">F</p></td></tr>
					<tr><td width="30"><p align="center">G</p></td><td width="30"><p align="center">H</p></td></tr>
					<tr><td width="30"><p align="center">I</p></td><td width="30"><p align="center">J</p></td></tr>
				</table>
			</td>
			<td width="325" align="right"><p align="center">The best you can currently achieve is: 				</p></td>
			<td width="325" align="right">
				<table border="1" cellpadding="0" cellspacing="0" bordercolordark="white" bordercolorlight="black" align="center">
					<tr><td width="30"><p align="center">A</p></td><td width="30"><p align="center">B</p></td></tr>
					<tr><td width="30"><p align="center">C</p></td><td width="30"><p align="center">D</p></td></tr>
					<tr><td width="30"><p align="center">E</p></td><td width="30"><p align="center">F</p></td></tr>
					<tr><td width="30"><p align="center">G</p></td><td width="30"><p align="center">H</p></td></tr>
					<tr><td width="30"><p align="center">I</p></td><td width="30"><p align="center">J</p></td></tr>
					<tr><td width="30"><p align="center">K</p></td><td width="30"><p align="center">&nbsp;</p></td></tr>
				</table>
			</td>
		</tr>
	</table>
</div>
<p><p><p align="center">
<form align="center" action="" method="post">
	 <input type="hidden" name="order" id="order" value="">
	<div align="center">
		<input type="button" onclick="showValue()" value="Save">
	</div>
	<br>
</form>
<script language="JavaScript" type="text/javascript"><!--
	loadMe();
//-->
</script>
