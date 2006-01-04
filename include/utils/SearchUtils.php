<?php


require_once('include/database/PearDatabase.php');
require_once('include/ComboUtil.php'); //new
require_once('include/utils/CommonUtils.php'); //new
//require_once('include/utils/UserInfoUtil.php'); //new
	
function getSearchListHeaderValues($focus, $module,$sort_qry='',$sorder='',$order_by='',$relatedlist='',$oCv='') //Function to get the header values in the combo box of search - By Jaguar
{
		echo "inn seaaaaaaa";
        global $adb;
        global $theme;
        global $app_strings;
        global $mod_strings;
        //Seggregating between module and smodule
        if(isset($_REQUEST['smodule']) && $_REQUEST['smodule'] == 'VENDOR')
        {
		$smodule = 'Vendor';
        }
        elseif(isset($_REQUEST['smodule']) && $_REQUEST['smodule'] == 'PRICEBOOK')
        {
                $smodule = 'PriceBook';
        }
        else
        {
                $smodule = $module;
        }

        $arrow='';
        $qry = getURLstring($focus);
        $theme_path="themes/".$theme."/";
        $image_path=$theme_path."images/";
        $search_header = Array();

        //Get the tabid of the module
        //require_once('include/utils/UserInfoUtil.php')
        $tabid = getTabid($smodule);
        global $profile_id;
        if($profile_id == '')
        {
                global $current_user;
                $profile_id = fetchUserProfileId($current_user->id);
        }
        //added for customview 27/5
        if($oCv)
        {
                if(isset($oCv->list_fields))
                {
			$focus->list_fields = $oCv->list_fields;
                }
        }

        //modified for customview 27/5 - $app_strings change to $mod_strings
        foreach($focus->list_fields as $name=>$tableinfo)
        {
                //$fieldname = $focus->list_fields_name[$name];  //commented for customview 27/5
                //added for customview 27/5
                if($oCv)
                {
                        if(isset($oCv->list_fields_name))
                        {
                                $fieldname = $oCv->list_fields_name[$name];
                        }
			else
                        {
                        	$fieldname = $focus->list_fields_name[$name];
                        }
                }
                else
                {
                        $fieldname = $focus->list_fields_name[$name];
                }

		global $current_user;
                require('user_privileges/user_privileges_'.$current_user->id.'.php');
	 	if($is_admin == false)
                {

                	$profileList = getCurrentUserProfileList();
	                $query = "select profile2field.* from field inner join profile2field on profile2field.fieldid=field.fieldid inner join def_org_field on def_org_field.fieldid=field.fieldid where field.tabid=".$tabid." and profile2field.visible=0 and def_org_field.visible=0  and profile2field.profileid in ".$profileList." and field.fieldname='".$fieldname."' group by field.fieldid";
		
                	$result = $adb->query($query);
		}


		if($profileGlobalPermission[1] == 0 || $profileGlobalPermission[2] ==0 || $adb->num_rows($result) == 1)
                {
			if(isset($focus->sortby_fields) && $focus->sortby_fields !='')
                        {
                                //Added on 14-12-2005 to avoid if and else check for every list field for arrow image and change order
                        	$change_sorder = array('ASC'=>'DESC','DESC'=>'ASC');
                                $arrow_gif = array('ASC'=>'arrow_down.gif','DESC'=>'arrow_up.gif');

                                foreach($focus->list_fields[$name] as $tab=>$col)
                                {
                                        if(in_array($col,$focus->sortby_fields))
                                        {
                                                if($order_by == $col)
                                                {
                                                        $temp_sorder = $change_sorder[$sorder];
                                                        $arrow = "<img src ='".$image_path.$arrow_gif[$sorder]."' border='0'>";
                                                }
                                                else
                                                {
                                                        $temp_sorder = 'ASC';
                                                }
                                                if($relatedlist !='')
                                                {
                                                        if($app_strings[$name])
                                                        {
                                                                $name = $app_strings[$name];
                                                        }
                                                        else
                                                        {
                                                                $name = $mod_strings[$name];
                                                        }
                                                }
                                                else
                                                {
                                                        if($app_strings[$name])
                                                        {
                                                                $lbl_name = $app_strings[$name];
                                                        }
                                                        else
                                                        {
								 $lbl_name = $mod_strings[$name];
                                                        }
                                                        //added to display currency symbol in listview header
                                                        if($lbl_name =='Amount')
                                                        {
                                                                $curr_symbol = getCurrencySymbol();
                                                               // $lbl_name .=': (in '.$curr_symbol.')';
                                                        }
                                                        $name = $lbl_name;
                                                        $arrow = '';
                                                }
                                        }
                                        else
                                        {       if($app_strings[$name])
                                                {
                                                        $name = $app_strings[$name];
                                                }
                                                elseif($mod_strings[$name])
                                                {
                                                        $name = $mod_strings[$name];
                                                }
                                        }

                                }
                        }
                        //added to display currency symbol in related listview header
/* -- commented out by-Jaguar
                        if($name =='Amount' && $relatedlist !='' )
                        {
                                $curr_symbol = getCurrencySymbol();
                                $name .=': (in '.$curr_symbol.')';
                        }

*/
                        //Added condition to hide the close column in Related Lists
                        if($name == 'Close' && $relatedlist != '')
                        {
                                //$search_header .= '';
                                // $search_header[] = '';
                        }
                        else
			 {
                                $col_name=$focus->list_fields_name[$name];
                                $search_header[$col_name]=$name;
                        }
                }
        }
	print_r($search_header);
        return $search_header;
}


								

?>

