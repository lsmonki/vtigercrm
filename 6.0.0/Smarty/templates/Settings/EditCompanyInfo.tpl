{*<!--
/*********************************************************************************
  ** The contents of this file are subject to the vtiger CRM Public License Version 1.0
   * ("License"); You may not use this file except in compliance with the License
   * The Original Code is:  vtiger CRM Open Source
   * The Initial Developer of the Original Code is vtiger.
   * Portions created by vtiger are Copyright (C) vtiger.
   * All Rights Reserved.
  *
 ********************************************************************************/
-->*}
<script language="JAVASCRIPT" type="text/javascript" src="include/js/smoothscroll.js"></script>
<script language="JavaScript" type="text/javascript" src="include/js/menu.js"></script>
<br>
<table align="center" border="0" cellpadding="0" cellspacing="0" width="98%">
    <tbody>
        <tr>
            <td valign="top"><img src="{'showPanelTopLeft.gif'|@vtiger_imageurl:$THEME}"></td>
            <td class="showPanelBg" style="padding: 10px;" valign="top" width="100%">
                <br>
                <form action="index.php" method="post" name="index" enctype="multipart/form-data" onsubmit="VtigerJS_DialogBox.block();">
                    <input type="hidden" name="module" value="Settings">
                    <input type="hidden" name="action" value="add2db">
                    <input type="hidden" name="return_module" value="Settings">
                    <input type="hidden" name="parenttab" value="Settings">
                    <input type="hidden" name="return_action" value="OrganizationConfig">
                    <input type="hidden" name="return_action2" value="EditCompanyDetails">
                    <input type="hidden" name="org_name" value="{$ORGANIZATIONNAME}">
                    <div align=center>
                        {include file="SetMenu.tpl"}
                        <!-- DISPLAY -->
                        <table border=0 cellspacing=0 cellpadding=5 width=100% class="settingsSelUITopLine">
                            <tr>
                                <td width=50 rowspan=2 valign=top><img src="{'company.gif'|@vtiger_imageurl:$THEME}" width="48" height="48" border=0 ></td>
                                <td class=heading2 valign=bottom><b><a href="index.php?module=Settings&action=index&parenttab=Settings">{$MOD.LBL_SETTINGS}</a> > {$MOD.LBL_EDIT} {$MOD.LBL_COMPANY_DETAILS} </b></td>
                            </tr>
                            <tr>
                                <td valign=top class="small">{$MOD.LBL_COMPANY_DESC}</td>
                            </tr>
                        </table>

                        <br>
                        <table border=0 cellspacing=0 cellpadding=10 width=100% >
                            <tr>
                                <td>
                                    <table border=0 cellspacing=0 cellpadding=5 width=100% class="tableHeading">
                                        <tr>
                                            <td class="big"><strong>{$MOD.LBL_ORGANIZATION_LOGO}</strong>
													{$ERRORFLAG}<br>
                                            </td>
                                            <td class="small" align=right>
                                                <input title="{$APP.LBL_CANCEL_BUTTON_LABEL}" accessKey="{$APP.LBL_CANCEL_BUTTON_KEY}" class="crmButton small cancel" onclick="window.history.back()" type="button" name="button" value="{$APP.LBL_CANCEL_BUTTON_LABEL}">
                                            </td>
                                        </tr>
                                    </table>
                                    <table border=0 cellspacing=0 cellpadding=0 width=100% class="listRow">
                                        <tr>
                                            <td class="small" valign=top >
                                                <table width="100%"  border="0" cellspacing="0" cellpadding="5">
                                                    <tr valign="top">
                                                        <td class="small cellLabel" width="20%"><strong>{'LBL_CURRENT_LOGO'|@getTranslatedString:'Settings'}</strong></td>
                                                        <td	class="small cellText">
															{if $ORGANIZATIONLOGONAME neq ''}
                                                            <img src="test/logo/{$ORGANIZATIONLOGONAME}" style="width: 15em;height: 4.2em;"/>
															{else}
                                                            <img src="include/images/noimage.gif" style="width: 15em;height: 4.2em;"/>
															{/if}
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="small cellLabel"><strong>{$MOD.LBL_SELECT_LOGO}</strong></td>
                                                        <td class="small cellText">
                                                            <INPUT TYPE="HIDDEN" NAME="MAX_FILE_SIZE" VALUE="800000">
                                                            <INPUT TYPE="HIDDEN" NAME="PREV_FILE" VALUE="{$ORGANIZATIONLOGONAME}">
                                                            <input type="file" name="binFile" class="small" value="{$ORGANIZATIONLOGONAME}" onchange="validateFilename(this);">
                                                            <input title="{$APP.LBL_UPLOAD_BUTTON_LABEL}" accessKey="{$APP.LBL_UPLOAD_BUTTON_LABEL}" class="crmButton small save" type="submit" name="button" value="{'LBL_UPLOAD_BUTTON_LABEL'|@getTranslatedString:'Settings'}" onclick="return verify_data(form,'{$MOD.LBL_ORGANIZATION_NAME}');" >
                                                            <input type="hidden" name="binFile_hidden" value="{$ORGANIZATIONLOGONAME}" />
															<br><br>{'LBL_LOGO_RECOMMENDED_SIZE'|@getTranslatedString:'Settings'}
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                        </td>
                        </tr>
                        </table>
                        </td>
                        </tr>
                        </table>
                    </div>
                </form>
            </td>
            <td valign="top"><img src="{'showPanelTopRight.gif'|@vtiger_imageurl:$THEME}"></td>
        </tr>
    </tbody>
</table>
{literal}
<script>
    function verify_data(form,company_name) {
        if (form.organization_name.value == "" ) {
            {/literal}
            alert(company_name +"{$APP.CANNOT_BE_NONE}");
            form.organization_name.focus();
            return false;
            {literal}
        } else if (form.organization_name.value.replace(/^\s+/g, '').replace(/\s+$/g, '').length==0) {
            {/literal}
            alert(company_name +"{$APP.CANNOT_BE_EMPTY}");
            form.organization_name.focus();
            return false;
            {literal}
        } else if (! upload_filter("binFile","jpg|jpeg|JPG|JPEG")) {
            form.binFile.focus();
            return false;
        } else {
            return true;
        }
    }
</script>
{/literal}
