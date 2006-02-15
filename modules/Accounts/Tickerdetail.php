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
include("modules/Accounts/Account.php");
include("getCompanyProfile.php");
$variable = $_REQUEST['tickersymbol'];
$url = "http://moneycentral.msn.com/investor/research/profile.asp?Symbol=".trim($variable);
$data = getComdata($url,trim($variable));
$summary = array_shift($data);
        list($comp, $address, $phone, $fax, $site, $industry, $emp,$desc1,$desc2) = explode(":", $summary);
        $address = str_replace("Company Report","",$address);
        $address = str_replace("Phone","",$address);
        $phone = str_replace("Fax","",$phone);
        $fax = str_replace("http","",$fax);
        $site = str_replace("Industry","",$site);
        $site = str_replace("//","",$site);
        $industry = str_replace("Employees","",$industry);
        $emp = str_replace("Exchange","",$emp);
        $first_array =array_slice($data,0, 8);
        $second_array = array_slice($data,8);
$output='';
	$output .= "<div id='quote' style='display:block' class='divEventToDo'>
		    <input type='hidden' name='address' value='".trim($address)."'>
		    <input type='hidden' name='Phone' value='".trim($phone)."'>
		    <input type='hidden' name='Fax' value='".trim($fax)."'>
		    <input type='hidden' name='site' value='".trim($site)."'>
		    <input type='hidden' name='emp' value='".trim($emp)."'>
		    <table><tbody>";
	//echo '<pre>';print_r($data);echo '</pre>';
	//$output .= "<tr><td>&nbsp;</td><td valign='top' width='24'><img src='themes/blue/images/tblPro1BtnHide.gif' alt='Minimize / Maximize' border='0' onclick=\"javascript:expandCont('quote');\"></td></tr>";
	$output .="<tr><td><table><tr><td><table width='185'>";
	foreach($first_array as $arr => $val)
	{
		$output .= "<tr>";
		for($j=0;$j<count($val);$j+=2)
		{
			$output .= "<td align=left width='48%' class='dvtCellLabel' >".$val[$j]."</td>
				    <td align=left class='dvtCellInfo'>".$val[$j+1]."</td>";
		}
		$output .= "</tr>";
	}
	$output .="</table></td>";
	$output .="<td><table width='185'>";
	array_shift($second_array[0]);
	array_shift($second_array[0]);
        foreach($second_array as $arr => $val)
        {
                $output .= "<tr>";
                for($j=0;$j<count($val);$j+=2)
                {
                        	$output .= "<td align=left width='48%' class='dvtCellLabel' >".$val[$j]."</td>
                                	    <td align=left class='dvtCellInfo'>".$val[$j+1]."</td>";
                }
                $output .= "</tr>";
        }
        $output .="</table></td><td align=left><a href='http://finance.yahoo.com/q/bc?s=WIT&amp;t=1d' target='_blank'><img src='http://ichart.finance.yahoo.com/t?s=".trim($variable)."' width='192' height='96' alt='[Chart]' border='0'></a></td></tr></table></td>
		</tr>";
	$output .= "<tr><table><tr><td align=left width='20%' id='label' class='dvtCellLabel'>BUSINESS SUMMARY:</td><td width='60%' align=left class='dvtCellInfo' id='summary'>".$desc1." ".$desc2."</td></tr>";
        $output .= "<tr><td><b>Is this information correct?</b></td><td><input title='OK' class='small' language='javascript'  onclick=\"populateData('quote' ,'subquote');\" type='button' name='button' value='Yes' style='width:70px'>
                   <input title='' class='small' onclick='' type='button' name='button' value='No' style='width:70px'></td></tr></table></tr></tbody></table>
		</div>";
        $output .= "<script language='Javascript'>
                        var leftpanelistarray=new Array('datahide');
                                  setExpandCollapse_gen()</script><div id='subquote' style='display:none' class='divEventToDo'><table><tbody>";
	$output .= "<tr><td>&nbsp;</td><td valign='top' width='24'><img src='themes/blue/images/tblPro1BtnHide.gif' alt='Minimize / Maximize' border='0' onclick=\"javascript:expandCont('datahide');\"></td></tr>";
        $output .="<tr><td><div id='datahide' style='display:block'><table><tr><td><table width='185'>";
        foreach($first_array as $arr => $val)
        {
                $output .= "<tr>";
                for($j=0;$j<count($val);$j+=2)
                {
                        $output .= "<td align=left width='48%' class='dvtCellLabel' >".$val[$j]."</td>
                                    <td align=left class='dvtCellInfo'>".$val[$j+1]."</td>";
                }
                $output .= "</tr>";
        }
        $output .="</table></td>";
        $output .="<td><table width='185'>";
        foreach($second_array as $arr => $val)
        {
                $output .= "<tr>";
                for($j=0;$j<count($val);$j+=2)
                {
                                $output .= "<td align=left width='48%' class='dvtCellLabel' >".$val[$j]."</td>
                                            <td align=left class='dvtCellInfo'>".$val[$j+1]."</td>";
                }
                $output .= "</tr>";
        }
        $output .="</table></td><td align=left><a href='http://finance.yahoo.com/q/bc?s=WIT&amp;t=1d' target='_blank'><img src='http://ichart.finance.yahoo.com/t?s=".trim($variable)."' width='192' height='96' alt='[Chart]' border='0'></a></td></tr></table></div></td>
                </tr>";
	$output .="</tbody></table></div>";
	echo $output;
?>
