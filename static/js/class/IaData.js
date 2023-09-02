export class IaData {
  #data = []
  #recentBans = []

  constructor (data = []) {
    this.#data = data
  }

  /**
   * Get recent bans
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

  getList (type) {
    return this.#data[type].list
  }

  getTimezone () {
    return this.#data.settings.timezone
  }

  getUpdatedDate () {
    return this.#data.updated
  }

  getSinceDate () {
    return this.#data.dataSince
  }

  getTotal (type) {
    return this.#data.stats.totals[type]
  }

  getBans (type) {
    return this.#data.stats.bans[type]
  }

  getMostBanned (type) {
    return this.#data[type].mostBanned
  }

  getDaemonLog () {
    return this.#data.log
  }

  getNetworkName (number) {
    return this.getNetwork(number).name
  }

  getCountryName (code) {
    return this.getCountry(code).name
  }

  getContinentName (code) {
    return this.getContinent(code).name
  }

  getIp (address) {
    for (let i = 0; i < this.#data.address.list.length; i++) {
      if (this.#data.address.list[i].address === address) {
        return this.#data.address.list[i]
      }
    }
  }

  getNetwork (number) {
    for (let i = 0; i < this.#data.network.list.length; i++) {
      if (this.#data.network.list[i].number.toString() === number.toString()) {
        return this.#data.network.list[i]
      }
    }
  }

  getCountry (code) {
    for (let i = 0; i < this.#data.country.list.length; i++) {
      if (this.#data.country.list[i].code === code) {
        return this.#data.country.list[i]
      }
    }
  }

  getContinent (code) {
    for (let i = 0; i < this.#data.continent.list.length; i++) {
      if (this.#data.continent.list[i].code === code) {
        return this.#data.continent.list[i]
      }
    }
  }

  getJail (name) {
    for (let i = 0; i < this.#data.jail.list.length; i++) {
      if (this.#data.jail.list[i].name === name) {
        return this.#data.jail.list[i]
      }
    }
  }
}
