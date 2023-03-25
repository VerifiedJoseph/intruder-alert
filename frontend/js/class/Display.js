import { Format } from './Format.js'
import { Details } from './Details.js'

export class Display {
  #data = []
  #details

  constructor (data = []) {
    this.#data = data
    this.#details = new Details(data)
  }

  headerDates () {
    document.getElementById('last-updated').innerText = this.#data.updated
    document.getElementById('data-since').innerText = ` ${this.#data.dataSince} (${Format.Number(this.#data.stats.totals.date)} days)`
    document.getElementById('dates').classList.remove('hide')
  }

  globalStats () {
    document.getElementById('total-bans').innerText = Format.Number(this.#data.stats.bans.total)
    document.getElementById('bans-today').innerText = Format.Number(this.#data.stats.bans.today)
    document.getElementById('bans-yesterday').innerText = Format.Number(this.#data.stats.bans.yesterday)
    document.getElementById('bans-per-day').innerText = Format.Number(this.#data.stats.bans.perDay)
    document.getElementById('total-ips').innerText = Format.Number(this.#data.stats.totals.ip)
    document.getElementById('total-jails').innerText = Format.Number(this.#data.stats.totals.jail)
    document.getElementById('total-networks').innerText = Format.Number(this.#data.stats.totals.network)
    document.getElementById('total-countries').innerText = Format.Number(this.#data.stats.totals.country)
    document.getElementById('global-stats').classList.remove('hide')
  }

  mostBanned () {
    const ip = this.#details.getIp(this.#data.address.mostBanned)
    const network = this.#details.getNetwork(this.#data.network.mostBanned)
    const country = this.#details.getCountry(this.#data.country.mostBanned)
    const jail = this.#details.getJail(this.#data.jail.mostBanned)

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
}
