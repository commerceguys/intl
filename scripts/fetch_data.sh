#!/bin/sh
mkdir assets
cd assets
git clone https://github.com/unicode-cldr/cldr-core.git
git clone https://github.com/unicode-cldr/cldr-numbers-full.git
git clone https://github.com/unicode-cldr/cldr-localenames-full.git
wget http://www.currency-iso.org/dam/downloads/table_a1.xml -O c2.xml
