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

//Function added for Mass select in Popup - Philip
function SelectAll()
{

        x = document.selectall.selected_id.length;
	var entity_id = window.opener.document.getElementById('parent_id').value
	var module = window.opener.document.getElementById('return_module').value
	document.selectall.action.value='updateRelations'
        idstring = "";

        if ( x == undefined)
        {

                if (document.selectall.selected_id.checked)
                {
                        document.selectall.idlist.value=document.selectall.selected_id.value;
                }
                else
                {
                        alert("Please select atleast one entity");
                        return false;
                }
        }
        else
        {
                xx = 0;
                for(i = 0; i < x ; i++)
                {
                        if(document.selectall.selected_id[i].checked)
                        {
                                idstring = document.selectall.selected_id[i].value +";"+idstring
                        xx++
                        }
                }
                if (xx != 0)
                {
                        document.selectall.idlist.value=idstring;
                }
                else
                {
                        alert("Please select atleast one entity");
                        return false;
                }
	}
		if(confirm("Are you sure you want to add the selected "+xx+" records ?"))
		{
		opener.document.location.href="index.php?module="+module+"&parentid="+entity_id+"&action=updateRelations&return_module=Potentials&return_action=CallRelatedList&idlist="+idstring;
		self.close();
		}
		else
		{
			return false;
		}
}


function set_return(product_id, product_name) {
        window.opener.document.EditView.parent_name.value = product_name;
        window.opener.document.EditView.parent_id.value = product_id;
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
