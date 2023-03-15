import { Table } from './table.js'
import { Row } from './table.js'
import { Cell } from './table.js'

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

function createNetworkFilterOptions() {
	const select = document.getElementById('network-filter')
	select.innerText = ''

	const option = document.createElement('option')
	option.value = 'all'
	option.innerText = 'All networks'
	select.appendChild(option)

	botData.network.list.forEach(function (item) {
		const option = document.createElement('option');
		option.value = item.number
		option.innerText = item.name

		select.appendChild(option)
	})
}

function createCountryFilterOptions() {
	const select = document.getElementById('country-filter')
	select.innerText = ''

	const option = document.createElement('option')
	option.value = 'all'
	option.innerText = 'All countries'
	select.appendChild(option)

	botData.country.list.forEach(function (item) {
		const option = document.createElement('option');
		option.value = item.code
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

function filter (type) {
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

	createTable(filtered, type);
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
	document.getElementById('total-days').innerText = formatNumber(botData.date.list.length);
	document.getElementById('total-ips').innerText = formatNumber(botData.ip.list.length);
	document.getElementById('total-networks').innerText = formatNumber(botData.network.list.length);
	document.getElementById('total-countries').innerText = formatNumber(botData.country.list.length);
	document.getElementById('global-stats').classList.remove('hide')
}

function displayMostBanned() {
	var ip = getIpDetails(botData.ip.mostBanned)
	var network = getNetworkDetails(botData.network.mostBanned)
	var country = getCountryDetails(botData.country.mostBanned)

	document.getElementById('most-banned-ip').innerText = ip.address;
	document.getElementById('most-banned-ip-count').innerText = formatNumber(ip.bans);
	document.getElementById('most-banned-network').innerText = network.name;
	document.getElementById('most-banned-network-count').innerText = formatNumber(network.bans);
	document.getElementById('most-banned-country').innerText = country.name;
	document.getElementById('most-banned-country-count').innerText = formatNumber(country.bans);
	document.getElementById('most-banned').classList.remove('hide')
}

function createModalInfoBox(label, value) {
	var box = document.createElement('box')
	var span = document.createElement('span')
	var h3 = document.createElement('h3')

	span.innerText = label;
	h3.innerText = value;

	box.appendChild(span)
	box.appendChild(h3)
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

function createDetailsButton(dataType, dataValue) {
	var button = document.createElement('button')
	button.innerText = 'View details'
	button.classList.add('details');
	button.setAttribute('data-type' , dataType);
	button.setAttribute('data-value' , dataValue);

	return button;
}

function createTable(data, type) {
	var box = document.getElementById('data');
	
	var table = new Table();
	var header = new Row()
	header.addCell(new Cell('#', 'number'))

	tableHeaders[type].forEach(function (text) {
		header.addCell(new Cell(text))
	});

	table.addHeader(header)

	data.forEach(function (item, index) {
		var row = new Row()
		row.addCell(new Cell(formatNumber(index + 1), 'number'))

		if (type === 'ip') {
			var network = getNetworkDetails(item.network)
			var country = getCountryDetails(item.country)

			row.addCell(new Cell(item.address))
			row.addCell(new Cell(formatNumber(item.bans)))
			row.addCell(new Cell(network.name, 'asn'))
			row.addCell(new Cell(`${country.name} (${country.code})`))
			row.addCell(new Cell(
				createDetailsButton(type, item.address),
				'button',
				true
			))
		}

		if (type === 'network' || type === 'jail') {
			var country = getCountryDetails(item.country)

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
			row.addCell(new Cell(network.name, 'asn'))
			row.addCell(new Cell(country.name))
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

	box.innerText = '';
	box.append(table.get());

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

fetchData()
.then(response => {
	return response.json();
}).then(data => {
	botData = data

	document.getElementById('last-updated').innerText = botData.updated

	displayGlobalStats()
	displayMostBanned()

	createNetworkFilterOptions()
	createCountryFilterOptions()
}).catch(error => {
	console.log(error);
})
