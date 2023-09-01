'use strict'

import { } from './lib/chart.js'
import { IaData } from './class/IaData.js'
import { Plot } from './class/Plot.js'
import { Table, Row, Cell } from './class/Table.js'
import { TableFilter } from './class/Filter/TableFilter.js'
import { ChartFilter } from './class/Filter/ChartFilter.js'
import { FilterPanel } from './class/FilterPanel.js'
import { Details } from './class/Details.js'
import { Display } from './class/Display.js'
import { Format } from './class/Format.js'
import { Pagination } from './class/Pagination.js'
import { Message } from './class/Message.js'
import { Button } from './class/Button.js'
import { Helper } from './class/Helper.js'

let filterPanel, filter, chartFilter, chartFilterPanel,
  plot, details, display, iaData
let chartsDisabled = false

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

function displayData (data, type, pageNumber = 0) {
  const pagination = new Pagination(Helper.orderData(data))
  pagination.setPage(pageNumber)

  createTable(pagination.getData(), type)

  pagination.setButtons()
}

/**
 * Create click events for removing table filters
 */
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

        displayData(filter.getData(Helper.getViewType()), Helper.getViewType())

        if (document.getElementById('applied-filters').hasChildNodes() === false) {
          document.getElementById('applied-filters').classList.add('hide')
        }
      })

      buttons[i].setAttribute('data-event', 'true')
    }
  }
}

/**
 * Create click events for removing chart filters
 */
function createChartFilerRemoveEvents () {
  const buttons = document.querySelectorAll('#chart-applied-filters > .item > button[data-filter-id]')

  for (let i = 0; i < buttons.length; i++) {
    if (buttons[i].getAttribute('data-event') !== 'true') {
      buttons[i].addEventListener('click', function (e) {
        e.target.parentElement.remove()

        chartFilter.removeValue(
          e.target.getAttribute('data-filter-id'),
          e.target.getAttribute('data-filter-value')
        )

        plot.newChart(chartFilter.getData(document.getElementById('chart-type').value))

        if (document.getElementById('chart-applied-filters').hasChildNodes() === false) {
          document.getElementById('chart-applied-filters').classList.add('hide')
        }
      })

      buttons[i].setAttribute('data-event', 'true')
    }
  }
}

/**
 * Create table
 * @param {array} data Table data
 * @param {string} type Table type
 */
function createTable (data = [], type) {
  const div = document.getElementById('data-table')

  const table = new Table()
  const header = new Row()
  header.addCell(new Cell('#', 'number'))

  tableHeaders[type].forEach(function (text) {
    header.addCell(new Cell(text))
  })

  table.addHeader(header)

  if (data.items.length > 0) {
    data.items.forEach(function (item, index) {
      const row = new Row()
      const itemNumber = index + data.indexStart

      row.addCell(new Cell(Format.Number(itemNumber), 'number'))

      if (type === 'address') {
        const network = details.getNetwork(item.network)
        const country = details.getCountry(item.country)

        row.addCell(new Cell(item.address))
        row.addCell(new Cell(
          Button.createFilter('subnet', item.subnet, item.subnet, filter),
          null,
          true
        ))
        row.addCell(new Cell(
          Button.createFilter('network', network.number, network.name, filter),
          'asn',
          true
        ))
        row.addCell(new Cell(
          Button.createFilter('country', country.code, country.name, filter),
          'country',
          true
        ))
        row.addCell(new Cell(Format.Number(item.bans)))
        row.addCell(new Cell(
          Button.createFilter('recentBans', type, item.address, filter),
          'view-bans-btn',
          true
        ))
      }

      if (type === 'network' || type === 'country' || type === 'continent') {
        const span = document.createElement('span')
        span.innerText = item.name
        span.setAttribute('title', item.name)

        row.addCell(new Cell(span, 'long', true))
        row.addCell(new Cell(Format.Number(item.ipCount)))
        row.addCell(new Cell(Format.Number(item.bans)))

        const viewButtons = document.createElement('span')
        viewButtons.appendChild(
          Button.createView('address', type, item.number || item.code || item.name)
        )
        viewButtons.appendChild(
          Button.createView('recentBans', type, item.number || item.code || item.name)
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
          Button.createFilter('address', item.address, item.address, filter),
          'address',
          true
        ))
        row.addCell(new Cell(
          Button.createFilter('jail', item.jail, item.jail, filter),
          'jail',
          true
        ))
        row.addCell(new Cell(
          Button.createFilter('network', network.number, network.name, filter),
          'asn',
          true
        ))
        row.addCell(new Cell(
          Button.createFilter('country', country.code, country.name, filter),
          'country',
          true
        ))
      }

      if (type === 'date' || type === 'jail') {
        row.addCell(new Cell(item.date || item.name, 'long'))
        row.addCell(new Cell(Format.Number(item.ipCount)))
        row.addCell(new Cell(Format.Number(item.bans)))
        row.addCell(new Cell(
          Button.createView('recentBans', type, item.date || item.name),
          'view-bans-btn',
          true
        ))
      }

      if (type === 'subnet') {
        const network = details.getNetwork(item.network)
        const country = details.getCountry(item.country)

        row.addCell(new Cell(item.subnet))
        row.addCell(new Cell(
          Button.createFilter('network', network.number, network.name, filter),
          'asn',
          true
        ))
        row.addCell(new Cell(
          Button.createFilter('country', country.code, country.name, filter),
          'country',
          true
        ))
        row.addCell(new Cell(Format.Number(item.ipCount)))
        row.addCell(new Cell(Format.Number(item.bans)))

        const viewButtons = document.createElement('span')
        viewButtons.appendChild(
          Button.createView('address', type, item.subnet)
        )
        viewButtons.appendChild(
          Button.createView('recentBans', type, item.subnet)
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
}

function clickHandler (event) {
  switch (event.target.id || event.target.className) {
    case 'filter-open-panel':
      filterPanel.setup(filter)
      filterPanel.show()
      break
    case 'filter-close-panel':
      filterPanel.hide()
      break
    case 'filter-apply':
      document.getElementById('applied-filters').classList.remove('hide')

      filterPanel.hide()
      filter.add(
        document.getElementById('filter-type').value,
        document.getElementById('filter-action').value,
        document.getElementById('filter-value').value
      )

      displayData(filter.getData(Helper.getViewType()), Helper.getViewType())
      createFilerRemoveEvents()
      break
    case 'chart-filter-open-panel':
      chartFilterPanel.setup(chartFilter)
      chartFilterPanel.show()
      break
    case 'chart-filter-close-panel':
      chartFilterPanel.hide()
      break
    case 'chart-filter-apply':
      chartFilterPanel.hide()
      document.getElementById('chart-applied-filters').classList.remove('hide')

      chartFilter.add(
        document.getElementById('chart-filter-type').value,
        document.getElementById('chart-filter-action').value,
        document.getElementById('chart-filter-value').value
      )

      plot.newChart(chartFilter.getData(document.getElementById('chart-type').value))
      createChartFilerRemoveEvents()
      break
    case 'row-filter':
      filterPanel.hide()
      filter.add(
        event.target.getAttribute('data-type'),
        'include',
        event.target.getAttribute('data-value')
      )

      document.getElementById('applied-filters').classList.remove('hide')

      displayData(filter.getData(Helper.getViewType()), Helper.getViewType())
      createFilerRemoveEvents()
      break
    case 'load-first-page':
    case 'load-prev-page':
    case 'load-next-page':
    case 'load-last-page':
      displayData(filter.getData(Helper.getViewType()), Helper.getViewType(), Number(event.target.getAttribute('data-page')))
      break
    case 'view-button':
      filterPanel.hide()
      chartFilterPanel.hide()

      if (event.target.getAttribute('data-context') === 'most-banned') {
        filter.reset()
        chartFilter.reset()
      }

      if (event.target.getAttribute('data-view-type') === 'recentBans' && Helper.getViewType() === 'address') {
        filter.reset()
      }

      document.getElementById('table-type').value = event.target.getAttribute('data-view-type')
      document.getElementById('chart-type').value = 'last30days'

      if (filter.hasFilter(event.target.getAttribute('data-filter-type'), event.target.getAttribute('data-filter-value')) === false) {
        filter.add(event.target.getAttribute('data-filter-type'), 'include', event.target.getAttribute('data-filter-value'))

        document.getElementById('applied-filters').classList.remove('hide')
        document.getElementById('filter-open-panel').disabled = false

        displayData(filter.getData(Helper.getViewType()), Helper.getViewType())
        createFilerRemoveEvents()
      } else {
        Message.error('A filter already exists for this.', true)
      }

      if (event.target.getAttribute('data-context') === 'most-banned' && chartsDisabled === false) {
        if (chartFilter.hasFilter(event.target.getAttribute('data-filter-type'), event.target.getAttribute('data-filter-value')) === false) {
          chartFilter.add(
            event.target.getAttribute('data-filter-type'),
            'include',
            event.target.getAttribute('data-filter-value')
          )

          document.getElementById('chart-applied-filters').classList.remove('hide')
          document.getElementById('chart-filter-open-panel').disabled = false

          plot.newChart(chartFilter.getData(document.getElementById('chart-type').value))
          createChartFilerRemoveEvents()
        }
      }
  }
}

function changeHandler (event) {
  switch (event.target.id || event.target.className) {
    case 'chart-type':
      plot.newChart(chartFilter.getData(event.target.value))
      break
    case 'table-type':
      filterPanel.hide()

      // Disable/enable order by select
      if (event.target.value === 'address' || event.target.value === 'recentBans') {
        document.getElementById('data-order-by').disabled = true
      } else {
        document.getElementById('data-order-by').disabled = false
      }

      // Disable/enable order by date option
      if (event.target.value === 'date') {
        document.getElementById('data-order-by').options[2].disabled = false
        document.getElementById('data-order-by').options[2].selected = true
      } else {
        document.getElementById('data-order-by').options[2].disabled = true
        document.getElementById('data-order-by').options[0].selected = true
      }

      if (event.target.value === 'address' || event.target.value === 'recentBans' || event.target.value === 'subnet') {
        document.getElementById('filter-open-panel').disabled = false

        if (event.target.value === 'address') {
          filter.removeMany(['date', 'jail'])
        }

        if (event.target.value === 'subnet') {
          filter.removeMany(['address', 'continent', 'date', 'jail'])
        }
      } else {
        document.getElementById('filter-open-panel').disabled = true
        filter.reset()
      }

      if (document.getElementById('applied-filters').hasChildNodes() === false) {
        document.getElementById('applied-filters').classList.add('hide')
      }

      displayData(filter.getData(event.target.value), event.target.value)
      break
    case 'data-order-by':
      displayData(filter.getData(Helper.getViewType()), Helper.getViewType())
      break
    case 'filter-type':
      filterPanel.setFilterValues(event.target.value, filter)
      break
    case 'chart-filter-type':
      chartFilterPanel.setFilterValues(event.target.value, chartFilter)
      break
    case 'page-number':
      displayData(filter.getData(Helper.getViewType()), Helper.getViewType(), Number(event.target.value))
      break
    case 'page-size':
      displayData(filter.getData(Helper.getViewType()), Helper.getViewType(), 0)
      break
  }
}

fetchData()
  .then(response => {
    if (response.status !== 200) {
      throw new Error(`Failed to fetch data (${response.status} ${response.statusText})`)
    }

    return response.json()
  }).then(data => {
    if (data.error === true) {
      throw new Error(data.message)
    }

    iaData = new IaData(data)

    details = new Details(data)
    filter = new TableFilter(iaData, data, details)
    filterPanel = new FilterPanel(data)
    chartFilter = new ChartFilter(iaData, data, details)
    chartFilterPanel = new FilterPanel(data, 'chart')
    display = new Display(iaData)

    document.getElementById('loading').classList.add('hide')

    chartsDisabled = data.settings.disableCharts
    if (data.settings.disableCharts === false) {
      plot = new Plot(data)
      plot.newChart(chartFilter.getData('last24hours'))

      document.getElementById('chart-options').classList.remove('hide')
      document.getElementById('chart').classList.remove('hide')
    }

    document.getElementById('options').classList.remove('hide')
    document.getElementById('data').classList.remove('hide')

    display.headerDates()
    display.globalStats()
    display.mostBanned()
    display.daemonLog()

    Helper.createMostBannedButtons(data)

    displayData(filter.getData('recentBans'), 'recentBans')
  }).catch(error => {
    document.getElementById('loading').classList.add('hide')
    Message.error(error.message)
    console.log(error)
  })

const body = document.querySelector('body')
body.addEventListener('click', clickHandler)
body.addEventListener('change', changeHandler)
