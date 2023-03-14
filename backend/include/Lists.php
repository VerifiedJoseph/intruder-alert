<?php 

class Lists {
	private IpList $ipList;
	private DateList $dateList;
	private JailList $jailList;
	private NetworkList $networkList;
	private CountryList $countryList;

	public function __construct()
	{
		$this->ipList = new IpList();
		$this->dateList = new DateList();
		$this->jailList = new JailList();
		$this->networkList = new NetworkList();
		$this->countryList = new CountryList();
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