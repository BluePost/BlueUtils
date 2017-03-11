# BlueUtils
Simple utils for PHP and JavaScript

## Contents
* BlueAjax  - Simple utilities for dealing with api requests in php and JS
* BlueAuth - A php auth library which is an easy way to set up a simple login/signup API
* BlueEmail - A wrapper for sending emails easily with SendGrid
* BlueError - A library for standardising API error methods
* BlueFiller - A simple utility for putting data into html pages
* BlueUtilsSettings - The main way of configuring
* Bundler - A utility that allows development of twig files that can then be compiled to static HTML files
* Loadr - A JS utility for loading in handlebars template
* Setup - A script for initialising a new project

## Getting started
To start a new project do the following:
* Install and globally alias Composer (install and `run mv composer.phar /usr/local/bin/composer`)
* Clone the repo into an empty project folder
* `cd` into `BlueUtils/Setup`
* Execute `./setup.sh -n`
* To update the BlueUtils components do this:
  * `git pull`
  * `cd Setup`
  * `./setup` (VERY IMPORTANT TO REMEMBER NOT INCLUDE a `-n`)
* Change settings in the file `helpers/BlueUtilsSettings/BlueUtilsSettings.php`

The file `helpers/header.php` should be included in all API pages. It sets up all php modules (Ajaz, auth, email, error) by default.

## TODO
* Update all docs - especially JS (Show where files are after ./setup.sh)

### Loadr
* Use promises for the main object
  * Find a way to have default actions
* Add a default onFail option

### BlueAjax

### BlueError

### Bundler

### Setup
* Allow custom writing of the .htaccess file

## Contributing
To contribute please submit a pull request

## Licence
<a rel="license" href="http://creativecommons.org/licenses/by-sa/4.0/"><img alt="Creative Commons License" style="border-width:0" src="https://i.creativecommons.org/l/by-sa/4.0/88x31.png" /></a><br />This work is licensed under a <a rel="license" href="http://creativecommons.org/licenses/by-sa/4.0/">Creative Commons Attribution-ShareAlike 4.0 International License</a>.
