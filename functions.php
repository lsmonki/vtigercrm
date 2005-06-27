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

require_once("language/en_us.lang.php");

function getNoofFaqsPerCategory($category_name)
{
	$faq_array = $_SESSION['faq_array'];
	$count = 0;
	for($i=0;$i<count($faq_array);$i++)
	{
		if($category_name == $faq_array[$i]['category'])
			$count++;
	}
	return $count;
}
function getNoofFaqsPerProduct($productid)
{
	$faq_array = $_SESSION['faq_array'];
	$count = 0;
	for($i=0;$i<count($faq_array);$i++)
	{
		if($productid == $faq_array[$i]['product_id'])
			$count++;
	}
	return $count;
}
function getLatestlyCreatedFaqList()
{
	global $mod_strings;
	$list = '';
	$product_array = $_SESSION['product_array'];
	$faq_array = $_SESSION['faq_array'];
	$list = '<div class="kbHead">'.$mod_strings['LBL_RECENTLY_CREATED'].'</div>';
	$list .= '<br><table width="100%" border="0" cellspacing="0" cellpadding="0">';
	
	for($i=0;$i<count($faq_array);$i++)
	{
		$record_exist = true;
		$list .= '<tr><td width="15"><img src="images/faq.gif" valign="absmiddle"></td><td>';
		$list .= '<a class="kbFAQ" href=index.php?fun=faq_comments&faqid='.$faq_array[$i]['id'].'>'.$faq_array[$i]['question'].'</a>';
		$list .= '</td></tr><tr><td></td><td class="kbAnswer">';
		$list .= $faq_array[$i]['answer'].'</td></tr><tr><td height="10"></td></tr>';
	}
	if(!$record_exist)
		$list .= $mod_strings['LBL_NO_FAQ'];

	$list .= '</table>';
	return $list; 
}
function ListFaqsPerCategory($category_index)
{
	global $mod_strings;
	$list = '';
	$category_array = $_SESSION['category_array'];
	$faq_array = $_SESSION['faq_array'];
	$category = $category_array[$category_index];
	$list = '<div class="kbHead">'.$mod_strings['LBL_CATEGORY'].': '.$category.'</div>';
	$list .= '<br><table width="100%" border="0" cellspacing="0" cellpadding="0">';

	for($i=0;$i<count($faq_array);$i++)
	{
		if($category == $faq_array[$i]['category'])
		{
			$flag = true;
			$list .= '<tr><td width="15"><img src="images/faq.gif" valign="absmiddle"></td>';
			$list .= '<td><a class="kbFAQ" href=index.php?fun=faq_comments&faqid='.$faq_array[$i]['id'].'>'.$faq_array[$i]['question'].'</a></td></tr>';
			$list .= '<tr><td></td><td class="kbAnswer">'.$faq_array[$i]['answer'].'</td></tr><tr><td height="10"></td></tr>';
		}
	}
	if(!$flag)
		$list .= $mod_strings['LBL_NO_FAQ_IN_THIS_CATEGORY'];
	$list .= '</table>';
	return $list; 
}
function ListFaqsPerProduct($productid)
{
	global $mod_strings;
	$list = '';
	$product_array = $_SESSION['product_array'];
	$faq_array = $_SESSION['faq_array'];
	$list = '<div class="kbHead">'.$mod_strings['LBL_PRODUCT'].': '.getProductname($productid).'</div>';
	$list .= '<br><table width="100%" border="0" cellspacing="0" cellpadding="0">';
	
	for($i=0;$i<count($faq_array);$i++)
	{
		if($productid == $faq_array[$i]['product_id'])
		{
			$flag = true;
			$list .= '<tr><td width="15"><img src="images/faq.gif" valign="absmiddle"></td><td>';
			$list .= '<a class="kbFAQ" href=index.php?fun=faq_comments&faqid='.$faq_array[$i]['id'].'>'.$faq_array[$i]['question'].'</a>';
			$list .= '</td></tr><tr><td></td><td class="kbAnswer">';
			$list .= $faq_array[$i]['answer'].'</td></tr><tr><td height="10"></td></tr>';
		}
	}
	if(!$flag) 
		$list .= $mod_strings['LBL_NO_FAQ_IN_THIS_PRODUCT'];
	$list .= '</table>';
	return $list; 
}

function Faq_Comments($faqid)
{
	global $mod_strings;
	$faq_array = $_SESSION['faq_array'];
	$list = '<table width="100%" border="0" cellspacing="0" cellpadding="0"><tr><td valign="top">';
	$list .= '<table width="100%" border="0" cellspacing="0" cellpadding="0">';
	
	for($i=0;$i<count($faq_array);$i++)
	{
		if($faqid == $faq_array[$i]['id'])
		{
			$list .= '<tr><td class="kbFAQ" style="font-size:18px;padding-bottom:5px;">'.$faq_array[$i]['question'].'</td></tr>';
			$list .= '<tr><td>'.$faq_array[$i]['answer'].'</td></tr></table>';
			
			$faq_id = $faq_array[$i]['id'];
			$faq_createdtime = $faq_array[$i]['faqcreatedtime'];
			$faq_modifiedtime = $faq_array[$i]['faqmodifiedtime'];
			$faq_productid = $faq_array[$i]['product_id'];
			$faq_category = $faq_array[$i]['category'];
			
			$comments_array = $_SESSION['faq_array'][$i]['comments'];
			$createdtime_array = $_SESSION['faq_array'][$i]['createdtime'];
			
			$list .= '<br><table width="100%" border="0" cellspacing="0" cellpadding="0">';
			$list .= '<tr><td><strong>'.$mod_strings['LBL_COMMENTS'].':</strong></td></tr>';
			for($j=0;$j<count($comments_array);$j++)
			{
				$list .= '<tr><td>'.$comments_array[$j].'</td></tr>';
				if ($createdtime_array[$j]!="0000-00-00 00:00:00")
					$list .= '<tr><td height="20" class="kbFAQInfo">'.$mod_strings['LBL_ADDED_ON'].$createdtime_array[$j].'</td></tr>';
				$list .= '<tr><td height="7"></td></tr>';
			}
			$list .= '</table>';
		}	
	}
	
	$list .= '<br><table width="100%" border="0" cellspacing="0" cellpadding="0">';
	$list .= '<form name="Submit" method="POST" action="index.php">';
	$list .= '<tr><td><strong>'.$mod_strings['LBL_ADD_COMMENT'].'</strong>: </td></tr>';
	$list .= '<tr><td><textarea name="comments" rows="7" cols="60"></textarea>';
	$list .= '<input type=hidden name=fun value="save"><input type=hidden name=faqid value="'.$faqid.'"></td></tr>';
	$list .= '<tr><td><input type=submit name=save onclick="this.save.value=true" value='.$mod_strings['LBL_SUBMIT'].'></td></tr>';
	$list .= '</form></table>';
	
	$list .= '</td><td valign="top" style="padding:5px;">';
	$list .= getArticleIdTime($faq_id,$faq_productid,$faq_category,$faq_createdtime,$faq_modifiedtime);	
	$list .= '<br><br>';
	$list .= getPageOption();
	$list .= '</td></tr></table>';

	return $list;
}
function getArticleIdTime($faqid,$product_id,$faqcategory,$faqcreatedtime,$faqmodifiedtime)
{
	global $mod_strings;
	$list = '<table width="100%" border="0" cellspacing="2" cellpadding="2" class="kbArticleInfo">';
	$list .= '<tr><td align="right" nowrap>'.$mod_strings['LBL_ARTICLE_ID'].': </td><td nowrap>'.$faqid.'</td></tr>';
	$list .= '<tr><td align="right" nowrap>'.$mod_strings['LBL_PRODUCT'].': </td><td nowrap>'.getProductName($product_id).'</td></tr>';
	$list .= '<tr><td align="right" nowrap>'.$mod_strings['LBL_CATEGORY'].': </td><td nowrap>'.$faqcategory.'</td></tr>';
	$list .= '<tr><td align="right" nowrap>'.$mod_strings['LBL_CREATED_DATE'].': </td><td nowrap>'.substr($faqcreatedtime,0,10).'</td></tr>';
	$list .= '<tr><td align="right" nowrap>'.$mod_strings['LBL_MODIFIED_DATE'].': </td><td nowrap>'.substr($faqmodifiedtime,0,10).'</td></tr>';
	$list .= '</table>';
	return $list;
}
function getPageOption()
{
	global $mod_strings;
	$list = '<table width="100%" border="0" cellspacing="0" cellpadding="0" class="kbArticleInfo">';
	$list .= '<tr><td class="kbPgOptHead" height="20">'.$mod_strings['LBL_PAGE_OPTIONS'].'</td></tr>';
	$list .= '<tr><td style="padding:3px;"><table width="100%" border="0" cellspacing="3" cellpadding="3">';
	$list .= '<tr><td width="18" align="center"><img src="images/print.gif" valign="absmiddle"></td><td><a href="javascript:printPage()">'.$mod_strings['LBL_PRINT_THIS_PAGE'].'</a></td></tr>';
	$list .= '<tr><td width="18" align="center"><img src="images/email.gif" valign="absmiddle"></td><td><a href="javascript:sendAsEmail();">'.$mod_strings['LBL_EMAIL_THIS_PAGE'].'</a></td></tr>';
	$list .= '<tr><td width="18" align="center"><img src="images/favorite.gif" valign="absmiddle"></td><td><a href="javascript:addToFavorite();">'.$mod_strings['LBL_ADD_TO_FAVORITES'].'</a></td></tr>';
	$list .= '</table>';
	$list .= '</td></tr>';
	$list .= '</table>';
	$list .= '<script language="JavaScript">
				function printPage() {
					window.print()
				}
				function sendAsEmail() {
					var emailBody=escape("Here\'s an article you might be interested in: "+String.fromCharCode(13)+String.fromCharCode(13)+"URL: "+document.location.href)
					document.location.href = "mailto:?body="+emailBody;
				}
				function addToFavorite() {
					if (document.all) {
						window.external.addFavorite(document.location.href,document.title);
					} else {
						alert("Press Ctrl+D")
					}
				}
			</script>';
	
	return $list;
}
function getProductName($productid)
{
	$product_array = $_SESSION['product_array'];
	$productname = '';
	for($i=0;$i<count($product_array);$i++)
	{
		if($productid == $product_array[$i]['productid'])
			$productname = $product_array[$i]['productname'];
	}
	return $productname;
}
function getSearchCombo()
{
	$category_array = $_SESSION['category_array'];
	$product_array = $_SESSION['product_array'];
	$comboarray = '<select name="search_category">';
	$comboarray .= '<OPTION value="all:All">All</OPTION>';
	$comboarray .= '<OPTGROUP label="Categories">';
	for($i=0;$i<count($category_array);$i++)
	{
		$selected = '';
		$search_category = explode(":",$_REQUEST['search_category']);
		if($category_array[$i] == $search_category[1])
			$selected = 'selected';
		$comboarray .= '<OPTION value="category:'.$category_array[$i].'"'.$selected.'>'.$category_array[$i].'</OPTION>';
	}
	$comboarray .= '</OPTGROUP>';
	$comboarray .= '<OPTGROUP label="Products">';
        for($i=0;$i<count($product_array);$i++)
        {
                $selected = '';
		$search_category = explode(":",$_REQUEST['search_category']);
                if($product_array[$i]['productname'] == $search_category[1])
                        $selected = 'selected';
                $comboarray .= '<OPTION value="products:'.$product_array[$i]['productname'].'"'.$selected.'>'.$product_array[$i]['productname'].'</OPTION>';
        }
        $comboarray .= '</OPTGROUP>';
	$comboarray .= '</select>';
	return $comboarray;
}
function getSearchResult($search_text,$search_value,$search_by)
{
	global $mod_strings;
	$faq_array = $_SESSION['faq_array'];
	
	$list = '<div class="kbHead">'.$mod_strings['LBL_SEARCH_RESULT'].'</div>';
	$list .= '<br><table width="100%" border=0 cellspacing=0 cellpadding=0>';

	if($search_value == 'All')
        {
                for($i=0;$i<count($faq_array);$i++)
                {
			if($search_text != '')
	                        $flag = @strstr($faq_array[$i]['question'],$search_text);
			else
				$flag = true;

                        if($flag)
                        {
				$record_exist = true;
                                $list .= '<tr><td width="15"><img src="images/faq.gif" valign="absmiddle"></td><td>';
                                $list .= '<a class="kbFAQ" href=index.php?fun=faq_comments&faqid='.$faq_array[$i]['id'].'>';
                                $list .= $faq_array[$i]['question'].'</a>';
                                $list .= '</td></tr><tr><td></td><td class="kbAnswer">';
								$list .= $faq_array[$i]['answer'].'</td></tr><tr><td></td>';
								$list .= '<td height="18" class="kbFAQInfo">'.$mod_strings['LBL_CATEGORY'].': '.$faq_array[$i]['category'].'</td></tr>';
								$list .= '<tr><td height="10"></td></tr>';
                        }
                }
		if(!$record_exist)
                        $list .=  $mod_strings['LBL_NO_FAQ_IN_THIS_SEARCH_CRITERIA'];
        }
        elseif($search_by == 'category')
        {
                for($i=0;$i<count($faq_array);$i++)
                {
			if($search_text != '')
	                        $flag = @strstr($faq_array[$i]['question'],$search_text);
			else
				$flag = true;
                        if($flag && $faq_array[$i]['category'] == $search_value)
                        {
				$record_exist = true;
                                $list .= '<tr><td width="15"><img src="images/faq.gif" valign="absmiddle"></td><td>';
                                $list .= '<a class="kbFAQ" href=index.php?fun=faq_comments&faqid='.$faq_array[$i]['id'].'>';
                                $list .= $faq_array[$i]['question'].'</a>';
								$list .= '</td></tr><tr><td></td><td class="kbAnswer">';
                                $list .= $faq_array[$i]['answer'].'</td></tr>';
                        }
                }
		if(!$record_exist)
			$list .=  $mod_strings['LBL_NO_FAQ_IN_THIS_SEARCH_CRITERIA'];
        }
	elseif($search_by == 'products')
	{
		$product_array = $_SESSION['product_array'];
		$faq_array = $_SESSION['faq_array'];
		for($i=0;$i<count($product_array);$i++)
		{
			if($product_array[$i]['productname'] == $search_value)
			{
				for($j=0;$j<count($faq_array);$j++)
       				{
					if($search_text != '')
		                                $flag = @strstr($faq_array[$j]['question'],$search_text);
                		        else
                                		$flag = true;
			        	if($flag && ($product_array[$i]['productid'] == $faq_array[$j]['product_id']))
			                {
                        			$record_exist = true;
			                        $list .= '<tr><td width="15"><img src="images/faq.gif" valign="absmiddle"></td><td>';
			                        $list .= '<a class="kbFAQ" href=index.php?fun=faq_comments&faqid='.$faq_array[$j]['id'].'>'.$faq_array[$j]['question'].'</a>';
                        			$list .= '</td></tr><tr><td></td><td class="kbAnswer">';
			                        $list .= $faq_array[$j]['answer'].'</td></tr><tr><td height="10"></td></tr>';
			                }
			        }
			}
		}
		if(!$record_exist)
                        $list .=  $mod_strings['LBL_NO_FAQ_IN_THIS_SEARCH_CRITERIA'];
	}

	$list .= '</table>';
	return $list;
}
?>
