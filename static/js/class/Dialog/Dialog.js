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
    console.log(`${this.viewType}-${this.dialogType}-dialog`)
    this.element = document.getElementById(`${this.viewType}-${this.dialogType}-dialog`)
  }
}
