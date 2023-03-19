<?php 

class Lists {
	private Lists\Address $addressList;
	private Lists\Date $dateList;
	private Lists\Jail $jailList;
	private Lists\Network $networkList;
	private Lists\Country $countryList;

	public function __construct()
	{
		$this->addressList = new Lists\Address();
		$this->dateList = new Lists\Date();
		$this->jailList = new Lists\Jail();
		$this->networkList = new Lists\Network();
		$this->countryList = new Lists\Country();
	}

	public function addIp(array $ip): void
	{
		$this->addressList->addIp($ip);
		$this->dateList->addIp($ip);
		$this->jailList->addIp($ip);
		$this->networkList->addIp($ip);
		$this->countryList->addIp($ip);
	}

	public function get(): array
	{
		return [
			'address' => $this->addressList->get(),
			'date' => $this->dateList->get(),
			'jail' => $this->jailList->get(),
			'network' => $this->networkList->get(),
			'country' => $this->countryList->get()
		];
	}
}