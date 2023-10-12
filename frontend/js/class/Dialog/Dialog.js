import { Helper } from '../Helper.js'

export class Dialog {
  element
  dialogId

  constructor () {
    this.element = document.getElementById('main-dialog')
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
   *
   * @returns
   */
  getButtonId () {
    return `${this.dialogId}-dialog-open`
  }

  /**
   * Enable dialog open button
   */
  enableBtn () {
    document.getElementById(this.getButtonId()).disabled = false
  }

  /**
   * Disable dialog open button
   */
  disableBtn () {
    document.getElementById(this.getButtonId()).disabled = true
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
      const closeBtn = this.createCloseButton('Close', btnDataValue, 'dialog-close')
      header.appendChild(closeBtn)
    }

    return header
  }

  /**
   * Create close button
   * @param {string} text Button text
   * @param {string} dataValue Value of the button's `data-close-dialog` attribute
   * @param {string} cssClass Button CSS class
   * @returns HTMLButtonElement
   */
  createCloseButton (text, dataValue, cssClass = null) {
    const button = document.createElement('button')

    if (cssClass !== null) {
      button.classList.add(cssClass)
    }

    button.setAttribute('id', 'dialog-close')
    button.setAttribute('data-close-dialog', dataValue)
    button.innerText = text

    return button
  }
}
