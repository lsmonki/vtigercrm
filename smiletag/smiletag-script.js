	var windowFocused   = 20000; //if window got focuses, this is the interval for refreshing (20 seconds)
	var windowBlurred   = 60000; //if window lost it focus, decrease the refresh interval to save bandwidth (60 seconds)
	var pingInterval = windowFocused; 
	var isWorking = false;
	
	if((smiletagURL == "http://localhost:90/smiletag/") || (smiletagURL == null)){
		var smiletagURL = "smiletag/";
	}
	
	var url = smiletagURL + "backend.php"; // The url to check the new message status
	
	//clear the message from the text area
	function clearMessage(){
		document.smiletagform.message.value=document.smiletagform.message_box.value;
	    document.smiletagform.message_box.value="";
    }
	
	//reload the iframe
	function reloadMessage(){
		var smiletagFrame = window.frames['iframetag'];
		smiletagFrame.location = smiletagURL + "view.php";
	}
	
	//check for new message
    function checkMessage(){
		if(!isWorking && (http != null)) {
			http.open("GET", url, true);
			http.onreadystatechange = handleHttpResponse;
			isWorking = true;
			http.send(null);
		}
		setTimeout('checkMessage()',pingInterval);
	}
	
	//check for new message, without calling the next settimeout
	//this function called when window got focus, so everytime user display the window
	//they will get the latest message
	function checkMessageForced(){
		if(!isWorking && (http != null)) {
			http.open("GET", url, true);
			http.onreadystatechange = handleHttpResponse;
			isWorking = true;
			http.send(null);
		}
	}
	
	
	function handleHttpResponse() {
	  if (http.readyState == 4) {
		if (http.responseText.indexOf('invalid') == -1) {
		   var responseText = http.responseText; 
	   	   if(responseText == '1'){
				reloadMessage();
		   }     
		   isWorking = false;
		}
	  }
	}
	
	function getHTTPObject() {
	  var xmlhttp;
	  /*@cc_on
	  @if (@_jscript_version >= 5)
		try {
		  xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
		} catch (e) {
		  try {
			xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
		  } catch (E) {
			xmlhttp = false;
		  }
		}
	  @else
	  xmlhttp = false;
	  @end @*/
	  if (!xmlhttp && typeof XMLHttpRequest != 'undefined') {
		try {
		  xmlhttp = new XMLHttpRequest();
		  xmlhttp.overrideMimeType("text/xml"); 
		} catch (e) {
		  xmlhttp = false;
		}
	  }
	  return xmlhttp;
	}
	
	var http = getHTTPObject(); // create the HTTP Object
	
	setTimeout('checkMessage()',pingInterval);
	
	window.onfocus=function(){
    	checkMessageForced();
    	pingInterval = windowFocused;
    }
	
    window.onblur=function(){
    	pingInterval = windowBlurred;
    }

function showSmileyWindow(e){
	if(document.all)e = event;
	
	var smileyBox = document.getElementById('smiley_box');
			
	smileyBox.style.display = 'block';
	var st = Math.max(document.body.scrollTop,document.documentElement.scrollTop);
	var leftPos = e.clientX - 100;
	if(leftPos<0)leftPos = 0;
	smileyBox.style.left = leftPos + 'px';
	smileyBox.style.top = e.clientY - smileyBox.offsetHeight -1 + st + 'px';
}	

function hideSmileyWindow()
{
	document.getElementById('smiley_box').style.display = 'none';
	
}

function insertSmiley(code){
	document.smiletagform.message_box.value += code;
	hideSmileyWindow();
}
 
