import { Dialog } from './Dialog.js'

export class FilterOptionsDialog extends Dialog {
  dialogType = 'filter-options'

  constructor (viewType) {
    super(viewType)
    this.setElement()
  }

  /**
   * Setup dialog
   * @param {Filter|ChartFilter} filter Filter class instance
   */
  setup (filter) {
    console.log(filter.hasFilters())

    if (filter.hasFilters() === true) {
      document.getElementById(`${this.viewType}-filters-reverse`).disabled = false
      document.getElementById(`${this.viewType}-filters-remove`).disabled = false
    } else {
      document.getElementById(`${this.viewType}-filters-reverse`).disabled = true
      document.getElementById(`${this.viewType}-filters-remove`).disabled = true
    }
  }
}
