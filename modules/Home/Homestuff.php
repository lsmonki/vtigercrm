<?php
/*********************************************************************************
  ** The contents of this file are subject to the vtiger CRM Public License Version 1.0
   * ("License"); You may not use this file except in compliance with the License
   * The Original Code is:  vtiger CRM Open Source
   * The Initial Developer of the Original Code is vtiger.
   * Portions created by vtiger are Copyright (C) vtiger.
   * All Rights Reserved.
  *
 ********************************************************************************/

 require_once('include/home.php');
 require_once('modules/Rss/Rss.php');
 $oHomestuff=new Homestuff();

 if(isset($_REQUEST['stufftype']) && $_REQUEST['stufftype']!="")
 	$oHomestuff->stufftype=$_REQUEST['stufftype']; 
 if(isset($_REQUEST['stufftitle']) && $_REQUEST['stufftitle']!="")
 	if(strlen($_REQUEST['stufftitle'])>20)
		$oHomestuff->stufftitle=substr($_REQUEST['stufftitle'],0,20)."...";
	else
		$oHomestuff->stufftitle=$_REQUEST['stufftitle'];
 if(isset($_REQUEST['selmodule']) && $_REQUEST['selmodule']!="")
 	$oHomestuff->selmodule=$_REQUEST['selmodule'];
 if(isset($_REQUEST['maxentries']) && $_REQUEST['maxentries']!="")
 	$oHomestuff->maxentries=$_REQUEST['maxentries'];
if(isset($_REQUEST['selFiltername']) && $_REQUEST['selFiltername']!="")
 	$oHomestuff->selFiltername=$_REQUEST['selFiltername'];
if(isset($_REQUEST['fldname']) && $_REQUEST['fldname']!="")
	$oHomestuff->fieldvalue=$_REQUEST['fldname'];
	
if(isset($_REQUEST['txtRss']) && $_REQUEST['txtRss']!="")
{
 	$ooRss=new vtigerRSS();
	//$newUrl=str_replace('##amp##','&',$_REQUEST['txtRss']);
	if($ooRss->setRSSUrl($_REQUEST['txtRss']))
	{
		$oHomestuff->txtRss=$_REQUEST['txtRss'];
	}
	else
	{
		return false;
	}
}
if(isset($_REQUEST['seldashbd']) && $_REQUEST['seldashbd']!="")
 	$oHomestuff->seldashbd=$_REQUEST['seldashbd'];
if(isset($_REQUEST['seldashtype']) && $_REQUEST['seldashtype']!="")
 	$oHomestuff->seldashtype=$_REQUEST['seldashtype'];
	
if(isset($_REQUEST['seldeftype']) && $_REQUEST['seldeftype']!="")
 
{
	$seldeftype=$_REQUEST['seldeftype'];
	$defarr=explode(",",$seldeftype);
	$oHomestuff->defaultvalue=$defarr[0];
	$deftitlehash=$defarr[1];
	$oHomestuff->defaulttitle=str_replace("#"," ",$deftitlehash);
}

	$loaddetail=$oHomestuff->addStuff();
 echo $loaddetail;	
?>
