<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<?

global $theme;
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";


?>



<html>
<head>
<title>Untitled Document</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>
<body>
<form action="index.php">
<div class="moduleTitle hline">Users : Email Notifications</div>
<br>
The following is the list of notifications that are activated automatically 
when the corresponding event has happened.
<br>
<br>
<br>
<br>
  <table alignment="center" width="50%" border="0" cellspacing="0" cellpadding="0" class="FormBorder">
    <tr> 
      <td class="moduleListTitle"><div align="center"><b>Active   </b></div></td>
      <td class="moduleListTitle" height="25"><b>     Notification</b></td>
      <td class="moduleListTitle"><b>Description</b></td>
    </tr>
    <tr class='oddListRow'> 
      <td valign="top" nowrap><div align="center"><img src="yes.gif" alt="" width="13" height="12" align="absmiddle"></div></td>
      <td height='25' valign="top" nowrap><a href="index.php?module=Users&action=notify_detail">Delay Notification</a></td>
      <td height='25' valign="top">Notifying the Task Owners when a task is delayed</td>
    </tr>
    <tr class='oddListRow'> 
      <td valign="top" nowrap><div align="center"><img src="yes.gif" alt="" width="13" height="12" align="absmiddle"></div></td>
      <td height='25' valign="top" nowrap><a href="index.php?module=Users&action=notify_detail">Big Deal Alert</a></td>
      <td height='25' valign="top">Notification when a big money deal has been achieve</td>
    </tr>
    <tr class='evenListRow'> 
      <td valign="top" nowrap><div align="center"><img src="yes.gif" alt="" width="13" height="12" align="absmiddle"></div></td>
      <td height='25' valign="top" nowrap><a href=#></a><a href="index.php?module=Users&action=notify_detail">Tickets Creation</a></td>
      <td height='25' valign="top">Internal notification to Ticket Owner and external notification to the Customer when a new ticket is created</td>
    </tr>
    <tr class='evenListRow'> 
      <td valign="top" nowrap><div align="center"><img src="yes.gif" alt="" width="13" height="12" align="absmiddle"></div></td>
      <td height='25' valign="top" nowrap><a href=#></a><a href="index.php?module=Users&action=notify_detail">Tickets Closed</a></td>
      <td height='25' valign="top">External notification to Customers with resolution 
        when a ticket is closed</td>
    </tr>
  </table>
</form>
</body>
</html>
