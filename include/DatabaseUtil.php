<?php
/*********************************************************************************
 * The contents of this file are subject to the SugarCRM Public License Version 1.1.2
 * ("License"); You may not use this file except in compliance with the
 * License. You may obtain a copy of the License at http://www.sugarcrm.com/SPL
 * Software distributed under the License is distributed on an  "AS IS"  basis,
 * WITHOUT WARRANTY OF ANY KIND, either express or implied. See the License for
 * the specific language governing rights and limitations under the License.
 * The Original Code is:  SugarCRM Open Source
 * The Initial Developer of the Original Code is SugarCRM, Inc.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.;
 * All Rights Reserved.
 * Contributor(s): ______________________________________.
 ********************************************************************************/

//Make a count query
function mkCountQuery($query)
{
    //Strip of the current SELECT fields and replace them by "select count(*) as count"
    $query = "SELECT count(*) AS count ".substr($query, strpos($query,'FROM'),strlen($query));

    //Strip of any "GROUP BY" clause
    if( strpos($query,'GROUP') > 0)
	$query = substr($query, 0, strpos($query,'GROUP'));

    //Strip of any "ORDER BY" clause
    if( strpos($query,'ORDER') > 0)
	$query = substr($query, 0, strpos($query,'ORDER'));

    //That's it
    return( $query);
}

?>
