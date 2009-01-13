/*********************************************************************************
** The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 ********************************************************************************/
/**
 * this file contains all the utility functions for notebook
 */

/**
 * this function saves the contents of the notebook and restores the div once the control is moved out of the textarea
 * @param object node - the textarea div
 */
function saveContents(node) {
	var contents = node.value;
	new Ajax.Request(
		'index.php',
		{
			queue: {position: 'end', scope: 'command'},
			method: 'post',
			postBody:'module=Home&action=SaveNotebookContents&contents='+contents,
			onComplete: function(response){
				if(response.responseText == false){
					alert("Some error has occurred during save");
				}else{
					//success
					node.style.display = 'none';
					
					temp = $('notebook_contents');
					temp.style.display = 'block';
					temp.innerHTML = contents;
					$('notebook').style.display = 'block';
				}
			}
		}
	);
}
/**
 * this function changes the div of the notebook to a textarea when double-clicked
 * @param object node - the notebook div
 */
function editContents(node) {
	var notebook = $('notebook_textarea');
	var contents = $('notebook_contents');
	
	notebook.value = contents.innerHTML;
	node.style.display = 'none';
	notebook.style.display = 'block';
	
	notebook.focus();
}

            