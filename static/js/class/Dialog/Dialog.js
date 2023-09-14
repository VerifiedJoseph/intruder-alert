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

  setElement () {
    this.element = document.getElementById(`${this.viewType}-${this.dialogType}-dialog`)
  }
}
