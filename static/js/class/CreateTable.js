import { Table, Row, Cell } from './Table.js'
import { Helper } from './Helper.js'

export class CreateTable {
  #tableHeaders = {
    address: ['Address', 'Subnet', 'Network', 'Country', 'Bans', ''],
    jail: ['Jail', 'IPs', 'Bans', ''],
    network: ['Network', 'IPs', 'Bans', ''],
    subnet: ['Subnet', 'Network', 'Country', 'IPs', 'Bans', ''],
    country: ['Country', 'IPs', 'Bans', ''],
    continent: ['Continent', 'IPs', 'Bans', ''],
    recentBans: ['Date', 'Address', 'Jail', 'Network', 'Country'],
    date: ['Date', 'IPs', 'Bans', '']
  }

  #table
  #type = ''
  #iaData
  #filter

  /**
  * Create table
  * @param {array} data Table data
  * @param {string} type Table type
  * @param {IaData} iaData
  * @param {Filter} filter
  */
  constructor (data = [], type, iaData, filter) {
    this.#type = type
    this.#iaData = iaData
    this.#filter = filter

    this.#build(data)
  }

  display () {
    const div = document.getElementById('data-table')
    div.innerText = ''
    div.append(this.#table)
  }

  #build (data) {
    const table = new Table()
    const header = new Row()
    header.addCell(new Cell('#', 'number'))

    this.#tableHeaders[this.#type].forEach(text => {
      header.addCell(new Cell(text))
    })

    table.addHeader(header)

    if (data.items.length > 0) {
      data.items.forEach((item, index) => {
        const itemNumber = index + data.indexStart

        let row = new Row()
        row.addCell(new Cell(Helper.formatNumber(itemNumber), 'number'))

        switch (this.#type) {
          case 'recentBans':
            row = this.#createRecentBansRow(item, row)
            break
          case 'address':
            row = this.#createAddressRow(item, row)
            break
          case 'subnet':
            row = this.#createSubnetRow(item, row)
            break
          case 'network':
          case 'country':
          case 'continent':
            row = this.#createGenericRow(item, row)
            break
          case 'jail':
            row = this.#createJailRow(item, row)
            break
          case 'date':
            row = this.#createDateRow(item, row)
            break
        }

        table.addRow(row)
      })
    } else {
      const row = new Row()
      row.addCell(new Cell('No data found', 'no-data', false, 6))
      table.addRow(row)
    }

    this.#table = table.get()
  }

  /**
   * Create row a for IP address table
   * @param {object} item
   * @param {Row} row
   * @returns row
   */
  #createAddressRow (item, row) {
    const network = this.#iaData.getNetwork(item.network)
    const country = this.#iaData.getCountry(item.country)

    const subnetFilterBtn = this.#createFilterBtn('subnet', item.subnet, item.subnet, this.#filter)
    const networkFilterBtn = this.#createFilterBtn('network', network.number, network.name, this.#filter)
    const countryFilterBtn = this.#createFilterBtn('country', country.code, country.name, this.#filter)
    const viewBtn = Helper.createViewBtn('recentBans', this.#type, item.address)

    row.addCell(new Cell(item.address))
    row.addCell(new Cell(subnetFilterBtn, null, true))
    row.addCell(new Cell(networkFilterBtn, 'asn', true))
    row.addCell(new Cell(countryFilterBtn, 'country', true))
    row.addCell(new Cell(Helper.formatNumber(item.bans), 'bans'))
    row.addCell(new Cell(viewBtn, 'view-bans-btn', true))
    return row
  }

  /**
   * Create row a for recent bans table
   * @param {object} item
   * @param {Row} row
   * @returns row
   */
  #createRecentBansRow (item, row) {
    const network = this.#iaData.getNetwork(item.network)
    const country = this.#iaData.getCountry(item.country)

    const addressFilterBtn = this.#createFilterBtn('address', item.address, item.address, this.#filter)
    const jailFilterBtn = this.#createFilterBtn('jail', item.jail, item.jail, this.#filter)
    const networkFilterBtn = this.#createFilterBtn('network', network.number, network.name, this.#filter)
    const countryFilterBtn = this.#createFilterBtn('country', country.code, country.name, this.#filter)

    row.addCell(new Cell(item.timestamp, 'date'))
    row.addCell(new Cell(addressFilterBtn, 'address', true))
    row.addCell(new Cell(jailFilterBtn, 'jail', true))
    row.addCell(new Cell(networkFilterBtn, 'asn', true))
    row.addCell(new Cell(countryFilterBtn, 'country', true))
    return row
  }

  /**
   * Create row a for subnet table
   * @param {object} item
   * @param {Row} row
   * @returns row
   */
  #createSubnetRow (item, row) {
    const network = this.#iaData.getNetwork(item.network)
    const country = this.#iaData.getCountry(item.country)

    const networkFilterBtn = this.#createFilterBtn('network', network.number, network.name, this.#filter)
    const countryFilterBtn = this.#createFilterBtn('country', country.code, country.name, this.#filter)
    const addressViewBtn = Helper.createViewBtn('address', this.#type, item.subnet)
    const recentBansViewBtn = Helper.createViewBtn('recentBans', this.#type, item.subnet)

    row.addCell(new Cell(item.subnet))
    row.addCell(new Cell(networkFilterBtn, 'asn', true))
    row.addCell(new Cell(countryFilterBtn, 'country', true))
    row.addCell(new Cell(Helper.formatNumber(item.ipCount)), 'ipCount')
    row.addCell(new Cell(Helper.formatNumber(item.bans), 'bans'))

    const viewButtons = document.createElement('span')
    viewButtons.appendChild(addressViewBtn)
    viewButtons.appendChild(recentBansViewBtn)

    row.addCell(new Cell(viewButtons, 'view-btn', true))
    return row
  }

  /**
   * Create row a for date table
   * @param {object} item
   * @param {Row} row
   * @returns row
   */
  #createDateRow (item, row) {
    const viewBtn = Helper.createViewBtn('recentBans', this.#type, item.date)

    row.addCell(new Cell(item.date, 'long'))
    row.addCell(new Cell(Helper.formatNumber(item.ipCount)))
    row.addCell(new Cell(Helper.formatNumber(item.bans)))
    row.addCell(new Cell(viewBtn, 'view-bans-btn', true))
    return row
  }

  /**
   * Create row a for jail table
   * @param {object} item
   * @param {Row} row
   * @returns row
   */
  #createJailRow (item, row) {
    const viewBtn = Helper.createViewBtn('recentBans', this.#type, item.name)

    row.addCell(new Cell(item.name, 'long'))
    row.addCell(new Cell(Helper.formatNumber(item.ipCount)))
    row.addCell(new Cell(Helper.formatNumber(item.bans)))
    row.addCell(new Cell(viewBtn, 'view-bans-btn', true))
    return row
  }

  /**
   * Create row a for network, country or continent table
   * @param {object} item
   * @param {Row} row
   * @returns row
   */
  #createGenericRow (item, row) {
    const span = document.createElement('span')
    span.innerText = item.name
    span.setAttribute('title', item.name)

    row.addCell(new Cell(span, 'long', true))
    row.addCell(new Cell(Helper.formatNumber(item.ipCount)))
    row.addCell(new Cell(Helper.formatNumber(item.bans)))

    const filterValue = item.number || item.code
    const addressViewBtn = Helper.createViewBtn('address', this.#type, filterValue)
    const recentBansViewBtn = Helper.createViewBtn('recentBans', this.#type, filterValue)

    const viewButtons = document.createElement('span')
    viewButtons.appendChild(addressViewBtn)
    viewButtons.appendChild(recentBansViewBtn)

    row.addCell(new Cell(viewButtons, 'view-btn', true))
    return row
  }

  /**
   * Create data filter button for a table cell
   * @param {string} dataType Data type
   * @param {string} dataValue Data value
   * @param {string} text Span text
   * @param {Filter} Filter Filter class instance
   * @returns HTMLSpanElement
   */
  #createFilterBtn (dataType, dataValue, text, filter) {
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
