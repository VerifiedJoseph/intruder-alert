import { Dataset } from './Dataset.js'
import { Settings } from './Settings.js'
import { Helper } from './Helper.js'

export class Display {
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
    document.getElementById('last-updated').innerText = Dataset.getUpdatedDate()
    document.getElementById('date-since').innerText = ` ${Dataset.getSinceDate()} (${Helper.formatNumber(Dataset.getTotal('date'))} days)`
    document.getElementById('dates').classList.remove('hide')
  }

  /**
   * Display version details in header
   */
  #headerVersion () {
    let version = Settings.getVersion()

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
    document.getElementById('total-bans').innerText = Helper.formatNumber(Dataset.getBans('total'))
    document.getElementById('bans-today').innerText = Helper.formatNumber(Dataset.getBans('today'))
    document.getElementById('bans-yesterday').innerText = Helper.formatNumber(Dataset.getBans('yesterday'))
    document.getElementById('bans-per-day').innerText = Helper.formatNumber(Dataset.getBans('perDay'))
    document.getElementById('total-ips').innerText = Helper.formatNumber(Dataset.getTotal('ip'))
    document.getElementById('total-networks').innerText = Helper.formatNumber(Dataset.getTotal('network'))
    document.getElementById('total-countries').innerText = Helper.formatNumber(Dataset.getTotal('country'))
    document.getElementById('total-jails').innerText = Helper.formatNumber(Dataset.getTotal('jail'))
  }

  /**
   * Display most banned details
   */
  #mostBanned () {
    const ip = Dataset.getIp(Dataset.getMostBanned('address'))
    const network = Dataset.getNetwork(Dataset.getMostBanned('network'))
    const country = Dataset.getCountry(Dataset.getMostBanned('country'))
    const jail = Dataset.getJail(Dataset.getMostBanned('jail'))

    document.getElementById('most-banned-ip').innerText = ip.address
    document.getElementById('most-banned-ip').setAttribute('title', ip.address)
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

    if (Settings.isDaemonLogEnabled() === true) {
      Dataset.getDaemonLog().forEach(item => {
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
