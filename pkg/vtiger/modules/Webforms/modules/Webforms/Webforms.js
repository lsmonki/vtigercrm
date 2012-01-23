var Webforms ={

	confirmAction:function(msg){
		return confirm(msg);
	},
	deleteForm:function(formname,id){
		var status=Webforms.confirmAction(webforms_alert_arr["LBL_DELETE_MSG"]);
		if(!status){
			return false;
		}
		Webforms.submitForm(formname, 'index.php?module=Webforms&action=Delete&id='+id);
		return true;
	},
	editForm:function(id){
		Webforms.submitForm('action_form', 'index.php?module=Webforms&action=WebformsEditView&id='+id+'&parenttab=Settings&operation=edit');
	},
	submitForm:function(formName,action){
		document.forms[formName].action=action;
		document.forms[formName].submit();
	},
	showHideElement:function(){
		var i;
		var len=arguments.length;
		 for(i=0;i<len;i++){
			if($(arguments[i]).style.display!="none"){
				$(arguments[i]).style.display="none";
			}else{
				$(arguments[i]).style.display="inline";
			}
		}
	},

	validateForm: function(form) {
		var name=$('name').value;
		var ownerid=$('ownerid').value;
		var module=$('targetmodule').value;
		var returnurl=$('returnurl').value;;	
		if((name=="")||(name==null)||(ownerid=="")||(ownerid==null)||(module=="")||(module==null)){
			alert(webforms_alert_arr["LBL_MADATORY_FIELDS"]);
			return false;
		}
		if(returnurl.search(/http/i)!= -1 || returnurl.search(/https/i) != -1){
			alert(webforms_alert_arr["LBL_HTTP_VALIDATION"]);
			return false;
		}

		return true;
	},

	getHTMLSource:function(id){
		var url = "module=Webforms&action=WebformsAjax&file=WebformsHTMLView&ajax=true&id=" + encodeURIComponent(id);

		VtigerJS_DialogBox.block();
		new Ajax.Request('index.php', {
			queue: {
				position: 'end',
				scope: 'command'
			},
			method: 'post',
			postBody:url,
			onComplete: function(response) {
				VtigerJS_DialogBox.unblock();
				var str = response.responseText
				$('webform_source').innerText = str;
				$('webform_source').value=str;
				$('orgLay').style.display="block";
			}
		});
	},

	fetchFieldsView: function(module) {
		if((module=="")||(module==null)) return;
		var url = "module=Webforms&action=WebformsAjax&file=WebformsFieldsView&ajax=true&targetmodule=" + encodeURIComponent(module);

		VtigerJS_DialogBox.block();
		new Ajax.Request('index.php', {
			queue: {
				position: 'end',
				scope: 'command'
			},
			method: 'post',
			postBody:url,
			onComplete: function(response) {
				VtigerJS_DialogBox.unblock();
				var str = response.responseText
				$('Webforms_FieldsView').innerHTML = str;
			}
		});
	}
}

