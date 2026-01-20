import Chart from 'chart.js/auto'

export class Plot {
  #chart

  /**
   * Create new chart
   * @param {object} data Chart data returned by `chart.filter.getData()`
   * @param {boolean} data.hasData Data status
   * @param {array} data.datasets Chart datasets
   * @param {array} data.labels Chart labels
   * @param {string} data.type Chart data type
   */
  newChart (data) {
    if (this.#chart !== undefined) {
      this.#chart.destroy()
    }

    if (data.hasData === false) {
      document.getElementById('chart-message').classList.remove('hide')
    } else {
      document.getElementById('chart-message').classList.add('hide')
    }

    const ctx = document.getElementById('chart-canvas')
    const options = {
      type: 'line',
      data: {
        labels: data.labels,
        datasets: data.datasets
      },
      options: {
        animation: false,
        interaction: {
          intersect: false,
          mode: 'index'
        },
        responsive: true,
        maintainAspectRatio: false,
        scales: {
          y: {
            beginAtZero: true
          },
          x: {
            ticks: {
              minRotation: 0,
              maxRotation: 0,
              align: 'inner',
              autoSkip: false,
              callback: function (val, index) {
                let x = 2
                let value = this.getLabelForValue(val)

                if (data.type === 'last14days') {
                  x = -1
                }

                if (data.type === 'last24hours' || data.type === 'last48hours') {
                  value = value.split(' ')[1]

                  if (data.type === 'last48hours') {
                    x = 6

                    if (index === 47) {
                      x = -1
                    }
                  }
                } else {
                  value = value.replace(/[0-9]{4}-/, '')
                }

                if (x === -1) {
                  return value
                }

                // Hide every x tick label
                return index % x === 0 ? value : ''
              }
            }
          }
        },
        plugins: {
          legend: {
            align: 'end'
          }
        }
      }
    }

    if (window.matchMedia('(prefers-color-scheme: dark)').matches) {
      Chart.defaults.color = '#E0E0E0'
    }

    this.#chart = new Chart(ctx, options)
  }
}
