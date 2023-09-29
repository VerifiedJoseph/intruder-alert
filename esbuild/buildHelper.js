const path = require('path')
const fs = require('fs')
const fsp = require('fs/promises')
const fsExtra = require('fs-extra')

module.exports = class buildHelper {
  /**
   * Copy file
   */
  async copyFile (filename) {
    const source = path.resolve(`./frontend/${filename}`)
    const destination = path.resolve(`./dist/${filename}`)

    console.log(`Copying ${source} to ${destination}`)

    try {
      await fsExtra.copy(source, destination)
      console.log(`Copied file to ${destination}`)
    } catch (err) {
      console.error(err)
    }
  }

  /**
   * Copy folder
   * @param {string} source Source folder
   * @param {string} destination Destination folder
   */
  async copyFolder (source, destination) {
    source = path.resolve(source)
    destination = path.resolve(destination)

    console.log(`Copying ${source} to ${destination}`)

    try {
      await fsExtra.copy(source, destination)
      console.log(`Copied folder to ${destination}`)
    } catch (err) {
      console.error(err)
    }
  }

  /**
   * Remove folder
   */
  async removeFolder (folder) {
    folder = path.resolve(folder)

    try {
      if (fs.existsSync(folder) === true) {
        await fsp.rm(folder, { recursive: true })
      }
    } catch (err) {
      console.error(err)
    }
  }

  /**
   * Remove file
   */
  async removeFile (file) {
    file = path.resolve(file)

    try {
      if (fs.existsSync(file) === true) {
        await fsp.rm(file)
      }
    } catch (err) {
      console.error(err)
    }
  }

  /**
   * Create symlink
   */
  createSymlink (source, destination, type = 'file') {
    source = path.resolve(source)
    destination = path.resolve(destination)

    if (fs.existsSync(destination) === false) {
      fs.symlink(
        source,
        destination,
        type, (err) => err && console.log(err)
      )
    }
  }

  /**
   * Remove symlink
   */
  async removeSymlink (symlink) {
    symlink = path.resolve(symlink)

    fs.readlink(symlink, (err, target) => {
      if (target !== undefined) {
        fs.unlink(symlink, err => {
          if (err) console.log(err)
        })
      } else if (err) {
        // console.log(err)
      }
    })
  }
}
