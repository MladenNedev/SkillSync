<script setup>
  import { ref, onMounted, computed } from 'vue';
  import { fetchSummary, fetchChart, fetchRecentCourses } from '../api/dashboard';
  import DashboardChart from '../components/DashboardChart.vue';
  import placeholderImage from '../assets/course-placeholder.png';

  const summary = ref(null);
  const chartData = ref(null);
  const recentCourses = ref([]);
  const loading = ref(true);
  const error = ref(null);

  function formatMinutesToHoursMinutes(totalMinutes) {
    if (totalMinutes == null) return '00 h 00 m';

    const hours = Math.floor(totalMinutes / 60);
    const minutes = totalMinutes % 60;

    const paddedHours = String(hours).padStart(2, '0');
    const paddedMinutes = String(minutes).padStart(2, '0');

    return `${paddedHours} h ${paddedMinutes} m`;
  }

  const formattedTimeSpentToday = computed(() => {
    if (!summary.value) return '00 h 00 m';

    const minutes = summary.value.stats.time_spent_today_minutes;
    return formatMinutesToHoursMinutes(minutes);
  });

  onMounted(async () => {
    loading.value = true;
    error.value = null;

    try {
      const [summaryRes, chartRes, recentRes] = await Promise.all([
        fetchSummary(),
        fetchChart(),
        fetchRecentCourses(),
      ]);

      summary.value = summaryRes;
      recentCourses.value = recentRes.data;

      console.log('Chart data from API (raw):', chartRes);

      // ----- Build fixed Monday → Sunday week -----
      const start = new Date(summaryRes.weekly.range.start); // Monday of the week
      const labels = [];
      const courseHours = [];
      const challengeHours = [];
      const allHours = [...courseHours, ...challengeHours];
      const maxHours = Math.max(...allHours, 0);

      let yMax;
      if (maxHours <= 1.5) {
        yMax = 2;
      } else if (maxHours <= 3) {
        yMax = 4;
      } else if (maxHours <= 5) {
        yMax = 6;
      } else {
        yMax = 8;
      }

      const days = chartRes.days ?? []; // adjust if your key is different

      const shortNames = ['M', 'T', 'W', 'T', 'F', 'S', 'S'];

      for (let i = 0; i < 7; i++) {
        const d = new Date(start);
        d.setDate(start.getDate() + i);

        labels.push(shortNames[i]);

        const iso = d.toISOString().split('T')[0]; // <-- lowercase split
        const entry = days.find((x) => x.date === iso);

        const courseMinutes = entry?.course_minutes ?? 0;      // adjust field names
        const challengeMinutes = entry?.challenge_minutes ?? 0;

        courseHours.push(Number((courseMinutes / 60).toFixed(2)));
        challengeHours.push(Number((challengeMinutes / 60).toFixed(2)));
      }

      chartData.value = {
        labels,
        course_hours: courseHours,
        challenge_hours: challengeHours,
        yMax,
      };

      console.log('Chart data shaped for chart:', chartData.value);
    } catch (e) {
      console.error('Failed to load dashboard', e);
      error.value = 'Failed to load dashboard data. Please try again.';
    } finally {
      loading.value = false;
    }
  });
</script>

<template>

  <div v-if="loading" style="color: red;">Loading...</div>
  <div v-else-if="error">{{ error }}</div>

  <main v-else>   
    <div class="container-fluid">

        <div class="row">
          <div class="welcome-box col-10 offset-1">
            <div class="welcome-box__text">
              <h1>Welcome back, {{ summary.user.name }} </h1>
              <p>Here's an overview of your study progress this week.</p>
            </div>
            <div class="welcome-box__scope-selector">
              <button>Weekly</button>
            </div>
          </div>
        </div>

        <div class="row justify-conent-center">
          <div class="col-10 offset-1">
            <div class="row g-4 summary-row">

              <div class="col-3">
                <article class="summary-card">
                  <div class="summary-card__icon">
                    <font-awesome-icon icon="fa-solid fa-list-check"/>
                  </div>
                  <div class="summary-card__text">
                    <h2> {{ summary.stats.courses_in_progress }} Courses </h2>
                    <p>In Progress</p>
                  </div>
                </article>
              </div>

              <div class="col-3">
                <article class="summary-card">
                  <div class="summary-card__icon">
                    <font-awesome-icon icon="fa-book-bookmark"/>
                  </div>
                  <div class="summary-card__text">
                    <h2> {{ summary.stats.completed_courses }} Courses </h2>
                    <p>Completed</p>
                  </div>
                </article>
              </div>

              <div class="col-3">
                <article class="summary-card">
                  <div class="summary-card__icon">
                    <font-awesome-icon icon="fa-solid fa-medal" />
                  </div>
                  <div class="summary-card__text">
                    <h2> {{ summary.stats.completed_challenges}} Challenges </h2>
                    <p>Completed</p>
                  </div>
                </article>
              </div>

              <div class="col-3">
                <article class="summary-card">
                  <div class="summary-card__icon">
                    <font-awesome-icon icon="fa-solid fa-clock" />
                  </div>
                  <div class="summary-card__text">
                    <h2> {{ formattedTimeSpentToday }}</h2>
                    <p>Time Spent</p>
                  </div>
                </article>
              </div>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="chart-box col-5 offset-1">

            <div class="chart-box_heading">
              <h1></h1>
              <div class="chart_legend"></div>
            </div>

            <div class="chart-box_content">
              <div class="chart">
                <DashboardChart :chart-data="chartData" />
              </div>
              

              <aside class="weekly-metrics">
                <div class="kpi-item">
                  <span>Total hours this week</span>
                  <strong>{{ summary.weekly.total_hours.toFixed(1) }}</strong>
                </div>
                <div class="kpi-item">
                  <span>Average hours per day</span>
                  <strong>{{ summary.weekly.average_hours_per_day.toFixed(2) }}</strong>
                </div>
                <div class="kpi-item">
                  <span>Course hours this week</span>
                  <strong>{{ summary.weekly.course_hours.toFixed(1) }}</strong>
                </div>
                <div class="kpi-item">
                  <span>Challenge hours this week</span>
                  <strong>{{ summary.weekly.challenge_hours.toFixed(1) }}</strong>
                </div>
              </aside>
            </div>

            
          </div>

          <div class="recent-courses-box col-5">
            <section>
              <header class="recent-courses-box_header">
                <h2>Recent courses</h2>
                <button>View all courses</button>
              </header>

              <div class="recent-courses-box_items">
                <div v-for="course in recentCourses" :key="course.id" class="course-item">
                  <img :src="course.image_url ?? placeholderImage" class="course-item_image"/>
                  <div>
                    <h3>{{ course.title }}</h3>
                    <p>{{ course.author }}</p>
                    <!-- you can add a proper progress bar later -->
                    <p>Progress: {{ course.progress_percent }}%</p>
                  </div>
                </div>
              </div>
            </section>
          </div>
        </div>
    </div>
  </main>
</template> 