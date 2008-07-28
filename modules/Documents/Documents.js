/*********************************************************************************
** The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 *
 ********************************************************************************/

//Added after 504 for renaming a folder
function UpdateAjaxSave(label,fid,fldname,fileOrFolder)
{
        fldVal=$('txtbox_'+label).value;
	if(fldVal.replace(/^\s+/g, '').replace(/\s+$/g, '').length==0)
        {
                alert('The Folder name cannot be empty');
                return false;
        }
        if(fldVal.replace(/^\s+/g, '').replace(/\s+$/g, '').length>=21)
        {
                alert('Folder name is too long. Try again!');
                return false;
        }
	if(fldVal.match(/['"\\%+?]/))
        {
                alert(alert_arr.NO_SPECIAL_CHARS_DOCS);
                return false;
        }
        if(fileOrFolder == 'file')
                var url='action=DocumentsAjax&mode=ajax&file=Save&module=Documents&fileid='+fid+'&fldVal='+fldVal+'&fldname='+fldname+'&act=ajaxEdit';
        else
	{
                var foldername = encodeURIComponent(fldVal);
		foldername = foldername.replace(/^\s+/g, '').replace(/\s+$/g, '');
                foldername = foldername.replace(/&/gi,'*amp*');		
                var url='action=DocumentsAjax&mode=ajax&file=SaveFolder&module=Documents&record='+fid+'&foldername='+fldVal+'&savemode=Save';
	}
	if(fldname == 'status')
        {
                fldVal = $('txtbox_'+label).options[$('txtbox_'+label).options.selectedIndex].text;
                gtempselectedIndex = $('txtbox_'+label).options.selectedIndex;
        }
	$('status').style.display="block";
        new Ajax.Request(
                        'index.php',
                        {queue: {position: 'end', scope: 'command'},
                        method: 'post',
                        postBody: url,
                        onComplete: function(response) {
                        		var item = response.responseText;
					$('status').style.display="none";
					if(item.indexOf("Failure") > -1 )
		                        {
                		                $("lblError").innerHTML="<table cellpadding=0 cellspacing=0 border=0 width=100%><tr><td class=small bgcolor=red><font color=white size=2><b>Unable to update! Please try it again.</b></font></td></tr></table>";
		                                setTimeout(hidelblError,3000);
                		        }
					else if(item.indexOf('DUPLICATE_FOLDERNAME') > -1)
					{
						alert('Trying to duplicate an existing folder name. Please try again !');
					}
                                        else
					{
						$('dtlview_'+label).innerHTML = fldVal;
                                        	eval("hndCancel('dtlview_"+label+"','editarea_"+label+"','"+label+"')");
						if(fldname == 'status')
                        	                        $('txtbox_'+label).selectedIndex = gtempselectedIndex;
						else
                        	                        $('txtbox_'+label).value = fldVal;
						eval(item);

					}
					
                                }
                        }
                );

}

function closeFolderCreate()
{
        $('folder_id').value = '';
        $('folder_name').value = '';
        $('folder_desc').value='';
        fninvsh('orgLay')
}

function AddFolder()
{
        var fldrname=getObj('folder_name').value;
		var fldrdesc=getObj('folder_desc').value;
		if(fldrname == 'Default')
		{
			alert('Cannot create folder with that name !');
			return false;
		}
        if(fldrname.replace(/^\s+/g, '').replace(/\s+$/g, '').length==0)
        {
                alert('The Folder name cannot be empty');
                return false;
        }
        if(fldrname.replace(/^\s+/g, '').replace(/\s+$/g, '').length>=21)
        {
                alert('Folder name is too long. Try again!');
                return false;
        }
        if(fldrdesc.replace(/^\s+/g, '').replace(/\s+$/g, '').length>=51)
        {
                alert('Folder description is too long. Try again!');
                return false;
        }
	if(fldrname.match(/['"\\%+]/) || fldrdesc.match(/['"\\%+]/))
        {
                alert(alert_arr.NO_SPECIAL_CHARS_DOCS+alert_arr.NAME_DESC);
                return false;
        }	
	if(fldrname.match(/[?]+$/) || fldrname.match(/[?]+/))
	{
		alert(alert_arr.NO_SPECIAL_CHARS_DOCS);
		return false;
	}
                fninvsh('orgLay');
                var foldername = encodeURIComponent(getObj('folder_name').value);
                var folderdesc = encodeURIComponent(getObj('folder_desc').value);
		foldername = foldername.replace(/^\s+/g, '').replace(/\s+$/g, '');
                foldername = foldername.replace(/&/gi,'*amp*');
                folderdesc = folderdesc.replace(/^\s+/g, '').replace(/\s+$/g, '');
                folderdesc = folderdesc.replace(/&/gi,'*amp*');
                getObj('folder_name').value = '';
                getObj('folder_desc').value = '';
                var mode = getObj('fldrsave_mode').value;
                if(mode == 'save')
                {
                        url ='&savemode=Save&foldername='+foldername+'&folderdesc='+folderdesc;
                }
                getObj('fldrsave_mode').value = 'save';
		$('status').style.display = 'block';
                new Ajax.Request(
                                'index.php',
                                {queue: {position: 'end', scope: 'command'},
                                method: 'post',
                                postBody: 'action=DocumentsAjax&mode=ajax&file=SaveFolder&module=Documents'+url,
                                onComplete: function(response) {
                                        var item = response.responseText;
					$('status').style.display = 'none';
					if(item.indexOf('NOT_PERMITTED') > -1)
                                        {
                                           $('lblError').innerHTML = "<table cellpadding=0 cellspacing=0 border=0 width=100%><tr><td class=small bgcolor=red><font color=white size=2><b>You are not permitted to do this operation.</b></font></td></tr></table>";
                                                setTimeOutFn();
                                        }
					else if(item.indexOf('Failure') > -1)
					{
						$('lblError').innerHTML = "<table cellpadding=0 cellspacing=0 border=0 width=100%><tr><td class=small bgcolor=red><font color=white size=2><b>Unable to add Folder. Please try again.</b></font></td></tr></table>";
						setTimeOutFn();	
					}
					else if(item.indexOf('DUPLICATE_FOLDERNAME') > -1)
					{
						alert('Trying to duplicate an existing folder name. Please try again !');
					}
					else if(item.indexOf('CREATING_DEFAULT') > -1)
					{
						alert('Cannot create a folder with this name !');
					}					
					else
					{
						getObj("ListViewContents").innerHTML = item;
						reloadFrame();
					}
                                }
                        }
                );
}

function reloadFrame()
{
	$('AddFile_id').src = 'index.php?module=Documents&action=DocumentsAjax&file=AddFile';
}

function DeleteFolderCheck(folderId)
{
        gtempfolderId = folderId;
        new Ajax.Request(
                'index.php',
                {queue: {position: 'end', scope: 'command'},
                        method: 'post',
                        postBody: "module=Documents&action=DocumentsAjax&mode=ajax&file=DeleteFolder&deletechk=true&folderid="+folderId,
                        onComplete: function(response) {
                                        var item = response.responseText;
                                       	if(item.indexOf("NOT_PERMITTED") > -1)
					{
						alert("You are not permitted to execute this operation.");
						return false;
					}
					else if(item.indexOf("FAILURE") > -1)
                                        {
                                                alert('Folder should be empty to remove it!')
                                        }
					else
                                        {
                                                if(confirm("Are you sure you want to delete the folder?"))
                                                {
                                                        DeleteFolder(gtempfolderId)                            
                                                }
                                        }
                        }
                }
        );
}

function DeleteFolder(folderId)
{
	$('status').style.display = "block";
        new Ajax.Request(
                'index.php',
                {queue: {position: 'end', scope: 'command'},
                        method: 'post',
                        postBody: "module=Documents&action=DocumentsAjax&mode=ajax&file=DeleteFolder&folderid="+folderId,
                        onComplete: function(response) {
                                        var item = response.responseText;
					$('status').style.display = "none";
                                        if(item.indexOf("FAILURE") > -1)
                                                alert('Error while deleting the folder.Please try again later.')
                                        else
						$('ListViewContents').innerHTML = item;
			reloadFrame();
                        }
                }
        );
}

function MoveFile(id,foldername)
{
	fninvsh('folderLay');
        var select_options  =  document.getElementById('allselectedboxes').value;
        var x = select_options.split(";");
	    var searchurl= document.getElementById('search_url').value;
        var count=x.length
        var viewid =getviewId();
        var idstring = "";
        if (count > 1)
        {
            document.getElementById('idlist').value=select_options;
            idstring = select_options;
        }
        else
        {
            alert(alert_arr.SELECT);
            return false;
        }

       // var alert_str = alert_arr.MOVE + count +alert_arr.RECORDS;        

	if(idstring != '')
	{
		if(confirm("Are you sure you want to move the file(s) to '"+foldername+"' folder ?"))
        	{
			$('status').style.display = "block";
			new Ajax.Request(
                        'index.php',
                        {queue: {position: 'end', scope: 'command'},
                                method: 'post',
                                postBody: 'action=DocumentsAjax&file=MoveFile&from_folderid=0&module=Documents&folderid='+id+'&idlist='+idstring,
                                onComplete: function(response) {
						var item = response.responseText;
						$('status').style.display = "none";
						if(item.indexOf("NOT_PERMITTED") > -1 )                                                                     							{
							$("lblError").innerHTML="<table cellpadding=0 cellspacing=0 border=0 width=100%><tr><td class=small bgcolor=red><font color=white size=2><b>You are not permitted to execute this operation.</b></font></td></tr></table>";
							setTimeout(hidelblError,3000);
						}
						else
                                	        	getObj('ListViewContents').innerHTML = item;
                	                }

                        	}
	                );
		}else
		{
			return false;
		}
			
	}else
	{
		alert('Please select atleast one File');
		return false;
	}
}

function dldCntIncrease(fileid)
{
	new Ajax.Request(
            'index.php',
            {queue: {position: 'end', scope: 'command'},
             method: 'post',
             postBody: 'action=DocumentsAjax&mode=ajax&file=SaveFile&module=Documents&file_id='+fileid+"&act=updateDldCnt",
             onComplete: function(response) {
                }
                }
                );
}

function checkFileIntegrity()
{
	new Ajax.Request(
            'index.php',
            {queue: {position: 'end', scope: 'command'},
             method: 'post',
             postBody: 'module=Documents&action=DocumentsAjax&mode=ajax&file=SaveFile&act=checkFileIntegrity&file_id=',
             onComplete: function(response) {
             	var item = response.responseText;
                if(item.indexOf('some_files') > -1)
		{
                	alert('Some files lost integrity. They have been set to inactive !');
		}
                else if(item.indexOf('no_files') > -1)
		{
                	alert('All active files are available for download');
		}
                }
                }
                );
}

function checkFileIntegrityDetailView(fileid)
{
	$('vtbusy_integrity_info').style.display='';
	new Ajax.Request(
            'index.php',
            {queue: {position: 'end', scope: 'command'},
             method: 'post',
             postBody: 'module=Documents&action=DocumentsAjax&mode=ajax&file=SaveFile&act=checkFileIntegrityDetailView&file_id='+fileid,
             onComplete: function(response) {
             	var item = response.responseText;
		if(item.indexOf('not_this_file') > -1)
		{
					$('vtbusy_integrity_info').style.display='none';
					$('integrity_result').innerHTML='<br><br>&nbsp;&nbsp;&nbsp;<font style=color:green>File available for download</font>';
					$('integrity_result').style.display='';
					setTimeout(hideresult,4000);
		}
                else if(item.indexOf('file_not_available') > -1)
		{
					$('vtbusy_integrity_info').style.display='none';
					$('integrity_result').innerHTML='<br><br>&nbsp;&nbsp;&nbsp;<font style=color:red>This file lost integrity. Not available for download!</font>';
					$('integrity_result').style.display='';	
					setTimeout(hideresult,6000);	
		}
                else if(item.indexOf('lost_integrity') > -1)
		{
					$('vtbusy_integrity_info').style.display='none';
					$('integrity_result').innerHTML='<br><br>&nbsp;&nbsp;&nbsp;<font style=color:red>This file lost integrity. It will no more be available for download!</font>';
					$('integrity_result').style.display='';	
					setTimeout(hideresult,6000);	
		}
                }
                }
                );
}

function hideresult()
{
	$('integrity_result').style.display = 'none';
}


