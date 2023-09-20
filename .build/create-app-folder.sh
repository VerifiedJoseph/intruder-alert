#!/bin/bash
# Create app folder and copy needed files
#
mkdir -p ./app/

cp -r ./backend/ ./app/backend/
cp -r ./static/ ./app/static/
cp ./index.html ./app/index.html
cp ./data.php ./app/data.php

