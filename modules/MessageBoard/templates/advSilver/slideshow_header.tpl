<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html dir="{S_CONTENT_DIRECTION}">
<head>
<meta http-equiv="Content-Type" content="text/html; charset={S_CONTENT_ENCODING}" />
<meta http-equiv="Content-Style-Type" content="text/css" />
<meta http-equiv="Page-Enter" content="blendTrans(duration=0.25)" />

{META}
{NAV_LINKS}
<title>{SITENAME} - {PAGE_TITLE}</title>
<!-- link rel="stylesheet" href="templates/advSilver/{T_HEAD_STYLESHEET}" type="text/css" -->
<style type="text/css">
<!--
/* General page style. The scroll bar colours only visible in IE5.5+ */

.imageborder { color: gray; border-color: gray; }
.imageload { background-color: {T_TR_COLOR2}; }

/* General text */
.slideshow { font-family: {T_FONTFACE1}; font-size: {T_FONTSIZE2}px; color: {T_BODY_TEXT}; }
a.slideshow{ color: {T_BODY_LINK}; text-decoration: none; }
a.slideshow.visited { color: {T_BODY_VLINK}; }
a.slideshow:hover { color: {T_BODY_HLINK}; }

/* Form elements */
select {
	color: {T_BODY_TEXT};
	font: normal {T_FONTSIZE1}px {T_FONTFACE1};
	background-color: {T_TD_COLOR2};
}

input {
	font: normal {T_FONTSIZE1}px {T_FONTFACE1};
}
-->
</style>

<!-- BEGIN switch_enable_pm_popup -->
<script language="Javascript" type="text/javascript">
<!--
	if ( {PRIVATE_MESSAGE_NEW_FLAG} )
	{
		window.open('{U_PRIVATEMSGS_POPUP}', '_phpbbprivmsg', 'HEIGHT=225,resizable=yes,WIDTH=400');
	}
//-->
</script>
<!-- END switch_enable_pm_popup -->
</head>

<body bgcolor="{T_TD_COLOR2}" text="{T_BODY_TEXT}" link="{T_BODY_LINK}" vlink="{T_BODY_VLINK}" topmargin="0" leftmargin="0" marginwidth="0" marginheight="0"
onLoad="PageLoaded = true; if (BoxChecked != -999) CountDown(0)"
onClick="if (BoxChecked == true) BoxChecked = false;"
onBeforeUnload="if (BoxChecked == true) BoxChecked = 2;">