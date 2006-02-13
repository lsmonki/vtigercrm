
<script language="Javascript">
function validateNotNull()
{
	if (document.feed.email.value=="")
	{
		alert("Enter your mail id");
		document.feed.email.focus();
		return false;
	}
	if (document.feed.message.value=="")
	{
		alert("Enter your comments");
		document.feed.message.focus();
		return false;
	}
}
</script>
<table width="100%" cellspacing="0" cellpadding="10" border="0" align="center">


				</table></td>
			</tr>
		</table>

		<br />


<table width="100%" cellspacing="2" cellpadding="2" border="0" align="center">
</table>

<table class="forumline" width="100%" cellspacing="1" cellpadding="4" border="0">
	<tr>
		<th class="thHead" height="25"><b>FeedBack</b></th>
	</tr>
	<tr>
		<td class="row1"><table width="100%" cellspacing="0" cellpadding="1" border="0">
<form name="contact" method="post" action="mailform.php" onSubmit="return validateNotNull();">
<table border="0" align="center" cellpadding="2" width="450">
<!-- <tr>
<td width="120" valign="top">Username:</td>
<td><input type="text" name="Username"></td>
</tr> -->
<tr>
<td valign="middle" class="gensmall">Reference:</td>
<td class="gensmall">{URL}</td>
<input type="hidden" name="Referrer" value="{URL}">
</tr>
<tr>
<td valign="middle" class="gensmall">From:</td>
<td><input type="text" name="e-mail" value={HOSTNAME}><span class="gensmall"></span></td>
</tr>
<tr>
<td valign="middle" class="gensmall">Comments:</td>
<td><textarea name="comments" rows="10" cols="60"> </textarea></td>
</tr>
<tr>
<td>&nbsp;</td>
<td class="gensmall"><input type="submit" name="submit" value="Send!"></td>
</tr>
</table>
</form></td>
			</tr>
		</table></td>
	</tr>
</table>
