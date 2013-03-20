{*<!--
/*********************************************************************************
** The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
*********************************************************************************/
-->*}

{if $HASGROUPBY }

    <table style="border: 1px solid rgb(0, 0, 0);" align="center" cellpadding="0" cellspacing="0" width="100%">
	    <tbody>
		    <tr>
			    <td style="background-repeat: repeat-y;" background="{'report_btn.gif'|@vtiger_imageurl:$THEME}" width="16"></td>
			    <td>
				    <table border=0 cellspacing=1 cellpadding=0 width="100%" class="lvtBg">
			<tr>
						    <td> {$PIECHART} </td>
						    <td> {$BARCHART} </td>
					    </tr>
				    </table>
			    </td>
			    <td style="background-repeat: repeat-y;" background="{'report_btn.gif'|@vtiger_imageurl:$THEME}" width="16"></td>
		    </tr>
	    </tbody>
    </table>

    {* For performance reason adding chart to home-page disabled for now *}
    {*
    <table align="center" border="0" cellpadding="5" cellspacing="0" width="100%" class="mailSubHeader">
	    <tbody>
		    <tr>
			    <td align="left"  width="100%"><input class="crmbutton small create" style="background:#E85313" id="addChartstodashboard" name="addChartstodashboard" value="{'LBL_ADD_CHARTS'|@getTranslatedString:$MODULE}" type="button" onClick="showAddChartPopup();" title="{'LBL_ADD_CHARTS'|@getTranslatedString:$MODULE}"></td>
		    </tr>
	    </tbody>
    </table>
    *}
{else}

    <table style="border: 1px solid rgb(0, 0, 0);" align="left" cellpadding="0" cellspacing="0" width="100%">
	    <tbody>
		    <tr>
			    <td style="background-repeat: repeat-y;" background="{'report_btn.gif'|@vtiger_imageurl:$THEME}" width="16"></td>
			    <td>
				    <table border=0 cellspacing=1 cellpadding=0 width="100%" class="lvtBg">
			<tr>
						    <td align="left"> No grouping condition found.</td>
					    </tr>
				    </table>
			    </td>
			    <td style="background-repeat: repeat-y;" background="{'report_btn.gif'|@vtiger_imageurl:$THEME}" width="16"></td>
		    </tr>
	    </tbody>
    </table>

{/if}