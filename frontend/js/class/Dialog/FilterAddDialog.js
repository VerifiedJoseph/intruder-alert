import { ViewGroupDialogs } from './ViewGroupDialogs.js'
import { Helper } from '../Helper.js'

export class FilterAddDialog extends ViewGroupDialogs {
  dialogId = 'filter-add'
  #iaData

  #actionSelectOptions = [
    {
      value: 'include',
      name: 'is'
    },
    {
      value: 'exclude',
      name: 'is not'
    }
  ]

  #typeSelectOptions = [
    {
      value: 'address',
      name: 'IP address',
      chart: true,
      table: true
    },
    {
      value: 'version',
      name: 'IP version',
      chart: true,
      table: true
    },
    {
      value: 'subnet',
      name: 'Subnet',
      chart: true,
      table: true
    },
    {
      value: 'network',
      name: 'Network',
      chart: true,
      table: true
    },
    {
      value: 'country',
      name: 'Country',
      chart: true,
      table: true
    },
    {
      value: 'continent',
      name: 'Continent',
      chart: true,
      table: true
    },
    {
      value: 'jail',
      name: 'Jail',
      chart: true,
      table: true
    },
    {
      value: 'date',
      name: 'Date',
      chart: false,
      table: true
    },
    {
      value: 'hour',
      name: 'Time / Hour',
      chart: false,
      table: true
    },
    {
      value: 'minute',
      name: 'Time / Minute',
      chart: false,
      table: true
    },
    {
      value: 'second',
      name: 'Time / Second',
      chart: false,
      table: true
    }
  ]

  constructor (viewGroup, iaData) {
    super(viewGroup)
    this.#iaData = iaData
  }

  /**
   * Setup dialog
   * @param {Filter|ChartFilter} filter Filter class instance
   */
  setup (filter) {
    this.#setupElements()

    if (this.viewType === 'table') {
      if (Helper.getTableType() !== 'recentBans') {
        this.#disableFilter('address')
        this.#disableFilter('jail')
        this.#disableFilter('date')
        this.#disableFilter('hour')
        this.#disableFilter('minute')
        this.#disableFilter('second')

        if (Helper.getTableType() === 'address') {
          this.#setSelectedFilter('version')
          this.setFilterValues('version', filter)
        }

        if (Helper.getTableType() === 'subnet') {
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

  #setupElements () {
    const header = this.createHeader(`New ${this.viewGroup} filter`)

    // Select group
    const selectGroup = document.createElement('div')
    selectGroup.classList.add('selects')

    const typeSelect = document.createElement('select')
    typeSelect.setAttribute('id', `${this.viewGroup}-filter-type`)
    typeSelect.classList.add('filter-type')

    const actionSelect = document.createElement('select')
    actionSelect.setAttribute('id', `${this.viewGroup}-filter-action`)
    actionSelect.classList.add('filter-action')

    const valueSelect = document.createElement('select')
    valueSelect.setAttribute('id', `${this.viewGroup}-filter-value`)
    valueSelect.classList.add('filter-value')

    // Type select options
    this.#typeSelectOptions.forEach(item => {
      if (item[this.viewGroup] === true) {
        const opt = document.createElement('option')
        opt.value = item.value
        opt.innerText = item.name

        typeSelect.appendChild(opt)
      }
    })

    // Action select options
    this.#actionSelectOptions.forEach(item => {
      const opt = document.createElement('option')
      opt.value = item.value
      opt.innerText = item.name

      actionSelect.appendChild(opt)
    })

    selectGroup.appendChild(typeSelect)
    selectGroup.appendChild(actionSelect)
    selectGroup.appendChild(valueSelect)

    // Apply button
    const applyBtn = document.createElement('button')
    applyBtn.innerText = 'Apply'
    applyBtn.setAttribute('id', 'dialog-filter-apply')
    applyBtn.setAttribute('data-view-group', this.viewGroup)

    const buttonGroup = document.createElement('div')
    buttonGroup.classList.add('buttons')
    buttonGroup.appendChild(applyBtn)
    buttonGroup.appendChild(
      this.createCloseButton('Cancel', `${this.viewGroup}-filter-add`)
    )

    const dialog = document.getElementById('main-dialog')
    dialog.innerText = ''
    dialog.appendChild(header)
    dialog.appendChild(selectGroup)
    dialog.appendChild(buttonGroup)
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
      default:
        valueName = type
        textValueName = type
    }

    let data = []
    if (type === 'version') {
      data = [{ number: 4 }, { number: 6 }]
    } else if (type === 'hour') {
      data = this.#getHourList()
    } else if (type === 'minute') {
      data = this.#getMinuteList()
    } else if (type === 'second') {
      data = this.#getSecondList()
    } else {
      data = this.#iaData.getList(type)
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
   * Set selected filter type
   * @param {string} name
   */
  #setSelectedFilter (name) {
    document.querySelector(`#${this.#getId('filter-type')} [value="${name}"]`).selected = true
  }

  /**
   * Get element Id with view group prefix
   * @param {string} name
   */
  #getId (name) {
    console.log(`${this.viewGroup}-${name}`)

    return `${this.viewGroup}-${name}`
  }

  /**
   * Get list of hours in 24 hour format
   * @returns {array}
   */
  #getHourList () {
    const hours = []

    for (let i = 0; i <= 23; i++) {
      let value = i.toString()

      if (i <= 9) {
        value = `0${i}`
      }

      hours.push({ hour: value })
    }

    return hours
  }

  /**
   * @returns Get list of minutes (00 to 60)
   * @returns {array}
   */
  #getMinuteList () {
    const minutes = []

    for (let i = 0; i <= 60; i++) {
      let value = i.toString()

      if (i <= 9) {
        value = `0${i}`
      }

      minutes.push({ minute: value })
    }

    return minutes
  }

  /**
   * @returns Get list of seconds (00 to 60)
   * @returns {array}
   */
  #getSecondList () {
    const minutes = []

    for (let i = 0; i <= 60; i++) {
      let value = i.toString()

      if (i <= 9) {
        value = `0${i}`
      }

      minutes.push({ second: value })
    }

    return minutes
  }
}
