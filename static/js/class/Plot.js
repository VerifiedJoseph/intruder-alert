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
                const regex = /[0-9]{4}-[0-9]{2}-[0-9]{2} [0-9]{2}:00/

                let value = this.getLabelForValue(val)
                if (regex.test(value) === true) {
                  const time = new Date(value)
                  value = time.toLocaleString('en-GB', { hour: 'numeric', minute: 'numeric', hour12: false })
                }

                // Hide every x tick label
                return index % 2 === 0 ? value.replace(/[0-9]{4}-/, '') : ''
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
