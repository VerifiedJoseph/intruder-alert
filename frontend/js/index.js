import { Table, Row, Cell } from './table.js'
import { Filter } from './Filter.js'

"use strict";

var filter

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

function displayData(data, type, page = 0) {
	var totalItems = data.length;

	let pageSize = 25;
	var dataPages = [];
	for (let i = 0; i < data.length; i += pageSize) {
		dataPages.push(data.slice(i, i + pageSize));
	}

	var pageCount = dataPages.length - 1

	var indexStart = 1;
	if (page >= 1) {
		indexStart = (page * pageSize) + 1;
	}

	createTable(dataPages[page], type, indexStart);
	createPageButtons(pageCount, totalItems, page);
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

function displayError(message) {
	document.getElementById('loading').classList.add('hide')

	var error = document.getElementById('error')
	error.classList.remove('hide')
	error.innerText = message
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
	var cell = document.createElement('div')
	var span = document.createElement('span')
	var div = document.createElement('div')

	div.classList.add('small')
	cell.classList.add('cell')

	span.innerText = label
	div.innerText = value
	div.setAttribute('title', value)

	cell.appendChild(span)
	cell.appendChild(div)

	return cell;
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
	info.classList.add('row')

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
	info.classList.add('row')

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
	info.classList.add('row')

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
			
			filter.getData(document.getElementById('data-filter').value)
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

function createPageButtons(pageCount, totalItems, currentPage) {
	var prev = null;
	var next = null;
	var last = pageCount;

	if (pageCount > 0) {
		updatePageButton('load-last-page', last)

		prev = currentPage - 1;
		next = currentPage + 1;

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
	paginationCount.innerText = `Page ${currentPage + 1} of ${pageCount + 1} (${formatNumber(totalItems)} total items)`
}

function createTable(data, type, indexStart = 0) {
	var div = document.getElementById('data-table');
	
	var table = new Table();
	var header = new Row()
	header.addCell(new Cell('#', 'number'))

	tableHeaders[type].forEach(function (text) {
		header.addCell(new Cell(text))
	});

	table.addHeader(header)

	data.forEach(function (item, index) {
		var row = new Row()
		var itemNumber = index + indexStart;

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

	createDetailButtonEvents()
	createFilerButtonEvents();
}

document.getElementById('data-filter').addEventListener('change', function(e) {
	resetFilterOption('network')
	resetFilterOption('country')

	var type = e.target.value
	var data = filter.getData(type)

	if (type === 'ip' || type === 'recentBans') {
		enableFilterOption('network')
		enableFilterOption('country')
	} else {
		disableFilterOption('network')
		disableFilterOption('country')
	}

	displayData(data, type)
});

document.getElementById('modal-close').addEventListener('click', function (e) {
	document.getElementById('modal').classList.toggle('hide')
	document.getElementById('modal-body').innerText = ''
	document.getElementById('modal-title').innerText = ''
})

document.getElementById('network-filter').addEventListener('change', function(e) {
	var type = document.getElementById('data-filter').value
	var data = filter.getData(type)

	displayData(data, type)
});

document.getElementById('country-filter').addEventListener('change', function(e) {
	var type = document.getElementById('data-filter').value
	var data = filter.getData(type)

	displayData(data, type)
});

document.getElementById('network-filter-reset').addEventListener('click', function (e) {	
	resetFilterOption('network')
	
	var type = document.getElementById('data-filter').value
	var data = filter.getData(type)

	displayData(data, type)
})

document.getElementById('country-filter-reset').addEventListener('click', function (e) {
	resetFilterOption('country')

	var type = document.getElementById('data-filter').value
	var data = filter.getData(type)

	displayData(data, type)
})

var pageButtons = document.getElementsByClassName('page-button')
for (var i = 0; i < pageButtons.length; i++) {
	pageButtons[i].addEventListener('click', function (e) {
		var type = document.getElementById('data-filter').value
		var data = filter.getData(type)
		var page = Number(e.target.getAttribute('data-chunk'))

		displayData(data, type, page)
	})
}

fetchData()
.then(response => {
	console.log(response);

	if (response.status !== 200) {
		throw new Error(`Failed to fetch data (${response.status} ${response.statusText})`);
	}

	return response.json();
}).then(data => {
	botData = data

	filter = new Filter(data)

	document.getElementById('last-updated').innerText = botData.updated
	document.getElementById('loading').classList.add('hide')
	document.getElementById('options').classList.remove('hide')
	document.getElementById('data').classList.remove('hide')

	displayGlobalStats()
	displayMostBanned()

	setFilterOptions('network')
	setFilterOptions('country')

	displayData(
		filter.getData('recentBans'),
		'recentBans'
	)

	enableFilterOption('network')
	enableFilterOption('country')
}).catch(error => {
	displayError(error.message)
	console.log(error);
})
