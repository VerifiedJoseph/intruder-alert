<?php

use Exception\AppException;
use Helper\Json;

require 'vendor/autoload.php';

$data = '';

try {
    Config::setDir(__DIR__);

	$app = new App();
	$data = $app->getJsonReport();

} catch (AppException $err) {
	$data = Json::encode([
		'error' => true,
		'message' => $err->getMessage()
	]);
} finally {
	header('cache-control: no-cache');
	header('content-type: application/json');
	echo $data;
}
