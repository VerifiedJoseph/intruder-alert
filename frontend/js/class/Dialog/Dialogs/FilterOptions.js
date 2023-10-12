import { ViewGroup } from '../ViewGroup.js'

export class FilterOptionsDialog extends ViewGroup {
  dialogId = 'filter-options'

  /**
   * Setup dialog
   * @param {Filter|ChartFilter} filter Filter class instance
   */
  setup (filter) {
    this.#setupElements()

    if (filter.hasFilters() === false) {
      document.getElementById('dialog-filters-reverse').disabled = true
      document.getElementById('dialog-filters-remove').disabled = true
    }
  }

  #setupElements () {
    const header = this.createHeader(
      `${this.viewGroup} filter options`,
      true,
      `${this.viewGroup}-filter-options`
    )

    // Button group
    const optionButtons = document.createElement('div')
    optionButtons.classList.add('option-btns')

    // Reverse filters button
    const reverseFiltersBtn = document.createElement('button')
    reverseFiltersBtn.innerText = 'Reverse filters'
    reverseFiltersBtn.setAttribute('id', 'dialog-filters-reverse')
    reverseFiltersBtn.setAttribute('data-view-group', this.viewGroup)

    // Remove filters button
    const removeFiltersBtn = document.createElement('button')
    removeFiltersBtn.innerText = 'Remove filters'
    removeFiltersBtn.setAttribute('id', 'dialog-filters-remove')
    removeFiltersBtn.setAttribute('data-view-group', this.viewGroup)

    optionButtons.appendChild(reverseFiltersBtn)
    optionButtons.appendChild(removeFiltersBtn)

    const dialog = document.getElementById('main-dialog')
    dialog.innerText = ''
    dialog.appendChild(header)
    dialog.appendChild(optionButtons)
  }
}
