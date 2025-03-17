export class Filter {
  iaData

  /**
   * @var array filters Array of filters
   */
  filters = []
  chip
  count = 0

  constructor (iaData) {
    this.iaData = iaData
    this.filters = []
  }

  updateIaData (iaData) {
    this.iaData = iaData
  }

  _getFilteredData (data) {
    const filtered = []
    const timestampFilterTypes = ['date', 'hour', 'minute', 'second']

    const filters = this.mergeFilters(this.filters)

    data.forEach(item => {
      const status = [];

      filters.forEach(filter => {
        let value

        if (timestampFilterTypes.includes(filter.type) === true) {
          value = this.#getTimestampPart(item.timestamp, filter.type)
        } else {
          value = item[filter.type].toString()
        }

        if (filter.action === 'include' && filter.values.length > 0) {
          if (filter.values.includes(value) === true) {
            status.push(1)
          } else {
            status.push(0)
          }
        }

        if (filter.action === 'exclude' && filter.values.length > 0) {
          if (filter.values.includes(value) === true) {
            status.push(0)
          } else {
            status.push(1)
          }
        }
      });

      if (status.includes(0) === false) {
        filtered.push(item)
      }
    })

    return filtered
  }

  /**
   * Adds a filter
   * @param {string} type Filter type (address, jail, network etc)
   * @param {string} action Filter action (include or exclude)
   * @param {string} value Filter value
   */
  add (type, action, value) {
    this.filters.push({
      "id": this.count,
      "type": type,
      "action": action,
      "value": value
    })

    this.chip.create(type, action, value, this.count)
    this.count++
  }

  /**
   * Removes a filter by identifier
   * @param {int} id Filter identifier
   */
  remove (id) {
    this.filters = this.filters.filter(filter => {
      if (filter.id === Number(id)) {
        this.chip.remove(filter.id)

        return false
      }

      return true
    })
  }

  /**
   * Remove all filter types except given
   * @param {array} types filter types to keep
   */
  removeAllExcept (types) {
    this.filters = this.filters.filter(filter => {
      if (types.includes(filter.type) === false) {
        this.chip.remove(filter.id)

        return false
      }

      return true
    })
  }

  /**
   * Reset filters
   */
  reset () {
    this.filters = []
    this.chip.removeAll()
  }

  /**
   * Reverse action value of applied filters
   */
  reverse () {
    this.filters.forEach((filter, index) => {
      let newAction = 'include'
      if (filter.action === 'include') {
        newAction = 'exclude'
      }

      this.filters[index].action = newAction
      this.chip.update(filter.id, newAction)
    })
  }

  /**
   * Check if filter has a value
   * @param {string} type Filter type
   * @param {string} value Filter value
   * @returns
   */
  hasFilter (type, value) {
    let status = false

    this.filters.forEach(filter => {
      if (filter.type === type && filter.value === value.toString() === true) {
        status = true
      }
    })

    return status
  }

  /**
   * Check if filter are set
   * @returns {boolean}
   */
  hasFilters () {
    if (this.filters.length > 0) {
      return true
    }

    return false
  }

  /**
   * Merges filters into format using by `_getFilteredData`
   * @param {array} filters Filters to merge
   * @returns Array of merged filters
   */
  mergeFilters (filters) {
    var merged = [];

    filters.forEach(filter => {
      let index = merged.findIndex((e) => e.type === filter.type && e.action === filter.action);

			if (index === -1) {
				merged.push({
					"action": filter.action,
					"type": filter.type,
					"values": [filter.value]
				})
			} else if (merged[index].values.includes(filter.value) === false) {
				merged[index].values.push(filter.value)
			}
    })

    return merged
  }

  /**
   * Get part of a timestamp
   * @param {string} timestamp Timestamp
   * @param {string} part Timestamp part (date, hour, minutes or seconds)
   * @returns {string}
   */
  #getTimestampPart (timestamp, part) {
    const parts = timestamp.split(' ')
    const timeParts = parts[1].split(':')

    if (part === 'date') {
      return parts[0]
    }

    if (part === 'hour') {
      return timeParts[0]
    }

    if (part === 'minute') {
      return timeParts[1]
    }

    if (part === 'second') {
      return timeParts[2]
    }
  }
}
