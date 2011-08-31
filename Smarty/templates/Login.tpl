{*<!--
/*+********************************************************************************
  * The contents of this file are subject to the vtiger CRM Public License Version 1.0
  * ("License"); You may not use this file except in compliance with the License
  * The Original Code is:  vtiger CRM Open Source
  * The Initial Developer of the Original Code is vtiger.
  * Portions created by vtiger are Copyright (C) vtiger.
  * All Rights Reserved.
  *********************************************************************************/
-->*}
{include file="LoginHeader.tpl}

<div class="brandingSpace">
	<div class="logo">
		<img align="absmiddle" src="test/logo/{$COMPANY_DETAILS.logo}" alt="logo"/></div>
	<div class="company">
		<p>
		{$COMPANY_DETAILS.name}
		<br />
		<a target="_blank" href="http://{$COMPANY_DETAILS.website}">{$COMPANY_DETAILS.website}</a>
		</p>
	</div>
</div>
<div class="loginSpace">
	<div class="loginForm">
		<div class="poweredBy">Powered by vtiger CRM - {$VTIGER_VERSION}</div>
		<form action="index.php" method="post" name="DetailView" id="form">
			<input type="hidden" name="module" value="Users" />
			<input type="hidden" name="action" value="Authenticate" />
			<input type="hidden" name="return_module" value="Users" />
			<input type="hidden" name="return_action" value="Login" />
			<div class="inputs">
				<div class="label">User Name</div>
				<div class="input"><input type="text" name="user_name"/></div>
				<br />
				<div class="label">Password</div>
				<div class="input"><input type="password" name="user_password"/></div>
				<br />
				<div class="button">
					<input type="submit" id="submitButton" value="Submit" />
				</div>
			</div>
		</form>
	</div>
</div>
<div class="footerSeperator"></div>
<div class="vtigerSpace">
	<div class="importantLinks">
		<p>
		<a href='javascript:mypopup()'>{$APP.LNK_READ_LICENSE}</a>
		|
		<a href='http://www.vtiger.com/products/crm/privacy_policy.html' target='_blank'>{$APP.LNK_PRIVACY_POLICY}</a>
		|
		&copy; 2004- {php} echo date('Y'); {/php}
		</p>
	</div>
	<div class="communityLinks">
		<p>
		Connect with us
		<br />
		<a target="_blank" href="http://www.facebook.com/pages/vtiger/226866697333578?sk=app_143539149057867">
			<img align="absmiddle" border="0" src="{$IMAGE_PATH}/Facebook.png" alt="Facebook">
		</a>
		<a target="_blank" href="http://twitter.com/#!/vtigercrm">
			<img align="absmiddle" border="0" src="{$IMAGE_PATH}/Twitter.png" alt="Twitter">
		</a>
		<a target="_blank" href="http://www.linkedin.com/company/1270573?trk=tyah">
			<img align="absmiddle" border="0" src="{$IMAGE_PATH}/Linkedin.png" alt="Linkedin">
		</a>
		<a target="_blank" href="http://www.youtube.com/user/vtigercrm">
			<img align="absmiddle" border="0" src="{$IMAGE_PATH}/Youtube.png" alt="Videos">
		</a>
		<a target="_blank" href="http://wiki.vtiger.com/">
			<img align="absmiddle" border="0" src="{$IMAGE_PATH}/Manuals.png" alt="Manuals">
		</a>
		<a target="_blank" href="http://forums.vtiger.com/">
			<img align="absmiddle" border="0" src="{$IMAGE_PATH}/Forums.png" alt="Forums">
		</a>
		<a target="_blank" href="http://blogs.vtiger.com/">
			<img align="absmiddle" border="0" src="{$IMAGE_PATH}/Blogs.png" alt="Blogs">
		</a>
		</p>
	</div>
</div>

{include file="LoginFooter.tpl}