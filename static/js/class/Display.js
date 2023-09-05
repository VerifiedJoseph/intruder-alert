import { Format } from './Format.js'

export class Display {
  #iaData

  /**
   *
   * @param {iaData} iaData iaData class instance
   */
  constructor (iaData) {
    this.#iaData = iaData
  }

  render () {
    this.#headerDates()
    this.#globalStats()
    this.#mostBanned()
    this.#daemonLog()
  }

  #headerDates () {
    document.getElementById('last-updated').innerText = this.#iaData.getUpdatedDate()
    document.getElementById('date-since').innerText = ` ${this.#iaData.getSinceDate()} (${Format.Number(this.#iaData.getTotal('date'))} days)`
    document.getElementById('dates').classList.remove('hide')
  }

  #globalStats () {
    document.getElementById('total-bans').innerText = Format.Number(this.#iaData.getBans('total'))
    document.getElementById('bans-today').innerText = Format.Number(this.#iaData.getBans('today'))
    document.getElementById('bans-yesterday').innerText = Format.Number(this.#iaData.getBans('yesterday'))
    document.getElementById('bans-per-day').innerText = Format.Number(this.#iaData.getBans('perDay'))
    document.getElementById('total-ips').innerText = Format.Number(this.#iaData.getTotal('ip'))
    document.getElementById('total-networks').innerText = Format.Number(this.#iaData.getTotal('network'))
    document.getElementById('total-countries').innerText = Format.Number(this.#iaData.getTotal('country'))
    document.getElementById('total-jails').innerText = Format.Number(this.#iaData.getTotal('jail'))
    document.getElementById('global-stats').classList.remove('hide')
  }

  #mostBanned () {
    const ip = this.#iaData.getIp(this.#iaData.getMostBanned('address'))
    const network = this.#iaData.getNetwork(this.#iaData.getMostBanned('network'))
    const country = this.#iaData.getCountry(this.#iaData.getMostBanned('country'))
    const jail = this.#iaData.getJail(this.#iaData.getMostBanned('jail'))

    document.getElementById('most-banned-ip').innerText = ip.address
    document.getElementById('most-banned-ip-count').innerText = Format.Number(ip.bans)

    document.getElementById('most-seen-network').innerText = network.name
    document.getElementById('most-seen-network').setAttribute('title', network.name)
    document.getElementById('most-seen-network-count').innerText = Format.Number(network.bans)

    document.getElementById('most-seen-country').innerText = country.name
    document.getElementById('most-seen-country').setAttribute('title', country.name)
    document.getElementById('most-seen-country-count').innerText = Format.Number(country.bans)

    document.getElementById('most-activated-jail').innerText = jail.name
    document.getElementById('most-activated-jail').setAttribute('title', jail.name)
    document.getElementById('most-activated-jail-count').innerText = Format.Number(jail.bans)
    document.getElementById('most-banned').classList.remove('hide')
  }

  #daemonLog () {
    const div = document.getElementById('log-entries')
    div.innerText = ''

    this.#iaData.getDaemonLog().forEach(item => {
      const entry = document.createElement('p')
      entry.innerText = item

      div.appendChild(entry)
    })

    document.getElementById('log').classList.remove('hide')
  }
}
