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

	static public function country(string $ip): array
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
			return [
				'name' => $name,
				'code' => $code
			];
		}
	}

	static public function asn(string $ip): array
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
			return [
				'name' => $name,
				'number' => $number
			];
		}
	}
}