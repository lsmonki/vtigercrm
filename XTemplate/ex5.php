<?

	/* 
		example 5
		demonstrates nullstrings

		$Id: ex5.php,v 1.1 2004/08/18 15:27:17 gjayakrishnan Exp $

	*/

	require "xtpl.p";

	$xtpl=new XTemplate ("ex5.xtpl");

	$xtpl->assign(INTRO_TEXT,"by default, if some variables weren't assigned a value, they simply disappear from the parsed html:");	
	$xtpl->parse("main.form");

	$xtpl->assign(INTRO_TEXT,"ok, now let's assign a nullstring:");	
	$xtpl->SetNullString("value not specified!");
	$xtpl->parse("main.form");

	$xtpl->assign(INTRO_TEXT,"custom nullstring for a specific variable and default nullstring mixed:");	
	$xtpl->SetNullString("no value..");
	$xtpl->SetNullString("no email specified!",EMAIL);
	$xtpl->parse("main.form");
	
	$xtpl->assign(INTRO_TEXT,"custom nullstring for every variable:) .. you should get it by now. :P");	
	$xtpl->SetNullString("no email specified",EMAIL);
	$xtpl->SetNullString("no name specified",FULLNAME);
	$xtpl->SetNullString("no income?",INCOME);
	$xtpl->parse("main.form");

	$xtpl->parse("main");
	$xtpl->out("main");

/*
		$Log: ex5.php,v $
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