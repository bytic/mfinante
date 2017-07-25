PHP MFinante Data Scraper
=============
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