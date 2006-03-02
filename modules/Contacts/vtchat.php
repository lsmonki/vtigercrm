<?php ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<meta content="text/html; charset=iso-8859-1" http-equiv="content-type"/>
<meta name="author" content="rolosworld@gmail.com"/>
<meta http-equiv="expires" content="-1"/>
<meta http-equiv="pragma" content="no-cache"/>

<title>Ajax Css-Popup chat</title>

<!-- NEEDED SCRIPTS  -->
<script type="text/javascript" src="modules/Contacts/js/ajax.js"></script>
<script type="text/javascript" src="modules/Contacts/js/dom-drag_p.js"></script>
<script type="text/javascript" src="modules/Contacts/js/css-window_p.js"></script>
<script type="text/javascript" src="modules/Contacts/js/chat.js"></script>
<!-- /NEEDED SCRIPTS -->


<script type="text/javascript">
<!--
function showPopup()
{
  var conf = new Array();
  conf["dt"] = 1000;
  conf["width"] = "400px";
  conf["height"] = "300px";
  conf["ulid"] = "uli";
  conf["pchatid"] = "chat";


  // USED TO INITIALIZE THE SESSION, I SUGGEST CALLING THIS ON BODY onload
  //   Chat(<conf array>);
  // NOTICE THE ChatStuff IS THE NAME OF THE ABOVE FUNCTION!!!
  var mychat = new Chat(conf);

}

-->
</script>

<!-- CSS classes for the popups -->
<link rel="stylesheet" type="text/css" href="modules/Contacts/chat.css"/>

</head>

<body onload="showPopup();" style="background-image:url(modules/Contacts/imgs/site_bg.gif);color:#ffffff;">


<!-- THIS IS NEEDED FOR THE USERS LIST TO APPEAR, -->
<!-- THE id CAN BE CHANGED, BUT HAS TO BE PASSED TO UList() -->
<ul id="uli"></ul>

<!-- THIS IS NEEDED FOR THE POPUPS TO APPEAR -->
<div id="chat"></div>

<!-- THIS IS NEEDED FOR DEBUG MSG'S TO APPEAR -->
<div id="debug"></div>
</body>
</html>
