<?php 

class Lists
{
	private List\Address $addresses;
	private List\Date $dates;
	private List\Jail $jails;
	private List\Network $networks;
	private List\Subnet $subnets;
	private List\Country $countries;
	private List\Continent $continents;

	public function __construct()
	{
		$this->addresses = new List\Address();
		$this->dates = new List\Date();
		$this->jails = new List\Jail();
		$this->networks = new List\Network();
		$this->subnets = new List\Subnet();
		$this->countries = new List\Country();
		$this->continents = new List\Continent();
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
		$this->subnets->addIp($ip);
		$this->countries->addIp($ip);
		$this->continents->addIp($ip);
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
			'subnet' => $this->subnets->get(),
			'country' => $this->countries->get(),
			'continent' => $this->continents->get()
		];
	}
}