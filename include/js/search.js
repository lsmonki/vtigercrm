function showhide(argg)
{
    var x=document.getElementById(argg).style
    if (x.display=="none")
    {
        x.display="block"
   
    }
    else {
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
