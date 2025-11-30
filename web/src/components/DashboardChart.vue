<script setup>
defineOptions({
  name: 'DashboardChart',
})
import { ref, onMounted, onBeforeUnmount, watch } from 'vue'
import { Chart, registerables } from 'chart.js'

Chart.register(...registerables)

const props = defineProps({
  chartData: {
    type: Object,
    required: true,
  },
})

const yMax = props.chartData.yMax ?? 8

const canvasRef = ref(null)
let chartInstance = null

function createChart() {
  if (!canvasRef.value || !props.chartData) return

  if (chartInstance) {
    chartInstance.destroy()
  }

  const ctx = canvasRef.value.getContext('2d')

  const labels = props.chartData.labels ?? []
  const courseHours = props.chartData.course_hours ?? []
  const challengeHours = props.chartData.challenge_hours ?? []

  chartInstance = new Chart(ctx, {
    type: 'bar',
    data: {
      labels,
      datasets: [
        {
          label: 'Learning',
          data: courseHours,
          backgroundColor: '#22c55e',
          borderRadius: 8,
          categoryPercentage: 0.5,
          barPercentage: 0.9,
        },
        {
          label: 'Challenge',
          data: challengeHours,
          backgroundColor: '#f59e0b',
          borderRadius: 8,
          categoryPercentage: 0.5,
          barPercentage: 0.9,
        },
      ],
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: {
          display: true,
          position: 'top',
          labels: {
            usePointStyle: true,
            boxWidth: 10,
            boxHeight: 10,
          },
        },
        tooltip: {
          enabled: true,
          callbacks: {
            label(context) {
              const hours = context.raw ?? 0
              const formatted = Number(hours).toFixed(2)
              return `${formatted} Hours`
            },
          },
        },
      },
      scales: {
        x: {
          grid: {
            display: false,
          },
        },
        y: {
          beginAtZero: true,
          max: yMax,
          ticks: {
            stepSize: yMax / 4,
            callback(value) {
              return `${value}h`
            },
          },
          grid: {
            color: 'rgba(255,255,255,0.05)',
          },
        },
      },
    },
  })
}

onMounted(() => {
  createChart()
})

watch(
  () => props.chartData,
  () => {
    createChart()
  },
  { deep: true }
)

onBeforeUnmount(() => {
  if (chartInstance) {
    chartInstance.destroy()
  }
})
</script>

<template>
  <div class="dashboard_chart_container">
    <canvas ref="canvasRef"></canvas>
  </div>
</template>
