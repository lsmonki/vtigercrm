<?php
require_once('database/DatabaseConnection.php');
//require_once('HelpDeskUtil.php');
require_once('XTemplate/xtpl.php');
require_once('include/utils.php');

//Retreiving the id from the request:
$productid = $_REQUEST['record'];

//Retreiving the product info from database
$query = "select products.id,products.unit_price,products.productname,products.category,products.product_description,products.qty_per_unit,products.commissionrate,products.discontinued from products where products.id = ".$productid;
$productresult = mysql_query($query);

//$user_id = mysql_result($productresult,0,'assigned_user_id');

//$user_query = "select user_name from users where id='".$user_id."'"; 
//$user_result = mysql_query($user_query);
//$user_name = mysql_result($user_result,0,'user_name');

global $app_strings;
global $app_list_strings;
global $mod_strings;
global $current_user;

global $theme;
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
require_once($theme_path.'layout_utils.php');

$xtpl=new XTemplate ('modules/Products/ProductDetailView.html');
$xtpl->assign("MOD", $mod_strings);
$xtpl->assign("APP", $app_strings);

//$xtpl->assign("NAME", mysql_result($productresult,0,'title'));
$xtpl->assign("PRODUCTID", mysql_result($productresult,0,'id'));
//$productid =  mysql_result($productresult,0,'id');
//$xtpl->assign("GROUPVALUE", mysql_result($productresult,0,'groupname'));
//$xtpl->assign("USERNAME", $user_name);

$xtpl->assign("NAME",mysql_result($productresult,0,'productname'));
$xtpl->assign("DESCRIPTION",mysql_result($productresult,0,'product_description'));
$xtpl->assign("QTY_PER_UNIT",mysql_result($productresult,0,'qty_per_unit'));
$xtpl->assign("COMISSIONRATE",mysql_result($productresult,0,'commissionrate'));
if(mysql_result($productresult,0,'discontinued') == 1)
{
	$active = 'yes';
}
else
{
	$active = 'no';
}
$xtpl->assign("ACTIVE",$active);
$xtpl->assign("CODE",mysql_result($productresult,0,'category'));
$xtpl->assign("UNITPRICE",mysql_result($productresult,0,'unit_price'));

$xtpl->parse("main");
$xtpl->out("main");

require_once('modules/Products/binaryfilelist.php');
echo '<br><br>';
echo '<table width="50%" cellpadding="0" cellspacing="0" border="0"><tbody><tr>';
echo '<form border="0" action="index.php" method="post" name="form" id="form">';

echo '<input type="hidden" name="module">';
echo '<input type="hidden" name="return_module" value="'.$currentModule.'">';
echo '<input type="hidden" name="return_id" value="'.$productid.'">';
echo '<input type="hidden" name="action">';


echo '<td>';
echo '<table cellpadding="0" cellspacing="0" border="0"><tbody><tr>
                <td class="formHeader" vAlign="top" align="left" height="20">
         <img src="' .$image_path. '/left_arc.gif" border="0"></td>
   <td class="formHeader" vAlign="middle" background="' . $image_path. '/header_tile.gif" align="left" noWrap width="100%" height="20">Attachments</td>
        <td  class="formHeader" vAlign="top" align="right" height="20">
                  <img src="' .$image_path. '/right_arc.gif" border="0"></td>
                </tr></tbody></table>
      </td>';
echo '<td>&nbsp;</td>';
echo '<td>&nbsp;</td>';
echo '<td valign="bottom"><input title="Attach File" accessyKey="F" class="button" onclick="this.form.action.value=\'upload\';this.form.module.value=\'Products\'" type="submit" name="button" value="New Attachment"></td>';
echo '<td width="50%"></td>';

echo '</td></tr></form></tbody></table>';
echo getAttachmentsList($productid, $theme);
// Stick on the form footer
echo get_form_footer();





?>
