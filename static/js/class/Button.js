/**
 * Class for creating buttons
 */
export class Button {
  /**
   * Create a data view button
   * @param {string} viewType Data view type
   * @param {string} filterType Filter type
   * @param {string} filterValue Filter value
   * @param {string} context Context the button is being used
   * @returns HTMLButtonElement
   */
  static createView (viewType, filterType, filterValue, context = 'table') {
    const button = document.createElement('button')

    button.innerText = (viewType === 'address') ? 'View IPs' : 'View Bans'
    button.classList.add('view')
    button.setAttribute('data-view-type', viewType)
    button.setAttribute('data-filter-type', filterType)
    button.setAttribute('data-filter-value', filterValue)
    button.setAttribute('data-context', context)
    return button
  }

  /**
   * Create data filter button for a table cell
   * @param {string} dataType Data type
   * @param {string} dataValue Data value
   * @param {string} text Span text
   * @param {Filter} Filter Filter class instance
   * @returns HTMLSpanElement
   */
  static createFilter (dataType, dataValue, text, filter) {
    const span = document.createElement('span')

    span.innerText = text
    span.setAttribute('title', text)

    if (filter.hasFilter(dataType, dataValue) === false) {
      const button = document.createElement('button')

      button.innerText = 'Filter'
      button.classList.add('row-filter')
      button.setAttribute('title', `Filter ${dataType} to ${text}`)
      button.setAttribute('data-type', dataType)
      button.setAttribute('data-value', dataValue)
      span.append(button)
    }

    return span
  }
}
