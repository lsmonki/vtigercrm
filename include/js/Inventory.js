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

function productPickList(currObj) {
	var trObj=currObj.parentNode.parentNode
	var rowId=parseInt(trObj.id.substr(trObj.id.indexOf("w")+1,trObj.id.length))
	window.open("index.php?module=Products&action=Popup&html=Popup_picker&form=HelpDeskEditView&popuptype=inventory_prod&curr_row="+rowId,"productWin","width=600,height=400,resizable=1,scrollbars=1,top=150,left=200");
}

function priceBookPickList(currObj) {
	var trObj=currObj.parentNode.parentNode
	var rowId=parseInt(trObj.id.substr(trObj.id.indexOf("w")+1,trObj.id.length))
	window.open("index.php?module=PriceBooks&action=Popup&html=Popup_picker&form=EditView&popuptype=inventory_pb&fldname=txtListPrice"+rowId+"&productid="+getObj("hdnProductId"+rowId).value,"priceBookWin","width=600,height=400,resizable=1,scrollbars=1,top=150,left=200");
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

function delRow(rowId) {
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
}


function calcTotal(currObj) {
	var trObj=currObj.parentNode.parentNode
	var rowId=parseInt(trObj.id.substr(trObj.id.indexOf("w")+1,trObj.id.length))
	var total=eval(getObj("txtQty"+rowId).value*getObj("txtListPrice"+rowId).value)
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
	function validate() {
		if(!formValidate())
			return false
				if(!FindDuplicate())
					return false;
		if(rowCnt == 0)
		{
			alert('No product is selected. Select atleast one Product');
			return false;
		}


		for (var i=1;i<=rowCnt;i++) {
			if (!emptyCheck("txtProduct"+i,"Product","text")) return false
				if (!emptyCheck("txtQty"+i,"Qty","text")) return false
					if (!numValidate("txtQty"+i,"Qty","any")) return false
						if (!numConstComp("txtQty"+i,"Qty","GE","1")) return false
							if (!emptyCheck("txtListPrice"+i,"List Price","text")) return false
								if (!numValidate("txtListPrice"+i,"List Price","any")) return false           }
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
			if(product_id[i] == product_id[j])
			{
				alert("You have selected < "+getObj("txtProduct"+j).value+" > more than once in line items  "+i+" & "+j+".\n Please select it once and change the Qty");
				return false;
			}
		}
	}
        return true;
}

