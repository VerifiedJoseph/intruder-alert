<?php 

class Lists
{
	private List\Address $addresses;
	private List\Date $dates;
	private List\Jail $jails;
	private List\Network $networks;
	private List\Country $countries;

	public function __construct()
	{
		$this->addresses = new List\Address();
		$this->dates = new List\Date();
		$this->jails = new List\Jail();
		$this->networks = new List\Network();
		$this->countries = new List\Country();
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