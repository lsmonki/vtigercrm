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

function productPickList(currObj,module) {
	var trObj=currObj.parentNode.parentNode
	var rowId=parseInt(trObj.id.substr(trObj.id.indexOf("w")+1,trObj.id.length))

	popuptype = 'inventory_prod';
	if(module == 'PurchaseOrder')
		popuptype = 'inventory_prod_po';

	window.open("index.php?module=Products&action=Popup&html=Popup_picker&form=HelpDeskEditView&popuptype="+popuptype+"&curr_row="+rowId,"productWin","width=640,height=565,resizable=0,scrollbars=0,status=1,top=150,left=200");
}

function priceBookPickList(currObj) {
	var trObj=currObj.parentNode.parentNode
	var rowId=parseInt(trObj.id.substr(trObj.id.indexOf("w")+1,trObj.id.length))
	window.open("index.php?module=PriceBooks&action=Popup&html=Popup_picker&form=EditView&popuptype=inventory_pb&fldname=txtListPrice"+rowId+"&productid="+getObj("hdnProductId"+rowId).value,"priceBookWin","width=640,height=565,resizable=0,scrollbars=0,top=150,left=200");
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

	calcTaxTotal(currObj)

	var trObj=currObj.parentNode.parentNode
	var rowId=parseInt(trObj.id.substr(trObj.id.indexOf("w")+1,trObj.id.length))

	var tax_total = 0
	if(getObj("txtTaxTotal"+rowId).value > 0)
		tax_total = getObj("txtTaxTotal"+rowId).value;

	var total=eval(getObj("txtQty"+rowId).value*getObj("txtListPrice"+rowId).value)+eval(tax_total)

	getObj("total"+rowId).innerHTML=getObj("hdnTotal"+rowId).value=roundValue(total.toString())
	calcGrandTotal()
}

function calcGrandTotal() {
	var subTotal=0,grandTotal=0;
	for (var i=1;i<=rowCnt;i++) {
		if (getObj("hdnTotal"+i).value=="") 
			getObj("hdnTotal"+i).value=0
		if (!isNaN(getObj("hdnTotal"+i).value)) 
			subTotal+=parseFloat(getObj("hdnTotal"+i).value)
	}
	
	grandTotal=subTotal+parseFloat(getObj("txtTax").value)+parseFloat(getObj("txtAdjustment").value)
	
	getObj("subTotal").value=getObj("hdnSubTotal").value=roundValue(subTotal.toString())
	getObj("grandTotal").value=getObj("hdnGrandTotal").value=roundValue(grandTotal.toString())
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
		alert('No product is selected. Select atleast one Product');
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
	product_name[1] = getObj("txtProduct"+1).value;
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
				return false;
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

function calcTaxTotal(currObj)
{
	var trObj=currObj.parentNode.parentNode
	var rowId=parseInt(trObj.id.substr(trObj.id.indexOf("w")+1,trObj.id.length))

	var vat_total=0.0;
	var sales_total=0.0;
	var service_total=0.0;

	var temp_total = eval(getObj("txtQty"+rowId).value*getObj("txtListPrice"+rowId).value);

	if(getObj("txtVATTax"+rowId).value > 0)
		vat_total = eval(getObj("txtVATTax"+rowId).value*temp_total/100.00)
	if(getObj("txtSalesTax"+rowId).value > 0)
		sales_total=eval(getObj("txtSalesTax"+rowId).value*temp_total/100.00)
	if(getObj("txtServiceTax"+rowId).value > 0)
		service_total=eval(getObj("txtServiceTax"+rowId).value*temp_total/100.00)

	var total = vat_total + sales_total + service_total

	getObj("txtVATTaxTotal"+rowId).value = vat_total
	getObj("txtSalesTaxTotal"+rowId).value = sales_total
	getObj("txtServiceTaxTotal"+rowId).value = service_total

	//set the tax total
	getObj("txtTaxTotal"+rowId).value=getObj("hdnTaxTotal"+rowId).value=roundValue(total.toString())
}
