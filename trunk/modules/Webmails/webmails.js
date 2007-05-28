/*********************************************************************************
  ** The contents of this file are subject to the vtiger CRM Public License Version 1.0
   * ("License"); You may not use this file except in compliance with the License
   * The Original Code is:  vtiger CRM Open Source
   * The Initial Developer of the Original Code is vtiger.
   * Portions created by vtiger are Copyright (C) vtiger.
   * All Rights Reserved.
  *
 ********************************************************************************/
function load_webmail(mid) {
        var node = $("row_"+mid);
	if(node.className == "unread_email") {
		var unread  = parseInt($(mailbox+"_unread").innerHTML);
		if(unread != 0)
			$(mailbox+"_unread").innerHTML = (unread-1);

                $("unread_img_"+mid).removeChild($("unread_img_"+mid).firstChild);
                $("unread_img_"+mid).appendChild(Builder.node('a',
                        {href: 'javascript:;', onclick: 'OpenCompose('+mid+',"reply")'},
                        [Builder.node('img',{src: 'modules/Webmails/images/stock_mail-read.png', border: '0', width: '10', height: '11'})]
                ));
	}
        node.className='read_email';

        $("from_addy").innerHTML = "&nbsp;"+webmail[mid]["from"];
        $("to_addy").innerHTML = "&nbsp;"+webmail[mid]["to"];
        $("webmail_subject").innerHTML = "&nbsp;"+webmail[mid]["subject"];
        $("webmail_date").innerHTML = "&nbsp;"+webmail[mid]["date"];

        $("body_area").removeChild($("body_area").firstChild);
        $("body_area").appendChild(Builder.node('iframe',{src: 'index.php?module=Webmails&action=body&mailid='+mid+'&mailbox='+mailbox, width: '100%', height: '210', frameborder: '0'},'You must enable iframes'));

        tmp = document.getElementsByClassName("previewWindow");
        for(var i=0;i<tmp.length;i++) {
                if(tmp[i].style.visibility === "hidden") {
                        tmp[i].style.visibility="visible";
                }
        }

        $("delete_button").removeChild($("delete_button").firstChild);
        $("delete_button").appendChild(Builder.node('input',{type: 'button', name: 'Button', value: 'Delete', className: 'classWebBtn', onclick: 'runEmailCommand(\'delete_msg\','+mid+')'}));

        $("reply_button_all").removeChild($("reply_button_all").firstChild);
        $("reply_button_all").appendChild(Builder.node('input',{type: 'button', name: 'reply', value: ' Reply To All ', className: 'classWebBtn', onclick: 'OpenCompose('+mid+',\'replyall\')'}));

        $("reply_button").removeChild($("reply_button").firstChild);
        $("reply_button").appendChild(Builder.node('input',{type: 'button', name: 'reply', value: ' Reply To Sender ', className: 'classWebBtn', onclick: 'OpenCompose('+mid+',\'reply\')'}));

        $("forward_button").removeChild($("forward_button").firstChild);
        $("forward_button").appendChild(Builder.node('input',{type: 'button', name: 'forward', value: ' Forward ', className: 'classWebBtn', onclick: 'OpenCompose('+mid+',\'forward\')'}));

        $("qualify_button").removeChild($("qualify_button").firstChild);
        $("qualify_button").appendChild(Builder.node('input',{type: 'button', name: 'Qualify2', value: ' Qualify ', className: 'classWebBtn', onclick: 'showRelationships('+mid+')'}));

        $("download_attach_button").removeChild($("download_attach_button").firstChild);
        $("download_attach_button").appendChild(Builder.node('input',{type: 'button', name: 'download', value: ' Download Attachments ', className: 'classWebBtn', onclick: 'displayAttachments('+mid+')'}));

        $("full_view").removeChild($("full_view").firstChild);
        $("full_view").appendChild(Builder.node('a',{href: 'javascript:;', onclick: 'OpenCompose('+mid+',\'full_view\')'},'Full Email View'));

}
function displayAttachments(mid) {
        var url = "index.php?module=Webmails&action=dlAttachments&mailid="+mid+"&mailbox="+mailbox;
        window.open(url,"Download Attachments",'menubar=no,toolbar=no,location=no,status=no,resizable=no,width=450,height=450');
}
function showRelationships(mid) {
	// TODO: present the user with a simple DHTML div to
	// choose what type of relationship they would like to create
	// before creating it.
	alert('Are you sure you wish to Qualify this Mail as Contact?');
        add_to_vtiger(mid);
}
function add_to_vtiger(mid) {
	// TODO: update this function to allow you to set what entity type
	// you would like to associate to
        $("status").style.display="block";
        new Ajax.Request(
                'index.php',
                {queue: {position: 'end', scope: 'command'},
                        method: 'post',
                        postBody: 'module=Webmails&action=Save&mailid='+mid+'&ajax=true',
                        onComplete: function(t) {
                                $("status").style.display="block";
                        }
                }
        );
}
function select_all() {
        var els = document.getElementsByClassName("msg_check");
	var id='';
        for(var i=0;i<els.length;i++) {
                id = els[i].name.substr((els[i].name.indexOf("_")+1),els[i].name.length);
		var tels = $("row_"+id);
		if(tels.className == "deletedRow") {
                        els[i].checked = false;
		} else {
                	if(els[i].checked)
                        	els[i].checked = false;
                	else 
                        	els[i].checked = true;
		}
        }
}
function check_in_all_boxes(mymbox) {
	// TODO: There is possibly still a bug in the mailbox counting code
	// check for NaN
        new Ajax.Request(
                'index.php',
                {queue: {position: 'end', scope: 'command'},
                        method: 'post',
                        postBody: 'module=Webmails&action=WebmailsAjax&command=check_mbox_all&mailbox='+mymbox+'&ajax=true&file=ListView',
                        onComplete: function(t) {
				//alert(t.responseText);
				if(t.responseText != "") {
                                	var data = eval('(' + t.responseText + ')');
                                	for (var i=0;i<data.msgs.length;i++) {
                                        	var mbox = data.msgs[i].msg.box;
						if(mbox != mailbox) {
                                        		var numnew = parseInt(data.msgs[i].msg.newmsgs);

							var read  = parseInt($(mbox+"_read").innerHTML);
							$(mbox+"_read").innerHTML = (read+numnew);
							var unread  = parseInt($(mbox+"_unread").innerHTML);
							$(mbox+"_unread").innerHTML = (unread+numnew);
						}
					}
				}
        			$("status").style.display="none";
			}
		}
	);
}
function check_for_new_mail(mbox) {
	if(degraded_service == 'true') {
		window.location=window.location;
		return;
	}
        $("status").style.display="block";
        new Ajax.Request(
                'index.php',
                {queue: {position: 'end', scope: 'command'},
                        method: 'post',
                        postBody: 'module=Webmails&action=WebmailsAjax&mailbox='+mbox+'&command=check_mbox&ajax=true&file=ListView',
                        onComplete: function(t) {
			//alert(t.responseText);
                            try {
				// TODO: replace this at some point with prototype JSON
				// tools
                                var data = eval('(' + t.responseText + ')');
				var read  = parseInt($(mailbox+"_read").innerHTML);
				$(mailbox+"_read").innerHTML = (read+data.mails.length);
				var unread  = parseInt($(mailbox+"_unread").innerHTML);
				$(mailbox+"_unread").innerHTML = (unread+data.mails.length);
                                for (var i=0;i<data.mails.length;i++) {
                                        var mailid = data.mails[i].mail.mailid;
                                        var date = data.mails[i].mail.date;
                                        var subject=data.mails[i].mail.subject;
                                        var attachments=data.mails[i].mail.attachments;
                                        var from=data.mails[i].mail.from;

                                        webmail[mailid] = new Array();
                                        webmail[mailid]["from"] = from;
                                        webmail[mailid]["to"] = data.mails[i].mail.to;
                                        webmail[mailid]["subject"] = subject;
                                        webmail[mailid]["date"] = date;

                                        // main row
                                        var tr = Builder.node(
                                                'tr',
                                                {id:'row_'+mailid, className: 'unread_email'}
                                        );

                                        // checkbox
                                        var check = Builder.node(
                                                'td',
                                                [ Builder.node(
                                                        'input',
                                                        {type: 'checkbox', name: 'checkbox_'+mailid, className: 'msg_check'}
                                                )]
                                        );

                                        tr.appendChild(check);
                                        // images
                                        // Attachment
                                        imgtd = Builder.node('td');
                                        if(attachments === "1")  {
                                            var attach = Builder.node('a',
                                                {href: 'javascript:;', onclick: 'displayAttachments('+mailid+')'},
                                                [ Builder.node('img',
                                                        {src: 'modules/Webmails/images/stock_attach.png', border: '0', width: '14px', height: '14px'}
                                                )]
                                            );
                                        } else {
                                            var attach = Builder.node('a',
                                                {src: 'modules/Webmails/images/blank.png', border: '0', width: '14px', height: '14px'}
                                            );
                                        }
                                        imgtd.appendChild(attach);
                                        imgtd.innerHTML += "&nbsp;";

                                        var unread = Builder.node('span',
                                                {id: 'unread_img_'+mailid},
                                                [ Builder.node('a',
                                                        {href: 'javascript:;', onclick: 'OpenCompose('+mailid+',\'reply\')'},
                                                        [ Builder.node('img',
                                                                {src: 'modules/Webmails/images/stock_mail-unread.png', border: '0', width: '10', height: '14'}
                                                        )]
                                                )]
                                        );
                                        imgtd.appendChild(unread);
                                        imgtd.innerHTML += "&nbsp;";

                                        var flag = Builder.node('span',
                                                {id: 'set_td_'+mailid},
                                                [ Builder.node('a',
                                                        {href: 'javascript:void(0);', onclick: 'runEmailCommand(\'set_flag\','+mailid+')'},
                                                        [ Builder.node('img',
                                                                {src: 'modules/Webmails/images/plus.gif', border: '0', width: '11px', height: '11px', id: 'set_flag_img_'+mailid}
                                                        )]
                                                )]
                                        );
                                        imgtd.appendChild(flag);
                                        tr.appendChild(imgtd);


                                        // MSG details
                                        tr.appendChild( Builder.node('td',
                                                [ Builder.node('a',
                                                        {href: 'javascript:;', onclick: 'load_webmail(\''+mailid+'\')', id: 'ndeleted_subject_'+mailid},
                                                        ''+subject+''
                                                )]
                                        ));
                                        tr.appendChild( Builder.node('td',
                                                {id: 'ndeleted_date_'+mailid},
                                                ''+date+''
                                        ));
                                        tr.appendChild( Builder.node('td',
                                                {id: 'ndeleted_from_'+mailid},
                                                ''+from+''
                                        ));

                                       var del = Builder.node('td',
                                                {align: 'center', id:'ndeleted_td_'+mailid},
                                                [ Builder.node('span',
                                                        {id: 'del_link_'+mailid},
                                                        [ Builder.node('a',
                                                                {href: 'javascript:;', onclick: 'runEmailCommand(\'delete_msg\','+mailid+')'},
                                                                [ Builder.node('img',
                                                                        {src: 'modules/Webmails/images/gnome-fs-trash-empty.png', border: '0', width: '14', height: '14', alt: 'del'}
                                                                )]
                                                        )]
                                                )]
                                        );
                                        tr.appendChild(del);

					// TODO: this is ugly, replace using prototype child walker tools
                                        tr.style.display='none';
                                        var tels = $("message_table").childNodes[1].childNodes;
                                        for(var j=0;j<tels.length;j++) {
					    try {
                                                if(tels[j].id.match(/row_/)) {
                                                	$("message_table").childNodes[1].insertBefore(tr,tels[j]);
                                                        break;
                                        	}
					    }catch(f){}
                                        }
                                        new Effect.Appear("row_"+mailid);
                                }
                            }catch(e) {}
			    check_in_all_boxes(mailbox);
        		    //$("status").style.display="none";
                        }
                }
        );
}
function periodic_event() {
	// NOTE: any functions you put in here may race.  This could probably
	// be avoided by executing functions in a 0'ed timeout, or a prototype
	// enumerator
        check_for_new_mail(mailbox);
        window.setTimeout("periodic_event()",box_refresh);
}
function show_hidden() {
	// prototype uses enumerable lists to queue events for execution.
	// because of this, this function executes and returns imediately and
	// the status spinner is never seen.  The status spinner below is a hack
	// and doesn't even attempt to pretend like it knows the event is finished.
	// this cannot be fixed with the scriptaculous beforeStart and afterFinish
	// event hooks for some reason, maybe because the event duration is too quick?
	window.setTimeout(function() {
        	$("status").style.display="block";
		window.setTimeout(function() {
       			$("status").style.display="none";
		},2000);
	},0);
        var els = document.getElementsByClassName("deletedRow");
        for(var i=0;i<els.length;i++) {
                if(els[i].style.display == "none")
                        new Effect.Appear(els[i],{queue: {position: 'end', scope: 'show'}, duration: 0.2});
                else
                        new Effect.Fade(els[i],{queue: {position: 'end', scope: 'show'}, duration: 0.2});
        }
}
function mass_delete() {
	var ok = confirm("Are you sure you want to delete these messages?");
	if(ok) {
		// TODO: CHANGE THIS ASAP.  This spikes the client proc @ 100% and
		// depending on the mbox size may seem completely unresponsive for
		// extended periods.  Could be changed with getElementsByClassName()
		// to shorten the loop.  The majority of the slowdown probably comes from
		// executing an AJAX delete_msg for each mailid :).
        	$("status").style.display="block";
        	var els = document.getElementsByTagName("INPUT");
        	var cnt = (els.length-1);
		var nids='';
		var j=0;
        	for(var i=cnt;i>0;i--) {
                	if(els[i].type === "checkbox" && els[i].name.indexOf("_")) {
                        	if(els[i].checked) {
                                	var nid = els[i].name.substr((els[i].name.indexOf("_")+1),els[i].name.length);
					if(typeof nid == 'undefined' || nid == "checkbox")
						nids += '';
					else
						nids += nid+":";
				}
			}
			j++;
		}
		runEmailCommand("delete_multi_msg",nids);
	}
}
function move_messages() {
        $("status").style.display="block";
        var els = document.getElementsByTagName("INPUT");
        var cnt = (els.length-1);
        for(var i=cnt;i>0;i--) {
                if(els[i].type === "checkbox" && els[i].name.indexOf("_")) {
                        if(els[i].checked) {
                                var nid = els[i].name.substr((els[i].name.indexOf("_")+1),els[i].name.length);
                                var mvmbox = $("mailbox_select").value;
                                new Ajax.Request(
                                        'index.php',
                                        {queue: {position: 'end', scope: 'command'},
                                                method: 'post',
                                                postBody: 'module=Webmails&action=ListView&mailbox=INBOX&command=move_msg&ajax=true&mailid='+nid+'&mvbox='+mvmbox,
                                                onComplete: function(t) {
                                                        //alert(t.responseText);
                                                }
                                        }
                                );
                        }
                }
        }
        runEmailCommand('expunge','');
        $("status").style.display="none";
}
function search_emails() {
	// TODO: find a way to search in degraded functionality mode.
        var search_query = $("search_input").value;
        var search_type = $("search_type").value;
        window.location = "index.php?module=Webmails&action=index&search=true&search_type="+search_type+"&search_input="+search_query;
}
function runEmailCommand(com,id) {
        $("status").style.display="block";
        command=com;
        id=id;
        new Ajax.Request(
                'index.php',
                {queue: {position: 'end', scope: 'command'},
                        method: 'post',
                        postBody: 'module=Webmails&action=WebmailsAjax&command='+command+'&mailid='+id+'&mailbox='+mailbox,
                        onComplete: function(t) {
                                resp = t.responseText;
                                if(resp.match(/ajax failed/)) {return;}
                                switch(command) {
                                    case 'expunge':
                                        // NOTE: we either have to reload the page or count up from the messages that
                                        // are deleted and moved or we introduce a bug from invalid mail ids
                                        window.location = window.location;
                                    break;
                                    case 'delete_multi_msg':
					var ids=resp;
					var rows = ids.split(":");
					for(i=0;i<rows.length;i++)  {
						var id = rows[i];
                                        	var row = $("row_"+id);
						if(row.className == "unread_email") {
							var unread  = parseInt($(mailbox+"_unread").innerHTML);
							$(mailbox+"_unread").innerHTML = (unread-1);
						}
                                        	row.className = "deletedRow";

                                        	Try.these (
							function() {
                                                		$("ndeleted_subject_"+id).innerHTML = "<s>"+$("ndeleted_subject_"+id).innerHTML+"</s>";
                                                		$("ndeleted_date_"+id).innerHTML = "<s>"+$("ndeleted_date_"+id).innerHTML+"</s>";
                                                		$("ndeleted_from_"+id).innerHTML = "<s>"+$("ndeleted_from_"+id).innerHTML+"</s>";
							},
							function() {
                                                		$("deleted_subject_"+id).innerHTML = "<s>"+$("deleted_subject_"+id).innerHTML+"</s>";
                                                		$("deleted_date_"+id).innerHTML = "<s>"+$("deleted_date_"+id).innerHTML+"</s>";
                                                		$("deleted_from_"+id).innerHTML = "<s>"+$("deleted_from_"+id).innerHTML+"</s>";
							}
                                        	);

					try {
                                        	$("del_link_"+id).innerHTML = '<a href="javascript:void(0);" onclick="runEmailCommand(\'undelete_msg\','+id+');"><img src="modules/Webmails/images/gnome-fs-trash-full.png" border="0" width="14" height="14" alt="del"></a>';

                                        	new Effect.Fade(row,{queue: {position: 'end', scope: 'effect'},duration: '0.5'});
                                        	tmp = document.getElementsByClassName("previewWindow");
                                                tmp[0].style.visibility="hidden";
					}catch(g){}

                                	$("status").style.display="none";
					}
                                    break;
                                    case 'delete_msg':
					id=resp;
                                        var row = $("row_"+id);
					if(row.className == "unread_email") {
						var unread  = parseInt($(mailbox+"_unread").innerHTML);
						$(mailbox+"_unread").innerHTML = (unread-1);
					}
                                        row.className = "deletedRow";

                                        Try.these (
						function() {
                                                	$("ndeleted_subject_"+id).innerHTML = "<s>"+$("ndeleted_subject_"+id).innerHTML+"</s>";
                                                	$("ndeleted_date_"+id).innerHTML = "<s>"+$("ndeleted_date_"+id).innerHTML+"</s>";
                                                	$("ndeleted_from_"+id).innerHTML = "<s>"+$("ndeleted_from_"+id).innerHTML+"</s>";
						},
						function() {
                                                	$("deleted_subject_"+id).innerHTML = "<s>"+$("deleted_subject_"+id).innerHTML+"</s>";
                                                	$("deleted_date_"+id).innerHTML = "<s>"+$("deleted_date_"+id).innerHTML+"</s>";
                                                	$("deleted_from_"+id).innerHTML = "<s>"+$("deleted_from_"+id).innerHTML+"</s>";
						}
                                        );

                                        $("del_link_"+id).innerHTML = '<a href="javascript:void(0);" onclick="runEmailCommand(\'undelete_msg\','+id+');"><img src="modules/Webmails/images/gnome-fs-trash-full.png" border="0" width="14" height="14" alt="del"></a>';

                                        new Effect.Fade(row,{queue: {position: 'end', scope: 'effect'},duration: '1.0'});
                                        tmp = document.getElementsByClassName("previewWindow");
                                        for(var i=0;i<tmp.length;i++) {
                                                if(tmp[i].style.visibility === "visible") {
                                                        tmp[i].style.visibility="hidden";
                                                }
                                        }
                                    break;
                                    case 'undelete_msg':
					id=resp;
                                        var node = $("row_"+id);
                                        node.className='';
                                        node.style.display = '';
                                        var newhtml = remove(remove(node.innerHTML,'<s>'),'</s>');
                                        node.innerHTML=newhtml;
                                        $("del_link_"+id).innerHTML = '<a href="javascript:void(0);" onclick="runEmailCommand(\'delete_msg\','+id+');"><img src="modules/Webmails/images/gnome-fs-trash-empty.png" border="0" width="14" height="14" alt="del"></a>';
                                	$("status").style.display="none";
                                    break;
                                    case 'clear_flag':
                                        var nm = "clear_td_"+id;
                                        var el = $(nm);
                                        var tmp = el.innerHTML;
                                        el.innerHTML ='<a href="javascript:void(0);" onclick="runEmailCommand(\'set_flag\','+id+');"><img src="modules/Webmails/images/plus.gif" border="0" width="11" height="11" id="set_flag_img_'+id+'"></a>';
                                        el.id = "set_td_"+id;
                                    break;
                                    case 'set_flag':
                                        var nm = "set_td_"+id;
                                        var el = $(nm);
                                        var tmp = el.innerHTML;
                                        el.innerHTML ='<a href="javascript:void(0);" onclick="runEmailCommand(\'clear_flag\','+id+');"><img src="modules/Webmails/images/stock_mail-priority-high.png" border="0" width="11" height="11" id="clear_flag_img'+id+'"></a>';
                                        el.id = "clear_td_"+id;
                                    break;

                                }
                                $("status").style.display="none";
                        }
                }
        );
}
function remove(s, t) {
  /*
  **  Remove all occurrences of a token in a string
  **    s  string to be processed
  **    t  token to be removed
  **  returns new string
  */
  i = s.indexOf(t);
  r = "";
  if (i == -1) return s;
  r += s.substring(0,i) + remove(s.substring(i + t.length), t);
  return r;
}
function changeMbox(box) {
        location.href = "index.php?module=Webmails&action=index&mailbox="+box;
}
// TODO: these two functions should be tied into a mailbox management panel of some kind.
// could be a DHTML div with AJAX calls to execute the commands on the mailbox.  
function show_addfolder() {
        var fldr = $("folderOpts");
        if(fldr.style.display == 'none')
                $("folderOpts").style.display="";
        else
                $("folderOpts").style.display="none";
}
function show_remfolder(mb) {
        var fldr = $("remove_"+mb);
        if(fldr.style.display == 'none')
                fldr.style.display="";
        else
                fldr.style.display="none";
}
