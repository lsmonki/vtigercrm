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

require_once 'Excel/reader.php';

global $data;
// ExcelFile($filename, $encoding);
$data = new Spreadsheet_Excel_Reader();


// Set output Encoding.
$data->setOutputEncoding('CP1251');

/***
*  Some function for formatting output.
* $data->setDefaultFormat('%.2f');
* setDefaultFormat - set format for columns with unknown formatting
*
* $data->setColumnFormat(4, '%.3f');
* setColumnFormat - set format for column (apply only to number fields)
*
**/

$filename = $_FILES["userfile"]["name"];

//echo $filename;


$data->read($filename);


/*

 $data->sheets[0]['numRows'] - count rows
 $data->sheets[0]['numCols'] - count columns
 $data->sheets[0]['cells'][$i][$j] - data from $i-row $j-column

 $data->sheets[0]['cellsInfo'][$i][$j] - extended info about cell
    
    $data->sheets[0]['cellsInfo'][$i][$j]['type'] = "date" | "number" | "unknown"
        if 'type' == "unknown" - use 'raw' value, because  cell contain value with format '0.00';
    $data->sheets[0]['cellsInfo'][$i][$j]['raw'] = value if cell without format 
    $data->sheets[0]['cellsInfo'][$i][$j]['colspan'] 
    $data->sheets[0]['cellsInfo'][$i][$j]['rowspan'] 

*/

//echo $data->sheets[0]['numRows'];
//echo $data->sheets[0]['numCols'];
echo "\n";
echo "\n";
echo "\n";
echo "\n";
echo "\n";
echo "\n";
for ($i = 1; $i<2; $i++) 
{
		for ($j = 1; $j <= $data->sheets[0]['numCols']; $j++) 
		{
			$value = $data->sheets[0]['cells'][$i][$j];
			//echo $value;
			//echo "\"".$data->sheets[0]['cells'][$i][$j]."\",";
			populateDB($value);
		}
		//echo "\n";
                header("Location:index.php");
                exit();
                //echo "table headers populated in db";
		}

function populateDB($value)
{
  $db = mysql_connect("shankarr", "root");

  mysql_select_db("shankar",$db);
  $sql = "INSERT INTO headers VALUES ('$value')";

  $result = mysql_query($sql);
}

?>
