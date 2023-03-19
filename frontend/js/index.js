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
	'address': ['Address', 'Bans', 'Network', 'Country' , ''],
	'jail': ['Jail', 'IPs', '', 'Bans', ''],
	'network': ['Network', 'IPs', '', 'Bans', ''],
	'country': ['Country', 'IPs', '', 'Bans', ''],
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
	var span = document.createElement('span')

	span.innerText = text
	span.setAttribute('title', text);

	if (filter.hasFilter(dataType, dataValue) === false) {
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

function createViewButton(viewType, filterType, filterValue) {
	var button = document.createElement('button')

	if (viewType === 'address') {
		button.innerText = 'View IPs'
	} else {
		button.innerText = 'View Bans'
	}

	button.classList.add('view');
	button.setAttribute('data-view-type' , viewType);
	button.setAttribute('data-filter-type' , filterType);
	button.setAttribute('data-filter-value' , filterValue);

	return button;
}

function createViewButtonEvents() {
	var buttons = document.getElementsByClassName('details')

	for (var i = 0; i < buttons.length; i++) {
		buttons[i].addEventListener('click', function (e) {
			var dataType = e.target.getAttribute('data-type')

			if (dataType === 'address') {
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

	var buttons = document.getElementsByClassName('view')

	for (var i = 0; i < buttons.length; i++) {
		buttons[i].addEventListener('click', function (e) {
			document.getElementById('data-view-type').value = e.target.getAttribute('data-view-type')

			console.log(e.target)

			filter.add(
				e.target.getAttribute('data-filter-type'),
				'include',
				e.target.getAttribute('data-filter-value')
			)

			document.getElementById('applied-filters').classList.remove('hide')

			var viewType = document.getElementById('data-view-type').value
			var data = filter.getData(viewType)
		
			displayData(data, viewType)
			createFilerRemoveEvents()
		})
	}
}

function createFilerButtonEvents() {
	var buttons = document.getElementsByClassName('row-filter')

	for (var i = 0; i < buttons.length; i++) {
		buttons[i].addEventListener('click', function (e) {
			filter.hidePanel()
			filter.add(
				e.target.getAttribute('data-type'),
				'include',
				e.target.getAttribute('data-value')
			)

			document.getElementById('applied-filters').classList.remove('hide')

			var viewType = document.getElementById('data-view-type').value
			var data = filter.getData(viewType)
		
			displayData(data, viewType)
			createFilerRemoveEvents()
		})
	}
}

function createFilerRemoveEvents() {
	var filterRemoveButtons = document.querySelectorAll(`button[data-filter-id]`);
	for (var i = 0; i < filterRemoveButtons.length; i++) {
		filterRemoveButtons[i].addEventListener('click', function (e) {
			filter.removeValue(
				e.target.getAttribute('data-filter-id'),
				e.target.getAttribute('data-filter-value')
			)

			e.target.parentElement.remove();

			var viewType = document.getElementById('data-view-type').value
			var data = filter.getData(viewType)

			displayData(data, viewType)
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

function createTable(data = [], type, indexStart = 0) {
	var div = document.getElementById('data-table');
	
	var table = new Table();
	var header = new Row()
	header.addCell(new Cell('#', 'number'))

	tableHeaders[type].forEach(function (text) {
		header.addCell(new Cell(text))
	});

	table.addHeader(header)

	if (data.length > 0) {
		data.forEach(function (item, index) {
			var row = new Row()
			var itemNumber = index + indexStart;
	
			row.addCell(new Cell(Format.Number(itemNumber), 'number'))
	
			if (type === 'address') {
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
	
			if (type === 'network' || type === 'jail' || type === 'country') {
				var span = document.createElement('span')
				span.innerText = item.name
				span.setAttribute('title', item.name)
				row.addCell(new Cell(
					span,
					'long',
					true
				))
				row.addCell(new Cell(Format.Number(item.ipCount)))
				row.addCell(new Cell(
					createViewButton('address', type, item.number || item.code || item.name),
					'view-btn',
					true
				))
				row.addCell(new Cell(Format.Number(item.bans)))
				row.addCell(new Cell(
					createViewButton('recentBans', type, item.number || item.code || item.name),
					'view-btn',
					true
				))
			}

			if (type === 'recentBans') {
				var network = details.getNetwork(item.network)
				var country = details.getCountry(item.country)
	
				row.addCell(new Cell(item.timestamp, 'date'))
				row.addCell(new Cell(
					createCellWithFilter('address', item.address, item.address),
					'address',
					true
				))
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
	} else {
		var row = new Row()
		row.addCell(new Cell('No data found', 'no-data', false, 6))

		table.addRow(row);
	}

	div.innerText = '';
	div.append(table.get());

	createViewButtonEvents()
	createFilerButtonEvents();
}

function errorMessage(message) {
	document.getElementById('loading').classList.add('hide')

	var error = document.getElementById('error')
	error.classList.remove('hide')
	error.innerText = message
} 

document.getElementById('modal-close').addEventListener('click', function (e) {
	document.getElementById('modal').classList.toggle('hide')
	document.getElementById('modal-body').innerText = ''
	document.getElementById('modal-title').innerText = ''
})

document.getElementById('data-view-type').addEventListener('change', function(e) {
	filter.hidePanel()
	filter.resetPanel()

	var type = e.target.value
	var data = filter.getData(type)

	if (type === 'address' || type === 'recentBans') {
		document.getElementById('open-filter-panel').disabled = false

		if (type === 'address') {
			filter.disableOption('jail')
		} else {
			filter.enableOption('jail')
		}
	} else {
		document.getElementById('open-filter-panel').disabled = true
		filter.reset()
	}

	displayData(data, type)
});

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

	filter.setOptions('address')

	displayData(
		filter.getData('recentBans'),
		'recentBans'
	)

	document.getElementById('open-filter-panel').addEventListener('click', function (e) {
		document.getElementById('open-filter-panel').disabled = true

		filter.showPanel()
		filter.resetPanel()
	})
	
	document.getElementById('close-filter-panel').addEventListener('click', function (e) {
		document.getElementById('open-filter-panel').disabled = false

		filter.hidePanel()
		filter.resetPanel()
	})
	
	document.getElementById('filter-type').addEventListener('change', function(e) {
		filter.setOptions(e.target.value)
	});

	document.getElementById('filter-apply').addEventListener('click', function (e) {
		document.getElementById('open-filter-panel').disabled = false
		document.getElementById('applied-filters').classList.remove('hide')

		filter.hidePanel()
		filter.add(
			document.getElementById(`filter-type`).value,
			document.getElementById(`filter-action`).value,
			document.getElementById(`filter-value`).value
		)

		var viewType = document.getElementById('data-view-type').value
		var data = filter.getData(viewType)
	
		displayData(data, viewType)
		createFilerRemoveEvents()
	})
}).catch(error => {
	errorMessage(error.message)
	console.log(error)
})
