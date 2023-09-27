import { Dialog } from './Dialog.js'

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
    const header = this.createHeader(
      `${this.viewType} filter options`,
      true,
      `${this.viewType}-filter-options`
    )

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

    optionButtons.appendChild(reverseFiltersBtn)
    optionButtons.appendChild(removeFiltersBtn)

    dialog.appendChild(header)
    dialog.appendChild(optionButtons)
  }
}
