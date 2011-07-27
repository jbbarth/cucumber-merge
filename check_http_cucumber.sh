#!/bin/bash

nagiosdir=/usr/local/nagios
datadir=$nagiosdir/share/cucumber
confdir=$nagiosdir/etc/cucumber

source $nagiosdir/libexec/utils.sh

cd $confdir/

# simple version, without html output
# /usr/bin/cucumber-nagios features/$*

# advanced version, with multiple output (html + json + nagios)
/usr/bin/cucumber -rfeatures features/$1 \
  --format html --out $datadir/.html/$1.html \
  --format json --out $datadir/.json/$1.json \
  --format Cucumber::Formatter::Nagios
RETVAL=$?

# replace the title of the site page so that it becomes a link to our portal (index.php)
sed -i 's!Cucumber Features!<a href="../" style="color:#fff;">My Cucumber Portal</a>!' $datadir/.html/$1.html

# return the correct value for nagios
if [ "$RETVAL" == "0" ]; then
  exit $STATE_OK
else
  exit $STATE_CRITICAL
fi
