import { Details } from './Details.js'

export class Filter {
  #supportedListTypes = ['address', 'recentBans', 'subnet']
  #data = []
  #settings = []
  #details

  constructor (data = []) {
    this.#data = data
    this.#settings = []
    this.#details = new Details(data)
  }

  /**
   * Get filtered data
   * @param {string} typeList
   * @returns
   */
  getData (listType) {
    let data

    if (listType === 'recentBans') {
      data = this.#getRecentBans()
    } else {
      data = this.#data[listType].list
    }

    if (this.#settings.length > 0 && this.#supportedListTypes.includes(listType) === true) {
      const filtered = []

      data.forEach(item => {
        const addStatus = []

        for (let index = 0; index < this.#settings.length; index++) {
          const filter = this.#settings[index]

          if (filter.type === 'jail' && listType !== 'recentBans') {
            continue
          }

          let value
          if (filter.type === 'date') {
            const date = new Date(item.timestamp)
            const parts = date.toISOString().substring(0, 10).split('-')

            value = `${parts[0]}-${parts[1]}-${parts[2]}`
          } else {
            value = item[filter.type].toString()
          }

          if (filter.action === 'include' && filter.values.length > 0) {
            if (filter.values.includes(value) === true) {
              addStatus.push(1)
            } else {
              addStatus.push(0)
            }
          }

          if (filter.action === 'exclude' && filter.values.length > 0) {
            if (filter.values.includes(value) === true) {
              addStatus.push(0)
            } else {
              addStatus.push(1)
            }
          }
        }

        if (addStatus.includes(0) === false) {
          filtered.push(item)
        }
      })

      return filtered
    }

    return data
  }

  /**
   * Add filter
   * @param {string} type Filter type
   * @param {string} action Filter action (include or exclude)
   * @param {string} value Filter value
   */
  add (type, action, value) {
    const index = this.#findFilter(type, action)

    if (index !== false) {
      this.#settings[index].values.push(value)
      this.#createLabel(type, action, value, this.#settings[index].id)
    } else {
      const id = crypto.randomUUID()

      this.#settings.push({
        id,
        type,
        action,
        values: [value]
      })

      this.#createLabel(type, action, value, id)
    }
  }

  /**
   * Remove filter by its type
   * @param {string} type filter type
   */
  remove (type) {
    let id = null

    this.#settings = this.#settings.filter(filter => {
      if (filter.type === type) {
        id = filter.id
        return false
      }

      return true
    })

    if (id !== null) {
      this.#removeLabel(id)
    }
  }

  /**
   * Remove value from a filter
   * @param {string} filterId filter UUID
   * @param {value} value filter value
   */
  removeValue (filterId, value) {
    const index = this.#findFilterByUUID(filterId)
    const filter = this.#settings[index]

    this.#settings[index].values = filter.values.filter(
      item => item !== value
    )
  }

  /**
   * Reset filters
   */
  reset () {
    this.#settings = []
    document.getElementById('applied-filters').innerText = ''
  }

  /**
   * Check if filter has a value
   * @param {string} type Filter type
   * @param {string} value Filter value
   * @returns
   */
  hasFilter (type, value) {
    let status = false

    this.#settings.forEach(filter => {
      if (filter.type === type && filter.values.includes(value.toString()) === true) {
        status = true
      }
    })

    return status
  }

  /**
   * Find filter array index by a filter's unique identifier
   * @param {string} uuid Unique identifier
   * @returns Array index of filter
   */
  #findFilterByUUID (uuid) {
    let key = null

    this.#settings.forEach((filter, index) => {
      if (filter.id === uuid) {
        key = index
      }
    })

    if (key !== null) {
      return key
    }

    return false
  }

  #findFilter (type, action) {
    let key = null

    this.#settings.forEach((filter, index) => {
      if (filter.type === type && filter.action === action) {
        key = index
      }
    })

    if (key !== null) {
      return key
    }

    return false
  }

  /**
   * Get recent bans
   */
  #getRecentBans () {
    const events = []

    this.#data.address.list.forEach(ip => {
      ip.events.forEach(event => {
        events.push({
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

    events.sort(function (a, b) {
      const da = new Date(a.timestamp).getTime()
      const db = new Date(b.timestamp).getTime()

      return da < db ? -1 : da > db ? 1 : 0
    })

    return events.reverse()
  }

  /**
   * Create filter label
   * @param {string} type Filter type
   * @param {string} action Filter action
   * @param {string} value Filter value
   * @param {string} uuid Filter UUID
   */
  #createLabel (type, action, value, uuid) {
    const labelCon = document.getElementById('applied-filters')
    const div = document.createElement('div')
    const typeSpan = document.createElement('span')
    const actionSpan = document.createElement('span')
    const valueSpan = document.createElement('span')
    const button = document.createElement('button')

    const typeTexts = {
      address: 'IP Address',
      version: 'IP Version',
      subnet: 'Subnet',
      network: 'Network',
      country: 'Country',
      continent: 'Continent',
      jail: 'Jail',
      date: 'Date'
    }

    let valueText = value
    if (type === 'network') {
      const network = this.#details.getNetwork(value)
      valueText = network.name
    }

    if (type === 'country') {
      const country = this.#details.getCountry(value)
      valueText = country.name
    }

    if (type === 'continent') {
      const continent = this.#details.getContinent(value)
      valueText = continent.name
    }

    let actionText = 'is'
    if (action === 'exclude') {
      actionText = 'is not'
    }

    typeSpan.innerText = `${typeTexts[type]} `
    actionSpan.innerText = `${actionText} `
    valueSpan.innerText = valueText
    actionSpan.classList.add('action')

    button.innerText = 'X'
    button.setAttribute('title', 'Remove filter')
    button.setAttribute('data-filter-id', uuid)
    button.setAttribute('data-filter-value', value)

    div.appendChild(typeSpan)
    div.appendChild(actionSpan)
    div.appendChild(valueSpan)
    div.appendChild(button)
    div.classList.add('item')
    div.setAttribute('title', `${typeTexts[type]} ${actionText} ${valueText}`)
    div.setAttribute('data-label-id', uuid)

    labelCon.appendChild(div)
  }

  /**
   * Remove filter label
   * @param {string} uuid Filter UUID
   */
  #removeLabel (uuid) {
    if (document.querySelector(`div[data-label-id="${uuid}"]`)) {
      document.querySelector(`div[data-label-id="${uuid}"]`).remove()
    }
  }
}
