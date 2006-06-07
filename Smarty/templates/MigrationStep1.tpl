{*<!--
/*********************************************************************************
  ** The contents of this file are subject to the vtiger CRM Public License Version 1.0
   * ("License"); You may not use this file except in compliance with the License
   * The Original Code is:  vtiger CRM Open Source
   * The Initial Developer of the Original Code is vtiger.
   * Portions created by vtiger are Copyright (C) vtiger.
   * All Rights Reserved.
  *
 ********************************************************************************/
-->*}


<script language="JavaScript" type="text/javascript" src="include/js/menu.js"></script>
<script language="JavaScript" type="text/javascript" src="include/js/general.js"></script>
<style type="text/css">@import url(themes/blue/style.css);</style>

<form name="Migration" method="POST" action="index.php" enctype="multipart/form-data">
<input type="hidden" name="module" value="Migration">
<input type="hidden" name="action" value="MigrationCheck">
<input type="hidden" name="migration_option" value="">
<input type="hidden" name="parenttab" value="Settings">
<input type="hidden" id="getmysqlpath" name="getmysqlpath" value="{$GET_MYSQL_PATH}">

<table width="100%" border="0" cellpadding="0" cellspacing="0" height="100%" class="small">
   <tr>
	<td class="showPanelBg" valign="top" width="95%"  style="padding-left:20px; "><br />
		<span class="lvtHeaderText"> Settings &gt; Migrate from Previous Version </span>
		<hr noshade="noshade" size="1" />
	</td>
	<td width="5%" class="showPanelBg">&nbsp;</td>
   </tr>
   <tr>
	<td width="98%" style="padding-left:20px;" valign="top">
		<!-- module Select Table -->
		<table width="95%"  border="0" cellspacing="0" cellpadding="0" align="center" class="small">
		   <tr>
			<td width="7" height="6" style="font-size:1px;font-family:Arial, Helvetica, sans-serif;"><img src="{$IMAGE_PATH}top_left.jpg" align="top"  /></td>
			<td bgcolor="#EBEBEB" style="font-size:1px;font-family:Arial, Helvetica, sans-serif;height:6px;"></td>
			<td width="8" height="6" style="font-size:1px;font-family:Arial, Helvetica, sans-serif;"><img src="{$IMAGE_PATH}top_right.jpg" width="8" height="6" align="top" /></td>
		   </tr>
		   <tr>
			<td bgcolor="#EBEBEB" width="7"></td>
			<td bgcolor="#ECECEC" style="padding-left:10px;padding-top:10px;vertical-align:top;">
				<table width="100%"  border="0" cellpadding="5" cellspacing="0" class="small">
				   <tr>
					<td width="10%"><img src="{$IMAGE_PATH}migrate.gif" align="absmiddle"/></td>
					<td width="90%">
						<span class="genHeaderBig">Migrate From Previous Version</span><br />
						Update your new vtiger CRM 5 database with the data from previous installation<br />
						To Start, follow the instructions below
					</td>
				   </tr>
				   <tr bgcolor="#FFFFFF">
					<td colspan="2">
						<span class="genHeaderGray">Step 1 : </span>
				  		<span class="genHeaderSmall">Select Source</span><br />
						To Start Migration, you must specify the format in which the old data is Available<br /><br />
					</td>
				   </tr>
				   <tr bgcolor="#FFFFFF">
					<td align="right" valign="top">
						<input type="radio" name="radio" id="db_details" value="db_details" onclick="fnChangeMigrate()" "{$DB_DETAILS_CHECKED}" />
					</td>
					<td>
						<b>I Have the Data Base Format</b> ( Live Data )<br />
						This option requires you to have the host machine's ( where the DB is stored ) address and DB access  details.
						Both local and remote systems are supported in this method. Refer documentation for Help.
					</td>
				   </tr>
				   <tr><td colspan="2" bgcolor="#FFFFFF" height="10"></td></tr>
				   <tr bgcolor="#FFFFFF">
					<td align="right" valign="top">
						<input type="radio" name="radio" id="dump_details" value="dump_details" onclick="fnChangeMigrate()" "{$DUMP_DETAILS_CHECKED}"/>
					</td>
					<td>
						<b>I Have a Data Base as a Database Dump</b> ( Usually archived )<br />
						This option requires you to have the dump file, in this local system.
						You cannot specify a remote machine. Refer documentation for Help.
					</td>
				   </tr>
				   <tr><td colspan="2" bgcolor="#FFFFFF" height="10"></td></tr>
				   <tr bgcolor="#FFFFFF">
					<td align="right" valign="top">
						<input type="radio" name="radio" id="alter_db_details" value="alter_db_details" onclick="fnChangeMigrate()" "{$ALTER_DB_DETAILS_CHECKED}"/>
					</td>
					<td>
						<b>I Have a New Data Base with 4.2.3 Data.</b> ( Usually archived )<br />
						This option requires you to have the 4.2.3 host machine's ( where the DB is stored ) address and DB access  details.
						You cannot specify a remote machine.
					</td>
				   </tr>
				   <tr><td colspan="2" bgcolor="#FFFFFF" height="10"></td></tr>

				   <tr><td colspan="2" height="10"></td></tr>
				   <tr bgcolor="#FFFFFF">
					<td colspan="2">


						<!-- OPTION 1 -->
						<div id="mnuTab" style="display:{$SHOW_DB_DETAILS}">
							<table width="100%" border="0" cellpadding="5" cellspacing="0" class="small">
							   <tr bgcolor="#FFFFFF">
								<td colspan="2">
									<span class="genHeaderGray">Step 2 : </span>
									<span class="genHeaderSmall">Host Database Access Details</span><br /><br />
								</td>
							   </tr>
							   <tr>
								<td width="30%" align="right">Source MySQL Host Name or IP Address : </td>
								<td width="70%"><input type="text" name="old_host_name" class="importBox" value="{$OLD_HOST_NAME}" /></td>
							   </tr>
							   <tr>
								<td align="right">Source MySQL Port Number : </td>
								<td><input type="text" name="old_port_no" class="importBox" value="{$OLD_PORT_NO}" /></td>
							   </tr>
							   <tr>
								<td align="right">Source MySql User Name : </td>
								<td><input type="text" name="old_mysql_username" class="importBox" value="{$OLD_MYSQL_USERNAME}" /></td>
							   </tr>
							   <tr>
								<td align="right">Source MySql Password : </td>
								<td><input type="text" name="old_mysql_password" class="importBox" value="{$OLD_MYSQL_PASSWORD}" /></td>
							   </tr>
							   <tr>
								<td align="right">Source Database Name : </td>
								<td><input type="text" name="old_dbname" class="importBox" value="{$OLD_DBNAME}" /></td>
							   </tr>
							</table>
						</div>

						<!-- OPTION 2 -->
						<div id="mnuTab1" style="display:{$SHOW_DUMP_DETAILS}">
							<table width="100%" border="0" cellpadding="5" cellspacing="0" class="small">
							   <tr bgcolor="#FFFFFF">
								<td colspan="2">
									<span class="genHeaderGray">Step 2 : </span>
									<span class="genHeaderSmall">Locate Database Dump File</span><br /><br />
								</td>
							   </tr>
							   <tr>
								<td width="10%">&nbsp;</td>
								<td width="90%">
									Dump File Location : 
									<input type="file" name="old_dump_filename" class="txtBox" />
								</td>
							   </tr>
							   <tr><td colspan="2" height="10"></td></tr>
							   <tr bgcolor="#FFFFFF">
								<td align="right" valign="top"><b>Note: </b></td>
								<td>{$MOD.LBL_NOTES_DUMP_PROCESS}</td>
							   </tr>
							</table>
						</div>


						<!-- OPTION 3 -->
						<div id="mnuTab2" style="display:{$SHOW_ALTER_DB_DETAILS}">
							<table width="100%" border="0" cellpadding="5" cellspacing="0" class="small">
							   <tr bgcolor="#FFFFFF">
								<td colspan="3">
									<span class="genHeaderGray">Step 2 : </span>
									<span class="genHeaderSmall">Host Database Access Details</span><br /><br />
								</td>
							   </tr>
							   <tr>
								<td width="5%">&nbsp;</td>
								<td width="20%" align="right">MySQL Host Name or IP Address : </td>
								<td width="75%"><input type="text" name="alter_old_host_name" class="importBox" value="{$ALTER_OLD_HOST_NAME}" /></td>
							   </tr>
							   <tr>
								<td>&nbsp;</td>
								<td align="right">MySQL Port Number : </td>
								<td><input type="text" name="alter_old_port_no" class="importBox" value="{$ALTER_OLD_PORT_NO}" /></td>
							   </tr>
							   <tr>
								<td>&nbsp;</td>
								<td align="right">MySql User Name : </td>
								<td><input type="text" name="alter_old_mysql_username" class="importBox" value="{$ALTER_OLD_MYSQL_USERNAME}" /></td>
							   </tr>
							   <tr>
								<td>&nbsp;</td>
								<td align="right">MySql Password : </td>
								<td><input type="text" name="alter_old_mysql_password" class="importBox" value="{$ALTER_OLD_MYSQL_PASSWORD}" /></td>
							   </tr>
							   <tr>
								<td>&nbsp;</td>
								<td align="right">Database Name : </td>
								<td><input type="text" name="alter_old_dbname" class="importBox" value="{$ALTER_OLD_DBNAME}" /></td>
							   </tr>
							   <tr><td colspan="3" height="10"></td></tr>
							   <tr bgcolor="#FFFFFF">
								<td align="right" valign="top"><b>Note: </b></td>
								<td width="90%" colspan="2">

<font color="red">Please do not give the 4.2.3 Database details. This option will alter the given database directly.</font>
<br>It is strongly recommended that to do the following.
<br>1. Take a dump of your 4.2.3 database
<br>2. Create new database (Better is to create a database in the server where your vtiger 5.0 Database is running.)
<br>3. Apply this 4.2.3 dump to this new database.
<br>Now give this new database access details. This migration will modify this Database to fit with the 5.0 Schema.
Then you can give this Database name in config.php file to use this Database ie., $dbconfig['db_name'] = 'new db name';

								</td>
							   </tr>

							</table>
						</div>


					</td>
				   </tr>

				   <!-- this if condition is added to display the text box to get the mysql server path -->
				   {if $GET_MYSQL_PATH eq 1}
				   <tr><td colspan="2" height="10"></td></tr>
				   <tr>
					<td colspan="2" bgcolor="white">
						<!-- OPTION 3 -->
						<div id="mnuTab3" style="width:100%; display:{$SHOW_MYSQL_PATH}">
							<table width="100%" border="0" cellpadding="5" cellspacing="0" class="small">
							   <tr bgcolor="#FFFFFF">
								<td colspan="2">
									<span class="genHeaderGray">Step 3 : </span>
									<span class="genHeaderSmall">Enter MySQL Server Path</span><br>MySQL path in the server like <b>/home/5beta/vtigerCRM5_beta/mysql/bin</b> or <b>c:\Program Files\mysql\bin</b><br /><br />
								</td>
							   </tr>
							   <tr>
								<td align="right" width="30%">MySQL Server Path : </td>
								<td width="70%">
									<input type="text" name="server_mysql_path" class="txtBox" value="{$SERVER_MYSQL_PATH}" />
								</td>
							   </tr>
							</table>
						</div>


					</td>
				   </tr>
				   {/if}
				   <tr>
					<td colspan="2" style="padding:10px;" align="center">
						<input type="submit" name="migrate" value="  Migrate  "  class="classBtn" onclick="return validate_migration(Migration);"/>
						&nbsp;<input type="submit" name="cancel" value=" &nbsp;Cancel&nbsp; "  class="classBtn" onclick="this.form.module.value='Settings';this.form.action.value='index';"/>
 					</td>
				   </tr>
				</table>
			</td>
			<td bgcolor="#EBEBEB" width="8"></td>
		   </tr>
		   <tr>
			<td width="7" height="8" style="font-size:1px;font-family:Arial, Helvetica, sans-serif;"><img src="{$IMAGE_PATH}bottom_left.jpg" align="bottom"  /></td>
			<td bgcolor="#ECECEC" height="8" style="font-size:1px;" ></td>
			<td width="8" height="8" style="font-size:1px;font-family:Arial, Helvetica, sans-serif;"><img src="{$IMAGE_PATH}bottom_right.jpg" align="bottom" /></td>
		   </tr>
		</table>
		<br />
	</td>
	<td>&nbsp;</td>
   </tr>
</table>
<!-- END -->
</form>

<script language="javascript" type="text/javascript">
	//function to show and hide the db_details or dump_details details based on the radio option selected
	function fnChangeMigrate()
	{ldelim}
		var opt_one = document.getElementById('db_details').checked;
		var opt_two = document.getElementById('dump_details').checked;
		var opt_three = document.getElementById('alter_db_details').checked;
		if(opt_one)
		{ldelim}
			document.getElementById('mnuTab').style.display = 'block';
			document.getElementById('mnuTab1').style.display = 'none';
			document.getElementById('mnuTab2').style.display = 'none';
		{rdelim}
		else if(opt_two)
		{ldelim}
			document.getElementById('mnuTab').style.display = 'none';
			document.getElementById('mnuTab1').style.display = 'block';
			document.getElementById('mnuTab2').style.display = 'none';
		{rdelim}
		else
		{ldelim}
			document.getElementById('mnuTab').style.display = 'none';
			document.getElementById('mnuTab1').style.display = 'none';
			document.getElementById('mnuTab2').style.display = 'block';
		{rdelim}
	{rdelim}

	//function to validate the input values based on the radio option selected
	function validate_migration(formname)
	{ldelim}

		var error = false;
		var mig_option = '';

		if(document.getElementById("db_details").checked == true)
		{ldelim}
			formname.migration_option.value = 'db_details';
			//check whether the user entered the valid Source MySQL database details when db details selected
			if(trim(formname.old_host_name.value) == '')
			{ldelim}
				error_msg = "Please enter the Source Host Name";
				error = true;
			{rdelim}
			else if(trim(formname.old_port_no.value) == '')
			{ldelim}
				error_msg = "Please enter the Source MySql Port Number";
				error = true;
			{rdelim}
			else if(trim(formname.old_mysql_username.value) == '')
			{ldelim}
				error_msg = "Please enter the Source MySql User Name";
				error = true;
			{rdelim}
			else if(trim(formname.old_dbname.value) == '')
			{ldelim}
				error_msg = "Please enter the Source Database Name";
				error = true;
			{rdelim}
		{rdelim}
		else if(document.getElementById("dump_details").checked == true)
		{ldelim}
			formname.migration_option.value = 'dump_details';
			//check whether the user entered the MySQL File when dump file details selected
			if(trim(formname.old_dump_filename.value) == '')
			{ldelim}
				error_msg = "Please enter the Valid MySQL Dump File";
				error = true;
			{rdelim}
		{rdelim}
		else if(document.getElementById("alter_db_details").checked == true)
		{ldelim}
			formname.migration_option.value = 'alter_db_details';
			//check whether the user entered the valid Source MySQL database details when db details selected
			if(trim(formname.alter_old_host_name.value) == '')
			{ldelim}
				error_msg = "Please enter the Host Name";
				error = true;
			{rdelim}
			else if(trim(formname.alter_old_port_no.value) == '')
			{ldelim}
				error_msg = "Please enter the MySql Port Number";
				error = true;
			{rdelim}
			else if(trim(formname.alter_old_mysql_username.value) == '')
			{ldelim}
				error_msg = "Please enter the MySql User Name";
				error = true;
			{rdelim}
			else if(trim(formname.alter_old_dbname.value) == '')
			{ldelim}
				error_msg = "Please enter the Database Name";
				error = true;
			{rdelim}
		{rdelim}
		else
		{ldelim}
			formname.migration_option.value = '';
			error_msg = "Please select any one option";
			error = true;
		{rdelim}

		//this is added to check whether the getmysql path is true and the user has entered the path or not
		if(error != true)
		{ldelim}
			if(document.getElementById("getmysqlpath").value == 1 && trim(formname.server_mysql_path.value) == '')
			{ldelim}
				//alert(document.getElementById("getmysqlpath").value+" Enter the mysql path");
				error_msg = "Please enter the Correct MySQL Path";
				error = true;
			{rdelim}
			else
			{ldelim}
				//alert(document.getElementById("getmysqlpath").value+" MySQL path found");
				error = false;
			{rdelim}
		{rdelim}

		//if there is any error then alert the user and return false;
		if(error == true)
		{ldelim}
			alert(error_msg);
			return false;
		{rdelim}
		else
		{ldelim}
			return true;
		{rdelim}
	{rdelim}
</script>

