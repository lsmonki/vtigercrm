<html>
	<head>
		<title>Vtiger Bookmarklet</title>
		<script type="text/javascript" src="modules/Emails/GmailBookmarklet.js"></script>
		<style type="text/css">
		{literal}
table { font: inherit; }
.small {
	color:#000000;
	font-family:Arial,Helvetica,sans-serif;
	font-size:12px;
}

input{
	color:#000000;
	font-style:normal;
	font-family:Arial,Helvetica,sans-serif;
	font-size:12px;
}
.big {
	font-family: Arial, Helvetica, sans-serif;
	font-size:14px;
}
.dvtCellLabel, .cellLabel {
	background:#F7F7F7;
	border-bottom:1px solid #DEDEDE;
	border-left:1px solid #DEDEDE;
	border-right:1px solid #DEDEDE;
	color:#545454;
	padding-left:10px;
	padding-right:10px;
	white-space:nowrap;
	text-align: right;
}
.dvtCellInfo, .cellInfo {
	border-bottom:1px solid #DEDEDE;
	border-left:1px solid #DEDEDE;
	border-right:1px solid #DEDEDE;
	padding-left:10px;
	padding-right:10px;
}
.button {
	width: 70px;
	color: black;
	font-family: Arial, Helvetica, sans-serif;
	font-size: 10px;
	font-weight: bold;
}
.save {
	background-color: #9CC83E;
}
.cancel {
	background-color: #E0AD07;
}
.tiny {
	font-size: 9px;
	text-decoration: italic;
	color: #3388cc;
}
.tableHeader{
	background: white;
}
.searchLinks{
	text-decoration: underline;
	padding: 10px;
	color: blue;
	cursor: pointer;
	line-height: 22px;  
}
.bold{
	font-weight: bold;
}
{/literal}
		</style>
		<script type="text/javascript">
			var moduleNameFields = '{$entityNameFields}';
		</script>
	</head>
	<body onload="init();" class="small">
		<div id="__vtigerBookMarkletDiv__">
			<table border="0" cellpadding="0" cellspacing="1" width="100%">
				<tbody>
					<tr>
						<td colspan="2" class="dvtCellLabel big tableHeader" style="text-align: center;">
							<b>Google Mail Information</b>
						</td>
					</tr>
					<tr>
						<th class="dvtCellLabel small">Subject</th>
						<td class="dvtCellInfo"><input id="subject" value="{$subject}" size="32"></td>
					</tr>
					<tr>
						<th class="dvtCellLabel small">Body</th>
						<td class="dvtCellInfo">
							<textarea id="description" rows="5" cols="40">this email is from Gmail, click &lt;a href="{$description}"&gt;here&lt;/a&gt; to view it contents.</textarea>
						</td>
					</tr>
					<tr>
						<th class="dvtCellLabel small">
							<select id="parentType">
								{foreach key=index item=moduleName from=$types}
								<option value="{$moduleName}">{$moduleName}</option>
								{/foreach}
							</select>
						</th>
						<td class="dvtCellInfo">
							<span id="parentName" class="small bold">&nbsp;</span><br>
							<input id="parent_id" value="" type="hidden" />
							<input id="__searchaccount__" value="" size="30" />&nbsp; 
							<input class="button save" id="__searchVtigerAccount__" value="Search" type="button" />
						</td>
					</tr>
					<tr id="__vtigerAccountSearchList___">
						
					</tr>
					<tr>
						<td align="center" colspan="2"><input id="__saveVtigerEmail__" value="Save" type="button" class="button save"></td>
					</tr>
				</tbody>
			</table>
		</div>
	</body>
</html>