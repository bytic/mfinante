<?php

require '../vendor/autoload.php';

use ByTIC\MFinante\MFinante;

$companyCif = 32586219;
$year       = 2016;

$balanceSheetData = MFinante::balanceSheet($companyCif, $year);

var_dump($balanceSheetData);
