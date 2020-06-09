<?php

require '../vendor/autoload.php';

use ByTIC\MFinante\MFinante;

$companyCif = 32586219;
$params = [
    'captcha' => isset($_POST['captcha']) ? $_POST['captcha'] : null
];
$companyData = MFinante::cif($companyCif, $params);

$content = $companyData->getContent();
if (isset($content['captcha-html'])) {
    echo $content['captcha-html'];
    echo '<form action="" method="POST">';
    echo '<input type="text" name="captcha" value="" />';
    echo '<input type="submit" value="GO" />';
    echo '</form>';
} else {

    var_dump($companyData->getContent());
}
