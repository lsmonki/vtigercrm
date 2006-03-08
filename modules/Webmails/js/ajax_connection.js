/* (c) 2005 Matthew Brichacek <mmbrich@fosslabs.com
 * All rights reserved
 */
function AjaxConnection() {
        this.setOptions=setOptions;
        this.setUid=setUid;
        this.connect=connect;
        this.setType=setType;
        this.clearOptions=clearOptions;
        this.uri="modules/Webmails/";
	this.type="";

        function setOptions(opt)
        {
            for(i=0;i<opt.length;i++) {
                this.options += "&"+opt[i];
            }
        }
        function clearOptions()
        {
                this.options = null;
                this.options = "uid="+this.uid;
        }
        function setUid(id)
        {
            this.uid=id;
            this.options = "uid="+this.uid;
        }
        function setType(t)
        {
                this.type=t;
        }
        function connect(return_func)
        {
            with(this)
            {
                x=init_object();
                var u = uri+type;
                x.open("POST", u,true);
                x.onreadystatechange = function() {
                        if (x.readyState != 4)  {
                                return;
                        }
                        if(x.responseText.indexOf("^") != 0) {
                                var tmp = x.responseText.split("^");
                                eval(return_func + '(tmp)');
                        } else {
                                eval(return_func + '(x.responseText)');
                        }
                        delete x;
                        delete tmp;
                }

                x.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                x.send(options);
            }
        }
}
function init_object() {
        var x;
        try {
                x=new ActiveXObject("Msxml2.XMLHTTP");
        } catch (e) {
                try {
                        x=new ActiveXObject("Microsoft.XMLHTTP");
                } catch (oc) {
                        x=null;
                }
        }
        if(!x && typeof XMLHttpRequest != "undefined")
                x = new XMLHttpRequest();
        if (x)
                return x;
}
