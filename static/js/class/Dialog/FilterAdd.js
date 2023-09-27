import { Dialog } from './Dialog.js'
import { Helper } from '../Helper.js'

export class FilterAddDialog extends Dialog {
  dialogType = 'filter-add'
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
      name: 'Jails',
      chart: true,
      table: true
    },
    {
      value: 'date',
      name: 'Date',
      chart: false,
      table: true
    }
  ]

  constructor (viewType, iaData) {
    super(viewType)
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
    const dialog = document.getElementById('main-dialog')
    dialog.innerText = ''

    // Dialog header
    const header = this.createHeader(`New ${this.viewType} filter`)

    // Select group
    const selectGroup = document.createElement('div')
    selectGroup.classList.add('selects')

    const typeSelect = document.createElement('select')
    typeSelect.setAttribute('id', `${this.viewType}-filter-type`)
    typeSelect.classList.add('filter-type')

    const actionSelect = document.createElement('select')
    actionSelect.setAttribute('id', `${this.viewType}-filter-action`)
    actionSelect.classList.add('filter-action')

    const valueSelect = document.createElement('select')
    valueSelect.setAttribute('id', `${this.viewType}-filter-value`)
    valueSelect.classList.add('filter-value')

    // Type select options
    this.#typeSelectOptions.forEach(item => {
      if ((this.viewType === 'chart' && item.chart === true) || (this.viewType === 'table' && item.table === true)) {
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

    // Button group
    const buttonGroup = document.createElement('div')
    buttonGroup.classList.add('buttons')

    // Apply button
    const applyBtn = document.createElement('button')
    applyBtn.innerText = 'Apply'
    applyBtn.setAttribute('id', 'dialog-filter-apply')
    applyBtn.setAttribute('data-view-type', this.viewType)

    buttonGroup.appendChild(applyBtn)
    buttonGroup.appendChild(
      this.createCloseButton('Cancel', `${this.viewType}-filter-add`)
    )

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
      case 'date':
        valueName = 'date'
        textValueName = 'date'
        break
    }

    let data = []
    if (type === 'version') {
      data = [{ number: 4 }, { number: 6 }]
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
   * Get element Id with dialog type prefix
   * @param {string} name
   */
  #getId (name) {
    return `${this.viewType}-${name}`
  }
}
