/*+*******************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 ******************************************************************************/

/**
 * Generic uitype popup selection handler
 */
function vtlib_setvalue_from_popup(recordid,value,target_fieldname) {
	if(window.opener.document.EditView) {
		var domnode_id = window.opener.document.EditView[target_fieldname];
		var domnode_display = window.opener.document.EditView[target_fieldname+'_display'];
		if(domnode_id) domnode_id.value = recordid;
		if(domnode_display) domnode_display.value = value;
		return true;
	} else{
		return false;
	}
}

/**
 * Show the vtiger field help if available.
 */
function vtlib_field_help_show(basenode, fldname) {
	var domnode = $('vtlib_fieldhelp_div');

	if(typeof(fieldhelpinfo) == 'undefined') return;

	var helpcontent = fieldhelpinfo[fldname];
	if(typeof(helpcontent) == 'undefined') return;

	if(!domnode) {
		domnode = document.createElement('div');
		domnode.id = 'vtlib_fieldhelp_div';
		domnode.className = 'dvtSelectedCell';
		domnode.style.position = 'absolute';
		domnode.style.width = '150px';
		domnode.style.padding = '4px';
		domnode.style.fontWeight = 'normal';
		document.body.appendChild(domnode);	

		domnode = $('vtlib_fieldhelp_div');	
		Event.observe(domnode, 'mouseover', function() { $('vtlib_fieldhelp_div').show(); });
		Event.observe(domnode, 'mouseout', vtlib_field_help_hide);
	}
	else {
		domnode.show();
	}
	domnode.innerHTML = helpcontent;
	fnvshobj(basenode,'vtlib_fieldhelp_div');
}
/**
 * Hide the vtiger field help
 */
function vtlib_field_help_hide(evt) {
	var domnode = $('vtlib_fieldhelp_div');
	if(domnode) domnode.hide();
}
