<?php

require_once('database/DatabaseConnection.php');
$idlist = $_POST['idlist'];
//split the string and store in an array
$storearray = explode(";",$idlist);
foreach($storearray as $id)
{
$sql = "Delete from users where id='" .$id ."'";
$result = mysql_query($sql);
}

header("Location: index.php?module=Administration&action=index");




?>