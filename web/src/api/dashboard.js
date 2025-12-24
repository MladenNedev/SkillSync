import http from './http'

// /api/dashboard/summary
export async function fetchSummary( scope = 'week') {
  const { data } = await http.get('/api/dashboard/summary', {
    params: { scope }
  })
  return data;
}

// /api/dashboard/chart
export async function fetchChart( scope = 'week') {
  const { data } = await http.get('/api/dashboard/chart', {
    params: { scope }
  })
  return data;
}

// /api/dashboard/recent-courses
export async function fetchRecentCourses() {
  const { data } = await http.get('/api/dashboard/recent-courses')
  return data;
}
