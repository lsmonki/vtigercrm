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
 * Contributor(s): ______________________________________.
 ********************************************************************************/
/*********************************************************************************
 * $Header: /advent/projects/wesat/vtiger_crm/sugarcrm/install/1checkSystem.php,v 1.16 2005/03/08 12:01:36 samk Exp $
 * Description:  Executes a step in the installation process.
 ********************************************************************************/

//get php configuration settings.  requires elaborate parsing of phpinfo() output
?>
<html>
<?php


ob_start();
eval("phpinfo();");
$info = ob_get_contents();
ob_end_clean();

 foreach(explode("\n", $info) as $line) {
           if(strpos($line, "Client API version")!==false)
               $mysql_version = trim(str_replace("Client API version", "", strip_tags($line)));
 }

$current_dir = pathinfo(dirname(__FILE__));
$current_dir = $current_dir['dirname']."/";

$package_dir = $current_dir."packages/5.1.0/optional/";
$handle = opendir($package_dir);
$opt_modules = array();
while($opt_mod = readdir($handle)){
	if($opt_mod[0]!="." && $opt_mod!=""){
		$opt_modules[] = str_replace(".zip","",$opt_mod);
	}
}

ob_start();
phpinfo(INFO_GENERAL);
$string = ob_get_contents();
ob_end_clean();

$pieces = explode("<h2", $string);
$settings = array();

if(isset($_REQUEST['filename'])){
	$file_name = $_REQUEST['filename'];
}

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<title>vtiger CRM 5 - Configuration Wizard - Installation Check</title>
	<link href="include/install/install.css" rel="stylesheet" type="text/css">
</head>

<body class="small cwPageBg" topmargin="0" leftmargin="0" marginheight="0" marginwidth="0">

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
		<td background="include/install/images/topInnerShadow.gif" colspan=2 align=left><img src="include/install/images/topInnerShadow.gif" ></td>

	</tr>
	</table>
	<table border=0 cellspacing=0 cellpadding=10 width=80% align=center>
	<tr>
		<td class="small" bgcolor="#4572BE" align=center>
			<!-- Master display -->
			<table border=0 cellspacing=0 cellpadding=0 width=97%>
			<tr>
				<td width=80% valign=top class="cwContentDisplay" align=center>
				<!-- Right side tabs -->
				    <table cellspacing=0 cellpadding=2 width=95% align=center>
				    <tr>
				    <td align=left colspan=2><img src="include/install/images/confWizInstallCheck.gif" alt="Pre Installation Check" title="Pre Installation Check"><br>
					  </td>
					</tr>
					<tr><td colspan=2><hr noshade size=1></td></tr>
				    <tr>
				    	<td colspan=2>
				    		<table cellpadding="0" cellspacing="1" align=right width="100%" class="level3">
				    			<tr>
				    			<td colspan=2 style="font-size:13;">
				    				<strong>Select Optional Modules to Install :</strong>
				    				<hr size="1" noshade=""/>
				    			</td>
				    			</tr>
				    			<tr >
									<td align=left width=50% valign=top>
										<table cellpadding="5" cellspacing="1" align=right width="100%" border="0">

									<?php
										
										foreach($opt_modules as $index=>$value) {
									?>
											<tr class='level1'>
				        						<td width= "5%" valign=top align="right"><input type="checkbox" id="<?php echo $value; ?>" name="<?php echo $value; ?>" value="<?php echo $value; ?>" onChange='ModuleSelected("<?php echo $value; ?>");'></td>
												<td valign=top ><i><?php echo $value; ?> </i></td>
											</tr>
									<?php
										}
									?>
				       				</table>
								<br>
							</td>
						</tr>
					</table>
				</td>
			</tr>
			<tr valign=top>
				<td align=left >
					<input type="image" src="include/install/images/cwBtnBack.gif" alt="Back" border="0" title="Back" onClick="window.history.back();">
					
					</td>
				<td align=right>
					<form action="install.php" method="post" name="form" id="form">
					<input type="hidden" value="<?php if(isset($selected_modules)) echo $selected_modules; else echo '';?>" id='selected_modules' name='selected_modules' />  
	                <?php echo '<input type="hidden" name="file" value="'.$file_name.'" />'; ?>
					<input type="image" src="include/install/images/cwBtnNext.gif" alt="Next" border="0" title="Next" onClick="submit();">
					</form>
				    </td>
			</tr>
		</table>
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
    	
<script language="javascript">
var selected_modules = '';

function ModuleSelected(module){
	if(document.getElementById(module).checked == true){
		if(selected_modules==''){
			selected_modules = selected_modules+document.getElementById(module).value;
		} else {
			selected_modules = selected_modules+":"+document.getElementById(module).value;
		}
	} else {
		if(selected_modules.indexOf(":"+module+":")>-1){
			selected_modules = selected_modules.replace(":"+module+":",":")
		} else if(selected_modules.indexOf(module+":")>-1){
			selected_modules = selected_modules.replace(module+":","")
		} else if(selected_modules.indexOf(":"+module)>-1){
			selected_modules = selected_modules.replace(":"+module,"")
		}
	}
	document.getElementById('selected_modules').value = selected_modules;
}
</script>
</body>
</html>	
