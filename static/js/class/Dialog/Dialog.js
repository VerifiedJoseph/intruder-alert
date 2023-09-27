import { Helper } from '../Helper.js'

export class Dialog {
  element
  dialogType
  viewType

  /**
   * @param {string} id Dialog element ID
   * @param {string} type Dialog type (chart or table)
   */
  constructor (viewType) {
    this.viewType = viewType
  }

  /**
   * Open dialog
   */
  open () {
    this.element.showModal()
  }

  /**
   * close dialog
   */
  close () {
    this.element.close()
  }

  /**
   * Enable dialog open button
   */
  enableBtn () {
    document.getElementById(`${this.viewType}-${this.dialogType}-dialog-open`).disabled = false
  }

  /**
   * Disable dialog open button
   */
  disableBtn () {
    document.getElementById(`${this.viewType}-${this.dialogType}-dialog-open`).disabled = true
  }

  setElement () {
    this.element = document.getElementById(`${this.viewType}-${this.dialogType}-dialog`)
  }

  /**
   * Create header element for dialog
   * @param {string} title Header title
   * @param {boolean} showCloseBtn Show close button
   * @param {string} btnDataValue Value of the button's `data-close-dialog` attribute
   * @returns HTMLDivElement
   */
  createHeader (title, showCloseBtn = false, btnDataValue = '') {
    const header = document.createElement('div')
    header.setAttribute('id', 'header')

    const span = document.createElement('span')
    span.innerText = `${Helper.capitalizeFirstChar(title)}`

    header.appendChild(span)

    if (showCloseBtn === true) {
      const closeBtn = document.createElement('button')
      closeBtn.classList.add('dialog-close')
      closeBtn.setAttribute('id', 'dialog-close')
      closeBtn.setAttribute('data-close-dialog', btnDataValue)
      closeBtn.innerText = 'Close'

      header.appendChild(closeBtn)
    }

    return header
  }
}
