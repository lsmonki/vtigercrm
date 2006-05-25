<table class="small" border="0" cellpadding="5" cellspacing="0" width="100%">
<tbody><tr>
<td style="padding: 5px;" bgcolor="#333333"><span style="color: rgb(255, 255, 255);">{$MOD.LBL_BOOKMARK_LIST} : </span>

<select id="urllist" name="urllist" style="border: 0px solid rgb(204, 204, 204); width: 90%;" onChange="setSite(this);">
{foreach item=portaldetails key=sno from=$PORTALS}
<option value="{$portaldetails.portalurl}">{$portaldetails.portalname}</option>
{/foreach}
</select>

</td>
</tr>
<tr><td><hr></td></tr>
<tr>
<td bgcolor="#ffffff">
<iframe id="locatesite" src="" frameborder="0" height="350" scrolling="auto" width="100%"></iframe>
</td>
</tr>
</tbody></table>

