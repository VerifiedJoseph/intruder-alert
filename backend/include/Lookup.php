<?php

use GeoIp2\Database\Reader;
use Helper\Output;

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
	 * @param string $address IP address
	 * @return array<string, string>
	 */
	static public function country(string $address): array
	{
		$data = [
			'name' => 'Unknown',
			'code' => 'Unknown'
		];

		try {
			$geo = new Reader(self::$countryDBPath);

			$record = $geo->country($address);
			$data['name'] = $record->country->name;
			$data['code'] = $record->country->isoCode;

		} catch (GeoIp2\Exception\AddressNotFoundException) {
			Output::text('Address not found in GeoIP2 country database: ' . $address);
		} finally {
			return $data;
		}
	}

	/**
	 * Lookup network details for an IP address
	 * 
	 * @param string $address IP address
	 * @return array<string, string|int>
	 */
	static public function network(string $address): array
	{
		$data = [
			'name' => 'Unknown',
			'number' => 'Unknown'
		];

		try {
			$geo = new Reader(self::$asnDBPath);

			$record = $geo->asn($address);
			$data['name'] = $record->autonomousSystemOrganization;
			$data['number'] = $record->autonomousSystemNumber;

		} catch (GeoIp2\Exception\AddressNotFoundException) {
			Output::text('Address not found in GeoIP2 ASN database: ' . $address);
		} finally {
			return $data;
		}
	}
}