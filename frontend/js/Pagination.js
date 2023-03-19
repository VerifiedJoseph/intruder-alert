import { Format } from './Format.js';

export class Pagination
{
	setButtons(pageCount, totalItems, currentPage) {
		var prev = null;
		var next = null;
		var last = pageCount;
	
		if (pageCount > 0) {
			this.#updateButton('load-last-page', last)
	
			prev = currentPage - 1;
			next = currentPage + 1;
	
			if (prev >= 0) {
				this.#updateButton('load-prev-page', prev)
				this.#enableButton('load-first-page')
				this.#enableButton('load-prev-page')
			} else {
				this.#disableButton('load-first-page')
				this.#disableButton('load-prev-page')
			}
	
			if (next < last || next === last) {
				this.#updateButton('load-next-page', next)
				this.#updateButton('load-last-page', last)
				this.#enableButton('load-next-page')
				this.#enableButton('load-last-page')
			} else {
				this.#disableButton('load-next-page')
				this.#disableButton('load-last-page')
			}
		} else {
			this.#disableButton('load-first-page')
			this.#disableButton('load-prev-page')
			this.#disableButton('load-next-page')
			this.#disableButton('load-last-page')
		}
	
		var paginationCount = document.getElementById('pagination-count');	
		paginationCount.innerText = `Page ${currentPage + 1} of ${pageCount + 1} (${Format.Number(totalItems)} total items)`
	}

	#updateButton(id, chunk) {
		var button = document.getElementById(id)
		button.setAttribute('data-page', chunk);
	}
	
	#enableButton(id) {
		document.getElementById(id).disabled = false
	}
	
	#disableButton(id) {
		document.getElementById(id).disabled = true
	}
}
