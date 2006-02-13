<?

	/* 
		example 3
		autoreset

		$Id: ex3.php,v 1.1 2004/08/18 15:27:17 gjayakrishnan Exp $

	*/

	require "xtpl.p";

	$xtpl=new XTemplate ("ex3.xtpl");

	/* this is the code from example 2: */

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
	}
	
	$xtpl->parse("main.table");					/* parse the table */

	/* now, if you wanted to parse the table once again with the old rows,
		and put one more $xtpl->parse("main.table") line, it wouldn't do it
		becuase the sub-blocks were resetted (normal operation)
		to parse the same block two or more times without having the sub-blocks resetted,
		you should use clear_autoreset();
		to switch back call set_autoreset();
		*/
	
	$xtpl->clear_autoreset();
	for ($i=1;$i<=3;$i++) {
		$xtpl->assign("DATA",$rows[$i]);		/* assign array data */
		$xtpl->assign("ROW_NR",$i);
		$xtpl->parse("main.table.row");			/* parse a row */
	}
	
	$xtpl->parse("main.table");					/* parse the table */
	$xtpl->parse("main.table");					/* parse it one more time.. wihtout clearing the rows (sub-block reset) */

	$xtpl->parse("main");
	$xtpl->out("main");

/*
		$Log: ex3.php,v $
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