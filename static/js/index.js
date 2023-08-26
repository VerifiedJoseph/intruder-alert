'use strict'

import { } from './lib/chart.js'

import { Plot } from './class/Plot.js'
import { Table, Row, Cell } from './class/Table.js'
import { Filter } from './class/Filter.js'
import { FilterPanel } from './class/FilterPanel.js'
import { Details } from './class/Details.js'
import { Display } from './class/Display.js'
import { Format } from './class/Format.js'
import { Pagination } from './class/Pagination.js'
import { Message } from './class/Message.js'

let filterPanel
let filter
let plot
let details
let display

let botData = {}
const tableHeaders = {
  address: ['Address', 'Subnet', 'Network', 'Country', 'Bans', ''],
  jail: ['Jail', 'IPs', 'Bans', ''],
  network: ['Network', 'IPs', 'Bans', ''],
  subnet: ['Subnet', 'Network', 'Country', 'IPs', 'Bans', ''],
  country: ['Country', 'IPs', 'Bans', ''],
  continent: ['Continent', 'IPs', 'Bans', ''],
  events: ['Date', 'Jail'],
  recentBans: ['Date', 'Address', 'Jail', 'Network', 'Country'],
  date: ['Date', 'IPs', 'Bans', '']
}

function fetchData () {
  return fetch('data.php')
}

function orderData (data) {
  if (document.getElementById('data-order-by').disabled === true) {
    return data
  }

  let orderBy = document.getElementById('data-order-by').value
  if (document.getElementById('data-order-by').value === 'ips') {
    orderBy = 'ipCount'
  }

  data.sort(function (a, b) {
    if (orderBy === 'date') {
      return new Date(b.date) - new Date(a.date)
    }

    return b[orderBy] - a[orderBy]
  })

  return data
}

function displayData (data, type, pageNumber = 0) {
  data = orderData(data)

  const pagination = new Pagination(data)
  pagination.setPage(pageNumber)

  createTable(
    pagination.getData(),
    type,
    pagination.getIndexStart()
  )

  pagination.setButtons()
}

function createCellWithFilter (dataType, dataValue, text) {
  const span = document.createElement('span')

  span.innerText = text
  span.setAttribute('title', text)

  if (filter.hasFilter(dataType, dataValue) === false) {
    const button = document.createElement('button')

    button.innerText = 'Filter'
    button.classList.add('row-filter')
    button.setAttribute('title', `Filter ${dataType} to ${text}`)
    button.setAttribute('data-type', dataType)
    button.setAttribute('data-value', dataValue)
    span.append(button)
  }

  return span
}

function createViewButton (viewType, filterType, filterValue, context = 'table') {
  const button = document.createElement('button')
  let text = 'View Bans'

  if (viewType === 'address') {
    text = 'View IPs'
  }

  button.innerText = text
  button.classList.add('view')
  button.setAttribute('data-view-type', viewType)
  button.setAttribute('data-filter-type', filterType)
  button.setAttribute('data-filter-value', filterValue)
  button.setAttribute('data-context', context)

  return button
}

function createViewButtonEvents () {
  const buttons = document.getElementsByClassName('view')

  for (let i = 0; i < buttons.length; i++) {
    if (buttons[i].getAttribute('data-event') !== 'true') {
      buttons[i].addEventListener('click', function (e) {
        location.hash = '#table'

        const filterType = e.target.getAttribute('data-filter-type')
        const filterValue = e.target.getAttribute('data-filter-value')
        const context = e.target.getAttribute('data-context')

        filterPanel.hide()
        if (context === 'most-banned') {
          filter.reset()
        }

        if (e.target.getAttribute('data-view-type') === 'recentBans' && document.getElementById('data-view-type').value === 'address') {
          filter.reset()
        }

        document.getElementById('data-view-type').value = e.target.getAttribute('data-view-type')

        if (filter.hasFilter(filterType, filterValue) === false) {
          filter.add(filterType, 'include', filterValue)

          document.getElementById('applied-filters').classList.remove('hide')
          document.getElementById('open-filter-panel').disabled = false

          const viewType = document.getElementById('data-view-type').value

          displayData(filter.getData(viewType), viewType)
          createFilerRemoveEvents()
        } else {
          Message.error('A filter already exists for this.', true)
        }
      })

      buttons[i].setAttribute('data-event', 'true')
    }
  }
}

function createFilerButtonEvents () {
  const buttons = document.getElementsByClassName('row-filter')

  for (let i = 0; i < buttons.length; i++) {
    buttons[i].addEventListener('click', function (e) {
      filterPanel.hide()
      filter.add(
        e.target.getAttribute('data-type'),
        'include',
        e.target.getAttribute('data-value')
      )

      document.getElementById('applied-filters').classList.remove('hide')

      const viewType = document.getElementById('data-view-type').value

      displayData(filter.getData(viewType), viewType)
      createFilerRemoveEvents()
    })
  }
}

function createFilerRemoveEvents () {
  const buttons = document.querySelectorAll('button[data-filter-id]')

  for (let i = 0; i < buttons.length; i++) {
    if (buttons[i].getAttribute('data-event') !== 'true') {
      buttons[i].addEventListener('click', function (e) {
        filter.removeValue(
          e.target.getAttribute('data-filter-id'),
          e.target.getAttribute('data-filter-value')
        )

        e.target.parentElement.remove()

        const viewType = document.getElementById('data-view-type').value

        displayData(filter.getData(viewType), viewType)

        if (document.getElementById('applied-filters').hasChildNodes() === false) {
          document.getElementById('applied-filters').classList.add('hide')
        }
      })

      buttons[i].setAttribute('data-event', 'true')
    }
  }
}

function createTable (data = [], type, indexStart = 0) {
  const div = document.getElementById('data-table')

  const table = new Table()
  const header = new Row()
  header.addCell(new Cell('#', 'number'))

  tableHeaders[type].forEach(function (text) {
    header.addCell(new Cell(text))
  })

  table.addHeader(header)

  if (data.length > 0) {
    data.forEach(function (item, index) {
      const row = new Row()
      const itemNumber = index + indexStart

      row.addCell(new Cell(Format.Number(itemNumber), 'number'))

      if (type === 'address') {
        const network = details.getNetwork(item.network)
        const country = details.getCountry(item.country)

        row.addCell(new Cell(item.address))
        row.addCell(new Cell(
          createCellWithFilter('subnet', item.subnet, item.subnet),
          null,
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
        row.addCell(new Cell(Format.Number(item.bans)))
        row.addCell(new Cell(
          createViewButton('recentBans', type, item.address),
          'view-bans-btn',
          true
        ))
      }

      if (type === 'network' || type === 'country' || type === 'continent') {
        const span = document.createElement('span')
        span.innerText = item.name
        span.setAttribute('title', item.name)
        row.addCell(new Cell(
          span,
          'long',
          true
        ))
        row.addCell(new Cell(Format.Number(item.ipCount)))
        row.addCell(new Cell(Format.Number(item.bans)))

        const viewButtons = document.createElement('span')
        viewButtons.appendChild(
          createViewButton('address', type, item.number || item.code || item.name)
        )
        viewButtons.appendChild(
          createViewButton('recentBans', type, item.number || item.code || item.name)
        )

        row.addCell(new Cell(
          viewButtons,
          'view-btn',
          true
        ))
      }

      if (type === 'recentBans') {
        const network = details.getNetwork(item.network)
        const country = details.getCountry(item.country)

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

      if (type === 'date' || type === 'jail') {
        row.addCell(new Cell(item.date || item.name, 'long'))
        row.addCell(new Cell(Format.Number(item.ipCount)))
        row.addCell(new Cell(Format.Number(item.bans)))
        row.addCell(new Cell(
          createViewButton('recentBans', type, item.date || item.name),
          'view-bans-btn',
          true
        ))
      }

      if (type === 'subnet') {
        const network = details.getNetwork(item.network)
        const country = details.getCountry(item.country)

        row.addCell(new Cell(item.subnet))
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
        row.addCell(new Cell(Format.Number(item.ipCount)))
        row.addCell(new Cell(Format.Number(item.bans)))

        const viewButtons = document.createElement('span')
        viewButtons.appendChild(
          createViewButton('address', type, item.subnet)
        )
        viewButtons.appendChild(
          createViewButton('recentBans', type, item.subnet)
        )

        row.addCell(new Cell(
          viewButtons,
          'view-btn',
          true
        ))
      }

      table.addRow(row)
    })
  } else {
    const row = new Row()
    row.addCell(new Cell('No data found', 'no-data', false, 6))

    table.addRow(row)
  }

  div.innerText = ''
  div.append(table.get())

  createViewButtonEvents()
  createFilerButtonEvents()
}

function createMostBannedButtons () {
  document.getElementById('most-banned-ip-button').appendChild(
    createViewButton('recentBans', 'address', botData.address.mostBanned, 'most-banned')
  )

  document.getElementById('most-seen-network-button').appendChild(
    createViewButton('recentBans', 'network', botData.network.mostBanned, 'most-banned')
  )

  document.getElementById('most-seen-country-button').appendChild(
    createViewButton('recentBans', 'country', botData.country.mostBanned, 'most-banned')
  )

  document.getElementById('most-activated-jail-button').appendChild(
    createViewButton('recentBans', 'jail', botData.jail.mostBanned, 'most-banned')
  )
}

document.getElementById('chart-type').addEventListener('change', function (e) {
  plot.destroyChart()
  plot.newChart(e.target.value)
})

document.getElementById('data-view-type').addEventListener('change', function (e) {
  filterPanel.hide()

  const type = e.target.value

  // Disable/enable order by select
  if (type === 'address' || type === 'recentBans') {
    document.getElementById('data-order-by').disabled = true
  } else {
    document.getElementById('data-order-by').disabled = false
  }

  // Disable/enable order by date option
  if (type === 'date') {
    document.getElementById('data-order-by').options[2].disabled = false
    document.getElementById('data-order-by').options[2].selected = true
  } else {
    document.getElementById('data-order-by').options[2].disabled = true
    document.getElementById('data-order-by').options[0].selected = true
  }

  if (type === 'address' || type === 'recentBans' || type === 'subnet') {
    document.getElementById('open-filter-panel').disabled = false

    if (type === 'address') {
      filter.remove('date')
      filter.remove('jail')
    }

    if (type === 'subnet') {
      filter.remove('address')
      filter.remove('continent')
      filter.remove('date')
      filter.remove('jail')
    }
  } else {
    document.getElementById('open-filter-panel').disabled = true
    filter.reset()
  }

  if (document.getElementById('applied-filters').hasChildNodes() === false) {
    document.getElementById('applied-filters').classList.add('hide')
  }

  displayData(filter.getData(type), type)
})

document.getElementById('data-order-by').addEventListener('change', function (e) {
  const viewType = document.getElementById('data-view-type').value
  displayData(filter.getData(viewType), viewType)
})

document.getElementById('open-filter-panel').addEventListener('click', function (e) {
  filterPanel.setup(filter)
  filterPanel.show()
})

document.getElementById('close-filter-panel').addEventListener('click', function (e) {
  filterPanel.hide()
})

document.getElementById('filter-type').addEventListener('change', function (e) {
  filterPanel.setFilterValues(e.target.value, filter)
})

document.getElementById('filter-apply').addEventListener('click', function (e) {
  const viewType = document.getElementById('data-view-type').value

  document.getElementById('applied-filters').classList.remove('hide')

  filterPanel.hide()
  filter.add(
    document.getElementById('filter-type').value,
    document.getElementById('filter-action').value,
    document.getElementById('filter-value').value
  )

  displayData(filter.getData(viewType), viewType)
  createFilerRemoveEvents()
})

const pageButtons = document.getElementsByClassName('page-button')
for (let i = 0; i < pageButtons.length; i++) {
  pageButtons[i].addEventListener('click', function (e) {
    const viewType = document.getElementById('data-view-type').value
    const page = Number(e.target.getAttribute('data-page'))

    displayData(filter.getData(viewType), viewType, page)
  })
}

document.getElementById('page-number').addEventListener('change', function (e) {
  const viewType = document.getElementById('data-view-type').value
  const page = Number(e.target.value)

  displayData(filter.getData(viewType), viewType, page)
})

document.getElementById('page-size').addEventListener('change', function (e) {
  const viewType = document.getElementById('data-view-type').value

  displayData(filter.getData(viewType), viewType, 0)
})

fetchData()
  .then(response => {
    if (response.status !== 200) {
      throw new Error(`Failed to fetch data (${response.status} ${response.statusText})`)
    }

    return response.json()
  }).then(data => {
    botData = data

    if (data.error === true) {
      throw new Error(data.message)
    }

    filter = new Filter(data)
    filterPanel = new FilterPanel(data)
    details = new Details(data)
    display = new Display(data)

    plot = new Plot(data)
    plot.newChart('last24hours')

    document.getElementById('loading').classList.add('hide')
    document.getElementById('chart-options').classList.remove('hide')
    document.getElementById('chart').classList.remove('hide')
    document.getElementById('options').classList.remove('hide')
    document.getElementById('data').classList.remove('hide')

    display.headerDates()
    display.globalStats()
    display.mostBanned()
    display.daemonLog()

    createMostBannedButtons()

    displayData(filter.getData('recentBans'), 'recentBans')
  }).catch(error => {
    document.getElementById('loading').classList.add('hide')

    console.log(error)
    Message.error(error.message)
  })
