<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">

<html>
<head>
	<title>vtiger CRM 5 - Free, Commercial grade Open Source CRM</title>
	<!--link rel="stylesheet" type="text/css" href="../style.css"-->

	<script src="modules/Calendar/script.js">	</script>
</head>

<body leftmargin=0 topmargin=0 marginheight=0 marginwidth=0>

<? 
/*
	include "basicIncludes.php";
	include "Tabs.php";
	include "header.php";
?>

<?
*/ 
	$mysel= $_GET['sel'];
	
	if ($mysel=="") { include "dayview.php"; }
	if ($mysel=="day") { include "dayview.php"; }
	if ($mysel=="week") { include "weekview.php"; }
	if ($mysel=="month") { include "monthview.php"; }
	if ($mysel=="year") { include "yearview.php"; }

?>
	


