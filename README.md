Cucumber-merge
==============

This repo aims at easing the integration of "cucumber":http://cukes.info/ and "cucumber-nagios":http://auxesis.github.com/cucumber-nagios/ with your nagios instance (or shinken or icinga etc.) :

* **check_http_cucumber.sh** is a sample nagios check script to help you test your websites ; cucumber-nagios is fine if we want a quick and dirty check in nagios, but this script uses directly the cucumber command so that it can have 3 outputs : an html page, a json file, and finally the nagios (formatted thanks to cucumber-nagios) to stdout.
* **index.php** is a sample PHP script which could be put in the $datadir defined in check_http_cucumber.sh : it aims at centralizing informations for different cucumber runs, so that you have a clean global portal in front of your "unit" cucumber html files

Installation
------------

TODO

Contribute
----------

TODO
