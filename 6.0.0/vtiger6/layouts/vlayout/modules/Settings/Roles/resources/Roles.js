/*+***********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 *************************************************************************************/
var Settings_Roles_Js = {
	
	initDeleteView: function() {
		
		jQuery('[data-action="popup"]').click(function(e) {
			e.preventDefault();
			var target = $(e.currentTarget);
			var field  = target.data('field');
			
			// TODO simiplify by pushing the retrieveSelectedRecords to library
			var popupInstance = Vtiger_Popup_Js.getInstance()();
			popupInstance.show(target.data('url'));
			popupInstance.retrieveSelectedRecords(function(data) {
				try {
					data = JSON.parse(data);
				} catch (e) {}
				
				if (typeof data == 'object') {
					jQuery('[name="'+field+'_display"]').val(data.label);
					data = data.value;
				}
				jQuery('[name="'+field+'"]').val(data);
			});
		});
	},
	
	initPopupView: function() {
		jQuery('.draggable').click(function(e){
			var target = $(e.currentTarget);
			// jquery_windowmsg plugin expects second parameter to be string.
			jQuery.triggerParentEvent('postSelection', JSON.stringify({value: target.closest('li').data('roleid'), label: target.text()}));
			self.close();
		});
	},
	
	initEditView: function() {
		
		function applyMoveChanges(roleid, parent_roleid) {
			var params = {
				module: 'Roles',
				action: 'MoveAjax',
				parent: 'Settings',
				record: roleid,
				parent_roleid: parent_roleid
			}
			
			AppConnector.request(params).then(function(res) {
				if (!res.success) {
					alert(app.vtranslate('JS_FAILED_TO_SAVE'));
					window.location.reload();
				}
			});
		}
		
		jQuery('[rel="tooltip"]').tooltip();
		
		function modalActionHandler(event) {
			var target = $(event.currentTarget);
			app.showModalWindow(null, target.data('url'));
		}
		
		jQuery('[data-action="modal"]').click(modalActionHandler);
		
		jQuery('.toolbar').hide();
		
		jQuery('.toolbar-handle').bind('mouseover', function(e){
			var target = $(e.currentTarget);
			jQuery('.toolbar', target).css({display: 'inline'});
		});
		jQuery('.toolbar-handle').bind('mouseout', function(e){
			var target = $(e.currentTarget);
			jQuery('.toolbar', target).hide();
		});
		
		jQuery('.draggable').draggable({
			containment: '.treeView',
			helper: function(event) {
				var target = $(event.currentTarget);
				var targetGroup = target.closest('li');
				var timestamp = +(new Date());

				var container = $('<div/>');
				container.data('refid', timestamp);
				container.html(targetGroup.clone());

				// For later reference we shall assign the id before we return
				targetGroup.attr('data-grouprefid', timestamp);

				return container;
			}
		});
		jQuery('.droppable').droppable({
			hoverClass: 'btn-primary',
			tolerance: 'pointer',
			drop: function(event, ui) {
				var container = $(ui.helper);
				var referenceid = container.data('refid');
				var sourceGroup = $('[data-grouprefid="'+referenceid+'"]');
				
				var thisWrapper = $(this).closest('div');

				var targetRole  = thisWrapper.closest('li').data('role');
				var targetRoleId= thisWrapper.closest('li').data('roleid');
				var sourceRole   = sourceGroup.data('role');
				var sourceRoleId = sourceGroup.data('roleid');

				// Attempt to push parent-into-its own child hierarchy?
				if (targetRole.indexOf(sourceRole) == 0) {
					// Sorry
					return;
				}
				sourceGroup.appendTo(thisWrapper.next('ul'));

				applyMoveChanges(sourceRoleId, targetRoleId);
			}
		});
	}
}