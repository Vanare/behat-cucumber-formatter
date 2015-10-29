# Behat Cucumber Json Formatter 

[![Build Status](https://travis-ci.org/adamculp/api-consumer.svg?branch=master)](https://travis-ci.org/adamculp/api-consumer)

This is Behat extension for generating json reports for [Cucumber Test Result Plugin](https://github.com/jenkinsci/cucumber-testresult-plugin/) which provides graphs over time and drill down to individual results using the standard Jenkins test reporting mechanism.

## Requirements

- PHP 5.5.x or higher

- Behat 3.x

## Installation

### Installation via Composer:

Add following strings to your `composer.json`:

```
"repositories": [
    {
        "url": "https://github.com/Vanare/behat-cucumber-formatter.git",
        "type": "git"
    }
],
//...
"require": {
    //...
    "vanare/behat-cucumber-json-formatter": "dev-master",
},
```

Then install composer dependencies:

```
composer install
```

## Usage

Setup extension by specifying your `behat.yml`:

```
default:
    extensions:
        Vanare\BehatCucumberJsonFormatter\Extension:
            filename: report.json
            outputDir: %paths.base%/build/tests
```

Then you can run:

```
bin/behat -f cucumber_json
```

### Available options:

- `filename`: Filename of generated report
- `outputDir`: Generated report will be placed in this directory

## Licence

MIT Licence