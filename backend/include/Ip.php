<?php

class Ip {
	private array $data = [];

	public function __construct(string $address, string $jail, string $timestamp)
	{
		$this->data['address'] = $address;
		$this->data['jail'] = $jail;
		$this->data['timestamp'] = $timestamp;

		$this->data['country'] = Lookup::country($address);
		$this->data['network'] = Lookup::asn($address);
	}

	public function getDetails(): array
	{
		return $this->data;
	}
}