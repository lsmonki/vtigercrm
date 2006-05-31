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


if($_REQUEST['faqid'] != '')
	$faqid = $_REQUEST['faqid'];

global $mod_strings;
$faq_array = $_SESSION['faq_array'];


$list = '<table width="100%"  border="0" cellspacing="0" cellpadding="0" class="dummy">';
for($i=0;$i<count($faq_array);$i++)
{

	if($faqid == $faq_array[$i]['id'])
	{
		$faq_id = $faq_array[$i]['id'];
		$faq_createdtime = $faq_array[$i]['faqcreatedtime'];
		$faq_modifiedtime = $faq_array[$i]['faqmodifiedtime'];
		$faq_productid = $faq_array[$i]['product_id'];
		$faq_category = $faq_array[$i]['category'];

		$comments_array = $_SESSION['faq_array'][$i]['comments'];
		$createdtime_array = $_SESSION['faq_array'][$i]['createdtime'];

		$comments_count = count($comments_array);

		$list .= '<tr><td class="detailedViewHeader">FAQ Title</td><td align="right" class="detailedViewHeader">
			  <span id="faq" class="lnkHdr" onMouseOver="fnShow(this)" onMouseOut="fnHideDiv(\'faqDetail\')">FAQ Detail</span></td></tr>';
		$list .= '<tr><td width="75%" valign="top" style="padding-right:5px;" colspan="2">'.$faq_array[$i]['question'];
		$list .= '<br><br><b>ANSWER : </b><br>'.$faq_array[$i]['answer'].'</td>
			    </tr>';

//<td>'.getArticleIdTime($faq_id,$faq_productid,$faq_category,$faq_createdtime,$faq_modifiedtime).'</td>

		$list .= '<tr><td colspan="2" class="detailedViewHeader">'.$mod_strings['LBL_COMMENTS'].'</td></tr>';

		$list .= '
			   <tr>
				<td colspan="2">
				   <div id="scrollTab2">
					<table width="98%"  border="0" cellspacing="5" cellpadding="5">';

		for($j=0;$j<$comments_count;$j++)
		{
			$list .= '
					   <tr>
						<td width="5%" valign="top"> '.($comments_count-$j).' </td>
						<td width="95%">
							'.$comments_array[$j];

			if ($createdtime_array[$j]!="0000-00-00 00:00:00")
				$list .= '<br><span class="hdr">'.$mod_strings['LBL_ADDED_ON'].$createdtime_array[$j].'</span>';

			$list .= '
						</td>
					   </tr>';
		}
		$list .= '
					</table>
				   </div>
				</td>
			   </tr>
			   <tr>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
			   </tr>

				<form name="comments" method="POST" action="index.php">
				   <input type="hidden" name="module">
				   <input type="hidden" name="action">
				   <input type="hidden" name="fun">
				   <input type=hidden name=faqid value="'.$faqid.'">
			   <tr>
				<td colspan="2" class="detailedViewHeader">'.$mod_strings['LBL_ADD_COMMENT'].'</td>
			   </tr>
			   <tr>
				<td colspan="2" class="dvtCellInfo">
					<textarea name="comments" cols="80" rows="5" class="detailedViewTextBox">&nbsp;</textarea>
				</td>
			   </tr>
			   <tr>
				<td colspan="2" class="dvtCellInfo">
					<input title="Save [Alt+S]" accesskey="S" class="small"  name="submit" value="'.$mod_strings['LBL_SUBMIT'].'" style="width: 70px;" type="submit" onclick="this.form.module.value=\'Faq\';this.form.action.value=\'index\';this.form.fun.value=\'faq_updatecomment\'; return verify_data(this.form,coments);"/>
				</td>
			   </tr>
			   <tr>
				<td colspan="2">&nbsp;</td>
			   </tr>
				</form>

			   <tr>
				<td style="padding:3px;">'.getPageOption().'</td>
			   </tr>';
	}
}

$list .= '		</table>';

//This is added to get the FAQ details as a Popup on Mouse over
$list .= getArticleIdTime($faq_id,$faq_productid,$faq_category,$faq_createdtime,$faq_modifiedtime);

echo $list;





?>
