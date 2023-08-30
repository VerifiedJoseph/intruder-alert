import { Button } from './Button.js'

export class Helper {
  /**
   * Create most banned buttons
   *
   * @param {object} data Data
   */
  static createMostBannedButtons (data) {
    const types = ['address', 'network', 'country', 'jail']

    types.forEach(type => {
      document.getElementById(`most-${type}-button`).appendChild(
        Button.createView('recentBans', type, data[type].mostBanned, 'most-banned')
      )
    })
  }

  static orderData (data) {
    if (document.getElementById('data-order-by').disabled === true) {
      return data
    }

    let orderBy = document.getElementById('data-order-by').value
    if (document.getElementById('data-order-by').value === 'ips') {
      orderBy = 'ipCount'
    }

    data.sort(function (a, b) {
      if (orderBy === 'date') {
        return new Date(b.date) - new Date(a.date)
      }

      return b[orderBy] - a[orderBy]
    })

    return data
  }

  static getViewType () {
    return document.getElementById('data-view-type').value
  }
}
