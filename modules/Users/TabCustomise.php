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
require_once('data/Tracker.php');
require_once('modules/Users/User.php');
require_once('include/utils/utils.php');
require_once('include/database/PearDatabase.php');

global $current_user;
global $theme;
global $default_language;

global $app_strings;
global $mod_strings;

$focus = new User();

if(isset($_REQUEST['record'])) {
	$focus->retrieve($_REQUEST['record']);
}
if(isset($_REQUEST['isDuplicate']) && $_REQUEST['isDuplicate'] == 'true') {
	$focus->id = "";
} 

global $theme;
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
require_once($theme_path.'layout_utils.php');

$log->info("User detail view");

$xtpl=new XTemplate ('modules/Users/TabCustomise.html');
$xtpl->assign("MOD", $mod_strings);
$xtpl->assign("APP", $app_strings);

$xtpl->assign("THEME", $theme);
$xtpl->assign("IMAGE_PATH", $image_path);$xtpl->assign("PRINT_URL", "phprint.php?jt=".session_id());
$xtpl->assign("ID", $focus->id);
$xtpl->assign("USER_NAME", $focus->user_name);
$xtpl->assign("FIRST_NAME", $focus->first_name);
$xtpl->assign("LAST_NAME", $focus->last_name);
$xtpl->assign("STATUS", $focus->status);
$xtpl->assign("YAHOO_ID", $focus->yahoo_id);
if (isset($focus->yahoo_id) && $focus->yahoo_id !== "") $xtpl->assign("YAHOO_MESSENGER", "<a href='http://edit.yahoo.com/config/send_webmesg?.target=".$focus->yahoo_id."'><img border=0 src='http://opi.yahoo.com/online?u=".$focus->yahoo_id."'&m=g&t=2'></a>");
$xtpl->parse("main");
$xtpl->out("main");

if ((is_admin($current_user) || $_REQUEST['record'] == $current_user->id) && $focus->is_admin == 'on') {
       $xtpl->assign("IS_ADMIN", "checked");
	$xtpl->parse("user_settings");
	$xtpl->out("user_settings");
}


?>
<script type="text/javascript" language="JavaScript" src="include/js/general.js"></script>
<style>
	.field {
    font-family: Verdana, Arial, Helvetica, San-serif;
    font-size: 11px;
    height: 20px;
    padding: 0 15 0 15;
    border-top: 1px solid #DDD;
    border-left: 1px solid #DDD;
    border-bottom: 1px solid #666;
    border-right: 1px solid #666;
}

.dummy {
    background: #FFF;
    width: 100%;
}

.handle {
    height: 2px;
    border: none;
}

.required {
    font-weight: bold;
    //background:  url(/crm/images/required.gif) no-repeat left;
}

.readonly {
    //background:  url(/crm/images/readonly.gif) no-repeat left;
}

.always {
    font-weight: bold;
}

.selected {
    background-color: #9C9;
    color: #000;
    cursor: move;
}

.used {
    background-color: #F0F0F0;
    color: #AAA;
    border: 0px;
}

.unused {
    background-color: #D4D0C8;
    color: #000;
    cursor: hand;
    cursor: pointer;   
}

.mouseover {
    background-color: #000;
}

.added {
    background-color: #D4D0C8;
}

.moved {
    border: 0px;
}
.moveLayer {
   position: absolute;
   left: -1000px;
   top: -1000px;
   z-index: 10;
   height: 17;
   width: 100;
   padding: 3;
   background-color: #9C9;
   font-family: Verdana, Arial, Helvetica, San-serif;
   font-size: 11px;
   cursor: move;
}

.section {
   font-family: Verdana, Arial, Helvetica, San-serif;
   font-size: 11px;
   font-weight: normal;
} 

</style>
</head>
<?
		$tabavail="SELECT tabid,name from tab where presence !=2";
		$tabrow=$adb->query($tabavail);
		if($adb->num_rows($tabrow) != 0)
		{
			while ($result = $adb->fetch_array($tabrow))
			{
				$availabletab[]=$result;
			}
		}	
		$tabsel="SELECT tabid,name from tab where presence=0 order by tabsequence";
		$tabrow=$adb->query($tabsel);
		if($adb->num_rows($tabrow) != 0)
		{
			while ($result = $adb->fetch_array($tabrow))
			{
				$selectedtab[]=$result;
			}
		}


?>
<body onmousedown="doItemSelect(event)" onmousemove="doItemMove(event)" onmouseup="endItemMove(event)">
<form method="post" name="CustomizeTabForm" >

  <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td class="formHeader"><?php echo $mod_strings['LBL_CUSTOMISE_TABS']; ?> </td>
    </tr>
  </table>
  <br>
  <table width="85%" border=0 cellPadding=0 cellSpacing=0>
    <tr>
      <td class="formSecHeader">1.<?php echo $mod_strings['LBL_CHOOSE_TABS']; ?>:</td>
    </tr>
    <tr>
      <td><table width="100%" border=0 cellpadding=2 cellspacing=0 class="secContent">
          <tr>
            <td width="70" valign="bottom"><div align="right"><img src="include/images/dragdrop.gif">
              </div></td>
            <td> <br><table width="98%" border="0" cellpadding="0" cellspacing="0">
                <tr>
                  <td class="formSecHeader"><?php echo $mod_strings['LBL_AVAILABLE_TABS']; ?></td>
                </tr>
                <tr>
                  <td> <table id="fieldlist" width="100%" border="0" cellpadding="2" cellspacing="2" onMouseOver="itemMouseOver(this.id)" onMouseOut="itemMouseOut(this.id)">
<?
                     $rowcount = 1;
                     $colcount = 1;
                     $fieldcount = 0;
                     $id = "null";
                     $tabID = "null";
                     $classType = "null";
                     $tabName = "null";
		     for ($k=0;$k<count($selectedtab);$k++)
		     {
			$selmenu[]=$selectedtab[$k]['name'];
		     }			
		     for ($i=0;$i<count($availabletab);$i++)
                     {
                        $id = "fdr"."$rowcount"."c"."$colcount";
                        $tabID = $availabletab[$i]['tabid'];
                        $tabName = $availabletab[$i]['name'];
			if(in_array($tabName,$selmenu))
                                $classType = "field used";
                        else
                                $classType = "field unused";

                        //System.out.println(" colname = "+tabID);
                        $fieldcount ++;
                        if($fieldcount == 1 || $fieldcount % 3 == 1)
                        {
?>                                <tr>
                                        <td id="<?php echo $id ?>" colname="<?php echo $tabID ?>" class="<?php echo $classType ?>"><?php echo $tabName ?></td>
                <?
                                $colcount++;
			}
                        else if ($fieldcount % 3 == 0)
                        {
                ?>
                  		       <td id="<?php echo $id ?>" colname="<?php echo $tabID ?>" class="<?php echo $classType ?>"><?php echo $tabName ?></td
>
                                </tr>
                <?php
                                $colcount = 1;
                                $rowcount++;
                        }
                        else
                        {
                ?>

                                        <td id="<?php echo $id ?>" colname="<?php echo $tabID ?>" class="<?php echo $classType ?>"><?php echo $tabName ?></td>
                <?php              $colcount++;
                        }
                     }
		    if ($fieldcount == 1 || $fieldcount % 3 == 1)
                     {
                ?>
                        </tr>
                <?php
                     }
                ?>
		</table><br>
                    </td>
                </tr>
                <tr>
                  <td class="formSecHeader"><?php echo $mod_strings['LBL_SELECTED_TABS']; ?></td>
                </tr>
                <tr>
                  <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
                      <tr>
                        <td height="30"> <table id="columnlayout" cols="2" border="0" cellspacing="0" cellpadding="1">
                            <tr>
                                <?php
                                        $keycolumncount = 0;
                                        $hnid = 'null';
                                        $clid = 'null';
                                        $selectedTabID = 'null';
                                        $unchangable = 'null';

                                        for($j=0;$j<count($selectedtab);$j++)
                                        {
                                                $keycolumncount++;
                                                $hnid = "hn".$keycolumncount;
                                                $clid = "cl".$keycolumncount;
                                                $selectedTabID = $selectedtab[$j]['tabid'];
                                                $tabName = $selectedtab[$j]['name'];
						if ( ($j % 7) == 0 ) echo "</tr><tr>";
                                                if ($keycolumncount == 1)
                                                        $unchangable = $tabName;
                                ?>
                              <td id="<?php echo $hnid ?>" width="1" class="handle"><img src="spacer.gif" width="1" height="1"></td>
				<td id="<?php echo $clid ?>" colname="<?php echo $selectedTabID ?>" class="field added" nowrap onMouseOver="itemMouseOver(this.id)" onMouseOut="itemMouseOut(this.id)"><?php echo $tabName ?></td>

                                <?
                                        }
                                        $hnid = "hn".($keycolumncount+1);
                                        $dmid = "dm".($keycolumncount+1);
                                        //System.out.println("dmid = "+dmid);
                                ?>

                              <td id="<?php echo $hnid ?>" width="1" class="handle"><img src="spacer.gif" width="1" height="1"></td>
                              <td id="<?php echo $dmid ?>" class="field dummy" onMouseOver="itemMouseOver(this.id)" onMouseOut="itemMouseOut(this.id)"></td>
                            </tr>
                          </table> </td>
                      </tr>
                    </table><br></td>
                </tr>
              </table></td>
          </tr>
        </table></td>
    </tr>
  </table>
  <div id="item_movelayer" class="moveLayer"></div>
  <p></p>
  <table width="80%" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td><div align="center">
          <input type="button" class="button" value="Save" onClick="formSubmit('<?php echo $focus->id ?>')">
                  <input type="button" class="button" value="Cancel" onClick="window.history.back()">
        </div></td>
    </tr>
  </table>

</form>
</body>
</html>
<script type="text/javascript" language="JavaScript">

var currSelItemObj="";
var itemOnMove=false;
var colLayoutObj=getObj("columnlayout")
var moveLayerObj=getObj('item_movelayer')

function itemMouseOver(id) {
        var itemObj=getObj(id)
        if (itemOnMove!=true) {
                if (itemObj.className=="field unused" || id.indexOf("cl")>=0) {
                        if (browser_ie) itemObj.style.cursor='hand'
                        else if (browser_nn4 || browser_nn6) itemObj.style.cursor='pointer'
                } else if (id.indexOf("fieldlist")>=0) itemObj.style.cursor='default'
        } else {
                if (id.indexOf("cl")>=0 || id.indexOf("dm")>=0) {//if Field
                        itemObj.style.cursor='move'
                        var handleObj=getObj('hn'+id.substring(2,id.length))
                        handleObj.className="handle mouseover"
                } else if (id.indexOf("fieldlist")>=0) {//if Field List
                        if (currSelItemObj.id.indexOf("fd")<0) {
                                itemObj.style.cursor='move'
                                itemObj.className='tableBorder mouseover'       //Hiliting Field List only when added fields are removed
                        }
                }
        }
}

function itemMouseOut(id) {
        var itemObj=getObj(id)
        if (itemOnMove==true) {
                if (id.indexOf("cl")>=0 || id.indexOf("dm")>=0) {//if Field or Dummy Field
                        var handleObj=getObj('hn'+id.substring(2,id.length))
                        handleObj.className="handle"
                } else if (id.indexOf("fieldlist")>=0) {//if Field List
                        itemObj.className='tableBorder'
                }
        }
}

function doItemSelect(ev) {
        var itemObj;
        if (browser_ie) itemObj=window.event.srcElement
        else if (browser_nn4 || browser_nn6) itemObj=ev.target

        if (itemObj && itemObj.className=="field used") //for Used Fields in Field List, no operation should be allowed
                return false

        //reseting the previously selected Item to default one
        if (currSelItemObj) {
                if (currSelItemObj.id.indexOf("cl")>=0)
                        currSelItemObj.className="field added"
                else if (currSelItemObj.id.indexOf("fd")>=0 && currSelItemObj.className=="field selected")
                        currSelItemObj.className="field unused"
        }

        //selecting the currently clicked Field
        if (itemObj.id.indexOf("fd")>=0 || itemObj.id.indexOf("cl")>=0) {
                itemObj.className='field selected'
                currSelItemObj=itemObj

                moveLayerObj.style.left=findPosX(itemObj)
                moveLayerObj.style.top=findPosY(itemObj)
                moveLayerObj.innerHTML=itemObj.innerHTML
        }
}
function doItemMove(ev)
{
        var posLeft=moveLayerObj.style.left
        posLeft=posLeft.substr(0,moveLayerObj.style.left.indexOf('p'))

        if (posLeft>0) {
                clearTextSelection();
                if (browser_ie) {
                        moveLayerObj.style.left=window.event.clientX+document.body.scrollLeft;
                        moveLayerObj.style.top=window.event.clientY+document.body.scrollTop;
                } else if (browser_nn4 || browser_nn6) {
                        moveLayerObj.style.left=ev.pageX+10 //+window.scrollX
                        moveLayerObj.style.top=ev.pageY+10 //+window.scrollY
                }
                itemOnMove=true
        }
}

function endItemMove(ev)
{
        moveLayerObj.style.left=-1000+'px'
        moveLayerObj.style.top=-1000+'px'
        if (itemOnMove==true) {
                if (browser_ie)
                        var srcElement=window.event.srcElement
                else if (browser_nn4 || browser_nn6)
                        var srcElement=ev.target

                var re1=new RegExp("(cl|dm)[0-9]+") //expr. for "cl1" and "dm1"
                var re2=new RegExp("fdr[0-9]+c[0-9]+") //expr. for "fdr1c1"
                if (re1.test(srcElement.id)) {//Adding or Repositioning a Field
                        var handleObj=getObj('hn'+srcElement.id.substring(2,srcElement.id.length))
                        var colId=parseInt(srcElement.id.substring(2,srcElement.id.length))

                        if (currSelItemObj.id.indexOf("fd")>=0) {//adding a Field
                                addField(colId)
                                resetId()
                                currSelItemObj.className="field used"
                        } else {//Repositioning a Field
                                var prevColId=parseInt(currSelItemObj.id.substring(currSelItemObj.id.indexOf("l")+1,currSelItemObj.id.length))

                                if (prevColId<colId) {
                                        for (i=prevColId;i<colId;i++) {
                                                var nxtFldObj=getObj("cl"+(i+1))?getObj("cl"+(i+1)):getObj("cl"+i)
                                                swapFields(getObj("cl"+i),nxtFldObj)
                                        }
                                } else if (prevColId>colId) {
                                        for (i=prevColId;i>colId;i--)
                                                swapFields(getObj("cl"+i),getObj("cl"+(i-1)))
                                }
                                currSelItemObj.className="field added"
                        }
                        handleObj.className="handle"
                        itemOnMove=false
                        return true;
                } else if (re2.test(srcElement.id) || srcElement.id=="fieldlist") {//Removing a Field
                        if (srcElement.id.indexOf("fd")>=0 || srcElement.id=="fieldlist") {
                                if (currSelItemObj.id.indexOf("cl")>=0) {//Only Fields present in Sections can be removed
                                   if (currSelItemObj.innerHTML=="<?php echo $unchangable?>") {
                                        alert("\<?php echo $unchangable?> Tab cannot be removed")
                                        currSelItemObj.className="field added"
                                   } else {
                                        if (srcElement.id.indexOf("fd")>=0)     var colList=srcElement.parentNode.parentNode.getElementsByTagName("TD")
                                        else var colList=srcElement.getElementsByTagName("TD")

                                        var col=colList[0];
                                        for (var i=0; i<colList.length; i++,col=colList[i]) {
                                                if (col.innerHTML==currSelItemObj.innerHTML) {
                                                        col.className="field unused"
                                                        break;
                                                }
                                       }

                                        var colId=parseInt(currSelItemObj.id.substring(2,srcElement.id.length))
                                        removeField(colId)
                                        resetId()
                                        getObj("fieldlist").className="tableBorder"
                                }
                                } else {
                                        currSelItemObj.className="field unused"
                                }
                                itemOnMove=false
                                return true;
                        }
                } else {
                        if (currSelItemObj.id.indexOf("cl")>=0) currSelItemObj.className="field added"
                        else currSelItemObj.className="field unused"

                        itemOnMove=false
                }
        }
}

//Removing a Field
function removeField(colId) {
        var removeFieldObj=getObj("cl"+colId)
        var removeHandleObj=getObj("hn"+colId)

        var rowObj=removeFieldObj.parentNode
        rowObj.removeChild(removeFieldObj)
        rowObj.removeChild(removeHandleObj)
}

//Adding a Field
function addField(colId) {
        var totCols;
        var newHandleObj=document.createElement("TD")
        var newHandleImgObj=document.createElement("IMG")
        var newFieldObj=document.createElement("TD")

        var prevFieldObj=getObj("cl"+colId)?getObj("cl"+colId):getObj("dm"+colId)

        if (browser_ie) {
                newHandleImgObj.src="spacer.gif"
                newHandleImgObj.height=1
                newHandleImgObj.width=1

                newHandleObj.id="hn"+(colId+1)
                newHandleObj.className="handle"
                newHandleObj.width=1
        } else if (browser_nn4 || browser_nn6) {
                newHandleImgObj.setAttribute("src","spacer.gif")
                newHandleImgObj.setAttribute("height",1)
                newHandleImgObj.setAttribute("width",1)

                newHandleObj.setAttribute("id","hn"+(colId+1))
                newHandleObj.setAttribute("class","handle")
                newHandleObj.setAttribute("width",1)
        }

        newHandleObj.appendChild(newHandleImgObj)
        newFieldObj.appendChild(document.createTextNode(moveLayerObj.innerHTML))

        if (browser_ie) {
                newFieldObj.id="cl"+(colId+1)
                newFieldObj.colname=currSelItemObj.colname
                newFieldObj.className="field added"
                newFieldObj.noWrap=true
        } else if (browser_nn4 || browser_nn6) {
                newFieldObj.setAttribute("id",(colId+1))
                newFieldObj.setAttribute("colname",currSelItemObj.getAttribute("colname"))
                newFieldObj.setAttribute("class","field added")
                newFieldObj.setAttribute("noWrap",true)
        }


        newFieldObj.onmouseover=function() {
                itemMouseOver(this.id);
        };

        newFieldObj.onmouseout=function() {
                itemMouseOut(this.id);
        };

        prevFieldObj.parentNode.insertBefore(newHandleObj,prevFieldObj)
        prevFieldObj.parentNode.insertBefore(newFieldObj,newHandleObj)
}

//Swapping contents of Fields
function swapFields(swaperObj,swapeeObj,swapClass) {

        var tempInnerHTML=swaperObj.innerHTML
        swaperObj.innerHTML=swapeeObj.innerHTML
        swapeeObj.innerHTML=tempInnerHTML

        if (browser_ie) {
                var colname=swaperObj.colname
                swaperObj.colname=swapeeObj.colname
                swapeeObj.colname=colname
        } else if (browser_nn4 || browser_nn6) {
                var colname=swaperObj.getAttribute("colname")
                swaperObj.setAttribute("colname",swapeeObj.getAttribute("colname"))
                swapeeObj.setAttribute("colname",colname)
        }

}

//resetting ids of Handle and Field objects
function resetId() {
        var i,j,k;
        var colList=colLayoutObj.getElementsByTagName("TD");
    var col=colList[0];

    for (i=0,k=1; i<colList.length; i++,col=colList[i]) {
                prefixStr=(i==0)?"hn":(i==colList.length-1)?"dm":(i%2!=0)?"cl":"hn"
                if (browser_ie)
                        col.id=prefixStr+k
                else if (browser_nn4 || browser_nn6)
                        col.setAttribute("id",prefixStr+k)
                if (i!=0) if (i%2!=0) k++;
        }
}

//creating Hidden Fields for selected Column
function createColList() {
        var colList=colLayoutObj.getElementsByTagName("TD");
    var col=colList[1];
        var formObj=getObj("CustomizeTabForm")

        for (var i=1,k=1; i<colList.length-1; i+=2,col=colList[i]) {
                var newHiddenObj=document.createElement("INPUT")
                if (browser_ie) 
		{
                        newHiddenObj.type="hidden"
                        newHiddenObj.name="col"+k
                        newHiddenObj.value=col.colname;
                } else if (browser_nn4 || browser_nn6) {
                        newHiddenObj.setAttribute("type","hidden")
                        newHiddenObj.setAttribute("name","col"+k)
                        newHiddenObj.setAttribute("value",col.getAttribute("colname"))
                }
                formObj.appendChild(newHiddenObj)
                k++
        }
}

function formSubmit(rec)
{
  createColList();
  
  document.CustomizeTabForm.action="index.php?module=Users&action=UpdateTab&record="+rec;
  document.CustomizeTabForm.submit();
}
</script>

