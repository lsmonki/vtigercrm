<script language="javascript" type="text/javascript" src="include/scriptaculous/prototype.js"></script>
<script language="javascript" type="text/javascript" src="include/scriptaculous/scriptaculous.js"></script>
<script language="javascript" type="text/javascript" src="include/scriptaculous/effects.js"></script>
<script language="javascript" type="text/javascript" src="include/scriptaculous/builder.js"></script>
<script language="javascript" type="text/javascript" src="include/scriptaculous/dragdrop.js"></script>
<script language="javascript" type="text/javascript" src="include/scriptaculous/controls.js"></script>
<script language="javascript" type="text/javascript" src="include/scriptaculous/slider.js"></script>
<script language="javascript" type="text/javascript" src="include/scriptaculous/dom-drag.js"></script>
<script type="text/javascript" language="JavaScript" src="include/js/general.js"></script>


{*<!--Home Page Entries  -->*}
{if isset($LOGINHISTORY.0)}
    <div id="loginhistory" style="float:left;position:absolute;left:300px;top:150px;height:100px:width:200px;overflow:auto;border:1px solid #dadada;">
    <table border="0" cellpadding="4" cellspacing="0" width="100%">
        <tr><td class=tblPro1ColHeader>ID</td><td class=tblPro1ColHeader>Type</td><td class=tblPro1ColHeader>Modified By</t
d><td class=tblPro1ColHeader nowrap><img src="{$IMAGE_PATH}tblPro1BtnHide.gif" alt="Close" align="right" border="0" onClick
="document.getElementById('loginhistory').style.display='none';">Modified Time</td></tr>
        {foreach key=label item=detail from=$LOGINHISTORY}
            <tr><td class=tblPro1DataCell>{$detail.crmid}</td><td class=tblPro1DataCell>{$detail.setype}</td><td class=tblP
ro1DataCell>{$detail.modifiedby}</td><td class=tblPro1DataCell>{$detail.modifiedtime}</td></tr>
        {/foreach}
    </table>
    </div>
{/if}

<TABLE border=0 cellspacing=0 cellpadding=0 width=100% class=small>
 	<tr><td style="height:2px"></td></tr>
 	<tr>
     <td style="padding-left:10px;padding-right:10px" class="moduleName" nowrap>My Home </td>
     <td class="sep1" style="width:1px"></td>
     <td class=small >
     	<table border=0 cellspacing=0 cellpadding=0>
  		<tr>
        <td>
    		<table border=0 cellspacing=0 cellpadding=5>
			<tr>
			<td style="padding-right:0px">&nbsp;</td>
			</tr>
			</table>
		</td>
		<td nowrap width=50>&nbsp;</td>
		<td>
			<table border=0 cellspacing=0 cellpadding=5>
			<tr>
			<td style="padding-right:0px"><a href="#"><img src="{$IMAGE_PATH}btnL3Calendar.gif" alt="Open Calendar..." title="Open Calendar..." border=0></a></a></td>
		
            <td style="padding-right:0px"><a href="#"><img src="{$IMAGE_PATH}btnL3Clock.gif" alt="Show World Clock..." title="Show World Clock..." border=0 onClick="fnvshobj(this,'wclock')"></a></a></td>
            <td style="padding-right:0px"><a href="#"><img src="{$IMAGE_PATH}btnL3Calc.gif" alt="Open Calculator..." title="Open Calculator..." border=0 onClick="fnvshobj(this,'calc')"></a></a></td>
			<td nowrap="nowrap" width="50">&nbsp;</td>
			<td style="padding-right: 0px;"><a href="#" onClick="fnvshobj(this,'allMenu')"><img src="{$IMAGE_PATH}btnL3AllMenu.gif" alt="Open All Menu..." title="Open All Menu..." border="0"></a></td>
		
			</tr>
			</table>
		</td>
		<td>
			<table border=0 cellspacing=0 cellpadding=5>
			<tr>
			</tr>
			</table>
		</td>
		</tr>
		</table>
	</td>
	<td nowrap style="width:50%;padding:10px">&nbsp;</td>
   </tr>
   <tr><td style="height:2px"></td></tr>

</TABLE>

{* Main Contents Start Here *}
<table width="98%" cellpadding="0" cellspacing="0" border="0" class="small showPanelBg" align="center">
			<tr>
					<td width="75%" align="center" style="border-right:1px solid #666666;" >

	<div id="MainMatrix">
				{foreach item=tabledetail from=$HOMEDETAILS}
				{if $tabledetail neq ''}
				
					<div class="MatrixLayer" style="float:left;" id="{$tabledetail.Title.2}">
	<table width="100%" border="0" cellpadding="5" cellspacing="0" class="small">
  <tr style="cursor:move;">
		<td align="left" style="border-bottom:1px solid #666666;"><b>{$tabledetail.Title.1}</b></td>
		<td align="right" style="border-bottom:1px solid #666666;"><img src="{$IMAGE_PATH}uparrow.gif" align="absmiddle" /></td>
           </tr>
	{foreach item=elements from=$tabledetail.Entries}
	    <tr >
		{if $tabledetail.Title.2 neq 'home_mytopinv' && $tabledetail.Title.2 neq 'home_mytopso' && $tabledetail.Title.2 neq 'home_mytopquote'}
		<td colspan="2"><img src="{$IMAGE_PATH}bookMark.gif" align="absmiddle" /> {$elements.0}</td>
		{else}
		<td colspan="2"><img src="{$IMAGE_PATH}bookMark.gif" align="absmiddle" /> {$elements.1}</td>
		{/if}
		
           </tr>
{/foreach}
	</table>
				
			</div>
			{/if}	
{/foreach}
<div class="MatrixLayer" style="float:left;" id="SubMatrix_9">

	  <table width="100%" border="0" cellpadding="5" cellspacing="0" class="small">
          <tr style="cursor:move;">
          <td align="left" style="border-bottom:1px solid #666666;"><b>Leads By Source </b></td>
          <td align="right" style="border-bottom:1px solid #666666;"><img src="{$IMAGE_PATH}uparrow.gif" align="absmiddle" /></td>
          </tr>
          <tr>
          <td colspan="2" align="center"><img src="cache/images/pie_2082672713_leadsource_96954858.png" width="280" height="170" align="absmiddle" /></td>
          </tr>
	<tr><td colspan="2" style="border-top:1px solid #666666;padding-right:30px;" align="right" >
	Total : <b>340</b>
	</td></tr>                                
        </table>
</div>
<div class="MatrixLayer" style="float:left;" id="SubMatrix_9">

	  <table width="100%" border="0" cellpadding="5" cellspacing="0" class="small">
          <tr style="cursor:move;">
          <td align="left" style="border-bottom:1px solid #666666;"><b>Leads By Status </b></td>
          <td align="right" style="border-bottom:1px solid #666666;"><img src="{$IMAGE_PATH}uparrow.gif" align="absmiddle" /></td>
          </tr>
          <tr>
          <td colspan="2" align="center"><img src="cache/images/hor_2082672713_leadstatus_96954858.png" width="240" height="170" align="absmiddle" /></td>
          </tr>
	<tr><td colspan="2" style="border-top:1px solid #666666;padding-right:30px;" align="right" >
	Total : <b>340</b>
	</td></tr>                                
        </table>
</div>
</div>
</td>

<td width="25%" valign="top" style="padding:5px;">
	<table width="100%" border="0" cellpadding="0" cellspacing="0">
	<tr>
	<td width="13" height="38"><img src="{$IMAGE_PATH}upcoming_left.gif" align="top"  /></td>
	<td width="100%" background="{$IMAGE_PATH}upcomingEvents.gif" style="background-repeat:repeat-x; ">&nbsp;</td>
	<td width="14" height="38" align="left"><img src="{$IMAGE_PATH}upcoming_right.gif" align="top"  /></td>
	</tr>		
	<tr>
	<td colspan="3" bgcolor="#FFFFCF" style="border-left:2px solid #A6A4A5;border-right:2px solid #A6A4A5;border-bottom:2px solid #A6A4A5;">
	<table width="100%" border="0" cellpadding="5" cellspacing="0">
	<tr>
	<td width="75%" colspan="2"><b class="fontBold">Upcoming Events</b><br />23 Events For Today</td>
	<td width="25%" valign="top" align="right"><img src="{$IMAGE_PATH}up.gif" align="absmiddle" /></td>
	</tr>
	<tr><td colspan="3" height="10"></td></tr>															
	{foreach item=entries from=$ACTIVITIES.0.Entries}
	<tr>
	<td align="right" width="15%">{$entries.IMAGE}</td>
	<td align="left" valign="middle" colspan="2" width="85%"><b class="style_Gray">{$entries.0}</b><br />1 800 800 8000</td>
	</tr>
	{/foreach}
	<tr><td colspan="3" height="10"></td></tr>
	</table>
	</td>
	</tr>
	</table><br />
	<table width="100%" border="0" cellpadding="0" cellspacing="0" class="small">
	<tr>
	<td width="14" height="70" background="{$IMAGE_PATH}pending_left.gif" ></td>
	<td width="90%" background="{$IMAGE_PATH}pendingEvents.gif" valign="bottom" style="background-repeat:repeat-x;">
	<b class="fontBold">Pending Events</b><br />
	7 Events in Past 10 days</td>
	<td width="15" height="70" background="{$IMAGE_PATH}pending_right.gif" valign="bottom">
	<img src="{$IMAGE_PATH}up.gif" align="top" />&nbsp;</td>
	</tr>		
	<tr>
	<td colspan="3" bgcolor="#FEF7C1" style="border-left:2px solid #A6A4A5;border-right:2px solid #A6A4A5;border-bottom:2px solid #A6A4A5;">
	<table width="100%" border="0" cellpadding="5" cellspacing="0">
	<tr>
	<td colspan="3" height="10"></td>
	</tr>
	{foreach item=entries from=$ACTIVITIES.1.Entries}
	<tr>
	<td align="right" width="15%">{$entries.IMAGE}</td>
	<td align="left" valign="middle" colspan="2" width="85%"><b class="style_Gray">{$entries.0}</b><br />1 800 800 8000</td>
	</tr>
	{/foreach}
	<tr>
	<td colspan="3" height="10"></td>
	</tr>
	</table></td>
	</tr>
	</table>

</td>

</tr>
</table>

{literal}
<script  language="javascript">
		Sortable.create("MainMatrix",
        {constraint:false,tag:'div',overlap:'horizontal',
			onUpdate:function(){
			//	alert(Sortable.serialize('MainMatrix')); 
			}
		});
	 
		//new Sortable.create('MainMatrix','div');
</script>
{/literal}
<script>
function showhide(tab)
{ldelim}
//alert(document.getElementById(tab))
var divid = document.getElementById(tab);
if(divid.style.display!='none')
	hide(tab)
else
	show(tab)
{rdelim}
</script>

	
			
