import { Filter } from './Filter.js'
import { FilterChip } from '../FilterChip.js'

export class TableFilter extends Filter {
  #supportedListTypes = ['address', 'recentBans', 'subnet']

  constructor (iaData) {
    super(iaData)
    this.chip = new FilterChip('table-applied-filters', iaData)
  }

  /**
   * Get filtered data
   * @param {string} typeList
   * @returns
   */
  getData (listType) {
    let data

    if (listType === 'recentBans') {
      data = this.iaData.getRecentBans()
    } else {
      data = this.iaData.getList(listType)
    }

    if (this.settings.length > 0 && this.#supportedListTypes.includes(listType) === true) {
      return this._getFilteredData(data)
    }

    return data
  }
}
