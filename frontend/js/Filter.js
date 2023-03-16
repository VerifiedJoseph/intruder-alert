export class Filter
{
	constructor (data = []) {
		this.data = data
	}

	getData(type) {
		const network = document.getElementById('network-filter').value
		const country = document.getElementById('country-filter').value
	
		if (type === 'recentBans') {
			var data = this.#getRecentBans()
	
		} else {
			var data = this.data[type].list;
		}
	
		var filtered = data.filter(function (item) {
			if (type === 'ip' || type === 'recentBans') {
				if (network !== 'all' && network != item.network) {
					return false
				}
	
				if (country !== 'all' && country != item.country) {
					return false
				}
			}
		
			return true
		})
	
		return filtered;
	}

	#getRecentBans() {
		var events = []

		this.data.ip.list.forEach(ip => {
			ip.events.forEach(event => {
				events.push({
					'address': ip.address,
					'jail': event.jail,
					'network': ip.network,
					'country': ip.country,
					'timestamp': event.timestamp
				})
			})
		})
	
		events.sort(function(a, b){
			var da = new Date(a.timestamp).getTime();
			var db = new Date(b.timestamp).getTime();
			
			return da < db ? -1 : da > db ? 1 : 0
		});
	
		return events.reverse().slice(0, 500);
	}
}
