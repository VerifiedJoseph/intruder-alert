export class FilterChip {
  #iaData

  /** @var {HTMLElement} */
  #container = null

  /** @var {string} */
  #viewGroup

  /** @var {object} typeTexts */
  #typeTexts = {
    address: 'IP Address',
    version: 'IP Version',
    subnet: 'Subnet',
    network: 'Network',
    country: 'Country',
    continent: 'Continent',
    jail: 'Jail',
    date: 'Date',
    hour: 'Hour',
    minute: 'Minute',
    second: 'Second'
  }

  constructor (viewGroup, iaData) {
    this.#container = document.getElementById(`${viewGroup}-applied-filters`)
    this.#viewGroup = viewGroup
    this.#iaData = iaData
  }

  updateIaData (iaData) {
    this.iaData = iaData
  }

  /**
   * Create filter chip
   * @param {string} type Filter type
   * @param {string} action Filter action
   * @param {string} value Filter value
   * @param {string} id Filter identifier
   */
  create (type, action, value, id) {
    const valueText = this.#getValueText(type, value)
    const actionText = this.#getActionText(action)

    const div = document.createElement('div')
    div.appendChild(this.#createSpan(this.#typeTexts[type]))
    div.appendChild(this.#createSpan(` ${actionText} `, 'action'))
    div.appendChild(this.#createSpan(valueText, 'ellipsis'))

    const item = document.createElement('div')
    item.appendChild(div)
    item.appendChild(this.#createButton(id, value))
    item.setAttribute(`data-${this.#viewGroup}-chip-id`, id)

    item.classList.add('item')

    this.#container.appendChild(item)
    this.#container.classList.remove('hide')
  }

  /**
   * Update filter chip
   * @param {string} id Filter identifier
   * @param {string} action Filter action
   */
  update (id, action) {
    var chip = document.querySelector(`div[data-${this.#viewGroup}-chip-id="${id}"]`);

    // Change text of div > span.action
    chip.childNodes[0].childNodes[1].innerText = ` ${this.#getActionText(action)} `
  }

  /**
   * Remove filter chip
   * @param {string} id Filter identifier
   */
  remove (id) {
    if (document.querySelector(`div[data-${this.#viewGroup}-chip-id="${id}"]`)) {
      document.querySelector(`div[data-${this.#viewGroup}-chip-id="${id}"]`).remove()
    }

    // Hide filter chip container if has no child nodes
    if (this.#container.hasChildNodes() === false) {
      this.#container.classList.add('hide')
    }
  }

  /**
   * Removes all filter chips
   */
  removeAll () {
    this.#container.innerText = ''
    this.#container.classList.add('hide')
  }

  /**
   * Create chip button
   * @param {string} id Filter identifier
   * @param {string} value filter value
   * @returns HTMLButtonElement
   */
  #createButton (id, value) {
    const button = document.createElement('button')
    button.classList.add('filter-remove')
    button.setAttribute('aria-label', 'Remove filter')
    button.setAttribute('title', 'Remove filter')
    button.setAttribute('data-filter-id', id)
    button.setAttribute('data-filter-value', value)
    button.setAttribute('data-view-group', this.#viewGroup)

    return button
  }

  /**
   * Create span
   * @param {string} text Text
   * @param {string} cssClass CSS class
   * @returns HTMLSpanElement
   */
  #createSpan (text, cssClass = '') {
    const span = document.createElement('span')
    span.innerText = text

    if (cssClass !== '') {
      span.classList.add(cssClass)
    }

    return span
  }

  /**
   * Get value text
   * @param {string} type Filter type
   * @param {string} value Filter value
   * @returns {string}
   */
  #getValueText (type, value) {
    switch (type) {
      case 'network':
        return this.#iaData.getNetworkName(value)
      case 'country':
        return this.#iaData.getCountryName(value)
      case 'continent':
        return this.#iaData.getContinentName(value)
    }

    return value
  }

  /**
   * Get action text
   * @param {string} action Filter action
   * @returns {string}
   */
  #getActionText (action) {
    if (action === 'exclude') {
      return 'is not'
    }

    return 'is'
  }
}
