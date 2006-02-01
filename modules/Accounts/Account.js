/*********************************************************************************

** The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 ********************************************************************************/


function copyAddressRight(form) {

	form.ship_street.value = form.bill_street.value;

	form.ship_city.value = form.bill_city.value;

	form.ship_state.value = form.bill_state.value;

	form.ship_code.value = form.bill_code.value;

	form.ship_country.value = form.bill_country.value;

	form.ship_pobox.value = form.bill_pobox.value;
	
	return true;

}

function copyAddressLeft(form) {

	form.bill_street.value = form.ship_street.value;

	form.bill_city.value = form.ship_city.value;

	form.bill_state.value = form.ship_state.value;

	form.bill_code.value =	form.ship_code.value;

	form.bill_country.value = form.ship_country.value;

	form.bill_pobox.value = form.ship_pobox.value;

	return true;

}

function showDefaultCustomView(selectView)
{
viewName = selectView.options[selectView.options.selectedIndex].value;
document.massdelete.viewname.value=viewName;
document.massdelete.action="index.php?module=Accounts&action=index&return_module=Accounts&return_action=index&viewname="+viewName;
document.massdelete.submit();
}
//added by raju for emails

function eMail()
{
    x = document.massdelete.selected_id.length;
	var viewid = document.massdelete.viewname.value;
	idstring = "";

        if ( x == undefined)
        {

                if (document.massdelete.selected_id.checked)
                {
                        document.massdelete.idlist.value=document.massdelete.selected_id.value;
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
                        if(document.massdelete.selected_id[i].checked)
                        {
                                idstring = document.massdelete.selected_id[i].value +";"+idstring
                                xx++
                        }
                }
                if (xx != 0)
                {
                        document.massdelete.idlist.value=idstring;
                }
                else
                {
                        alert("Please select atleast one entity");
                        return false;
                }
        }
        document.massdelete.action="index.php?module=Emails&action=SelectEmails&return_module=Accounts&return_action=index";
}







function massMail()
{

    x = document.massdelete.selected_id.length;
	var viewid = document.massdelete.viewname.value;
	idstring = "";

        if ( x == undefined)
        {

                if (document.massdelete.selected_id.checked)
                {
                        document.massdelete.idlist.value=document.massdelete.selected_id.value;
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
                        if(document.massdelete.selected_id[i].checked)
                        {
                                idstring = document.massdelete.selected_id[i].value +";"+idstring
                                xx++
                        }
                }
                if (xx != 0)
                {
                        document.massdelete.idlist.value=idstring;
                }
                else
                {
                        alert("Please select atleast one entity");
                        return false;
                }
        }
        document.massdelete.action="index.php?module=CustomView&action=SendMailAction&return_module=Accounts&return_action=index&viewname="+viewid;
}

function massDelete()
{
        x = document.massdelete.selected_id.length;
	var viewid = document.massdelete.viewname.value;
        idstring = "";

        if ( x == undefined)
        {

                if (document.massdelete.selected_id.checked)
                {
                        document.massdelete.idlist.value=document.massdelete.selected_id.value;
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
                        if(document.massdelete.selected_id[i].checked)
                        {
                                idstring = document.massdelete.selected_id[i].value +";"+idstring
                        xx++
                        }
                }
                if (xx != 0)
                {
                        document.massdelete.idlist.value=idstring;
                }
               else
                {
                        alert("Please select atleast one entity");
                        return false;
                }
        }
		if(confirm("Are you sure you want to delete the selected "+xx+" records ?"))
		{
	        document.massdelete.action="index.php?module=Users&action=massdelete&return_module=Accounts&return_action=index&viewname="+viewid;
		}
		else
		{
			return false;
		}

}

//to merge a list of acounts with a template
function massMerge()
{
        x = document.massdelete.selected_id.length;
	var viewid = document.massdelete.viewname.value;
        idstring = "";

        if ( x == undefined)
        {

                if (document.massdelete.selected_id.checked)
                {
                        document.massdelete.idlist.value=document.massdelete.selected_id.value;
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
                        if(document.massdelete.selected_id[i].checked)
                        {
                                idstring = document.massdelete.selected_id[i].value +";"+idstring
                        xx++
                        }
                }
                if (xx != 0)
                {
                        document.massdelete.idlist.value=idstring;
                }
               else
                {
                			
                        alert("Please select atleast one entity");
                        return false;
                }
        }
        
        if(getObj('selectall').checked == true)
				{
						getObj('idlist').value = getObj('allids').value;
				}
	
        document.massdelete.action="index.php?module=Accounts&action=Merge&return_module=Accounts&return_action=index";
}
//end of mass merge


//added by rdhital/Raju for better emails
function set_return_emails(entity_id,email_id,parentname,emailadd){
		window.opener.document.EditView.parent_id.value = window.opener.document.EditView.parent_id.value+entity_id+'@'+email_id+'|';
		window.opener.document.EditView.parent_name.value = window.opener.document.EditView.parent_name.value+parentname+'<'+emailadd+'>; ';
}		
		
//Raju
function set_return(product_id, product_name) {
        window.opener.document.EditView.parent_name.value = product_name;
        window.opener.document.EditView.parent_id.value = product_id;
}
function set_return_specific(product_id, product_name) {
        window.opener.document.EditView.account_name.value = product_name;
        window.opener.document.EditView.account_id.value = product_id;
}
function add_data_to_relatedlist(entity_id,recordid) {

        opener.document.location.href="index.php?module=Emails&action=updateRelations&destination_module=Accounts&entityid="+entity_id+"&parid="+recordid;
}
function set_return_formname_specific(formname,product_id, product_name) {
        window.opener.document.EditView1.account_name.value = product_name;
        window.opener.document.EditView1.account_id.value = product_id;
}
function set_return_address(account_id, account_name, bill_street, ship_street, bill_city, ship_city, bill_state, ship_state, bill_code, ship_code, bill_country, ship_country,bill_pobox,ship_pobox) {
        window.opener.document.EditView.account_name.value = account_name;
        window.opener.document.EditView.account_id.value = account_id;
        window.opener.document.EditView.bill_street.value = bill_street;
        window.opener.document.EditView.ship_street.value = ship_street;
        window.opener.document.EditView.bill_city.value = bill_city;
        window.opener.document.EditView.ship_city.value = ship_city;
        window.opener.document.EditView.bill_state.value = bill_state;
        window.opener.document.EditView.ship_state.value = ship_state;
        window.opener.document.EditView.bill_code.value = bill_code;
        window.opener.document.EditView.ship_code.value = ship_code;
        window.opener.document.EditView.bill_country.value = bill_country;
        window.opener.document.EditView.ship_country.value = ship_country;
        window.opener.document.EditView.bill_pobox.value = bill_pobox;
        window.opener.document.EditView.ship_pobox.value = ship_pobox;
}
//added to populate address
function set_return_contact_address(account_id, account_name, bill_street, ship_street, bill_city, ship_city, bill_state, ship_state, bill_code, ship_code, bill_country, ship_country,bill_pobox,ship_pobox ) {
        window.opener.document.EditView.account_name.value = account_name;
        window.opener.document.EditView.account_id.value = account_id;
        window.opener.document.EditView.mailingstreet.value = bill_street;
        window.opener.document.EditView.otherstreet.value = ship_street;
        window.opener.document.EditView.mailingcity.value = bill_city;
        window.opener.document.EditView.othercity.value = ship_city;
        window.opener.document.EditView.mailingstate.value = bill_state;
        window.opener.document.EditView.otherstate.value = ship_state;
        window.opener.document.EditView.mailingzip.value = bill_code;
        window.opener.document.EditView.otherzip.value = ship_code;
        window.opener.document.EditView.mailingcountry.value = bill_country;
        window.opener.document.EditView.othercountry.value = ship_country;
        window.opener.document.EditView.mailingpobox.value = bill_pobox;
        window.opener.document.EditView.otherpobox.value = ship_pobox;
}

//added by rdhital/Raju for emails
function submitform(id){
		document.massdelete.entityid.value=id;
		document.massdelete.submit();
}	

function searchMapLocation(addressType)
{
        var mapParameter = '';
        if (addressType == 'Main')
        {
                mapParameter = getObj("Billing Address").value+' '
                           +getObj("Billing Po Box").value+' '
                           +getObj("Billing City").value+' '
                           +getObj("Billing State").value+' '
                           +getObj("Billing Country").value+' '
                           +getObj("Billing Code").value
        }
        else if (addressType == 'Other')
        {
                mapParameter = getObj("Shipping Address").value+' '
                           +getObj("Shipping Po Box").value+' '
                           +getObj("Shipping City").value+' '
                           +getObj("Shipping State").value+' '
                           +getObj("Shipping Country").value+' '
                           +getObj("Shipping Code").value
        }
	 window.open('http://maps.google.com/maps?q='+mapParameter,'goolemap','height=450,width=700,resizable=no,titlebar,location,top=200,left=250');
}

