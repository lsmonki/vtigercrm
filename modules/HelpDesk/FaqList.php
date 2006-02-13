<?
/*********************************************************************************
** The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
*
 ********************************************************************************/
require_once('include/database/PearDatabase.php');
require_once('XTemplate/xtpl.php');
require_once('include/utils.php');
require_once('modules/HelpDesk/HelpDeskUtil.php');
require_once('themes/'.$theme.'/layout_utils.php');

global $app_strings;
global $mod_strings;

global $theme;
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
require_once($theme_path.'layout_utils.php');

$xtpl=new XTemplate ('modules/HelpDesk/FaqList.html');
$xtpl->assign("MOD", $mod_strings);
$xtpl->assign("APP", $app_strings);
$xtpl->assign("IMAGE_PATH",$image_path);


if(isset($_REQUEST['query']) && $_REQUEST['query'] != '' && $_REQUEST['query'] == 'true')
{
	
	$query_val = "true";
        if (isset($_REQUEST['category'])) $category = $_REQUEST['category'];
	if (isset($_REQUEST['question'])) $question = $_REQUEST['question'];
        if (isset($_REQUEST['date'])) $date = $_REQUEST['date'];
        if (isset($_REQUEST['current_user_only'])) $current_user_only = $_REQUEST['current_user_only'];

	$search_query="select faq.id,question,answer,category,author_id,users.id,users.user_name from faq left join users on faq.author_id=users.id where faq.deleted ='0'";

	if (isset($category) && $category !='')
	{
	 	$search_query .= " and faq.category like '".$category."%'";
		$query_val .= "&category=".$category;
		$xtpl->assign("CATEGORY", $category);
	}

	if (isset($question) && $question !='')
	{
		$search_query .= " and faq.question like '".$question."%'";
		$query_val .= "&question=".$question;
		$xtpl->assign("QUESTION", $question);
	}
	
	if (isset($date) && $date !='')
	{
		$date_criteria = $_REQUEST['date_crit'];
		if($date_criteria == 'is')
		{ 
	 		$search_query .= " and faq.date_created like '".$date."%'";
			$xtpl->assign("IS", 'selected');
		}
		if($date_criteria == 'isnot')
		{ 
	 		$search_query .= " and faq.date_created not like '".$date."%'";
			$xtpl->assign("ISNOT", 'selected');
		}
		if($date_criteria == 'before')
		{ 
	 		$search_query .= " and faq.date_created < '".$date." 23:59:59'";
			$xtpl->assign("BEFORE", 'selected');
		}
		if($date_criteria == 'after')
		{ 
	 		$search_query .= " and faq.date_created > '".$date." 00:00:00'";
			$xtpl->assign("AFTER", 'selected');
		}
		$query_val .= "&date=".$date;
		$query_val .= "&date_crit=".$date_criteria;
		$xtpl->assign("DATE", $date);
	}
	
	if (isset($current_user_only) && $current_user_only !='')
	{
	 	$search_query .= " and faq.author_id='".$current_user->id."'";
		$query_val .= "&current_user_only=".$current_user_only;
		 $xtpl->assign("CURRENT_USER_ONLY", "checked");
	}
	 
        //echo $search_query;
	//echo '<BR>';
	//echo $_REQUEST['query'];
	$tktresult = $adb->query($search_query);
}
else
{
	//Retreive the list from Database
	$tktresult = getFaqList();
	$query_val = "false";
}

//Retreiving the no of rows
$noofrows = $adb->num_rows($tktresult);

//Retreiving the start value from request
if(isset($_REQUEST['start']) && $_REQUEST['start'] != '')
{
	$start = $_REQUEST['start'];
}
else
{
	
	$start = 1;
}
//Setting the start value
//Setting the start to end counter
$starttoendvaluecounter = $list_max_entries_per_page - 1;
//Setting the ending value
if($noofrows > $list_max_entries_per_page)
{
	$end = $start + $starttoendvaluecounter;
	if($end > $noofrows)
	{
		$end = $noofrows;
	}
	$startvalue = 1;
	$remainder = $noofrows % $list_max_entries_per_page;
	if($remainder > 0)
	{
		$endval = $noofrows - $remainder + 1;
	}
	elseif($remainder == 0)
	{
		$endval = $noofrows - $starttoendvaluecounter;
	}
}
else
{
	$end = $noofrows;
}


//Setting the next and previous value
if(isset($_REQUEST['start']) && $_REQUEST['start'] != '')
{
	$tempnextstartvalue = $_REQUEST['start'] + $list_max_entries_per_page;
	if($tempnextstartvalue <= $noofrows)
	{
		
		$nextstartvalue = $tempnextstartvalue;
	}
	$tempprevvalue = $_REQUEST['start'] - $list_max_entries_per_page;
	if($tempprevvalue  > 0)
	{
		$prevstartvalue = $tempprevvalue;
	}
}
else
{
	if($noofrows > $list_max_entries_per_page)
	{
		$nextstartvalue = $list_max_entries_per_page + 1;
	}
}


echo get_module_title("HelpDesk", "Faqs" , true);
echo "<br>";
echo get_form_header("Faq Search", "", false);

$xtpl->assign("FAQLISTHEADER", get_form_header("Faq's List", "", false ));

$tkList = '';
for ($i=$start; $i<=$end; $i++)
{
	if (($i%2)==0)
		$tkList .= '<tr height=20 class=evenListRow>';
	else
		$tkList .= '<tr height=20 class=oddListRow>';
   		$tkList .= '<td WIDTH="1" class="blackLine" NOWRAP><IMG SRC="'.$image_path.'blank.gif"></td>';	
	$question = '<a href="index.php?action=FaqInfoView&module=HelpDesk&record='.$adb->query_result($tktresult,$i-1,"id").'">'.$adb->query_result($tktresult,$i-1,"question").'</a>';
       $tkList .= '<td style="padding:0px 3px 0px 3px;">'.$question.'</td>';
   		$tkList .= '<td WIDTH="1" class="blackLine" NOWRAP><IMG SRC="'.$image_path.'blank.gif"></td>';
        $tkList .= '<td style="padding:0px 3px 0px 3px;">'.$adb->query_result($tktresult,$i-1,"category").'</td>';
		$tkList .= '<td WIDTH="1" class="blackLine" NOWRAP><IMG SRC="'.$image_path.'blank.gif"></td>';
                $tkList .= '<td style="padding:0px 3px 0px 3px;">'.$adb->query_result($tktresult,$i-1,"user_name").'</td>';
   		$tkList .= '<td WIDTH="1" class="blackLine" NOWRAP><IMG SRC="'.$image_path.'blank.gif"></td>';

		$tkList .= '<td style="padding:0px 3px 0px 3px;"><a href="index.php?module=HelpDesk&action=EditFaq&return_module=HelpDesk&return_action=FaqList&record='.$adb->query_result($tktresult,$i-1,"id").'">[ '.$app_strings['LNK_EDIT'].' ]</a> <a href="index.php?module=HelpDesk&action=DeleteFaq&return_module=HelpDesk&return_action=FaqList&record='.$adb->query_result($tktresult,$i-1,"id").'">[ '.$app_strings['LNK_DELETE'].' ]</a></td>';

	$tkList .= '</tr>';
}
$xtpl->assign("FAQLIST", $tkList);
if(isset($startvalue))
{
	$startoutput = '<a href="index.php?action=FaqList&module=HelpDesk&start='.$startvalue.'&query='.$query_val.'"><b>Start</b></a>';
}
else
{
	$startoutput = '[ Start ]';
}
if(isset($endval))
{
	$endoutput = '<a href="index.php?action=FaqList&module=HelpDesk&start='.$endval.'&query='.$query_val.'"><b>End</b></a>';
}
else
{
	$endoutput = '[ End ]';
}
if(isset($nextstartvalue))
{
	$nextoutput = '<a href="index.php?action=FaqList&module=HelpDesk&start='.$nextstartvalue.'&query='.$query_val.'"><b>Next</b></a>';
}
else
{
	$nextoutput = '[ Next ]';
}
if(isset($prevstartvalue))
{
	$prevoutput = '<a href="index.php?action=FaqList&module=HelpDesk&start='.$prevstartvalue.'&query='.$query_val.'"><b>Prev</b></a>';
}
else
{
	$prevoutput = '[ Prev ]';
}
$xtpl->assign("Start", $startoutput);
$xtpl->assign("End", $endoutput);
$xtpl->assign("Next", $nextoutput);
$xtpl->assign("Prev", $prevoutput);

$xtpl->parse("main");

$xtpl->out("main");

?>

