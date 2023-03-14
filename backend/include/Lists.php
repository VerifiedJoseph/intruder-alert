<?php 

class Lists {
	private Lists\Ip $ipList;
	private Lists\Date $dateList;
	private Lists\Jail $jailList;
	private Lists\Network $networkList;
	private Lists\Country $countryList;

	public function __construct()
	{
		$this->ipList = new Lists\Ip();
		$this->dateList = new Lists\Date();
		$this->jailList = new Lists\Jail();
		$this->networkList = new Lists\Network();
		$this->countryList = new Lists\Country();
	}

	public function addIp(Ip $ip) {
		$this->ipList->addIp($ip->getDetails());
		$this->dateList->addIp($ip->getDetails());
		$this->jailList->addIp($ip->getDetails());
		$this->networkList->addIp($ip->getDetails());
		$this->countryList->addIp($ip->getDetails());
	}

	public function get(): array
	{
		return [
			'ip' => $this->ipList->get(),
			'date' => $this->dateList->get(),
			'jail' => $this->jailList->get(),
			'network' => $this->networkList->get(),
			'country' => $this->countryList->get()
		];
	}
}