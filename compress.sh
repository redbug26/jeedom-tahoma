#!/bin/bash

vi plugin_info/info.xml

VERSION=$(echo "cat info/version/text()" | xmllint --shell plugin_info/info.xml | sed '1d;$d')

echo "Current version: $VERSION"

DIR=$(basename "$PWD")

zip -r $DIR.zip * -x *.DS_Store compress.sh $DIR.zip

unzip -l $DIR.zip