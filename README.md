# BlueUtils
Simple utils for PHP and JavaScript

## Contents
* BlueAjax  - Simple utilities for dealing with api requests in php and JS
* BlueError - A library for standardising API error methods
* Bundler - A utility that allows development of twig files that can then be compiled to static HTML files
* Loadr - A JS utility for loading in handlebars template
* Setup - A script for initialising a new project

## Getting started
To start a new project do the following:
* Clone the repo into an empty project folder
* `cd` into `BlueUtils/Setup`
* Execute `./setup.sh -n`
* To update the BlueUtils components do this:
  * `git pull`
  * `cd Setup`
  * `./setup` (VERY IMPORTANT TO REMEMBER NOT INCLUDE a `-n`)

## TODO

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
