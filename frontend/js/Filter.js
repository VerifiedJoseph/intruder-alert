import { Details } from './Details.js';

export class Filter
{
	#settings = []
	#details

	constructor (data = []) {
		this.data = data
		this.#settings = []
		this.#details = new Details(data)
	}

	/**
	 * Get filtered data
	 * @param {string} typeList
	 * @returns 
	 */
	getData(typeList) {
		if (typeList === 'recentBans') {
			var data = this.#getRecentBans()
	
		} else {
			var data = this.data[typeList].list;
		}
	
		var filtered = []

		if (this.#settings.length > 0 && (typeList === 'address' || typeList === 'recentBans') ) {
			data.forEach(item => {
				var addStatus = [];

				for (let index = 0; index < this.#settings.length; index++) {
					var filter = this.#settings[index];

					if (filter.type === 'jail' && typeList !== 'recentBans') {
						continue;
					}

					var value
					if (filter.type === 'date') {
						var date = new Date(item.timestamp)
						var parts = date.toISOString().substring(0, 10).split('-')
						
						value = `${parts[0]}-${parts[1]}-${parts[2]}`
					} else {
						value = item[filter.type].toString()
					}

					if (filter.action === 'include' && filter.values.length > 0) {
						if (filter.values.includes(value) === true) {
							addStatus.push(1)
						} else {
							addStatus.push(0)
						}
					}

					if (filter.action === 'exclude' && filter.values.length > 0) {
						if (filter.values.includes(value) === true) {
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

	/**
	 * Add filter
	 * @param {string} type Filter type
	 * @param {string} action Filter action (include or exclude)
	 * @param {string} value Filter value
	 */
	add(type, action, value) {
		var index = this.#findFilter(type, action)

		if (index !== false) {
			this.#settings[index].values.push(value)
			this.#createLabel(type, action, value, this.#settings[index].id)

		} else {
			var id = crypto.randomUUID();

			this.#settings.push({
				id: id,
				type: type,
				action: action,
				values: [value],
			})

			this.#createLabel(type, action, value, id)
		}

		console.log(this.#settings)
	}

	/**
	 * Remove filter by its type
	 * @param {string} type filter type 
	 */
	remove(type)
	{
		var id = null;

		this.#settings = this.#settings.filter(filter => {
			if (filter.type === type) {
				id = filter.id
				return false;
			}

			return true;
		})

		if (id !== null) {
			this.#removeLabel(id)
		}
	}

	/**
	 * Remove value from a filter
	 * @param {string} filterId filter UUID
	 * @param {value} value filter value
	 */
	removeValue(filterId, value = null) {
		var index = this.#findFilterByUUID(filterId)
		var filter = this.#settings[index]

		this.#settings[index].values = filter.values.filter(
			item => item !== value
		)
	}

	/**
	 * Reset filters
	 */
	reset() {
		this.#settings = []
		document.getElementById('applied-filters').innerText = ''
	}

	/**
	 * Set filter select options
	 * @param {string} type Filter type
	 */
	setOptions(type) {
		const select = document.getElementById(`filter-value`)
		select.innerText = ''

		var valueName = ''
		switch (type) {
			case 'address':
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
			case 'date':
				valueName = 'date'
				break;
		}
	
		for (let index = 0; index < this.data[type].list.length; index++) {
			const item = this.data[type].list[index];
			
			const option = document.createElement('option')
			option.value = item[valueName]
			option.innerText = item.date || item.name || item.address

			if (this.hasFilter(type, item[valueName]) === true) {
				option.disabled = true
			}
	
			select.appendChild(option)
		}
	}

	/**
	 * Disable select option
	 * @param {string} name
	 */
	disableOption(name) {
		document.querySelector(`#filter-type [value="${name}"]`).disabled = true;
	}
	
	/**
	 * Enable select option
	 * @param {string} name
	 */
	enableOption(name) {
		document.querySelector(`#filter-type [value="${name}"]`).disabled = false;
	}

	/**
	 * Show filter panel
	 */
	showPanel() {
		document.getElementById('filter-panel').classList.remove('hide')
	}

	/**
	 * Hide filter panel
	 */
	hidePanel() {
		document.getElementById('filter-panel').classList.add('hide')
	}

	/**
	 * Reset filter panel
	 */
	resetPanel() {
		document.getElementById('filter-type')[0].selected = true
		document.getElementById('filter-action')[0].selected = true

		this.setOptions('address')
	}

	/**
	 * Check if filter has a value
	 * @param {string} type Filter type
	 * @param {string} value Filter value
	 * @returns 
	 */
	hasFilter(type, value) {
		var status = false

		this.#settings.forEach(filter => {
			if (filter.type === type && filter.values.includes(value.toString()) === true) {
				status = true
			}
		});

		return status
	}

	/**
	 * Find filter array index by a filter's unique identifier
	 * @param {string} uuid Unique identifier
	 * @returns Array index of filter
	 */
	#findFilterByUUID(uuid) {
		var key = null;

		this.#settings.forEach((filter, index) => {
			if (filter.id === uuid) {
				key = index
			}
		});

		if (key !== null) {
			return key
		}

		return false
	}

	#findFilter(type, action) {
		var key = null;

		this.#settings.forEach((filter, index) => {
			if (filter.type === type && filter.action === action) {
				key = index
			}
		});

		if (key !== null) {
			return key
		}

		return false
	}

	/**
	 * Get recent bans
	 */
	#getRecentBans() {
		var events = []

		this.data.address.list.forEach(ip => {
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

	/**
	 * Create filter label
	 * @param {string} type Filter type
	 * @param {string} action Filter action
	 * @param {string} value Filter value
	 * @param {string} uuid Filter UUID
	 */
	#createLabel(type, action, value, uuid) {
		var labelCon = document.getElementById('applied-filters')
		var div = document.createElement('div')
		var span = document.createElement('span')
		var button = document.createElement('button')

		var typeTexts = {
			address: 'IP Address',
			network: 'Network',
			country: 'Country',
			jail: 'Jail',
			date: 'Date',
		}
		
		var valueText = value
		if (type === 'network') {
			var network = this.#details.getNetwork(value)
			valueText = network.name
		}

		if (type === 'country') {
			var country = this.#details.getCountry(value)
			valueText = country.name
		}

		var actionText = 'is'
		if (action == 'exclude') {
			actionText = 'is not'
		}

		span.innerText = `${typeTexts[type]} ${actionText} '${valueText}'`
		
		button.innerText = 'X'
		button.setAttribute('title', `Remove filter '${typeTexts[type]} ${actionText} ${valueText}'`)
		button.setAttribute('data-filter-id', uuid)
		button.setAttribute('data-filter-value', value)

		div.appendChild(span)
		div.appendChild(button)
		div.classList.add('item')
		div.setAttribute('data-label-id', uuid)

		labelCon.appendChild(div)
	}

	/**
	 * Remove filter label
	 * @param {string} uuid Filter UUID
	 */
	#removeLabel(uuid) {
		document.querySelector(`div[data-label-id="${uuid}"]`).remove()
	}
}
