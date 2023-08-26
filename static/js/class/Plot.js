/* global Chart */
export class Plot {
  #data = []
  #chart

  constructor (data = []) {
    this.#data = data
  }

  newChart (type) {
    const ctx = document.getElementById('chart-canvas')

    let plotData = []
    let plotLabels = []

    if (type === 'last7days') {
      plotLabels = this.#data.plots.last7days.labels
      plotData = this.#data.plots.last7days.data
    } else if (type === 'last24hours') {
      plotLabels = this.#data.plots.last24hours.labels
      plotData = this.#data.plots.last24hours.data
    } else {
      plotLabels = this.#data.plots.last30days.labels
      plotData = this.#data.plots.last30days.data
    }

    const options = {
      type: 'line',
      data: {
        labels: plotLabels,
        datasets: [{
          fill: true,
          label: 'Bans',
          data: plotData
        }]
      },
      options: {
        interaction: {
          intersect: false,
          mode: 'index'
        },
        responsive: true,
        maintainAspectRatio: false,
        scales: {
          y: {
            beginAtZero: false
          },
          x: {
            ticks: {
              maxRotation: 0,
              callback: function (val, index) {
                const regex = /[0-9]{4}-[0-9]{2}-[0-9]{2} [0-9]{2}:00/

                let value = this.getLabelForValue(val)
                if (regex.test(this.getLabelForValue(val)) === true) {
                  const time = new Date(this.getLabelForValue(val))
                  value = time.toLocaleString('en-GB', { hour: 'numeric', minute: 'numeric', hour12: false })
                }

                // Hide every x tick label
                return index % 2 === 0 ? value : ''
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

    this.#chart = new Chart(ctx, options)
  }

  destroyChart () {
    this.#chart.destroy()
  }
}
