export class Details {
  constructor (data = []) {
    this.data = data
  }

  getIp (address) {
    for (let i = 0; i < this.data.address.list.length; i++) {
      if (this.data.address.list[i].address === address) {
        return this.data.address.list[i]
      }
    }
  }

  getNetwork (number) {
    for (let i = 0; i < this.data.network.list.length; i++) {
      if (this.data.network.list[i].number.toString() === number.toString()) {
        return this.data.network.list[i]
      }
    }
  }

  getCountry (code) {
    for (let i = 0; i < this.data.country.list.length; i++) {
      if (this.data.country.list[i].code === code) {
        return this.data.country.list[i]
      }
    }
  }

  getJail (name) {
    for (let i = 0; i < this.data.jail.list.length; i++) {
      if (this.data.jail.list[i].name === name) {
        return this.data.jail.list[i]
      }
    }
  }
}
