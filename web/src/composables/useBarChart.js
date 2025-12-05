import { Chart, registerables } from 'chart.js'

Chart.register(...registerables)

export function useBarChart() {
  let chartInstance = null

  function destroyChart() {
    if (chartInstance) {
      chartInstance.destroy()
      chartInstance = null
    }
  }

  function extract(data) {
    return {
      labels: data.labels ?? [],
      datasets: [
        {
          key: 'course_hours',
          label: 'Learning',
          data: data.course_hours ?? [],
          color: '#22c55e',
        },
        {
          key: 'challenge_hours',
          label: 'Challenge',
          data: data.challenge_hours ?? [],
          color: '#f59e0b',
        },
      ].filter(ds => ds.data.length > 0),

      yMax: data.yMax ?? 8,
    }
  }

  function buildDatasets(sets) {
    return sets.map(s => ({
      label: s.label,
      data: s.data,
      backgroundColor: s.color,
      borderRadius: 8,
      barPercentage: 0.9,
      categoryPercentage: 0.5,
    }))
  }

  function buildOptions(yMax) {
    return {
      responsive: true,
      maintainAspectRatio: false,
      animation: {
        duration: 600,
        easing: 'easeOutQuart',
      },
      plugins: {
        legend: {
          display: true,
          position: 'top',
        },
        tooltip: {
          callbacks: {
            label(ctx) {
              return `${ctx.raw.toFixed(2)} hours`
            },
          },
        },
      },
      scales: {
        x: { grid: { display: false } },
        y: {
          beginAtZero: true,
          max: yMax,
          ticks: {
            stepSize: yMax / 4,
            callback: v => `${v}h`,
          },
        },
      },
    }
  }

  function createChart(el, data) {
    destroyChart()
    if (!el) return

    const ctx = el.getContext('2d')
    const parsed = extract(data)

    chartInstance = new Chart(ctx, {
      type: 'bar',
      data: {
        labels: parsed.labels,
        datasets: buildDatasets(parsed.datasets),
      },
      options: buildOptions(parsed.yMax),
    })
  }

  return { createChart, destroyChart }
}