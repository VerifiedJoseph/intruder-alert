'use strict'

import { } from './lib/chart.js'
import { IaData } from './class/IaData.js'
import { Plot } from './class/Plot.js'
import { TableFilter } from './class/Filter/TableFilter.js'
import { ChartFilter } from './class/Filter/ChartFilter.js'
import { FilterPanel } from './class/FilterPanel.js'
import { Display } from './class/Display.js'
import { Pagination } from './class/Pagination.js'
import { Helper } from './class/Helper.js'
import { CreateTable } from './class/CreateTable.js'

let filterPanel, filter, chartFilter, chartFilterPanel,
  plot, display, iaData
let chartsEnabled = true

function fetchData (lastUpdate = '') {
  let setting = {}

  if (lastUpdate !== '') {
    setting = {
      method: 'POST',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      body: `lastUpdated=${lastUpdate}`
    }
  }

  return fetch('data.php', setting)
}

function displayData (data, type, pageNumber = 0) {
  const pagination = new Pagination(Helper.orderData(data))
  pagination.setPage(pageNumber)

  const table = new CreateTable(
    pagination.getData(),
    type,
    iaData,
    filter
  )

  table.display()
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

function onViewBtnClick (viewType, filterType, filterValue) {
  filterPanel.hide()
  chartFilterPanel.hide()

  filter.reset()
  chartFilter.reset()

  Helper.setTableType(viewType)
  Helper.setChartType('last30days')

  if (filter.hasFilter(filterType, filterValue) === false) {
    filter.add(filterType, 'include', filterValue)

    document.getElementById('applied-filters').classList.remove('hide')
    document.getElementById('filter-open-panel').disabled = false

    displayData(filter.getData(viewType), viewType)
    createFilerRemoveEvents()
  }

  if (chartsEnabled === true && chartFilter.hasFilter(filterType, filterValue) === false) {
    chartFilter.add(filterType, 'include', filterValue)

    document.getElementById('chart-applied-filters').classList.remove('hide')
    document.getElementById('chart-filter-open-panel').disabled = false

    plot.newChart(chartFilter.getData(document.getElementById('chart-type').value))
    createChartFilerRemoveEvents()
  }
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
      onViewBtnClick(
        event.target.getAttribute('data-view-type'),
        event.target.getAttribute('data-filter-type'),
        event.target.getAttribute('data-filter-value')
      )
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
        document.getElementById('data-order-by').options[2].hidden = false
        document.getElementById('data-order-by').options[2].disabled = false
        document.getElementById('data-order-by').options[2].selected = true
      } else {
        document.getElementById('data-order-by').options[2].hidden = true
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

function updateDashboard (data) {
  document.getElementById('updating').classList.remove('hide')

  iaData = new IaData(data)
  filter.updateIaData(iaData)
  chartFilter.updateIaData(iaData)

  filterPanel = new FilterPanel(iaData)
  chartFilterPanel = new FilterPanel(iaData, 'chart')

  display = new Display(iaData)
  display.render()

  chartsEnabled = data.settings.enableCharts
  if (data.settings.enableCharts === true) {
    plot.newChart(chartFilter.getData(document.getElementById('chart-type').value))

    document.getElementById('chart').classList.remove('hide')
  } else {
    document.getElementById('chart').classList.add('hide')
  }

  Helper.createMostBannedButtons(data)
  displayData(filter.getData(Helper.getViewType()), Helper.getViewType())
}

function checkForUpdate () {
  fetchData(iaData.getUpdatedDate())
    .then(response => {
      if (response.status !== 200) {
        throw new Error(`Failed to fetch data (${response.status} ${response.statusText})`)
      }

      return response.json()
    }).then(data => {
      if (data.error === true) {
        throw new Error(data.message)
      }

      if (data.hasUpdates === true) {
        updateDashboard(data)

        setTimeout(() => {
          document.getElementById('updating').classList.add('hide')
        }, 1500)
      }

      document.getElementById('content').classList.remove('hide')
      document.getElementById('error').classList.add('hide')
    }).catch(error => {
      document.getElementById('updating').classList.add('hide')
      document.getElementById('content').classList.add('hide')

      Helper.errorMessage(error.message)
      console.log(error)
    })
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
    filter = new TableFilter(iaData)
    filterPanel = new FilterPanel(iaData)
    chartFilter = new ChartFilter(iaData)
    chartFilterPanel = new FilterPanel(iaData, 'chart')

    display = new Display(iaData)
    display.render()

    document.getElementById('loading').classList.add('hide')
    document.getElementById('content').classList.remove('hide')

    chartsEnabled = data.settings.enableCharts
    if (data.settings.enableCharts === true) {
      plot = new Plot()
      plot.newChart(chartFilter.getData('last24hours'))

      document.getElementById('chart').classList.remove('hide')
    }

    if (data.settings.enableUpdates === true) {
      setInterval(checkForUpdate, 60000)
    }

    Helper.createMostBannedButtons(data)
    displayData(filter.getData('recentBans'), 'recentBans')
  }).catch(error => {
    document.getElementById('loading').classList.add('hide')
    Helper.errorMessage(error.message)
  })

const body = document.querySelector('body')
body.addEventListener('click', clickHandler)
body.addEventListener('change', changeHandler)
