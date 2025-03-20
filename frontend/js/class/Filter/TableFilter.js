import { Dataset } from '../Dataset.js'
import { Filter } from './Filter.js'
import { FilterChip } from '../FilterChip.js'

export class TableFilter extends Filter {
  #supportedListTypes = ['address', 'recentBans', 'subnet']

  constructor () {
    super()
    this.chip = new FilterChip('table')
  }

  /**
   * Get filtered data
   * @param {string} typeList
   * @returns
   */
  getData (listType) {
    let data

    if (listType === 'recentBans') {
      data = Dataset.getRecentBans()
    } else {
      data = Dataset.getList(listType)
    }

    if (this.filters.length > 0 && this.#supportedListTypes.includes(listType) === true) {
      return this._getFilteredData(data)
    }

    // Clone array and return
    return [...data]
  }
}
