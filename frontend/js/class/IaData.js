export class IaData {
  #data = []
  #recentBans = []

  constructor (data = []) {
    this.#data = data
  }

  /**
   * Get recent bans
   * @returns {array}
   */
  getRecentBans () {
    if (this.#recentBans.length > 0) {
      return this.#recentBans
    }

    this.#data.address.list.forEach(ip => {
      ip.events.forEach(event => {
        this.#recentBans.push({
          address: ip.address,
          version: ip.version,
          jail: event.jail,
          subnet: ip.subnet,
          network: ip.network,
          country: ip.country,
          continent: ip.continent,
          timestamp: event.timestamp
        })
      })
    })

    this.#recentBans.sort(function (a, b) {
      const da = new Date(a.timestamp).getTime()
      const db = new Date(b.timestamp).getTime()

      return da < db ? -1 : da > db ? 1 : 0
    })

    return this.#recentBans.reverse()
  }

  /**
   * Get data list
   * @param {string} type List type
   * @returns {array}
   */
  getList (type) {
    return this.#data[type].list
  }

  /**
   * Get timezone
   * @returns {string}
   */
  getTimezone () {
    return this.#data.settings.timezone
  }

  /**
   * Get version
   * @returns {string}
   */
  getVersion () {
    return this.#data.settings.version
  }

  /**
   * Get last updated date
   * @returns {string}
   */
  getUpdatedDate () {
    return this.#data.updated
  }

  /**
   * Get hash
   * @returns {string}
   */
  getHash () {
    return this.#data.hash
  }

  /**
   * Get data since date
   * @returns {string}
   */
  getSinceDate () {
    return this.#data.dataSince
  }

  /**
   * Check if charts are enabled
   * @returns {boolean}
   */
  isChartEnabled () {
    return this.#data.settings.features.charts
  }

  /**
   * Check if dashboard updating is enabled
   * @returns {boolean}
   */
  isUpdatingEnabled () {
    return this.#data.settings.features.updates
  }

  /**
   * Check if displaying the daemon log is enabled
   * @returns {boolean}
   */
  isDaemonLogEnabled () {
    return this.#data.settings.features.daemonLog
  }

  /**
   * Get total count for a list
   * @param {string} type List type
   * @returns {int}
   */
  getTotal (type) {
    return this.#data.stats.totals[type]
  }

  /**
   * Get ban count for a list
   * @param {string} type List type
   * @returns {int}
   */
  getBans (type) {
    return this.#data.stats.bans[type]
  }

  /**
   * Get most banned for a list
   * @param {string} type List type
   * @returns {mixed}
   */
  getMostBanned (type) {
    return this.#data[type].mostBanned
  }

  /**
   * Get backend daemon log
   * @returns {array}
   */
  getDaemonLog () {
    return this.#data.log
  }

  /**
   * Get default chart
   */
  getDefaultChart () {
    return this.#data.settings.defaults.chart
  }

  /**
   * Get a network name
   * @param {int} number Network number (ASN)
   * @returns {string}
   */
  getNetworkName (number) {
    return this.getNetwork(number).name
  }

  /**
   * Get a country name
   * @param {string} code Two letter country code
   * @returns {string}
   */
  getCountryName (code) {
    return this.getCountry(code).name
  }

  /**
   * Get a continent name
   * @param {string} code Two letter continent code
   * @returns {string}
   */
  getContinentName (code) {
    return this.getContinent(code).name
  }

  /**
   * Get IP address details
   * @param {string} address IP address
   * @returns {object}
   */
  getIp (address) {
    for (let i = 0; i < this.#data.address.list.length; i++) {
      if (this.#data.address.list[i].address === address) {
        return this.#data.address.list[i]
      }
    }
  }

  /**
   * Get network details
   * @param {int} number Network number (ASN)
   * @returns {object}
   */
  getNetwork (number) {
    for (let i = 0; i < this.#data.network.list.length; i++) {
      if (this.#data.network.list[i].number.toString() === number.toString()) {
        return this.#data.network.list[i]
      }
    }
  }

  /**
   * Get country details
   * @param {string} code Two letter country code
   * @returns {object}
   */
  getCountry (code) {
    for (let i = 0; i < this.#data.country.list.length; i++) {
      if (this.#data.country.list[i].code === code) {
        return this.#data.country.list[i]
      }
    }
  }

  /**
   * Get continent details
   * @param {string} code Two letter continent code
   * @returns {object}
   */
  getContinent (code) {
    for (let i = 0; i < this.#data.continent.list.length; i++) {
      if (this.#data.continent.list[i].code === code) {
        return this.#data.continent.list[i]
      }
    }
  }

  /**
   * Get jail details
   * @param {string} name Jail name
   * @returns {object}
   */
  getJail (name) {
    for (let i = 0; i < this.#data.jail.list.length; i++) {
      if (this.#data.jail.list[i].name === name) {
        return this.#data.jail.list[i]
      }
    }
  }
}
