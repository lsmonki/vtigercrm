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
        
        //getOpenerObj used for DetailView 
        var fldName = getOpenerObj("account_name");
        var fldId = getOpenerObj("account_id");
        fldName.value = product_name;
        fldId.value = product_id;
	//window.opener.document.EditView.account_name.value = product_name;
        //window.opener.document.EditView.account_id.value = product_id;
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
                mapParameter = document.getElementById('dtlview_Billing Address').innerHTML+' '
                           +document.getElementById("dtlview_Billing Po Box").innerHTML+' '
                           +document.getElementById("dtlview_Billing City").innerHTML+' '
                           +document.getElementById("dtlview_Billing State").innerHTML+' '
                           +document.getElementById("dtlview_Billing Country").innerHTML+' '
                           +document.getElementById("dtlview_Billing Code").innerHTML
        }
        else if (addressType == 'Other')
        {
                mapParameter = document.getElementById("dtlview_Shipping Address").innerHTML+' '
                           +document.getElementById("dtlview_Shipping Po Box").innerHTML+' '
                           +document.getElementById("dtlview_Shipping City").innerHTML+' '
                           +document.getElementById("dtlview_Shipping State").innerHTML+' '
                           +document.getElementById("dtlview_Shipping Country").innerHTML+' '
                           +document.getElementById("dtlview_Shipping Code").innerHTML
        }
	 window.open('http://maps.google.com/maps?q='+mapParameter,'goolemap','height=450,width=700,resizable=no,titlebar,location,top=200,left=250');
}
//javascript function will open new window to display traffic details for particular url using alexa.com
function getRelatedLink()
{
	var param='';
	param = getObj("website").value;
	window.open('http://www.alexa.com/data/details/traffic_details?q=&url='+param,'relatedlink','height=400,width=700,resizable=no,titlebar,location,top=250,left=250');
}

/*
* javascript function to populate fieldvalue in account editview
* @param id1 :: div tag ID
* @param id2 :: div tag ID
*/
function populateData(id1,id2)
{
	document.EditView.description.value = document.getElementById('summary').innerHTML;
	document.EditView.employees.value = getObj('emp').value;
	document.EditView.website.value = getObj('site').value;
	document.EditView.phone.value = getObj('Phone').value;
	document.EditView.fax.value = getObj('Fax').value;
	document.EditView.bill_street.value = getObj('address').value;
	
	showhide(id1,id2);
}
/*
* javascript function to show/hide the div tag
* @param argg1 :: div tag ID
* @param argg2 :: div tag ID
*/
function showhide(argg1,argg2)
{
        var x=document.getElementById(argg1).style;
	var y=document.getElementById(argg2).style;
        if (y.display=="none")
        {
                y.display="block"
		x.display="none"

        }
}

// JavaScript Document

if (document.all) var browser_ie=true
else if (document.layers) var browser_nn4=true
else if (document.layers || (!document.all && document.getElementById)) var browser_nn6=true

function getObj(n,d) {
  var p,i,x;
  if(!d)d=document;
  if((p=n.indexOf("?"))>0&&parent.frames.length) {d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
  if(!(x=d[n])&&d.all)x=d.all[n];
  for(i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
  for(i=0;!x&&d.layers&&i<d.layers.length;i++)  x=getObj(n,d.layers[i].document);
  if(!x && d.getElementById) x=d.getElementById(n);
  return x;
}


function findPosX(obj) {
        var curleft = 0;
        if (document.getElementById || document.all) {
                while (obj.offsetParent) { curleft += obj.offsetLeft; obj = obj.offsetParent;}
        }
        else if (document.layers) { curleft += obj.x; }
        return curleft;
}


function findPosY(obj) {
        var curtop = 0;
        if (document.getElementById || document.all) {
                while (obj.offsetParent) { curtop += obj.offsetTop; obj = obj.offsetParent; }
        }
        else if (document.layers) {curtop += obj.y;}
        return curtop;
}

function openPopUp(winInst,currObj,baseURL,winName,width,height,features) {
        var left=parseInt(findPosX(currObj))
        var top=parseInt(findPosY(currObj))

        if (window.navigator.appName!="Opera") top+=parseInt(currObj.offsetHeight)
        else top+=(parseInt(currObj.offsetHeight)*2)+10
        if (browser_ie) {
                top+=window.screenTop-document.body.scrollTop
                left-=document.body.scrollLeft
                if (top+height+30>window.screen.height)
                        top=findPosY(currObj)+window.screenTop-height-30
                if (left+width>window.screen.width)
                        left=findPosX(currObj)+window.screenLeft-width
        } else if (browser_nn4 || browser_nn6) {
                top+=(scrY-pgeY)
                left+=(scrX-pgeX)
                if (top+height+30>window.screen.height)
                        top=findPosY(currObj)+(scrY-pgeY)-height-30
                if (left+width>window.screen.width)
                        left=findPosX(currObj)+(scrX-pgeX)-width
        }

        features="width="+width+",height="+height+",top="+top+",left="+left+";"+features
        eval(winInst+'=window.open("'+baseURL+'","'+winName+'","'+features+'")')
}

var scrX=0,scrY=0,pgeX=0,pgeY=0;

if (browser_nn4 || browser_nn6) {
        document.addEventListener("click",popUpListener,true)
}

function popUpListener(ev) {
        if (browser_nn4 || browser_nn6) {
                scrX=ev.screenX
                scrY=ev.screenY
                pgeX=ev.pageX
                pgeY=ev.pageY
        }
}


ScrollEffect = function(){ };
ScrollEffect.lengthcount=202;
ScrollEffect.closelimit=0;
ScrollEffect.limit=0;


function just(){
        ig=getObj("company");
        if(ScrollEffect.lengthcount > ScrollEffect.closelimit ){closet();return;}
        ig.style.display="block";
        ig.style.height=ScrollEffect.lengthcount+'px';
        ScrollEffect.lengthcount=ScrollEffect.lengthcount+10;
        if(ScrollEffect.lengthcount < ScrollEffect.limit){setTimeout("just()",25);}
        else{ getObj("innerLayer").style.display="block";return;}
}

function closet(){
        ig=getObj("company");
        getObj("innerLayer").style.display="none";
        ScrollEffect.lengthcount=ScrollEffect.lengthcount-10;
        ig.style.height=ScrollEffect.lengthcount+'px';
        if(ScrollEffect.lengthcount<20){ig.style.display="none";return;}
        else{setTimeout("closet()", 25);}
}


function fnDown(obj){
        var tagName = document.getElementById(obj);
        document.EditView.description.value = document.getElementById('summary').innerHTML;
        document.EditView.employees.value = getObj('emp').value;
        document.EditView.website.value = getObj('site').value;
        document.EditView.phone.value = getObj('Phone').value;
        document.EditView.fax.value = getObj('Fax').value;
        document.EditView.bill_street.value = getObj('address').value;
        if(tagName.style.display == 'none')
                tagName.style.display = 'block';
        else
                tagName.style.display = 'none';
}

