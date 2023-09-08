/* global Chart */
export class Plot {
  #chart

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
                let value = this.getLabelForValue(val)
                const time = new Date(value)

                if (data.type === 'last24hours') {
                  value = time.toLocaleString(navigator.languages[0], { hour: 'numeric', minute: 'numeric', hour12: false })
                } else {
                  value = value.replace(/[0-9]{4}-/, '')
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
}
