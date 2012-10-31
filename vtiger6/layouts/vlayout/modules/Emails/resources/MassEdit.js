/*+***********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 *************************************************************************************/
jQuery.Class("Emails_MassEdit_Js",{},{

	ckEditorInstance : false,
	massEmailForm : false,
	saved : "SAVED",
	sent : "SENT",
	attachmentsFileSize : 0,
	documentsFileSize : 0,

	/**
	 * function to display the email form
	 * return UI
	 */
	showComposeEmailForm : function(params){
	    app.hideModalWindow();
		var popupInstance = Vtiger_Popup_Js.getInstance();
		popupInstance.show(params);
	},

	/*
	 * Function to get the Mass Email Form
	 */
	getMassEmailForm : function(){
		if(this.massEmailForm == false){
			this.massEmailForm = jQuery("#massEmailForm");
		}
		return this.massEmailForm;
	},

	/**
	 * function to call the registerevents of send Email step1
	 */
	registerEmailFieldSelectionEvent : function(){
		var thisInstance = this;
		var selectEmailForm = jQuery("#SendEmailFormStep1");
		selectEmailForm.on('submit',function(e){
			var form = jQuery(e.currentTarget);
			var params = form.serializeFormData();
			thisInstance.showComposeEmailForm(params);
			e.preventDefault();
		});
	},

	/*
		* Function to register the event of send email
		*/
	registerSendEmailEvent : function(){
		this.getMassEmailForm().on('submit',function(e){
			//TODO close the window once the mail has sent
//			var result = jQuery(e.currentTarget).validationEngine('validate') ;
//			if(result == true){
//				return true;
//				window.close();
//			}
//			return false;
		});
	},
	setAttachmentsFileSize : function(element){
		this.attachmentsFileSize += element.get(0).files[0].size;
	},

	removeAttachmentFileSize : function(element) {
		this.attachmentsFileSize -= element.get(0).files[0].size;
	},

	getAttachmentsFileSize : function(){
		return this.attachmentsFileSize;
	},
	setDocumentsFileSize : function(documentSize){
		this.documentsFileSize += documentSize;
	},
	getDocumentsFileSize : function(){
		return this.documentsFileSize;
	},

	getTotalAttachmentsSize : function(){
		return this.getAttachmentsFileSize()+this.getDocumentsFileSize();
	},

	getMaxUploadSize : function(){
		return jQuery('#maxUploadSize').val();
	},

	removeDocumentsFileSize : function(){
		//TODO  update the document file size when you delete any documents from the list
	},

	removeAttachmentsFileSize : function(){
		//TODO  update the attachment file size when you delete any attachment from the list
	},

	fileAfterSelectHandler : function(element, value, master_element){
		var thisInstance = this;
		element = jQuery(element);
		thisInstance.setAttachmentsFileSize(element);
		var totalAttachmentsSize = thisInstance.getTotalAttachmentsSize();
		var maxUploadSize = thisInstance.getMaxUploadSize();
		if(totalAttachmentsSize > maxUploadSize){
			alert("max file Upload exceeds");
			this.removeAttachmentFileSize(element);
			master_element.list.find('.MultiFile-label:last').find('.MultiFile-remove').trigger('click');
		} 
		return true;
	},
	/*
	 * Function to register the events for getting the values
	 */
	registerEventsToGetFlagValue : function(){
		var thisInstance = this;
		jQuery('#saveDraft').on('click',function(){
			jQuery('#flag').val(thisInstance.saved);
		});
		jQuery('#sendEmail').on('click',function(e){
			jQuery('#flag').val(thisInstance.sent);
		});
	},

	/*
	 * Function to register the events for bcc and cc links
	 */
	registerCcAndBccEvents : function(){
		jQuery('#ccLink').on('click',function(e){
			jQuery('#ccContainer').show();
			jQuery(e.currentTarget).hide();
		});
		jQuery('#bccLink').on('click',function(e){
			jQuery('#bccContainer').show();
			jQuery(e.currentTarget).hide();
		});
	},

	/*
	 * Function to register the send email template event
	 */
	registerSendEmailTemplateEvent : function(){

		var thisInstance = this;
		jQuery('#selectEmailTemplate').on('click',function(e){
			var url = jQuery(e.currentTarget).data('url');
			var popupInstance = Vtiger_Popup_Js.getInstance();
			popupInstance.show(url,function(data){
				var responseData = JSON.parse(data);
				for(var id in responseData){
					var selectedName = responseData[id].name;
					var selectedTemplateBody = responseData[id].info;
				}
				thisInstance.ckEditorInstance.loadContentsInCkeditor(selectedTemplateBody);
				jQuery('#subject').val(selectedName);
			},'tempalteWindow');
		});
	},
	getDocumentAttachmentElement : function(selectedFileName,id){
		return '<div class="MultiFile-label"><a class="removeAttachment" data-id='+id+'>x </a><span>'+selectedFileName+'</span></div>';
	},
	registerBrowseCrmEvent : function(){
		var thisInstance = this;
		jQuery('#browseCrm').on('click',function(e){
			var documentsIds = new Array();
			var url = jQuery(e.currentTarget).data('url');
			var popupInstance = Vtiger_Popup_Js.getInstance();
			popupInstance.show(url,function(data){
				var responseData = JSON.parse(data);
				for(var id in responseData){
					documentsIds.push(id);
					var selectedFileName = responseData[id].info['filename'];
					var attachmentElement = thisInstance.getDocumentAttachmentElement(selectedFileName,id);
					//TODO handle the validation if the size exceeds 5mb before appending.
					jQuery(attachmentElement).appendTo(jQuery('.MultiFile-list'));
					var selectFileSize =  responseData[id].info['filesize'];
					thisInstance.setDocumentsFileSize(selectFileSize);
				}
				thisInstance.writeDocumentIds(documentsIds)
			},'browseCrmWindow');
		});
	},

	writeDocumentIds :function(documentsIds){
		jQuery('#documentIds').val(JSON.stringify(documentsIds));
	},
	registerRemoveAttachmentEvent : function(){
		var thisInstance = this;
		this.getMassEmailForm().on('click','.removeAttachment',function(e){
			var currentTarget = jQuery(e.currentTarget);
			var documentIdsArray = JSON.parse(jQuery('#documentIds').val());
			var id = currentTarget.data('id');
			documentIdsArray.splice( jQuery.inArray(id, documentIdsArray), 1 );
			thisInstance.writeDocumentIds(documentIdsArray);
			currentTarget.closest('.MultiFile-label').remove();
		});
	},

	registerEvents : function(){
		var thisInstance = this;
		//this.registerSendEmailEvent();
		var composeEmailForm = this.getMassEmailForm();
		if(composeEmailForm.length > 0){
			jQuery("input[type=file].multiFile").MultiFile({
				'afterFileSelect' : function(element, value, master_element){
					thisInstance.fileAfterSelectHandler(element, value, master_element);
				}
			});
			this.getMassEmailForm().validationEngine();
			var textAreaElement = jQuery('#description');
			this.ckEditorInstance = new Vtiger_CkEditor_Js();
			this.ckEditorInstance.loadCkEditor(textAreaElement);
			this.registerRemoveAttachmentEvent();
			this.registerEventsToGetFlagValue();
			this.registerCcAndBccEvents();
			this.registerSendEmailTemplateEvent();
			this.registerBrowseCrmEvent();
		}
	}
});
//On Page Load
jQuery(document).ready(function() {
	var emailMassEditInstance = new Emails_MassEdit_Js();
	emailMassEditInstance.registerEvents();
});

