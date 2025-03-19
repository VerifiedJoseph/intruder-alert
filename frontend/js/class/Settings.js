export class Settings {
  static #settings = []

  /**
   * Initialisation settings
   * @param {object} settings
   */
  static init (settings) {
    this.#settings = settings
  }

  /**
   * Returns default chart
   * @returns {string}
   */
  static getDefaultChart () {
    return this.#settings.defaults.chart
  }

  /**
   * Returns default table page size
   * @returns {int}
   */
  static getDefaultPageSize () {
    return this.#settings.defaults.pageSize
  }

  /**
   * Returns timezone
   * @returns {string}
   */
  static getTimezone () {
    return this.#settings.timezone
  }

  /**
   * Returns Intruder Alert version
   * @returns {string}
   */
  static getVersion () {
    return this.#settings.version
  }

  /**
   * Check if charts are enabled
   * @returns {boolean}
   */
  static isChartEnabled () {
    return this.#settings.features.charts
  }

  /**
   * Check if dashboard updating is enabled
   * @returns {boolean}
   */
  static isUpdatingEnabled () {
    return this.#settings.features.updates
  }

  /**
   * Check if displaying the daemon log is enabled
   * @returns {boolean}
   */
  static isDaemonLogEnabled () {
    return this.#settings.features.daemonLog
  }
}
