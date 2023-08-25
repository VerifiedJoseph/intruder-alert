#!/bin/bash
# Create app folder and copy needed files
#
mkdir -p ./app/demo/

cp -r ./static/ ./app/demo/static/
cp ./index.html ./app/demo/index.html
cp ./.pages/data.json ./app/demo/data.json
