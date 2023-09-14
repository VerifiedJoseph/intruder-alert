export class FilterChip {
  #iaData

  /** @var {HTMLElement} */
  #container = null

  /** @var {object} typeTexts */
  #typeTexts = {
    address: 'IP Address',
    version: 'IP Version',
    subnet: 'Subnet',
    network: 'Network',
    country: 'Country',
    continent: 'Continent',
    jail: 'Jail',
    date: 'Date'
  }

  constructor (containerId, iaData) {
    this.#container = document.getElementById(containerId)
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
   * @param {string} uuid Filter UUID
   */
  create (type, action, value, uuid) {
    const valueText = this.#getValueText(type, value)
    const actionText = this.#getActionText(action)

    const div = document.createElement('div')
    div.appendChild(this.#createSpan(this.#typeTexts[type]))
    div.appendChild(this.#createSpan(` ${actionText} `, 'action'))
    div.appendChild(this.#createSpan(valueText))
    div.appendChild(this.#createButton(uuid, value))
    div.setAttribute('title', `${this.#typeTexts[type]} ${actionText} ${valueText}`)
    div.setAttribute('data-label-id', uuid)
    div.classList.add('item')

    this.#container.appendChild(div)
  }

  /**
   * Update filter chip
   * @param {string} type Filter type
   * @param {string} action Filter action
   * @param {string} value Filter value
   * @param {string} uuid Filter UUID
   */
  update (type, action, value, uuid) {
    const valueText = this.#getValueText(type, value)
    const actionText = this.#getActionText(action)

    const div = document.querySelector(`div[data-label-id="${uuid}"]`)
    div.setAttribute('title', `${this.#typeTexts[type]} ${actionText} ${valueText}`)
    div.childNodes[1].innerText = ` ${actionText} `
  }

  /**
   * Remove filter chip
   * @param {string} uuid Filter UUID
   */
  remove (uuid) {
    if (document.querySelector(`div[data-label-id="${uuid}"]`)) {
      document.querySelector(`div[data-label-id="${uuid}"]`).remove()
    }
  }

  /**
   * Removes all filter chips
   */
  removeAll () {
    this.#container.innerText = ''
  }

  /**
   * Create chip button
   * @param {string} uuid Unique filter identifier
   * @param {string} value filter value
   * @returns HTMLButtonElement
   */
  #createButton (uuid, value) {
    const button = document.createElement('button')
    button.innerText = 'X'
    button.setAttribute('title', 'Remove filter')
    button.setAttribute('data-filter-id', uuid)
    button.setAttribute('data-filter-value', value)

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
