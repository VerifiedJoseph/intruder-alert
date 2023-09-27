'use strict'

import { } from './lib/chart.js'
import { IaData } from './class/IaData.js'
import { Plot } from './class/Plot.js'
import { TableFilter } from './class/Filter/TableFilter.js'
import { ChartFilter } from './class/Filter/ChartFilter.js'
import { FilterAddDialog } from './class/Dialog/FilterAdd.js'
import { FilterOptionsDialog } from './class/Dialog/FilterOptions.js'
import { Display } from './class/Display.js'
import { Pagination } from './class/Pagination.js'
import { Helper } from './class/Helper.js'
import { CreateTable } from './class/CreateTable.js'

let table = {}
let chart = {}
let display, iaData

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

/**
 * Order table data by date or ip count
 */
function orderTableData (data) {
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

function displayData (data, pageNumber = 0) {
  const pagination = new Pagination(orderTableData(data))
  pagination.setPage(pageNumber)

  const htmlTable = new CreateTable(
    pagination.getData(),
    Helper.getTableType(),
    iaData,
    table.filter
  )

  htmlTable.display()
  pagination.setButtons()
}

function onViewBtnClick (viewType, filterType, filterValue) {
  table.filter.reset()
  Helper.setTableType(viewType)

  table.dialog.filterOptions.enableBtn()
  document.getElementById('data-order-by').disabled = true
  document.getElementById('data-order-by').options[0].selected = true

  if (table.filter.hasFilter(filterType, filterValue) === false) {
    table.filter.add(filterType, 'include', filterValue)

    document.getElementById('table-applied-filters').classList.remove('hide')
    table.dialog.filterAdd.enableBtn()

    displayData(table.filter.getData(viewType))
  }

  if (iaData.isChartEnabled() === true && filterType !== 'date' && chart.filter.hasFilter(filterType, filterValue) === false) {
    chart.filter.reset()
    Helper.setChartType('last30days')

    chart.filter.add(filterType, 'include', filterValue)

    document.getElementById('chart-applied-filters').classList.remove('hide')

    chart.plot.newChart(chart.filter.getData(Helper.getChartType()))
  }
}

function onRemoveFilterBtnClick (event) {
  const viewGroup = event.target.getAttribute('data-view-group')

  if (viewGroup === 'chart') {
    chart.filter.removeValue(
      event.target.getAttribute('data-filter-id'),
      event.target.getAttribute('data-filter-value')
    )

    chart.plot.newChart(chart.filter.getData(Helper.getChartType()))
  }

  if (viewGroup === 'table') {
    table.filter.removeValue(
      event.target.getAttribute('data-filter-id'),
      event.target.getAttribute('data-filter-value')
    )

    displayData(table.filter.getData(Helper.getTableType()))
  }

  event.target.parentElement.remove()

  if (document.getElementById(`${viewGroup}-applied-filters`).hasChildNodes() === false) {
    document.getElementById(`${viewGroup}-applied-filters`).classList.add('hide')
  }
}

function clickHandler (event) {
  switch (event.target.id || event.target.className) {
    case 'table-filter-add-dialog-open':
      table.dialog.filterAdd.setup(table.filter)
      table.dialog.filterAdd.open()
      break
    case 'table-filter-close-panel':
      table.dialog.filterAdd.close()
      break
    case 'table-filter-apply':
      document.getElementById('table-applied-filters').classList.remove('hide')

      table.dialog.filterAdd.close()
      table.filter.add(
        document.getElementById('table-filter-type').value,
        document.getElementById('table-filter-action').value,
        document.getElementById('table-filter-value').value
      )

      displayData(table.filter.getData(Helper.getTableType()))
      break
    case 'chart-filter-add-dialog-open':
      chart.dialog.filterAdd.setup(chart.filter)
      chart.dialog.filterAdd.open()
      break
    case 'chart-filter-close-panel':
      chart.dialog.filterAdd.close()
      break
    case 'chart-filter-apply':
      chart.dialog.filterAdd.close()
      document.getElementById('chart-applied-filters').classList.remove('hide')

      chart.filter.add(
        document.getElementById('chart-filter-type').value,
        document.getElementById('chart-filter-action').value,
        document.getElementById('chart-filter-value').value
      )

      chart.plot.newChart(chart.filter.getData(Helper.getChartType()))
      break
    case 'chart-filter-options-dialog-open':
      chart.dialog.filterOptions.setup(chart.filter)
      chart.dialog.filterOptions.open()
      break
    case 'chart-filter-options-close':
      chart.dialog.filterOptions.close()
      break
    case 'chart-filters-reverse':
      chart.dialog.filterOptions.close()

      chart.filter.reverse()
      chart.plot.newChart(chart.filter.getData(Helper.getChartType()))
      break
    case 'chart-filters-remove':
      chart.dialog.filterOptions.close()

      chart.filter.reset()
      chart.plot.newChart(chart.filter.getData(Helper.getChartType()))
      break
    case 'table-filter-options-dialog-open':
      table.dialog.filterOptions.setup(table.filter)
      table.dialog.filterOptions.open()
      break
    case 'dialog-filters-reverse':
      if (event.target.getAttribute('data-view-type') === 'chart') {
        chart.dialog.filterOptions.close()
        chart.filter.reverse()
        chart.plot.newChart(chart.filter.getData(Helper.getChartType()))
      }

      if (event.target.getAttribute('data-view-type') === 'table') {
        table.dialog.filterOptions.close()
        table.filter.reverse()
        displayData(table.filter.getData(Helper.getTableType()))
      }
      break
    case 'dialog-filters-remove':
      if (event.target.getAttribute('data-view-type') === 'chart') {
        chart.dialog.filterOptions.close()
        chart.filter.reset()
        chart.plot.newChart(chart.filter.getData(Helper.getChartType()))
      }

      if (event.target.getAttribute('data-view-type') === 'table') {
        table.dialog.filterOptions.close()
        table.filter.reset()
        displayData(table.filter.getData(Helper.getTableType()))
      }
      break
    case 'dialog-close':
      if (event.target.getAttribute('data-close-dialog') === 'chart-filter-options') {
        chart.dialog.filterOptions.close()
      }

      if (event.target.getAttribute('data-close-dialog') === 'table-filter-options') {
        table.dialog.filterOptions.close()
      }
      break
    case 'filter-remove':
      onRemoveFilterBtnClick(event)
      break
    case 'row-filter':
      table.dialog.filterAdd.close()
      table.filter.add(
        event.target.getAttribute('data-type'),
        'include',
        event.target.getAttribute('data-value')
      )

      document.getElementById('table-applied-filters').classList.remove('hide')

      displayData(table.filter.getData(Helper.getTableType()))
      break
    case 'load-first-page':
    case 'load-prev-page':
    case 'load-next-page':
    case 'load-last-page':
      displayData(table.filter.getData(Helper.getTableType()), Number(event.target.getAttribute('data-page')))
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
      chart.plot.newChart(chart.filter.getData(event.target.value))
      break
    case 'table-type':
      table.dialog.filterAdd.close()

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
        table.dialog.filterAdd.enableBtn()
        table.dialog.filterOptions.enableBtn()

        if (event.target.value === 'address') {
          table.filter.removeMany(['date', 'jail'])
        }

        if (event.target.value === 'subnet') {
          table.filter.removeMany(['address', 'continent', 'date', 'jail'])
        }
      } else {
        table.dialog.filterAdd.disableBtn()
        table.dialog.filterOptions.disableBtn()
        table.filter.reset()
      }

      if (document.getElementById('table-applied-filters').hasChildNodes() === false) {
        document.getElementById('table-applied-filters').classList.add('hide')
      }

      displayData(table.filter.getData(event.target.value))
      break
    case 'data-order-by':
      displayData(table.filter.getData(Helper.getTableType()))
      break
    case 'table-filter-type':
      table.dialog.filterAdd.setFilterValues(event.target.value, table.filter)
      break
    case 'chart-filter-type':
      chart.dialog.filterAdd.setFilterValues(event.target.value, chart.filter)
      break
    case 'page-number':
      displayData(table.filter.getData(Helper.getTableType()), Number(event.target.value))
      break
    case 'page-size':
      displayData(table.filter.getData(Helper.getTableType()))
      break
  }
}

function updateDashboard (data) {
  document.getElementById('updating').classList.remove('hide')

  iaData = new IaData(data)
  table.filter.updateIaData(iaData)
  chart.filter.updateIaData(iaData)

  table.dialog.filterAdd = new FilterAddDialog('table', iaData)
  chart.dialog.filterAdd = new FilterAddDialog('chart', iaData)

  display = new Display(iaData)
  display.render()

  if (iaData.isChartEnabled() === true) {
    chart.plot.newChart(chart.filter.getData(Helper.getChartType()))

    document.getElementById('chart').classList.remove('hide')
  } else {
    document.getElementById('chart').classList.add('hide')
  }

  Helper.createMostBannedButtons(data)
  displayData(table.filter.getData(Helper.getTableType()))
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

document.getElementById('loading').classList.remove('hide')

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
    table = {
      filter: new TableFilter(iaData),
      dialog: {
        filterAdd: new FilterAddDialog('table', iaData),
        filterOptions: new FilterOptionsDialog('table')
      }
    }
    chart = {
      filter: new ChartFilter(iaData),
      dialog: {
        filterAdd: new FilterAddDialog('chart', iaData),
        filterOptions: new FilterOptionsDialog('chart')
      }
    }

    display = new Display(iaData)
    display.render()

    document.getElementById('loading').classList.add('hide')
    document.getElementById('content').classList.remove('hide')

    if (iaData.isChartEnabled() === true) {
      chart.plot = new Plot()
      chart.plot.newChart(chart.filter.getData('last24hours'))

      document.getElementById('chart').classList.remove('hide')
    }

    if (data.settings.enableUpdates === true) {
      setInterval(checkForUpdate, 60000)
    }

    Helper.createMostBannedButtons(data)
    displayData(table.filter.getData('recentBans'))
  }).catch(error => {
    document.getElementById('loading').classList.add('hide')
    Helper.errorMessage(error.message)
  })

const body = document.querySelector('body')
body.addEventListener('click', clickHandler)
body.addEventListener('change', changeHandler)
