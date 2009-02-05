
{*<!-- this file displays a widget div - the contents of the div are loaded later usnig javascript -->*}

<div id="stuff_{$tablestuff.Stuffid}" class="MatrixLayer {if $tablestuff.Stufftitle eq 'Home Page Dashboard'}twoColumnWidget{/if}" style="float:left;overflow-x:hidden;overflow-y:auto;">
	<table width="100%" cellpadding="0" cellspacing="0" class="small" style="padding-right:0px;padding-left:0px;padding-top:0px;">
		<tr id="headerrow_{$tablestuff.Stuffid}" class="headerrow">
{if $MOD[$tablestuff.Stufftitle] neq ""}
	{assign var="stitle" value=$MOD[$tablestuff.Stufftitle]}
{else}
	{assign var="stitle" value=$tablestuff.Stufftitle}
{/if}
			<td align="left" class="homePageMatrixHdr" style="height:30px;" nowrap width=60%><b>&nbsp;{$stitle}</b></td>
			<td align="right" class="homePageMatrixHdr" style="height:30px;" width=5%>
				<span id="refresh_{$tablestuff.Stuffid}" style="position:relative;">&nbsp;&nbsp;</span>
			</td>
			<td align="right" class="homePageMatrixHdr" style="height:30px;" width=35% nowrap>

{*<!-- the edit button for widgets :: don't show for key metrics and dasboard widget -->*}
{if ($tablestuff.Stufftype neq "Default" || $tablestuff.Stufftitle neq "Key Metrics") && ($tablestuff.Stufftype neq "Default" || $tablestuff.Stufftitle neq "Home Page Dashboard") && ($tablestuff.Stufftype neq "Tag Cloud")}
				<a id="editlink" style='cursor:pointer;' onclick="showEditrow({$tablestuff.Stuffid})">
					<img src="{'windowSettings.gif'|@vtiger_imageurl:$THEME}" border="0" alt="Edit" title="Edit" hspace="2" align="absmiddle"/>
				</a>	
{else}
				<img src="{'windowSettings-off.gif'|@vtiger_imageurl:$THEME}" border="0" alt="Edit" title="Edit" hspace="2" align="absmiddle"/>
{/if}
{*<!-- code for edit button ends here -->*}

{*<!-- code for refresh button -->*}
{if $tablestuff.Stufftitle eq "Home Page Dashboard"}
				<a style='cursor:pointer;' onclick="fetch_homeDB({$tablestuff.Stuffid});">
					<img src="{'windowRefresh.gif'|@vtiger_imageurl:$THEME}" border="0" alt="Refresh" title="Refresh" hspace="2" align="absmiddle"/>
				</a>
{else}
				<a style='cursor:pointer;' onclick="loadStuff({$tablestuff.Stuffid},'{$tablestuff.Stufftype}');">
					<img src="{'windowRefresh.gif'|@vtiger_imageurl:$THEME}" border="0" alt="Refresh" title="Refresh" hspace="2" align="absmiddle"/>
				</a>
{/if}
{*<!-- code for refresh button ends here -->*}

{*<!-- hide button :: show only for default widgets  -->*}
{if $tablestuff.Stufftype eq "Default" || $tablestuff.Stufftype eq "Tag Cloud"}
				<a style='cursor:pointer;' onclick="HideDefault({$tablestuff.Stuffid})"><img src="{'windowMinMax.gif'|@vtiger_imageurl:$THEME}" border="0" alt="Hide" title="Hide" hspace="5" align="absmiddle"/></a>
{else}
				<img src="{'windowMinMax-off.gif'|@vtiger_imageurl:$THEME}" border="0" alt="Hide" title="Hide" hspace="5" align="absmiddle"/>
{/if}
{*<!-- code for hide button ends here -->*}

{*<!-- code for delete button :: dont show for default widgets -->*}
{if $tablestuff.Stufftype neq "Default" && $tablestuff.Stufftype neq "Tag Cloud"}
				<a id="deletelink" style='cursor:pointer;' onclick="DelStuff({$tablestuff.Stuffid})"><img src="{'windowClose.gif'|@vtiger_imageurl:$THEME}" border="0" alt="Close" title="Close" hspace="5" align="absmiddle"/></a>
{else}
				<img src="{'windowClose-off.gif'|@vtiger_imageurl:$THEME}" border="0" alt="Close" title="Close" hspace="5" align="absmiddle"/>
{/if}
{*<!-- code for delete button ends here -->*}
			</td>
		</tr>
	</table>
	
	<table width="100%" cellpadding="0" cellspacing="0" class="small" style="padding-right:0px;padding-left:0px;padding-top:0px;">
{if $tablestuff.Stufftype eq "Module"}
		<tr id="maincont_row_{$tablestuff.Stuffid}" class="show_tab winmarkModulesusr">
{elseif $tablestuff.Stufftype eq "Default" && $tablestuff.Stufftitle neq "Home Page Dashboard"}
		<tr id="maincont_row_{$tablestuff.Stuffid}" class="show_tab winmarkModulesdef">
{elseif $tablestuff.Stufftype eq "RSS"}
		<tr id="maincont_row_{$tablestuff.Stuffid}" class="show_tab winmarkRSS">
{elseif $tablestuff.Stufftype eq "DashBoard"}
		<tr id="maincont_row_{$tablestuff.Stuffid}" class="show_tab winmarkDashboardusr">
{elseif $tablestuff.Stufftype eq "Default" && $tablestuff.Stufftitle eq "Home Page Dashboard"}
		<tr id="maincont_row_{$tablestuff.Stuffid}" class="show_tab winmarkDashboarddef">
{elseif $tablestuff.Stufftype eq "Notebook" || $tablestuff.Stufftype eq "Tag Cloud"}
		<tr id="maincont_row_{$tablestuff.Stuffid}">
{else}
		<tr id="maincont_row_{$tablestuff.Stuffid}" class="show_tab">
{/if}
			<td colspan="2">
				<div id="stuffcont_{$tablestuff.Stuffid}" style="height:260px; overflow-y: auto; overflow-x:hidden;width:100%;height:100%;"> 
				</div>
			</td>
		</tr>
	</table>
	
	<table width="100%" cellpadding="0" cellspacing="0" class="small scrollLink">
	<tr>
		<td align="left">
			<a href="javascript:;" onclick="addScrollBar({$tablestuff.Stuffid});">
				{$MOD.LBL_SCROLL}
			</a>
		</td>
{if $tablestuff.Stufftype eq "Module" || ($tablestuff.Stufftype eq "Default" &&  $tablestuff.Stufftitle neq "Key Metrics" && $tablestuff.Stufftitle neq "Home Page Dashboard" && $tablestuff.Stufftitle neq "My Group Allocation" ) || $tablestuff.Stufftype eq "RSS" || $tablestuff.Stufftype eq "DashBoard"}
		<td align="right">
			<a href="#" id="a_{$tablestuff.Stuffid}">
				{$MOD.LBL_MORE}
			</a>
		</td>
{/if}
	</tr>	
	</table>
</div>

<script language="javascript">
	{*<!-- position the div in the page -->*}
	window.onresize = function(){ldelim}positionDivInAccord('stuff_{$tablestuff.Stuffid}','{$tablestuff.Stufftitle}','{$tablestuff.Stufftype}');{rdelim};
	positionDivInAccord('stuff_{$tablestuff.Stuffid}','{$tablestuff.Stufftitle}','{$tablestuff.Stufftype}');
</script>	
