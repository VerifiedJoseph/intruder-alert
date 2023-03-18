import { Table, Row, Cell } from './table.js'
import { Filter } from './Filter.js'
import { Details } from './Details.js'
import { Display } from './Display.js'
import { Format } from './Format.js'

"use strict";

var filter
var details
var display

var botData = {}
var tableHeaders = {
	'ip': ['Address', 'Bans', 'Network', 'Country' , ''],
	'jail': ['Jail', 'IPs', 'Bans', ''],
	'network': ['Network', 'IPs', 'Bans', ''],
	'country': ['Country', 'IPs', 'Bans', ''],
	'events': ['Date', 'Jail'],
	'recentBans': ['Date', 'Address', 'Jail', 'Network', 'Country'],
	'date': ['Date', 'IPs', 'Bans', '']
}

function fetchData() {
	return fetch('data.json');
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
		row.addCell(new Cell(Format.Number(index + 1), 'number'))

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
			var ip = details.getIp(address)
			var country = details.getCountry(ip.country)

			var row = new Row()
			row.addCell(new Cell(Format.Number(index + 1), 'number'))
			row.addCell(new Cell(address, 'ip'))
			row.addCell(new Cell(Format.Number(ip.bans), 'ban'))
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
	var ip = details.getIp(address)
	var country = details.getCountry(ip.country)
	var network = details.getNetwork(ip.network)

	var modalBody = document.getElementById('modal-body')
	var modalTitle = document.getElementById('modal-title')

	var info = document.createElement('div')
	info.classList.add('row')

	info.appendChild(createModalInfoBox('IP Address', address))
	info.appendChild(createModalInfoBox('Network', network.name))
	info.appendChild(createModalInfoBox('Country', country.name))
	info.appendChild(createModalInfoBox('Bans', Format.Number(ip.bans)))

	modalBody.appendChild(info);
	modalTitle.innerText = 'IP Address Details'

	modalBody.appendChild(createBanEventTable(ip.events))
}

function createNetworkModal(number) {
	var network = details.getNetwork(number)

	var modalBody = document.getElementById('modal-body')
	var modalTitle = document.getElementById('modal-title')

	var info = document.createElement('div')
	info.classList.add('row')

	info.appendChild(createModalInfoBox('Network', network.name))
	info.appendChild(createModalInfoBox('IPs', Format.Number(network.ipCount)))
	info.appendChild(createModalInfoBox('Bans', Format.Number(network.bans)))

	modalBody.appendChild(info);
	modalTitle.innerText = 'Network Details'

	modalBody.appendChild(createNetworkModalTable(network, 'ips'))
}

function createCountryModal(code) {
	var country = details.getCountry(code)

	var modalBody = document.getElementById('modal-body')
	var modalTitle = document.getElementById('modal-title')

	var info = document.createElement('div')
	info.classList.add('row')

	info.appendChild(createModalInfoBox('Country', country.name))
	info.appendChild(createModalInfoBox('IPs', Format.Number(country.ipCount)))
	info.appendChild(createModalInfoBox('Bans', Format.Number(country.bans)))

	modalBody.appendChild(info);
	modalTitle.innerText = 'Country Details'
}

function createCellWithFilter(dataType, dataValue, text) {
	var currentFilterValue = document.getElementById(`${dataType}-filter`).value
	var span = document.createElement('span')

	span.innerText = text
	span.setAttribute('title', text);

	if (currentFilterValue.toString() !== dataValue.toString()) {
		var button = document.createElement('button')
		
		button.innerText = 'Filter'
		button.classList.add('row-filter')
		button.setAttribute('title' , `Filter ${dataType} to ${text}`)
		button.setAttribute('data-type' , dataType)
		button.setAttribute('data-value' , dataValue)
		span.append(button)
	}

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

			displayData(
				filter.getData(document.getElementById('data-view-type').value),
				document.getElementById('data-view-type').value
			)
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
	paginationCount.innerText = `Page ${currentPage + 1} of ${pageCount + 1} (${Format.Number(totalItems)} total items)`
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

		row.addCell(new Cell(Format.Number(itemNumber), 'number'))

		if (type === 'ip') {
			var network = details.getNetwork(item.network)
			var country = details.getCountry(item.country)

			row.addCell(new Cell(item.address))
			row.addCell(new Cell(Format.Number(item.bans)))
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
			var span = document.createElement('span')
			span.innerText = item.name
			span.setAttribute('title', item.name)
			row.addCell(new Cell(
				span,
				'long',
				true
			))
			row.addCell(new Cell(Format.Number(item.ipCount)))
			row.addCell(new Cell(Format.Number(item.bans)))
			row.addCell(new Cell(
				createDetailsButton(type, item.number),
				'button',
				true
			))
		}

		if (type === 'country') {
			row.addCell(new Cell(item.name, 'long'))
			row.addCell(new Cell(Format.Number(item.ipCount)))
			row.addCell(new Cell(Format.Number(item.bans)))
			row.addCell(new Cell(
				createDetailsButton(type, item.code),
				'button',
				true
			))
		}

		if (type === 'recentBans') {
			var network = details.getNetwork(item.network)
			var country = details.getCountry(item.country)

			row.addCell(new Cell(item.timestamp, 'date'))
			row.addCell(new Cell(item.address))
			row.addCell(new Cell(
				createCellWithFilter('jail', item.jail, item.jail),
				'jail',
				true
			))
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
			row.addCell(new Cell(item.date, 'long'))
			row.addCell(new Cell(Format.Number(item.ipCount)))
			row.addCell(new Cell(Format.Number(item.bans)))
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

function errorMessage(message) {
	document.getElementById('loading').classList.add('hide')

	var error = document.getElementById('error')
	error.classList.remove('hide')
	error.innerText = message
} 

document.getElementById('data-view-type').addEventListener('change', function(e) {
	filter.hidePanel()
	filter.resetPanel()

	var type = e.target.value
	var data = filter.getData(type)

	if (type === 'ip' || type === 'recentBans') {
		document.getElementById('open-filter-panel').disabled = false

		if (type === 'ip') {
			filter.disableOption('jail')
		} else {
			filter.enableOption('jail')
		}
	} else {
		document.getElementById('open-filter-panel').disabled = true
	}

	displayData(data, type)
});

document.getElementById('modal-close').addEventListener('click', function (e) {
	document.getElementById('modal').classList.toggle('hide')
	document.getElementById('modal-body').innerText = ''
	document.getElementById('modal-title').innerText = ''
})

document.getElementById('network-filter').addEventListener('change', function(e) {
	var type = document.getElementById('data-view-type').value
	var data = filter.getData(type)

	displayData(data, type)
});

document.getElementById('country-filter').addEventListener('change', function(e) {
	var type = document.getElementById('data-view-type').value
	var data = filter.getData(type)

	displayData(data, type)
});

document.getElementById('jail-filter').addEventListener('change', function(e) {
	var type = document.getElementById('data-view-type').value
	var data = filter.getData(type)

	displayData(data, type)
});

document.getElementById('network-filter-reset').addEventListener('click', function (e) {
	filter.resetOption('network')
	
	var type = document.getElementById('data-view-type').value
	var data = filter.getData(type)

	displayData(data, type)
})

document.getElementById('country-filter-reset').addEventListener('click', function (e) {
	filter.resetOption('country')

	var type = document.getElementById('data-view-type').value
	var data = filter.getData(type)

	displayData(data, type)
})

document.getElementById('jail-filter-reset').addEventListener('click', function (e) {
	filter.resetOption('jail')

	var type = document.getElementById('data-view-type').value
	var data = filter.getData(type)

	displayData(data, type)
})

var pageButtons = document.getElementsByClassName('page-button')
for (var i = 0; i < pageButtons.length; i++) {
	pageButtons[i].addEventListener('click', function (e) {
		var type = document.getElementById('data-view-type').value
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

	if (data.error === true) {
		throw new Error(data.message);
	}

	filter = new Filter(data)
	details = new Details(data)
	display = new Display(data, details)

	document.getElementById('loading').classList.add('hide')
	document.getElementById('options').classList.remove('hide')
	document.getElementById('data').classList.remove('hide')

	display.headerDates()
	display.globalStats()
	display.mostBanned()

	filter.setOptions('ip')

	displayData(
		filter.getData('recentBans'),
		'recentBans'
	)

	document.getElementById('open-filter-panel').addEventListener('click', function (e) {
		filter.showPanel()
		filter.resetPanel()
	})
	
	document.getElementById('close-filter-panel').addEventListener('click', function (e) {
		filter.hidePanel()
		filter.resetPanel()
	})
	
	document.getElementById('filter-type').addEventListener('change', function(e) {
		filter.setOptions(e.target.value)
	});

	document.getElementById('filter-apply').addEventListener('click', function (e) {
		filter.hidePanel()
		filter.save()

		var type = document.getElementById('data-view-type').value
		var data = filter.getData(type)
	
		displayData(data, type)

		document.querySelector(`button[data-filter-id]`).addEventListener('click', function (e) {
			filter.remove(e.target.getAttribute('data-filter-id'))
			e.target.parentElement.remove();

			var type = document.getElementById('data-view-type').value
			var data = filter.getData(type)

			displayData(data, type)
		})
	})
}).catch(error => {
	errorMessage(error.message)
	console.log(error)
})
