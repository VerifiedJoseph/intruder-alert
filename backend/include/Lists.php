<?php 

class Lists
{
	private Lists\Address $addresses;
	private Lists\Date $dates;
	private Lists\Jail $jails;
	private Lists\Network $networks;
	private Lists\Country $countries;

	public function __construct()
	{
		$this->addresses = new Lists\Address();
		$this->dates = new Lists\Date();
		$this->jails = new Lists\Jail();
		$this->networks = new Lists\Network();
		$this->countries = new Lists\Country();
	}

	/**
	 * Add IP address
	 * 
	 * @param array<string, mixed> $ip IP address details
	 */
	public function addIp(array $ip): void
	{
		$this->addresses->addIp($ip);
		$this->dates->addIp($ip);
		$this->jails->addIp($ip);
		$this->networks->addIp($ip);
		$this->countries->addIp($ip);
	}

	/**
	 * Get lists
	 * 
	 * @return array<string, mixed>
	 */
	public function get(): array
	{
		return [
			'address' => $this->addresses->get(),
			'date' => $this->dates->get(),
			'jail' => $this->jails->get(),
			'network' => $this->networks->get(),
			'country' => $this->countries->get()
		];
	}
}