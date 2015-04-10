PHP METAR decoder
=================

A PHP library to decode METAR strings, fully unit tested (100% code coverage)

[![License](https://poser.pugx.org/inouire/php-metar-decoder/license.svg)](https://packagist.org/packages/inouire/php-metar-decoder)
[![Build Status](https://travis-ci.org/inouire/php-metar-decoder.svg?branch=master)](https://travis-ci.org/inouire/php-metar-decoder)
[![Latest Stable Version](https://poser.pugx.org/inouire/php-metar-decoder/v/stable.svg)](https://packagist.org/packages/inouire/php-metar-decoder)

Introduction
------------

This piece of software is a library package that provides a parser to decode raw METAR observation.

METAR is a format made for weather information reporting. METAR weather reports are predominantly used by pilots and by meteorologists, who use it to assist in weather forecasting.
Raw METAR format is highly standardized through the International Civil Aviation Organization (ICAO).

*    [METAR definition on wikipedia](http://en.wikipedia.org/wiki/METAR)
*    METAR format specification(link needed)
*    [METAR documentation](http://meteocentre.com/doc/metar.html)

Requirements
------------

This library package only requires PHP >= 5.3 

It is currently tested automatically for PHP 5.3, 5.4 and 5.5.

If you want to integrate it easily in your project, you should consider installing [composer](http://getcomposer.org) on your system.
It is not mandatory though.

Setup
-----

- With composer *(recommended)*

Add the following line to the `composer.json` of your project

```json
{
    "require": {
        "inouire/php-metar-decoder": "dev-master"
    }
}
```

Launch install from your project root with:

```shell
composer install --no-dev
```

Load the library thanks to composer autoloading:

```php
<?php
require_once 'vendor/autoload.php';
```

- By hand

Download the latest release from [github](https://github.com/inouire/php-metar-decoder/releases)

Extract it wherever you want in your project. The library itself is in the src/ directory, the other directories are not mandatory for the library to work.

Load the library with the static import file:

```php
<?php
require_once 'path/to/MetarDecoder/MetarDecoder.inc.php';
```

Usage
-----

Instantiate the decoder and launch it on a METAR string.
The returned object is a DecodedMetar object from which you can retrieve all the weather properties that have been decoded.

All values who have a unit are based on the `Value` object which provides the methods `getValue()` and `getUnit()`

*TODO: full documentation of the structure of the DecodedMetar object*

```php
<?php

require_once 'vendor/autoload.php';

$decoder = new MetarDecoder\MetarDecoder();
$d = $decoder->parse('PAPO 131156Z 31014KT 5SM +DZ BR OVC042 M23/M27 A2959 RMK A01 11200 21230 52010')

//context information
$d->isValid()); //true
$d->getRawMetar(); //'METAR LFPO 231027Z AUTO 24004G09MPS 2500 1000NW R32/0400 R08C/0004D +FZRA VCSN //FEW015 17/10 Q1009 REFZRA WS R03'
$d->getType(); //'METAR'
$d->getIcao(); //'LFPO'
$d->getDay(); //23
$d->getTime(); //'10:27 UTC'
$d->getStatus(); //'AUTO'

//surface wind
$sw = $d->getSurfaceWind(); //SurfaceWind object
$sw->getMeanDirection()->getValue(); //240
$sw->getMeanSpeed()->getValue(); //4
$sw->getSpeedVariations()->getValue(); //9
$sw->getMeanSpeed()->getUnit(); //'m/s'

//visibility
$v = $d->getVisibility(); //Visibility object
$v->getVisibility()->getValue(); //2500
$v->getVisibility()->getUnit(); //'m'
$v->getMinimumVisibility()->getValue(); //1000
$v->getMinimumVisibilityDirection(); //'NW'

//runway visual range
$rvr = $d->getRunwaysVisualRange(); //RunwayVisualRange array
$rvr[0]->getRunway(); //'32'
$rvr[0]->getVisualRange()->getValue(); //400
$rvr[0]->getPastTendency(); //''
$rvr[1]->getRunway(); //'08C'
$rvr[1]->getVisualRange()->getValue(); //4
$rvr[1]->getPastTendency(); //'D'

//present weather
$pw = $d->getPresentWeather(); WeatherPhenomenon array
$pw[0]->getIntensityProximity(); //'+'
$pw[0]->getCharacteristics(); //'FZ'
$pw[0]->getTypes(); //array('RA')
$pw[1]->getIntensityProximity(); //'VC'
$pw[1]->getCharacteristics(); //null
$pw[1]->getTypes(); //array('SN')

// clouds
$cld = $d->getClouds(); //CloudLayer array
$cld[0]->getAmount(); //'FEW'
$cld[0]->getBaseHeight()->getValue(); //1500
$cld[0]->getBaseHeight()->getUnit(); //'ft'

// temperature
$d->getAirTemperature()->getValue(); //17
$d->getAirTemperature()->getUnit(); //'deg C'
$d->getDewPointTemperature()->getValue(); //10

// pressure
$d->getPressure()->getValue(); //1009
$d->getPressure()->getUnit(); //'hPa'

// recent weather
$rw = $d->getRecentWeather();
$rw->getCharacteristics(); //'FZ'
current($rw->getTypes()); //'RA'

// windshears
$d->getWindshearRunways(); //array('03')

```

Contribute
----------

If you find a valid METAR that is badly parsed by this library, please open a github issue with all possible details:

- the full METAR causing problem
- the parsing exception returned by the library
- how you expected the decoder to behave
- anything to support your proposal (links to official websites appreciated)

If you want to improve or enrich the test suite, fork the repository and submit your changes with a pull request.

If you have any other idea to improve the library, please use github issues or directly pull requests depending on what you're more confortable with.

Tests and coverage
------------------

This library is fully unit tested, and uses [PHPUnit](https://phpunit.de/getting-started.html) to launch the tests.

Travis CI is used for continuous integration, which triggers tests for PHP 5.3, 5.4, 5.5 for each push to the repo.

To run the tests by yourself, you must first install the dev dependencies ([composer](http://getcomposer.org) needed)

```shell
composer install --dev
apt-get install php5-xdebug # only needed if you're interested in code coverage
```

Launch the test suite with the following command:
    
```shell
./vendor/bin/phpunit tests
```

You can also generate an html coverage report by adding the `--coverage-html` option:

```shell
./vendor/bin/phpunit --coverage-html ./report tests
```



