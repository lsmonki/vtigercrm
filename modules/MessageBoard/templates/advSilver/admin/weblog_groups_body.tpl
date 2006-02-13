<!-- FIND - Forum Inserted News Delivery - Input - Version 0.9.6 BETA -->
<script language="Javascript" type="text/javascript"> 
<!-- 
function setCheckboxes(theForm, elementName, isChecked)
{
    var chkboxes = document.forms[theForm].elements[elementName];
    var count = chkboxes.length;

    if (count) 
	{
        for (var i = 0; i < count; i++) 
		{
            chkboxes[i].checked = isChecked;
    	}
    } 
	else 
	{
    	chkboxes.checked = isChecked;
    } 

    return true;
} 
//--> 
</script> 

<h1>{L_WEBLOG_GROUPS}</h1>

<p>{L_WEBLOG_GROUPS_EXPLAIN}</p>

<form name="weblog_groups_form" id="weblog_groups_form" method="post" action="{S_FORM_ACTION}">
<table width="100%" cellspacing="1" cellpadding="4" border="0" align="center" class="forumline">
	<tr>
		<th class="thHead" align="center" colspan="2">{L_WEBLOG_ADD_GROUP}</th>
	</tr>
	<tr>
		<td class="row1">{L_WEBLOG_GROUP_SELECT}: <br/>{L_WEBLOG_GROUP_SELECT_EXPLAIN}</td><td class="row2" width="50%">&nbsp;{S_GROUP_SELECT}</td>
	</tr>
	<tr>
		<td height="1" colspan="2" class="spaceRow"><img src="../templates/advSilver/images/spacer.gif" alt="" width="1" height="1" /></td>
	</tr>
    <tr><td class="row2" colspan="2" align="center">
            <input type="submit" name="add_weblog_group" class="liteoption" value="{L_WEBLOG_ADD_GROUP}" />&nbsp;&nbsp;
        </td>
    </tr>
</table>
<br/>
<br/>
<table width="100%" cellspacing="1" cellpadding="4" border="0" align="center" class="forumline">
	<tr><th class="thHead" align="center" colspan="4">{L_WEBLOGS_TITLE}</th></tr>
    <!-- BEGIN group_row -->
	<tr><td class="row1" colspan="3"><span class="gen"><a href="{group_row.U_WEBLOG_GROUP}" class="gen">{group_row.GROUP_NAME}</a></span></td>
   		<td class="row2" align="center" nowrap>
            <input type="checkbox" name="group_id_list[]" value="{group_row.GROUP_ID}" />
        </td>
    </tr>
    <!-- END group_row -->
	<tr>
		<td height="1" colspan="4" class="spaceRow"><img src="../templates/advSilver/images/spacer.gif" alt="" width="1" height="1" /></td>
	</tr>
    <tr><td class="row2" colspan="3" align="right" width="100%">
            <input type="submit" name="remove_weblog_group" class="liteoption" value="{L_WEBLOG_REMOVE_GROUP}" />&nbsp;&nbsp;
        </td>
        <td class="row2" align="center" nowrap>
   		<span class="gensmall">
			<a href="#" onclick="setCheckboxes('weblog_groups_form', 'group_id_list[]', true); return false;">{L_CHECK_ALL}</a>&nbsp;/&nbsp;
			<a href="#" onclick="setCheckboxes('weblog_groups_form', 'group_id_list[]', false); return false;">{L_UNCHECK_ALL}</a>
		</span>
        </td>
    </tr>
</table>
</form>
<br/>
<br/>
