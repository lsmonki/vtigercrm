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

function BuildSearchForm($focus)
{
	global $current_module_strings,$app_strings,$adb;
    $advanced = (isset($_REQUEST['advanced']) && $_REQUEST['advanced'] == 'true');

	if ( !isset($focus) )
	{
        echo "Very big error!  No focus for Search Form.\n<br>";
    	print_r($base);
    	print_r($custom);
    	print_r($focus);
    	return "";
    }
    
	if ( is_array($focus->base_table_name ) )
		$base = "tablename like '".implode("%' or tablename like '",$focus->base_table_name)."%'";
	else
		$base = "tablename like '".$focus->base_table_name."%'";
		
    $custom = "tablename like '".$focus->cf_table_name."%'";
    $doadvanced = ( isset($focus->cf_table_name) && $focus->cf_table_name <> "");
    $fields = ( $advanced ) ? array() : $focus->sortby_fields;

    if ( preg_match("/\w+\((\d+)/",$focus->object_name,$res) )
        $tabid = $res[1];
    else
        $tabid = getTabid($focus->object_name);
    $tabsrch = " AND tabid=$tabid ";
	$search_form=new XTemplate('include/SearchBlock.html');

	// Stick the form header out there.
	$custfld = SearchFieldBlock("$base", $focus->module_id, $tabsrch,
        true, $advanced, $advanced ? 1 : 0,
        $current_module_strings['LBL_GENERAL_INFORMATION'], $fields, $doadvanced);
	$search_form->filecontents = str_replace("{STANDARDFILTER}", $custfld, $search_form->filecontents);
    // Now do advanced
	if ( $doadvanced && $advanced )
    {
		$custfld = SearchFieldBlock("$custom",$focus->module_id, $tabsrch,
            false, $advanced, 0, $app_strings['LBL_CUSTOM_INFORMATION'],
            array());
    	$search_form->filecontents = str_replace("{ADVANCEDFILTER}", $custfld, $search_form->filecontents);
        //$url_string .="&advanced=true";
    }

    // Now, reset/reload template so partials are scanned
    $search_form->blocks = $search_form->maketree($search_form->filecontents,"main");
    $search_form->scan_globals();
    return $search_form;
}

function SearchFieldBlock($tableName,$colidName,$tabsrch, $basic=true,$advanced=false,$block=0,$label="",$fields=array(),$doadvanced=true)
{
	global $sorder, $order_by, $viewid, $currentModule;
	global $adb,$app_strings,$mod_strings;

    $block = ( $block > 0 ) ? " AND block=$block " : "";
    $ftest = ( count($fields) ) ? " AND columnname in ('".implode("','",$fields)."')" : "";
	$sql="select * from field where $tableName $tabsrch $block $ftest order by sequence";

	$result=$adb->query($sql);
	
/*
echo "<div align='right'>";
Debug::DumpObj(array("base"=>$tableName,"module_id"=>$colidName,"tabsrch"=>$tabsrch,
    "advanced"=>$advanced,"fields"=>$fields,"sql"=>$sql,"doadvanced"=>$doadvanced));
echo "</div>";

*/
	if ( $basic && $adb->num_rows($result)==0 )
	{
        //return "";
		print_r("Big error!  No fields to put in search form");
		print_r($sql);
		print_r($focus);
		print_r(array("base"=>$tableName,"module_id"=>$colidName,"tabsrch"=>$tabsrch,"advanced"=>$advanced,"fields"=>$fields,"sql"=>$sql));
	}
		
	$column = array();
	for($i=0;$i<$adb->num_rows($result);$i++)
	{
        $column[$i]=$adb->query_result($result,$i,'columnname');
        $fieldlabel[$i]=$adb->query_result($result,$i,'fieldlabel');
        if (isset($_REQUEST[$column[$i]])) $customfieldarray[$i] = $_REQUEST[$column[$i]];
	}

    $divname = substr($tableName,0,5).$basic.$advanced;
    
    if($adb->num_rows($result) != 0)
    {
        $noofrows = $adb->num_rows($result);
        $j = 1;
        $custfld = "<div id='$divname' style='display:block;'>
        <table  class='dataLabel' align='left' width='100%' border='0' cellspacing='0' cellpadding='0'>";
		$done = 0;
        for($i=0; $i<$noofrows; $i++)
        {
			$id=$customfieldarray[$i];
			$colName=$fieldlabel[$i];
			$setName=$column[$i];
			$uitype[$i] = $adb->query_result($result,$i,'uitype');
			$insert = count($fields)==0;
			if ( count($fields) > 0 && in_array($setName,$fields) )
			{
				$insert = true;
			}

			if ( $insert )
			{
				$done++;
                if ( $j == 1 ) $custfld .= '
                    <tr>';

				if($uitype[$i] == 56)
				{
					$custfld .= '
                        <td width="20%" class="dataLabel">'.$colName.':</td>';
					if($customfieldarray[$i] == 'on')
					{
						$custfld .='
                            <td align="left" width="30%"><input class="inputField" name="'.$setName.'" type="checkbox"  checked></td>';
					}
					else
					{
						$custfld .='
                            <td align="left" width="30%"><input class="inputField" name="'.$setName.'" type="checkbox"></td>';
					}
				}
				else
				{
					$custfld .= '
                        <td width="20%" class="dataLabel">'.$colName.':</td>';
					$custfld .= '
                        <td align="left" width="30%"><input class="inputField" name="'.$setName.'" type="text" tabindex="'.$i.'" size="25" maxlength="25" value="'.$customfieldarray[$i].'"></td>';
				}
				$j = 3 - $j;
                if ( $j == 1 ) $custfld .= '
                    </tr>';
                if ( $basic && !$advanced && $done >= 2 )
					break;
			}
		}

		if ( $basic )
		{
			if ( !$advanced )
			{
				if ( $j == 1 )
					$custfld .= "<tr>";
				$custfld .= '
				  <td class="dataLabel">{APP.LBL_CURRENT_USER_FILTER}</td>
				  <td align="left"><input class="inputField" type="checkbox" name="current_user_only"></td>';
				if ( $j == 1 )
				{
					$custfld .= '<td  class="dataLabel"></td>
					<td></td>
					</tr>';
					$j = 1 ;
				}
			}
		}
		else
		{
            if ( $j == 1 )
                $custfld .= "<tr>";
            $custfld .= '
              <td class="dataLabel">{APP.LBL_LIST_ASSIGNED_USER}</td>
              <td align="left"><select size="3" tabindex="1" name="assigned_user_id[]" multiple="multiple">'.get_select_options_with_id(get_user_array(FALSE), '').'</select></td>';
            if ( $j == 1 )
            {
                $custfld .= '<td  class="dataLabel"></td>
				<td></td>
                </tr>';
                $j = 1 ;
            }
		}
        if ( $j == 2 )
        {
            $custfld .= "
                <td class='dataLabel'></td>
                <td class='dataLabel'></td>
            </tr>";
        }
        $custfld .= "
        </table></div>";

        $res = '<form>
        <table align="left" width="100%" border="0" cellspacing="0" cellpadding="2" class="formOuterBorder">
            <tr>
                <td width="70%" align="left" class="formSecHeader" colspan="3"  style="cursor:pointer;" unselectable="on" onclick="javascript:expandCont(\''.$divname.'\');">
                    <img src="themes/images/toggle2.gif" id="img_'.$divname.'" width="15" height="15"></img>
                    '.$label.'
                </td>
				<td align="right" class="formSecHeader" >
				  <input type="hidden" name="action" value="index">
				  <input type="hidden" name="query" value="true">
				  <input type="hidden" name="module" value="'.$currentModule.'">
				  <input type="hidden" name="order_by" value="'.$order_by.'">
				  <input type="hidden" name="sorder" value="'.$sorder.'">
				  <input type="hidden" name="viewname" value="'.$viewid.'">
				  <table align="right" width="100%"><tr>';
        if ( $basic )
        {
            $res .= '
                    <td><input type="submit" value="{APP.LBL_SEARCH_BUTTON_LABEL}"
                      title="{APP.LBL_SEARCH_BUTTON_TITLE}"
                      accesskey="{APP.LBL_SEARCH_BUTTON_KEY}" class="button"
                      name="button"></td>
                    <td><input type="button" title="{APP.LBL_CLEAR_BUTTON_TITLE}"
                      accesskey="{APP.LBL_CLEAR_BUTTON_KEY}"
                      onclick="clear_form(this.form);" class="button" name="clear"
                      value=" {APP.LBL_CLEAR_BUTTON_LABEL} "></td>';
            if ( $doadvanced )
                if ( $advanced )
                    $res .= '
                	  	<td>
                            <input type="hidden" name="advanced" value="true">
                            <font size="1">
                    		  	[ <a href="{BASIC_LINK}">{APP.LNK_BASIC_SEARCH}</a> ]
                    		</font>
                        </td>';
                else
                    $res .= '
                		<td>
                            <font size="1">
                    			[ <a href="{ADVANCE_LINK}">{APP.LNK_ADVANCED_SEARCH}</a> ]
                    		</font>
                        </td>';
        }
        else
            $res .= "<td class='dataLabel'>";
        $res .= '</tr></table></td></tr>';
        $res .= "<tr><td colspan=4>$custfld</td></tr>";
  		$res .= '
            </table>
        </form>';
/*
echo "<div align='right'>";
Debug::DumpObj($res);
echo "</div>";

*/
        return $res;
    }
}
?>
