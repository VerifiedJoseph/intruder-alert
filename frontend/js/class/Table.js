export class Table {
  #html
  #header
  #body

  constructor () {
    this.#html = document.createElement('table')
    this.#header = document.createElement('thead')
    this.#body = document.createElement('tbody')
  }

  /**
   * Returns html table
   * @returns {HTMLTableElement}
   */
  get () {
    this.#html.appendChild(this.#header)
    this.#html.appendChild(this.#body)

    return this.#html
  }

  /**
   * Add table header
   * @param {Row} row
   */
  addHeader (row) {
    const tr = document.createElement('tr')

    row.get().forEach(cell => {
      const th = document.createElement('th')

      th.setAttribute('scope', 'col')
      th.innerText = cell.value

      if (cell.cssClass !== null) {
        th.classList.add(cell.cssClass)
      }

      tr.appendChild(th)
    })

    this.#header.appendChild(tr)
  }

  /**
  * Add table row
  * @param {Row} row
  */
  addRow (row) {
    const tr = document.createElement('tr')

    row.get().forEach(item => {
      const td = document.createElement('td')

      if (item.html && item.html === true) {
        td.appendChild(item.value)
      } else {
        td.innerText = item.value
      }

      if (item.cssClass) {
        td.classList.add(item.cssClass)
      }

      if (item.colSpan > 0) {
        td.setAttribute('colspan', item.colSpan)
      }

      tr.appendChild(td)
    })

    this.#body.appendChild(tr)
  }
}

export class Row {
  #data = []

  /**
   * Get row details
   */
  get () {
    return this.#data
  }

  /**
   * Add a cell to the row
   * @param {Cell} cell
   */
  addCell (cell) {
    this.#data.push(cell.get())
  }
}

export class Cell {
  #value
  #cssClass
  #html
  #colSpan

  /**
   * @param {*} value Cell value
   * @param {null|string} cssClass CSS class of the cell
   * @param {boolean} html Is cell value an HTML element?
   * @param {int} colSpan Number columns the cell should span
   */
  constructor (value, cssClass = null, html = false, colSpan = 0) {
    this.#value = value
    this.#cssClass = cssClass
    this.#html = html
    this.#colSpan = colSpan
  }

  /**
   * Get cell details
   */
  get () {
    return {
      value: this.#value,
      cssClass: this.#cssClass,
      html: this.#html,
      colSpan: this.#colSpan
    }
  }
}
