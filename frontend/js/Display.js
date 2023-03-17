import { Format } from './Format.js';
import { Details } from './Details.js';

export class Display
{
	constructor (data = []) {
		this.data = data
		this.details = new Details(data)
	}

	headerDates() {
		document.getElementById('last-updated').innerText = this.data.updated
		document.getElementById('data-since').innerText = ` ${this.data.dataSince} (${Format.Number(this.data.stats.totals.date)} days)`
		document.getElementById('dates').classList.remove('hide')
	}

	globalStats() {
		document.getElementById('total-bans').innerText = Format.Number(this.data.stats.bans.total)
		document.getElementById('bans-today').innerText = Format.Number(this.data.stats.bans.today)
		document.getElementById('bans-yesterday').innerText = Format.Number(this.data.stats.bans.yesterday)
		document.getElementById('bans-per-day').innerText = Format.Number(this.data.stats.bans.perDay)
		document.getElementById('total-ips').innerText = Format.Number(this.data.stats.totals.ip)
		document.getElementById('total-networks').innerText = Format.Number(this.data.stats.totals.network)
		document.getElementById('total-countries').innerText = Format.Number(this.data.stats.totals.country)
		document.getElementById('global-stats').classList.remove('hide')
	}

	mostBanned() {
		var ip = this.details.getIp(this.data.ip.mostBanned)
		var network = this.details.getNetwork(this.data.network.mostBanned)
		var country = this.details.getCountry(this.data.country.mostBanned)
	
		document.getElementById('most-banned-ip').innerText = ip.address
		document.getElementById('most-banned-ip-count').innerText = Format.Number(ip.bans)
		document.getElementById('most-banned-network').innerText = network.name
		document.getElementById('most-banned-network').setAttribute('title', network.name)
		document.getElementById('most-banned-network-count').innerText = Format.Number(network.bans);
		document.getElementById('most-banned-country').innerText = country.name
		document.getElementById('most-banned-country').setAttribute('title', country.name)
		document.getElementById('most-banned-country-count').innerText = Format.Number(country.bans);
		document.getElementById('most-banned').classList.remove('hide')
	}
}
