export class Table
{
	constructor (cssClass = null, id = null) {
		this.id = id
		this.cssClass = cssClass

		this.html = document.createElement('table')

		if (cssClass !== null) {
			this.html.classList.add(cssClass)
		}

		this.header = document.createElement('thead')
		this.body = document.createElement('tbody')
	}

	get() {
		this.html.appendChild(this.header)
		this.html.appendChild(this.body)

		return this.html;
	}

	addHeader(row) {
		var tr = document.createElement('tr')

		row.get().forEach(cell => {
			var th = document.createElement('th')

			if (cell.cssClass !== null) {
				th.classList.add(cell.cssClass)
			}

			th.innerText = cell.value

			tr.appendChild(th);
		})

		this.header.appendChild(tr)
	}

	addRow(row) {
		var tr = document.createElement('tr')

		row.get().forEach(item => {
			var td = document.createElement('td')

			if (item.html && item.html === true) {
				td.appendChild(item.value)
			} else {
				td.innerText = item.value
			}

			if (item.cssClass) {
				td.classList.add(item.cssClass)
			}

			if (item.colSpan > 0) {
				td.setAttribute('colspan', item.colSpan)
			}

			tr.appendChild(td)
		})

		this.body.appendChild(tr);
	}
}

export class Row
{
	constructor() {
		this.data = [];
	}

	get() {
		return this.data
	}

	addCell(cell) {
		this.data.push(cell.get())
	}
}

export class Cell
{
	constructor (value, cssClass = null, html = false, colSpan = 0) {
		this.value = value
		this.cssClass = cssClass
		this.html = html
		this.colSpan = colSpan
	}

	get() {
		return {
			value: this.value,
			cssClass: this.cssClass,
			html: this.html,
			colSpan: this.colSpan
		}
	}
}
