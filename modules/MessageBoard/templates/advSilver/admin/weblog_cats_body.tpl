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

<h1>{L_WEBLOG_CATEGORIES}</h1>

<p>{L_WEBLOG_CATEGORIES_EXPLAIN}</p>

<form name="weblogs_form" id="weblogs_form" method="post" action="{S_FORM_ACTION}">
<table width="100%" cellspacing="1" cellpadding="4" border="0" align="center" class="forumline">
	<tr>
		<th class="thHead" align="center" colspan="2">{L_WEBLOG_ADD_CAT}</th>
	</tr>
	<tr>
		<td class="row1">{L_WEBLOG_CAT_SELECT}: <br/>{L_WEBLOG_CAT_SELECT_EXPLAIN}</td><td class="row2" width="50%">&nbsp;{S_CAT_SELECT}</td>
	</tr>
	<tr>
		<td height="1" colspan="2" class="spaceRow"><img src="../templates/advSilver/images/spacer.gif" alt="" width="1" height="1" /></td>
	</tr>
    <tr><td class="row2" colspan="2" align="center">
            <input type="submit" name="add_weblog_cat" class="liteoption" value="{L_WEBLOG_ADD_CAT}" />&nbsp;&nbsp;
        </td>
    </tr>
</table>
<br/>
<br/>
<table width="100%" cellspacing="1" cellpadding="4" border="0" align="center" class="forumline">
	<tr><th class="thHead" align="center" colspan="4">{L_WEBLOGS_TITLE}</th></tr>
    <!-- BEGIN cat_row -->
	<tr><td class="catLeft" colspan="3"><span class="cattitle"><a href="{cat_row.U_WEBLOG_CAT}" class="gen">{cat_row.CAT_TITLE}</a></span></td>
   		<td class="catRight" align="center" nowrap>
            <input type="checkbox" name="cat_id_list[]" value="{cat_row.CAT_ID}" />
        </td>
    </tr>
   	<!-- BEGIN weblog_row -->
   	<tr>
   		<td class="row1" align="left"><span class="genmed"><a href="{cat_row.weblog_row.U_WEBLOG_FORUM}" class="gen">{cat_row.weblog_row.S_WEBLOG_NAME}</a></span></td>
   		<td class="row1" align="left"><span class="genmed">{cat_row.weblog_row.S_WEBLOG_DESC}</span></td>
   		<td class="row1" align="left"><span class="genmed">{cat_row.weblog_row.S_WEBLOG_START}</span></td>
   		<td class="row1" align="left"><span class="genmed"><a href="{cat_row.weblog_row.U_WEBLOG_PROFILE}" class="gen">{cat_row.weblog_row.S_USER_NAME}</a></span></td>
   	</tr>
   	<!-- END weblog_row -->
	<tr>
		<td height="1" colspan="4" class="spaceRow"><img src="../templates/advSilver/images/spacer.gif" alt="" width="1" height="1" /></td>
	</tr>
    <!-- END cat_row -->
    <tr><td class="row2" colspan="3" align="right" width="100%">
            <input type="submit" name="remove_weblog_cat" class="liteoption" value="{L_WEBLOG_REMOVE_CAT}" />&nbsp;&nbsp;
        </td>
        <td class="row2" align="center" nowrap>
   		<span class="gensmall">
			<a href="#" onclick="setCheckboxes('weblogs_form', 'cat_id_list[]', true); return false;">{L_CHECK_ALL}</a>&nbsp;/&nbsp;
			<a href="#" onclick="setCheckboxes('weblogs_form', 'cat_id_list[]', false); return false;">{L_UNCHECK_ALL}</a>
		</span>
        </td>
    </tr>
</table>
</form>
<br/>
<br/>
