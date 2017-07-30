PHP MFinante Data Scraper
=============

[![Latest Version on Packagist](https://img.shields.io/packagist/v/bytic/mfinante.svg?style=flat-square)](https://packagist.org/packages/bytic/mfinante)
[![Latest Stable Version](https://poser.pugx.org/bytic/mfinante/v/stable)](https://packagist.org/packages/bytic/mfinante)
[![Latest Unstable Version](https://poser.pugx.org/bytic/mfinante/v/unstable)](https://packagist.org/packages/bytic/mfinante)

[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE)
[![Build Status](https://img.shields.io/travis/ByTIC/mfinante/master.svg?style=flat-square)](https://travis-ci.org/ByTIC/mfinante)
[![SensioLabsInsight](https://img.shields.io/sensiolabs/i/2baaa07e-6dc3-4d1d-8f9c-c66940158289.svg?style=flat-square)](https://insight.sensiolabs.com/projects/2baaa07e-6dc3-4d1d-8f9c-c66940158289)
[![Quality Score](https://img.shields.io/scrutinizer/g/bytic/mfinante.svg?style=flat-square)](https://scrutinizer-ci.com/g/bytic/mfinante)
[![StyleCI](https://styleci.io/repos/97980380/shield?branch=master)](https://styleci.io/repos/97980380)
[![Total Downloads](https://img.shields.io/packagist/dt/bytic/mfinante.svg?style=flat-square)](https://packagist.org/packages/bytic/mfinante)


Php Scrapper for mfinante.ro website

## Usage example
Calls to mfinante website will take ~15 seconds. 
This is because of the use phantom JS browser to parse the javascript 
needed to load the mfinante.ro 

##### Getting company data.
``` php
use ByTIC\MFinante\MFinante;
$companyData = MFinante::cif($companyCif); 
```

##### Getting company balance sheet data.
``` php
use ByTIC\MFinante\MFinante;
$balanceSheetData = MFinante::balanceSheet($companyCif, $year); 
```


## Installation
It is recommended that you use Composer to install PHP PhantomJS. 
First, add the following to your project’s composer.json file:
``` composer.json
#composer.json

"scripts": {
    "post-install-cmd": [
        "ByTIC\\MFinante\\Composer\\PhantomInstaller::installPhantomJS"
    ],
    "post-update-cmd": [
        "ByTIC\\MFinante\\Composer\\PhantomInstaller::installPhantomJS"
    ]
}
```

Finally, install the library from the root of your project:
``` bash
$ composer require bytic/mfinante
```

## Changelog