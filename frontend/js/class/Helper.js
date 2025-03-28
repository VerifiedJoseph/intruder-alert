import { Dataset } from './Dataset.js'

export class Helper {
  /**
   * Create most banned buttons
   */
  static createMostBannedButtons () {
    const types = ['address', 'network', 'country', 'jail']

    types.forEach(type => {
      const span = document.getElementById(`most-${type}-button`)

      span.innerText = ''
      span.appendChild(
        Helper.createViewBtn('recentBans', type, Dataset.getMostBanned(type), 'most-banned')
      )
    })
  }

  /**
   * Create a data view button
   * @param {string} viewType Data view type
   * @param {string} filterType Filter type
   * @param {string} filterValue Filter value
   * @param {string} context Context the button is being used
   * @returns HTMLButtonElement
   */
  static createViewBtn (viewType, filterType, filterValue, context = 'table') {
    const button = document.createElement('button')

    button.innerText = (viewType === 'address') ? 'View IPs' : 'View Bans'
    button.classList.add('view-button')
    button.setAttribute('data-view-type', viewType)
    button.setAttribute('data-filter-type', filterType)
    button.setAttribute('data-filter-value', filterValue)
    button.setAttribute('data-context', context)
    return button
  }

  /**
   * Set table type
   * @param {string} value
   */
  static setTableType (value) {
    document.getElementById('table-type').value = value
  }

  /**
   * Returns table type
   * @returns {string}
   */
  static getTableType () {
    return document.getElementById('table-type').value
  }

  /**
   * Set chart type
   * @param {string} value
   */
  static setChartType (value) {
    document.getElementById('chart-type').value = value
  }

  /**
   * Returns chart type
   * @returns {string}
   */
  static getChartType () {
    return document.getElementById('chart-type').value
  }

  /**
   * Display a error message
   * @param {string} text Message
   * @param {boolean} hideAfter Hide message after 5 seconds
   */
  static errorMessage (text, hideAfter = false) {
    const error = document.getElementById('error')
    error.classList.remove('hide')
    error.innerText = text

    if (hideAfter === true) {
      setTimeout(() => {
        error.classList.add('hide')
      }, 5000)
    }
  }

  /**
   * Format a number using `Intl.NumberFormat`
   * @param {int} number
   * @returns {string}
   */
  static formatNumber (number) {
    return new Intl.NumberFormat().format(number)
  }

  /**
   * Capitalize first character of a string
   * @param {string} text
   * @returns {string}
   */
  static capitalizeFirstChar (text) {
    return text.charAt(0).toUpperCase() + text.slice(1)
  }
}
