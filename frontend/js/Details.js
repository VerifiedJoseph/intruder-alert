export class Details
{
	constructor (data = [])
	{
		this.data = data
	}

	getIp(address)
	{
		for (var i = 0; i < this.data.ip.list.length; i++) {
			if (this.data.ip.list[i].address === address) {
				return this.data.ip.list[i];
			}
		}
	}

	getNetwork(number)
	{
		for (var i = 0; i < this.data.network.list.length; i++) {
			if (this.data.network.list[i].number.toString() === number.toString()) {
				return this.data.network.list[i];
			}
		}
	}
	
	getCountry(code)
	{
		for (var i = 0; i < this.data.country.list.length; i++) {
			if (this.data.country.list[i].code === code) {
				return this.data.country.list[i];
			}
		}
	}
}
