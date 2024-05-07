import { Helper } from './Helper.js'

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
    this.#headerVersion()
    this.#globalStats()
    this.#mostBanned()
    this.#daemonLog()
  }

  /**
   * Display last updated and since data in header
   */
  #headerDates () {
    document.getElementById('last-updated').innerText = this.#iaData.getUpdatedDate()
    document.getElementById('date-since').innerText = ` ${this.#iaData.getSinceDate()} (${Helper.formatNumber(this.#iaData.getTotal('date'))} days)`
    document.getElementById('dates').classList.remove('hide')
  }

  /**
   * Display version details in header
   */
  #headerVersion () {
    let version = this.#iaData.getVersion()

    if (version !== '') {
      if (version.charAt(0) !== 'v') {
        version = `v${version}`
      }

      const link = document.createElement('a')
      link.setAttribute('href', `https://github.com/VerifiedJoseph/intruder-alert/releases/tag/${version}`)
      link.setAttribute('title', `View release notes for ${version} on Github`)
      link.setAttribute('target', '_blank')
      link.innerText = version

      document.getElementById('version').innerText = ''
      document.getElementById('version').appendChild(link)
    }
  }

  /**
   * Display global stats
   */
  #globalStats () {
    document.getElementById('total-bans').innerText = Helper.formatNumber(this.#iaData.getBans('total'))
    document.getElementById('bans-today').innerText = Helper.formatNumber(this.#iaData.getBans('today'))
    document.getElementById('bans-yesterday').innerText = Helper.formatNumber(this.#iaData.getBans('yesterday'))
    document.getElementById('bans-per-day').innerText = Helper.formatNumber(this.#iaData.getBans('perDay'))
    document.getElementById('total-ips').innerText = Helper.formatNumber(this.#iaData.getTotal('ip'))
    document.getElementById('total-networks').innerText = Helper.formatNumber(this.#iaData.getTotal('network'))
    document.getElementById('total-countries').innerText = Helper.formatNumber(this.#iaData.getTotal('country'))
    document.getElementById('total-jails').innerText = Helper.formatNumber(this.#iaData.getTotal('jail'))
  }

  /**
   * Display most banned details
   */
  #mostBanned () {
    const ip = this.#iaData.getIp(this.#iaData.getMostBanned('address'))
    const network = this.#iaData.getNetwork(this.#iaData.getMostBanned('network'))
    const country = this.#iaData.getCountry(this.#iaData.getMostBanned('country'))
    const jail = this.#iaData.getJail(this.#iaData.getMostBanned('jail'))

    document.getElementById('most-banned-ip').innerText = ip.address
    document.getElementById('most-banned-ip-count').innerText = Helper.formatNumber(ip.bans)

    document.getElementById('most-seen-network').innerText = network.name
    document.getElementById('most-seen-network').setAttribute('title', network.name)
    document.getElementById('most-seen-network-count').innerText = Helper.formatNumber(network.bans)

    document.getElementById('most-seen-country').innerText = country.name
    document.getElementById('most-seen-country').setAttribute('title', country.name)
    document.getElementById('most-seen-country-count').innerText = Helper.formatNumber(country.bans)

    document.getElementById('most-activated-jail').innerText = jail.name
    document.getElementById('most-activated-jail').setAttribute('title', jail.name)
    document.getElementById('most-activated-jail-count').innerText = Helper.formatNumber(jail.bans)
  }

  /**
   * Display daemon log
   */
  #daemonLog () {
    const div = document.getElementById('log-entries')
    div.innerText = ''

    if (this.#iaData.isDaemonLogEnabled() === true) {
      this.#iaData.getDaemonLog().forEach(item => {
        const entry = document.createElement('div')
        entry.innerText = item

        div.appendChild(entry)
      })

      document.getElementById('log').classList.remove('hide')
    } else {
      document.getElementById('log').classList.add('hide')
    }
  }
}
