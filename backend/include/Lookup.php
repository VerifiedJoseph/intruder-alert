<?php

use GeoIp2\Database\Reader;

/**
 * Class for looking up IP address details in GeoIP2 databases
 */
class Lookup
{
	/** @var string $countryDBPath GeoIP2 country database path */
	static private string $countryDBPath = '';

	/** @var string $asnDBPath GeoIP2 network database path */
	static private string $asnDBPath = '';

	/**
	 * Set country database
	 * 
	 * @param string $path Database path
	 */
	static public function setCountryDB(string $path): void
	{
		self::$countryDBPath = $path;
	}

	/**
	 * Set network database
	 * 
	 * @param string $path Database path
	 */
	static public function setNetworkDB(string $path): void
	{
		self::$asnDBPath = $path;
	}

	/**
	 * Lookup country details for an IP address
	 * 
	 * @param string $ip IP address
	 * @return array<string, string>
	 */
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

	/**
	 * Lookup network details for an IP address
	 * 
	 * @param string $ip IP address
	 * @return array<string, string|int>
	 */
	static public function network(string $ip): array
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