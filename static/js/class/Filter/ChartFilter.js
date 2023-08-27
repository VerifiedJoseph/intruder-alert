/* global spacetime */
import { Filter } from './Filter.js'
import { } from '../../lib/spacetime.js'

export class ChartFilter extends Filter {
  labelDiv = 'chart-applied-filters'

  /**
   * Get filtered data
   * @param {string} chartType
   * @returns
   */
  getData (chartType) {
    const data = this.getRecentBans()

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
    const groups = []
    const banCounts = []

    let yesterday = spacetime.now('Europe/London')
    yesterday = yesterday.subtract('24', 'hours')

    for (const item of data) {
      const timestamp = spacetime(item.timestamp, 'Europe/London')

      if (timestamp.isAfter(yesterday) === true) {
        const timestampFormat = timestamp.format('{year}-{iso-month}-{date-pad} {hour-24-pad}:00')

        if (groups.includes(timestampFormat) === true) {
          const key = groups.indexOf(timestampFormat)

          banCounts[key]++
        } else {
          groups.push(timestampFormat)
          banCounts.push(1)
        }
      } else {
        break
      }
    }

    return {
      labels: groups.reverse(),
      data: banCounts.reverse(),
      type: chartType
    }
  }

  /**
   * Group data by day
   * @param {array} data Data to group
   * @param {string} chartType Chart type
   */
  #groupByDay (data, days, chartType) {
    const groups = []
    const banCounts = []

    let lastWeek = spacetime.now('Europe/London')
    lastWeek = lastWeek.subtract(`${days}`, 'days')

    for (const item of data) {
      const timestamp = spacetime(item.timestamp, 'Europe/London')

      if (timestamp.isAfter(lastWeek) === true) {
        const timestampFormat = timestamp.format('{year}-{iso-month}-{date-pad}')

        if (groups.includes(timestampFormat) === true) {
          const key = groups.indexOf(timestampFormat)

          banCounts[key]++
        } else {
          groups.push(timestampFormat)
          banCounts.push(1)
        }
      } else {
        break
      }
    }

    return {
      labels: groups.reverse(),
      data: banCounts.reverse(),
      type: chartType
    }
  }
}
