import { Dialog } from './Dialog.js'

export class FilterOptionsDialog extends Dialog {
  dialogType = 'filter-add'

  constructor (viewType) {
    super(viewType)

    this.setElement()
  }
}
