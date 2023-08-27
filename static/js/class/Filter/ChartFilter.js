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
    const groupKeys = []
    const groups = []

    let yesterday = spacetime.now()
    yesterday = yesterday.subtract('24', 'hours')

    for (const item of data) {
      const timestamp = spacetime(item.timestamp)

      if (timestamp.isAfter(yesterday) === true) {
        const timestampFormat = timestamp.format('{year}-{iso-month}-{date-pad} {hour-24-pad}:00')

        if (groupKeys.includes(timestampFormat) === true) {
          const key = groupKeys.indexOf(timestampFormat)

          const group = groups[key]
          group.banCount++

          if (group.addresses.includes(item.address) === false) {
            group.ipCount++
            group.addresses.push(item.address)
          }
        } else {
          const group = {
            date: timestampFormat,
            banCount: 1,
            ipCount: 1,
            addresses: [item.address]
          }

          groupKeys.push(timestampFormat)
          groups.push(group)
        }
      } else {
        break
      }
    }

    return {
      labels: groupKeys.reverse(),
      datasets: this.#getDatasets(groups),
      type: chartType
    }
  }

  /**
   * Group data by day
   * @param {array} data Data to group
   * @param {string} chartType Chart type
   */
  #groupByDay (data, days, chartType) {
    const groupKeys = []
    const groups = []

    let lastWeek = spacetime.now()
    lastWeek = lastWeek.subtract(`${days - 1}`, 'days')

    for (const item of data) {
      const timestamp = spacetime(item.timestamp)

      if (timestamp.isAfter(lastWeek) === true) {
        const timestampFormat = timestamp.format('{year}-{iso-month}-{date-pad}')

        if (groupKeys.includes(timestampFormat) === true) {
          const key = groupKeys.indexOf(timestampFormat)

          const group = groups[key]
          group.banCount++

          if (group.addresses.includes(item.address) === false) {
            group.ipCount++
            group.addresses.push(item.address)
          }

          groups[key] = group
        } else {
          const group = {
            date: timestampFormat,
            banCount: 1,
            ipCount: 1,
            addresses: [item.address]
          }

          groupKeys.push(timestampFormat)
          groups.push(group)
        }
      } else {
        break
      }
    }

    console.log(groups)

    return {
      labels: groupKeys.reverse(),
      datasets: this.#getDatasets(groups),
      type: chartType
    }
  }

  #getDatasets (groups) {
    const banCounts = []
    const ipCounts = []

    groups.reverse().forEach(g => {
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
}
