<script setup>
import { ref, onMounted, computed } from 'vue'
import { fetchSummary, fetchChart, fetchRecentCourses } from '../api/dashboard'
import DashboardChart from '@/components/DashboardChart.vue'
import placeholderImage from '@/assets/course-placeholder.png'
import NavBar from '@/components/NavBar.vue'

const summary = ref(null)
const chartData = ref(null)
const recentCourses = ref([])
const loading = ref(true)
const error = ref(null)

// --- Scope State --- //
const scope = ref('week')

const scopeLabel = computed(() => (scope.value === 'week' ? 'Weekly' : 'Monthly'))

const scopeSubtitle = computed(() =>
  scope.value === 'week'
    ? "Here's an overview of your study progress this week."
    : "Here's an overview of your study progress this month."
)

function setScope(newScope) {
  scope.value = newScope
}

function formatMinutesToHoursMinutes(totalMinutes) {
  if (totalMinutes == null) return '00 h 00 m'

  const hours = Math.floor(totalMinutes / 60)
  const minutes = totalMinutes % 60

  const paddedHours = String(hours).padStart(2, '0')
  const paddedMinutes = String(minutes).padStart(2, '0')

  return `${paddedHours} h ${paddedMinutes} m`
}

const formattedTimeSpentToday = computed(() => {
  if (!summary.value) return '00 h 00 m'

  const minutes = summary.value.stats.time_spent_today_minutes
  return formatMinutesToHoursMinutes(minutes)
})

onMounted(async () => {
  loading.value = true
  error.value = null

  try {
    const [summaryRes, chartRes, recentRes] = await Promise.all([
      fetchSummary(),
      fetchChart(),
      fetchRecentCourses(),
    ])

    summary.value = summaryRes
    recentCourses.value = recentRes.data

    console.log('Chart data from API (raw):', chartRes)

    // ----- Build fixed Monday → Sunday week -----
    const start = new Date(summaryRes.weekly.range.start) // Monday of the week
    const labels = []
    const courseHours = []
    const challengeHours = []

    const days = chartRes.days ?? [] 
    const shortNames = ['M', 'T', 'W', 'T', 'F', 'S', 'S']

    for (let i = 0; i < 7; i++) {
      const d = new Date(start)
      d.setDate(start.getDate() + i)

      labels.push(shortNames[i])

      const iso = d.toISOString().split('T')[0]
      const entry = days.find((x) => x.date === iso)

      const courseMinutes = entry?.course_minutes ?? 0 
      const challengeMinutes = entry?.challenge_minutes ?? 0

      courseHours.push(Number((courseMinutes / 60).toFixed(2)))
      challengeHours.push(Number((challengeMinutes / 60).toFixed(2)))
    }


    const allHours = [...courseHours, ...challengeHours]
    const maxHours = Math.max(...allHours, 0)

    const decimalPart = maxHours % 1

    let yMax
    if (decimalPart < 0.6) {
      yMax = Math.ceil(maxHours)
    } else {
      yMax = Math.ceil(maxHours) + 1
    }





    chartData.value = {
      labels,
      course_hours: courseHours,
      challenge_hours: challengeHours,
      yMax,
    }

    console.log('Chart data shaped for chart:', chartData.value)
  } catch (e) {
    console.error('Failed to load dashboard', e)
    error.value = 'Failed to load dashboard data. Please try again.'
  } finally {
    loading.value = false
  }
})
</script>

<template>

  <NavBar />
  <div v-if="loading" style="color: red">Loading...</div>
  <div v-else-if="error">{{ error }}</div>
  <main v-else>
    <div class="container-fluid">
      <div class="row">
        <div class="welcome-box col-10 offset-1">
          <div class="welcome-box__text">
            <h1 class="welcome-box__text-title">Welcome back, {{ summary.user.name }}</h1>
            <p class="welcome-box__text-subtitle">
              {{ scopeSubtitle }}
            </p>
          </div>
          <div class="welcome-box__scope-selector dropdown">
            <button 
              class="welcome-box__scope-selector__button dropdown-toggle"
              type="button"
              data-bs-toggle="dropdown"
              aria-expanded="false"
            > 
              {{ scopeLabel }} 
              <span class="welcome-box__scope-selector__button-chevron">
                <font-awesome-icon icon="chevron-down" />
              </span>
            </button>

            <ul class="dropdown-menu welcome-box__scope-selector__menu">
              <li>
                <button
                  class="dropdown-item welcome-box__scope-selector__menu-item"
                  :class="{ 'welcome-box__scope-selector__menu-item--active': scope === 'week' }"
                  @click="setScope('week')"
                >
                  Weekly
                </button>
              </li>
              <li>
                <button
                  class="dropdown-item welcome-box__scope-selector__menu-item"
                  :class="{ 'welcome-box__scope-selector__menu-item--active': scope === 'month' }"
                  @click="setScope('month')"
                >
                  Monthly
                </button>
              </li>
            </ul>
          </div>
        </div>
      </div>

      <div class="row justify-conent-center">
        <div class="col-10 offset-1">
          <div class="row g-4 summary-row">
            <div class="col-3">
              <article class="summary-card">
                <div class="summary-card__icon">
                  <font-awesome-icon icon="fa-solid fa-list-check" />
                </div>
                <div class="summary-card__text">
                  <h2 class="summary-card__text-title">
                    {{ summary.stats.courses_in_progress }} Courses
                  </h2>
                  <p class="summary-card__text-subtitle">In Progress</p>
                </div>
              </article>
            </div>

            <div class="col-3">
              <article class="summary-card">
                <div class="summary-card__icon">
                  <font-awesome-icon icon="fa-book-bookmark" />
                </div>
                <div class="summary-card__text">
                  <h2 class="summary-card__text-title">
                    {{ summary.stats.completed_courses }} Courses
                  </h2>
                  <p class="summary-card__text-subtitle">Completed</p>
                </div>
              </article>
            </div>

            <div class="col-3">
              <article class="summary-card">
                <div class="summary-card__icon">
                  <font-awesome-icon icon="fa-solid fa-medal" />
                </div>
                <div class="summary-card__text">
                  <h2 class="summary-card__text-title">
                    {{ summary.stats.completed_challenges }} Challenges
                  </h2>
                  <p class="summary-card__text-subtitle">Completed</p>
                </div>
              </article>
            </div>

            <div class="col-3">
              <article class="summary-card">
                <div class="summary-card__icon">
                  <font-awesome-icon icon="fa-solid fa-clock" />
                </div>
                <div class="summary-card__text">
                  <h2 class="summary-card__text-title">{{ formattedTimeSpentToday }}</h2>
                  <p class="summary-card__text-subtitle">Time Spent</p>
                </div>
              </article>
            </div>
          </div>
        </div>
      </div>

      <div class="row">
        <div class="chart-box col-5 offset-1">
          <div class="chart-box__header">
            <h1 class="chart-box__header-title">Spent Hours</h1>

            <div class="chart-box__legend">
              <button class="legend-btn legend-btn--learning">
                <span class="legend-color legend-color--learning"></span>
                Learning
              </button>

              <button class="legend-btn legend-btn--challenge">
                <span class="legend-color legend-color--challenge"></span>
                Challenge
              </button>
            </div>
          </div>

          <div class="chart-box__content">
            <div class="chart-box__content-chart">
              <DashboardChart :chart-data="chartData" />
            </div>

            <aside class="weekly-metrics">
              <div class="kpi-item">
                <span class="kpi-label">Total hours this week</span>
                <strong class="kpi-value">{{ summary.weekly.total_hours.toFixed(1) }}</strong>
              </div>

              <div class="kpi-item">
                <span class="kpi-label">Average per day</span>
                <strong class="kpi-value">{{ summary.weekly.average_hours_per_day.toFixed(2) }}</strong>
              </div>

              <div class="kpi-item">
                <span class="kpi-label">Course hours</span>
                <strong class="kpi-value">{{ summary.weekly.course_hours.toFixed(1) }}</strong>
              </div>

              <div class="kpi-item">
                <span class="kpi-label">Challenge hours</span>
                <strong class="kpi-value">{{ summary.weekly.challenge_hours.toFixed(1) }}</strong>
              </div>
            </aside>
          </div>
        </div>

        <div class="recent-courses-box col-5">
          <section>
            <header class="recent-courses-box__header">
              <h2 class="recent-courses-box__header-title">Recent courses</h2>
              <button class="recent-courses-box__header-button">View all courses</button>
            </header>

            <div class="recent-courses-box_items">
              <div v-for="course in recentCourses" :key="course.id" class="course-item">
                <img :src="course.image_url ?? placeholderImage" class="course-item__image" />
                <div class="course-item__content">
                  <div class="course-item__content-header">
                    <h3 class="course-item__content-header-title">{{ course.title }}</h3>
                    <p class="course-item__content-header-author">{{ course.author }}</p>
                  </div>

                  <div class="course-item__content-footer">
                    <p class="course-item__content-footer-progress">Progress</p>
                  </div>
                </div>
              </div>
            </div>
          </section>
        </div>
      </div>
    </div>
  </main>
</template>
