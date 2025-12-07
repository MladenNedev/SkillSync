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

    const course = data.course_hours ?? []
    const challenge = data.challenge_hours ?? []
    
    const maxCourse = Math.max(...course, 0)
    const maxChallenge = Math.max(...challenge, 0)
    const maxVal = Math.max(maxCourse, maxChallenge)

    const computedMax = Math.ceil(maxVal)

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
      ].filter((ds) => ds.data.length > 0),

      yMax: computedMax,
    }
  }

  function formatHoursToHM(value) {
    if (!value || value <= 0) return '0h'

    const hours = Math.floor(value)
    const minutes = Math.round((value % 1) * 60)

    if (hours === 0) return `${minutes}m`
    if (minutes === 0) return `${hours}h`

    return `${hours}h ${minutes}m`
  }

  function buildDatasets(sets) {
    return sets.map((s) => ({
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
          display: false,
        },
        tooltip: {
          displayColors: false,
          callbacks: {
            title(ctx) {
              const dayIndex = ctx[0].dataIndex
              const datasetName = ctx[0].dataset.label
        
              const fullDayNames = [
                'Monday',
                'Tuesday',
                'Wednesday',
                'Thursday',
                'Friday',
                'Saturday',
                'Sunday',
              ]
        
              const dayName = fullDayNames[dayIndex] ?? 'Day'
        
              return `${dayName} – ${datasetName}`
            },
        
            label(ctx) {

              const raw = ctx.raw ?? 0
        
              // Convert hours.decimal → minutes total
              const totalMinutes = Math.round(raw * 60)
              const hours = Math.floor(totalMinutes / 60)
              const minutes = totalMinutes % 60
        
              const hm =
                hours > 0 && minutes > 0
                  ? `${hours}h ${minutes}m`
                  : hours > 0
                  ? `${hours}h`
                  : `${minutes}m`
        
              return `Time spent: ${totalMinutes} minutes (${hm})`
              
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
            callback: (v) => `${v}h`,
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
