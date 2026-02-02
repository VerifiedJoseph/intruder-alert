import { Settings } from '../Settings.js'
import { Dataset } from '../Dataset.js'
import { Filter } from './Filter.js'
import { FilterChip } from '../FilterChip.js'

export class ChartFilter extends Filter {
  constructor () {
    super()
    this.chip = new FilterChip('chart')
  }

  /**
   * Get filtered data
   * @param {string} chartType
   * @returns
   */
  getData (chartType) {
    const data = Dataset.getRecentBans()

    if (this.filters.length > 0) {
      return this.#groupData(this._getFilteredData(data), chartType)
    }

    if (this.filters.length === 0 && (chartType === 'last14days' || chartType === 'last30days')) {
      return this.#createDaysFromDateList(Dataset.getList('date'), chartType)
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

    if (chartType === 'last30days') {
      let days = 30
      if (Dataset.getTotal('date') < 30) {
        days = Dataset.getTotal('date')
      }

      return this.#groupByDay(data, days, chartType)
    }

    if (chartType === 'last14days') {
      return this.#groupByDay(data, 14, chartType)
    }

    if (chartType === 'last48hours') {
      return this.#groupByHour(data, 48, chartType)
    }

    return this.#groupByHour(data, 24, chartType)
  }

  /**
   * Group data by hour
   * @param {array} data Data to group
   * @param {int} hours Number of hours in data group
   * @param {string} chartType Chart type
   */
  #groupByHour (data, hours, chartType) {
    const groupParts = this.#createHourGroups(hours)
    const groupKeys = groupParts[0]
    const groups = groupParts[1]

    for (const item of data) {
      const parts = item.timestamp.split(':')
      const key = groupKeys.indexOf(parts[0] + ':00')

      if (groups[key]) {
        groups[key].banCount++

        if (groups[key].addresses.includes(item.address) === false) {
          groups[key].ipCount++
          groups[key].addresses.push(item.address)
        }
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
   * @param {int} days Number of days in data group
   * @param {string} chartType Chart type
   */
  #groupByDay (data, days, chartType) {
    const groupParts = this.#createDayGroups(days)
    const groupKeys = groupParts[0]
    const groups = groupParts[1]

    for (const item of data) {
      const parts = item.timestamp.split(' ')
      const key = groupKeys.indexOf(parts[0])

      if (groups[key]) {
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

  /**
   * Create hour groups
   * @param {int} hours Number of hours
   */
  #createHourGroups (hours) {
    let hour = Temporal.Now.plainDateTimeISO(Settings.getTimezone())
    hour = hour.subtract({ hours: hours});

    const groups = []
    const keys = []

    for (let i = 1; i <= hours; i++) {
      if (i > 1) {
        hour = hour.add({hours: 1})
      }

      const hourFormatted = this.#formatDateTimeTostring(hour, true)

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

  /**
   * Create day groups
   * @param {int} days Number of days
   */
  #createDayGroups (days) {
    let date = Temporal.Now.plainDateTimeISO(Settings.getTimezone())
    date = date.subtract({ days: days});

    const groups = []
    const keys = []

    for (let i = 1; i <= days; i++) {
      date = date.add({days: 1})
      const dateFormatted = this.#formatDateTimeTostring(date)

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

  #createDaysFromDateList (data, chartType) {
    if (data.length === 0) {
      return { hasData: false }
    }

    let days = 30
    if (chartType === 'last14days') {
      days = 14
    }

    const groupParts = this.#createDayGroups(days)
    const groupKeys = groupParts[0]
    const groups = groupParts[1]

    for (const item of data) {
      const key = groupKeys.indexOf(item.date)

      if (groups[key]) {
        groups[key].banCount = item.bans
        groups[key].ipCount = item.ipCount
      }
    }

    return {
      labels: groupKeys,
      datasets: this.#getDatasets(groups),
      type: chartType,
      hasData: true
    }
  }

  #formatDateTimeTostring (dateTime, includeHour = false)
  {
    var text = `${dateTime.year}-${this.#padNumber(dateTime.month)}-${this.#padNumber(dateTime.day)}`

    if (includeHour === true) {
      text += ` ${this.#padNumber(dateTime.hour)}:00`
    }

    return text;
  }

  #padNumber (number)
  {
    return String(number).padStart(2, '0');
  }
}
