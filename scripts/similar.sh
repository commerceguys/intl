#!/bin/sh
# Can be used to detect languages which lack translations in Commerce intl
# Usage:
#   ./similar en.json   -   will compare all files in current dir with en.json
#   ./similar en.json 5 -   will compare all files in current dir with en.json
#       and a threshold of under 5 lines being different before reporting
#
# Run this from within any resources/<type> folder, e.g. 'resources/country'
# $   ./../../scripts/similar.sh en.json 60

BASEFILE="$1"
LINE_DIFFERENCE_THRESHOLD=${2:-"40"}


for FILE in *.json
  do
  if [ "$BASEFILE" != "$FILE" ]
  then
    LINE_DIFFERENCE="$(diff -U 0 "$BASEFILE" "$FILE" | grep ^@ | wc -l)"
      if [[ "$LINE_DIFFERENCE" -lt "$LINE_DIFFERENCE_THRESHOLD" ]]
        then
        FILE=${FILE%.json}
        printf "'$FILE', "
      fi
  fi
done
