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
{if !$SEARCH_MODE}
<div class="container-fluid" id="importModules">
    <div class="widget_header row-fluid">
        <div class="span6">
            <h3>{vtranslate('LBL_VTIGER_EXTENSION_STORE', $QUALIFIED_MODULE)}</h3>
        </div>
        <div class="span6">
            <span class="btn-toolbar pull-right">
                <span class="btn-group">
                    <a class='alert alert-danger' onclick=window.open("http://community.vtiger.com/help/vtigercrm/php/extension-loader.html")>
                        <strong>{vtranslate('LBL_PHP_EXTENSION_LOADER_IS_NOT_AVAIABLE', $QUALIFIED_MODULE)}</strong>
                    </a>
                </span>
            </span>
        </div>
    </div><hr>
        
    <div class="row-fluid">
        <span class="span8">
            <div class="row-fluid">
                <input type="text" id="searchExtension" class="span7 extensionSearch" placeholder="{vtranslate('LBL_SEARCH_FOR_AN_EXTENSION', $QUALIFIED_MODULE)}"/>
            </div>
        </span>
    </div>
{/if}
    <div class="contents" id="extensionContainer">
         <div class="row-fluid">
            {foreach item=EXTENSION from=$EXTENSIONS_LIST name=extensions}
                {if $EXTENSION->isAlreadyExists()}
                    {assign var=EXTENSION_MODULE_MODEL value= Vtiger_Module_Model::getInstance($EXTENSION->get('name'))}
                 {/if}
                <div class="span6">
                    <div class="extension_container extensionWidgetContainer">
                        <div class="extension_header row-fluid widget_header">
                                <div class="span8 font-x-x-large boxSizingBorderBox" style="cursor:pointer">{vtranslate($EXTENSION->get('label'), $QUALIFIED_MODULE)}</div>
                                <div class="span4 cursorPointer"><div class="pull-right extensionDetails" style="padding: 6px 15px;">{vtranslate('LBL_MORE_DETAILS', $QUALIFIED_MODULE)}</div></div>
                                <input type="hidden" name="extensionName" value="{$EXTENSION->get('name')}" />
                                <input type="hidden" name="extensionUrl" value="{$EXTENSION->get('downloadURL')}" />
                                <input type="hidden" name="moduleAction" value="{if ($EXTENSION->isAlreadyExists()) and (!$EXTENSION_MODULE_MODEL->get('trail'))}{if $EXTENSION->isUpgradable()}Upgrade{else}Installed{/if}{else}Install{/if}" />
                                <input type="hidden" name="extensionId" value="{$EXTENSION->get('id')}" />
                        </div>
                        <div class="extension_contents">
                            <div class="row-fluid">
                                <span class="span8">
                                    <div class="row-fluid extensionDescription" style="word-wrap:break-word;">
                                        {assign var=SUMMARY value=$EXTENSION->get('summary')}
                                        {if empty($SUMMARY)}
                                            {assign var=SUMMARY value={$EXTENSION->get('description')|truncate:100}}
                                        {/if}
                                       {$SUMMARY}
                                    </div>
                                </span>
                                <span class="span4">

                                    {if $EXTENSION->get('thumbnailURL') neq NULL}
                                        {assign var=imageSource value=$EXTENSION->get('thumbnailURL')}
                                    {else}
                                        {assign var=imageSource value= vimage_path('unavailable.png')}
                                    {/if}     
                                        <img class="thumbnailImage" src="{$imageSource}"/>
                                </<span>
                            </div>
                            <div class="extensionInfo">
                                <div class="row-fluid">
                                        <span class="span3">{$EXTENSION->get('NumberOfUsers')}{vtranslate('LBL_USERS', $QUALIFIED_MODULE)}</span>
                                        <span class="span9">
                                            <span class="pull-right">{$EXTENSION->get('downloads')}&nbsp;&nbsp;{vtranslate('LBL_DOWNLOADS', $QUALIFIED_MODULE)}</span>
                                        </span>
                                </div>
                                <div class="row-fluid">
                                    {assign var=ON_RATINGS value=$EXTENSION->get('avgrating')}
                                    <span class="span3 rating" data-score="{$ON_RATINGS}" data-readonly=true></span>
                                    <span class="span9">
                                        <span class="pull-right">
                                            {if $EXTENSION->isVtigerCompatible()}
                                                {if ($EXTENSION->isAlreadyExists()) and (!$EXTENSION_MODULE_MODEL->get('trail'))}
                                                    {if ($EXTENSION->isUpgradable()) and ($REGISTRATION_STATUS)}
                                                        <button class="oneclickInstallFree btn btn-success margin0px">
                                                                {vtranslate('LBL_UPGRADE', $QUALIFIED_MODULE)}
                                                        </button>
                                                    {else}
                                                        <span class="alert alert-info">{vtranslate('LBL_INSTALLED', $QUALIFIED_MODULE)}</span>
                                                    {/if}
                                                {elseif ($EXTENSION->get('price') eq 'Free') and ($REGISTRATION_STATUS) }
                                                    <button class="oneclickInstallFree btn btn-success">{vtranslate('LBL_INSTALL', $QUALIFIED_MODULE)}</button>
                                                {elseif $REGISTRATION_STATUS}
                                                    {if ($EXTENSION->get('trialdays') gt 0) and (!$EXTENSION_MODULE_MODEL->get('trail'))}
                                                        <button class="oneclickInstallPaid btn btn-success" data-trail=true>{vtranslate('LBL_TRAIL', $QUALIFIED_MODULE)}</button>
                                                    {elseif $EXTENSION_MODULE_MODEL->get('trail')}
                                                        <span class="alert alert-info">{vtranslate('LBL_TRAIL_INSTALLED', $QUALIFIED_MODULE)}</span>&nbsp;&nbsp;
                                                    {/if}
                                                     <button class="oneclickInstallPaid btn btn-info" data-trail=false>${$EXTENSION->get('price')}</button>
                                                {/if}
                                            {else}
                                                <span class="alert alert-error">{vtranslate('LBL_EXTENSION_NOT_COMPATABLE', $QUALIFIED_MODULE)}</span>
                                            {/if}
                                        </span>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                {if $smarty.foreach.extensions.index % 2 != 0}
            </div>
                <div class="row-fluid">
            {/if}
            {/foreach}
            {if empty($EXTENSIONS_LIST)}
               <table class="emptyRecordsDiv">
                   <tbody>
                       <tr>
                           <td>
                               {vtranslate('LBL_NO_EXTENSIONS_FOUND', $QUALIFIED_MODULE)}
                           </td>
                       </tr>
                   </tbody>
               </table>
           {/if}
            </div>
    </div>
</div>