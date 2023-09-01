import { Format } from './Format.js'

export class Display {
  #data

  /**
   *
   * @param {Data} data Data class instance
   */
  constructor (data) {
    this.#data = data
  }

  headerDates () {
    document.getElementById('last-updated').innerText = this.#data.getUpdatedDate()
    document.getElementById('data-since').innerText = ` ${this.#data.getSinceDate()} (${Format.Number(this.#data.getTotal('date'))} days)`
    document.getElementById('dates').classList.remove('hide')
  }

  globalStats () {
    document.getElementById('total-bans').innerText = Format.Number(this.#data.getBans('total'))
    document.getElementById('bans-today').innerText = Format.Number(this.#data.getBans('today'))
    document.getElementById('bans-yesterday').innerText = Format.Number(this.#data.getBans('yesterday'))
    document.getElementById('bans-per-day').innerText = Format.Number(this.#data.getBans('perDay'))
    document.getElementById('total-ips').innerText = Format.Number(this.#data.getTotal('ip'))
    document.getElementById('total-networks').innerText = Format.Number(this.#data.getTotal('network'))
    document.getElementById('total-countries').innerText = Format.Number(this.#data.getTotal('country'))
    document.getElementById('total-jails').innerText = Format.Number(this.#data.getTotal('jail'))
    document.getElementById('global-stats').classList.remove('hide')
  }

  mostBanned () {
    const ip = this.#data.getIp(this.#data.getMostBanned('address'))
    const network = this.#data.getNetwork(this.#data.getMostBanned('network'))
    const country = this.#data.getCountry(this.#data.getMostBanned('country'))
    const jail = this.#data.getJail(this.#data.getMostBanned('jail'))

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

  daemonLog () {
    const div = document.getElementById('log-entries')

    this.#data.getDaemonLog().forEach(item => {
      const entry = document.createElement('p')
      entry.innerText = item

      div.appendChild(entry)
    })

    document.getElementById('log').classList.remove('hide')
  }
}
