import http from './http';

// /api/dashboard/summary
export async function fetchSummary() {
    const { data } = await http.get('/api/dashboard/summary');
    return data;
}

// /api/dashboard/chart
export async function fetchChart() {
    const { data } = await http.get('/api/dashboard/chart');
    return data;
}

// /api/dashboard/recent-courses
export async function fetchRecentCourses() {
    const { data } = await http.get('/api/dashboard/recent-courses');
    return data;
}
