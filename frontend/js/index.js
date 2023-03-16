import { Table, Row, Cell } from './table.js'

"use strict";

var botData = {}
var tableHeaders = {
	'ip': ['Address', 'Bans', 'Network', 'Country' , ''],
	'jail': ['Jail', 'IPs', 'Bans', ''],
	'network': ['Network', 'IPs', 'Bans', ''],
	'country': ['Country', 'IPs', 'Bans', ''],
	'events': ['Date', 'Jail'],
	'recentBans': ['Date', 'Jail', 'Address', 'Network', 'Country'],
	'date': ['Date', 'IPs', 'Bans', '']
}

function formatNumber (number) {
	return new Intl.NumberFormat().format(number)
}

function fetchData() {
	return fetch('data.json');
}

function setFilterOptions(type) {
	const select = document.getElementById(`${type}-filter`)
	select.innerText = ''

	const option = document.createElement('option')
	option.value = 'all'

	if (type == 'network') {
		option.innerText = 'All networks'
	} else {
		option.innerText = 'All countries'
	}

	select.appendChild(option)

	botData[type].list.forEach(function (item) {
		const option = document.createElement('option')
		option.value = item.number || item.code
		option.innerText = item.name

		select.appendChild(option)
	})
}

function resetFilterOption(name) {
	document.getElementById(`${name}-filter`).value = 'all'
}

function disableFilterOption(name) {
	document.getElementById(`${name}-filter`).disabled = true;
	document.getElementById(`${name}-filter-reset`).disabled = true;
}

function enableFilterOption(name) {
	document.getElementById(`${name}-filter`).disabled = false;
	document.getElementById(`${name}-filter-reset`).disabled = false;
}

function filter (type, chunk = 0) {
	const network = document.getElementById('network-filter').value
    const country = document.getElementById('country-filter').value

	if (type === 'recentBans') {
		var data = getRecentBans()

	} else {
		var data = botData[type]['list'];
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

	createTable(filtered, type, chunk);
}

function getIpDetails(address) {
	for (var i = 0; i < botData.ip.list.length; i++) {
		if (botData.ip.list[i].address === address) {
			return botData.ip.list[i];
		}
	}
}

function getNetworkDetails(number) {
	for (var i = 0; i < botData.network.list.length; i++) {
		if (botData.network.list[i].number.toString() === number.toString()) {
			return botData.network.list[i];
		}
	}
}

function getCountryDetails(code) {
	for (var i = 0; i < botData.country.list.length; i++) {
		if (botData.country.list[i].code === code) {
			return botData.country.list[i];
		}
	}
}

function getRecentBans() {
	var events = []

	botData.ip.list.forEach(ip => {
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

function displayGlobalStats() {
	document.getElementById('total-bans').innerText = formatNumber(botData.stats.bans.total);
	document.getElementById('bans-today').innerText = formatNumber(botData.stats.bans.today);
	document.getElementById('bans-yesterday').innerText = formatNumber(botData.stats.bans.yesterday);
	document.getElementById('bans-per-day').innerText = formatNumber(botData.stats.bans.perDay);
	document.getElementById('total-days').innerText = formatNumber(botData.stats.totals.date);
	document.getElementById('total-ips').innerText = formatNumber(botData.stats.totals.ip);
	document.getElementById('total-networks').innerText = formatNumber(botData.stats.totals.network);
	document.getElementById('total-countries').innerText = formatNumber(botData.stats.totals.country);
	document.getElementById('global-stats').classList.remove('hide')
}

function displayMostBanned() {
	var ip = getIpDetails(botData.ip.mostBanned)
	var network = getNetworkDetails(botData.network.mostBanned)
	var country = getCountryDetails(botData.country.mostBanned)

	document.getElementById('most-banned-ip').innerText = ip.address;
	document.getElementById('most-banned-ip-count').innerText = formatNumber(ip.bans);
	document.getElementById('most-banned-network').innerText = network.name;
	document.getElementById('most-banned-network').setAttribute('title', network.name);
	document.getElementById('most-banned-network-count').innerText = formatNumber(network.bans);
	document.getElementById('most-banned-country').innerText = country.name;
	document.getElementById('most-banned-country').setAttribute('title', country.name);
	document.getElementById('most-banned-country-count').innerText = formatNumber(country.bans);
	document.getElementById('most-banned').classList.remove('hide')
}

function createModalInfoBox(label, value) {
	var box = document.createElement('box')
	var span = document.createElement('span')
	var div = document.createElement('div')

	span.innerText = label
	div.innerText = value
	div.classList.add(['big'])

	box.appendChild(span)
	box.appendChild(div)
	box.classList.add('box')

	return box;
}

function createBanEventTable(events) {
	var table = new Table('modal-ip');

	var header = new Row()
	header.addCell(new Cell('#', 'number'))

	tableHeaders.events.forEach(function (text) {
		header.addCell(new Cell(text))
	});

	table.addHeader(header)

	events.forEach(function (item, index) {
		var row = new Row()
		row.addCell(new Cell(formatNumber(index + 1), 'number'))

		for (var [key, value] of Object.entries(item)) {
			row.addCell(new Cell(value))
		}

		table.addRow(row)
	})

	return table.get()
}

function createNetworkModalTable(network, view) {
	var table = new Table('modal-network-ip');

	var header = new Row()
	header.addCell(new Cell('#', 'number'))
	header.addCell(new Cell('IP address'))
	header.addCell(new Cell('Bans'))
	header.addCell(new Cell('Country'))
	header.addCell(new Cell(''))
	table.addHeader(header)

	if (view === 'ips') {
		network.ipList.forEach(function(address, index) {
			var ip = getIpDetails(address)
			var country = getCountryDetails(ip.country)

			var row = new Row()
			row.addCell(new Cell(formatNumber(index + 1), 'number'))
			row.addCell(new Cell(address, 'ip'))
			row.addCell(new Cell(formatNumber(ip.bans), 'ban'))
			row.addCell(new Cell(country.name, 'country'))
			row.addCell(new Cell(
				createDetailsButton('ip', address),
				'',
				true
			))

			table.addRow(row);
		})
	} 

	return table.get()
}

function createIpModal(address) {
	var ip = getIpDetails(address)
	var country = getCountryDetails(ip.country)
	var network = getNetworkDetails(ip.network)

	var modalBody = document.getElementById('modal-body')
	var modalTitle = document.getElementById('modal-title')

	var info = document.createElement('div')
	info.classList.add('info')

	info.appendChild(createModalInfoBox('IP Address', address))
	info.appendChild(createModalInfoBox('Network', network.name))
	info.appendChild(createModalInfoBox('Country', country.name))
	info.appendChild(createModalInfoBox('Bans', formatNumber(ip.bans)))

	modalBody.appendChild(info);
	modalTitle.innerText = 'IP Address Details'

	modalBody.appendChild(createBanEventTable(ip.events))
}

function createNetworkModal(number) {
	var network = getNetworkDetails(number)

	var modalBody = document.getElementById('modal-body')
	var modalTitle = document.getElementById('modal-title')

	var info = document.createElement('div')
	info.classList.add('info')

	info.appendChild(createModalInfoBox('Network', network.name))
	info.appendChild(createModalInfoBox('IPs', formatNumber(network.ipCount)))
	info.appendChild(createModalInfoBox('Bans', formatNumber(network.bans)))

	modalBody.appendChild(info);
	modalTitle.innerText = 'Network Details'

	modalBody.appendChild(createNetworkModalTable(network, 'ips'))
}

function createCountryModal(code) {
	var country = getCountryDetails(code)

	var modalBody = document.getElementById('modal-body')
	var modalTitle = document.getElementById('modal-title')

	var info = document.createElement('div')
	info.classList.add('info')

	info.appendChild(createModalInfoBox('Country', country.name))
	info.appendChild(createModalInfoBox('IPs', formatNumber(country.ipCount)))
	info.appendChild(createModalInfoBox('Bans', formatNumber(country.bans)))

	modalBody.appendChild(info);
	modalTitle.innerText = 'Country Details'
}

function createCellWithFilter(dataType, dataValue, text) {
	var span = document.createElement('span')
	span.innerText = text
	span.setAttribute('title', text);

	var button = document.createElement('button')
	button.innerText = 'Filter'
	button.classList.add('row-filter')
	button.setAttribute('data-type' , dataType)
	button.setAttribute('data-value' , dataValue)

	span.append(button)

	return span;
}

function createDetailsButton(dataType, dataValue){
	var button = document.createElement('button')
	button.innerText = 'View details'
	button.classList.add('details');
	button.setAttribute('data-type' , dataType);
	button.setAttribute('data-value' , dataValue);

	return button;
}

function createDetailButtonEvents() {
	var buttons = document.getElementsByClassName('details')

	for (var i = 0; i < buttons.length; i++) {
		buttons[i].addEventListener('click', function (e) {
			var dataType = e.target.getAttribute('data-type')

			if (dataType === 'ip') {
				createIpModal(e.target.getAttribute('data-value'))
			}

			if (dataType === 'network') {
				createNetworkModal(e.target.getAttribute('data-value'))
			}

			if (dataType === 'country') {
				createCountryModal(e.target.getAttribute('data-value'))
			}

			document.getElementById('modal').classList.toggle('hide')
		})
	}
}

function createFilerButtonEvents() {
	var buttons = document.getElementsByClassName('row-filter')

	for (var i = 0; i < buttons.length; i++) {
		buttons[i].addEventListener('click', function (e) {
			var type = e.target.getAttribute('data-type')
			var value = e.target.getAttribute('data-value')

			document.querySelector(`#${type}-filter [value="${value}"]`).selected = true;
			
			filter(document.getElementById('data-filter').value)
		})
	}
}

function updatePageButton(id, chunk) {
	var button = document.getElementById(id)
	button.setAttribute('data-chunk', chunk);
}

function enablePageButton(id) {
	document.getElementById(id).disabled = false
}

function disablePageButton(id) {
	document.getElementById(id).disabled = true
}

function createPageButtons(chunkCount, current) {
	var prev = null;
	var next = null;
	var last = chunkCount;

	console.log(chunkCount)

	if (chunkCount > 0) {
		updatePageButton('load-last-page', last)

		prev = current - 1;
		next = current + 1;

		if (prev >= 0) {
			updatePageButton('load-prev-page', prev)
			enablePageButton('load-first-page')
			enablePageButton('load-prev-page')
		} else {
			disablePageButton('load-first-page')
			disablePageButton('load-prev-page')
		}

		if (next < last || next === last) {
			updatePageButton('load-next-page', next)
			updatePageButton('load-last-page', last)
			enablePageButton('load-next-page')
			enablePageButton('load-last-page')
		} else {
			disablePageButton('load-next-page')
			disablePageButton('load-last-page')
		}
	} else {
		disablePageButton('load-first-page')
		disablePageButton('load-prev-page')
		disablePageButton('load-next-page')
		disablePageButton('load-last-page')
	}

	var paginationCount = document.getElementById('pagination-count');
	paginationCount.innerText = `Page ${current + 1} of ${chunkCount + 1}`
}

function createTable(data, type, chunk) {
	var div = document.getElementById('data-table');
	
	var table = new Table();
	var header = new Row()
	header.addCell(new Cell('#', 'number'))

	tableHeaders[type].forEach(function (text) {
		header.addCell(new Cell(text))
	});

	table.addHeader(header)

	let chunkSize = 25;
	var dataChunks = [];
	for (let i = 0; i < data.length; i += chunkSize) {
		dataChunks.push(data.slice(i, i + chunkSize));
	}

	var chunkCount = dataChunks.length - 1

	dataChunks[chunk].forEach(function (item, index) {
		var row = new Row()

		var itemNumber = index + 1;
		if (chunk >= 1) {
			itemNumber = (chunk * chunkSize) + index + 1;
		}

		row.addCell(new Cell(formatNumber(itemNumber), 'number'))

		if (type === 'ip') {
			var network = getNetworkDetails(item.network)
			var country = getCountryDetails(item.country)

			row.addCell(new Cell(item.address))
			row.addCell(new Cell(formatNumber(item.bans)))
			row.addCell(new Cell(
				createCellWithFilter('network', network.number, network.name),
				'asn',
				true
			))
			row.addCell(new Cell(
				createCellWithFilter('country', country.code, country.name),
				'country',
				true
			))
			row.addCell(new Cell(
				createDetailsButton(type, item.address),
				'button',
				true
			))
		}

		if (type === 'network' || type === 'jail') {
			var cssClass = null;

			if (type === 'network') {
				cssClass = 'asn';
			}

			row.addCell(new Cell(item.name, cssClass))
			row.addCell(new Cell(formatNumber(item.ipCount)))
			row.addCell(new Cell(formatNumber(item.bans)))
			row.addCell(new Cell(
				createDetailsButton(type, item.number),
				'button',
				true
			))
		}

		if (type === 'country') {
			row.addCell(new Cell(item.name, 'asn'))
			row.addCell(new Cell(formatNumber(item.ipCount)))
			row.addCell(new Cell(formatNumber(item.bans)))
			row.addCell(new Cell(
				createDetailsButton(type, item.code),
				'button',
				true
			))
		}

		if (type === 'recentBans') {
			var network = getNetworkDetails(item.network)
			var country = getCountryDetails(item.country)

			row.addCell(new Cell(item.timestamp))
			row.addCell(new Cell(item.jail))
			row.addCell(new Cell(item.address))

			row.addCell(new Cell(
				createCellWithFilter('network', network.number, network.name),
				'asn',
				true
			))
			row.addCell(new Cell(
				createCellWithFilter('country', country.code, country.name),
				'country',
				true
			))
		}
	
		if (type === 'date') {
			row.addCell(new Cell(item.date))
			row.addCell(new Cell(formatNumber(item.ipCount)))
			row.addCell(new Cell(formatNumber(item.bans)))
			row.addCell(new Cell(
				createDetailsButton(type, item.date),
				'button',
				true
			))
		}

		table.addRow(row);
	});

	div.innerText = '';
	div.append(table.get());

	createPageButtons(chunkCount, chunk);

	createDetailButtonEvents()
	createFilerButtonEvents();
}

document.getElementById('data-filter').addEventListener('change', function(e) {
	resetFilterOption('network')
	resetFilterOption('country')

	filter(e.target.value)

	if (e.target.value === 'ip' || e.target.value === 'recentBans') {
		enableFilterOption('network')
		enableFilterOption('country')
	} else {
		disableFilterOption('network')
		disableFilterOption('country')
	}
});

document.getElementById('modal-close').addEventListener('click', function (e) {
	document.getElementById('modal').classList.toggle('hide')
	document.getElementById('modal-body').innerText = ''
	document.getElementById('modal-title').innerText = ''
})

document.getElementById('network-filter').addEventListener('change', function(e) {
	filter(document.getElementById('data-filter').value)
});

document.getElementById('country-filter').addEventListener('change', function(e) {
	filter(document.getElementById('data-filter').value)
});

document.getElementById('network-filter-reset').addEventListener('click', function (e) {
	resetFilterOption('network')
	filter(document.getElementById('data-filter').value)
})

document.getElementById('country-filter-reset').addEventListener('click', function (e) {
	resetFilterOption('country')
	filter(document.getElementById('data-filter').value)
})

var pageButtons = document.getElementsByClassName('page-button')
for (var i = 0; i < pageButtons.length; i++) {
	pageButtons[i].addEventListener('click', function (e) {
		filter(
			document.getElementById('data-filter').value,
			Number(e.target.getAttribute('data-chunk'))
		)
	})
}

fetchData()
.then(response => {
	return response.json();
}).then(data => {
	botData = data

	document.getElementById('last-updated').innerText = botData.updated
	document.getElementById('loading').classList.add('hide')
	document.getElementById('options').classList.remove('hide')
	document.getElementById('data').classList.remove('hide')

	displayGlobalStats()
	displayMostBanned()

	setFilterOptions('network')
	setFilterOptions('country')

	filter('recentBans')
	enableFilterOption('network')
	enableFilterOption('country')
}).catch(error => {
	console.log(error);
})
