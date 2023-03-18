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

	public function addIp(Ip $ip) {
		$this->addressList->addIp($ip->getDetails());
		$this->dateList->addIp($ip->getDetails());
		$this->jailList->addIp($ip->getDetails());
		$this->networkList->addIp($ip->getDetails());
		$this->countryList->addIp($ip->getDetails());
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