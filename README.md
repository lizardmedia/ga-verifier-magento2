[![Build Status](https://scrutinizer-ci.com/g/lizardmedia/ga-verifier-magento2/badges/build.png?b=master)](https://scrutinizer-ci.com/g/lizardmedia/ga-verifier-magento2/build-status/master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/lizardmedia/ga-verifier-magento2/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/lizardmedia/ga-verifier-magento2/?branch=master)
[![Latest Stable Version](https://poser.pugx.org/lizardmedia/module-ga-verifier/v/stable)](https://packagist.org/packages/lizardmedia/module-ga-verifier)
[![License](https://poser.pugx.org/lizardmedia/module-ga-verifier/license)](https://packagist.org/packages/lizardmedia/module-ga-verifier)

# Magento2 Google Analytics Verifier #

Module LizardMedia_GoogleAnalyticsVerifier allows you to add verification scripts to the <head> of all pages via admin
panel and add Google Verification files using file name and file content via admin panel, without having to upload
the file to the web server.

## Getting Started

These instructions will get you a copy of the project up and running on your local machine for development and testing purposes. See deployment for notes on how to deploy the project on a live system.

### Prerequisites

* Magento 2.2
* PHP 7.1

### Installing

#### Download the module

##### Using composer (suggested)

Simply run

```
composer require lizardmedia/module-ga-verifier
```

##### Downloading ZIP

Download a ZIP version of the module and unpack it into your project into
```
app/code/LizardMedia/GoogleAnalyticsVerifier
```

#### Install the module

Run this command
```
bin/magento module:enable LizardMedia_GoogleAnalyticsVerifier
bin/magento setup:upgrade
```

## Usage

#### Admin panel

* Enter Stores -> Configuration -> Google Analytics Verifier

## Versioning

We use [SemVer](http://semver.org/) for versioning. For the versions available, see the [tags on this repository](https://github.com/lizardmedia/ga-verifier-magento2/tags).

## Authors

* **Maciej Sławik**
* **Michał Kobierzyński**
* **Michał Broniszewski**

See also the list of [contributors](https://github.com/maciejslawik/ga-verifier-magento2/contributors) who participated in this project.

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details