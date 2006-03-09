/*
  EffectsPack
  EffectsPack requires Prototype(http://prototype.conio.net/) and
  Effects v2 (http://mir.aculo.us)
  Feel free to use under any conditions
  
  These effects are targeted to specific cases and thus will not work 
  under every scenerio.
*/

 EffectPack = {}


/*---------------------------------+
 |  Nested Unordered List Effects  |
 +---------------------------------+
*/

EffectPack.BlindToggle = function(element) {
  element = element.parentNode.getElementsByTagName('ul')[0];
  if(element.style.display == 'none') 
    new Effect2.BlindDown(element);
  else
    new Effect2.BlindUp(element);
}


EffectPack.MagicToggle = function(element) {
  element = element.parentNode.getElementsByTagName('ul')[0];
  if(element.style.display == 'none') 
    new Effect.Appear(element);
  else
    new Effect.Fade(element);
}
  

/*---------------------------------+
 |        ActiveTab                |
 +---------------------------------+
*/

EffectPack.TabToggle = function(element) {

  //First We control the look and feel of the active tab
  tabs = element.parentNode.parentNode.getElementsByTagName('li');
   	document.getElementById("prof").className='dvtUnSelectedCell';

  	document.getElementById("more").className='dvtUnSelectedCell';

 	document.getElementById("addr").className='dvtUnSelectedCell';
  	
	element.parentNode.className = "dvtSelectedCell"
  
  //Hide all content containers
  contents = document.getElementsByClassName('tabset_content');
  for(var i = 0; i < contents.length; i++) {
    contents[i].style.display = 'none';
  }

  //Extract content container id from href
  tabname = element.getAttribute('href').replace('#', '');
  ele = $(tabname);
  
  //Magic Happens
  new Effect.Appear(ele);
}
  
  
