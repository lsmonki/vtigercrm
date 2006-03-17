<script language="javascript" type="text/javascript">
	var Sele= '{$HOMEDEFAULTVIEW}';
	divarray = new Array('home_myaccount','home_mypot','home_mytopquote','home_metrics','home_mytkt','home_myact','home_mygrp','home_mytopso','home_mytopinv','home_mynewlead' );
	trarray = new Array('My Top Accounts','My Top Open Potentials','My Top Open Quotes','Key Metrics','My Tickets','My Upcoming and Pending Activities','My Group Allocation ','My Top Open Sales Orders','My Top Open Invoice','My New Leads'); 	
	var selrow ;

	function toggleshowhide(currentselected,rowselected)
	{ldelim}
		for(i = 0; i < divarray.length ;i++)
		{ldelim}
			if(Sele == divarray[i])
			{ldelim}
				selrow = trarray[i];			
				break;	
			{rdelim}
		{rdelim}
		hide (Sele);
		document.getElementById(selrow).className="mnuUnSel";
		Sele = currentselected;
		selrow = rowselected;
		document.getElementById(selrow).className="mnuSel";
		show (Sele);
	{rdelim}

	function ajaxResponse(response)
        {ldelim}
                document.getElementById('headlines').innerHTML = response.responseText;
                document.getElementById('headlines').style.display="block";
        {rdelim}
	
	function getHeadLines(Ticker)
        {ldelim}
                if(Ticker!='')
                {ldelim}
                        var ajaxObj = new Ajax(ajaxResponse);
                        var urlstring = "module=Accounts&action=HeadLines&tickersymbol="+Ticker;
                        ajaxObj.process("index.php?",urlstring);
                {rdelim}
        {rdelim}
</script>


<script type="text/javascript" language="JavaScript" src="smiletag/smiletag-script.js"></script>

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

<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="75%" style="padding:10px;" valign="top"><table width="100%"  border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td colspan="3" class="hometop">
		
		<div>		
			<table width="100%" border="0" cellpadding="0" cellspacing="0">
				<tr><td>My Home - <b>At a Glance</b></td>
					<td align="right" style="padding-right:10px; ">
						<select class="frmSelect">
							<option>Show Top 10</option>
							<option>Show Top 20</option>
							<option>Show Top 40</option>
							<option>Show All</option>
						</select>

					</td>
				
		{* {foreach item=hometab from=$HOMEDETAILS} 
	  		{if $hometab.Title.3 neq ''}	
				<td align="right" style="padding-right:10px; ">
					{$hometab.Title.3}
				</td>
			{/if}
		{/foreach} *}
				</tr>
		

		</table>
		</div>
		</td>
        </tr>
      <tr>
        <td colspan="3" class="homeBtm" >&nbsp;</td>
        </tr>
	   <tr>

        <td bgcolor="#959595" height="300" width="8"></td>
        <td valign="top"><table width="100%"  border="0" cellspacing="0" cellpadding="0" height="300">
          <tr>
            <td width="24%" bgcolor="#D7D7D7" valign="top">
				<table class="mnuTabH"  cellspacing="0" cellpadding="5">
				{foreach item=hometab from=$HOMEDETAILS}
	             		{if $hometab neq ''}
				{if $hometab.Title.2 eq $HOMEDEFAULTVIEW}  
				   <tr id="{$hometab.Title.1}" class="mnuSel" onclick = "toggleshowhide('{$hometab.Title.2}','{$hometab.Title.1}');">
    	           			 <td><img src="{$IMAGE_PATH}{$hometab.Title.0}" width="24" height="24" /></td>

        	        		 <td>{$hometab.Title.1}</td>
            	  		   </tr>
				{else}
					<tr id="{$hometab.Title.1}" class="mnuUnSel" onclick = "toggleshowhide('{$hometab.Title.2}','{$hometab.Title.1}');">
    	           			 <td><img src="{$IMAGE_PATH}{$hometab.Title.0}" width="24" height="24"/></td>
        	        		 <td>{$hometab.Title.1}</td>
            	  		   </tr>
			{/if}
			{/if}
	              		{/foreach}		  
	            </table>
			</td>
			<td id ="headlines"></td>
			<td class="padTab1">
				{foreach item=tabledetail from=$HOMEDETAILS}
				{if $tabledetail neq ''}
				{if $tabledetail.Title.2 neq $HOMEDEFAULTVIEW}	
				<div id="{$tabledetail.Title.2}" style="display:none">
				{else}   
				<div id="{$tabledetail.Title.2}" style="display:block">
				{/if}
				<table border="0" cellpadding="3" cellspacing="0" width="100%">
				<form name="homepage">
				<tr>
				  <td colspan="4"><span class="genHeaderSmall">{$tabledetail.Title.1}</span> <a href="#">(Mark as Default View)</a></td>
				</tr>
				<tr><td colspan="4">&nbsp;</td></tr>
				<tr>
				<!--header part-->
				{foreach key=header item=headerdetail from=$tabledetail.Header}	
				<td class="tblPro1ColHeader">&nbsp;{$headerdetail}</td>
				{/foreach}
				<!--end of header Part-->	
				</tr>
				<!--row of entries	-->
				{foreach key=row item=detail from=$tabledetail.Entries}
				<tr>
				<!--entries-->
				{foreach key=label item=entries from=$detail}
				<td class="tblPro1DataCell">{$entries}</td>
				{/foreach}
				<!--end of entries -->
				</tr>
				{/foreach}
				</form>
			</table>
			</div>
{/if}
{/foreach}
			</td>
			<td class="tabRht"></td>
          </tr>
        </table></td>

        <td bgcolor="#959595" width="8"></td>
      </tr>
	   <tr>
        <td height="8" colspan="3" bgcolor="#959595"></td>
	</tr>

       <tr>
        <td colspan="3" background="{$IMAGE_PATH}Home_15.gif" height="18">&nbsp;</td>
      </tr>
	  
    </table></td>

    <td width="25%" valign="top"><br>
	
		{if $TAGCLOUD_JS ne ''}
	            <link href="{$TAGCLOUD_CSS}" rel="stylesheet" type="text/css">
        	    <script language="JavaScript"  type="text/javascript" src="{$TAGCLOUD_JS}"></script>
        	{/if}
        	<br>
		</td>
		</tr>
		</table>
	</td>
	<td valign=top><img src="{$IMAGE_PATH}showPanelTopRight.gif"></td>
	</tr>
</table>
	
</td></tr></table>
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

	
			
