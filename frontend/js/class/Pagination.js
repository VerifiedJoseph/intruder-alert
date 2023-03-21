import { Format } from './Format.js'

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

  setPage (number = 0) {
    this.#pageNumber = number
  }

  getData () {
    const pages = []

    for (let i = 0; i < this.#data.length; i += this.#pageSize) {
      pages.push(this.#data.slice(i, i + this.#pageSize))
    }

    this.#pageCount = pages.length - 1

    return pages[this.#pageNumber]
  }

  getIndexStart () {
    if (this.#pageNumber >= 1) {
      return (this.#pageNumber * this.#pageSize) + 1
    }

    return this.#indexStart
  }

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

    const paginationCount = document.getElementById('pagination-count')
    let displayPageNumber = this.#pageNumber + 1
    let displayPageCount = this.#pageCount + 1

    if (displayPageCount === 0) {
      displayPageNumber = 1
      displayPageCount = 1
    }

    paginationCount.innerText = `Page ${displayPageNumber} of ${displayPageCount} (${Format.Number(this.#totalItems)} total items)`
  }

  #updateButton (id, number) {
    const button = document.getElementById(id)
    button.setAttribute('data-page', number)
  }

  #enableButton (id) {
    document.getElementById(id).disabled = false
  }

  #disableButton (id) {
    document.getElementById(id).disabled = true
  }
}
