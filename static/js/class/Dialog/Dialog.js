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

  enableBtn () {
    document.getElementById(`${this.viewType}-${this.dialogType}-dialog-open`).disabled = false
  }

  disableBtn () {
    document.getElementById(`${this.viewType}-${this.dialogType}-dialog-open`).disabled = true
  }

  setElement () {
    this.element = document.getElementById(`${this.viewType}-${this.dialogType}-dialog`)
  }
}
