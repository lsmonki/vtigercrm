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
		<td style="padding-right: 0px;"><a href="#" onclick="fnvshobj(this,'allMenu')"><img src="{$IMAGE_PATH}btnL3AllMenu.gif" alt="Open All Menu..." title="Open All Menu..." border="0"></a></td>
		
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
</tr>
</table>

{* Quick Access Functionality *}
<div id="allMenu" onmouseover="fnvshNrm('allMenu');">
		<table cellpadding="5" cellspacing="0" class="allMnuTable" >
				<tr class="allMnuHandle">
						<td colspan="2" id="AllHandle">Jump To</td>
						<td align="right" style="padding-right:5px;">
							<a href="javascript:fninvsh('allMenu');"><img src="{$IMAGE_PATH}close_all.gif"  border="0"/></a></td>
				</tr>
				<tr>
						<td valign="top">

								<span class="allMnuHdr">Home</span>
										<a href="#" class="allMnu">Home</a>
										<a href="#" class="allMnu">Activities</a>
										<a href="#" class="allMnu">Calander</a>
										<a href="#" class="allMnu">Email</a>
								<span class="allMnuHdr">Sales</span>

										<a href="#" class="allMnu">Leads</a>
										<a href="#" class="allMnu">Accounts</a>
										<a href="#" class="allMnu">Contacts</a>
										<a href="#" class="allMnu">Potentials</a>
										<a href="#" class="allMnu">Quotes</a>
										<a href="#" class="allMnu">Sales Order</a>

										<a href="#" class="allMnu">Invoice</a>
										<a href="#" class="allMnu">Campaigns</a>
										<a href="#" class="allMnu">Products</a>
										<a href="#" class="allMnu">Price Books</a>
										<a href="#" class="allMnu">Notes</a>
						</td>

						<td valign="top">
								<span class="allMnuHdr">Support</span>
										<a href="#" class="allMnu">Help Desk</a>
										<a href="#" class="allMnu">Faq</a>
										<a href="#" class="allMnu">Accounts</a>
										<a href="#" class="allMnu">Contacts</a>

										<a href="#" class="allMnu">Products</a>
										<a href="#" class="allMnu">Notes</a>
								<span class="allMnuHdr">Analytics</span>
										<a href="#" class="allMnu">Dashboard</a>
										<a href="#" class="allMnu">Reports</a>
								<span class="allMnuHdr">Inventory</span>

										<a href="#" class="allMnu">Products</a>
										<a href="#" class="allMnu">Vendors</a>
										<a href="#" class="allMnu">Price Books</a>
										<a href="#" class="allMnu">PurchaseOrder</a>
										<a href="#" class="allMnu">SalesOrder</a>
										<a href="#" class="allMnu">Quotes</a>

										<a href="#" class="allMnu">Invoice</a>
						</td>
						<td valign="top">
								<span class="allMnuHdr">Tools</span>
										<a href="#" class="allMnu">Rss</a>
										<a href="#" class="allMnu">Portal</a>
										<a href="#" class="allMnu">Notes</a>

								<span class="allMnuHdr">Settings</span>
										<a href="#" class="allMnu">Settings</a>
						</td>
				</tr>
		</table>
</div>

{literal}
<script>
	var AllMnuHandle = document.getElementById("AllHandle");
	var AllMnuRoot   = document.getElementById("allMenu");
	Drag.init(AllMnuHandle, AllMnuRoot);
</script>

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

	
			
