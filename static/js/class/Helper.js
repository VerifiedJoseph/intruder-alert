import { Button } from './Button.js'

export class Helper {
  /**
   * Create most banned buttons
   *
   * @param {object} data Data
   */
  static createMostBannedButtons (data) {
    const types = ['address', 'network', 'country', 'jail']

    types.forEach(type => {
      const span = document.getElementById(`most-${type}-button`)

      span.innerText = ''
      span.appendChild(
        Button.createView('recentBans', type, data[type].mostBanned, 'most-banned')
      )
    })
  }

  static setTableType (value) {
    document.getElementById('table-type').value = value
  }

  static getTableType () {
    return document.getElementById('table-type').value
  }

  static setChartType (value) {
    document.getElementById('chart-type').value = value
  }

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
