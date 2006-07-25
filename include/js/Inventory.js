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
	var max_row_count = document.getElementById('proTab').rows.length;
        max_row_count = eval(max_row_count)-2;

	//set the total number of products
	document.EditView.totalProductCount.value = max_row_count;	
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



function calcTotal() {

	var max_row_count = document.getElementById('proTab').rows.length;
	max_row_count = eval(max_row_count)-2;//Because the table has two header rows. so we will reduce two from row length

	for(var i=1;i<=max_row_count;i++)
	{
		rowId = i;

		var total=eval(getObj("qty"+rowId).value*getObj("listPrice"+rowId).value);
		getObj("productTotal"+rowId).innerHTML=roundValue(total.toString())

		var totalAfterDiscount = eval(total-document.getElementById("discountTotal"+rowId).innerHTML);
		getObj("totalAfterDiscount"+rowId).innerHTML=roundValue(totalAfterDiscount.toString())

		var netprice = 0.00;
		var tax_type = document.getElementById("taxtype").value;
		//if the tax type is individual then add the tax with net price
		if(tax_type == 'individual')
			netprice = totalAfterDiscount+eval(document.getElementById("taxTotal"+rowId).innerHTML);
		else
			netprice = totalAfterDiscount;

		getObj("netPrice"+rowId).innerHTML=roundValue(netprice.toString())

	}
	calcGrandTotal()
}

function calcGrandTotal() {
	var netTotal = 0.0, grandTotal = 0.0;
	var discountTotal_final = 0.0, finalTax = 0.0, sh_amount = 0.0, sh_tax = 0.0, adjustment = 0.0;

	var taxtype = document.getElementById("taxtype").value;

	var max_row_count = document.getElementById('proTab').rows.length;
	max_row_count = eval(max_row_count)-2;//Because the table has two header rows. so we will reduce two from row length

	for (var i=1;i<=max_row_count;i++) 
	{
		if (document.getElementById("netPrice"+i).innerHTML=="") 
			document.getElementById("netPrice"+i).innerHTML = 0.0
		if (!isNaN(document.getElementById("netPrice"+i).innerHTML)) 
			netTotal += parseFloat(document.getElementById("netPrice"+i).innerHTML)
	}

	document.getElementById("netTotal").innerHTML = netTotal;
	document.getElementById("subtotal").value = netTotal;

	//Tax and Adjustment values will be taken when they are valid integer or decimal values
	//if(/^-?(0|[1-9]{1}\d{0,})(\.(\d{1}\d{0,}))?$/.test(document.getElementById("txtTax").value))
	//	txtTaxVal = parseFloat(getObj("txtTax").value);	
	//if(/^-?(0|[1-9]{1}\d{0,})(\.(\d{1}\d{0,}))?$/.test(document.getElementById("txtAdjustment").value))
	//	txtAdjVal = parseFloat(getObj("txtAdjustment").value);

	discountTotal_final = document.getElementById("discountTotal_final").innerHTML

	//get the final tax based on the group or individual tax selection
	var taxtype = document.getElementById("taxtype").value;
	if(taxtype = 'group')
		finalTax = document.getElementById("tax_final").innerHTML

	sh_amount = getObj("shipping_handling_charge").value
	sh_tax = document.getElementById("shipping_handling_tax").innerHTML

	adjustment = getObj("adjustment").value

	//Add or substract the adjustment based on selection
	adj_type = document.getElementById("adjustmentType").value;
	if(adj_type == '+')
		grandTotal = eval(netTotal)-eval(discountTotal_final)+eval(finalTax)+eval(sh_amount)+eval(sh_tax)+eval(adjustment)
	else
		grandTotal = eval(netTotal)-eval(discountTotal_final)+eval(finalTax)+eval(sh_amount)+eval(sh_tax)-eval(adjustment)

	document.getElementById("grandTotal").innerHTML = roundValue(grandTotal.toString())
	document.getElementById("total").value = roundValue(grandTotal.toString())
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
		if (!emptyCheck("productName"+i,"Product","text")) return false
		if (!emptyCheck("qty"+i,"Qty","text")) return false
		if (!numValidate("qty"+i,"Qty","any")) return false
		if (!numConstComp("qty"+i,"Qty","GE","1")) return false
		if (!emptyCheck("listPrice"+i,"List Price","text")) return false
		if (!numValidate("listPrice"+i,"List Price","any")) return false           
	}
	if (getObj("txtTax").value.replace(/^\s+/g, '').replace(/\s+$/g, '').length>0)
	if (!numValidate("txtTax","Tax","any")) return false
	if (getObj("txtAdjustment").value.replace(/^\s+/g, '').replace(/\s+$/g, '').length>0)
	if (!numValidate("txtAdjustment","Adjustment","any")) return false

	return true    
}

function FindDuplicate()
{
	var max_row_count = document.getElementById('proTab').rows.length;
        max_row_count = eval(max_row_count)-2;//As the table has two header rows, we will reduce two from row length

	var product_id = new Array(max_row_count-1);
	var product_name = new Array(max_row_count-1);
	product_id[1] = getObj("hdnProductId"+1).value;
	product_name[1] = getObj("productName"+1).value;
	for (var i=1;i<=max_row_count;i++)
	{
		for(var j=i+1;j<=max_row_count;j++)
		{
			if(i == 1)
			{
				product_id[j] = getObj("hdnProductId"+j).value;
			}
			if(product_id[i] == product_id[j] && product_id[i] != '')
			{
				alert("You have selected < "+getObj("productName"+j).value+" > more than once in line items  "+i+" & "+j+".\n It is advisable to select the product just once but change the Qty. Thank You");
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

function loadTaxes_Ajax(curr_row)
{
	//Retrieve all the tax values for the currently selected product
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
function fnAddProductRow(module,image_path){
	rowCnt++;

	var tableName = document.getElementById('proTab');
	var prev = tableName.rows.length;
    	var count = rowCnt;//prev;
    	var row = tableName.insertRow(prev);
		row.id = "row"+count;
		
	
	
	var colone = row.insertCell(0);
	var coltwo = row.insertCell(1);
	if(module == "PurchaseOrder"){
		var colfour = row.insertCell(2);
		var colfive = row.insertCell(3);
		var colsix = row.insertCell(4);
		var colseven = row.insertCell(5);
	}
	else{
		var colthree = row.insertCell(2);
		var colfour = row.insertCell(3);
		var colfive = row.insertCell(4);
		var colsix = row.insertCell(5);
		var colseven = row.insertCell(6);
	}
	
	//Delete link
	colone.className = "crmTableRow small";
	colone.innerHTML='<input id="hdnRowStatus'+count+'" name="hdnRowStatus'+count+'" type="hidden"><img src="themes/blue/images/delete.gif" border="0" onclick="">';

	//Product Name with Popup image to select product
	coltwo.className = "crmTableRow small"
	coltwo.innerHTML= '<table border="0" cellpadding="1" cellspacing="0" width="100%"><tr><td class="small"><input id="productName'+count+'" name="productName'+count+'" class="small" style="width: 70%;" value="" readonly="readonly" type="text"><input id="hdnProductId'+count+'" name="hdnProductId'+count+'" value="" type="hidden"><img src="'+image_path+'search.gif" style="cursor: pointer;" onclick="productPickList(this,\''+module+'\','+count+')" align="absmiddle"></td></tr><tr><td class="small" id="setComment'+count+'">[<a href="javascript:;" onclick="">Add Comment</a>]</td></tr></tbody></table>';	
	
	//Quantity In Stock - only for SO, Quotes and Invoice
	if(module != "PurchaseOrder"){
	colthree.className = "crmTableRow small"
	colthree.innerHTML='<span id="qtyInStock'+count+'">&nbsp;</span>';
	}
	
	//Quantiry
	colfour.className = "crmTableRow small"
	colfour.innerHTML='<input id="qty'+count+'" name="qty'+count+'" type="text" class="small " style="width:50px" onfocus="this.className=\'detailedViewTextBoxOn\'" onBlur="FindDuplicate(); settotalnoofrows(); calcTotal(); loadTaxes_Ajax('+count+');" value=""/>';
	
	//List Price with Discount, Total after Discount and Tax labels
	colfive.className = "crmTableRow small"
	colfive.innerHTML='<table width="100%" cellpadding="0" cellspacing="0"><tr><td align="right"><input id="listPrice'+count+'" name="listPrice'+count+'" value="0.00" type="text" class="small " style="width:70px" onBlur="calcTotal()"/>&nbsp;<img src="'+image_path+'pricebook.gif" onclick="priceBookPickList(this,'+count+')"></td></tr><tr><td align="right" style="padding:5px;" nowrap>		(-)&nbsp;<b><a href="javascript:doNothing();" onClick="displayCoords(event,\'discount_div'+count+'\',\'discount\','+count+')" >Discount</a> : </b><div class=\"discountUI\" id=\"discount_div'+count+'"><input type="hidden" id="discount_type'+count+'" name="discount_type'+count+'" value=""><table width="100%" border="0" cellpadding="5" cellspacing="0" class="small"><tr><td id="discount_div_title'+count+'" nowrap align="left" ></td><td align="right"><img src="'+image_path+'close.gif" border="0" onClick="fnHidePopDiv(\'discount_div'+count+'\')" style="cursor:pointer;"></td></tr><tr><td align="left" class="lineOnTop"><input type="radio" name="discount'+count+'" checked onclick="setDiscount(this,'+count+')">&nbsp; Zero Discount</td><td class="lineOnTop">&nbsp;</td></tr><tr><td align="left"><input type="radio" name="discount'+count+'" onclick="setDiscount(this,'+count+')">&nbsp; % of Price </td><td align="right"><input type="text" class="small" size="2" id="discount_percentage'+count+'" name="discount_percentage'+count+'" value="0" style="visibility:hidden" onBlur="setDiscount(this,'+count+')">&nbsp;%</td></tr><tr><td align="left" nowrap><input type="radio" name="discount'+count+'" onclick="setDiscount(this,'+count+')">&nbsp; Direct Price Redunction</td><td align="right"><input type="text" id="discount_amount'+count+'" name="discount_amount'+count+'" size="5" value="0" style="visibility:hidden" onBlur="setDiscount(this,'+count+')"></td></tr></table></div></td></tr><tr> <td align="right" style="padding:5px;" nowrap><b>Total After Discount :</b></td></tr><tr id="individual_tax_row'+count+'" class="TaxShow"><td align="right" style="padding:5px;" nowrap>(+)&nbsp;<b><a href="javascript:doNothing();" onClick="displayCoords(event,\'tax_div'+count+'\',\'tax\','+count+')" >Tax </a> : </b><div class="discountUI" id="tax_div'+count+'"></div></td></tr></table> ';

	//Total and Discount, Total after Discount and Tax details
	colsix.className = "crmTableRow small"
	colsix.innerHTML = '<table width="100%" cellpadding="5" cellspacing="0"><tr><td id="productTotal'+count+'" align="right">&nbsp;</td></tr><tr><td id="discountTotal'+count+'" align="right">0.00</td></tr><tr><td id="totalAfterDiscount'+count+'" align="right">&nbsp;</td></tr><tr><td id="taxTotal'+count+'" align="right">0.00</td></tr></table>';

	//Net Price
	colseven.className = "crmTableRow small"
	colseven.innerHTML = '<span id="netPrice'+count+'"><b>&nbsp;</b></span>';
	
	//This is to show or hide the individual or group tax
	decideTaxDiv();

	calcTotal();
}

function decideTaxDiv()
{
	var taxtype = document.getElementById("taxtype").value
	if(taxtype == 'group')
		hideIndividualTaxes()
	else if(taxtype == 'individual')
		hideGroupTax()

	calcTotal();
}

function hideIndividualTaxes()
{
	var max_row_count = document.getElementById('proTab').rows.length;
	max_row_count = eval(max_row_count)-2;//Because the table has two header rows. so we will reduce two from row length

	for(var i=1;i<=max_row_count;i++)
	{
		document.getElementById("individual_tax_row"+i).className = 'TaxHide';
		document.getElementById("taxTotal"+i).style.display = 'none';
	}
	document.getElementById("group_tax_row").className = 'TaxShow';
}

function hideGroupTax()
{
	var max_row_count = document.getElementById('proTab').rows.length;
	max_row_count = eval(max_row_count)-2;//Because the table has two header rows. so we will reduce two from table row length

	for(var i=1;i<=max_row_count;i++)
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

		var discount_amount = 0.00;
		//This is to calculate the final discount
		if(curr_row == '_final')
		{
			discount_amount = eval(document.getElementById("netTotal").innerHTML)*eval(document.getElementById("discount_percentage"+curr_row).value)/eval(100);
		}
		else//This is to calculate the product discount
		{
			discount_amount = eval(document.getElementById("productTotal"+curr_row).innerHTML)*eval(document.getElementById("discount_percentage"+curr_row).value)/eval(100);
		}

		document.getElementById("discountTotal"+curr_row).innerHTML = discount_amount;
	}
	if(discount_checks[2].checked == true)
	{
		document.getElementById("discount_type"+curr_row).value = 'amount';
		document.getElementById("discount_percentage"+curr_row).style.visibility = 'hidden';
		document.getElementById("discount_amount"+curr_row).style.visibility = 'visible';
		document.getElementById("discountTotal"+curr_row).innerHTML = document.getElementById("discount_amount"+curr_row).value;
	}

	calcTotal();
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

	calcTotal();
}

function calcGroupTax()
{
	var group_tax_count = document.getElementById("group_tax_count").value;
	var net_total_after_discount = eval(document.getElementById("netTotal").innerHTML)-eval(document.getElementById("discountTotal_final").innerHTML);
	var group_tax_total = 0.00, tax_amount=0.00;

	for(var i=1;i<=group_tax_count;i++)
	{
		tax_amount = eval(net_total_after_discount)*eval(document.getElementById("group_tax_percentage"+i).value)/eval(100);
		document.getElementById("group_tax_amount"+i).value = tax_amount;
		group_tax_total = eval(group_tax_total) + eval(tax_amount);
	}

	document.getElementById("tax_final").innerHTML = group_tax_total;

	calcTotal();
}

function calcSHTax()
{
	var sh_tax_count = document.getElementById("sh_tax_count").value;
	var sh_charge = document.getElementById("shipping_handling_charge").value;
	var sh_tax_total = 0.00, tax_amount=0.00;

	for(var i=1;i<=sh_tax_count;i++)
	{
		tax_amount = eval(sh_charge)*eval(document.getElementById("sh_tax_percentage"+i).value)/eval(100);
		document.getElementById("sh_tax_amount"+i).value = tax_amount;
		sh_tax_total = eval(sh_tax_total) + eval(tax_amount);
	}

	document.getElementById("shipping_handling_tax").innerHTML = sh_tax_total;

	calcTotal();
}

function calculateInventoryTotal(currObj)
{
	//First check for duplication
	if(!FindDuplicate())
		return false;

	//loadTaxes_Ajax(currObj);


	calcTotal();

}



