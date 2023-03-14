<?php

use GeoIp2\Database\Reader;

class Lookup
{
	static private $countryDBPath = '';
	static private $asnDBPath = '';

	static public function setCountryDB(string $path): void
	{
		self::$countryDBPath = $path;
	}

	static public function setAsnDB(string $path): void
	{
		self::$asnDBPath = $path;
	}

	static public function country(string $ip): stdClass
	{
		$geo = new Reader(self::$countryDBPath);
		$name = 'Unknown';
		$code = 'Unknown';

		try {
			$record = $geo->country($ip);
			$name = $record->country->name;
			$code = $record->country->isoCode;

		} catch (GeoIp2\Exception\AddressNotFoundException) {
			// do things here
		} finally {
			$data = new stdClass();
			$data->name = $name;
			$data->code = $code;

			return $data;
		}
	}

	static public function asn(string $ip): stdClass
	{
		$geo = new Reader(self::$asnDBPath);
		$name = 'Unknown';
		$number = 'Unknown';

		try {
			$record = $geo->asn($ip);
			$name = $record->autonomousSystemOrganization;
			$number = $record->autonomousSystemNumber;

		} catch (GeoIp2\Exception\AddressNotFoundException) {
			// do things here
		} finally {
			$data = new stdClass();
			$data->name = $name;
			$data->number = $number;

			return $data;
		}
	}
}