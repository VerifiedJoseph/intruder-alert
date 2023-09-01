export class Filter {
  iaData
  data = []
  settings = []
  chip

  constructor (iaData, data = []) {
    this.iaData = iaData
    this.data = data
    this.settings = []
  }

  _getFilteredData (data) {
    const filtered = []

    data.forEach(item => {
      const addStatus = []

      for (let index = 0; index < this.settings.length; index++) {
        const filter = this.settings[index]

        let value
        if (filter.type === 'date') {
          const date = new Date(item.timestamp)
          const parts = date.toISOString().substring(0, 10).split('-')

          value = `${parts[0]}-${parts[1]}-${parts[2]}`
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
   * Remove filter by its type
   * @param {string} type filter type
   */
  remove (type) {
    let id = null

    this.settings = this.settings.filter(filter => {
      if (filter.type === type) {
        id = filter.id
        return false
      }

      return true
    })

    if (id !== null) {
      this.chip.remove(id)
    }
  }

  /**
   * Remove a number of filters by type
   * @param {array} types filter types
   */
  removeMany (types) {
    Array.from(types).forEach(type => {
      let id = null

      this.settings = this.settings.filter(filter => {
        if (filter.type === type) {
          id = filter.id
          return false
        }

        return true
      })

      if (id !== null) {
        this.chip.remove(id)
      }
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
  }

  /**
   * Reset filters
   */
  reset () {
    this.settings = []
    this.chip.removeAll()
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
}
