export class FilterPanel {
  constructor (data = []) {
    this.data = data
  }

  /**
   * Set filter select options
   * @param {string} type Filter type
   */
  setOptions (type, filter) {
    const select = document.getElementById('filter-value')
    select.innerText = ''

    let valueName = ''
    switch (type) {
      case 'address':
        valueName = 'address'
        break
      case 'network':
        valueName = 'number'
        break
      case 'country':
        valueName = 'code'
        break
      case 'jail':
        valueName = 'name'
        break
      case 'date':
        valueName = 'date'
        break
    }

    for (let index = 0; index < this.data[type].list.length; index++) {
      const item = this.data[type].list[index]

      const option = document.createElement('option')
      option.value = item[valueName]
      option.innerText = item.date || item.name || item.address

      if (filter.hasFilter(type, item[valueName]) === true) {
        option.disabled = true
      }

      select.appendChild(option)
    }
  }

  /**
   * Disable select option
   * @param {string} name
   */
  disableOption (name) {
    document.querySelector(`#filter-type [value="${name}"]`).disabled = true
  }

  /**
   * Enable select option
   * @param {string} name
   */
  enableOption (name) {
    document.querySelector(`#filter-type [value="${name}"]`).disabled = false
  }

  /**
   * Show filter panel
   */
  show () {
    document.getElementById('filter-panel').classList.remove('hide')
  }

  /**
   * Hide filter panel
   */
  hide () {
    document.getElementById('filter-panel').classList.add('hide')
  }

  setup (filter) {
    const viewType = document.getElementById('data-view-type').value

    document.getElementById('filter-type')[0].selected = true
    document.getElementById('filter-action')[0].selected = true

    this.setOptions('address', filter)
    this.enableOption('jail')
    this.enableOption('date')

    if (viewType === 'address') {
      this.disableOption('jail')
      this.disableOption('date')
    }
  }
}
