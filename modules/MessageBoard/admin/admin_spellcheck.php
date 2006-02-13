<?php

if ( !empty($setmodules) )
{
        $filename = basename(__FILE__);
    //    $forum_module['Forums']['Spellcheck'] = $filename;
        return;
}
if (!isset($phpEx)) $phpEx = "php";
?><html>
<head>
</head>
<body bgcolor="#E5E5E5" text="#000000">
<script language="javascript">
<!--
document.location.href="../spelling/spell_admin<?php echo ".".$phpEx; ?>"
//-->
</script>
</body>
</html>
