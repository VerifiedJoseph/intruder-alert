<?php

use GeoIp2\Database\Reader;
use Helper\Output;

/**
 * Class for looking up IP address details in GeoIP2 databases
 */
class Lookup
{
	/** @var Reader $countryReader GeoIP2 country database reader */
	static private Reader $countryReader;

	/** @var Reader $networkReader GeoIP2 network database reader */
	static private Reader $networkReader;

	/**
	 * Set country database
	 * 
	 * @param string $path Database path
	 */
	static public function setCountryDB(string $path): void
	{
		self::$countryReader = new Reader($path);
	}

	/**
	 * Set network database
	 * 
	 * @param string $path Database path
	 */
	static public function setNetworkDB(string $path): void
	{
		self::$networkReader = new Reader($path);
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
			$record = self::$countryReader->country($address);
			$data['name'] = (string) $record->country->name;
			$data['code'] = (string) $record->country->isoCode;
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
			$record = self::$networkReader->asn($address);
			$data['name'] = (string) $record->autonomousSystemOrganization;
			$data['number'] = (int) $record->autonomousSystemNumber;
		} catch (GeoIp2\Exception\AddressNotFoundException) {
			Output::text('Address not found in GeoIP2 ASN database: ' . $address);
		} finally {
			return $data;
		}
	}
}