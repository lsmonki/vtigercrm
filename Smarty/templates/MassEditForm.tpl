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

<table border=0 cellspacing=0 cellpadding=5 width=95% align=center> 
	<tr>
		<td class=small >		
			<!-- popup specific content fill in starts -->
	      <form name="EditView" id="massedit_form" action="index.php">
				<input id="idstring" value="{$IDS}" type="hidden" />
				<table border=0 celspacing=0 cellpadding=5 width=100% align=center bgcolor=white>
				<tr>
					<td  colspan=4 valign="top"><b>Select fields to be updated and give the new value</b><br>
						<!-- Hidden Fields -->
						{include file='EditViewHidden.tpl'}
						<input type="hidden" name="massedit_recordids">
						<input type="hidden" name="massedit_module">
						<input type="hidden" name="module" value="{$MODULE}">
						<input type="hidden" name="action" value="MassEditSave">
					</td>


				</tr>
				{foreach key=header item=data from=$BLOCKS}
					<tr>
						<td colspan=4 class="detailedViewHeader">
							<b>{$header}</b>
						</td>
					</tr>
					{include file="DisplayFields.tpl"}
				{/foreach}
			</table>
			<table border=0 cellspacing=0 cellpadding=5 width=100% class="layerPopupTransport">
				<tr>
					<td align="center">
							<!--input type="submit" name="save" class="crmbutton small edit" value="{$APP.LBL_SAVE_LABEL}">
							<input type="button" name="button" class="crmbutton small cancel" value="{$APP.LBL_CANCEL_BUTTON_LABEL}" onClick="fninvsh('massedit')"-->
  				                     <input title="{$APP.LBL_SAVE_BUTTON_TITLE}" accessKey="{$APP.LBL_SAVE_BUTTON_KEY}" class="crmbutton small save" onclick="this.form.action.value='MassEditSave';  return massEditFormValidate()" type="submit" name="button" value="  {$APP.LBL_SAVE_BUTTON_LABEL}  " style="width:70px" >
                                   	 <input title="{$APP.LBL_CANCEL_BUTTON_TITLE}" accessKey="{$APP.LBL_CANCEL_BUTTON_KEY}" class="crmbutton small cancel" onclick="fninvsh('massedit')" type="button" name="button" value="  {$APP.LBL_CANCEL_BUTTON_LABEL}  " style="width:70px">

					</td>
				</tr>
			</table>			
			</form>
		</td>
	</tr>
</table>

<script type="text/javascript">	

        var fieldname = new Array({$VALIDATION_DATA_FIELDNAME})

        var fieldlabel = new Array({$VALIDATION_DATA_FIELDLABEL})

        var fielddatatype = new Array({$VALIDATION_DATA_FIELDDATATYPE})

	var count=0;
</script>
