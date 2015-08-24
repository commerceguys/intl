#!/bin/sh

URL="http://www.currency-iso.org/dam/downloads/lists/list_one.xml"

rm -fR assets
mkdir assets
cd assets

git clone https://github.com/unicode-cldr/cldr-core.git
git clone https://github.com/unicode-cldr/cldr-numbers-full.git
git clone https://github.com/unicode-cldr/cldr-localenames-full.git

if command -v wget >/dev/null 2>&1;
then
    wget $URL -O c2.xml
else 
    if command -v curl >/dev/null 2>&1;
    then
        curl $URL > c2.xml
    else
        echo "I require wget or curl but it's not installed.  Aborting."
        exit 1
    fi
fi
