/*********************************************************************************

** The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 ********************************************************************************/		
		
document.write("<script type='text/javascript' src='include/js/Mail.js'></"+"script>");
document.write("<script type='text/javascript' src='include/js/Merge.js'></"+"script>");

function copyAddressRight(form) {

	form.otherstreet.value = form.mailingstreet.value;

	form.othercity.value = form.mailingcity.value;

	form.otherstate.value = form.mailingstate.value;

	form.otherzip.value = form.mailingzip.value;

	form.othercountry.value = form.mailingcountry.value;

	form.otherpobox.value = form.mailingpobox.value;
	
	return true;

}

function copyAddressLeft(form) {

	form.mailingstreet.value = form.otherstreet.value;

	form.mailingcity.value = form.othercity.value;

	form.mailingstate.value = form.otherstate.value;

	form.mailingzip.value =	form.otherzip.value;

	form.mailingcountry.value = form.othercountry.value;

	form.mailingpobox.value = form.otherpobox.value;
	
	return true;

}

	
function toggleDisplay(id){
		
if(this.document.getElementById( id).style.display=='none'){
	this.document.getElementById( id).style.display='inline'
	this.document.getElementById(id+"link").style.display='none';
					
}else{
	this.document.getElementById(  id).style.display='none'
	this.document.getElementById(id+"link").style.display='none';
	}
}

function set_return(product_id, product_name) {
        window.opener.document.EditView.parent_name.value = product_name;
        window.opener.document.EditView.parent_id.value = product_id;
}

function add_data_to_relatedlist_incal(id,name)
{
	var idval = window.opener.document.EditView.contactidlist.value;
	var nameval = window.opener.document.EditView.contactlist.value;
	if(idval != '')
	{
		if(idval.indexOf(id) != -1)
                {
                        window.opener.document.EditView.contactidlist.value = idval;
                        window.opener.document.EditView.contactlist.value = nameval;
                }
                else
                {
                        window.opener.document.EditView.contactidlist.value = idval+';'+id;
                        window.opener.document.EditView.contactlist.value = nameval+'\n'+name;
                }
	}
	else
	{
		window.opener.document.EditView.contactidlist.value = id;
		window.opener.document.EditView.contactlist.value = name;
	}
}
function set_return_specific(product_id, product_name) {
        //Used for DetailView, Removed 'EditView' formname hardcoding
        var fldName = getOpenerObj("contact_name");
        var fldId = getOpenerObj("contact_id");
        fldName.value = product_name;
        fldId.value = product_id;
}
function submitform(id){
		document.massdelete.entityid.value=id;
		document.massdelete.submit();
}	

function searchMapLocation(addressType)
{
        var mapParameter = '';
        if (addressType == 'Main')
        {
                mapParameter = document.getElementById("dtlview_Mailing Street").innerHTML+' '
                           +document.getElementById("dtlview_Mailing Po Box").innerHTML+' '
                           +document.getElementById("dtlview_Mailing City").innerHTML+' '
                           +document.getElementById("dtlview_Mailing State").innerHTML+' '
                           +document.getElementById("dtlview_Mailing Country").innerHTML+' '
                           +document.getElementById("dtlview_Mailing Zip").innerHTML
        }
        else if (addressType == 'Other')
        {
                mapParameter = document.getElementById("dtlview_Other Street").innerHTML+' '
                           +document.getElementById("dtlview_Other Po Box").innerHTML+' '
                           +document.getElementById("dtlview_Other City").innerHTML+' '
                           +document.getElementById("dtlview_Other State").innerHTML+' '
                           +document.getElementById("dtlview_Other Country").innerHTML+' '
                           +document.getElementById("dtlview_Other Zip").innerHTML
        }
         window.open('http://maps.google.com/maps?q='+mapParameter,'goolemap','height=450,width=700,resizable=no,titlebar,location,top=200,left=250');
}
