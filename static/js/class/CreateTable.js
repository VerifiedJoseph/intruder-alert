
import { Table, Row, Cell } from './Table.js'
import { Format } from './Format.js'
import { Button } from './Button.js'

export class CreateTable {
  #tableHeaders = {
    address: ['Address', 'Subnet', 'Network', 'Country', 'Bans', ''],
    jail: ['Jail', 'IPs', 'Bans', ''],
    network: ['Network', 'IPs', 'Bans', ''],
    subnet: ['Subnet', 'Network', 'Country', 'IPs', 'Bans', ''],
    country: ['Country', 'IPs', 'Bans', ''],
    continent: ['Continent', 'IPs', 'Bans', ''],
    events: ['Date', 'Jail'],
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

    this.#tableHeaders[this.#type].forEach(function (text) {
      header.addCell(new Cell(text))
    })

    table.addHeader(header)

    if (data.items.length > 0) {
      data.items.forEach((item, index) => {
        let row = new Row()
        const itemNumber = index + data.indexStart

        row.addCell(new Cell(Format.Number(itemNumber), 'number'))

        if (this.#type === 'address') {
          row = this.#createAddressRow(item, row)
        }

        if (this.#type === 'network' || this.#type === 'country' || this.#type === 'continent') {
          const span = document.createElement('span')
          span.innerText = item.name
          span.setAttribute('title', item.name)

          row.addCell(new Cell(span, 'long', true))
          row.addCell(new Cell(Format.Number(item.ipCount)))
          row.addCell(new Cell(Format.Number(item.bans)))

          const viewButtons = document.createElement('span')
          viewButtons.appendChild(
            Button.createView('address', this.#type, item.number || item.code || item.name)
          )
          viewButtons.appendChild(
            Button.createView('recentBans', this.#type, item.number || item.code || item.name)
          )

          row.addCell(new Cell(
            viewButtons,
            'view-btn',
            true
          ))
        }

        if (this.#type === 'recentBans') {
          row = this.#createRecentBansRow(item, row)
        }

        if (this.#type === 'date') {
          row = this.#createDateRow(item, row)
        }

        if (this.#type === 'jail') {
          row = this.#createJailRow(item, row)
        }

        if (this.#type === 'subnet') {
          row = this.#createSubnetRow(item, row)
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

  #createAddressRow (item, row) {
    const network = this.#iaData.getNetwork(item.network)
    const country = this.#iaData.getCountry(item.country)

    row.addCell(new Cell(item.address))
    row.addCell(new Cell(
      Button.createFilter('subnet', item.subnet, item.subnet, this.#filter),
      null,
      true
    ))
    row.addCell(new Cell(
      Button.createFilter('network', network.number, network.name, this.#filter),
      'asn',
      true
    ))
    row.addCell(new Cell(
      Button.createFilter('country', country.code, country.name, this.#filter),
      'country',
      true
    ))
    row.addCell(new Cell(Format.Number(item.bans)))
    row.addCell(new Cell(
      Button.createView('recentBans', this.#type, item.address),
      'view-bans-btn',
      true
    ))

    return row
  }

  #createRecentBansRow (item, row) {
    const network = this.#iaData.getNetwork(item.network)
    const country = this.#iaData.getCountry(item.country)

    row.addCell(new Cell(item.timestamp, 'date'))
    row.addCell(new Cell(
      Button.createFilter('address', item.address, item.address, this.#filter),
      'address',
      true
    ))
    row.addCell(new Cell(
      Button.createFilter('jail', item.jail, item.jail, this.#filter),
      'jail',
      true
    ))
    row.addCell(new Cell(
      Button.createFilter('network', network.number, network.name, this.#filter),
      'asn',
      true
    ))
    row.addCell(new Cell(
      Button.createFilter('country', country.code, country.name, this.#filter),
      'country',
      true
    ))

    return row
  }

  #createSubnetRow (item, row) {
    const network = this.#iaData.getNetwork(item.network)
    const country = this.#iaData.getCountry(item.country)

    row.addCell(new Cell(item.subnet))
    row.addCell(new Cell(
      Button.createFilter('network', network.number, network.name, this.#filter),
      'asn',
      true
    ))
    row.addCell(new Cell(
      Button.createFilter('country', country.code, country.name, this.#filter),
      'country',
      true
    ))
    row.addCell(new Cell(Format.Number(item.ipCount)))
    row.addCell(new Cell(Format.Number(item.bans)))

    const viewButtons = document.createElement('span')
    viewButtons.appendChild(
      Button.createView('address', this.#type, item.subnet)
    )
    viewButtons.appendChild(
      Button.createView('recentBans', this.#type, item.subnet)
    )

    row.addCell(new Cell(
      viewButtons,
      'view-btn',
      true
    ))

    return row
  }

  #createDateRow (item, row) {
    row.addCell(new Cell(item.date, 'long'))
    row.addCell(new Cell(Format.Number(item.ipCount)))
    row.addCell(new Cell(Format.Number(item.bans)))
    row.addCell(new Cell(
      Button.createView('recentBans', this.#type, item.date),
      'view-bans-btn',
      true
    ))

    return row
  }

  #createJailRow (item, row) {
    row.addCell(new Cell(item.name, 'long'))
    row.addCell(new Cell(Format.Number(item.ipCount)))
    row.addCell(new Cell(Format.Number(item.bans)))
    row.addCell(new Cell(
      Button.createView('recentBans', this.#type, item.name),
      'view-bans-btn',
      true
    ))

    return row
  }
}
