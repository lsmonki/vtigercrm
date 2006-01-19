<?php
/*********************************************************************************
** The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
*
 ********************************************************************************/


if($error_message != '')
	echo '<h6><font color="purple">'.$error_message.'</font></h6>';

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN">
<html>
<head>
<style>
   h3{margin:0; font-size: 14px;}
   hr{color: #EEE; background-color: #EEE; margin: 0; height:1px; } 
   body { color: #333333; font-size: 11px; font-family: verdana, sans-serif }
   table#perfect         { background-color: #F5F5F5; border: 1px solid #CCC;}
   table#perfect caption { color: yellow; font-size: 110%; font-weight: bold;
                           background-color: black; padding: 3px;
                           margin-left: auto; margin-right: auto }
   table#perfect th      { text-align: right; font-weight: bold }
   table#perfect div     { text-align: center; margin-top: 6px }
   table#perfect span    { float: right; font-size: 65% }
   table#perfect a       { color: darkslategray; text-decoration: none }
   input.click           { cursor: pointer }
</style>
</head>
<body>


<form method=post action="send_data.php">
<input type="hidden" name="create" value="contact">
<table width="50%" align="center">
	<tr>
		<td><h3>Contact Details</h3></td>
		<td width="70%"><hr width="100%"></td>
	</tr>
</table>

<table cellspacing="0" cellpadding="5" border="0" id="perfect" width="50%" align="center">
		<tr><th>Last Name:<font color="#CC0000"><sup>*</sup></font> </th>
		      <td><input type=text name=contact_lastname size=30 value="<?php echo $_POST['contact_lastname']?>"></td>
		</tr>
		<tr><th>First Name:</th>
		      <td><input type=text name=contact_firstname size=30 value="<?php echo $_POST['contact_firstname']?>"></td>
		</tr>
		<tr><th>E-Mail Id:</th>
		      <td><input type=text name=contact_email size=30 value="<?php echo $_POST['contact_email']?>"></td>
		</tr>
		<tr><th>Phone:</th>
		      <td><input type=text name=contact_phone size=30 value="<?php echo $_POST['contact_phone']?>"></td>
		</tr>
		<tr><th>Department:</th>
		      <td><input type=text name=contact_department size=30 value="<?php echo $_POST['contact_department']?>"></td>
		</tr>
		<tr><th>Description:</th>
			<td><textarea name=contact_description rows=2 cols=23><?php echo $_POST['contact_description']?></textarea></td>
		</tr>

   <tr><td align="center" colspan="2"><input type=submit value="Submit" class=click></td></tr>
   </table>
</form>

</body>
</html>
<?php


?>
