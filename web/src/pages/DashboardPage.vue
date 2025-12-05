<script setup>
defineOptions({ name: 'DashboardChart' })

import { ref, onMounted, onBeforeUnmount, watch } from 'vue'
import { useBarChart } from '@/composables/useBarChart.js'

const props = defineProps({
  chartData: { type: Object, required: true },
})

const canvasRef = ref(null)
const { createChart, destroyChart } = useBarChart()

onMounted(() => {
  createChart(canvasRef.value, props.chartData)
})

watch(
  () => props.chartData,
  newVal => {
    createChart(canvasRef.value, newVal)
  },
  { deep: true }
)

onBeforeUnmount(() => destroyChart())
</script>

<template>
  <div class="chart-box__content-chart-container">
    <canvas ref="canvasRef"></canvas>
  </div>
</template>