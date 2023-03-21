function createModalInfoBox (label, value) {
  const cell = document.createElement('div')
  const span = document.createElement('span')
  const div = document.createElement('div')

  div.classList.add('small')
  cell.classList.add('cell')

  span.innerText = label
  div.innerText = value
  div.setAttribute('title', value)

  cell.appendChild(span)
  cell.appendChild(div)

  return cell
}

function createBanEventTable (events) {
  const table = new Table('modal-ip')

  const header = new Row()
  header.addCell(new Cell('#', 'number'))

  tableHeaders.events.forEach(function (text) {
    header.addCell(new Cell(text))
  })

  table.addHeader(header)

  events.forEach(function (item, index) {
    const row = new Row()
    row.addCell(new Cell(Format.Number(index + 1), 'number'))

    for (const [key, value] of Object.entries(item)) {
      row.addCell(new Cell(value))
    }

    table.addRow(row)
  })

  return table.get()
}

function createIpModal (address) {
  const ip = details.getIp(address)
  const country = details.getCountry(ip.country)
  const network = details.getNetwork(ip.network)

  const modalBody = document.getElementById('modal-body')
  const modalTitle = document.getElementById('modal-title')

  const info = document.createElement('div')
  info.classList.add('row')

  info.appendChild(createModalInfoBox('IP Address', address))
  info.appendChild(createModalInfoBox('Network', network.name))
  info.appendChild(createModalInfoBox('Country', country.name))
  info.appendChild(createModalInfoBox('Bans', Format.Number(ip.bans)))

  modalBody.appendChild(info)
  modalTitle.innerText = 'IP Address Details'

  modalBody.appendChild(createBanEventTable(ip.events))
}

document.getElementById('modal-close').addEventListener('click', function (e) {
  document.getElementById('modal').classList.toggle('hide')
  document.getElementById('modal-body').innerText = ''
  document.getElementById('modal-title').innerText = ''
})

function createViewButtonEvents () {
  const buttons = document.getElementsByClassName('details')

  for (let i = 0; i < buttons.length; i++) {
    buttons[i].addEventListener('click', function (e) {
      const dataType = e.target.getAttribute('data-type')

      if (dataType === 'address') {
        createIpModal(e.target.getAttribute('data-value'))
      }

      document.getElementById('modal').classList.toggle('hide')
    })
  }
}

function createDetailsButton (dataType, dataValue) {
  const button = document.createElement('button')
  button.innerText = 'View details'
  button.classList.add('details')
  button.setAttribute('data-type', dataType)
  button.setAttribute('data-value', dataValue)

  return button
}
