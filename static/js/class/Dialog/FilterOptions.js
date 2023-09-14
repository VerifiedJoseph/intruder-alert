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
      document.getElementById('chart-filters-reverse').disabled = false
      document.getElementById('chart-filters-remove').disabled = false
      document.getElementById('chart-filter-a').disabled = false
    } else {
      document.getElementById('chart-filters-reverse').disabled = true
      document.getElementById('chart-filters-remove').disabled = true
      document.getElementById('chart-filter-a').disabled = true
    }
  }
}
