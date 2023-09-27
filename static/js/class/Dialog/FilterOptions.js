import { Dialog } from './Dialog.js'
import { Helper } from '../Helper.js'

export class FilterOptionsDialog extends Dialog {
  dialogType = 'filter-options'

  constructor (viewType) {
    super(viewType)
    this.element = document.getElementById('main-dialog')
  }

  /**
   * Setup dialog
   * @param {Filter|ChartFilter} filter Filter class instance
   */
  setup (filter) {
    this.#setupDom()

    if (filter.hasFilters() === true) {
      document.getElementById('dialog-filters-reverse').disabled = false
      document.getElementById('dialog-filters-remove').disabled = false
    } else {
      document.getElementById('dialog-filters-reverse').disabled = true
      document.getElementById('dialog-filters-remove').disabled = true
    }
  }

  #setupDom () {
    const dialog = document.getElementById('main-dialog')
    dialog.innerText = ''

    // Dialog header
    const header = document.createElement('div')
    header.setAttribute('id', 'header')

    // Header title
    const title = document.createElement('span')
    title.innerText = `${Helper.capitalizeFirstChar(this.viewType)} filter options`

    // Close button
    const closeBtn = document.createElement('button')
    closeBtn.classList.add('dialog-close')
    closeBtn.setAttribute('id', 'dialog-close')
    closeBtn.setAttribute('data-close-dialog', `${this.viewType}-filter-options`)
    closeBtn.innerText = 'Close'

    // Button group
    const optionButtons = document.createElement('div')
    optionButtons.classList.add('option-btns')

    // Reverse filters button
    const reverseFiltersBtn = document.createElement('button')
    reverseFiltersBtn.innerText = 'Reverse filters'
    reverseFiltersBtn.setAttribute('id', 'dialog-filters-reverse')
    reverseFiltersBtn.setAttribute('data-view-type', this.viewType)

    // Remove filters button
    const removeFiltersBtn = document.createElement('button')
    removeFiltersBtn.innerText = 'Remove filters'
    removeFiltersBtn.setAttribute('id', 'dialog-filters-remove')
    removeFiltersBtn.setAttribute('data-view-type', this.viewType)

    header.appendChild(title)
    header.appendChild(closeBtn)
    optionButtons.appendChild(reverseFiltersBtn)
    optionButtons.appendChild(removeFiltersBtn)
    dialog.appendChild(header)
    dialog.appendChild(optionButtons)
  }
}
