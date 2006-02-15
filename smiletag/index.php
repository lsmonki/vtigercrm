<html>
<head>
<title>SmileTag</title>
<script type="text/javascript" language="JavaScript">
<!--
	var smiletagURL = "http://localhost:90/smiletag/";
//-->
</script>
<script type="text/javascript" language="JavaScript" src="smiletag-script.js"></script>
</head>

<body>

<table border="0" cellpadding="0" cellspacing="0">
     <tr>
          <td valign="top" >
      	  <iframe name="iframetag" marginwidth="0" marginheight="0" src="view.php" width="190" height="300">
			Your Browser must support IFRAME to view
			this page correctly
		  </iframe>
		  </td>
     </tr>
     <tr>
          <td>
 			<form name="smiletagform" method="post" action="post.php" target="iframetag"><br />
              Name<br /><input type="text" name="name"/><br />
              URL or Email<br /><input type="text" name="mail_or_url" value="http://" /><br />
              Message<br /><textarea name="message_box" rows="3" cols="20"></textarea><br />
              <input type="hidden" name="message" value="" />
              <input type="submit" name="submit" value="Tag!" onclick="clearMessage()" /> 
		  <input type="reset"  name="reset" value="Reset" /><br />
            </form>
	       </td>
        </tr>
</table>

</body>

</html>
