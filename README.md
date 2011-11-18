Cucumber-merge
==============

This repo aims at easing the integration of [cucumber](http://cukes.info/) and [cucumber-nagios](http://auxesis.github.com/cucumber-nagios/) with your nagios instance (or shinken or icinga etc.) :

* **check_http_cucumber.sh** is a sample nagios check script to help you test your websites ; cucumber-nagios is fine if we want a quick and dirty check in nagios, but this script uses directly the cucumber command so that it can have 3 outputs : an html page, a json file, and finally the nagios (formatted thanks to cucumber-nagios) to stdout.
* **index.php** is a sample PHP script which could be put in the $datadir defined in check_http_cucumber.sh : it aims at centralizing informations for different cucumber runs, so that you have a clean global portal in front of your "unit" cucumber html files

Installation
------------

You can use the PHP script wherever you want, as soon as you have access to both HTML and JSON outputs from Cucumber. I personnaly tend to do the following :

* create a new `cucumber` sub-directory under the `share/` directory at the root of my Nagios installation (so that it's automatically served by Apache)
* put the `index.php` script in this directory
* generate `.html/www.example.com.html` and `.json/www.example.com.json` in under this same directory

Contribute
----------

If you want to contribute :

* [Fork](http://help.github.com/forking/) the project
* Do your changes and commit them to your repository
* Send me a pull request

Licence
-------

These scripts are released under the MIT license.
