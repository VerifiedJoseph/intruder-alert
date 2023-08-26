import { Filter } from './Filter.js'

export class TableFilter extends Filter {
  #supportedListTypes = ['address', 'recentBans', 'subnet']
  labelDiv = 'applied-filters'

  /**
   * Get filtered data
   * @param {string} typeList
   * @returns
   */
  getData (listType) {
    let data

    if (listType === 'recentBans') {
      data = this.getRecentBans()
    } else {
      data = this.data[listType].list
    }

    if (this.settings.length > 0 && this.#supportedListTypes.includes(listType) === true) {
      return this._getFilteredData(data, listType)
    }

    return data
  }
}
