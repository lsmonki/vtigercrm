{*<!--
/*********************************************************************************
  ** The contents of this file are subject to the vtiger CRM Public License Version 1.0
   * ("License"); You may not use this file except in compliance with the License
   * The Original Code is:  vtiger CRM Open Source
   * The Initial Developer of the Original Code is vtiger.
   * Portions created by vtiger are Copyright (C) vtiger.
   * All Rights Reserved.
  *
 ********************************************************************************/
-->*}
<style type="text/css">
a.x {ldelim}
		color:black;
		text-align:center;
		text-decoration:none;
		padding:5px;
		font-weight:bold;
{rdelim}
	
a.x:hover {ldelim}
		color:#333333;
		text-decoration:underline;
		font-weight:bold;
{rdelim}

ul {ldelim}color:black;{rdelim}	 
	
.drag_Element{ldelim}
	position:relative;
	left:0px;
	top:0px;
	padding-left:5px;
	padding-right:5px;
	border:0px dashed #CCCCCC;
	visibility:hidden;
{rdelim}

#Drag_content{ldelim}
	position:absolute;
	left:0px;
	top:0px;
	padding-left:5px;
	padding-right:5px;
	background-color:#000066;
	color:#FFFFFF;
	border:1px solid #CCCCCC;
	font-weight:bold;
	display:none;
{rdelim}
</style>
<script>
 if(!e)
  window.captureEvents(Event.MOUSEMOVE);

//  window.onmousemove= displayCoords;
//  window.onclick = fnRevert;
  
   function displayCoords(event) 
	 {ldelim}
				var move_Element = document.getElementById('Drag_content').style;
				if(!event){ldelim}
						move_Element.left = e.pageX +'px' ;
						move_Element.top = e.pageY+10 + 'px';	
				{rdelim}
				else{ldelim}
						move_Element.left = event.clientX +'px' ;
					    move_Element.top = event.clientY+10 + 'px';	
				{rdelim}
	{rdelim}
  
	  function fnRevert(e)
	  {ldelim}
		  	if(e.button == 2){ldelim}
				document.getElementById('Drag_content').style.display = 'none';
				hideAll = false;
				parentId = "Head";
	    		parentName = "DEPARTMENTS";
			    childId ="NULL";
	    		childName = "NULL";
			{rdelim}
	{rdelim}

</script>
<table width="100%" border="0" cellpadding="0" cellspacing="0" onMouseMove="displayCoords(event)">
		<tr>
				{include file='SettingsMenu.tpl'}
				<td width="75%" valign="top">
					<table width="100%" border="0" cellpadding="0" cellspacing="0" height="100%">
							<tr>
									<td class="showPanelBg" valign="top" width="100%" style="padding-left:20px; "><br />
															<span class="lvtHeaderText"><b>
															<a href="index.php?module=Settings&action=index&parenttab=Settings">{$MOD.LBL_SETTINGS} </a>
															 > {$MOD.LBL_USER_MANAGEMENT} > {$CMOD.LBL_CREATE_NEW_ROLE}</b></span>
															<hr noshade="noshade" size="1"/>
									  </td>
							 </tr>
							 <tr>
									<td  valign="top" class="leadTable">
										<table width="100%" cellpadding="0" cellspacing="0">
											 <tr>
													<td align="left" style="padding:10px;border-bottom:1px dashed #CCCCCC;">
																				<img src="{$IMAGE_PATH}roles.gif" align="absmiddle">
																				<span class="genHeaderGray">Role List View</span>
																		</td>
																</tr>
														</table>
		<div id='RoleTreeFull'>
			{include file='RoleTree.tpl'}
		</div>

		{*
														<table width="100%" border="0" cellspacing="0" cellpadding="0" class="small">
													   <tr>
														    <td style="padding:10px;" valign="top">{$ROLETREE}</td>
													  </tr>
													</table> *}
											  </td>
											</tr>
										</table>
								</td>
						</tr>
		</table>
	</td>
<td width="1%" style="border-right:1px dotted #CCCCCC;">&nbsp;</td>
</tr>
</table>
</td>
</tr>
</table>
</td>
</tr>
</table>
	{include file='SettingsSubMenu.tpl'}
	
	<div id="Drag_content">&nbsp;</div>

<script language="javascript" type="text/javascript">
	var hideAll = false;
	var parentId = "";
	var parentName = "";
	var childId ="NULL";
	var childName = "NULL";

		
	
	 function get_parent_ID(obj,currObj)
	 {ldelim}
			var leftSide = findPosX(obj);
    			var topSide = findPosY(obj);
			var move_Element = document.getElementById('Drag_content');
		 	childName  = document.getElementById(currObj).innerHTML;
			childId = currObj;
			move_Element.innerHTML = childName;
			move_Element.style.left = leftSide + 15 + 'px';
			move_Element.style.top = topSide + 15+ 'px';
			move_Element.style.display = 'block';
			hideAll = true;	
	{rdelim}
	
	function put_child_ID(currObj)
	{ldelim}
			var move_Element = $('Drag_content');
	 		parentName  = $(currObj).innerHTML;
			parentId = currObj;
			move_Element.style.display = 'none';
			hideAll = false;	
			if(childId == "NULL")
			{ldelim}
//				alert("Please Select the Node");
				parentId = parentId.replace(/user_/gi,'');
				window.location.href="index.php?module=Users&action=RoleDetailView&parenttab=Settings&roleid="+parentId;
			{rdelim}
			else
			{ldelim}
				childId = childId.replace(/user_/gi,'');
				parentId = parentId.replace(/user_/gi,'');
				new Ajax.Request(
  					'index.php',
				        {ldelim}queue: {ldelim}position: 'end', scope: 'command'{rdelim},
					        method: 'post',
					        postBody: 'module=Users&action=UsersAjax&file=RoleDragDrop&ajax=true&parentId='+parentId+'&childId='+childId,
						onComplete: function(response) {ldelim}
							if(response.responseText != 'You cannot move a Parent Node under a Child Node')
							{ldelim}
						                $('RoleTreeFull').innerHTML=response.responseText;
						                hideAll = false;
							        parentId = "";
						                parentName = "";
						                childId ="NULL";
								childName = "NULL";
						        {rdelim}
						        else
						                alert(response.responseText);
			                        {rdelim}
				        {rdelim}
				);
			{rdelim}
	{rdelim}

	function fnVisible(Obj)
	{ldelim}
			if(!hideAll)
				document.getElementById(Obj).style.visibility = 'visible';
	{rdelim}

	function fnInVisible(Obj)
	{ldelim}
		document.getElementById(Obj).style.visibility = 'hidden';
	{rdelim}

	


function showhide(argg,imgId)
{ldelim}
	var harray=argg.split(",");
	var harrlen = harray.length;
	var i;
	for(i=0; i<harrlen; i++)
	{ldelim}
			var x=document.getElementById(harray[i]).style;
        	if (x.display=="none")
        	{ldelim}
            		x.display="block";
					//document.all[imgId].src = "{$IMAGE_PATH}minus.gif";   By Ela	
        	{rdelim}
        	else
			{ldelim}
            			x.display="none";
						//document.all[imgId].src = "{$IMAGE_PATH}plus.gif"; By Ela
            {rdelim}
	{rdelim}
{rdelim}



</script>
