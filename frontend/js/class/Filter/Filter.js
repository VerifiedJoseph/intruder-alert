export class Filter {
  iaData
  settings = []
  chip

  constructor (iaData) {
    this.iaData = iaData
    this.settings = []
  }

  updateIaData (iaData) {
    this.iaData = iaData
  }

  _getFilteredData (data) {
    const filtered = []
    const timestampFilterTypes = ['date', 'hour', 'minute', 'second']

    data.forEach(item => {
      const addStatus = []

      for (let index = 0; index < this.settings.length; index++) {
        const filter = this.settings[index]
        let value

        if (timestampFilterTypes.includes(filter.type) === true) {
          value = this.#getTimestampPart(item.timestamp, filter.type)
        } else {
          value = item[filter.type].toString()
        }

        if (filter.action === 'include' && filter.values.length > 0) {
          if (filter.values.includes(value) === true) {
            addStatus.push(1)
          } else {
            addStatus.push(0)
          }
        }

        if (filter.action === 'exclude' && filter.values.length > 0) {
          if (filter.values.includes(value) === true) {
            addStatus.push(0)
          } else {
            addStatus.push(1)
          }
        }
      }

      if (addStatus.includes(0) === false) {
        filtered.push(item)
      }
    })

    return filtered
  }

  /**
   * Add filter
   * @param {string} type Filter type
   * @param {string} action Filter action (include or exclude)
   * @param {string} value Filter value
   */
  add (type, action, value) {
    const index = this.findFilter(type, action)

    if (index !== false) {
      this.settings[index].values.push(value)
      this.chip.create(type, action, value, this.settings[index].id)
    } else {
      const id = crypto.randomUUID()

      this.settings.push({
        id,
        type,
        action,
        values: [value]
      })

      this.chip.create(type, action, value, id)
    }
  }

  /**
   * Remove filter by unique identifier
   * @param {string} uuid filter unique identifier
   */
  remove (uuid) {
    this.settings = this.settings.filter(filter => {
      if (filter.id === uuid) {
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
    this.settings = this.settings.filter(filter => {
      if (types.includes(filter.type) === false) {
        this.chip.remove(filter.id)

        return false
      }

      return true
    })
  }

  /**
   * Remove value from a filter
   * @param {string} filterId filter UUID
   * @param {value} value filter value
   */
  removeValue (filterId, value) {
    const index = this.findFilterByUUID(filterId)
    const filter = this.settings[index]

    this.settings[index].values = filter.values.filter(
      item => item !== value
    )

    // Remove filter if values array is now empty
    if (this.settings[index].values.length === 0) {
      this.remove(this.settings[index].id)
    }
  }

  /**
   * Reset filters
   */
  reset () {
    this.settings = []
    this.chip.removeAll()
  }

  /**
   * Reverse action value of applied filters
   */
  reverse () {
    this.settings.forEach((filter, index) => {
      let newAction = 'include'
      if (filter.action === 'include') {
        newAction = 'exclude'
      }

      this.chip.update(filter.id, newAction)
      this.settings[index].action = newAction
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

    this.settings.forEach(filter => {
      if (filter.type === type && filter.values.includes(value.toString()) === true) {
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
    if (this.settings.length > 0) {
      return true
    }

    return false
  }

  /**
   * Find filter array index by a filter's unique identifier
   * @param {string} uuid Unique identifier
   * @returns Array index of filter
   */
  findFilterByUUID (uuid) {
    let key = null

    this.settings.forEach((filter, index) => {
      if (filter.id === uuid) {
        key = index
      }
    })

    if (key !== null) {
      return key
    }

    return false
  }

  findFilter (type, action) {
    let key = null

    this.settings.forEach((filter, index) => {
      if (filter.type === type && filter.action === action) {
        key = index
      }
    })

    if (key !== null) {
      return key
    }

    return false
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
