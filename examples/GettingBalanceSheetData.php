<?php

require '../vendor/autoload.php';

use ByTIC\MFinante\MFinante;

$companyCif = 6453132;
$year       = 2012;

$balanceSheetData = MFinante::balanceSheet($companyCif, $year);

var_dump($balanceSheetData->getContent());
