import { Dialog } from './Dialog.js'

export class ViewGroupDialogs extends Dialog {
  viewGroup

  /**
   * @param {string} viewGroup Data view group (chart or table)
   */
  constructor (viewGroup) {
    super()

    this.viewGroup = viewGroup
  }

  /**
   * Get open dialog button id
   * @returns
   */
  getButtonId () {
    return `${this.viewGroup}-${this.dialogId}-dialog-open`
  }
}
