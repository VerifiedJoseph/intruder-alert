<?php

class Ip 
{
	/** @var array<string, mixed> $data IP address details */
	private array $data = [];

	/**
	 * @param string $address IP address
	 * @param string $jail Jail name
	 * @param string $timestamp Event timestamp
	 */
	public function __construct(string $address, string $jail, string $timestamp)
	{
		$this->data['address'] = $address;
		$this->data['jail'] = $jail;
		$this->data['timestamp'] = $timestamp;

		$this->data['country'] = Lookup::country($address);
		$this->data['network'] = Lookup::asn($address);
	}

	/**
	 * Get IP address details
	 * 
	 * @return array<string, mixed>
	 */
	public function getDetails(): array
	{
		return $this->data;
	}
}