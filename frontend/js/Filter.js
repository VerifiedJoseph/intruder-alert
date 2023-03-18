export class Filter
{
	#settings = []

	constructor (data = []) {
		this.data = data
		this.#settings = []
	}

	getData(typeList) {
		if (typeList === 'recentBans') {
			var data = this.#getRecentBans()
	
		} else {
			var data = this.data[typeList].list;
		}
	
		var filtered = []

		if (this.#settings.length > 0 && (typeList === 'ip' || typeList === 'recentBans') ) {
			data.forEach(item => {
				var addStatus = [];

				for (let index = 0; index < this.#settings.length; index++) {
					var filter = this.#settings[index];

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

	reset() {
		this.#settings = []
		document.getElementById('applied-filters').innerText = ''
	}

	setOptions(type) {
		const select = document.getElementById(`filter-value`)
		select.innerText = ''

		if (type === 'address') {
			type = 'ip';
		}

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
		document.getElementById('filter-action')[0].selected = true

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
			ip: 'IP Address',
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
