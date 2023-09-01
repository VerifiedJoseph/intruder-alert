/* global spacetime */
import { Filter } from './Filter.js'
import { FilterChip } from '../FilterChip.js'
import { } from '../../lib/spacetime.js'

export class ChartFilter extends Filter {
  #hourDisplayFormat = '{year}-{iso-month}-{date-pad} {hour-24-pad}:00'
  #dateDisplayFormat = '{year}-{iso-month}-{date-pad}'

  constructor (iaData) {
    super(iaData)
    this.chip = new FilterChip('chart-applied-filters', iaData)
  }

  /**
   * Get filtered data
   * @param {string} chartType
   * @returns
   */
  getData (chartType) {
    const data = this.iaData.getRecentBans()

    if (this.settings.length > 0) {
      return this.#groupData(this._getFilteredData(data), chartType)
    }

    return this.#groupData(data, chartType)
  }

  /**
   * Group data
   * @param {array} data Data to group
   * @param {string} chartType Chart type
   */
  #groupData (data, chartType) {
    if (data.length === 0) {
      return { hasData: false }
    }

    if (chartType === 'last24hours') {
      return this.#groupByHour(data, chartType)
    }

    if (chartType === 'last7days') {
      return this.#groupByDay(data, 7, chartType)
    }

    return this.#groupByDay(data, 30, chartType)
  }

  /**
   * Group data by hour
   * @param {array} data Data to group
   * @param {string} chartType Chart type
   */
  #groupByHour (data, chartType) {
    const groupParts = this.#createHourGroups()
    const groupKeys = groupParts[0]
    const groups = groupParts[1]

    const yesterday = spacetime.now().subtract('24', 'hours')

    for (const item of data) {
      const timestamp = spacetime(item.timestamp)

      if (timestamp.isAfter(yesterday) === true) {
        const key = groupKeys.indexOf(timestamp.format(this.#hourDisplayFormat))

        groups[key].banCount++

        if (groups[key].addresses.includes(item.address) === false) {
          groups[key].ipCount++
          groups[key].addresses.push(item.address)
        }
      } else {
        break
      }
    }

    return {
      labels: groupKeys,
      datasets: this.#getDatasets(groups),
      type: chartType,
      hasData: true
    }
  }

  /**
   * Group data by day
   * @param {array} data Data to group
   * @param {string} chartType Chart type
   */
  #groupByDay (data, days, chartType) {
    const groupParts = this.#createDayGroups(days)
    const groupKeys = groupParts[0]
    const groups = groupParts[1]

    const date = spacetime.now().subtract(`${days - 1}`, 'days')

    for (const item of data) {
      const timestamp = spacetime(item.timestamp)

      if (timestamp.isAfter(date) === true) {
        const key = groupKeys.indexOf(timestamp.format(this.#dateDisplayFormat))

        groups[key].banCount++

        if (groups[key].addresses.includes(item.address) === false) {
          groups[key].ipCount++
          groups[key].addresses.push(item.address)
        }
      } else {
        break
      }
    }

    return {
      labels: groupKeys,
      datasets: this.#getDatasets(groups),
      type: chartType,
      hasData: true
    }
  }

  #getDatasets (groups) {
    const banCounts = []
    const ipCounts = []

    groups.forEach(g => {
      banCounts.push(g.banCount)
      ipCounts.push(g.ipCount)
    })

    return [
      {
        fill: true,
        label: 'IPs',
        data: ipCounts
      },
      {
        fill: true,
        label: 'Bans',
        data: banCounts
      }]
  }

  #createHourGroups () {
    let hour = spacetime.now().subtract('24', 'hours')
    const groups = []
    const keys = []

    for (let i = 1; i <= 24; i++) {
      if (i > 1) {
        hour = hour.add('1', 'hours')
      }

      const hourFormatted = hour.format(this.#hourDisplayFormat)

      keys.push(hourFormatted)
      groups.push({
        date: hourFormatted,
        banCount: 0,
        ipCount: 0,
        addresses: []
      })
    }

    return [keys, groups]
  }

  #createDayGroups (days) {
    let date = spacetime.now().subtract(days, 'days')
    const groups = []
    const keys = []

    for (let i = 1; i <= days; i++) {
      date = date.add(1, 'day')
      const dateFormatted = date.format(this.#dateDisplayFormat)

      keys.push(dateFormatted)
      groups.push({
        date: dateFormatted,
        banCount: 0,
        ipCount: 0,
        addresses: []
      })
    }

    return [keys, groups]
  }
}
