<?php

require '../vendor/autoload.php';

use ByTIC\MFinante\MFinante;

$companyCif  = 32586219;
$companyData = MFinante::cif($companyCif);

var_dump($companyData->getContent());
