<?php
#
# Update fetch file path in index.js to use data.json.
#
$jsonFilepath = './.pages/data.json';
$jsFilepath = './static/js/index.js';

$data = file_get_contents($jsFilepath);

$data = str_replace(
	'data.php', 'data.json', $data
);

file_put_contents($jsFilepath, $data);
