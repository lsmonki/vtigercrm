function searchshowhide(argg,argg2)
{
    var x=document.getElementById(argg).style
    var y=document.getElementById(argg2).style
    if (x.display=="none" && y.display=="none")
    {
        x.display="block"
   
    }
    else {
	    y.display="none"
            x.display="none"
          }
}

 function moveMe(arg1) {
    var posx = 0;
    var posy = 0;
    var e=document.getElementById(arg1);
   
    if (!e) var e = window.event;
   
    if (e.pageX || e.pageY)
    {
        posx = e.pageX;
        posy = e.pageY;
    }
    else if (e.clientX || e.clientY)
    {
        posx = e.clientX + document.body.scrollLeft;
        posy = e.clientY + document.body.scrollTop;
    }
 }
