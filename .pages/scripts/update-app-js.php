<?php
#
# Update fetch file path in app.js to use data.json.
#
$jsonFilepath = '../data.json';
$jsFilepath = '../../frontend/js/app.js';

$data = file_get_contents($jsFilepath);

$data = str_replace(
	'data.php', 'data.json', $data
);

file_put_contents($jsFilepath, $data);
