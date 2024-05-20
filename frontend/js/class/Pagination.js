import { Helper } from './Helper.js'

export class Pagination {
  #data = []
  #pageNumber = 0
  #pageSize = 25
  #pageCount = 0
  #totalItems = 0
  #indexStart = 1

  constructor (data = []) {
    this.#data = data
    this.#totalItems = data.length
  }

  /**
   * Set page number
   * @param {int} number
   */
  setPage (number = 0) {
    this.#pageNumber = number
  }

  /**
   * Returns page data
   * @returns {object}
   */
  getData () {
    if (this.#data.length === 0) {
      return { items: [] }
    }

    const pages = []
    this.#pageSize = Number(document.getElementById('page-size').value)

    for (let i = 0; i < this.#data.length; i += this.#pageSize) {
      pages.push(this.#data.slice(i, i + this.#pageSize))
    }

    this.#pageCount = pages.length - 1

    return {
      items: pages[this.#pageNumber],
      indexStart: this.#getIndexStart()
    }
  }

  /**
   * Set buttons
   */
  setButtons () {
    let prev = null
    let next = null
    const last = this.#pageCount

    if (this.#pageCount > 0) {
      this.#updateButton('load-last-page', last)

      prev = this.#pageNumber - 1
      next = this.#pageNumber + 1

      if (prev >= 0) {
        this.#updateButton('load-prev-page', prev)
        this.#enableButton('load-first-page')
        this.#enableButton('load-prev-page')
      } else {
        this.#disableButton('load-first-page')
        this.#disableButton('load-prev-page')
      }

      if (next < last || next === last) {
        this.#updateButton('load-next-page', next)
        this.#updateButton('load-last-page', last)
        this.#enableButton('load-next-page')
        this.#enableButton('load-last-page')
      } else {
        this.#disableButton('load-next-page')
        this.#disableButton('load-last-page')
      }
    } else {
      this.#disableButton('load-first-page')
      this.#disableButton('load-prev-page')
      this.#disableButton('load-next-page')
      this.#disableButton('load-last-page')
    }

    let displayPageCount = this.#pageCount + 1
    if (displayPageCount === 0) {
      displayPageCount = 1
    }

    document.getElementById('total-pages').innerText = displayPageCount
    document.getElementById('total-page-count').innerText = Helper.formatNumber(this.#totalItems)

    const select = document.getElementById('page-number')
    select.innerText = ''

    for (let index = 0; index < displayPageCount; index++) {
      const option = document.createElement('option')
      option.value = index
      option.innerText = index + 1

      select.appendChild(option)
    }

    select.value = this.#pageNumber

    if (this.#totalItems === 0 || this.#pageCount === 0) {
      select.disabled = true
    } else {
      select.disabled = false
    }
  }

  #getIndexStart () {
    if (this.#pageNumber >= 1) {
      return (this.#pageNumber * this.#pageSize) + 1
    }

    return this.#indexStart
  }

  /**
   * Update `data-page` attribute value of a button
   * @param {string} id Element id
   * @param {int} number Page number
   */
  #updateButton (id, number) {
    const button = document.getElementById(id)
    button.setAttribute('data-page', number)
  }

  /**
   * Enable a button
   * @param {string} id Element id
   */
  #enableButton (id) {
    document.getElementById(id).disabled = false
  }

  /**
   * Disable a button
   * @param {string} id Element id
   */
  #disableButton (id) {
    document.getElementById(id).disabled = true
  }
}
