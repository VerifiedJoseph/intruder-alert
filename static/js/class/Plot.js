/* global Chart */
export class Plot {
  #data = []
  #chart

  constructor (data = []) {
    this.#data = data
  }

  newChart (data) {
    const ctx = document.getElementById('chart-canvas')
    const options = {
      type: 'line',
      data: {
        labels: data.labels,
        datasets: [{
          fill: true,
          label: 'Bans',
          data: data.data
        }]
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
