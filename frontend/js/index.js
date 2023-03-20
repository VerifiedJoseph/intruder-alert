import { Table, Row, Cell } from './Table.js'
import { Filter } from './Filter.js'
import { Details } from './Details.js'
import { Display } from './Display.js'
import { Format } from './Format.js'
import { Pagination } from './Pagination.js'
import { Message } from './Message.js'

"use strict";

var filter
var details
var display

var botData = {}
var tableHeaders = {
	'address': ['Address', 'Network', 'Country', 'Bans', ''],
	'jail': ['Jail', 'IPs', 'Bans', ''],
	'network': ['Network', 'IPs', 'Bans', ''],
	'country': ['Country', 'IPs', 'Bans', ''],
	'events': ['Date', 'Jail'],
	'recentBans': ['Date', 'Address', 'Jail', 'Network', 'Country'],
	'date': ['Date', 'IPs', 'Bans', '']
}

function fetchData() {
	return fetch('../backend/data.php');
}

function displayData(data, type, pageNumber = 0) {
	var pagination = new Pagination(data)
	pagination.setPage(pageNumber)

	createTable(
		pagination.getData(),
		type,
		pagination.getIndexStart()
	);
	
	pagination.setButtons()
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
	var buttons = document.getElementsByClassName('view')

	for (var i = 0; i < buttons.length; i++) {
		var filterType =  buttons[i].getAttribute('data-filter-type')
		var filterValue = buttons[i].getAttribute('data-filter-value')

		if (filter.hasFilter(filterType, filterValue) === true) {
			buttons[i].disabled = true;
		} else {
			buttons[i].disabled = false;
		}

		if (buttons[i].getAttribute('data-event') !== 'true') {
			buttons[i].addEventListener('click', function (e) {
				document.getElementById('data-view-type').value = e.target.getAttribute('data-view-type')
	
				var filterType =  e.target.getAttribute('data-filter-type')
				var filterValue = e.target.getAttribute('data-filter-value')
	
				if (filter.hasFilter(filterType, filterValue) === false) {
					filter.add(filterType, 'include', filterValue)
		
					document.getElementById('applied-filters').classList.remove('hide')
					document.getElementById('open-filter-panel').disabled = false
		
					var viewType = document.getElementById('data-view-type').value
					var data = filter.getData(viewType)
				
					displayData(data, viewType)
					createFilerRemoveEvents()
				} else {
					Message.error('A filter already exists for this.', true)
				}
			})

			buttons[i].setAttribute('data-event', 'true')
		}
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
	var buttons = document.querySelectorAll(`button[data-filter-id]`);
	
	for (var i = 0; i < buttons.length; i++){
		if (buttons[i].getAttribute('data-event') !== 'true') {
			buttons[i].addEventListener('click', function (e) {
				filter.removeValue(
					e.target.getAttribute('data-filter-id'),
					e.target.getAttribute('data-filter-value')
				)
	
				e.target.parentElement.remove();
	
				var viewType = document.getElementById('data-view-type').value
				var data = filter.getData(viewType)
	
				displayData(data, viewType)
			})

			buttons[i].setAttribute('data-event', 'true')
		}
	}
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
				row.addCell(new Cell(Format.Number(item.bans)))
				row.addCell(new Cell(
					createViewButton('recentBans', type, item.address),
					'view-bans-btn',
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
				row.addCell(new Cell(Format.Number(item.bans)))

				var span = document.createElement('span')
				span.appendChild(
					createViewButton('address', type, item.number || item.code || item.name)
				)
				span.appendChild(
					createViewButton('recentBans', type, item.number || item.code || item.name),
				)

				row.addCell(new Cell(
					span,
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
					createViewButton('recentBans', type, item.date),
					'view-bans-btn',
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

function createMostBannedButtons() {
	document.getElementById('most-banned-ip-button').appendChild(
		createViewButton('recentBans', 'address', botData.address.mostBanned)
	)

	document.getElementById('most-banned-network-button').appendChild(
		createViewButton('recentBans', 'network', botData.network.mostBanned)
	)

	document.getElementById('most-banned-country-button').appendChild(
		createViewButton('recentBans', 'country', botData.country.mostBanned)
	)
}

document.getElementById('data-view-type').addEventListener('change', function(e) {
	filter.hidePanel()
	filter.resetPanel()

	var type = e.target.value

	if (type === 'address' || type === 'recentBans') {
		document.getElementById('open-filter-panel').disabled = false

		if (type === 'address') {
			filter.disableOption('jail')
			filter.disableOption('date')
			filter.remove('date')
		} else {
			filter.enableOption('jail')
			filter.enableOption('date')
		}
	} else {
		document.getElementById('open-filter-panel').disabled = true
		filter.reset()
	}

	displayData(
		filter.getData(type),
		type
	)
});

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

var pageButtons = document.getElementsByClassName('page-button')
for (var i = 0; i < pageButtons.length; i++) {
	pageButtons[i].addEventListener('click', function (e) {
		var type = document.getElementById('data-view-type').value
		var data = filter.getData(type)
		var page = Number(e.target.getAttribute('data-page'))

		displayData(data, type, page)
	})
}

fetchData()
.then(response => {
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
	display = new Display(data)

	document.getElementById('loading').classList.add('hide')
	document.getElementById('options').classList.remove('hide')
	document.getElementById('data').classList.remove('hide')

	display.headerDates()
	display.globalStats()
	display.mostBanned()
	createMostBannedButtons()

	filter.setOptions('address')

	displayData(
		filter.getData('recentBans'),
		'recentBans'
	)
}).catch(error => {
	document.getElementById('loading').classList.add('hide')

	console.log(error)
	Message.error(error.message)
})
