<?

	/* 
		example 7
		demonstrates file includes
		
		$Id: ex7.php,v 1.1 2004/08/18 15:27:17 gjayakrishnan Exp $

	*/

	require "xtpl.p";

	$xtpl=new XTemplate ("ex7.xtpl");

	$xtpl->assign(FILENAME,"ex7-inc.xtpl");
	$xtpl->rparse("main.inc");

	$xtpl->parse("main");
	$xtpl->out("main");

/*
		$Log: ex7.php,v $
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