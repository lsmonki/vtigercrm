<?

	/* 
		example 6
		demonstrates nullblocks

		$Id: ex6.php,v 1.1 2004/08/18 15:27:17 gjayakrishnan Exp $

	*/

	require "xtpl.p";

	$xtpl=new XTemplate ("ex6.xtpl");

	$xtpl->assign(INTRO_TEXT,"what happens if we don't parse the subblocks?");
	$xtpl->parse("main.block");
	
	$xtpl->assign(INTRO_TEXT,"what happens if we parse them? :)");
	$xtpl->parse("main.block.subblock1");
	$xtpl->parse("main.block.subblock2");
	$xtpl->parse("main.block");

	$xtpl->assign(INTRO_TEXT,"ok.. SetNullBlock(\"block not parsed!\") coming");
	$xtpl->SetNullBlock("block not parsed!");
	$xtpl->parse("main.block");

	$xtpl->assign(INTRO_TEXT,"ok.. custom nullblocks.. SetNullBlock(\"subblock1 not parsed!\",\"main.block.subblock1\")");
	$xtpl->SetNullBlock("block not parsed!");
	$xtpl->SetNullBlock("subblock1 not parsed!","main.block.subblock1");
	$xtpl->parse("main.block");

	$xtpl->parse("main");
	$xtpl->out("main");

/*
		$Log: ex6.php,v $
		Revision 1.1  2004/08/18 15:27:17  gjayakrishnan
		XTemplate files added
		
		Revision 1.1  2004/05/27 05:30:47  sugarjacob
		Moving project to SourceForge.
		
		Revision 1.1  2004/05/19 01:48:20  sugarcrm
		Adding files with binary option as appropriate.
		
		Revision 1.2  2001/03/26 23:25:02  cranx
		added keyword expansion to be more clear
		
*/

?>