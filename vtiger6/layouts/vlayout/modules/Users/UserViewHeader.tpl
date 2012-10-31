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
{strip}
    {assign var="MODULE_NAME" value=$MODULE_MODEL->get('name')}
    <input id="recordId" type="hidden" value="{$RECORD->getId()}" />
    <div class="detailViewContainer">
        <div class="detailViewTitle" id="prefPageHeader">
            <div class="row-fluid">
                <div class="span8">
                    <span class="span6 marginLeftZero">
                        <table>
                            <tr>
                                <td>
                                    <span class="logo">
                                        {foreach key=ITER item=IMAGE_INFO from=$RECORD->getImageDetails()}
                                            {if !empty($IMAGE_INFO.path) && !empty($IMAGE_INFO.orgname)}
                                                <img src="../{$IMAGE_INFO.path}_{$IMAGE_INFO.orgname}" alt="{$IMAGE_INFO.orgname}" title="{$IMAGE_INFO.orgname}" data-image-id="{$IMAGE_INFO.id}">
                                            {/if}
                                        {/foreach}
                                    </span>
                                </td>
                                <td id="myPrefHeading">
                                    <h3>{vtranslate('LBL_MY_PREFERENCES', $MODULE_NAME)} </h3>
                                    {vtranslate('LBL_USERDETAIL_INFO', $MODULE_NAME)}<span>"<b>{$RECORD->getName()}</b>"</span>
                                </td>
                            </tr>
                        </table>
                    </span>
                </div>
                <div class="span4">
                    <div class="row- fluid pull-right detailViewButtoncontainer">
                        <div class="btn-toolbar pull-right">
                            {foreach item=DEVAIL_VIEW_BASIC_LINK from=$DETAILVIEW_LINKS['DETAILVIEWBASIC']}
                                <button class="btn"
                                        {if $DEVAIL_VIEW_BASIC_LINK->isPageLoadLink()}
                                            onclick="window.location.href='{$DEVAIL_VIEW_BASIC_LINK->getUrl()}'"
                                        {else}
                                            onclick={$DEVAIL_VIEW_BASIC_LINK->getUrl()}
                                        {/if}>
                                    <strong>{vtranslate($DEVAIL_VIEW_BASIC_LINK->getLabel(), $MODULE_NAME)}</strong>
                                </button>
                            {/foreach}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="detailViewInfo row-fluid">
            <div class="details">
                <form id="detailView" data-name-fields='{ZEND_JSON::encode($MODULE_MODEL->getNameFields())}'>
                    <div class="contents">
                    {/strip}