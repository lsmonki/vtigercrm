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
<div class="container-fluid detailViewInfo" style='margin-top:0px;'>
    <input type="hidden" name="extensionId" value="{$EXTENSION_ID}" />
    <div class="row-fluid contentHeader">
        <span class="span6">
            <div class="font-x-x-large row-fluid">{$EXTENSION_DETAIL->get('name')}</div>
            <div class="row-fluid">
                <span class="span6">
                    {assign var=ON_RATINGS value=$EXTENSION_DETAIL->get('avgrating')}
                    <div class="row-fluid">
                        <span data-score="{$ON_RATINGS}" class="row-fluid rating span5" data-readonly="true"></span>
                        <span class="span6">({$ON_RATINGS} {vtranslate('LBL_RATINGS', $QUALIFIED_MODULE)})</span>
                    </div>
                </span>
            </div>
        </span>
        <span class="span6">
            <div class="row-fluid">
                <div class="pull-right">
                    <a class="cancelLink" type="reset" id="declineExtension">{vtranslate('LBL_CANCEL', $MODULE)}</a>
                </div>
                <div class="clearfix"></div>
             </div>
        </span>
    </div>
     <div class="container-fluid tabbable margin0px" style="padding-bottom: 20px;">
        <ul id="extensionTab" class="nav nav-tabs" style="margin-bottom: 0px; padding-bottom: 0px;">
            <li class="active"><a href="#description" data-toggle="tab"><strong>{vtranslate('LBL_DESCRIPTION', $QUALIFIED_MODULE)}</strong></a></li>
            <li><a href="#Author" data-toggle="tab"><strong>{vtranslate('LBL_AUTHOR_INFORMATION', $QUALIFIED_MODULE)}</strong></a></li>
            <li id="screenShots"><a href="#ScreenShots" data-toggle="tab"><strong>{vtranslate('LBL_SCREEN_SHOTS', $QUALIFIED_MODULE)}</strong></a></li>
            <li><a href="#CustomerReviews" data-toggle="tab"><strong>{vtranslate('LBL_CUSTOMER_REVIEWS', $QUALIFIED_MODULE)}</strong></a></li>
        </ul>
        <div class="tab-content row-fluid boxSizingBorderBox" style="background-color: #fff; padding: 20px; border: 1px solid #ddd; border-top-width: 0px;">
            <div class="tab-pane active" id="description">
                <div class="scrollableTab">
                    <p>{$EXTENSION_DETAIL->get('description')}</p>
                </div>
            </div>
            <div class="tab-pane row-fluid" id="Author">
                <div class="scrollableTab"></div>
            </div>
            <div class="tab-pane row-fluid" id="ScreenShots">
                <div class="scrollableTab">
                    <div class="row-fluid">
                        <ul id="imageSlider"></ul>
                    </div>
                </div>
            </div>
            <div class="tab-pane row-fluid" id="CustomerReviews">
                <div class="row-fluid boxSizingBorderBox" style="padding-bottom: 15px;">
                    <span class="span6" style="font-weight: bold">
                        {assign var=CUSTOMER_REVIEW_NUMBERS value={count($CUSTOMER_REVIEWS)}}
                        {if $CUSTOMER_REVIEW_NUMBERS eq 1}
                            ({vtranslate('LBL_SINGLE_CUSTOMER_REVIEWED', $QUALIFIED_MODULE)})
                        {else}
                            ({$CUSTOMER_REVIEW_NUMBERS} {vtranslate('LBL_CUSTOMERS_REVIEWED', $QUALIFIED_MODULE)})
                        {/if}
                     </span>
                </div>
            </div>
        </div>
    </div>
</div>