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

function settotalnoofrows() {
	document.EditView.totalProductCount.value = rowCnt;	
}

function productPickList(currObj,module, row_no) {
	var trObj=currObj.parentNode.parentNode
	var rowId=row_no;//parseInt(trObj.id.substr(trObj.id.indexOf("w")+1,trObj.id.length))

	popuptype = 'inventory_prod';
	if(module == 'PurchaseOrder')
		popuptype = 'inventory_prod_po';

	window.open("index.php?module=Products&action=Popup&html=Popup_picker&form=HelpDeskEditView&popuptype="+popuptype+"&curr_row="+rowId,"productWin","width=640,height=565,resizable=0,scrollbars=0,status=1,top=150,left=200");
}

function priceBookPickList(currObj, row_no) {
	var trObj=currObj.parentNode.parentNode
	var rowId=row_no;//parseInt(trObj.id.substr(trObj.id.indexOf("w")+1,trObj.id.length))
	window.open("index.php?module=PriceBooks&action=Popup&html=Popup_picker&form=EditView&popuptype=inventory_pb&fldname=listPrice"+rowId+"&productid="+getObj("hdnProductId"+rowId).value,"priceBookWin","width=640,height=565,resizable=0,scrollbars=0,top=150,left=200");
}


function getProdListBody() {
	if (browser_ie) {
		var prodListBody=getObj("productList").children[0].children[0]
	} else if (browser_nn4 || browser_nn6) {
		if (getObj("productList").childNodes.item(0).tagName=="TABLE") {
			var prodListBody=getObj("productList").childNodes.item(0).childNodes.item(0)
		} else {
			var prodListBody=getObj("productList").childNodes.item(1).childNodes.item(1)
		}
	}
	return prodListBody;
}

/*function delRow(rowId) {
   var rowId=parseInt(rowId.substr(rowId.indexOf("w")+1,rowId.length))
      //removing the corresponding row
   var prodListBody=getProdListBody()
   prodListBody.removeChild(getObj("row"+rowId))
      //assigning new innerHTML after deleting a row
   var newInnerHTML="<tr class='moduleListTitle' height='20' id='tablehead'>"+getObj("tablehead").innerHTML+"</tr>"
   newInnerHTML+="<tr id='tableheadline'>"+getObj("tableheadline").innerHTML+"</tr>";
      var rowArray=new Array(rowCnt-1);
      if (browser_nn4 || browser_nn6) {
       var product=new Array(rowCnt-1)
       var qty=new Array(rowCnt-1)
       var listPrice=new Array(rowCnt-1)
       var productId=new Array(rowCnt-1)
       var total=new Array(rowCnt-1)
       var rowStatus=new Array(rowCnt-1)
   }
      for (var i=1,k=0;i<=rowId-1;i++,k++) {
       if (i%2==0) var rowClass="evenListRow"
       else var rowClass="oddListRow"
              rowArray[k]="<tr id='row"+i+"' class='"+rowClass+"'>"+getObj("row"+i).innerHTML+"</tr>"
       newInnerHTML+=rowArray[k]
              if (browser_nn4 || browser_nn6) {
           product[k]=getObj("txtProduct"+i).value
           qty[k]=getObj("txtQty"+i).value
           listPrice[k]=getObj("txtListPrice"+i).value
           total[k]=getObj("hdnTotal"+i).value
           productId[k]=getObj("hdnProductId"+i).value
           rowStatus[k]=getObj("hdnRowStatus"+i).value
       }
   }
      for (var i=rowId+1;i<=rowCnt;i++,k++) {
       rowArray[k]=getObj("row"+i).innerHTML
       var temp=rowArray[k]
       temp=temp.replace("row"+i,"row"+(i-1))
       temp=temp.replace("txtProduct"+i,"txtProduct"+(i-1))
       temp=temp.replace("txtProduct"+i,"txtProduct"+(i-1))
       temp=temp.replace("qtyInStock"+i,"qtyInStock"+(i-1))
       temp=temp.replace("txtQty"+i,"txtQty"+(i-1))
       temp=temp.replace("txtQty"+i,"txtQty"+(i-1))
       temp=temp.replace("unitPrice"+i,"unitPrice"+(i-1))
       temp=temp.replace("txtListPrice"+i,"txtListPrice"+(i-1))
       temp=temp.replace("txtListPrice"+i,"txtListPrice"+(i-1))
       temp=temp.replace("total"+i,"total"+(i-1))
       temp=temp.replace("delRow"+i,"delRow"+(i-1))
       temp=temp.replace("hdnProductId"+i,"hdnProductId"+(i-1))
       temp=temp.replace("hdnProductId"+i,"hdnProductId"+(i-1))
       temp=temp.replace("hdnRowStatus"+i,"hdnRowStatus"+(i-1))
       temp=temp.replace("hdnRowStatus"+i,"hdnRowStatus"+(i-1))
       temp=temp.replace("hdnTotal"+i,"hdnTotal"+(i-1))
       temp=temp.replace("hdnTotal"+i,"hdnTotal"+(i-1))
              if ((i-1)%2==0) var rowClass="evenListRow"
       else var rowClass="oddListRow"
              rowArray[k]="<tr id='row"+(i-1)+"' class='"+rowClass+"'>"+temp+"</tr>"
       newInnerHTML+=rowArray[k]
              if (browser_nn4 || browser_nn6) {
           product[k]=getObj("txtProduct"+i).value
           qty[k]=getObj("txtQty"+i).value
           listPrice[k]=getObj("txtListPrice"+i).value
           total[k]=getObj("hdnTotal"+i).value
           productId[k]=getObj("hdnProductId"+i).value
           rowStatus[k]=getObj("hdnRowStatus"+i).value
       }           }

   var prodListBody=getProdListBody()
   prodList.innerHTML=listTableStart+newInnerHTML+"</table>"

   rowCnt--

   for (var i=1,k=0;i<=rowCnt;i++,k++) {
       if (browser_nn4 || browser_nn6) {
           getObj("txtProduct"+i).value=product[k]
           getObj("txtQty"+i).value=qty[k]
           getObj("txtListPrice"+i).value=listPrice[k]
           getObj("hdnTotal"+i).value=total[k]
           getObj("hdnProductId"+i).value=productId[k]
           getObj("hdnRowStatus"+i).value=rowStatus[k]
       }
   }

   calcGrandTotal()
}*/

/*  New Delete Function */

//  Don't take risk by changing this function 

function deleteRow(module,i)
{
	rowCnt--;
	var tableName = document.getElementById('proTab');
	var prev = tableName.rows.length;
	document.getElementById('proTab').deleteRow(i);
	for(loop_count=i+1;loop_count<prev;loop_count++)
	{

		var row_id = "row" + loop_count;
		var new_id = "row" + (loop_count - 1);

		if(module == 'PurchaseOrder')
		{						
			var stack = new Array("txtProduct","txtQty","txtListPrice","hdnTaxTotal","txtTaxTotal","hdnProductId","hdnRowStatus","hdnTotal","txtVATTax","txtVATTaxTotal","txtSalesTax","txtSalesTaxTotal","txtServiceTax","txtServiceTaxTotal");
			var stack_new = new Array("txtProduct","txtQty","txtListPrice","hdnTaxTotal","txtTaxTotal","hdnProductId","hdnRowStatus","hdnTotal","txtVATTax","txtVATTaxTotal","txtSalesTax","txtSalesTaxTotal","txtServiceTax","txtServiceTaxTotal");
		}
		else
		{
			var stack = new Array("txtProduct","qtyInStock","txtQty","txtListPrice","hdnTaxTotal","txtTaxTotal","hdnProductId","hdnRowStatus","hdnTotal","txtVATTax","txtVATTaxTotal","txtSalesTax","txtSalesTaxTotal","txtServiceTax","txtServiceTaxTotal");
			var stack_new = new Array("txtProduct","qtyInStock","txtQty","txtListPrice","hdnTaxTotal","txtTaxTotal","hdnProductId","hdnRowStatus","hdnTotal","txtVATTax","txtVATTaxTotal","txtSalesTax","txtSalesTaxTotal","txtServiceTax","txtServiceTaxTotal");
		}

		for(inner_loop=0;inner_loop<stack.length;inner_loop++)
		{
			stack_new[inner_loop] = getObj(stack[inner_loop]+loop_count).value;
		}

		document.getElementById(row_id).id=new_id;
		document.getElementById('vat'+row_id).id='vat'+new_id;
		document.getElementById('sales'+row_id).id='sales'+new_id;
		document.getElementById('service'+row_id).id='service'+new_id;

		var temp = document.getElementById(new_id).innerHTML;
		var vatTemp = document.getElementById('vat'+new_id).innerHTML;
		var salesTemp = document.getElementById('sales'+new_id).innerHTML;
		var serviceTemp = document.getElementById('service'+new_id).innerHTML;

		temp = temp.replace('txtProduct'+loop_count,'txtProduct'+(loop_count-1));
		temp = temp.replace('txtProduct'+loop_count,'txtProduct'+(loop_count-1));

		if(module != 'PurchaseOrder')
		{
			temp = temp.replace('qtyInStock'+loop_count,'qtyInStock'+(loop_count-1));
		}

		temp = temp.replace('txtQty'+loop_count,'txtQty'+(loop_count-1));
		temp = temp.replace('txtQty'+loop_count,'txtQty'+(loop_count-1));
		temp = temp.replace('unitPrice'+loop_count,'unitPrice'+(loop_count-1));
		temp = temp.replace('txtListPrice'+loop_count,'txtListPrice'+(loop_count-1));
		temp = temp.replace('txtListPrice'+loop_count,'txtListPrice'+(loop_count-1));
		temp = temp.replace('total'+loop_count,'total'+(loop_count-1));
		temp = temp.replace('hdnTaxTotal'+loop_count,'hdnTaxTotal'+(loop_count-1));
		temp = temp.replace('hdnTaxTotal'+loop_count,'hdnTaxTotal'+(loop_count-1));
		temp = temp.replace('txtTaxTotal'+loop_count,'txtTaxTotal'+(loop_count-1));
		temp = temp.replace('txtTaxTotal'+loop_count,'txtTaxTotal'+(loop_count-1));
		temp = temp.replace('hdnProductId'+loop_count,'hdnProductId'+(loop_count-1));
		temp = temp.replace('hdnProductId'+loop_count,'hdnProductId'+(loop_count-1));
		temp = temp.replace('hdnRowStatus'+loop_count,'hdnRowStatus'+(loop_count-1));
		temp = temp.replace('hdnRowStatus'+loop_count,'hdnRowStatus'+(loop_count-1));
		temp = temp.replace('hdnTotal'+loop_count,'hdnTotal'+(loop_count-1));
		temp = temp.replace('hdnTotal'+loop_count,'hdnTotal'+(loop_count-1));
		temp = temp.replace('tax_Lay'+loop_count,'tax_Lay'+(loop_count-1));
		temp = temp.replace('tax_Lay'+loop_count,'tax_Lay'+(loop_count-1));	

		vatTemp = vatTemp.replace('txtVATTax'+loop_count,'txtVATTax'+(loop_count-1));
		vatTemp = vatTemp.replace('txtVATTax'+loop_count,'txtVATTax'+(loop_count-1));
		vatTemp = vatTemp.replace('txtVATTax'+loop_count,'txtVATTax'+(loop_count-1));
		vatTemp = vatTemp.replace('txtVATTaxTotal'+loop_count,'txtVATTaxTotal'+(loop_count-1));
		vatTemp = vatTemp.replace('txtVATTaxTotal'+loop_count,'txtVATTaxTotal'+(loop_count-1));
		vatTemp = vatTemp.replace('txtVATTaxTotal'+loop_count,'txtVATTaxTotal'+(loop_count-1));
		salesTemp = salesTemp.replace('txtSalesTax'+loop_count,'txtSalesTax'+(loop_count-1));
		salesTemp = salesTemp.replace('txtSalesTax'+loop_count,'txtSalesTax'+(loop_count-1));
		salesTemp = salesTemp.replace('txtSalesTax'+loop_count,'txtSalesTax'+(loop_count-1));
		salesTemp = salesTemp.replace('txtSalesTaxTotal'+loop_count,'txtSalesTaxTotal'+(loop_count-1));
		salesTemp = salesTemp.replace('txtSalesTaxTotal'+loop_count,'txtSalesTaxTotal'+(loop_count-1));
		salesTemp = salesTemp.replace('txtSalesTaxTotal'+loop_count,'txtSalesTaxTotal'+(loop_count-1));
		serviceTemp = serviceTemp.replace('txtServiceTax'+loop_count,'txtServiceTax'+(loop_count-1));
		serviceTemp = serviceTemp.replace('txtServiceTax'+loop_count,'txtServiceTax'+(loop_count-1));
		serviceTemp = serviceTemp.replace('txtServiceTax'+loop_count,'txtServiceTax'+(loop_count-1));
		serviceTemp = serviceTemp.replace('txtServiceTaxTotal'+loop_count,'txtServiceTaxTotal'+(loop_count-1));
		serviceTemp = serviceTemp.replace('txtServiceTaxTotal'+loop_count,'txtServiceTaxTotal'+(loop_count-1));
		serviceTemp = serviceTemp.replace('txtServiceTaxTotal'+loop_count,'txtServiceTaxTotal'+(loop_count-1));

		document.getElementById(new_id).innerHTML = temp;
		document.getElementById('vat'+new_id).innerHTML = vatTemp;
		document.getElementById('sales'+new_id).innerHTML = salesTemp;
		document.getElementById('service'+new_id).innerHTML = serviceTemp;

		for(inner_loop=0;inner_loop<stack.length;inner_loop++)
		{
			getObj(stack[inner_loop]+(loop_count-1)).value = stack_new[inner_loop];
		}

	}
	calcGrandTotal()
}
/*  End */



function calcTotal(currObj) {

	var row_count = 2;

	for(var i=1;i<row_count;i++)
	{
		rowId = i;

		var trObj=currObj.parentNode.parentNode
		//var rowId=parseInt(trObj.id.substr(trObj.id.indexOf("w")+1,trObj.id.length))

		var total=eval(getObj("qty"+rowId).value*getObj("listPrice"+rowId).value);
		getObj("productTotal"+rowId).innerHTML=roundValue(total.toString())

		var totalAfterDiscount = eval(total-document.getElementById("discountTotal"+rowId).innerHTML);
		getObj("totalAfterDiscount"+rowId).innerHTML=roundValue(totalAfterDiscount.toString())

		//loadTaxes_Ajax(currObj)
		//calcDiscountTotal(currObj)

		var netprice = totalAfterDiscount+eval(document.getElementById("taxTotal"+rowId).innerHTML);
		getObj("netPrice"+rowId).innerHTML=roundValue(netprice.toString())

	}
	calcGrandTotal()
}

function calcGrandTotal() {
	var netTotal = 0.0, grandTotal = 0.0;
	var discount_final = 0.0, finalTax = 0.0, sh_amount = 0.0, sh_tax = 0.0, adjustment = 0.0;

	var max_row_count = 2;
	for (var i=1;i<max_row_count;i++) 
	{
		if (document.getElementById("netPrice"+i).innerHTML=="") 
			document.getElementById("netPrice"+i).innerHTML = 0.0
		if (!isNaN(document.getElementById("netPrice"+i).innerHTML)) 
			netTotal += parseFloat(document.getElementById("netPrice"+i).innerHTML)
	}

	document.getElementById("netTotal").innerHTML = netTotal;

	//Tax and Adjustment values will be taken when they are valid integer or decimal values
	//if(/^-?(0|[1-9]{1}\d{0,})(\.(\d{1}\d{0,}))?$/.test(document.getElementById("txtTax").value))
	//	txtTaxVal = parseFloat(getObj("txtTax").value);	
	//if(/^-?(0|[1-9]{1}\d{0,})(\.(\d{1}\d{0,}))?$/.test(document.getElementById("txtAdjustment").value))
	//	txtAdjVal = parseFloat(getObj("txtAdjustment").value);

	discount_final = document.getElementById("discount_final").innerHTML
	//get the final tax based on the group or individual tax selection
	//if()
	finalTax = document.getElementById("tax_final").innerHTML

	sh_amount = getObj("shipping_handling_charge").value
	sh_tax = document.getElementById("shipping_handling_tax").innerHTML
	adjustment = getObj("adjustment").value

	//Add or substract the adjustment based on selection
	//if()

	grandTotal = eval(netTotal)-eval(discount_final)+eval(finalTax)+eval(sh_amount)+eval(sh_tax)+eval(adjustment)

	document.getElementById("grandTotal").innerHTML = roundValue(grandTotal.toString())
}

//Method changed as per advice by jon http://forums.vtiger.com/viewtopic.php?t=4162
function roundValue(val) {
   val = parseFloat(val);
   val = Math.round(val*100)/100;
   val = val.toString();
   
   if (val.indexOf(".")<0) {
      val+=".00"
   } else {
      var dec=val.substring(val.indexOf(".")+1,val.length)
      if (dec.length>2)
         val=val.substring(0,val.indexOf("."))+"."+dec.substring(0,2)
      else if (dec.length==1)
         val=val+"0"
   }
   
   return val;
} 

//This function is used to validate the Inventory modules 
function validateInventory(module) 
{
	if(!formValidate())
		return false

	//for products, vendors and pricebook modules we won't validate the product details. here return the control
	if(module == 'Products' || module == 'Vendors' || module == 'PriceBooks')
	{
		return true;
	}


	if(!FindDuplicate())
		return false;

	if(rowCnt == 0)
	{
		alert('No product is selected. Select at least one Product');
		return false;
	}

	for (var i=1;i<=rowCnt;i++) 
	{
		if (!emptyCheck("txtProduct"+i,"Product","text")) return false
		if (!emptyCheck("txtQty"+i,"Qty","text")) return false
		if (!numValidate("txtQty"+i,"Qty","any")) return false
		if (!numConstComp("txtQty"+i,"Qty","GE","1")) return false
		if (!emptyCheck("txtListPrice"+i,"List Price","text")) return false
		if (!numValidate("txtListPrice"+i,"List Price","any")) return false           
	}
	if (getObj("txtTax").value.replace(/^\s+/g, '').replace(/\s+$/g, '').length>0)
	if (!numValidate("txtTax","Tax","any")) return false
	if (getObj("txtAdjustment").value.replace(/^\s+/g, '').replace(/\s+$/g, '').length>0)
	if (!numValidate("txtAdjustment","Adjustment","any")) return false

	return true    
}

function FindDuplicate()
{
	var product_id = new Array(rowCnt-1);
	var product_name = new Array(rowCnt-1);
	product_id[1] = getObj("hdnProductId"+1).value;
	product_name[1] = getObj("productName"+1).value;
	for (var i=1;i<=rowCnt;i++)
	{
		for(var j=i+1;j<=rowCnt;j++)
		{
			if(i == 1)
			{
				product_id[j] = getObj("hdnProductId"+j).value;
			}
			if(product_id[i] == product_id[j] && product_id[i] != '')
			{
				alert("You have selected < "+getObj("txtProduct"+j).value+" > more than once in line items  "+i+" & "+j+".\n It is advisable to select the product just once but change the Qty. Thank You");
				//return false;
			}
		}
	}
        return true;
}

function fnshow_Hide(Lay){
    var tagName = document.getElementById(Lay);
   	if(tagName.style.display == 'none')
   		tagName.style.display = 'block';
	else
		tagName.style.display = 'none';
}

function ValidateTax(txtObj)
{
	temp= /^\d+(\.\d\d*)*$/.test(document.getElementById(txtObj).value);
	if(temp == false)
		alert("Please enter Valid TAX value");
}

function loadTaxes_Ajax(currObj)
{
	//calculate tax total for all rows
	for(var i=1;i<2;i++)
	{
		var curr_row = i;
		new Ajax.Request(
			'index.php',
			{queue: {position: 'end', scope: 'command'},
				method: 'post',
				postBody: 'module=Products&action=ProductsAjax&file=InventoryTaxAjax&productid='+document.getElementById("hdnProductId"+curr_row).value+'&curr_row='+curr_row+'&productTotal='+document.getElementById('totalAfterDiscount'+curr_row).innerHTML,
				onComplete: function(response)
					{
						$("tax_div"+curr_row).innerHTML=response.responseText;
						document.getElementById("taxTotal"+curr_row).innerHTML = getObj('hdnTaxTotal'+curr_row).value;
					}
			}
		);
	}
}


function fnAddTaxConfigRow(sh){

	var table_id = 'add_tax';
	var td_id = 'td_add_tax';
	var label_name = 'addTaxLabel';
	var label_val = 'addTaxValue';
	var add_tax_flag = 'add_tax_type';

	if(sh != '' && sh == 'sh')
	{
		table_id = 'sh_add_tax';
		td_id = 'td_sh_add_tax';
		label_name = 'sh_addTaxLabel';
		label_val = 'sh_addTaxValue';
		add_tax_flag = 'sh_add_tax_type';
	}
	var tableName = document.getElementById(table_id);
	var prev = tableName.rows.length;
    	var count = rowCnt;

    	var row = tableName.insertRow(0);

	var colone = row.insertCell(0);
	var coltwo = row.insertCell(1);

	colone.className = "cellLabel small";
	coltwo.className = "cellText small";

	colone.innerHTML="<input type='text' id='"+label_name+"' name='"+label_name+"' class='txtBox'/>";
	coltwo.innerHTML="<input type='text' id='"+label_val+"' name='"+label_val+"' class='txtBox'/>";

	document.getElementById(td_id).innerHTML="<input type='submit' name='Save' value='Save' class='crmButton small save' onclick=\"this.form.action.value='TaxConfig'; this.form."+add_tax_flag+".value='true'; this.form.parenttab.value='Settings'; return validateNewTaxType();\">&nbsp;<input type='submit' name='Cancel' value='Cancel' class='crmButton small cancel' onclick=\"this.form.action.value='TaxConfig'; window.history.back();\">";
}

function validateNewTaxType()
{
	if(trim(document.getElementById("addTaxLabel").value)== '')
	{
		alert("Enter valid Tax Name");
		return false;
	}
	if(trim(document.getElementById("addTaxValue").value)== '')
	{
		alert("Enter Correct Tax Value");
		return false;
	}
	else
	{
		var temp = /^(0|[1-9]{1}\d{0,})(\.(\d{1}\d{0,}))?$/.test(document.getElementById("addTaxValue").value);
		if(!temp)
		{
			alert("Please enter positive value");
			return false;
		}
	}

	return true;
}


//Function used to add a new product row in PO, SO, Quotes and Invoice
function fnAddProductRow(module){
	rowCnt++;
	var tableName = document.getElementById('proTab');
	var prev = tableName.rows.length;
    	var count = rowCnt;//prev;

    	var row = tableName.insertRow(prev);
	row.id = "row"+count;
	if(count%2)
		row.className = "dvtCellLabel";
	else
		row.className = "dvtCellInfo";
	var colone = row.insertCell(0);
	var coltwo = row.insertCell(1);
	var colthree = row.insertCell(2);
	var colfour = row.insertCell(3);
	var colfive = row.insertCell(4);
	var colsix = row.insertCell(5);
	var colseven = row.insertCell(6);
	
	colone.style.verticalAlign = 'top';
	coltwo.style.verticalAlign = 'top';
	colthree.style.verticalAlign = 'top';
	colfour.style.verticalAlign = 'top';
	colfive.style.verticalAlign = 'top';
	colsix.style.verticalAlign = 'top';
	colseven.style.verticalAlign = 'top';
		 
	colone.innerHTML='<input type="text" id="txtProduct'+count+'" name="txtProduct'+count+'" class="txtBox" readonly/>&nbsp;<img src="themes/blue/images/search.gif" onclick="productPickList(this,\''+module+'\')" align="absmiddle" /><input type="hidden" id="hdnProductId'+count+'" name="hdnProductId'+count+'">';

	coltwo.innerHTML="<div id='qtyInStock"+count+"'>";	
	colthree.innerHTML="<input type='text' id='txtQty"+count+"' name='txtQty"+count+"' class='detailedViewTextBox' onfocus='this.className=\"detailedViewTextBoxOn\"' onBlur='this.className=\"detailedViewTextBox\"; FindDuplicate(); settotalnoofrows(); calcTotal(this);' /> ";
	colfour.innerHTML="&nbsp;</div><div id='unitPrice"+count+"'></div>";
	colfive.innerHTML="<input type='text' id='txtListPrice"+count+"' name='txtListPrice"+count+"' class='txtBox' onBlur='FindDuplicate(); settotalnoofrows(); calcTotal(this)'>&nbsp;<img src='themes/blue/images/pricebook.gif' onClick='priceBookPickList(this)' align='absmiddle' style='cursor:hand;cursor:pointer' title='Price Book' /> ";
	//Added for tax calculation
	colsix.innerHTML="<input type='text' id='txtTaxTotal"+count+"' name='txtTaxTotal"+count+"' value='' class='detailedViewTextBox' style='width:65%;'><input type='hidden' id='hdnTaxTotal"+count+"' name='hdnTaxTotal"+count+"'>&nbsp;<input type='button' name='showTax' value=' ... '  class='classBtnSmall'  onclick='fnshow_Hide(\"tax_Lay"+count+"\");'><div id='tax_Lay"+count+"' style='width:93%;position:relative;border:1px dotted #CCCCCC;display:none;background-color:#FFFFCC;top:5px;padding:5px;'><table width='100%' border='0' cellpadding='0' cellspacing='0' class='small'><tr id='vatrow"+count+"'><td align='left' width='40%' style='border:0px solid red;'><input type='text' id='txtVATTax"+count+"' name='txtVATTax"+count+"' class='txtBox' onBlur='ValidateTax(\"txtVATTax"+count+"\"); calcTotal(this);'/>%&nbsp;</td><td width='20%' align='right' style='border:0px solid red;'>&nbsp;VAT</td><td align='left' width='40%' style='border:0px solid red;'><input type='text' id='txtVATTaxTotal"+count+"' name='txtVATTaxTotal"+count+"' class='txtBox' onBlur='ValidateTax(\"txtVATTaxTotal"+count+"\"); calcTotal(this);'/></td></tr><tr id='salesrow"+count+"'><td align='left' style='border:0px solid red;'><input type='text' id='txtSalesTax"+count+"' name='txtSalesTax"+count+"' class='txtBox' onBlur='ValidateTax(\"txtSalesTax"+count+"\"); calcTotal(this);'/>%&nbsp;</td><td align='right' style='border:0px solid red;'>&nbsp;Sales</td><td align='left' style='border:0px solid red;'><input type='text' id='txtSalesTaxTotal"+count+"' name='txtSalesTaxTotal"+count+"' class='txtBox' onBlur='ValidateTax(\"txtSalesTaxTotal"+count+"\"); calcTotal(this);'/></td></tr><tr id='servicerow"+count+"'><td align='left' style='border:0px solid red;'><input type='text' id='txtServiceTax"+count+"' name='txtServiceTax"+count+"' class='txtBox' onBlur='ValidateTax(\"txtServiceTax"+count+"\"); calcTotal(this);'/>%&nbsp;</td><td align='right' style='border:0px solid red;'>&nbsp;Service</td><td align='left' style='border:0px solid red;'><input type='text' id='txtServiceTaxTotal"+count+"' name='txtServiceTaxTotal"+count+"' class='txtBox' onBlur='ValidateTax(\"txtServiceTaxTotal"+count+"\"); calcTotal(this);'/></td></tr></table></div>";

	colseven.innerHTML="&nbsp;<div id='total"+count+"' align='right'></div><input type='hidden' id='hdnTotal"+count+"' name='hdnTotal"+count+"'><input type='hidden' id='hdnRowStatus"+count+"' name='hdnRowStatus"+count+"'>";
	

}

function decideTaxDiv(currObj)
{
	var taxtype = document.getElementById("taxtype").value
	if(taxtype == 'group')
		hideIndividualTaxes(currObj)
	else if(taxtype == 'individual')
		hideGroupTax(currObj)
}

function hideIndividualTaxes(currObj)
{
	max_row_count = 2;
	for(var i=1;i<max_row_count;i++)
	{
		document.getElementById("individual_tax_row"+i).className = 'TaxHide';
		document.getElementById("taxTotal"+i).style.display = 'none';
	}
	document.getElementById("group_tax_row").className = 'TaxShow';
}

function hideGroupTax(currObj)
{
	max_row_count = 2;
	for(var i=1;i<max_row_count;i++)
	{
		document.getElementById("individual_tax_row"+i).className = 'TaxShow';
		document.getElementById("taxTotal"+i).style.display = 'block';
	}
	document.getElementById("group_tax_row").className = 'TaxHide';
}

function setDiscount(currObj,curr_row)
{
	var discount_checks = new Array();

	discount_checks = document.getElementsByName("discount"+curr_row);

	if(discount_checks[0].checked == true)
	{
		document.getElementById("discount_type"+curr_row).value = 'zero';
		document.getElementById("discount_percentage"+curr_row).style.visibility = 'hidden';
		document.getElementById("discount_amount"+curr_row).style.visibility = 'hidden';
		document.getElementById("discountTotal"+curr_row).innerHTML = 0.00;
	}
	if(discount_checks[1].checked == true)
	{
		document.getElementById("discount_type"+curr_row).value = 'percentage';
		document.getElementById("discount_percentage"+curr_row).style.visibility = 'visible';
		document.getElementById("discount_amount"+curr_row).style.visibility = 'hidden';
		document.getElementById("discountTotal"+curr_row).innerHTML = eval(document.getElementById("productTotal"+curr_row).innerHTML)*eval(document.getElementById("discount_percentage"+curr_row).value)/eval(100);
	}
	if(discount_checks[2].checked == true)
	{
		document.getElementById("discount_type"+curr_row).value = 'amount';
		document.getElementById("discount_percentage"+curr_row).style.visibility = 'hidden';
		document.getElementById("discount_amount"+curr_row).style.visibility = 'visible';
		document.getElementById("discountTotal"+curr_row).innerHTML = document.getElementById("discount_amount"+curr_row).value;
	}

	calcTotal(currObj);
}

function calcCurrentTax(tax_name, curr_row, tax_row)
{
	var product_total = document.getElementById("productTotal"+curr_row).innerHTML
	var new_tax_percent = document.getElementById(tax_name).value;

	var new_amount_lbl = document.getElementsByName("popup_tax_row"+curr_row);

	//calculate the new tax amount
	new_tax_amount = eval(product_total)*eval(new_tax_percent)/eval(100);

	//assign the new tax amount in the corresponding text box
	new_amount_lbl[tax_row].value = new_tax_amount;

	var tax_total = 0.00;
	for(var i=0;i<new_amount_lbl.length;i++)
	{
		tax_total = tax_total + eval(new_amount_lbl[i].value);
	}
	document.getElementById("taxTotal"+curr_row).innerHTML = tax_total;
}

function calculateInventoryTotal(currObj)
{
	//First check for duplication
	if(!FindDuplicate())
		return false;

	//loadTaxes_Ajax(currObj);


	calcTotal(currObj);

}



