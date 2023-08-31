import { Helper } from './Helper.js'

export class FilterPanel {
  #data = []
  #type = ''

  constructor (data = [], type = '') {
    this.#data = data
    this.#type = type
  }

  /**
   * Set select options for a filter type
   * @param {string} type Filter type
   */
  setFilterValues (type, filter) {
    const select = document.getElementById(this.#getId('filter-value'))
    select.innerText = ''

    let valueName = ''
    let textValueName = ''
    switch (type) {
      case 'address':
        valueName = 'address'
        textValueName = 'address'
        break
      case 'version':
        valueName = 'number'
        textValueName = 'number'
        break
      case 'subnet':
        valueName = 'subnet'
        textValueName = 'subnet'
        break
      case 'network':
        valueName = 'number'
        textValueName = 'name'
        break
      case 'country':
      case 'continent':
        valueName = 'code'
        textValueName = 'name'
        break
      case 'jail':
        valueName = 'name'
        textValueName = 'name'
        break
      case 'date':
        valueName = 'date'
        textValueName = 'date'
        break
    }

    let data = []
    if (type === 'version') {
      data = [{ number: 4 }, { number: 6 }]
    } else {
      data = this.#data[type].list
    }

    for (let index = 0; index < data.length; index++) {
      const item = data[index]

      const option = document.createElement('option')
      option.value = item[valueName]
      option.innerText = item[textValueName]

      if (filter.hasFilter(type, item[valueName]) === true) {
        option.disabled = true
      }

      select.appendChild(option)
    }
  }

  /**
   * Show filter panel
   */
  show () {
    document.getElementById(this.#getId('filter-panel')).classList.remove('hide')
    document.getElementById(this.#getId('filter-open-panel')).disabled = true
  }

  /**
   * Hide filter panel
   */
  hide () {
    document.getElementById(this.#getId('filter-panel')).classList.add('hide')
    document.getElementById(this.#getId('filter-open-panel')).disabled = false
  }

  /**
   * Setup filter panel
   * @param {Filter|ChartFilter} filter Filter class instance
   */
  setup (filter) {
    document.getElementById(this.#getId('filter-action'))[0].selected = true

    this.#enableAllFilters()

    if (this.#type === '') {
      if (Helper.getViewType() !== 'recentBans') {
        this.#disableFilter('address')
        this.#disableFilter('jail')
        this.#disableFilter('date')

        if (Helper.getViewType() === 'address') {
          this.#setSelectedFilter('version')
          this.setFilterValues('version', filter)
        }

        if (Helper.getViewType() === 'subnet') {
          this.#setSelectedFilter('subnet')
          this.setFilterValues('subnet', filter)
          this.#disableFilter('continent')
        }
      } else {
        this.#setSelectedFilter('address')
        this.setFilterValues('address', filter)
      }
    } else {
      this.#setSelectedFilter('address')
      this.setFilterValues('address', filter)
    }
  }

  /**
   * Disable a filter type
   * @param {string} name
   */
  #disableFilter (name) {
    const element = document.querySelector(`#${this.#getId('filter-type')} [value="${name}"]`)
    element.hidden = true
    element.disabled = true
  }

  /**
   * Enable a filter type
   * @param {string} name
   */
  #enableFilter (name) {
    const element = document.querySelector(`#${this.#getId('filter-type')} [value="${name}"]`)
    element.hidden = false
    element.disabled = false
  }

  /**
   * Enable all filter types
   */
  #enableAllFilters () {
    const elements = document.querySelectorAll(`#${this.#getId('filter-type')} [value]`)

    elements.forEach(element => {
      element.hidden = false
      element.disabled = false
    })
  }

  /**
   * Set selected filter type
   * @param {string} name
   */
  #setSelectedFilter (name) {
    document.querySelector(`#${this.#getId('filter-type')} [value="${name}"]`).selected = true
  }

  /**
   * Get element Id with prefix
   * @param {string} name
   */
  #getId (name) {
    if (this.#type !== '') {
      return `${this.#type}-${name}`
    }

    return name
  }
}
