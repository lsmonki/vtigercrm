{*<!-- module header -->*}
<script language="JavaScript" type="text/javascript" src="include/general.js"></script>
<script language="javascript">
function showDefaultCustomView(selectView)
{ldelim}
	viewName = selectView.options[selectView.options.selectedIndex].value;
	document.massdelete.viewname.value=viewName;
	document.massdelete.action="index.php?module={$MODULE}&action=index&return_module={$MODULE}&return_action=index&viewname="+viewName;
	document.massdelete.submit();
{rdelim}


                        function confirmdelete(url)
                        {ldelim}
                                if(confirm("Are you sure?"))
                                {ldelim}
                                        document.location.href=url;
                                {rdelim}
                        {rdelim}

function eMail()
{ldelim}
    x = document.massdelete.selected_id.length;
        var viewid = document.massdelete.viewname.value;
        idstring = "";

        if ( x == undefined)
        {ldelim}

                if (document.massdelete.selected_id.checked)
                {ldelim}
                        document.massdelete.idlist.value=document.massdelete.selected_id.value;
                {rdelim}
                else
                {ldelim}
                        alert("Please select atleast one entity");
                        return false;
                {rdelim}
        {rdelim}
        else
        {ldelim}
                xx = 0;
                for(i = 0; i < x ; i++)
                {ldelim}
                        if(document.massdelete.selected_id.checked)
                        {ldelim}
                                idstring = document.massdelete.selected_id.value +";"+idstring
                                xx++
                        {rdelim}
                {rdelim}
                if (xx != 0)
                {ldelim}
                        document.massdelete.idlist.value=idstring;
                {rdelim}
                else
                {ldelim}
                        alert("Please select atleast one entity");
                        return false;
                {rdelim}
        {rdelim}

   document.massdelete.action="index.php?module=Emails&action=SelectEmails&return_module={$MODULE}&return_action=index";
{rdelim}

function massDelete()
{ldelim}
        x = document.massdelete.selected_id.length;
        var viewid = document.massdelete.viewname.value;
        idstring = "";

        if ( x == undefined)
        {ldelim}

                if (document.massdelete.selected_id.checked)
                {ldelim}
                        document.massdelete.idlist.value=document.massdelete.selected_id.value;
                {rdelim}
                else
                {ldelim}
                        alert("Please select atleast one entity");
                        return false;
                {rdelim}
        {rdelim}
        else
        {ldelim}
                xx = 0;
                for(i = 0; i < x ; i++)
                {ldelim}
                        if(document.massdelete.selected_id.checked)
                        {ldelim}
                                idstring = document.massdelete.selected_id.value +";"+idstring
                 	        xx++
                        {rdelim}
                {rdelim}
                if (xx != 0)
                {ldelim}
                        document.massdelete.idlist.value=idstring;
                {rdelim}
               else
		{ldelim}
                        alert("Please select atleast one entity");
                        return false;
                {rdelim}
        {rdelim}
        if(confirm("Are you sure you want to delete the selected "+xx+" records ?"))
        {ldelim}
            document.massdelete.action="index.php?module=Users&action=massdelete&return_module={$MODULE}&return_action=index&viewname="+viewid;
        {rdelim}
        else
        {ldelim}
            return false;
        {rdelim}

{rdelim}




</script>



<TABLE border=0 cellspacing=0 cellpadding=0 width=100% class=small>

<tr><td style="height:2px"></td></tr>
<tr>
	<td style="padding-left:10px;padding-right:10px" class="moduleName" nowrap>Sales > {$MODULE}</td>
	<td class="sep1" style="width:1px"></td>
	<td class=small >
		<table border=0 cellspacing=0 cellpadding=0>
		<tr>
			<td>
				<table border=0 cellspacing=0 cellpadding=5>

				<tr>
					<td style="padding-right:0px"><a href="index.php?module={$MODULE}&action=EditView"><img src="{$IMAGE_PATH}btnL3Add.gif" alt="Create {$MODULE}..." title="Create {$MODULE}..." border=0></a></td>
					<td style="padding-right:0px"><a href="#"><img src="{$IMAGE_PATH}btnL3Search.gif" alt="Search in {$MODULE}..." title="Search in {$MODULE}..." border=0></a></a></td>
				</tr>
				</table>
			</td>
			<td nowrap width=50>&nbsp;</td>
			<td>
				<table border=0 cellspacing=0 cellpadding=5>

				<tr>
					<td style="padding-right:0px"><a href="#"><img src="{$IMAGE_PATH}btnL3Calendar.gif" alt="Open Calendar..." title="Open Calendar..." border=0></a></a></td>
					<td style="padding-right:0px"><a href="#"><img src="{$IMAGE_PATH}btnL3Clock.gif" alt="Show World Clock..." title="Show World Clock..." border=0></a></a></td>
					<td style="padding-right:0px"><a href="#"><img src="{$IMAGE_PATH}btnL3Calc.gif" alt="Open Calculator..." title="Open Calculator..." border=0></a></a></td>
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
	<td class="sep1" style="width:1px"></td>
	<td nowrap style="width:50%;padding:10px">

		<a href="index.php?module={$MODULE}&action=Import&step=1&return_module={$MODULE}&return_action=index">Import {$MODULE}</a> | <a href="export.php?module={$MODULE}&action=Export&all=1">Export {$MODULE}</a>
		{if $MODULE eq 'Contacts'}
			&nbsp;|&nbsp;<a href='index.php?module={$MODULE}&action=AddBusinessCard&return_module={$MODULE}&return_action=ListView'>Add Business Card</a>
		{/if}
	</td>
</tr>
<tr><td style="height:2px"></td></tr>

</TABLE>



<table border=0 cellspacing=0 cellpadding=0 width=98% align=center>
<tr>
	<td valign=top><img src="{$IMAGE_PATH}showPanelTopLeft.gif"></td>

	<td class="showPanelBg" valign=top width=100%>
		<!-- PUBLIC CONTENTS STARTS-->
		<div class="small" style="padding:20px">

		<table border=0 cellspacing=1 cellpadding=0 width=100% class="lvtBg">
		<tr style="background-color:#efefef">
			<td >
			  <table border=0 cellspacing=0 cellpadding=2 width=100%>
				<tr>
					{foreach item=view from=$CUSTOMVIEW}

                                        <td>{$view}</td>
                                        {/foreach}

				</tr>
           			<tr>
					{*<td style="padding-right:20px" ><input type="button" value="Delete" class="small"></td>*}
					<td style="padding-right:20px" nowrap>{$RECORD_COUNTS}</td>
	
        			        <td nowrap><table border=0 cellspacing=0 cellpadding=0><tr>{$NAVIGATION}</tr></table></td>
					{*<td width=100% align=right>{$CUSTOMVIEW}</td>*}
               			</tr>
			</table>

			<div  style="overflow:auto;width:100%;height:300px; border-top:1px solid #999999;border-bottom:1px solid #999999">
			<table border=0 cellspacing=1 cellpadding=3 width=100% style="background-color:#cccccc;">
			<tr>
			<td class="lvtCol"><input type="checkbox"  name="selectall" onClick=toggleSelect(this.checked,"selected_id")></td>
			{foreach item=header from=$LISTHEADER}
        			<td class="lvtCol">{$header}</td>
			{/foreach}
			</tr>
			{foreach item=entity from=$LISTENTITY}
				<tr bgcolor=white onMouseOver="this.className='lvtColDataHover'" onMouseOut="this.className='lvtColData'"  >
				<td><input type="checkbox" NAME="selected_id" value= '.$entity_id.' onClick=toggleSelectAll(this.name,"selectall")></td>
				{foreach item=data from=$entity}	
					<td>
						{$data}
					</td>
				{/foreach}
				</tr>
			{/foreach}
			</table>
			</div>

			{*<table border=0 cellspacing=1 cellpadding=0 width=100% class="lvtBg">
               		<tr style="background-color:#efefef">
                        <td >
                          <table border=0 cellspacing=0 cellpadding=2 width=100%>
                                <tr>
                                        <td style="padding-right:20px" ><input type="button" value="Delete" class="small"></td>
                                        <td style="padding-right:20px" nowrap>{$RECORD_COUNTS}</td>

                                        <td nowrap><table border=0 cellspacing=0 cellpadding=0><tr>{$NAVIGATION}</tr></table></td>
                                </tr>
                        </table>*}

</td></tr></table></div>

<table cellpadding="0" cellspacing="0" width="100%" border="0" class="FormBorder">
<tr><td>
{$WORDTEMPLATEOPTIONS}
{$MERGEBUTTON}
</td></tr></table>
</td></tr></table>
{$SELECT_SCRIPT}
</form>

