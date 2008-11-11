function vtlib_setvalue_from_popup(recordid,value,target_fieldname) {
	var domnode_id = window.opener.document.EditView[target_fieldname];
	var domnode_display = window.opener.document.EditView[target_fieldname+'_display'];
	if(domnode_id) domnode_id.value = recordid;
	if(domnode_display) domnode_display.value = value;
	return true;
}
