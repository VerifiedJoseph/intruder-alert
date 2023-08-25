#!/bin/bash
# Create app folder and copy needed files
#
mkdir ./app/demo/

cp -r ./static/ ./app/demo/static/
cp ./imdex.html ./app/demo/index.html
cp ./.pages/data.json ./app/demo/data.json
