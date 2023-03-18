export class Filter
{
	#settings = []

	constructor (data = []) {
		this.data = data
		this.#settings = []
	}

	getData(typeList) {
		const network = document.getElementById('network-filter').value
		const country = document.getElementById('country-filter').value
		const jail = document.getElementById('jail-filter').value
	
		if (typeList === 'recentBans') {
			var data = this.#getRecentBans()
	
		} else {
			var data = this.data[typeList].list;
		}
	
		console.log(this.#settings.length)

		var filtered = []
		var _settings = this.#settings

		if (_settings.length > 0 && (typeList === 'ip' || typeList === 'recentBans') ) {

			/*for (let index = 0; index < _settings.length; index++) {
				var filter = _settings[index];

				if (filter.type === 'jail' && typeList !== 'recentBans') {
					continue;
				}
				
				var add = false;
				data.forEach(item => {
					if (item[filter.type].toString() === filter.value) {
						console.log('Match' + filter.value);
						console.log(filter)
	
						if (filter.action === 'include') {
							add = true
						}
					}
				})
			}*/

			data.forEach(item => {
				var addStatus = [];

				for (let index = 0; index < _settings.length; index++) {
					var filter = _settings[index];
	
					if (filter.type === 'jail' && typeList !== 'recentBans') {
						continue;
					}

					if (filter.action === 'include') {
						if (item[filter.type].toString() === filter.value) {
							addStatus.push(1)
						} else {
							addStatus.push(0)
						}
					}

					if (filter.action === 'exclude') {
						if (item[filter.type].toString() === filter.value) {
							addStatus.push(0)
						} else {
							addStatus.push(1)
						}
					}
				}

				if (addStatus.includes(0) === false) {
					filtered.push(item)
				}
			});

			return filtered
		}

		return data

		/*var filtered = data.filter(function (item) {
			if (typeList === 'ip' || typeList === 'recentBans') {

				for (let index = 0; index < _settings.length; index++) {
					var include = false;
					var filter = _settings[index];

					if (filter.type === 'jail' && typeList !== 'recentBans') {
						continue;
					}
				
					if (filter.action === 'include')
						include = true

					console.log(include)

					if (item[filter.type] === filter.value) {
						console.log(filter.value );

						return include;
					}
				}

				/*this.settings.forEach(filter => {
					
					if (filter.type === 'jail' && typeList !== 'recentBans') {

					}

				});*/

				/*if (network !== 'all' && network != item.network) {
					return false
				}
	
				if (country !== 'all' && country != item.country) {
					return false
				}

				if (jail !== 'all' && jail != item.jail) {
					return false
				}*/
		//	}
		
		//	return true
		//})
	
		return filtered;
	}

	save() {
		var type = document.getElementById(`filter-type`).value
		var action = document.getElementById(`filter-action`).value
		var value = document.getElementById(`filter-value`).value

		this.#settings.push({
			type: type,
			action: action,
			value: value,
		})

		this.#createLabel(type, action, value, this.#settings.length - 1)

		console.log(this.#settings)
	}

	remove(id) {
		this.#settings.splice(id, 1);
	}

	setOptions(type) {
		const select = document.getElementById(`filter-value`)
		select.innerText = ''

		var valueName = ''
		switch (type) {
			case 'ip':
				valueName = 'address'
				break;
			case 'network':
				valueName = 'number'
				break;
			case 'country':
				valueName = 'code'
				break;
			case 'jail':
				valueName = 'name'
				break;
		}
	
		this.data[type].list.forEach(function (item) {
			const option = document.createElement('option')
			option.value = item[valueName]
			option.innerText = item.name || item.address
	
			select.appendChild(option)
		})
	}

	resetOption(name) {
		document.getElementById(`${name}-filter`).value = 'all'
	}

	disableOption(name) {
		document.querySelector(`#filter-type [value="${name}"]`).disabled = true;
	}
	
	enableOption(name) {
		document.querySelector(`#filter-type [value="${name}"]`).disabled = false;
	}

	showPanel() {
		document.getElementById('filter-panel').classList.remove('hide')
	}

	hidePanel() {
		document.getElementById('filter-panel').classList.add('hide')
	}

	resetPanel() {
		document.getElementById('filter-type')[0].selected = true
		this.setOptions('ip')
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
	
		return events.reverse();
	}

	#createLabel(type, action, value, id) {
		var labelCon = document.getElementById('applied-filters')
		var div = document.createElement('div')
		var span = document.createElement('span')
		var button = document.createElement('button')

		var typeTexts = {
			address: 'IP Address',
			network: 'Network',
			country: 'Country',
			jail: 'Jail',
		}
		
		var actionText = 'is'
		if (action == 'exclude') {
			actionText = 'is not'
		}

		span.innerText = `${typeTexts[type]} ${actionText} ${value}`
		
		button.innerText = 'X'
		button.setAttribute('data-filter-id', id.toString())

		div.appendChild(span)
		div.appendChild(button)
		labelCon.appendChild(div)
	}
}
