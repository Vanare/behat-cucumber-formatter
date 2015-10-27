# Behat Cucumber Json Formmatter 

This is Behat extension for generating json reports for [Cucumber Test Result Plugin](https://github.com/jenkinsci/cucumber-testresult-plugin/) which provides graphs over time and drill down to individual results using the standard Jenkins test reporting mechanism.


## Requirements

- PHP 5.5.x or higher

- Behat 3.x

## Installation

### Installation via Composer:

```
composer require --dev fourxxi/behat-cucumber-json-formatter
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

### Master
[ ![Codeship Status for 4xxi/behat-cucumber-json-formatter](https://codeship.com/projects/7bfc56d0-50fa-0133-3a1b-4adc43044553/status?branch=develop)](https://codeship.com/projects/107883)

### Develop
[ ![Codeship Status for 4xxi/behat-cucumber-json-formatter](https://codeship.com/projects/7bfc56d0-50fa-0133-3a1b-4adc43044553/status?branch=develop)](https://codeship.com/projects/107883)