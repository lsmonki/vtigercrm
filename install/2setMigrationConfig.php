<?php
/*********************************************************************************
 * The contents of this file are subject to the SugarCRM Public License Version 1.1.2
 * ("License"); You may not use this file except in compliance with the
 * License. You may obtain a copy of the License at http://www.sugarcrm.com/SPL
 * Software distributed under the License is distributed on an  "AS IS"  basis,
 * WITHOUT WARRANTY OF ANY KIND, either express or implied. See the License for
 * the specific language governing rights and limitations under the License.
 * The Original Code is: SugarCRM Open Source
 * The Initial Developer of the Original Code is SugarCRM, Inc.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.;
 * All Rights Reserved.
 lpha2* Contributor(s): ______________________________________.
 ********************************************************************************/
/*********************************************************************************
 * $Header: /advent/projects/wesat'vtiger_crm/sugarcrm/install/2setConfig.php,v 1.41 2005/04/29 06:44:13 samk Exp $
 * Description:  Executes a step in the installation process.
 ********************************************************************************/

// TODO: deprecate connection.php file
//require_once("connection.php");

// TODO: introduce MySQL port as parameters to use non-default value 3306
//$sock_path=":" .$mysql_port;
$hostname = $_SERVER['SERVER_NAME'];

// TODO: introduce Apache port as parameters to use non-default value 80
//$web_root = $_SERVER['SERVER_NAME']. ":" .$_SERVER['SERVER_PORT'].$_SERVER['PHP_SELF'];
//$web_root = $hostname.$_SERVER['PHP_SELF'];
//$web_root = $HTTP_SERVER_VARS["HTTP_HOST"] . $HTTP_SERVER_VARS["REQUEST_URI"];
$web_root = ($_SERVER["HTTP_HOST"]) ? $_SERVER["HTTP_HOST"]:$_SERVER['SERVER_NAME'].':'.$_SERVER['SERVER_PORT'];
$web_root .= $_SERVER["REQUEST_URI"];
$web_root = str_replace("/install.php", "", $web_root);
$web_root = "http://".$web_root;

$current_dir = pathinfo(dirname(__FILE__));
$current_dir = $current_dir['dirname']."/";
$cache_dir = "cache/";

if (is_file("config.php") && is_file("config.inc.php")) {
	require_once("config.inc.php");
	session_start();
	
	$cur_dir_path = false;
	if(!isset($dbconfig['db_hostname']) || $dbconfig['db_status']=='_DB_STAT_') {
		$cur_dir_path = true;
	}
	if(isset($upload_maxsize))
	$_SESSION['upload_maxsize'] = $upload_maxsize;

	if(isset($allow_exports))
	$_SESSION['allow_exports'] = $allow_exports;

	if(isset($disable_persistent_connections))
	$_SESSION['disable_persistent_connections'] = $disable_persistent_connections;

	if(isset($default_language))
	$_SESSION['default_language'] = $default_language;

	if(isset($translation_string_prefix))
	$_SESSION['translation_string_prefix'] = $translation_string_prefix;

	if(isset($default_charset))
	$_SESSION['default_charset'] = $default_charset;

	if(isset($languages)) {
		// need to encode the languages in a way that can be retrieved later
		$language_keys = Array();
		$language_values = Array();

		foreach($languages as $key=>$value) {
			$language_keys[] = $key;
			$language_values[] = $value;
		}
		$_SESSION['language_keys'] = urlencode(implode(",",$language_keys));
		$_SESSION['language_values'] = urlencode(implode(",",$language_values));
	}
													
	global $dbconfig;

	if (isset($_REQUEST['db_name']))
	$db_name = $_REQUEST['db_name'];
	elseif (isset($dbconfig['db_name']) && $dbconfig['db_name']!='_DBC_NAME_')
	$db_name = $dbconfig['db_name'];
	else
	$db_name = 'vtigercrm510';

	if(isset($_REQUEST['root_directory'])) $root_directory = $_REQUEST['root_directory'];
	else $root_directory = $current_dir;

	if(isset($_REQUEST['source_directory'])) $source_directory = $_REQUEST['source_directory'];
	else $source_directory = '';
	    
	if (isset($_REQUEST['cache_dir']))
		$cache_dir= $_REQUEST['cache_dir'];

	if (isset($_REQUEST['mail_server']))
		$mail_server= $_REQUEST['mail_server'];

	if (isset($_REQUEST['mail_server_username']))
		$mail_server_username= $_REQUEST['mail_server_username'];

	if (isset($_REQUEST['mail_server_password']))
		$mail_server_password= $_REQUEST['mail_server_password'];

	}
	else {
		!isset($_REQUEST['db_name']) ? $db_name = "vtigercrm510" : $db_name = $_REQUEST['db_name'];
		!isset($_REQUEST['root_directory']) ? $root_directory = $current_dir : $root_directory = stripslashes($_REQUEST['root_directory']);
		!isset($_REQUEST['source_directory']) ? $source_directory = "" : $source_directory = stripslashes($_REQUEST['source_directory']);
		!isset($_REQUEST['cache_dir']) ? $cache_dir = $cache_dir : $cache_dir = stripslashes($_REQUEST['cache_dir']);
		!isset($_REQUEST['mail_server']) ? $mail_server = $mail_server : $mail_server = stripslashes($_REQUEST['mail_server']);
		!isset($_REQUEST['mail_server_username']) ? $mail_server_username = $mail_server_username : $mail_server_username = stripslashes($_REQUEST['mail_server_username']);
		!isset($_REQUEST['mail_server_password']) ? $mail_server_password = $mail_server_password : $mail_server_password = stripslashes($_REQUEST['mail_server_password']);
	}
		!isset($_REQUEST['check_createdb']) ? $check_createdb = "" : $check_createdb = $_REQUEST['check_createdb'];
		!isset($_REQUEST['root_user']) ? $root_user = "" : $root_user = $_REQUEST['root_user'];
		!isset($_REQUEST['root_password']) ? $root_password = "" : $root_password = $_REQUEST['root_password'];
		!isset($_REQUEST['create_utf8_db'])? $create_utf8_db = "true" : $create_utf8_db = $_REQUEST['create_utf8_db'];
		// determine database options
		$db_options = array();
		if(function_exists('mysql_connect')) {
			$db_options['mysql'] = 'MySQL';
		}
		if(function_exists('pg_connect')) {
			$db_options['pgsql'] = 'Postgres';
		}
	include("modules/Migration/versions.php");
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<title>vtiger CRM 5 - Configuration Wizard - System Configuration</title>
	<link href="include/install/install.css" rel="stylesheet" type="text/css">
	<link href="include/install/install.css" rel="stylesheet" type="text/css">
</head>

<body class="small cwPageBg" topmargin="0" leftmargin="0" marginheight="0" marginwidth="0">
<style>
	.hide_tab{display:none;}
	.show_tab{}
</style>
<script language="JavaScript" type="text/javascript" src="include/scriptaculous/prototype.js"></script>
<script type="text/javascript" language="Javascript">

	function fnShow_Hide(){
		var sourceTag = document.getElementById('check_createdb').checked;
		if(sourceTag){
			document.getElementById('root_user').className = 'show_tab';
			document.getElementById('root_pass').className = 'show_tab';
			document.getElementById('create_db_config').className = 'show_tab';
			document.getElementById('root_user_txtbox').focus();
		}
		else{
			document.getElementById('root_user').className = 'hide_tab';
			document.getElementById('root_pass').className = 'hide_tab';
			document.getElementById('create_db_config').className = 'hide_tab';
		}
	}

function trim(s) {
        while (s.substring(0,1) == " ") {
                s = s.substring(1, s.length);
        }
        while (s.substring(s.length-1, s.length) == ' ') {
                s = s.substring(0,s.length-1);
        }

        return s;
}

function verify_data(form) {
	var isError = false;
	var errorMessage = "";
	// Here we decide whether to submit the form.
	if (trim(form.source_directory.value) =='') {
		isError = true;
		errorMessage += "\n path";
		form.source_directory.focus();
	}
	if (trim(form.user_name.value) =='') {
		isError = true;
		errorMessage += "\n username";
		form.user_name.focus();
	}
	if(form.old_version.value == ""){
		alert("Please Select Previous Insallation Version");
		form.old_version.focus();
		return false;
	}
	<?php
		if($cur_dir_path == true){
	?>					
			if (trim(form.db_name.value) =='') {
				isError = true;
				errorMessage += "\n database name";
				form.db_name.focus();
			}
			if(document.getElementById('check_createdb').checked == true)
			{
				if (trim(form.root_user.value) =='') {
					isError = true;
					errorMessage += "\n root username";
					form.root_user.focus();
				}
			}
	<?php
		} else {
	?>					
			if(form.new_db.checked){
				if (trim(form.db_name.value) =='') {
					isError = true;
					errorMessage += "\n database name";
					form.db_name.focus();
				}
				if(document.getElementById('check_createdb').checked == true)
				{
					if (trim(form.root_user.value) =='') {
						isError = true;
						errorMessage += "\n root username";
						form.root_user.focus();
					}
				}
			}
	<?php
		}
	?>					
	// Here we decide whether to submit the form.
	if (isError == true) {
		alert("Missing required fields:" + errorMessage);
		return false;
	}
return true;
}

function radio_checked(){
	if(document.installform.new_db.checked){
		document.installform.new_db_name.removeAttribute('disabled');
		document.installform.check_createdb.removeAttribute('disabled');
	}else{
		document.installform.new_db_name.disabled =true;
		document.installform.check_createdb.disabled = true;
	}
}
function migrate(){
	var oDivfreeze = $('divId');
	oDivfreeze.style.height = document.documentElement['clientHeight'] + 'px';
	$('divId').style.display = 'block';
	
	var source_path = document.getElementById("source_directory").value;
	<?php
		if($cur_dir_path == true){
	?>					
			var db_name = document.getElementById("db_name").value;
			var check_createdb =  document.getElementById("check_createdb").value;
			var root_user =  document.getElementById("root_user_txtbox").value;
			var root_password =  document.getElementById("root_password").value;
			var create_utf8_db =  document.getElementById("create_utf8_db").value;
	<?php
		} else {
	?>					
		if(document.installform.new_db.checked){
			if(document.getElementById("new_db_name").value=='')
				var db_name = document.getElementById("db_name").value;
			else
				var db_name = document.getElementById("new_db_name").value;
			var check_createdb =  document.getElementById("check_createdb").value;
			var root_user =  document.getElementById("root_user_txtbox").value;
			var root_password =  document.getElementById("root_password").value;
			var create_utf8_db =  document.getElementById("create_utf8_db").value;
		} else {
			var db_name = document.getElementById("existing_db_name").value;
		}
	<?php
		}
	?>					

	var user_name = document.getElementById("user_name").value;
	var old_version = document.getElementById("old_version").value;
	var user_pwd = document.getElementById("password").value;
	var site_URL =  document.getElementById("site_url").value;
	var root_directory =  document.getElementById("root_directory").value;
	url = '&old_version='+old_version+'&check_createdb='+check_createdb+'&root_user='+root_user+'&root_password='+root_password+'&create_utf8_db='+create_utf8_db+'&root_directory='+root_directory+'&site_URL='+site_URL;
	new Ajax.Request(
		'migrate.php',
		{queue: {position: 'end', scope: 'command'},
			method: 'post',
			postBody: 'source_path='+source_path+'&db_name='+db_name+'&user_name='+user_name+'&user_pwd='+user_pwd+url,
			onComplete: function(response) {
 					var str = response.responseText
					if(str.indexOf('NO_CONFIG_FILE') > -1){
						alert("The Source you have specified doesn't have a config file. \n Please provide a proper Source.'");
						$('divId').style.display = 'none';
						return false;
					}
					else if(str.indexOf('NO_USER_PRIV_DIR') > -1){
						alert("The Source specified doesn't have a user privileges directory. \n Please provide a proper Source.'");
						$('divId').style.display = 'none';
						return false;
					}
					else if(str.indexOf('NO_SOURCE_DIR') > -1){
						alert("The Source specified doesn't seem to be existing. \n Please provide a proper Source.'");
						$('divId').style.display = 'none';
						return false;
					}
					else if(str.indexOf('NO_STORAGE_DIR') > -1){
						alert("The Source specified doesn't have a Storage directory. \n Please provide a proper Source.'");
						$('divId').style.display = 'none';
						return false;
					}
					else if(str.indexOf('NOT_VALID_USER') > -1){
						alert("Not a valid user. Provide an Admin user login details'");
						$('divId').style.display = 'none';
						return false;
					}
					else if(str.substring(0,2) == 'ERR'){
						alert(str);
						$('divId').style.display = 'none';
						return false;
					}
					else if(str.substring(0,6) == 'FAILURE'){
						alert(str);
						$('divId').style.display = 'none';
						return false;
					}
					else
					{
						
						if(trim(str)!='QF: ') {
							location.href ='install.php?source_directory='+source_path+'&root_directory='+root_directory+'&file=3MigrationComplete.php';
						}else{
							str=str.replace('QF: ','');
							str=str.replace(/:: /gi,'\n\n');alert('failure');
							placeAtCenter($('failedqueries'));
							$('failedqueries').style.display = 'block';
							$('queries').value = str;
						} 
						$('divId').style.display = 'none';
						return true;
					}
			}
		}
	);
	return false;
}

function redirect(){
	var source_path = document.getElementById("source_directory").value;
	var root_directory =  document.getElementById("root_directory").value;
	location.href ='install.php?source_directory='+source_path+'&root_directory='+root_directory+'&file=3MigrationComplete.php';
	
}
function placeAtCenter(node){
	var centerPixel = getViewPortCenter()
	node.style.position = "absolute";
	var point = getDimension(node);
	
	node.style.top = centerPixel.y - point.y/2 +"px";
	node.style.right = centerPixel.x - point.x/2 + "px";
}

function getDimension(node){
	
	var ht = node.offsetHeight;
	var wdth = node.offsetWidth;
	var nodeChildren = node.getElementsByTagName("*");
	var noOfChildren = nodeChildren.length;
	for(var index =0;index < noOfChildren;++index){
		ht = Math.max(nodeChildren[index].offsetHeight, ht);
		wdth = Math.max(nodeChildren[index].offsetWidth,wdth);
	}
	return {x: wdth,y: ht};
}

function getViewPortCenter(){
	
	var height;
	var width;
	
	if(typeof window.pageXOffset != "undefined"){
		height = window.innerHeight/2;
		width = window.innerWidth/2;
		height +=window.pageYOffset;
		width +=window.pageXOffset;
	}else if(document.documentElement && typeof document.documentElement.scrollTop != "undefined"){
		height = document.documentElement.clientHeight/2;
		width = document.documentElement.clientWidth/2;
		height += document.documentElement.scrollTop;
		width += document.documentElement.scrollLeft;
	}else if(document.body && typeof document.body.clientWidth != "undefined"){
		var height = window.screen.availHeight/2;
		var width = window.screen.availWidth/2;
		height += document.body.clientHeight;
		width += document.body.clientWidth;
	}
	return {x: width,y: height};
}

// end hiding contents from old browsers  
</script>

<br>
	<!-- Table for cfgwiz starts -->

	<table border=0 cellspacing=0 cellpadding=0 width=80% align=center>
	<tr>
		<td class="cwHeadBg" align=left><img src="include/install/images/configwizard.gif" alt="Configuration Wizard" hspace="20" title="Configuration Wizard"></td>
		<td class="cwHeadBg1" align=right><img src="include/install/images/vtigercrm5.gif" alt="vtiger CRM 5" title="vtiger CRM 5"></td>
		<td class="cwHeadBg1" width=2%></td>
	</tr>
	</table>
	<table border=0 cellspacing=0 cellpadding=0 width=80% align=center>
	<tr>
		<td background="include/install/images/topInnerShadow.gif" align=left><img src="include/install/images/topInnerShadow.gif" ></td>

	</tr>
	</table>
	<table border=0 cellspacing=0 cellpadding=10 width=80% align=center>
	<tr>
		<td class="small" bgcolor="#4572BE" align=center>
			<!-- Master display -->
			<table border=0 cellspacing=0 cellpadding=0 width=97%>
			<tr>
				<td width=80% valign=top class="cwContentDisplay" align=left>
				<!-- Right side tabs -->
				    <form action="javascript: void(0); " method="post" name="installform" id="form" name="setConfig" id="form">
				    <input name="action" type="hidden" value='' />
				    <table border=0 cellspacing=0 cellpadding=10 width=100%>
				    <tr><td class=small align=left><img src="include/install/images/confWizSysConfig.gif" alt="System Configuration" title="System Configuration"><br>
					  <hr noshade size=1></td></tr>
				    <tr>
					<td align=left class="small" style="padding-left:10px">
			
		<table width="95%" cellpadding="2" align=center border="0" cellspacing="1" class="level3"><tbody>
			<tr>
				<td colspan=2><strong>Previous Installation Information</strong><hr size="1" noshade=""/></td>
			</tr>
			<tr>
				<td  nowrap width = 25%>Previous Installation Path<sup><font color=red>*</font></sup></td>
				<td align="left">
					<?php
						if($cur_dir_path == true){
					?>					
					<input class="dataInput" type="text" name="source_directory" id="source_directory" value="<?php if (isset($source_directory)) echo "$source_directory"; ?>" size="40" /> 
					<?php
						} else {
							echo $root_directory;
					?>					
					<input class="dataInput" type="hidden" name="source_directory" id="source_directory" value="<?php if (isset($root_directory)) echo "$root_directory"; ?>" size="40" /> 
					<?php
						}
					?>					
				</td>
			</tr>
			<tr>
				<td width = 25% >Previous Installation Version<sup><font color=red>*</font></sup></td>
				<td align="left">
					<select name='old_version' id='old_version'>
						<?php
							if(!isset($_SESSION['VTIGER_DB_VERSION'])){ 
								echo "<option value='' selected>--SELECT--</option>";
							} else {
								echo "<option value=''>--SELECT--</option>";
							}
								
							foreach($versions as $index=>$value){
								if($value==$_SESSION['VTIGER_DB_VERSION'] && isset($_SESSION['VTIGER_DB_VERSION']))
									echo "<option value='$index' selected>$value</option>";
								else
									echo "<option value='$index'>$value</option>"; 
							}
						?>
					</select>
					</select>
				</td>
			</tr>
			<tr>
				<td width = 25% >Admin Username<sup><font color=red>*</font></sup></td>
				<td align="left"><input class="dataInput" type="text" name="user_name" id="user_name" value="<?php if (isset($user_name)) echo "$user_name"; ?>" size="40" /> </td>
			</tr>
			<tr>
				<td width = 25%>Admin Password<sup><font color=red></font></sup></td>
				<td align="left"><input class="dataInput" type="password" name="password" id="password" value="" size="40" /> </td>
			</tr>
		</table>
		<br>
		<table width="95%" cellpadding="2"  cellspacing="1" border="0" align=center class='level3'><tbody>
			<tr><td colspan=2><strong>Database Configuration</strong><hr size="1" noshade=""/></td></tr>
				<?php
					if($cur_dir_path == true){
				?>					
					<tr>
		               <td nowrap width=25%>New Database Name<sup><font color=red>*</font></sup></td>
		               <td align="left" nowrap><input type="text" class="dataInput" name="db_name" id="db_name" value="<?php if (isset($db_name)) echo "$db_name"; ?>" />&nbsp;
				       <?php if($check_createdb == 'on')
					       {?>
					       <input name="check_createdb" type="checkbox" id="check_createdb" checked onClick="fnShow_Hide()"/>
					       <?php }else{?>
						       <input name="check_createdb" type="checkbox" id="check_createdb" onClick="fnShow_Hide()" />
					       <?php } ?>
					       &nbsp;Create Database (will drop the database if exists)</td>
					</tr>
				<?php
					} else {
				?>					
					<tr>
						<td nowrap width = 25%><input type='radio' value='E' onchange='radio_checked();' name='database' checked /> Upgrade Existing Database</td>
		               	<td align="left"><input type="hidden" class="dataInput" name="existing_db_name" id="existing_db_name" value="<?php if (isset($db_name)) echo "$db_name"; ?>" /><?php  echo "$db_name"; ?></td>
					</tr>
					<tr>
						<td nowrap width = 25%><input type='radio' value='C' onchange='radio_checked();' id='new_db' name='database'/>Use Other Database</td>
		               <td align="left"><input type="text" class="dataInput" name="new_db_name" id="new_db_name" value="" disabled />&nbsp;
				       <?php if($check_createdb == 'on')
					       {?>
					       <input disabled name="check_createdb" type="checkbox" id="check_createdb" disabled checked onClick="fnShow_Hide()"/>
					       <?php }else{?>
						       <input name="check_createdb" type="checkbox" id="check_createdb" disabled onClick="fnShow_Hide()" />
					       <?php } ?>
					       &nbsp;Create Database (will drop the database if exists)</td>
					</tr>
				<?php
					}
				?>					
			<tr id="root_user" class="hide_tab">
				<td nowrap width="25%">Root Username<sup><font color="red">*</font></sup></td>
				<td align="left"><input class="dataInput" name="root_user" id="root_user_txtbox" value="<?php echo $root_user;?>" type="text"></td>
			</tr>
			<tr id="root_pass" class="hide_tab">
				<td nowrap width="25%">Root Password</td>
				<td align="left"><input class="dataInput" name="root_password" id="root_password" value="<?php echo $root_password;?>" type="password"></td>
			</tr>
			<tr id="create_db_config" class="hide_tab">
				<td nowrap width="25%">UTF-8 Support</td>
				<td align="left"><input name="create_utf8_db" type="checkbox" id="create_utf8_db" <?php if($create_utf8_db == 'true') echo "checked"; ?> /> DEFAULT CHARACTER SET utf8, DEFAULT COLLATE utf8_general_ci</td>
			</tr>
			<input name="site_url" type="hidden" id="site_url" value = '<?php echo $web_root; ?>' />
			<input class="dataInput" type="hidden" name="root_directory" id="root_directory" value="<?php if (isset($root_directory)) echo "$root_directory"; ?>" size="40" /> 
		</table>
			
			<br>
			
		  <!-- User Verification -->
		<!-- System Configuration -->
		</td>
		</tr>
		<tr>
				<td align=center>
					<input type="hidden" name="file" value="3MigrationComplete.php">
					<input type="image" src="include/install/images/cwBtnMigrate.gif" id="starttbn" alt="Copy files and Migrate" border="0" title="Next" onClick=" if(verify_data(installform))migrate();">
				</td>
			</tr>
		</table>
		</form>
	</td>
		</tr>
	</table>
	<!-- Master display stops -->
	<br>
	</td>
	</tr>
	</table>
	<table border=0 cellspacing=0 cellpadding=0 width=80% align=center>
	<tr>

		<td background="include/install/images/bottomGradient.gif"><img src="include/install/images/bottomGradient.gif"></td>
	</tr>
	</table>
	<table border=0 cellspacing=0 cellpadding=0 width=80% align=center>
	<tr>
		<td align=center><img src="include/install/images/bottomShadow.jpg"></td>
	</tr>
	</table>	
	<table border=0 cellspacing=0 cellpadding=0 width=80% align=center>

      	<tr>
        	<td class=small align=center> <a href="http://www.vtiger.com" target="_blank">www.vtiger.com</a></td>
      	</tr>
    	</table>
<script>fnShow_Hide();</script>
<div id="divId" class="veil_new" style="position:absolute;width:100%;display:none;top:0px;left:0px;background-color:#FFFFFF;filter: alpha(Opacity=75);opacity:0.75;border: solid 1px gray;">
<table border="5" cellpadding="0" cellspacing="0" align="center" style="vertical-align:middle;width:100%;height:900px;">
<tbody><tr>
		<td class="big" align="center" style="font-size:20px;">
		    <img src="include/install/images/loading.gif"><br>
		    <font color='#575864'><strong>Migraton in Progress. Please Wait...</strong></font>
		</td>
	</tr>
</tbody>
</table>
</div>
<div id="failedqueries" class="veil_new" align="center" style="vertical-align:middle;position:absolute;width:80%;height:70%;display:none;top:0px;left:150px;background-color:#FFFFFF;">
<table border="5" cellpadding="0" cellspacing="0" align="center" style="width:100%;height:100%;font-size:12px;">
<tbody>
<th>
Queries Failed:
</th>
<tr>
		<td class="big" align="center" >
		    <textarea rows=20 cols=100 id='queries' name='queries' value=''></textarea>
		</td>
	</tr>
	<tr>
		<td class="big" align="center" >
		    <input type='button' class='crmbutton small cancel' value='Close' alt='Close' onClick='redirect();' />
		</td>
	</tr>
</tbody>
</table>
</div>
</body>
</html>
