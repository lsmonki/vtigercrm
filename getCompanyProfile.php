<?php
/*********************************************************************************

** The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 ********************************************************************************/

require_once('class_http/class_http.php');
/** Function to get data from the external site
  * @param $url -- url:: Type string
  * @param $variable -- variable:: Type string
  * @returns $desc -- desc:: Type string array
  *
 */
function getComdata($url,$variable)
{
	$h = new http();
	$desc = array();
	$h->dir = "class_http_dir/";
	if (!$h->fetch($url, 2)) {
	  echo "<h2>There is a problem with the http request!</h2>";
	  echo $h->log;
	  exit();
	} 
	$msft_stats = http::table_into_array($h->body, 'Find Symbol', 0, null);
	$desc=$msft_stats[0];
	$data=getQuoteData($variable);
	foreach($data as $key=>$value)
		array_push($desc,$value);
	return $desc;
}

/** Function to get company quotes from external site
  * @param $var -- var:: Type string(company trickersymbol)
  * @returns $quote_data -- quote_data:: Type string array
  *
 */
function getQuoteData($var)
{
	//$url = "http://moneycentral.msn.com/detail/stock_quote?Symbol=".$var;
	$url = "http://finance.yahoo.com/q?s=".$var;
	$h = new http();
        $h->dir = "class_http_dir/";
        if (!$h->fetch($url, 2)) {
          echo "<h2>There is a problem with the http request!</h2>";
          echo $h->log;
          exit();
        }
	$res_arr=array();
	$quote_data = http::table_into_array($h->body, 'Delayed quote data', 0, null);
	if($quote_data[0][0] == '')
        {
                array_shift($quote_data);
                array_shift($quote_data);
        }
	for($i=0;$i<16;$i++)
	{
		if($quote_data !='')
			$res_arr[]=$quote_data[$i];
	}
	//array_shift($res_arr);
	//array_shift($res_arr);	
	//echo '<pre>';print_r($quote_data);echo '</pre>';
	//die;
	return $res_arr;
}
?>
