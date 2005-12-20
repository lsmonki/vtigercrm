<?php


$top="215";

$desc=explode("\n",$description);
$cond=explode("\n",$conditions);
$num=230;

/* **************** Begin Description ****************** */
$descBlock=array("10",$top,"53", $num);
$pdf->addDescBlock($description, "Description", $descBlock);

/* ************** End Description *********************** */



/* **************** Begin Terms ****************** */
$termBlock=array("107",$top,"53", $num);
$pdf->addDescBlock($conditions, "Terms & Conditions", $termBlock);

/* ************** End Terms *********************** */


?>
