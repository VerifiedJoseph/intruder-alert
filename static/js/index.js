'use strict'

import { } from './lib/chart.js'
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

let filterPanel, filter, chartFilter, chartFilterPanel,
  plot, details, display

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
  const pagination = new Pagination(orderData(data))
  pagination.setPage(pageNumber)

  createTable(pagination.getData(), type)

  pagination.setButtons()
}

function getViewType () {
  return document.getElementById('data-view-type').value
}

/**
 * Create data filter button for a table cell
 * @param {string} dataType Data type
 * @param {string} dataValue Data value
 * @param {string} text Span text
 * @returns HTMLSpanElement
 */
function createFilterButton (dataType, dataValue, text) {
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

/**
 * Create a data view button
 * @param {string} viewType Data view type
 * @param {string} filterType Filter type
 * @param {string} filterValue Filter value
 * @param {string} context Context the button is being used
 * @returns HTMLButtonElement
 */
function createViewButton (viewType, filterType, filterValue, context = 'table') {
  const button = document.createElement('button')

  button.innerText = (viewType === 'address') ? 'View IPs' : 'View Bans'
  button.classList.add('view')
  button.setAttribute('data-view-type', viewType)
  button.setAttribute('data-filter-type', filterType)
  button.setAttribute('data-filter-value', filterValue)
  button.setAttribute('data-context', context)
  return button
}

/**
 * Create click events for view buttons
 */
function createViewButtonEvents () {
  const buttons = document.getElementsByClassName('view')

  for (let i = 0; i < buttons.length; i++) {
    if (buttons[i].getAttribute('data-event') !== 'true') {
      buttons[i].addEventListener('click', function (e) {
        const filterType = e.target.getAttribute('data-filter-type')
        const filterValue = e.target.getAttribute('data-filter-value')
        const context = e.target.getAttribute('data-context')

        filterPanel.hide()
        chartFilterPanel.hide()

        if (context === 'most-banned') {
          filter.reset()
          chartFilter.reset()
        }

        if (e.target.getAttribute('data-view-type') === 'recentBans' && getViewType() === 'address') {
          filter.reset()
        }

        document.getElementById('data-view-type').value = e.target.getAttribute('data-view-type')
        document.getElementById('chart-type').value = 'last30days'

        if (filter.hasFilter(filterType, filterValue) === false) {
          filter.add(filterType, 'include', filterValue)

          document.getElementById('applied-filters').classList.remove('hide')
          document.getElementById('filter-open-panel').disabled = false

          displayData(filter.getData(getViewType()), getViewType())
          createFilerRemoveEvents()
        } else {
          Message.error('A filter already exists for this.', true)
        }

        if (chartFilter.hasFilter(filterType, filterValue) === false && context === 'most-banned') {
          chartFilter.add(filterType, 'include', filterValue)

          document.getElementById('chart-applied-filters').classList.remove('hide')
          document.getElementById('chart-filter-open-panel').disabled = false

          plot.newChart(chartFilter.getData(document.getElementById('chart-type').value))
          createChartFilerRemoveEvents()
        } else {
          Message.error('A filter already exists for this.', true)
        }
      })

      buttons[i].setAttribute('data-event', 'true')
    }
  }
}

/**
 * Create click events for filer buttons
 */
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

      displayData(filter.getData(getViewType()), getViewType())
      createFilerRemoveEvents()
    })
  }
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

        displayData(filter.getData(getViewType()), getViewType())

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
          createFilterButton('subnet', item.subnet, item.subnet),
          null,
          true
        ))
        row.addCell(new Cell(
          createFilterButton('network', network.number, network.name),
          'asn',
          true
        ))
        row.addCell(new Cell(
          createFilterButton('country', country.code, country.name),
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

        row.addCell(new Cell(span, 'long', true))
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
          createFilterButton('address', item.address, item.address),
          'address',
          true
        ))
        row.addCell(new Cell(
          createFilterButton('jail', item.jail, item.jail),
          'jail',
          true
        ))
        row.addCell(new Cell(
          createFilterButton('network', network.number, network.name),
          'asn',
          true
        ))
        row.addCell(new Cell(
          createFilterButton('country', country.code, country.name),
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
          createFilterButton('network', network.number, network.name),
          'asn',
          true
        ))
        row.addCell(new Cell(
          createFilterButton('country', country.code, country.name),
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

function createMostBannedButtons (data) {
  const types = ['address', 'network', 'country', 'jail']

  types.forEach(type => {
    document.getElementById(`most-${type}-button`).appendChild(
      createViewButton('recentBans', type, data[type].mostBanned, 'most-banned')
    )
  })
}

document.getElementById('chart-type').addEventListener('change', function (e) {
  plot.newChart(chartFilter.getData(e.target.value))
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
    document.getElementById('filter-open-panel').disabled = false

    if (type === 'address') {
      filter.removeMany(['date', 'jail'])
    }

    if (type === 'subnet') {
      filter.removeMany(['address', 'continent', 'date', 'jail'])
    }
  } else {
    document.getElementById('filter-open-panel').disabled = true
    filter.reset()
  }

  if (document.getElementById('applied-filters').hasChildNodes() === false) {
    document.getElementById('applied-filters').classList.add('hide')
  }

  displayData(filter.getData(type), type)
})

document.getElementById('data-order-by').addEventListener('change', function (e) {
  displayData(filter.getData(getViewType()), getViewType())
})

document.getElementById('filter-open-panel').addEventListener('click', function (e) {
  filterPanel.setup(filter)
  filterPanel.show()
})

document.getElementById('filter-close-panel').addEventListener('click', function (e) {
  filterPanel.hide()
})

document.getElementById('filter-type').addEventListener('change', function (e) {
  filterPanel.setFilterValues(e.target.value, filter)
})

document.getElementById('filter-apply').addEventListener('click', function (e) {
  document.getElementById('applied-filters').classList.remove('hide')

  filterPanel.hide()
  filter.add(
    document.getElementById('filter-type').value,
    document.getElementById('filter-action').value,
    document.getElementById('filter-value').value
  )

  displayData(filter.getData(getViewType()), getViewType())
  createFilerRemoveEvents()
})

document.getElementById('chart-filter-open-panel').addEventListener('click', function (e) {
  chartFilterPanel.setup(chartFilter)
  chartFilterPanel.show()
})

document.getElementById('chart-filter-close-panel').addEventListener('click', function (e) {
  chartFilterPanel.hide()
})

document.getElementById('chart-filter-type').addEventListener('change', function (e) {
  chartFilterPanel.setFilterValues(e.target.value, chartFilter)
})

document.getElementById('chart-filter-apply').addEventListener('click', function (e) {
  const chartType = document.getElementById('chart-type').value

  chartFilterPanel.hide()
  document.getElementById('chart-applied-filters').classList.remove('hide')

  chartFilter.add(
    document.getElementById('chart-filter-type').value,
    document.getElementById('chart-filter-action').value,
    document.getElementById('chart-filter-value').value
  )

  plot.newChart(chartFilter.getData(chartType))
  createChartFilerRemoveEvents()
})

const pageButtons = document.getElementsByClassName('page-button')
for (let i = 0; i < pageButtons.length; i++) {
  pageButtons[i].addEventListener('click', function (e) {
    const page = Number(e.target.getAttribute('data-page'))

    displayData(filter.getData(getViewType()), getViewType(), page)
  })
}

document.getElementById('page-number').addEventListener('change', function (e) {
  displayData(filter.getData(getViewType()), getViewType(), Number(e.target.value))
})

document.getElementById('page-size').addEventListener('change', function (e) {
  displayData(filter.getData(getViewType()), getViewType(), 0)
})

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

    filter = new TableFilter(data)
    filterPanel = new FilterPanel(data)
    chartFilter = new ChartFilter(data)
    chartFilterPanel = new FilterPanel(data, 'chart')

    details = new Details(data)
    display = new Display(data)

    document.getElementById('loading').classList.add('hide')

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

    createMostBannedButtons(data)

    displayData(filter.getData('recentBans'), 'recentBans')
  }).catch(error => {
    document.getElementById('loading').classList.add('hide')
    Message.error(error.message)
  })
