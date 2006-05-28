function load_webmail(mid) {
        var node = $("row_"+mid);
	if(node.className == "unread_email") {
		var unread  = parseInt($(mailbox+"_unread").innerHTML);
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
        var url = "index.php?module=Webmails&action=dlAttachments&mailid="+mid;
        window.open(url,"Download Attachments",'menubar=no,toolbar=no,location=no,status=no,resizable=no,width=450,height=450');
}
function showRelationships(mid) {
        // just add to vtiger for now
        add_to_vtiger(mid);
}
function add_to_vtiger(mid) {
        $("status").style.display="block";
        new Ajax.Request(
                'index.php',
                {queue: {position: 'end', scope: 'command'},
                        method: 'post',
                        postBody: 'module=Webmails&action=Save&mailid='+mid+'&ajax=true',
                        onComplete: function(t) {
                                $("status").style.display="none";
                        }
                }
        );
}
function select_all() {
        var els = document.getElementsByClassName("msg_check");
        for(var i=0;i<els.length;i++) {
                if(els[i].checked)
                        els[i].checked = false;
                else
                        els[i].checked = true;
        }
}
function check_in_all_boxes(mymbox) {
	if(degraded_service == 'true') {
		return;
	}
        $("status").style.display="block";
        new Ajax.Request(
                'index.php',
                {queue: {position: 'end', scope: 'command'},
                        method: 'post',
                        postBody: 'module=Webmails&action=WebmailsAjax&command=check_mbox_all&ajax=true',
                        onComplete: function(t) {
				//alert(t.responseText);
				try {
				if(t.responseText != "") {
                                	var data = eval('(' + t.responseText + ')');
                                	for (var i=0;i<data.msgs.length;i++) {
						if(mbox != mymbox) {
                                        		var mbox = data.msgs[i].msg.box;
                                        		var numnew = parseInt(data.msgs[i].msg.newmsgs);
							
							var read  = parseInt($(mbox+"_read").innerHTML);
							$(mbox+"_read").innerHTML = (read+numnew);
							var unread  = parseInt($(mbox+"_unread").innerHTML);
							$(mbox+"_unread").innerHTML = (unread+numnew);
						}
					}
				}
				}catch(e){alert(e);}
			}
		}
	);
        $("status").style.display="none";
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
                        postBody: 'module=Webmails&action=WebmailsAjax&mailbox='+mbox+'&command=check_mbox&ajax=true',
                        onComplete: function(t) {
			//alert(t.responseText);
                            try {
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

                                        tr.style.display='none';
                                        var tels = $("message_table").childNodes[1].childNodes;
                                        for(var j=0;j<tels.length;j++) {
                                                try {
                                                    if(tels[j].id.match(/row_/)) {
                                                        $("message_table").childNodes[1].insertBefore(tr,tels[j]);
                                                        break;
                                                    }
                                                }catch(e){}
                                        }
                                        new Effect.Appear("row_"+mailid);
                                }
                            }catch(e) {}
			    window.setTimeout("check_in_all_boxes('"+mailbox+"')",0);
                            $("status").style.display="none";
                        }
                }
        );
}
function periodic_event() {
        check_for_new_mail(mailbox);
        window.setTimeout("periodic_event()",box_refresh);
}
function show_hidden() {
	if(degraded_service == 'true') {
		window.location=window.location+"&show_hidden=true";
		return;
	}

        $("status").style.display="block";
        var els = document.getElementsByClassName("deletedRow");
        for(var i=0;i<els.length;i++) {
                if(els[i].style.display == "none")
                        new Effect.Appear(els[i],{queue: {position: 'end', scope: 'command'}, duration: 0.2});
                else
                        new Effect.Fade(els[i],{queue: {position: 'end', scope: 'command'}, duration: 0.2});
        }
        $("status").style.display="none";
}
function mass_delete() {
	var ok = confirm("Are you sure you want to delete these messages?");
	if(ok) {
        	$("status").style.display="block";
        	var els = document.getElementsByTagName("INPUT");
        	var cnt = (els.length-1);
        	for(var i=cnt;i>0;i--) {
                	if(els[i].type === "checkbox" && els[i].name.indexOf("_")) {
                        	if(els[i].checked) {
                                	var nid = els[i].name.substr((els[i].name.indexOf("_")+1),els[i].name.length);
					runEmailCommand("delete_msg",nid);
				}
			}
		}
	}
       	$("status").style.display="none";
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
                        postBody: 'module=Webmails&action=body&command='+command+'&mailid='+id+'&mailbox='+mailbox,
                        onComplete: function(t) {
                                resp = t.responseText;
                                if(resp.match(/ajax failed/)) {return;}
                                switch(command) {
                                    case 'expunge':
                                        // NOTE: we either have to reload the page or count up from the messages that
                                        // are deleted and moved or we introduce a bug from invalid mail ids
                                        window.location = window.location;
                                    break;
                                    case 'delete_msg':
                                        var row = $("row_"+id);
					if(row.className == "unread_email") {
						var unread  = parseInt($(mailbox+"_unread").innerHTML);
						$(mailbox+"_unread").innerHTML = (unread-1);
					}
                                        row.className = "deletedRow";
                                        try {
                                                $("ndeleted_subject_"+id).innerHTML = "<s>"+$("ndeleted_subject_"+id).innerHTML+"</s>";
                                                $("ndeleted_date_"+id).innerHTML = "<s>"+$("ndeleted_date_"+id).innerHTML+"</s>";
                                                $("ndeleted_from_"+id).innerHTML = "<s>"+$("ndeleted_from_"+id).innerHTML+"</s>";
                                        }catch(e){
                                                $("deleted_subject_"+id).innerHTML = "<s>"+$("deleted_subject_"+id).innerHTML+"</s>";
                                                $("deleted_date_"+id).innerHTML = "<s>"+$("deleted_date_"+id).innerHTML+"</s>";
                                                $("deleted_from_"+id).innerHTML = "<s>"+$("deleted_from_"+id).innerHTML+"</s>";
                                        }

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
                                        var node = $("row_"+id);
                                        node.className='';
                                        node.style.display = '';
                                        var newhtml = remove(remove(node.innerHTML,'<s>'),'</s>');
                                        node.innerHTML=newhtml;
                                        $("del_link_"+id).innerHTML = '<a href="javascript:void(0);" onclick="runEmailCommand(\'delete_msg\','+id+');"><img src="modules/Webmails/images/gnome-fs-trash-empty.png" border="0" width="14" height="14" alt="del"></a>';
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
