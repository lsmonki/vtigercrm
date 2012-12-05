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
	{assign var="topMenus" value=$MENU_STRUCTURE->getTop()}
	{assign var="moreMenus" value=$MENU_STRUCTURE->getMore()}

	<div class="navbar" id="topMenus">
		<div class="navbar-inner" id="nav-inner">
			<div class="menuBar row-fluid">
				<div class="span9">
					<ul class="nav modulesList">
						<li class="tabs">
							<a class="alignMiddle {if $MODULE eq 'Home'} selected {/if}" href="{$HOME_MODULE_MODEL->getDefaultUrl()}"><img src="{vimage_path('home.png')}" alt="{vtranslate('LBL_HOME',$moduleName)}" title="{vtranslate('LBL_HOME',$moduleName)}" /></a>
						</li>
						{foreach key=moduleName item=moduleModel from=$topMenus}
							{assign var='translatedModuleLabel' value=vtranslate($moduleModel->get('label'),$moduleName)}
							<li class="tabs">
								<a id="menubar_item_{$moduleName}" href="{$moduleModel->getDefaultUrl()}" {if $MODULE eq $moduleName} class="selected" {/if}>{$translatedModuleLabel}</a>
							</li>
						{/foreach}
						<li class="dropdown" id="moreMenu">
							<a class="dropdown-toggle" data-toggle="dropdown" href="#moreMenu">
								{vtranslate('LBL_ALL',$MODULE)}
								<b class="caret"></b>
							</a>
							<div class="dropdown-menu moreMenus">
								{foreach key=parent item=moduleList from=$moreMenus name=more}
									{if $smarty.foreach.more.index % 4 == 0}
										<div class="row-fluid">
									{/if}
									{if $moduleList}
									<span class="span3">
										<strong><label>{vtranslate("LBL_$parent",$moduleName)}</label></strong><hr>
										{foreach key=moduleName item=moduleModel from=$moduleList}
											{assign var='translatedModuleLabel' value=vtranslate($moduleModel->get('label'),$moduleName)}
											<label class="moduleNames"><a id="menubar_item_{$moduleName}" href="{$moduleModel->getDefaultUrl()}">{$translatedModuleLabel}</a></label>
										{/foreach}
									</span>
									{/if}
									{if $smarty.foreach.more.last OR ($smarty.foreach.more.index+1) % 4 == 0}
										</div>
									{/if}
									{/foreach}
								{if $USER_MODEL->isAdminUser()}
									<a id="menubar_item_moduleManager" href="index.php?module=Vtiger&parent=Settings&view=Index&item=ModuleManager" class="pull-right">{vtranslate('LBL_ADD_MANAGE_MODULES',$MODULE)}</a>
								{/if}
							</div>
						</li>
					</ul>
				</div>
				<div class="span3 row-fluid" id="headerLinks">
					<span class="pull-right headerLinksContainer">
						{foreach key=index item=obj from=$HEADER_LINKS}
							{assign var="src" value=$obj->getIconPath()}
							{assign var="icon" value=$obj->getIcon()}
							{assign var="title" value=$obj->getLabel()}
							{assign var="childLinks" value=$obj->getChildLinks()}
							<span class="dropdown span{if !empty($src)} settingIcons {/if}">
									{if !empty($src)}
										<a id="menubar_item_right_{$title}" class="dropdown-toggle" data-toggle="dropdown" href="#"><img src="{$src}" alt="{vtranslate($title,$MODULE)}" title="{vtranslate($title,$MODULE)}" /></a>
										{else}
											{assign var=title value=$USER_MODEL->get('first_name')}
											{if empty($title)} 
												{assign var=title value=$USER_MODEL->get('last_name')}
											{/if}
										<span class="dropdown-toggle row-fluid" data-toggle="dropdown" href="#">
											<a id="menubar_item_right_{$title}"  class="userName textOverflowEllipsis span" title="{$title}">{$title}</a> <i class="caret"></i></span>
									{/if}
									{if !empty($childLinks)}
										<ul class="dropdown-menu pull-right">
											{foreach key=index item=obj from=$childLinks}
												{if $obj->getLabel() eq NULL}
													<li class="divider">&nbsp;</li>
												{else}
													{assign var="id" value=$obj->getId()}
													{assign var="href" value=$obj->getUrl()}
													{assign var="label" value=$obj->getLabel()}
													{assign var="onclick" value=""}
													{if stripos($obj->getUrl(), 'javascript:') === 0}
														{assign var="onclick" value="onclick="|cat:$href}
														{assign var="href" value="javascript:;"}
													{/if}
													<li>
														<a id="menubar_item_right_{Vtiger_Util_Helper::replaceSpaceWithUnderScores($label)}" {if $label=='Switch to old look'}switchLook{/if} href="{$href}" {$onclick}>{vtranslate($label,$MODULE)}</a>
													</li>
												{/if}
											{/foreach}
										</ul>
									{/if}
							</span>
						{/foreach}
					</span>
				</div>
			</div>
			<div class="clearfix"></div>
		</div>
	</div>
	{assign var="announcement" value=$ANNOUNCEMENT->get('announcement')}
	<div class="announcement" id="announcement">
		<marquee direction="left" scrolldelay="10" scrollamount="3" behavior="scroll" class="marStyle" onmouseover="javascript:stop();" onmouseout="javascript:start();">{if !empty($announcement)}{$announcement}{else}{vtranslate('LBL_NO_ANNOUNCEMENTS',$MODULE)}{/if}</marquee>
	</div>
	<input type='hidden' value="{$MODULE}" id='module' name='module'/>
	<input type="hidden" value="{$PARENT_MODULE}" id="parent" name='parent' />
{/strip}