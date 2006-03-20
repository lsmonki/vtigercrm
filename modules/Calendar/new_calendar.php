<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">

<html>
<head>
	<title>vtiger CRM 5 - Free, Commercial grade Open Source CRM</title>
	<!--link rel="stylesheet" type="text/css" href="../style.css"-->

	<script language="JavaScript" type="text/javascript" src="modules/Calendar/script.js">	</script>
</head>

<body leftmargin=0 topmargin=0 marginheight=0 marginwidth=0>

<?php 
	$mysel= $_GET['sel'];
	
	if ($mysel=="") { include "calendar_dayview.php"; }
	if ($mysel=="day") { include "calendar_dayview.php"; }
	if ($mysel=="week") { include "calendar_weekview.php"; }
	if ($mysel=="month") { include "calendar_monthview.php"; }
	if ($mysel=="year") { include "yearview.php"; }
	if ($mysel=="share") { include "calendar_share.php"; }

?>
	


