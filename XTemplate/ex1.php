<?

	/* 
		example 1
		demonstrates basic template functions
		-simple replaces ( {VARIABLE1}, and {DATA.ID} {DATA.NAME} {DATA.AGE} )
		-dynamic blocks

		$Id: ex1.php,v 1.1 2004/08/18 15:27:17 gjayakrishnan Exp $
	*/

	require "xtpl.p";
	
	$xtpl=new XTemplate ("ex1.xtpl");
	
	
	$xtpl->assign("VARIABLE","TEST"); /* simple replace */
	
	$xtpl->parse("main.block1");	/* parse block1 */
	$xtpl->parse("main.block2"); /* uncomment to parse block2 */

	/* you can reference to array keys in the template file the following way:
		{DATA.ID} or {DATA.NAME} 
		say we have an array from a mysql query with the following fields: ID, NAME, AGE
		*/
	$row=array(
							ID=>"38",
							NAME=>"cranx",
             	AGE=>"20"
             );
	
	$xtpl->assign("DATA",$row);

	$xtpl->parse("main.block3"); /* parse block3 */
	
	$xtpl->parse("main");
	$xtpl->out("main");

/*
		$Log: ex1.php,v $
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