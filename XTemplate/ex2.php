<?

	/* 
		example 2
		demonstrates multiple level dynamic blocks

		$Id: ex2.php,v 1.1 2004/08/18 15:27:17 gjayakrishnan Exp $

	*/

	require "xtpl.p";

	$xtpl=new XTemplate ("ex2.xtpl");

	/* you can reference to array keys in the template file the following way:
		{DATA.ID} or {DATA.NAME} 
		say we have an array from a mysql query with the following fields: ID, NAME, AGE
		*/
	$rows[1]=array(				/* add some data */
							ID=>"38",
							NAME=>"cranx",
             	AGE=>"20"
             );
	
	$rows[2]=array(				/* add some data */
							ID=>"27",
							NAME=>"ozsvar",
							AGE=>"34"
						 );

	$rows[3]=array(			/* add some data */
							ID=>"56",
							NAME=>"alpi",
							AGE=>"23"
						 );

	for ($i=1;$i<=3;$i++) {
		
		$xtpl->assign("DATA",$rows[$i]);		/* assign array data */
		$xtpl->assign("ROW_NR",$i);
		$xtpl->parse("main.table.row");			/* parse a row */

/* 
	another way to do it would be:

		$xtpl->insert_loop("main.table.row",array(
																								DATA=>$rows[$i],
																								ROW_NR=>$i
																						));

*/
	
	}
	
	$xtpl->parse("main.table");					/* parse the table */
	
	$xtpl->parse("main");
	$xtpl->out("main");

/*
		$Log: ex2.php,v $
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