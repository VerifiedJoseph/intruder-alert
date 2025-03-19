export class Dataset {
  static #data = []
  static #recentBans = []

  /**
   * Initialisation dataset
   * @param {object} data
   */
  static init (data) {
    this.#data = data
    this.#recentBans = this.#generateRecentBans()
  }

  /**
   * Returns recent bans as an array
   * @returns {array}
   */
  static getRecentBans () {
    return this.#recentBans
  }

  /**
   * Returns data list for a type
   * @param {string} type List type
   * @returns {array}
   */
  static getList (type) {
    return this.#data[type].list
  }

  /**
   * Returns last updated date
   * @returns {string}
   */
  static getUpdatedDate () {
    return this.#data.updated
  }

  /**
   * Returns data version hash
   * @returns {string}
   */
  static getHash () {
    return this.#data.hash
  }

  /**
   * Returns backend daemon log
   * @returns {array}
   */
  static getDaemonLog () {
    return this.#data.log
  }

  /**
   * Returns data since date
   * @returns {string}
   */
  static getSinceDate () {
    return this.#data.dataSince
  }

  /**
   * Returns total count for a list
   * @param {string} type List type
   * @returns {int}
   */
  static getTotal (type) {
    return this.#data.stats.totals[type]
  }

  /**
   * Returns ban count for a list
   * @param {string} type List type
   * @returns {int}
   */
  static getBans (type) {
    return this.#data.stats.bans[type]
  }

  /**
   * Returns most banned for a list
   * @param {string} type List type
   * @returns {mixed}
   */
  static getMostBanned (type) {
    return this.#data[type].mostBanned
  }

  /**
   * Get a network name
   * @param {int} number Network number (ASN)
   * @returns {string}
   */
  static getNetworkName (number) {
    return this.getNetwork(number).name
  }

  /**
   * Get a country name
   * @param {string} code Two letter country code
   * @returns {string}
   */
  static getCountryName (code) {
    return this.getCountry(code).name
  }

  /**
   * Get a continent name
   * @param {string} code Two letter continent code
   * @returns {string}
   */
  static getContinentName (code) {
    return this.getContinent(code).name
  }

  /**
   * Get IP address details
   * @param {string} address IP address
   * @returns {object}
   */
  static getIp (address) {
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
  static getNetwork (number) {
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
  static getCountry (code) {
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
  static getContinent (code) {
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
  static getJail (name) {
    for (let i = 0; i < this.#data.jail.list.length; i++) {
      if (this.#data.jail.list[i].name === name) {
        return this.#data.jail.list[i]
      }
    }
  }

  /**
   * Generates array of recent bans
   * @returns {array}
   */
  static #generateRecentBans() {
    var bans = []

    this.#data.address.list.forEach(ip => {
      ip.events.forEach(event => {
        bans.push({
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

    bans.sort(function (a, b) {
      const da = new Date(a.timestamp).getTime()
      const db = new Date(b.timestamp).getTime()

      return da < db ? -1 : da > db ? 1 : 0
    })

    return bans.reverse()
  }
}
